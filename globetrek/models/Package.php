<?php
require_once __DIR__ . '/../config/database.php';

class Package {

    /** All packages, optionally filtered by a search keyword (destination or title) */
    public static function getAll($search = '') {
        $db = getDB();
        if ($search !== '') {
            $stmt = $db->prepare(
                "SELECT package_id, title, destination, description, activities,
                        price, duration_days, duration_nights, created_at
                 FROM packages
                 WHERE title LIKE ? OR destination LIKE ?
                 ORDER BY created_at DESC"
            );
            $like = "%$search%";
            $stmt->execute([$like, $like]);
        } else {
            $stmt = $db->query(
                "SELECT package_id, title, destination, description, activities,
                        price, duration_days, duration_nights, created_at
                 FROM packages ORDER BY created_at DESC"
            );
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        $db = getDB();
        $stmt = $db->prepare(
            "SELECT package_id, title, destination, description, activities,
                    price, duration_days, duration_nights, created_at
             FROM packages WHERE package_id = ?"
        );
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /** Returns ['data' => binary, 'type' => mime] or null if no image stored */
    public static function getImage($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT image, image_type FROM packages WHERE package_id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && $row['image']) {
            return ['data' => $row['image'], 'type' => $row['image_type']];
        }
        return null;
    }

    public static function hasImage($id) {
        $db = getDB();
        $stmt = $db->prepare("SELECT image_type FROM packages WHERE package_id = ?");
        $stmt->execute([$id]);
        $type = $stmt->fetchColumn();
        return !empty($type);
    }

    /** $imageData / $imageType may be null when no picture was uploaded */
    public static function create($data, $imageData, $imageType, $createdBy) {
        $db = getDB();
        $stmt = $db->prepare(
            "INSERT INTO packages
                (title, destination, description, activities, price,
                 duration_days, duration_nights, image, image_type, created_by)
             VALUES (?,?,?,?,?,?,?,?,?,?)"
        );
        $stmt->execute([
            $data['title'], $data['destination'], $data['description'], $data['activities'],
            $data['price'], $data['duration_days'], $data['duration_nights'],
            $imageData, $imageType, $createdBy
        ]);
        return $db->lastInsertId();
    }

    public static function update($id, $data, $imageData, $imageType) {
        $db = getDB();
        if ($imageData !== null) {
            $stmt = $db->prepare(
                "UPDATE packages SET title=?, destination=?, description=?, activities=?,
                    price=?, duration_days=?, duration_nights=?, image=?, image_type=?
                 WHERE package_id = ?"
            );
            $stmt->execute([
                $data['title'], $data['destination'], $data['description'], $data['activities'],
                $data['price'], $data['duration_days'], $data['duration_nights'],
                $imageData, $imageType, $id
            ]);
        } else {
            // Keep the existing picture if the staff member did not upload a new one
            $stmt = $db->prepare(
                "UPDATE packages SET title=?, destination=?, description=?, activities=?,
                    price=?, duration_days=?, duration_nights=?
                 WHERE package_id = ?"
            );
            $stmt->execute([
                $data['title'], $data['destination'], $data['description'], $data['activities'],
                $data['price'], $data['duration_days'], $data['duration_nights'],
                $id
            ]);
        }
    }

    public static function delete($id) {
        $db = getDB();
        $stmt = $db->prepare("DELETE FROM packages WHERE package_id = ?");
        $stmt->execute([$id]);
    }

    public static function countAll() {
        $db = getDB();
        return (int) $db->query("SELECT COUNT(*) FROM packages")->fetchColumn();
    }
}
