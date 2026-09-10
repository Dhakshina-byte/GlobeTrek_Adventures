<?php
require_once __DIR__ . '/../config/database.php';

class Payment {

    public static function create($bookingId, $amount, $method, $cardHolder, $cardLast4) {
        $db = getDB();
        $stmt = $db->prepare(
            "INSERT INTO payments (booking_id, amount, method, card_holder, card_last4)
             VALUES (?,?,?,?,?)"
        );
        $stmt->execute([$bookingId, $amount, $method, $cardHolder, $cardLast4]);
        return $db->lastInsertId();
    }

    public static function getByBooking($bookingId) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM payments WHERE booking_id = ?");
        $stmt->execute([$bookingId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
