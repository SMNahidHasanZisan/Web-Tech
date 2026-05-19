<?php
class User {
    public static function findByEmail($pdo, $email) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findById($pdo, $id) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($pdo, $name, $email, $password, $role) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?, ?, ?, ?)');
        return $stmt->execute([$name, $email, $hash, $role]);
    }

    public static function updateProfile($pdo, $id, $name, $email, $profilePicture) {
        $stmt = $pdo->prepare('UPDATE users SET name = ?, email = ?, profile_picture = COALESCE(?, profile_picture) WHERE id = ?');
        return $stmt->execute([$name, $email, $profilePicture, $id]);
    }

    public static function updatePassword($pdo, $id, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
        return $stmt->execute([$hash, $id]);
    }

    public static function moderators($pdo) {
        $stmt = $pdo->query("SELECT * FROM users WHERE role = 'moderator' ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updateModerator($pdo, $id, $name, $email, $password = '') {
        if ($password !== '') {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('UPDATE users SET name = ?, email = ?, password_hash = ? WHERE id = ? AND role = ?');
            return $stmt->execute([$name, $email, $hash, $id, 'moderator']);
        }

        $stmt = $pdo->prepare('UPDATE users SET name = ?, email = ? WHERE id = ? AND role = ?');
        return $stmt->execute([$name, $email, $id, 'moderator']);
    }

    public static function delete($pdo, $id) {
        $stmt = $pdo->prepare('DELETE FROM users WHERE id = ? AND role = ?');
        return $stmt->execute([$id, 'moderator']);
    }
}
