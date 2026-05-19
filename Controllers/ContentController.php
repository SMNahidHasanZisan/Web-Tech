<?php
require_once __DIR__ . '/../config/helpers.php';
require_once __DIR__ . '/../models/Content.php';

function handle_content_create($pdo, $backPage) {
    require_login();
    verify_csrf();

    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $categoryId = (int)($_POST['category_id'] ?? 0);

    if ($title === '' || $description === '' || $categoryId <= 0) {
        flash('error', 'Title, description and category are required.');
        redirect($backPage);
    }

    $filePath = upload_file(
        'content_file',
        'contents',
        ['mp4', 'mkv', 'pdf', 'exe', 'zip', 'rar', 'jpg', 'png', 'txt'],
        25 * 1024 * 1024,
        ['video/mp4', 'video/x-matroska', 'application/pdf', 'application/zip', 'application/x-rar', 'application/x-dosexec', 'application/octet-stream', 'application/vnd.microsoft.portable-executable', 'image/jpeg', 'image/png', 'text/plain']
    );
    if (!$filePath) {
        flash('error', 'Upload a valid file.');
        redirect($backPage);
    }

    Content::create($pdo, $title, $description, $filePath, $categoryId, current_user_id());
    flash('success', 'Content uploaded successfully.');
    redirect($backPage);
}

function handle_content_update($pdo, $backPage) {
    require_role('admin');
    verify_csrf();

    $id = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $categoryId = (int)($_POST['category_id'] ?? 0);

    if ($id <= 0 || $title === '' || $description === '' || $categoryId <= 0) {
        flash('error', 'Enter valid content data.');
        redirect($backPage);
    }

    Content::update($pdo, $id, $title, $description, $categoryId);
    flash('success', 'Content updated successfully.');
    redirect($backPage);
}

function handle_content_delete($pdo, $backPage) {
    require_login();
    verify_csrf();
    $id = (int)($_POST['id'] ?? 0);

    if ($id > 0) {
        Content::delete($pdo, $id);
        flash('success', 'Content deleted successfully.');
    }

    redirect($backPage);
}
