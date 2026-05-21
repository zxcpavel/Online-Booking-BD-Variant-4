<?php
class AppointmentRepository {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }

    // Фильтрация через GET-параметры
    public function findAllWithFilters($filters = []) {
        $sql = "SELECT l.*, 
                       CONCAT(s.last_name, ' ', s.first_name) as student_name,
                       CONCAT(t.last_name, ' ', t.first_name) as teacher_name,
                       i.instrument_name
                FROM lessons l
                JOIN students s ON l.student_id = s.student_id
                JOIN teachers t ON l.teacher_id = t.teacher_id
                JOIN instruments i ON l.instrument_id = i.instrument_id
                WHERE 1=1";
        $params = [];

        if (!empty($filters['date_from'])) {
            $sql .= " AND DATE(l.lesson_datetime) >= ?";
            $params[] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND DATE(l.lesson_datetime) <= ?";
            $params[] = $filters['date_to'];
        }
        if (!empty($filters['teacher_id'])) {
            $sql .= " AND l.teacher_id = ?";
            $params[] = $filters['teacher_id'];
        }
        if (!empty($filters['status'])) {
            $sql .= " AND l.status = ?";
            $params[] = $filters['status'];
        }
        if (!empty($filters['student_search'])) {
            $sql .= " AND (s.last_name LIKE ? OR s.first_name LIKE ?)";
            $params[] = "%{$filters['student_search']}%";
            $params[] = "%{$filters['student_search']}%";
        }

        $sql .= " ORDER BY l.lesson_datetime DESC LIMIT 100";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Создание записи с проверкой конфликтов
    public function createAppointment($data) {
        // Проверка: ученик не записан на этот инструмент дважды в один день
        $check = $this->pdo->prepare("SELECT COUNT(*) FROM lessons 
                                      WHERE student_id = ? AND instrument_id = ? 
                                      AND DATE(lesson_datetime) = DATE(?) AND status != 'отменено'");
        $check->execute([$data['student_id'], $data['instrument_id'], $data['lesson_datetime']]);
        if ($check->fetchColumn() > 0) {
            throw new Exception("Ученик уже записан на этот инструмент в выбранный день.");
        }

        $sql = "INSERT INTO lessons (student_id, teacher_id, instrument_id, lesson_datetime, duration_minutes, status) 
                VALUES (?, ?, ?, ?, ?, 'запланировано')";
        $this->pdo->prepare($sql)->execute([
            $data['student_id'], $data['teacher_id'], $data['instrument_id'],
            $data['lesson_datetime'], $data['duration_minutes'] ?? 60
        ]);
        return $this->pdo->lastInsertId();
    }

    // Смена статуса
       public function changeStatus($id, $newStatus) {
        // 1. Получаем текущие данные урока
        $stmt = $this->pdo->prepare("SELECT * FROM lessons WHERE lesson_id = ?");
        $stmt->execute([$id]);
        $lesson = $stmt->fetch();
        if (!$lesson) throw new Exception("Запись не найдена");

        $currentStatus = $lesson['status'];
        $lessonTime = strtotime($lesson['lesson_datetime']);
        $now = time();

        // 2. Валидация: нельзя завершить занятие, которое ещё не началось
        if ($newStatus === 'проведено') {
            if ($lessonTime > $now) {
                throw new Exception("Невозможно завершить не начатое занятие (время урока ещё не наступило)");
            }
            if ($currentStatus === 'отменено' || $currentStatus === 'проведено') {
                throw new Exception("Статус уже '$currentStatus', изменение невозможно");
            }
        }

        // 3. Валидация: нельзя отменить уже проведённое занятие
        if ($newStatus === 'отменено') {
            if ($currentStatus === 'проведено') {
                throw new Exception("Нельзя отменить уже проведённое занятие");
            }
        }

        // 4. Применяем изменение
        $stmt = $this->pdo->prepare("UPDATE lessons SET status = ? WHERE lesson_id = ?");
        $stmt->execute([$newStatus, $id]);
        return true;
    }
}