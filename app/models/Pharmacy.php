<?php


//user class

class Pharmacy extends Model
{

   protected $table = 'pharmacist';

   protected $allowedColumns = [

      'nic',
      'password',
      'id',
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

}
