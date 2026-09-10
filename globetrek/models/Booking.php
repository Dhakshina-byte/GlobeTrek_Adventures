<?php
require_once __DIR__ . '/../config/database.php';

class Booking {

    public static function create($userId, $packageId, $travelDate, $travelers, $requests, $totalPrice) {
        $db = getDB();
        $stmt = $db->prepare(
            "INSERT INTO bookings (user_id, package_id, travel_date, travelers, special_requests, total_price)
             VALUES (?,?,?,?,?,?)"
        );
        $stmt->execute([$userId, $packageId, $travelDate, $travelers, $requests, $totalPrice]);
        return $db->lastInsertId();
    }

    public static function getById($id) {
        $db = getDB();
        $stmt = $db->prepare(
            "SELECT b.*, p.title, p.destination, p.price AS unit_price
             FROM bookings b JOIN packages p ON p.package_id = b.package_id
             WHERE b.booking_id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getByUser($userId) {
        $db = getDB();
        $stmt = $db->prepare(
            "SELECT b.*, p.title, p.destination
             FROM bookings b JOIN packages p ON p.package_id = b.package_id
             WHERE b.user_id = ? ORDER BY b.created_at DESC"
        );
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getAll() {
        $db = getDB();
        $stmt = $db->query(
            "SELECT b.*, p.title, p.destination, u.name AS customer_name, u.email AS customer_email
             FROM bookings b
             JOIN packages p ON p.package_id = b.package_id
             JOIN users u ON u.user_id = b.user_id
             ORDER BY b.created_at DESC"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updateStatus($id, $status) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE bookings SET status = ? WHERE booking_id = ?");
        $stmt->execute([$status, $id]);
    }

    public static function markPaid($id) {
        $db = getDB();
        $stmt = $db->prepare("UPDATE bookings SET payment_status = 'paid', status = 'confirmed' WHERE booking_id = ?");
        $stmt->execute([$id]);
    }

    public static function countAll() {
        $db = getDB();
        return (int) $db->query("SELECT COUNT(*) FROM bookings")->fetchColumn();
    }

    public static function totalRevenue() {
        $db = getDB();
        return (float) $db->query("SELECT COALESCE(SUM(total_price),0) FROM bookings WHERE payment_status = 'paid'")->fetchColumn();
    }
}
