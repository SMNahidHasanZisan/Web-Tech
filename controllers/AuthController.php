<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../models/User.php';

function handle_register($pdo) {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $role = $_POST['role'] ?? '';

    if ($name === '' || $email === '' || $password === '' || $confirm === '' || $role === '') {
        flash('error', 'All fields are required.');
        redirect('register.php');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash('error', 'Enter a valid email address.');
        redirect('register.php');
    }

    if (strlen($password) < 8) {
        flash('error', 'Password must be at least 8 characters.');
        redirect('register.php');
    }

    if ($password !== $confirm) {
        flash('error', 'Passwords do not match.');
        redirect('register.php');
    }

    if (!in_array($role, ['admin', 'moderator'], true)) {
        flash('error', 'Select a valid role.');
        redirect('register.php');
    }

    if (User::findByEmail($pdo, $email)) {
        flash('error', 'Email already exists.');
        redirect('register.php');
    }

    User::create($pdo, $name, $email, $password, $role);
    flash('success', 'Registration successful. Please login.');
    redirect('login.php');
}

function handle_login($pdo) {
    verify_csrf();
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = !empty($_POST['remember']);

    if ($email === '' || $password === '') {
        flash('error', 'Email and password are required.');
        redirect('login.php');
    }

    $user = User::findByEmail($pdo, $email);
    if (!$user || !password_verify($password, $user['password_hash'])) {
        flash('error', 'Invalid email or password.');
        redirect('login.php');
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['role'] = $user['role'];

    if ($remember) {
        $token = bin2hex(random_bytes(32));
        $hash = hash('sha256', $token);
        $expires = date('Y-m-d H:i:s', time() + 86400 * 7);
        $stmt = $pdo->prepare('INSERT INTO remember_tokens (user_id, token_hash, expires_at) VALUES (?, ?, ?)');
        $stmt->execute([$user['id'], $hash, $expires]);
        setcookie('remember_token', $user['id'] . ':' . $token, time() + 86400 * 7, '/');
    }

    redirect('index.php');
}

function check_remember_login($pdo) {
    if (is_logged_in() || empty($_COOKIE['remember_token'])) {
        return;
    }

    $parts = explode(':', $_COOKIE['remember_token'], 2);
    if (count($parts) !== 2) {
        return;
    }

    [$userId, $token] = $parts;
    $hash = hash('sha256', $token);
    $stmt = $pdo->prepare('SELECT * FROM remember_tokens WHERE user_id = ? AND token_hash = ? AND expires_at > NOW()');
    $stmt->execute([$userId, $hash]);
    if (!$stmt->fetch()) {
        return;
    }

    $user = User::findById($pdo, $userId);
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
    }
}

function handle_logout($pdo) {
    if (!empty($_COOKIE['remember_token'])) {
        $parts = explode(':', $_COOKIE['remember_token'], 2);
        if (count($parts) === 2) {
            $stmt = $pdo->prepare('DELETE FROM remember_tokens WHERE user_id = ?');
            $stmt->execute([$parts[0]]);
        }
        setcookie('remember_token', '', time() - 3600, '/');
    }

    session_unset();
    session_destroy();
    redirect('index.php');
}
