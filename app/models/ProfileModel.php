<?php

class ProfileModel extends Model
{
    protected $table = 'user_profile';

    protected $allowedColumns = [
        'id',
        'image',
    ];

    public function getImage($userId)
    {
        $db = new Database();
        $query = "SELECT image FROM {$this->table} WHERE id = :id LIMIT 1";
        $result = $db->read($query, ['id' => $userId]);

        if ($result) {
            $image = $result[0]['image'] ?? null;
            return [
                'profile_image_url' => $image ? ROOT . '/assets/images/users/' . $image : null
            ];
        }
        return null;
    }

    public function getImageAll()
    {
        $db = new Database();
        $query = "SELECT id, image FROM {$this->table}";
        $results = $db->read($query);

        $users = [];
        foreach ($results as $user) {
            $users[] = [
                'id' => $user['id'],
                'profile_image_url' => $user['image'] ? ROOT . '/assets/images/users/' . $user['image'] : null
            ];
        }
        return $users;
    }

    public function updateImage($userId, $image)
    {
        if (empty($userId)) {
            throw new Exception("User ID cannot be empty");
        }
        if (empty($image)) {
            throw new Exception("Image filename cannot be empty");
        }

        $db = new Database();

        $exists = $db->read("SELECT 1 FROM {$this->table} WHERE id = :id LIMIT 1", ['id' => $userId]);
        if (!$exists) {
            throw new Exception("User ID $userId does not exist in user_profile table");
        }

        $current = $this->getImage($userId);
        if ($current && !empty($current['profile_image_url'])) {
            $oldImagePath = __DIR__ . '/../../public/assets/images/users/' . basename($current['profile_image_url']);
            $oldOriginalImagePath = __DIR__ . '/../../public/assets/images/users/' . pathinfo(basename($current['profile_image_url']), PATHINFO_FILENAME) . '_original.' . pathinfo(basename($current['profile_image_url']), PATHINFO_EXTENSION);
            
            if (file_exists($oldImagePath) && basename($oldImagePath) !== 'Profile_default.png') {
                if (!unlink($oldImagePath)) {
                    error_log("Failed to delete old image file: $oldImagePath", 3, __DIR__ . '/../../logs/error.log');
                }
            }
            if (file_exists($oldOriginalImagePath) && basename($oldOriginalImagePath) !== 'Profile_default.png') {
                if (!unlink($oldOriginalImagePath)) {
                    error_log("Failed to delete old original image file: $oldOriginalImagePath", 3, __DIR__ . '/../../logs/error.log');
                }
            }
        }

        $query = "UPDATE {$this->table} SET image = :image WHERE id = :id";
        $params = ['image' => $image, 'id' => $userId];
        $db->write($query, $params);
    }

    public function deleteImage($userId)
    {
        $db = new Database();

        $current = $this->getImage($userId);
        if ($current && !empty($current['profile_image_url'])) {
            $currentImagePath = __DIR__ . '/../../public/assets/images/users/' . basename($current['profile_image_url']);

            if (file_exists($currentImagePath) && basename($currentImagePath) !== 'Profile_default.png') {
                if (!unlink($currentImagePath)) {
                    error_log("Failed to delete image file: $currentImagePath", 3, __DIR__ . '/../../logs/error.log');
                }
            }
        }

        $query = "UPDATE {$this->table} SET image = 'Profile_default.png' WHERE id = :id";
        $result = $db->write($query, ['id' => $userId]);
        if (!$result) {
            throw new Exception("Failed to delete image from user_profile table for user ID: $userId");
        }
    }
}