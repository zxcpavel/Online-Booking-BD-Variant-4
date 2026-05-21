<?php
class InstrumentRepository {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }

    public function findAll($search = '') {
        $sql = "SELECT * FROM instruments WHERE instrument_name LIKE ? ORDER BY instrument_name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(["%$search%"]);
        return $stmt->fetchAll();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM instruments WHERE instrument_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO instruments (instrument_name, description) VALUES (?, ?)";
        $this->pdo->prepare($sql)->execute([$data['instrument_name'], $data['description'] ?? null]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE instruments SET instrument_name=?, description=? WHERE instrument_id=?";
        return $this->pdo->prepare($sql)->execute([$data['instrument_name'], $data['description'] ?? null, $id]);
    }

    public function delete($id) {
        return $this->pdo->prepare("DELETE FROM instruments WHERE instrument_id = ?")->execute([$id]);
    }

    public function hasRelatedLessons($id) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM lessons WHERE instrument_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }
}