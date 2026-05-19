<?php
class Category {
    public static function all($pdo) {
        $stmt = $pdo->query('SELECT * FROM categories ORDER BY parent_id IS NOT NULL, name');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function topLevel($pdo) {
        $stmt = $pdo->query('SELECT * FROM categories WHERE parent_id IS NULL ORDER BY name');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function children($pdo, $parentId) {
        $stmt = $pdo->prepare('SELECT * FROM categories WHERE parent_id = ? ORDER BY name');
        $stmt->execute([$parentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function idsWithChildren($pdo, $id) {
        $ids = [(int)$id];
        $stmt = $pdo->prepare('SELECT id FROM categories WHERE parent_id = ?');
        $stmt->execute([$id]);
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $ids[] = (int)$row['id'];
        }
        return $ids;
    }
}

