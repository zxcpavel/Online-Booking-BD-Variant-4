<?php
require_once __DIR__ . '/../src/StudentRepository.php';
require_once __DIR__ . '/../src/Validator.php';

class StudentController extends BaseController {
    private $repo;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->repo = new StudentRepository($pdo);
    }

    public function index() {
        $search = $_GET['search'] ?? '';
        $students = $this->repo->findAll($search);
        $this->render('student/list', [
            'students' => $students,
            'search' => $search
        ]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Validator::checkCsrfToken($_POST['csrf_token'] ?? '')) {
                Validator::setFlash('error', 'Ошибка безопасности (CSRF)');
                $this->redirect('index.php', ['entity' => 'student', 'action' => 'create']);
            }

            $errors = Validator::validateStudent($_POST);
            if ($errors) {
                $this->render('student/create', ['errors' => $errors, 'old' => $_POST]);
                return;
            }

            try {
                $this->repo->create($_POST);
                Validator::setFlash('success', 'Ученик добавлен');
                $this->redirect('index.php', ['entity' => 'student']);
            } catch (Exception $e) {
                Validator::setFlash('error', $e->getMessage());
                $this->render('student/create', ['old' => $_POST]);
            }
        } else {
            $this->render('student/create', ['csrf' => Validator::generateCsrfToken()]);
        }
    }

    public function edit($id) {
        $student = $this->repo->findById($id);
        if (!$student) {
            Validator::setFlash('error', 'Ученик не найден');
            $this->redirect('index.php', ['entity' => 'student']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Validator::checkCsrfToken($_POST['csrf_token'] ?? '')) {
                Validator::setFlash('error', 'Ошибка безопасности (CSRF)');
                $this->redirect('index.php', ['entity' => 'student', 'action' => 'edit', 'id' => $id]);
            }

            $errors = Validator::validateStudent($_POST, true);
            if ($errors) {
                $this->render('student/edit', ['errors' => $errors, 'old' => $_POST, 'student' => $student]);
                return;
            }

            try {
                $this->repo->update($id, $_POST);
                Validator::setFlash('success', 'Данные обновлены');
                $this->redirect('index.php', ['entity' => 'student']);
            } catch (Exception $e) {
                Validator::setFlash('error', $e->getMessage());
                $this->render('student/edit', ['old' => $_POST, 'student' => $student]);
            }
        } else {
            $this->render('student/edit', [
                'student' => $student,
                'csrf' => Validator::generateCsrfToken()
            ]);
        }
    }

    public function delete($id) {
        $student = $this->repo->findById($id);
        if (!$student) {
            Validator::setFlash('error', 'Ученик не найден');
            $this->redirect('index.php', ['entity' => 'student']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Validator::checkCsrfToken($_POST['csrf_token'] ?? '')) {
                Validator::setFlash('error', 'Ошибка безопасности');
                $this->redirect('index.php', ['entity' => 'student']);
            }

            if ($this->repo->hasRelatedLessons($id)) {
                Validator::setFlash('error', 'Нельзя удалить: у ученика есть записанные уроки');
                $this->redirect('index.php', ['entity' => 'student']);
            }

            $this->repo->delete($id);
            Validator::setFlash('success', 'Ученик удалён');
            $this->redirect('index.php', ['entity' => 'student']);
        } else {
            $this->render('student/delete', [
                'student' => $student,
                'csrf' => Validator::generateCsrfToken()
            ]);
        }
    }

    public function view($id) {
        $student = $this->repo->findById($id);
        if (!$student) {
            Validator::setFlash('error', 'Ученик не найден');
            $this->redirect('index.php', ['entity' => 'student']);
        }
        $this->render('student/view', ['student' => $student]);
    }
}