<?php

class ProfileModel extends Model
{
    protected $table = 'user_profile';

    protected $allowedColumns = [
        'id',
        'username',
        'password',
        'role',
        'image',
        'state'
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

    public function addUser($data, $role)
    {
        if($role == 1){
            $id = $data['nic'] . 'h';
            $password = password_hash('pharm123', PASSWORD_DEFAULT);
            //$password = 'pharm123';
            $role ='1';
        }

        if($role == 2){
            $id = $data['nic'] . 'l';
            //$password = 'lab123';
            $password = password_hash('lab123', PASSWORD_DEFAULT);
            $role = '2';
        }

        if($role == 3){
            $id = $data['nic'] . 'a';
            //$password = 'admin';
            $password = password_hash('admin', PASSWORD_DEFAULT);
            $role = '3';
        }

        if($role == 4){
            $id = $data['nic'] . 'p';
            //$password = 'patient123';
            $password = password_hash('patient123', PASSWORD_DEFAULT);
            $role = '4';
        }

        if($role == 5){
            $id = $data['nic'] . 'd';
            //$password = 'doc123';
            $password = password_hash('doc123', PASSWORD_DEFAULT);
            $role = '5';
        }

        // Build the SQL query using the provided data
        $query = "
            INSERT INTO `user_profile` 
            (`id`, `username`, `password`, `role`) 
            VALUES (
                '{$id}', 
                '{$data['first_name']}',
                '{$password}', 
                '{$role}'
            )
        ";

        // Debug the query
        //echo("Generated Query: <pre>$query</pre>");

        // Execute the query
        return $this->query($query);

    }


    public function addPatientUser($data, $role)
    {
        // Check if the role is 4 (patient)
        if ($role == 4) {
            // Assuming the password is provided in the $data array from the form
            $password = $data['password'];  // Retrieve password from the form data
    
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
            // Create the user ID by appending 'p' to the NIC
            $id = $data['nic'] . 'p';
        }
    
        // Build the SQL query using the provided data
        $query = "
            INSERT INTO `user_profile` 
            (`id`, `username`, `password`, `role`) 
            VALUES (
                '{$id}', 
                '{$data['first_name']}',
                '{$hashedPassword}',  // Use the hashed password
                '{$role}'
            )
        ";
    
        // Execute the query
        return $this->query($query);
    }
    
}