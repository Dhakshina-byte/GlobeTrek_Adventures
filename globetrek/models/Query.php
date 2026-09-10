<?php
require_once __DIR__ . '/../config/database.php';

class InquiryQuery {

    public static function create($userId, $name, $email, $subject, $message) {
        $db = getDB();
        $stmt = $db->prepare(
            "INSERT INTO queries (user_id, name, email, subject, message) VALUES (?,?,?,?,?)"
        );
        $stmt->execute([$userId, $name, $email, $subject, $message]);
        return $db->lastInsertId();
    }

    public static function getAll() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM queries ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM queries WHERE query_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function reply($id, $reply, $staffId) {
        $db = getDB();
        $stmt = $db->prepare(
            "UPDATE queries SET reply = ?, replied_by = ?, status = 'answered' WHERE query_id = ?"
        );
        $stmt->execute([$reply, $staffId, $id]);
    }

    public static function countOpen() {
        $db = getDB();
        return (int) $db->query("SELECT COUNT(*) FROM queries WHERE status = 'open'")->fetchColumn();
    }
}
