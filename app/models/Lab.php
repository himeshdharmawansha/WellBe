<?php


//user class

class Lab extends Model
{

   protected $table = 'lab_technician';

   protected $allowedColumns = [

      'nic',
      'password',
      'first_name',
      'last_name',
      'dob',
      'age',
      'gender',
      'address',
      'email',
      'contact',
      'emergency_contact_no',
      'medical_license_no',
      'specialization',
      'experience',
      'qualifications',
      'prev_employment_history',
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

   private function calculateAge($dob)
   {
         $dobDate = new DateTime($dob);
         $currentDate = new DateTime();
         return $dobDate->diff($currentDate)->y;
   }

   private function checkNIC($nic)
   {
        $query = "SELECT COUNT(*) AS count FROM lab_technician WHERE nic LIKE :nic";
        $data = ['nic' => "%$nic%"];

        return $this->query($query, $data);
    }

   public function formValidate($labTechData, $step = 1)
   {
      $this->errors = [];

      if ($step === 1) {
          // Required fields for doctorForm1
          $requiredFields = [
              'nic', 'first_name', 'last_name', 'dob', 
              'address', 'email', 'contact', 'emergency_contact_no'
          ];
      } else {
          // Required fields for doctorForm2
          $requiredFields = [
              'medical_license_no', 'specialization', 
              'experience', 'qualifications', 'prev_employment_history'
          ];
      }

      // Step-specific validations
      if ($step === 1) {
          // Validate NIC format (12 digits)
          if (!empty($labTechData['nic']) && !preg_match('/^(\d{12}|\d{9}[vV])$/', $labTechData['nic'])) {
              $this->errors[] = 'Invalid NIC format. It must be 12 digits or 9 digits followed by "V" or "v".';
          }else{
            $result = $this->checkNIC($labTechData['nic']);
            if($result && $result[0]->count > 0){
                $this->errors[] = 'This NIC is already registered.';
            }
        }

          // Validate email format manually
          if (!empty($labTechData['email']) && !preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $labTechData['email'])) {
              $this->errors[] = 'Invalid email address.';
          }


          // Validate contact number (10 digits)
          if (!empty($labTechData['contact']) && !preg_match('/^\d{10}$/', $labTechData['contact'])) {
              $this->errors[] = 'Invalid contact number. It must be 10 digits.';
          }

          // Validate emergency contact number (10 digits)
          if (!empty($labTechData['emergency_contact_no']) && !preg_match('/^\d{10}$/', $labTechData['emergency_contact_no'])) {
              $this->errors[] = 'Invalid emergency contact number. It must be 10 digits.';
          }

          // Validate date of birth (must be a past date)
          if (!empty($labTechData['dob'])) {
              $dob = strtotime($labTechData['dob']);
              if (!$dob || $dob >= time()) {
                  $this->errors[] = 'Invalid date of birth. Please select a valid past date.';
              }
          }
      }

      if ($step === 2) {
          // Validate years of experience as a positive integer
          if (!empty($labTechData['experience']) && (!is_numeric($labTechData['experience']) || $labTechData['experience'] < 0)) {
              $this->errors[] = 'Years of experience must be a positive number.';
          }
      }

      // Return true if no errors, false otherwise
      return empty($this->errors);
   }   

   public function addLabTech($data)
   {
      // Calculate the age based on the date of birth
      $data['age'] = $this->calculateAge($data['dob']);
      //$lab_pw = 'lab123';

      //hash password
      $lab_pw = password_hash('lab123', PASSWORD_DEFAULT);
      $id = $data['nic'] . 'l';

      // Build the SQL query using the provided data
      $query = "
         INSERT INTO `lab_technician` 
         (`nic`, `password`, `first_name`, `last_name`, `dob`, `age`, `gender`, `address`, `email`, `contact`, `emergency_contact_no`, `medical_license_no`, `specialization`, `experience`, `qualifications`, `prev_employment_history`, `user_id`) 
            VALUES (
               '{$id}',
               '{$lab_pw}', 
               '{$data['first_name']}', 
               '{$data['last_name']}', 
               '{$data['dob']}', 
               '{$data['age']}', 
               '{$data['gender']}', 
               '{$data['address']}', 
               '{$data['email']}', 
               '{$data['contact']}', 
               '{$data['emergency_contact_no']}', 
               '{$data['medical_license_no']}', 
               '{$data['specialization']}', 
               '{$data['experience']}', 
               '{$data['qualifications']}', 
               '{$data['prev_employment_history']}',
               '{$id}'
            )
         ";

      // Debug the query
      //echo("Generated Query: <pre>$query</pre>");

      // Execute the query
      return $this->query($query);

   }

    public function getAllLabTechs()
    {
        $query = "
            SELECT 
                nic, 
                CONCAT(first_name, ' ', last_name) AS name, 
                age, 
                contact
            FROM lab_technician
            WHERE account_state = 'Active'
        ";
        return $this->query($query); // Use the query method to execute and fetch data
    }

    public function getLabTechById($nic)
    {
        $query = "SELECT * FROM lab_technician WHERE nic = :nic";
        $data = ['nic' => $nic]; // Bind the NIC parameter

        $result = $this->query($query, $data); // Execute query with binding
        return $result ? $result[0] : null; // Return the first result (single doctor) or null if not found
    }

    public function validateLabTech($labTechData)
    {
        $this->errors = [];

        $requiredFields = [
            'nic', 'first_name', 'last_name', 'dob', 
            'address', 'email', 'contact', 'emergency_contact_no', 
            'medical_license_no', 'specialization', 
            'experience', 'qualifications', 'prev_employment_history'
        ];

        // Validate NIC format (12 digits)
        if (!empty($labTechData['nic']) && !preg_match('/^\d{12}$/', $labTechData['nic'])) {
            $this->errors[] = 'Invalid NIC format. It must be 12 digits.';
        }

        // Validate email format manually
        if (!empty($labTechData['email']) && !preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $labTechData['email'])) {
            $this->errors[] = 'Invalid email address.';
        }


        // Validate contact number (10 digits)
        if (!empty($labTechData['contact']) && !preg_match('/^\d{10}$/', $labTechData['contact'])) {
            $this->errors[] = 'Invalid contact number. It must be 10 digits.';
        }

        // Validate emergency contact number (10 digits)
        if (!empty($labTechData['emergency_contact_no']) && !preg_match('/^\d{10}$/', $labTechData['emergency_contact_no'])) {
            $this->errors[] = 'Invalid emergency contact number. It must be 10 digits.';
        }

        // Validate date of birth (must be a past date)
        if (!empty($labTechData['dob'])) {
            $dob = strtotime($labTechData['dob']);
            if (!$dob || $dob >= time()) {
                $this->errors[] = 'Invalid date of birth. Please select a valid past date.';
            }
        }

        // Validate years of experience as a positive integer
        if (!empty($labTechData['experience']) && (!is_numeric($labTechData['experience']) || $labTechData['experience'] < 0)) {
            $this->errors[] = 'Years of experience must be a positive number.';
        }

        // Return true if no errors, false otherwise
        return empty($this->errors);
    }

    public function updateLabTech($data, $old_nic)
    {
        // Calculate the age based on the date of birth
        $data['age'] = $this->calculateAge($data['dob']);

        // SQL query with positional placeholders
        $query = "
        UPDATE `lab_technician` SET
            `first_name` = ?, 
            `last_name` = ?, 
            `dob` = ?,
            `age` = ?, 
            `gender` = ?, 
            `address` = ?, 
            `email` = ?, 
            `contact` = ?, 
            `emergency_contact_no` = ?, 
            `medical_license_no` = ?, 
            `specialization` = ?, 
            `experience` = ?, 
            `qualifications` = ?, 
            `prev_employment_history` = ?
        WHERE `nic` = ?";

        // Parameters array
        $params = [
            $data['first_name'],
            $data['last_name'],
            $data['dob'],
            $data['age'],
            $data['gender'],
            $data['address'],
            $data['email'],
            $data['contact'],
            $data['emergency_contact_no'],
            $data['medical_license_no'],
            $data['specialization'],
            $data['experience'],
            $data['qualifications'],
            $data['prev_employment_history'],
            $old_nic // NIC for the WHERE condition
        ];

        // Execute the query with parameter binding
        return $this->query($query, $params);

    }

    public function deleteLabTech($nic)
    {
        $query = "
        UPDATE lab_technician
        SET account_state = 'Deleted'
        WHERE nic = :nic
        ";

        //$query = "DELETE FROM lab_technician WHERE nic = :nic";
        $data = ['nic' => $nic];
        return $this->query($query, $data);
    }

    public function totalLabTechs(){
        $query = "
        SELECT 
            COUNT(id) as totalLabTechs
        FROM 
            lab_technician 
        WHERE 
            account_state = 'Active'
        ";

        $result = $this->query($query);
        return $result[0]->totalLabTechs ?? 0;
        //return $this->query($query);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
