 <?php

class Doctor extends Model
{
    // public $errors = [];
    protected $table = 'doctor';

    protected $allowedColumns = [

        'password',
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
        //$doc_pw = 'doc123';

        //hash password
        $doc_pw = password_hash('doc123', PASSWORD_DEFAULT);
        $id = $data['nic'] . 'd';

        // Build the SQL query using the provided data
        $query = "
            INSERT INTO `doctor` 
            (`nic`, `password`, `first_name`, `last_name`, `dob`, `age`, `gender`, `address`, `email`, `contact`, `emergency_contact`, `emergency_contact_relationship`, `medical_license_no`, `specialization`, `experience`, `qualifications`, `medical_school`, `user_id`) 
            VALUES ( 
                '{$id}',
                '{$doc_pw}', 
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
                '{$data['medical_school']}',
                '{$id}'
            )
        ";

        // Debug the query
        echo("Generated Query: <pre>$query</pre>");

        // Execute the query
        return $this->query($query);

    }

    private function calculateAge($dob)
    {
        $dobDate = new DateTime($dob);
        $currentDate = new DateTime();
        return $dobDate->diff($currentDate)->y;
    }

    private function checkNIC($nic){
        $query = "SELECT COUNT(*) AS count FROM doctor WHERE nic LIKE :nic";
        $data = ['nic' => "%$nic%"];

        return $this->query($query, $data);
    }

    public function validate($doctorData, $step = 1)
    {
        $this->errors = [];

        if ($step === 1) {
            // Required fields for doctorForm1
            $requiredFields = [
                'nic', 'first_name', 'last_name', 'dob', 
                'address', 'email', 'contact', 'emergency_contact', 
                'emergency_contact_relationship'
            ];
        } else {
            // Required fields for doctorForm2
            $requiredFields = [
                'medical_license_no', 'specialization', 
                'experience', 'qualifications', 'medical_school'
            ];
        }

        // Step-specific validations
        if ($step === 1) {
            // Validate NIC format (12 digits)
            if (!empty($doctorData['nic']) && !preg_match('/^(\d{12}|\d{9}[vV])$/', $doctorData['nic'])) {
                $this->errors[] = 'Invalid NIC format. It must be 12 digits or 9 digits followed by "V" or "v".';
            }else{
                $result = $this->checkNIC($doctorData['nic']);
                if($result && $result[0]->count > 0){
                    $this->errors[] = 'This NIC is already registered.';
                }
            }

            // Validate email format manually
            if (!empty($doctorData['email']) && !preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $doctorData['email'])) {
                $this->errors[] = 'Invalid email address.';
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
        }

        if ($step === 2) {
            // Validate years of experience as a positive integer
            if (!empty($doctorData['experience']) && (!is_numeric($doctorData['experience']) || $doctorData['experience'] < 0)) {
                $this->errors[] = 'Years of experience must be a positive number.';
            }
        }

        // Return true if no errors, false otherwise
        return empty($this->errors);
    }

    public function validateDoctor($doctorData)
    {
        $this->errors = [];

        $requiredFields = [
            'nic', 'first_name', 'last_name', 'dob', 
            'address', 'email', 'contact', 'emergency_contact', 
            'emergency_contact_relationship', 'medical_license_no', 'specialization', 
            'experience', 'qualifications', 'medical_school'
        ];

        // Validate NIC format (12 digits)
        if (!empty($doctorData['nic']) && !preg_match('/^\d{12}$/', $doctorData['nic'])) {
            $this->errors[] = 'Invalid NIC format. It must be 12 digits.';
        }

        // Validate email format manually
        if (!empty($doctorData['email']) && !preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $doctorData['email'])) {
            $this->errors[] = 'Invalid email address.';
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


    public function getErrors()
    {
        return $this->errors;
    }

    public function getAllDoctors()
    {
        $query = "
            SELECT 
                nic, 
                CONCAT(first_name, ' ', last_name) AS name, 
                age,
                specialization, 
                contact
            FROM doctor
            WHERE account_state = 'Active'
        ";
        return $this->query($query); // Use the query method to execute and fetch data
    }

    public function getDoctorById($nic)
    {
        $query = "SELECT * FROM doctor WHERE nic = :nic";
        $data = ['nic' => $nic]; // Bind the NIC parameter

        $result = $this->query($query, $data); // Execute query with binding
        return $result ? $result[0] : null; // Return the first result (single doctor) or null if not found
    }

    public function updateDoctor($data, $old_nic)
    {
        // Calculate the age based on the date of birth
        $data['age'] = $this->calculateAge($data['dob']);
        //$new_nic = $data['nic'] . 'd';

        // SQL query with positional placeholders
        $query = "
        UPDATE `doctor` SET
            `first_name` = ?, 
            `last_name` = ?, 
            `dob` = ?,
            `age` = ?, 
            `gender` = ?, 
            `address` = ?, 
            `email` = ?, 
            `contact` = ?, 
            `emergency_contact` = ?, 
            `emergency_contact_relationship` = ?, 
            `medical_license_no` = ?, 
            `specialization` = ?, 
            `experience` = ?, 
            `qualifications` = ?, 
            `medical_school` = ?
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
            $data['emergency_contact'],
            $data['emergency_contact_relationship'],
            $data['medical_license_no'],
            $data['specialization'],
            $data['experience'],
            $data['qualifications'],
            $data['medical_school'],
            $old_nic // NIC for the WHERE condition
        ];

        // Debug the query
        // echo("Generated Query: <pre>$query</pre>");
        // echo(print_r($params, true));

        // Execute the query with parameter binding
        return $this->query($query, $params);

    }

    public function deleteDoctor($nic)
    {
        $query = "
        UPDATE doctor
        SET account_state = 'Deleted'
        WHERE nic = :nic
        ";

        //$query = "DELETE FROM doctor WHERE nic = :nic";
        $data = ['nic' => $nic];
        return $this->query($query, $data);
    }

    public function totalDoctors(){
        $query = "
        SELECT 
            COUNT(id) as totalDoctors
        FROM 
            doctor 
        WHERE 
            account_state = 'Active'
        ";

        $result = $this->query($query);
        return $result[0]->totalDoctors ?? 0;
        //return $this->query($query);
    }


    public function getDocname()
    {
        $query = "SELECT  CONCAT(first_name, ' ', last_name) AS name FROM doctor";
        return $this->query($query);
    }

    public function getDoctorsWithSpecializations()
    {
        $query = "SELECT CONCAT(first_name, ' ', last_name) AS name, specialization FROM doctor";
        return $this->query($query);
    }

    public function getFeesByDoctorId($doctorId)
    {
        // SQL query to fetch fees based on doctor ID
        $query = "SELECT fees FROM doctor WHERE id = :doctorId";
        
        // Parameters array
        $data = ['doctorId' => $doctorId];
    
        // Execute the query with parameter binding
        return $this->query($query, $data);
    }
    

    public function getDoctorId($firstName,$lastName)
    {
        $query = "SELECT id FROM doctor WHERE first_name = :firstName AND last_name = :lastName";
        $data = ['firstName'=>$firstName,'lastName'=>$lastName];
        return $this->query($query,$data);
    }
    

}






