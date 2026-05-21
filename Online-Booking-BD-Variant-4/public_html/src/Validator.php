<?php
class Validator {
    // Генерация CSRF токена
    public static function generateCsrfToken() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    // Проверка CSRF токена
    public static function checkCsrfToken($token) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

    // Flash-сообщения (успех/ошибка)
    public static function setFlash($type, $message) {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    public static function getFlash() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }

    // Валидация ученика
    public static function validateStudent($data, $isUpdate = false) {
        $errors = [];
        if (empty(trim($data['last_name'] ?? ''))) $errors['last_name'] = 'Фамилия обязательна';
        if (empty(trim($data['first_name'] ?? ''))) $errors['first_name'] = 'Имя обязательно';
        if (!preg_match('/^\+?7?\d{10,11}$/', preg_replace('/\D/', '', $data['phone'] ?? ''))) {
            $errors['phone'] = 'Телефон в формате +7...';
        }
        if (!filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Некорректный email';
        }
        if (!empty($data['birth_date']) && $data['birth_date'] > date('Y-m-d')) {
            $errors['birth_date'] = 'Дата не может быть в будущем';
        }
        return $errors;
    }

    // Валидация преподавателя
    public static function validateTeacher($data, $isUpdate = false) {
        $errors = [];
        if (empty(trim($data['last_name'] ?? ''))) $errors['last_name'] = 'Фамилия обязательна';
        if (empty(trim($data['first_name'] ?? ''))) $errors['first_name'] = 'Имя обязательно';
        if (!filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Некорректный email';
        return $errors;
    }

    // Валидация инструмента
    public static function validateInstrument($data, $isUpdate = false) {
        $errors = [];
        if (empty(trim($data['instrument_name'] ?? ''))) $errors['instrument_name'] = 'Название обязательно';
        return $errors;
    }

    // Валидация записи на урок
    public static function validateAppointment($data) {
        $errors = [];
        if (empty($data['student_id'])) $errors['student_id'] = 'Выберите ученика';
        if (empty($data['teacher_id'])) $errors['teacher_id'] = 'Выберите преподавателя';
        if (empty($data['instrument_id'])) $errors['instrument_id'] = 'Выберите инструмент';
        if (empty($data['lesson_datetime'])) $errors['lesson_datetime'] = 'Укажите дату и время';
        else if (strtotime($data['lesson_datetime']) < time()) $errors['lesson_datetime'] = 'Нельзя записаться в прошлое';
        return $errors;
    }
}