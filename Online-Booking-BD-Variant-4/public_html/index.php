<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/src/Database.php';
$pdo = Database::getConnection();

// 🔥 Читаем entity и action из POST (если форма) или GET (если ссылка)
$entity = isset($_POST['entity']) ? $_POST['entity'] : (isset($_GET['entity']) ? $_GET['entity'] : 'student');
$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : 'index');
$id     = isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : null);

$controllers = array(
    'student'      => 'StudentController',
    'teacher'      => 'TeacherController',
    'instrument'   => 'InstrumentController',
    'appointment'  => 'AppointmentController'
);

if (!isset($controllers[$entity])) {
    die("Сущность '$entity' не найдена. Доступные: " . implode(', ', array_keys($controllers)));
}

require_once __DIR__ . '/controllers/BaseController.php';
require_once __DIR__ . '/controllers/' . $controllers[$entity] . '.php';

$controllerClass = $controllers[$entity];
$controller = new $controllerClass($pdo);

switch ($action) {
    case 'list':
    case 'index':
        $controller->index();
        break;
    case 'create':
        $controller->create();
        break;
    case 'edit':
        $controller->edit($id);
        break;
    case 'delete':
        $controller->delete($id);
        break;
    case 'view':
        $controller->view($id);
        break;
    case 'change_status':
        $controller->changeStatus();
        break;
    default:
        $controller->index();
}