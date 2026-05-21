<?php
require_once __DIR__ . '/../src/AppointmentRepository.php';
require_once __DIR__ . '/../src/Validator.php';

class AppointmentController extends BaseController {
    private $repo;

    public function __construct($pdo) {
        parent::__construct($pdo);
        $this->repo = new AppointmentRepository($pdo);
    }

    public function index() {
        $filters = array(
            'date_from'      => isset($_GET['date_from']) ? $_GET['date_from'] : '',
            'date_to'        => isset($_GET['date_to']) ? $_GET['date_to'] : '',
            'teacher_id'     => isset($_GET['teacher_id']) ? $_GET['teacher_id'] : '',
            'status'         => isset($_GET['status']) ? $_GET['status'] : '',
            'student_search' => isset($_GET['student_search']) ? $_GET['student_search'] : ''
        );

        $appointments = $this->repo->findAllWithFilters($filters);
        $this->render('appointment/list', array(
            'appointments' => $appointments,
            'filters' => $filters
        ));
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Validator::checkCsrfToken(isset($_POST['csrf_token']) ? $_POST['csrf_token'] : '')) {
                Validator::setFlash('error', 'Ошибка безопасности (CSRF)');
                $this->redirect('index.php', array('entity' => 'appointment', 'action' => 'create'));
            }

            $errors = Validator::validateAppointment($_POST);
            if (!empty($errors)) {
                $this->render('appointment/create', array('errors' => $errors, 'old' => $_POST));
                return;
            }

            try {
                $this->repo->createAppointment($_POST);
                Validator::setFlash('success', 'Запись успешно создана');
                $this->redirect('index.php', array('entity' => 'appointment'));
            } catch (Exception $e) {
                Validator::setFlash('error', $e->getMessage());
                $this->render('appointment/create', array('old' => $_POST));
            }
        } else {
            require_once __DIR__ . '/../src/StudentRepository.php';
            require_once __DIR__ . '/../src/TeacherRepository.php';
            require_once __DIR__ . '/../src/InstrumentRepository.php';

            $students    = (new StudentRepository($this->pdo))->findAll();
            $teachers    = (new TeacherRepository($this->pdo))->findAll();
            $instruments = (new InstrumentRepository($this->pdo))->findAll();

            $this->render('appointment/create', array(
                'students'    => $students,
                'teachers'    => $teachers,
                'instruments' => $instruments,
                'csrf'        => Validator::generateCsrfToken()
            ));
        }
    }

    public function view($id) {
        $all = $this->repo->findAllWithFilters(array());
        $appointment = null;
        foreach ($all as $a) {
            if ($a['lesson_id'] == $id) {
                $appointment = $a;
                break;
            }
        }
        if (!$appointment) {
            Validator::setFlash('error', 'Запись не найдена');
            $this->redirect('index.php', array('entity' => 'appointment'));
        }
        $this->render('appointment/view', array('appointment' => $appointment));
    }

    public function changeStatus() {
        // Работаем только с POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?entity=appointment');
            exit;
        }

        $id     = isset($_POST['appointment_id']) ? (int)$_POST['appointment_id'] : 0;
        $status = isset($_POST['status']) ? $_POST['status'] : '';

        if ($id > 0 && !empty($status)) {
            try {
                $this->repo->changeStatus($id, $status);
                Validator::setFlash('success', 'Статус изменён на: ' . $status);
            } catch (Exception $e) {
                Validator::setFlash('error', $e->getMessage());
            }
        }

        //  Чистый редирект без фильтров
        header('Location: /index.php?entity=appointment');
        exit;
    }
}