<?php

class Doctor extends Model
{

    protected $table = 'doctor';

    protected $allowedColumns = [

        'id',
        //'password',
        'nic',
        'first_name',
        'last_name',
        'dob',
        'age',
        'gender',
        'address',
        'email',
        'contact',
        'emergency_contact',
        'emergency_contact_relationship',
        'medical_license_no',
        'specialization',
        'experience',
        'qualifications',
        'medical_school',
    ];

    public function addDoctor($data)
    {
        // Calculate the age based on the date of birth
        $data['age'] = $this->calculateAge($data['dob']);

        // Build the SQL query using the provided data
        $query = "
            INSERT INTO `doctor` 
            (`id`, `nic`, `first_name`, `last_name`, `dob`, `age`, `gender`, `address`, `email`, `contact`, `emergency_contact`, `emergency_contact_relationship`, `medical_license_no`, `specialization`, `experience`, `qualifications`, `medical_school`) 
            VALUES (
                '{$data['nic']}', 
                '{$data['nic']}', 
                '{$data['first_name']}', 
                '{$data['last_name']}', 
                '{$data['dob']}', 
                '{$data['age']}', 
                '{$data['gender']}', 
                '{$data['address']}', 
                '{$data['email']}', 
                '{$data['contact']}', 
                '{$data['emergency_contact']}', 
                '{$data['emergency_contact_relationship']}', 
                '{$data['medical_license_no']}', 
                '{$data['specialization']}', 
                '{$data['experience']}', 
                '{$data['qualifications']}', 
                '{$data['medical_school']}'
            )
        ";

        // Debug the query
        echo "Generated Query: <pre>$query</pre>";

        // Execute the query
        return $this->query($query);
    }

    private function calculateAge($dob)
    {
        $dobDate = new DateTime($dob);
        $currentDate = new DateTime();
        return $dobDate->diff($currentDate)->y;
    }

    public function validate($doctorData)
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

        // Validate contact number (10 digits)
        if (!empty($doctorData['contact']) && !preg_match('/^\d{10}$/', $doctorData['contact'])) {
            $this->errors[] = 'Invalid contact number. It must be 10 digits.';
        }

        // Validate emergency contact number (10 digits)
        if (!empty($doctorData['emergency_contact']) && !preg_match('/^\d{10}$/', $doctorData['emergency_contact'])) {
            $this->errors[] = 'Invalid emergency contact number. It must be 10 digits.';
        }

        // Validate date of birth (must be a past date)
        if (!empty($doctorData['dob'])) {
            $dob = strtotime($doctorData['dob']);
            if (!$dob || $dob >= time()) {
                $this->errors[] = 'Invalid date of birth. Please select a valid past date.';
            }
        }

        // Validate years of experience as a positive integer
        if (!empty($doctorData['experience']) && (!is_numeric($doctorData['experience']) || $doctorData['experience'] < 0)) {
            $this->errors[] = 'Years of experience must be a positive number.';
        }

        // Return true if no errors, false otherwise
        return empty($this->errors);
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
