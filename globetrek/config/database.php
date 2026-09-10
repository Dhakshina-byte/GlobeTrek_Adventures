<?php
/**
 * Database connection settings.
 * Default values match a fresh WAMP install (user: root, no password).
 * Change these if your MySQL setup is different.
 */
define('DB_HOST', 'localhost');
define('DB_NAME', 'globetrek_db');
define('DB_USER', 'root');
define('DB_PASS', '');

function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS
            );
            // Throw exceptions on error instead of failing silently
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Friendly error page instead of a raw PHP crash
            die('<h2>Database connection failed.</h2>
                 <p>Please make sure WAMP is running and that the
                 <strong>globetrek_db</strong> database has been imported
                 in phpMyAdmin.</p>
                 <p style="color:#888">Technical detail: ' . htmlspecialchars($e->getMessage()) . '</p>');
        }
    }
    return $pdo;
}
