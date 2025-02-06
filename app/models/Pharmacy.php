<?php


//user class

class Pharmacy extends Model
{

    protected $table = 'pharmacist';

    protected $allowedColumns = [

        'id',
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
          if (!empty($pharmacistData['nic']) && !preg_match('/^\d{12}$/', $pharmacistData['nic'])) {
              $this->errors[] = 'Invalid NIC format. It must be 12 digits.';
          }

          // Validate email format manually
          if (!empty($pharmacistData['email']) && !preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $pharmacistData['email'])) {
              $this->errors[] = 'Invalid email address.';
          }


          // Validate contact number (10 digits)
          if (!empty($pharmacistData['contact']) && !preg_match('/^\d{10}$/', $pharmacistData['contact'])) {
              $this->errors[] = 'Invalid contact number. It must be 10 digits.';
          }

          // Validate emergency contact number (10 digits)
          if (!empty($pharmacistData['emergency_contact_no']) && !preg_match('/^\d{10}$/', $pharmacistData['emergency_contact_no'])) {
              $this->errors[] = 'Invalid emergency contact number. It must be 10 digits.';
          }

          // Validate date of birth (must be a past date)
          if (!empty($pharmacistData['dob'])) {
              $dob = strtotime($pharmacistData['dob']);
              if (!$dob || $dob >= time()) {
                  $this->errors[] = 'Invalid date of birth. Please select a valid past date.';
              }
          }
      }

      if ($step === 2) {
          // Validate years of experience as a positive integer
          if (!empty($pharmacistData['experience']) && (!is_numeric($pharmacistData['experience']) || $pharmacistData['experience'] < 0)) {
              $this->errors[] = 'Years of experience must be a positive number.';
          }
      }

      // Return true if no errors, false otherwise
      return empty($this->errors);
   }   

   public function addPharmacist($data)
   {
      // Calculate the age based on the date of birth
      $data['age'] = $this->calculateAge($data['dob']);
      $pharm_pw = 'pharm123';

      // Build the SQL query using the provided data
      $query = "
         INSERT INTO `pharmacist` 
         (`id`, `nic`, `password`, `first_name`, `last_name`, `dob`, `age`, `gender`, `address`, `email`, `contact`, `emergency_contact_no`, `medical_license_no`, `experience`, `qualifications`, `prev_employment_history`) 
            VALUES (
               '{$data['nic']}', 
               '{$data['nic']}',
               '{$pharm_pw}', 
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
               '{$data['experience']}', 
               '{$data['qualifications']}', 
               '{$data['prev_employment_history']}'
            );
         ";

      // Debug the query
      echo("Generated Query: <pre>$query</pre>");

      // Execute the query
      return $this->query($query);

   }

   public function getAllPharmacists()
    {
        $query = "
            SELECT 
                nic, 
                CONCAT(first_name, ' ', last_name) AS name, 
                age, 
                contact
            FROM pharmacist
        ";
        return $this->query($query); // Use the query method to execute and fetch data
    }

    public function getPharmacistById($nic)
    {
        $query = "SELECT * FROM pharmacist WHERE nic = :nic";
        $data = ['nic' => $nic]; // Bind the NIC parameter

        $result = $this->query($query, $data); // Execute query with binding
        return $result ? $result[0] : null; // Return the first result (single doctor) or null if not found
    }

    public function validatePharmacist($pharmacistData)
    {
        $this->errors = [];

        $requiredFields = [
            'nic', 'first_name', 'last_name', 'dob', 
            'address', 'email', 'contact', 'emergency_contact_no', 
            'medical_license_no', 'experience', 'qualifications', 'prev_employment_history'
        ];

        // Validate NIC format (12 digits)
        if (!empty($pharmacistData['nic']) && !preg_match('/^\d{12}$/', $pharmacistData['nic'])) {
            $this->errors[] = 'Invalid NIC format. It must be 12 digits.';
        }

        // Validate email format manually
        if (!empty($pharmacistData['email']) && !preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $pharmacistData['email'])) {
            $this->errors[] = 'Invalid email address.';
        }


        // Validate contact number (10 digits)
        if (!empty($pharmacistData['contact']) && !preg_match('/^\d{10}$/', $pharmacistData['contact'])) {
            $this->errors[] = 'Invalid contact number. It must be 10 digits.';
        }

        // Validate emergency contact number (10 digits)
        if (!empty($pharmacistData['emergency_contact']) && !preg_match('/^\d{10}$/', $pharmacistData['emergency_contact'])) {
            $this->errors[] = 'Invalid emergency contact number. It must be 10 digits.';
        }

        // Validate date of birth (must be a past date)
        if (!empty($pharmacistData['dob'])) {
            $dob = strtotime($pharmacistData['dob']);
            if (!$dob || $dob >= time()) {
                $this->errors[] = 'Invalid date of birth. Please select a valid past date.';
            }
        }

        // Validate years of experience as a positive integer
        if (!empty($pharmacistData['experience']) && (!is_numeric($pharmacistData['experience']) || $pharmacistData['experience'] < 0)) {
            $this->errors[] = 'Years of experience must be a positive number.';
        }

        // Return true if no errors, false otherwise
        return empty($this->errors);
    }


    public function getErrors()
    {
        return $this->errors;
    }

    public function updatePharmacist($data, $old_nic)
    {
        // Calculate the age based on the date of birth
        $data['age'] = $this->calculateAge($data['dob']);

        // SQL query with positional placeholders
        $query = "
        UPDATE `pharmacist` SET
            `id` = ?,
            `nic` = ?, 
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
            `experience` = ?, 
            `qualifications` = ?, 
            `prev_employment_history` = ?
        WHERE `nic` = ?";

        // Parameters array
        $params = [
            $data['nic'],
            $data['nic'],
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
            $data['experience'],
            $data['qualifications'],
            $data['prev_employment_hsitory'],
            $old_nic // NIC for the WHERE condition
        ];

        // Execute the query with parameter binding
        return $this->query($query, $params);

    }

    public function deletePharmacist($nic)
    {
        $query = "DELETE FROM pharmacist WHERE nic = :nic";
        $data = ['nic' => $nic];
        return $this->query($query, $data);
    }

}
