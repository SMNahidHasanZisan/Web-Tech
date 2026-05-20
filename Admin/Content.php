<?php
class Content {
    public static function highlighted($pdo) {
        $stmt = $pdo->query('SELECT c.*, cat.name AS category_name FROM contents c JOIN categories cat ON c.category_id = cat.id ORDER BY c.download_count DESC, c.uploaded_at DESC LIMIT 6');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function all($pdo) {
        $stmt = $pdo->query('SELECT c.*, cat.name AS category_name, u.name AS uploader_name FROM contents c JOIN categories cat ON c.category_id = cat.id JOIN users u ON c.uploader_id = u.id ORDER BY c.uploaded_at DESC');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function byCategoryIds($pdo, $ids) {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("SELECT c.*, cat.name AS category_name FROM contents c JOIN categories cat ON c.category_id = cat.id WHERE c.category_id IN ($placeholders) ORDER BY c.uploaded_at DESC");
        $stmt->execute($ids);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function search($pdo, $query, $categoryId = null) {
        $sql = 'SELECT c.*, cat.name AS category_name FROM contents c JOIN categories cat ON c.category_id = cat.id WHERE (c.title LIKE ? OR c.description LIKE ?)';
        $params = ['%' . $query . '%', '%' . $query . '%'];

        if ($categoryId) {
            $sql .= ' AND c.category_id = ?';
            $params[] = $categoryId;
        }

        $sql .= ' ORDER BY c.uploaded_at DESC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($pdo, $title, $description, $filePath, $categoryId, $uploaderId) {
        $stmt = $pdo->prepare('INSERT INTO contents (title, description, file_path, category_id, uploader_id) VALUES (?, ?, ?, ?, ?)');
        return $stmt->execute([$title, $description, $filePath, $categoryId, $uploaderId]);
    }

    public static function find($pdo, $id) {
        $stmt = $pdo->prepare('SELECT * FROM contents WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function update($pdo, $id, $title, $description, $categoryId) {
        $stmt = $pdo->prepare('UPDATE contents SET title = ?, description = ?, category_id = ? WHERE id = ?');
        return $stmt->execute([$title, $description, $categoryId, $id]);
    }

    public static function delete($pdo, $id) {
        $content = self::find($pdo, $id);
        if ($content && !empty($content['file_path'])) {
            $file = __DIR__ . '/../public/' . $content['file_path'];
            if (is_file($file)) {
                unlink($file);
            }
        }

        $stmt = $pdo->prepare('DELETE FROM contents WHERE id = ?');
        return $stmt->execute([$id]);
    }

    public static function incrementDownload($pdo, $id) {
        $stmt = $pdo->prepare('UPDATE contents SET download_count = download_count + 1 WHERE id = ?');
        return $stmt->execute([$id]);
    }
}

