<?php
require_once __DIR__ . '/../src/TeacherRepository.php';
require_once __DIR__ . '/../src/Validator.php';

class TeacherController extends BaseController {
    private $repo;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->repo = new TeacherRepository($pdo);
    }

    public function index() {
        $search = $_GET['search'] ?? '';
        $teachers = $this->repo->findAll($search);
        $this->render('teacher/list', [
            'teachers' => $teachers,
            'search' => $search
        ]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Validator::checkCsrfToken($_POST['csrf_token'] ?? '')) {
                Validator::setFlash('error', 'Ошибка безопасности (CSRF)');
                $this->redirect('index.php', ['entity' => 'teacher', 'action' => 'create']);
            }

            $errors = Validator::validateTeacher($_POST);
            if ($errors) {
                $this->render('teacher/create', ['errors' => $errors, 'old' => $_POST]);
                return;
            }

            try {
                $this->repo->create($_POST);
                Validator::setFlash('success', 'Преподаватель добавлен');
                $this->redirect('index.php', ['entity' => 'teacher']);
            } catch (Exception $e) {
                Validator::setFlash('error', $e->getMessage());
                $this->render('teacher/create', ['old' => $_POST]);
            }
        } else {
            $this->render('teacher/create', ['csrf' => Validator::generateCsrfToken()]);
        }
    }

    public function edit($id) {
        $teacher = $this->repo->findById($id);
        if (!$teacher) {
            Validator::setFlash('error', 'Преподаватель не найден');
            $this->redirect('index.php', ['entity' => 'teacher']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Validator::checkCsrfToken($_POST['csrf_token'] ?? '')) {
                Validator::setFlash('error', 'Ошибка безопасности (CSRF)');
                $this->redirect('index.php', ['entity' => 'teacher', 'action' => 'edit', 'id' => $id]);
            }

            $errors = Validator::validateTeacher($_POST, true);
            if ($errors) {
                $this->render('teacher/edit', ['errors' => $errors, 'old' => $_POST, 'teacher' => $teacher]);
                return;
            }

            try {
                $this->repo->update($id, $_POST);
                Validator::setFlash('success', 'Данные обновлены');
                $this->redirect('index.php', ['entity' => 'teacher']);
            } catch (Exception $e) {
                Validator::setFlash('error', $e->getMessage());
                $this->render('teacher/edit', ['old' => $_POST, 'teacher' => $teacher]);
            }
        } else {
            $this->render('teacher/edit', [
                'teacher' => $teacher,
                'csrf' => Validator::generateCsrfToken()
            ]);
        }
    }

    public function delete($id) {
        $teacher = $this->repo->findById($id);
        if (!$teacher) {
            Validator::setFlash('error', 'Преподаватель не найден');
            $this->redirect('index.php', ['entity' => 'teacher']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Validator::checkCsrfToken($_POST['csrf_token'] ?? '')) {
                Validator::setFlash('error', 'Ошибка безопасности');
                $this->redirect('index.php', ['entity' => 'teacher']);
            }

            if ($this->repo->hasRelatedLessons($id)) {
                Validator::setFlash('error', 'Нельзя удалить: у преподавателя есть уроки');
                $this->redirect('index.php', ['entity' => 'teacher']);
            }

            $this->repo->delete($id);
            Validator::setFlash('success', 'Преподаватель удалён');
            $this->redirect('index.php', ['entity' => 'teacher']);
        } else {
            $this->render('teacher/delete', [
                'teacher' => $teacher,
                'csrf' => Validator::generateCsrfToken()
            ]);
        }
    }

    public function view($id) {
        $teacher = $this->repo->findById($id);
        if (!$teacher) {
            Validator::setFlash('error', 'Преподаватель не найден');
            $this->redirect('index.php', ['entity' => 'teacher']);
        }
        $this->render('teacher/view', ['teacher' => $teacher]);
    }
}