# Task Manager

A simple Task Manager web application built with PHP, JavaScript (AJAX), and Tailwind CSS. Users can create, update, and delete tasks dynamically.

## Features
- Add new tasks
- View all tasks (fetched dynamically via AJAX)
- Edit existing tasks
- Delete tasks

## Installation
### Prerequisites
Ensure you have the following installed on your system:
- PHP (>=7.4)
- MySQL
- Apache or any local server (XAMPP, WAMP, etc.)

### Setup

1. Clone this repository and move it to the XAMPP `htdocs` directory:

   ```sh
   git clone https://github.com/rdevz-ph/task-manager-php-mysql.git
   ```

2. Set up the database by running the `setup.php` in your browser:

   ```sh
   http://localhost/task-manager-php-mysql/setup.php
   ```

   This will create the necessary tables for the application.

3. Start Apache and MySQL in XAMPP Control Panel.

4. Open your browser and visit:

   ```
   http://localhost/task-manager-php-mysql
   ```

## File Structure
- `index.php` - Main UI for managing tasks
- `init.php` - Database configuration checker
- `setup.php` - Creates the database config (initial configuration)
- `ajax-handler.php` - Handles AJAX requests for CRUD operations
- and many more.

## Usage
- Enter a task name and click 'Add Task' to create a new task.
- Click 'Edit' to modify an existing task.
- Click 'Delete' to remove a task.

## Code Structure
This project follows Object-Oriented Programming (OOP) principles to maintain clean and modular code. The business logic is encapsulated in classes, making the code more readable and reusable.

## Dependencies
- Tailwind CSS
- Vanilla JavaScript for AJAX

## License
This project is open-source and available under the [MIT License](LICENSE).

