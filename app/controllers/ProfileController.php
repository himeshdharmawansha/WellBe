<?php

require_once(__DIR__ . "/../models/ProfileModel.php");

class ProfileController
{
    private $profileModel;

    public function __construct()
    {
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
        $this->profileModel = new ProfileModel();
    }

    public function get($userId)
    {
        $user = $this->profileModel->getImage($userId);
        echo json_encode($user ?: ['error' => 'User not found']);
        exit();
    }

    public function getAll()
    {
        $users = $this->profileModel->getImageAll();
        echo json_encode($users ?: ['error' => 'No users found']);
        exit();
    }

    public function saveOriginalPhoto()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Invalid request method']);
            exit();
        }

        $userId = $_POST['userId'] ?? null;
        if (!$userId) {
            echo json_encode(['error' => 'User ID is required']);
            exit();
        }

        if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['error' => 'No file uploaded or upload error']);
            exit();
        }

        $file = $_FILES['photo'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            echo json_encode(['error' => 'Invalid file type. Only JPEG, PNG, and GIF are allowed']);
            exit();
        }

        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($file['size'] > $maxSize) {
            echo json_encode(['error' => 'File size exceeds 5MB limit']);
            exit();
        }

        $filename = $_POST['filename'] ?? null;
        if (!$filename) {
            echo json_encode(['error' => 'Filename is required']);
            exit();
        }

        $uploadDir = __DIR__ . '/../../public/assets/images/users/';
        $originalFilename = pathinfo($filename, PATHINFO_FILENAME) . '_original.' . pathinfo($filename, PATHINFO_EXTENSION);
        $originalPath = $uploadDir . $originalFilename;

        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                error_log("Failed to create directory: $uploadDir", 3, __DIR__ . '/../../logs/error.log');
                echo json_encode(['error' => 'Failed to create upload directory']);
                exit();
            }
        }

        if (!move_uploaded_file($file['tmp_name'], $originalPath)) {
            error_log("Failed to move original file to $originalPath", 3, __DIR__ . '/../../logs/error.log');
            echo json_encode(['error' => 'Failed to move original file']);
            exit();
        }

        try {
            error_log("Updating image for user ID: $userId with filename: $filename", 3, __DIR__ . '/../../logs/debug.log');
            $this->profileModel->updateImage($userId, $filename);
            echo json_encode(['status' => 'success', 'filename' => $filename]);
        } catch (Exception $e) {
            error_log("Failed to update image in database: " . $e->getMessage(), 3, __DIR__ . '/../../logs/error.log');
            echo json_encode(['error' => 'Failed to update image in database: ' . $e->getMessage()]);
        }
        exit();
    }

    public function saveEditedPhoto()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Invalid request method']);
            exit();
        }

        $userId = $_POST['userId'] ?? null;
        if (!$userId) {
            echo json_encode(['error' => 'User ID is required']);
            exit();
        }

        if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['error' => 'No file uploaded or upload error']);
            exit();
        }

        $file = $_FILES['photo'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            echo json_encode(['error' => 'Invalid file type. Only JPEG, PNG, and GIF are allowed']);
            exit();
        }

        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($file['size'] > $maxSize) {
            echo json_encode(['error' => 'File size exceeds 5MB limit']);
            exit();
        }

        $filename = $_POST['filename'] ?? null;
        if (!$filename) {
            echo json_encode(['error' => 'Filename is required']);
            exit();
        }

        $uploadDir = __DIR__ . '/../../public/assets/images/users/';
        $uploadPath = $uploadDir . $filename;

        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                error_log("Failed to create directory: $uploadDir", 3, __DIR__ . '/../../logs/error.log');
                echo json_encode(['error' => 'Failed to create upload directory']);
                exit();
            }
        }

        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            error_log("Failed to move edited file to $uploadPath", 3, __DIR__ . '/../../logs/error.log');
            echo json_encode(['error' => 'Failed to move edited file']);
            exit();
        }

        echo json_encode(['status' => 'success', 'filename' => $filename]);
        exit();
    }

    public function deletePhoto($userId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Invalid request method']);
            exit();
        }

        if (!$userId) {
            echo json_encode(['error' => 'User ID is required']);
            exit();
        }

        $user = $this->profileModel->getImage($userId);
        if ($user && !empty($user['profile_image_url'])) {
            $imagePath = __DIR__ . '/../../public/assets/images/users/' . basename($user['profile_image_url']);
            $originalImagePath = __DIR__ . '/../../public/assets/images/users/' . pathinfo(basename($user['profile_image_url']), PATHINFO_FILENAME) . '_original.' . pathinfo(basename($user['profile_image_url']), PATHINFO_EXTENSION);
            
            if (file_exists($imagePath)) {
                if (!unlink($imagePath)) {
                    error_log("Failed to delete image file: $imagePath", 3, __DIR__ . '/../../logs/error.log');
                }
            }
            if (file_exists($originalImagePath)) {
                if (!unlink($originalImagePath)) {
                    error_log("Failed to delete original image file: $originalImagePath", 3, __DIR__ . '/../../logs/error.log');
                }
            }
        }

        try {
            $this->profileModel->deleteImage($userId);
            echo json_encode(['status' => 'success', 'message' => 'Profile image deleted']);
        } catch (Exception $e) {
            error_log("Failed to delete image from database: " . $e->getMessage(), 3, __DIR__ . '/../../logs/error.log');
            echo json_encode(['error' => 'Failed to delete image from database']);
        }
        exit();
    }
}