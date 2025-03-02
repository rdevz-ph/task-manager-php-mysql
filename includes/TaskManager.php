<?php
require_once 'Database.php';

class TaskManager
{
    private $db;

    public function __construct()
    {
        if (!file_exists('config.php')) {
            // Don't throw an error here. Just return and let setupDatabase() handle it.
            return;
        }

        require 'config.php';
        $database = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS);
        $this->db = $database->getConnection();
    }

    public function setupDatabase($host, $name, $user, $pass)
    {
        try {
            // Create database
            $tempDb = new PDO("mysql:host=$host", $user, $pass);
            $tempDb->exec("CREATE DATABASE IF NOT EXISTS `$name`");

            // Create tables
            $tempDb->exec("USE `$name`");
            $tempDb->exec("CREATE TABLE IF NOT EXISTS tasks (
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");

            // Create config file
            $configContent = "<?php
define('DB_HOST', '$host');
define('DB_NAME', '$name');
define('DB_USER', '$user');
define('DB_PASS', '$pass');
";
            file_put_contents('config.php', $configContent);

            // Add sample task
            $this->db = $tempDb;
            $this->createTask('First Task');

            return true;
        } catch (PDOException $e) {
            throw new Exception("Setup failed: " . $e->getMessage());
        }
    }

    public function getTasks()
    {
        $stmt = $this->db->query("SELECT * FROM tasks ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createTask($title)
    {
        $stmt = $this->db->prepare("INSERT INTO tasks (title) VALUES (?)");
        $stmt->execute([$title]);
        return $this->db->lastInsertId();
    }

    public function updateTask($id, $title)
    {
        $stmt = $this->db->prepare("UPDATE tasks SET title = ? WHERE id = ?");
        return $stmt->execute([$title, $id]);
    }

    public function deleteTask($id)
    {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = ?");
        return $stmt->execute([$id]);
    }
}