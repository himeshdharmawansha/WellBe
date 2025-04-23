<?php
class checkValues extends Model
{
    public function check($email)
    {
        $sql = "SELECT * FROM patient WHERE email=?";
        $check = $this->query($sql, [$email]);

        if (is_array($check) && count($check) > 0) {
            // Handle array of stdClass objects
            return ['found' => 'true', 'pass' => $check[0]->password];
        }
        
        return ['found' => 'false'];
    }

    public function updatePassword($email, $hashedPassword)
    {
        $sql = "UPDATE patient SET password=? WHERE email=?";
        return $this->write($sql, [$hashedPassword, $email]);
    }
}