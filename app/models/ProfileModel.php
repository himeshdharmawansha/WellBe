<?php

class ProfileModel extends Model
{
    protected $table = 'users';

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
        $db = new Database();
        // First, get the current image to delete it
        $current = $this->getImage($userId);
        if ($current && !empty($current['profile_image_url'])) {
            $oldImagePath = __DIR__ . '/../../public/assets/images/users/' . basename($current['profile_image_url']);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        // Update with the new image
        $query = "UPDATE {$this->table} SET image = :image WHERE id = :id";
        $db->write($query, ['image' => $image, 'id' => $userId]);
    }

    public function deleteImage($userId)
    {
        $db = new Database();
        $query = "UPDATE {$this->table} SET image = NULL WHERE id = :id";
        $db->write($query, ['id' => $userId]);
    }
}