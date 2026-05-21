<?php
require_once __DIR__ . '/../src/InstrumentRepository.php';
require_once __DIR__ . '/../src/Validator.php';

class InstrumentController extends BaseController {
    private $repo;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->repo = new InstrumentRepository($pdo);
    }

    public function index() {
        $search = $_GET['search'] ?? '';
        $instruments = $this->repo->findAll($search);
        $this->render('instrument/list', [
            'instruments' => $instruments,
            'search' => $search
        ]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Validator::checkCsrfToken($_POST['csrf_token'] ?? '')) {
                Validator::setFlash('error', 'Ошибка безопасности (CSRF)');
                $this->redirect('index.php', ['entity' => 'instrument', 'action' => 'create']);
            }

            $errors = Validator::validateInstrument($_POST);
            if ($errors) {
                $this->render('instrument/create', ['errors' => $errors, 'old' => $_POST]);
                return;
            }

            try {
                $this->repo->create($_POST);
                Validator::setFlash('success', 'Инструмент добавлен');
                $this->redirect('index.php', ['entity' => 'instrument']);
            } catch (Exception $e) {
                Validator::setFlash('error', $e->getMessage());
                $this->render('instrument/create', ['old' => $_POST]);
            }
        } else {
            $this->render('instrument/create', ['csrf' => Validator::generateCsrfToken()]);
        }
    }

    public function edit($id) {
        $instrument = $this->repo->findById($id);
        if (!$instrument) {
            Validator::setFlash('error', 'Инструмент не найден');
            $this->redirect('index.php', ['entity' => 'instrument']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Validator::checkCsrfToken($_POST['csrf_token'] ?? '')) {
                Validator::setFlash('error', 'Ошибка безопасности (CSRF)');
                $this->redirect('index.php', ['entity' => 'instrument', 'action' => 'edit', 'id' => $id]);
            }

            $errors = Validator::validateInstrument($_POST, true);
            if ($errors) {
                $this->render('instrument/edit', ['errors' => $errors, 'old' => $_POST, 'instrument' => $instrument]);
                return;
            }

            try {
                $this->repo->update($id, $_POST);
                Validator::setFlash('success', 'Данные обновлены');
                $this->redirect('index.php', ['entity' => 'instrument']);
            } catch (Exception $e) {
                Validator::setFlash('error', $e->getMessage());
                $this->render('instrument/edit', ['old' => $_POST, 'instrument' => $instrument]);
            }
        } else {
            $this->render('instrument/edit', [
                'instrument' => $instrument,
                'csrf' => Validator::generateCsrfToken()
            ]);
        }
    }

    public function delete($id) {
        $instrument = $this->repo->findById($id);
        if (!$instrument) {
            Validator::setFlash('error', 'Инструмент не найден');
            $this->redirect('index.php', ['entity' => 'instrument']);
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Validator::checkCsrfToken($_POST['csrf_token'] ?? '')) {
                Validator::setFlash('error', 'Ошибка безопасности');
                $this->redirect('index.php', ['entity' => 'instrument']);
            }

            if ($this->repo->hasRelatedLessons($id)) {
                Validator::setFlash('error', 'Нельзя удалить: с инструментом связаны уроки');
                $this->redirect('index.php', ['entity' => 'instrument']);
            }

            $this->repo->delete($id);
            Validator::setFlash('success', 'Инструмент удалён');
            $this->redirect('index.php', ['entity' => 'instrument']);
        } else {
            $this->render('instrument/delete', [
                'instrument' => $instrument,
                'csrf' => Validator::generateCsrfToken()
            ]);
        }
    }

    public function view($id) {
        $instrument = $this->repo->findById($id);
        if (!$instrument) {
            Validator::setFlash('error', 'Инструмент не найден');
            $this->redirect('index.php', ['entity' => 'instrument']);
        }
        $this->render('instrument/view', ['instrument' => $instrument]);
    }
}