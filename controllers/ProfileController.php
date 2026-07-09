<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../models/User.php';

function handle_profile_update($pdo) {
    require_login();
    verify_csrf();

    $user = User::findById($pdo, current_user_id());
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($name === '' || $email === '') {
        flash('error', 'Name and email are required.');
        redirect('profile.php');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash('error', 'Enter a valid email.');
        redirect('profile.php');
    }

    $existing = User::findByEmail($pdo, $email);
    if ($existing && (int)$existing['id'] !== (int)$user['id']) {
        flash('error', 'This email is already used.');
        redirect('profile.php');
    }

    $picture = upload_file(
        'profile_picture',
        'profiles',
        ['jpg', 'jpeg', 'png', 'gif'],
        2 * 1024 * 1024,
        ['image/jpeg', 'image/png', 'image/gif']
    );
    User::updateProfile($pdo, $user['id'], $name, $email, $picture);

    if ($newPassword !== '') {
        if ($currentPassword === '' || !password_verify($currentPassword, $user['password_hash'])) {
            flash('error', 'Current password is wrong.');
            redirect('profile.php');
        }

        if (strlen($newPassword) < 8 || $newPassword !== $confirmPassword) {
            flash('error', 'New password must be 8 characters and match confirm password.');
            redirect('profile.php');
        }

        User::updatePassword($pdo, $user['id'], $newPassword);
    }

    $_SESSION['name'] = $name;
    flash('success', 'Profile updated successfully.');
    redirect('profile.php');
}
