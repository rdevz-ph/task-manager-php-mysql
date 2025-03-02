<?php

header('Content-Type: application/json');
require_once __DIR__ . '/includes/TaskManager.php';

try {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $action = $_GET['action'] ?? ($data['action'] ?? ($_POST['action'] ?? ''));

    switch ($action) {
        case 'setup':
            $tm = new TaskManager();
            $tm->setupDatabase(
                $data['host'],
                $data['name'],
                $data['user'],
                $data['pass']
            );
            echo json_encode(['success' => true]);
            break;

        case 'get_tasks':
            $tm = new TaskManager();
            echo json_encode($tm->getTasks());
            break;

        case 'create_task':
            $tm = new TaskManager();
            $id = $tm->createTask($data['title']);
            echo json_encode(['success' => true, 'id' => $id]);
            break;

        case 'update_task':
            $tm = new TaskManager();
            $tm->updateTask($data['id'], $data['title']);
            echo json_encode(['success' => true]);
            break;

        case 'delete_task':
            $tm = new TaskManager();
            $tm->deleteTask($data['id']);
            echo json_encode(['success' => true]);
            break;

        default:
            throw new Exception('Invalid action');
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}