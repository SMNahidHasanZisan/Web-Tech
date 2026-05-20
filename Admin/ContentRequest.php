<?php
class ContentRequest {
    public static function create($pdo, $ip, $title, $category, $message) {
        $stmt = $pdo->prepare('INSERT INTO content_requests (requester_ip, content_title, category_requested, message) VALUES (?, ?, ?, ?)');
        return $stmt->execute([$ip, $title, $category, $message]);
    }

    public static function all($pdo) {
        $stmt = $pdo->query('SELECT * FROM content_requests ORDER BY created_at DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updateStatus($pdo, $id, $status) {
        $stmt = $pdo->prepare('UPDATE content_requests SET status = ? WHERE id = ?');
        return $stmt->execute([$status, $id]);
    }

    public static function pendingCount($pdo) {
        $stmt = $pdo->query("SELECT COUNT(*) FROM content_requests WHERE status = 'pending'");
        return (int)$stmt->fetchColumn();
    }
}

