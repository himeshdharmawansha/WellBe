<?php
class checkValues extends Model
{
    public function check($nicID, $email, $table)
    {
        $sql = "SELECT * FROM $table WHERE nic=? AND email=?";
        $check = $this->query($sql, [$nicID, $email]);

        if (is_array($check) && count($check) > 0) {
            // Handle array of stdClass objects
            return ['found' => 'true', 'pass' => $check[0]->password];
        }
        
        return ['found' => 'false'];
    }

    public function updatePassword($nicID, $table, $hashedPassword)
    {
        $sql = "UPDATE $table SET password=? WHERE nic=?";
        return $this->write($sql, [$hashedPassword, $nicID]);
    }
}