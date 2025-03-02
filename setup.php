<?php
if (file_exists('config.php')) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Database Setup</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h1 class="text-2xl font-bold mb-6 text-center">Database Setup</h1>
        <form id="setupForm">
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Host</label>
                <input type="text" name="host" value="localhost" required class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Database Name</label>
                <input type="text" name="name" required class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Username</label>
                <input type="text" name="user" required class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 mb-2">Password</label>
                <input type="password" name="pass" class="w-full px-3 py-2 border rounded-lg">
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">
                Complete Setup
            </button>
        </form>
        <div id="message" class="mt-4 hidden"></div>
    </div>

    <script>
        document.getElementById('setupForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const message = document.getElementById('message');

            try {
                const response = await fetch('ajax-handler.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        action: 'setup',
                        ...Object.fromEntries(formData)
                    })
                });

                const result = await response.json();
                message.classList.remove('hidden');

                if (result.success) {
                    message.classList.add('text-green-600');
                    message.textContent = 'Setup completed successfully! Redirecting...';
                    setTimeout(() => window.location.href = 'index.php', 2000);
                } else {
                    message.classList.add('text-red-600');
                    message.textContent = result.error || 'Setup failed';
                }
            } catch (error) {
                console.error('Error:', error);
                message.classList.remove('hidden');
                message.classList.add('text-red-600');
                message.textContent = 'An error occurred during setup';
            }
        });
    </script>
</body>

</html>