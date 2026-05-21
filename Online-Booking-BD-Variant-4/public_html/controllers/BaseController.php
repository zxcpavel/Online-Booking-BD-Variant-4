<?php
class BaseController {
    protected $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    protected function redirect($url, $params = []) {
        if ($params) {
            $url .= '?' . http_build_query($params);
        }
        header("Location: $url");
        exit;
    }

   protected function render($view, $data = []) {
    extract($data, EXTR_SKIP);
    $__content_file = __DIR__ . '/../views/' . $view . '.php';
    require __DIR__ . '/../views/layout.php';
}

    protected function renderPartial($view, $data = []) {
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../views/' . $view . '.php';
    }
}