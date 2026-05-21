<?php
class StudentRepository {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }

    public function findAll($search = '') {
        $sql = "SELECT * FROM students WHERE last_name LIKE ? OR first_name LIKE ? ORDER BY last_name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(["%$search%", "%$search%"]);
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM students WHERE student_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO students (last_name, first_name, patronymic, phone, email, birth_date, enrollment_date) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $this->pdo->prepare($sql)->execute([
            $data['last_name'], $data['first_name'], $data['patronymic'] ?? null,
            $data['phone'], $data['email'], $data['birth_date'], date('Y-m-d')
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE students SET last_name=?, first_name=?, patronymic=?, phone=?, email=?, birth_date=? WHERE student_id=?";
        return $this->pdo->prepare($sql)->execute([
            $data['last_name'], $data['first_name'], $data['patronymic'] ?? null,
            $data['phone'], $data['email'], $data['birth_date'], $id
        ]);
    }

    public function delete($id) {
        return $this->pdo->prepare("DELETE FROM students WHERE student_id = ?")->execute([$id]);
    }

    // Проверка связей для удаления
    public function hasRelatedLessons($id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM lessons WHERE student_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }
}