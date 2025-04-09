<?php

class ProfileModel extends Model
{
    protected $table = 'user_profile';
    protected $allowedColumns = ['id', 'username', 'image', 'state']; // Adjust based on your actual columns

    // Fetch a single user's profile by ID
    public function getImage($userId)
    {
        $query = "SELECT id, username, image, state FROM user_profile WHERE id = :userId";
        $params = [':userId' => $userId];
        $result = $this->read($query, $params);

        if (!empty($result)) {
            $user = $result[0];
            $user['profile_image_url'] = !empty($user['image']) 
                ? ROOT . '/assets/images/users/' . $user['image'] 
                : ROOT . '/assets/images/users/Profile_default.png'; // Default image
            return $user;
        }
        return null;
    }

    // Fetch all user profiles
    public function getImageAll()
    {
        $query = "SELECT id, username, image, state FROM user_profile";
        $result = $this->read($query);

        if (!empty($result)) {
            foreach ($result as &$user) {
                $user['profile_image_url'] = !empty($user['image']) 
                    ? ROOT . '/assets/images/users/' . $user['image'] 
                    : ROOT . '/assets/images/users/Profile_default.png'; // Default image
            }
        }
        return $result;
    }

    // Placeholder for deleting a user's profile image
    public function deleteImage($userId)
    {
        // To be implemented later
        $query = "UPDATE user_profile SET image = NULL WHERE id = :userId";
        $params = [':userId' => $userId];
        $this->write($query, $params);
    }

    // Placeholder for updating a user's profile image
    public function updateImage($userId, $newImage)
    {
        // To be implemented later
        $query = "UPDATE user_profile SET image = :image WHERE id = :userId";
        $params = [
            ':image' => $newImage,
            ':userId' => $userId
        ];
        $this->write($query, $params);
    }
}