<?php

require_once(__DIR__ . "/../models/ProfileModel.php");

class ProfileController
{
    private $profileModel;

    public function __construct()
    {
        $this->profileModel = new ProfileModel();
    }

    // Fetch a single user's profile data
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

    // Placeholder for deleting a user's profile image
    public function delete($userId)
    {
        // To be implemented later
        $this->profileModel->deleteImage($userId);
        echo json_encode(['status' => 'success', 'message' => 'Profile image deleted']);
    }

    // Placeholder for updating a user's profile image
    public function update($userId)
    {
        // To be implemented later
        $data = json_decode(file_get_contents('php://input'), true);
        $newImage = $data['image'] ?? null;
        if ($newImage) {
            $this->profileModel->updateImage($userId, $newImage);
            echo json_encode(['status' => 'success', 'message' => 'Profile image updated']);
        } else {
            echo json_encode(['error' => 'No image provided']);
        }
    }
}