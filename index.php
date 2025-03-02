<?php

require 'init.php';
require 'header.php';
?>

<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto p-4 max-w-2xl">
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h1 class="text-3xl font-bold mb-6 text-gray-800">Task Manager</h1>

            <!-- Add Task Form -->
            <form id="createTaskForm" class="mb-6">
                <div class="flex gap-2">
                    <input type="text" name="title" placeholder="New task..." required
                        class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit"
                        class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        Add Task
                    </button>
                </div>
            </form>

            <!-- Tasks List -->
            <div id="tasksContainer" class="space-y-2">
                <!-- Tasks will be loaded here via AJAX -->
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">Edit Task</h2>
            <form id="editTaskForm">
                <input type="hidden" name="id" id="editTaskId">
                <div class="mb-4">
                    <input type="text" name="title" id="editTaskTitle" required
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 text-gray-500 hover:text-gray-600">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Load tasks on page load
        document.addEventListener('DOMContentLoaded', fetchTasks);

        async function fetchTasks() {
            try {
                const response = await fetch('ajax-handler.php?action=get_tasks');
                const text = await response.text(); // Get raw response

                console.log('Raw response:', text); // Log response for debugging

                const tasks = JSON.parse(text);
                console.log(tasks);

                renderTasks(tasks);
            } catch (error) {
                console.error('Error:', error);
            }
        }

        function renderTasks(tasks) {
            const container = document.getElementById('tasksContainer');
            container.innerHTML = tasks.map(task => `
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg group">
                    <span class="text-gray-700">${escapeHtml(task.title)}</span>
                    <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <small class="text-gray-500">
                            ${new Date(task.created_at).toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            })}
                        </small>
                        <button 
                            onclick="openEditModal(${task.id}, '${escapeHtml(task.title)}')"
                            class="text-blue-500 hover:text-blue-600">
                            Edit
                        </button>
                        <button 
                            onclick="deleteTask(${task.id})"
                            class="text-red-500 hover:text-red-600">
                            Delete
                        </button>
                    </div>
                </div>
            `).join('');
        }

        // Form handlers
        document.getElementById('createTaskForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);

            try {
                const response = await fetch('ajax-handler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'create_task',
                        title: formData.get('title')
                    })
                });

                const result = await response.json();
                if (result.success) {
                    e.target.reset();
                    fetchTasks();
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });

        // Edit modal functions
        function openEditModal(id, title) {
            document.getElementById('editTaskId').value = id;
            document.getElementById('editTaskTitle').value = title;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        document.getElementById('editTaskForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);

            try {
                const response = await fetch('ajax-handler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'update_task',
                        id: formData.get('id'),
                        title: formData.get('title')
                    })
                });

                const result = await response.json();
                if (result.success) {
                    closeEditModal();
                    fetchTasks();
                }
            } catch (error) {
                console.error('Error:', error);
            }
        });

        async function deleteTask(id) {
            if (confirm('Are you sure you want to delete this task?')) {
                try {
                    const response = await fetch('ajax-handler.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            action: 'delete_task',
                            id: id
                        })
                    });

                    const result = await response.json();
                    if (result.success) {
                        fetchTasks();
                    }
                } catch (error) {
                    console.error('Error:', error);
                }
            }
        }

        function escapeHtml(unsafe) {
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }
    </script>
</body>

</html>