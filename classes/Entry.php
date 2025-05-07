<?php

class Entry {
    private $db;

    public function __construct(Database $db) {
        $this->db = $db->getConnection();
    }

    // Grąžina visus įrašus su prisijungusiu username
    public function getAll(): array {
        $sql = "SELECT e.*, u.username 
                FROM entries e 
                JOIN users u ON e.user_id = u.id 
                ORDER BY e.created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Patikrina ar įrašas priklauso vartotojui
    public function belongsToUser(int $entryId, int $userId): bool {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM entries WHERE id = ? AND user_id = ?");
        $stmt->execute([$entryId, $userId]);
        return (bool) $stmt->fetchColumn();
    }

    // Ištrina įrašą pagal ID
    public function delete(int $entryId): void {
        $stmt = $this->db->prepare("DELETE FROM entries WHERE id = ?");
        $stmt->execute([$entryId]);
    }

    // Grąžina vieną įrašą pagal ID
    public function getById(int $entryId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM entries WHERE id = ?");
        $stmt->execute([$entryId]);
        $entry = $stmt->fetch(PDO::FETCH_ASSOC);
        return $entry ?: null;
    }

    // Atnaujina įrašą
    public function update(int $entryId, string $title, string $content, string $location, string $entryType): bool {
        $sql = "UPDATE entries 
                SET title = ?, content = ?, location = ?, entry_type = ? 
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$title, $content, $location, $entryType, $entryId]);
    }
}