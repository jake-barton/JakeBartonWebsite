<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit;
}

// Check if file was uploaded
if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['success' => false, 'error' => 'No file uploaded or upload error']);
    exit;
}

$file = $_FILES['file'];
$fileName = $file['name'];
$fileTmpName = $file['tmp_name'];
$fileSize = $file['size'];
$fileError = $file['error'];

// Get file extension
$fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

// Allowed file types
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'svg'];

if (!in_array($fileExt, $allowedExtensions)) {
    echo json_encode(['success' => false, 'error' => 'Invalid file type. Allowed: JPG, PNG, GIF, WEBP, SVG, PDF']);
    exit;
}

// Check file size (max 5MB)
if ($fileSize > 5242880) {
    echo json_encode(['success' => false, 'error' => 'File too large. Max size: 5MB']);
    exit;
}

// Create uploads directory if it doesn't exist
$uploadDir = 'uploads/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Generate unique filename
$newFileName = uniqid() . '_' . time() . '.' . $fileExt;
$uploadPath = $uploadDir . $newFileName;

// Move uploaded file
if (move_uploaded_file($fileTmpName, $uploadPath)) {
    echo json_encode([
        'success' => true,
        'url' => $uploadPath,
        'filename' => $newFileName
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Failed to move uploaded file']);
}
?>
