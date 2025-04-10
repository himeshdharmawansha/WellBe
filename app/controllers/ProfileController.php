<?php

require_once(__DIR__ . "/../models/ProfileModel.php");

class ProfileController
{
    private $profileModel;

    public function __construct()
    {
        // Suppress any warnings or notices that might interfere with JSON output
        error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
        $this->profileModel = new ProfileModel();
    }

    // Fetch a single user's profile image
    public function get($userId)
    {
        $user = $this->profileModel->getImage($userId);
        echo json_encode($user ?: ['error' => 'User not found']);
        exit();
    }

    // Fetch all user profiles
    public function getAll()
    {
        $users = $this->profileModel->getImageAll();
        echo json_encode($users ?: ['error' => 'No users found']);
        exit();
    }

    // Handle photo upload
    public function uploadPhoto()
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

        // Validate and sanitize the filename
        if (empty($file['name'])) {
            echo json_encode(['error' => 'Uploaded file has no name']);
            exit();
        }

        // Use the original filename, sanitized to remove problematic characters
        $filename = basename($file['name']);
        $filename = preg_replace('/[^A-Za-z0-9\-_\.]/', '_', $filename); // Replace special characters with underscores
        // Log the filename
        error_log("Generated filename: $filename", 3, __DIR__ . '/../../logs/debug.log');

        $uploadDir = __DIR__ . '/../../public/assets/images/users/';
        $uploadPath = $uploadDir . $filename;

        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
                error_log("Failed to create directory: $uploadDir", 3, __DIR__ . '/../../logs/error.log');
                echo json_encode(['error' => 'Failed to create upload directory']);
                exit();
            }
        }

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            try {
                // Log the user ID and filename before updating
                error_log("Updating image for user ID: $userId with filename: $filename", 3, __DIR__ . '/../../logs/debug.log');
                // Update the user's profile image in the database
                $this->profileModel->updateImage($userId, $filename);
                echo json_encode(['status' => 'success', 'filename' => $filename]);
            } catch (Exception $e) {
                error_log("Failed to update image in database: " . $e->getMessage(), 3, __DIR__ . '/../../logs/error.log');
                echo json_encode(['error' => 'Failed to update image in database: ' . $e->getMessage()]);
            }
        } else {
            error_log("Failed to move uploaded file to $uploadPath", 3, __DIR__ . '/../../logs/error.log');
            echo json_encode(['error' => 'Failed to move uploaded file']);
        }
        exit();
    }

    // Handle photo deletion
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

        // Get the current image to delete the file
        $user = $this->profileModel->getImage($userId);
        if ($user && !empty($user['profile_image_url'])) {
            $imagePath = __DIR__ . '/../../public/assets/images/users/' . basename($user['profile_image_url']);
            if (file_exists($imagePath)) {
                if (!unlink($imagePath)) {
                    error_log("Failed to delete image file: $imagePath", 3, __DIR__ . '/../../logs/error.log');
                }
            }
        }

        // Update the database to remove the image reference
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