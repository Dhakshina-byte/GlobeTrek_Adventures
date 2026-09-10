<?php
require_once __DIR__ . '/../config/database.php';

class User {

    public static function findByEmail($email) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findById($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /** Register a new account. Returns true on success, or an error string. */
    public static function register($name, $email, $password, $phone, $role = 'customer') {
        if (self::findByEmail($email)) {
            return 'An account with that email already exists.';
        }
        $db = getDB();
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare(
            "INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$name, $email, $hash, $phone, $role]);
        return true;
    }

    /** Returns the user row if credentials are correct, otherwise false */
    public static function attemptLogin($email, $password) {
        $user = self::findByEmail($email);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public static function getAllStaff() {
        $db = getDB();
        $stmt = $db->query("SELECT * FROM users WHERE role = 'staff' ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function countByRole($role) {
        $db = getDB();
        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE role = ?");
        $stmt->execute([$role]);
        return (int) $stmt->fetchColumn();
    }

    public static function deleteStaff($id) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM users WHERE user_id = ? AND role = 'staff'");
        $stmt->execute([$id]);
    }
}
