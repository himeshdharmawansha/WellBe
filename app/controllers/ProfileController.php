<?php

require_once(__DIR__ . "/../models/ProfileModel.php");

class ProfileController
{
    private $profileModel;

    public function __construct()
    {
        $this->profileModel = new ProfileModel();
    }

    // Fetch a single user's profile image
    public function get($userId)
    {
        $user = $this->profileModel->getImage($userId);
        echo json_encode($user ?: ['error' => 'User not found']);
    }

    // Fetch all user profiles
    public function getAll()
    {
        $users = $this->profileModel->getImageAll();
        echo json_encode($users ?: ['error' => 'No users found']);
    }

    // Handle photo upload
    public function uploadPhoto()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Invalid request method']);
            return;
        }

        $userId = $_POST['userId'] ?? null;
        if (!$userId) {
            echo json_encode(['error' => 'User ID is required']);
            return;
        }

        if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
            echo json_encode(['error' => 'No file uploaded or upload error']);
            return;
        }

        $file = $_FILES['photo'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            echo json_encode(['error' => 'Invalid file type. Only JPEG, PNG, and GIF are allowed']);
            return;
        }

        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($file['size'] > $maxSize) {
            echo json_encode(['error' => 'File size exceeds 5MB limit']);
            return;
        }

        $filename = uniqid() . '-' . basename($file['name']);
        $uploadDir = __DIR__ . '/../../public/assets/images/users/';
        $uploadPath = $uploadDir . $filename;

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            // Update the user's profile image in the database
            $this->profileModel->updateImage($userId, $filename);
            echo json_encode(['status' => 'success', 'filename' => $filename]);
        } else {
            echo json_encode(['error' => 'Failed to move uploaded file']);
        }
    }

    // Handle photo deletion
    public function deletePhoto($userId)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Invalid request method']);
            return;
        }

        if (!$userId) {
            echo json_encode(['error' => 'User ID is required']);
            return;
        }

        // Get the current image to delete the file
        $user = $this->profileModel->getImage($userId);
        if ($user && !empty($user['profile_image_url'])) {
            $imagePath = __DIR__ . '/../../public/assets/images/users/' . basename($user['profile_image_url']);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Update the database to remove the image reference
        $this->profileModel->deleteImage($userId);
        echo json_encode(['status' => 'success', 'message' => 'Profile image deleted']);
    }
}