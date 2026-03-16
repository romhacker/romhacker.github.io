<?php

header('Content-Type: application/json; charset=utf-8');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode([
            'status' => 'error',
            'message' => 'Method not allowed'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if (!isset($_FILES['file'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'File field "file" not found'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $file = $_FILES['file'];

    if (!empty($file['error'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Upload error: ' . $file['error']
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if (!is_uploaded_file($file['tmp_name'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid uploaded file'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $maxSize = 5 * 1024 * 1024;
    if ($file['size'] > $maxSize) {
        echo json_encode([
            'status' => 'error',
            'message' => 'File too large'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $allowed = [
        'image/jpeg'    => 'jpeg',
        'image/png'     => 'png',
        'image/svg+xml' => 'svg'
    ];

    if (!isset($allowed[$mime])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Unsupported file type'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $ext = $allowed[$mime];

    $hash = md5(uniqid((string)mt_rand(), true));
    $dir1 = substr($hash, 0, 2);
    $dir2 = substr($hash, 2, 2);
    $name = substr($hash, 4, 12) . '_' . mt_rand(10000, 99999) . '.' . $ext;

    $projectRoot = realpath(__DIR__ . '/../../../');
    if ($projectRoot === false) {
        throw new RuntimeException('Project root not found');
    }

    $storageDir = $projectRoot . DIRECTORY_SEPARATOR . 'storage'
        . DIRECTORY_SEPARATOR . $dir1
        . DIRECTORY_SEPARATOR . $dir2;

    if (!is_dir($storageDir) && !mkdir($storageDir, 0777, true) && !is_dir($storageDir)) {
        throw new RuntimeException('Failed to create storage directory');
    }

    $targetPath = $storageDir . DIRECTORY_SEPARATOR . $name;

    if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
        throw new RuntimeException('Failed to save uploaded file');
    }

    $url = $dir1 . '/' . $dir2 . '/' . $name;

    echo json_encode([
        'status' => 'success',
        'url'    => $url
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

} catch (Throwable $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}