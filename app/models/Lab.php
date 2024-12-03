<?php


//user class

class Lab extends Model
{

   protected $table = 'lab_technicia';

   protected $allowedColumns = [

      'nic',
      'password',
   ];

   public function validate($data)
   {
      $this->errors = [];

      if (empty($data['nic'])) {
         $this->errors['nic'] = "Username is required";
      }

      if (empty($data['password'])) {
         $this->errors['password'] = "Password is required";
      }


      if (empty($this->errors)) {
         return true;
      } else {
         return false;
      }
   }
   public function loggedin()
   {
      $DB = new Database();
      // Update user state to 1 (logged in)
      $updateStateQuery = "UPDATE user_profile SET state = 1 WHERE id = :userid";
      $DB->write($updateStateQuery, ['userid' => $_SESSION['userid']]);

      // Update messages as received
      $updateQuery = "UPDATE message SET received = 1 WHERE receiver = :receiver AND received = 0";
      $DB->write($updateQuery, ['receiver' => $_SESSION['userid']]);
   }
}
