<?php

//user class

class Patient extends Model
{   

    // public $errors = [];
    protected $table = 'patient';

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
        'medical_history',
        'allergies',
        'emergency_contact_name',
        'emergency_contact_no',
        'emergency_contact_relationship',
        'e_wallet',
        'verified'
        
    ];

    public function validate_first_form($data)
    {
        $this->errors = [];

        if (empty($data['first_name'])) {
            $this->errors['first_name'] = "First Name is required";
        } elseif (!preg_match("/^[a-zA-Z\s]+$/", $data['first_name'])) {
            $this->errors['first_name'] = "First Name must contain only letters and spaces";
        }



        if (empty($data['last_name'])) {
            $this->errors['last_name'] = "Last Name is required";
        } elseif (!preg_match("/^[a-zA-Z\s]+$/", $data['last_name'])) {
            $this->errors['last_name'] = "Last Name must contain only letters and spaces";
        }

        if (empty($data['nic'])) {
            $this->errors['nic'] = "NIC is required";
        } elseif (
            !preg_match("/^([0-9]{9}[vVxX]|[0-9]{12})$/", $data['nic'])
        ) {
            $this->errors['nic'] = "Invalid NIC format. Use 9 digits followed by V/X or 12 digits.";
        }
        
        if (empty($data['dob'])) {
            $this->errors['dob'] = "Date of Birth is required";
        } else {
            $dob = new DateTime($data['dob']);
            $currentDate = new DateTime();
        
            if ($dob > $currentDate) {
                $this->errors['dob'] = "Invalid Date of Birth";
            } else {
                $age = $currentDate->diff($dob)->y;
        
                if ($age < 16) {
                    $this->errors['dob'] = "You must be at least 16 years old";
                }
            }
        }
        
        

        if (!empty($data['age']) && !is_numeric($data['age'])) {
            $this->errors['age'] = "Age must be a number";
        }

        if (empty($data['gender'])) {
            $this->errors['gender'] = "Gender is required";
        } elseif (!in_array($data['gender'], ['M', 'F'])) {
            $this->errors['gender'] = "Gender must be either M or F";
        }

        if (empty($data['address'])) {
            $this->errors['address'] = "Address is required";
        }

        if (empty($data['email'])) {
            $this->errors['email'] = "Email Address is required";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = "Invalid Email Address";
        }

        if (empty($data['contact'])) {
            $this->errors['contact'] = "Contact Number is required";
        } elseif (!preg_match("/^[0-9]{10}$/", $data['contact'])) {
            $this->errors['contact'] = "Contact Number must be 10 digits";
        }


        return empty($this->errors);
    }

    public function validate_second_form($data){

        $this->errors = [];

        if (empty($data['emergency_contact_name'])) {
            $this->errors['emergency_contact_name'] = "Emergency Contact Name is required";
        } elseif (!preg_match("/^[a-zA-Z\s]+$/", $data['emergency_contact_name'])) {
            $this->errors['emergency_contact_name'] = "Emergency Contact Name must contain only letters and spaces";
        }


        if (empty($data['emergency_contact_no'])) {
            $this->errors['emergency_contact_no'] = "Emergency Contact Number is required";
        } elseif (!preg_match("/^[0-9]{10}$/", $data['emergency_contact_no'])) {
            $this->errors['emergency_contact_no'] = "Emergency Contact Number must be 10 digits";
        }

        if (empty($data['emergency_contact_relationship'])) {
            $this->errors['emergency_contact_relationship'] = "Emergency Contact Relationship is required";
        }

        return empty($this->errors);
    }



    private function calculateAge($dob)
    {
        $dobDate = new DateTime($dob);
        $currentDate = new DateTime();
        return $dobDate->diff($currentDate)->y;
    }
    
    private function checkNIC($nic){
        $query = "SELECT COUNT(*) AS count FROM patient WHERE nic LIKE :nic";
        $data = ['nic' => "%$nic%"];

        return $this->query($query, $data);
    }

    public function validate($patientData, $step = 1)
    {
        $this->errors = [];

        if ($step === 1) {
            $requiredFields = [
                'nic', 'first_name', 'last_name', 'dob', 
                'address', 'email', 'contact'
            ];
        } else {
            $requiredFields = [
                'medical_history', 'allergies', 
                'emergency_contact_name', 'emergency_contact_no', 'emergency_contact_relationship'
            ];
        }

        if ($step === 1) {
            if (!empty($patientData['nic']) && !preg_match('/^(\d{12}|\d{9}[vV])$/', $patientData['nic'])) {
                $this->errors[] = 'Invalid NIC format. It must be 12 digits or 9 digits followed by "V" or "v".';
            }else{
                $result = $this->checkNIC($patientData['nic']);
                if($result && $result[0]->count > 0){
                    $this->errors[] = 'This NIC is already registered.';
                }
            }

            if (!empty($patientData['email']) && !preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $patientData['email'])) {
                $this->errors[] = 'Invalid email address.';
            }

            if (!empty($patientData['contact']) && !preg_match('/^\d{10}$/', $patientData['contact'])) {
                $this->errors[] = 'Invalid contact number. It must be 10 digits.';
            }
            
            if (!empty($patientData['dob'])) {
                $dob = strtotime($patientData['dob']);
                if (!$dob || $dob >= time()) {
                    $this->errors[] = 'Invalid date of birth. Please select a valid past date.';
                }
            }
        }

        if ($step === 2) {
            if (!empty($patientData['emergency_contact']) && !preg_match('/^\d{10}$/', $patientData['emergency_contact'])) {
                $this->errors[] = 'Invalid emergency contact number. It must be 10 digits.';
            }

        }

        return empty($this->errors);
    }

    public function addPatient($data)
    {
        $data['age'] = $this->calculateAge($data['dob']);

        $patient_pw = password_hash('patient123', PASSWORD_DEFAULT);
        $id = $data['nic'] . 'p';

        $query = "
            INSERT INTO `patient` 
            (`nic`, `password`, `first_name`, `last_name`, `dob`, `age`, `gender`, `address`, `email`, `contact`, `medical_history`, `allergies`, `emergency_contact_name`, `emergency_contact_no`, `emergency_contact_relationship`, `user_id`) 
            VALUES ( 
                '{$id}',
                '{$patient_pw}', 
                '{$data['first_name']}', 
                '{$data['last_name']}', 
                '{$data['dob']}', 
                '{$data['age']}', 
                '{$data['gender']}', 
                '{$data['address']}', 
                '{$data['email']}', 
                '{$data['contact']}', 
                '{$data['medical_history']}', 
                '{$data['allergies']}', 
                '{$data['emergency_contact_name']}', 
                '{$data['emergency_contact_no']}', 
                '{$data['emergency_contact_relationship']}',
                '{$id}'
            )
        ";

        return $this->query($query);

    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function addToUserProfile($data){

        $query = "INSERT INTO user_profile (id,username,password,role,image,state) VALUES (?, ?, ?, ?, ?, ?);";

        $this ->query($query,[$data['nic'], $data['first_name'], $data['password'], 4, 'Profile_default.png', 0]);
    }

    public function getAllPatients()
    {
        $query = "
            SELECT 
                nic, 
                CONCAT(first_name, ' ', last_name) AS name, 
                age, 
                contact
            FROM patient
            WHERE account_state = 'Active'
        ";
        return $this->query($query); 
    }

    public function getPatientById($nic)
    {
        $query = "SELECT * FROM patient WHERE nic = :nic";
        $data = ['nic' => $nic]; 

        $result = $this->query($query, $data); 
        return $result ? $result[0] : null; 
    }

    public function validatePatient($patientData)
    {
        $this->errors = [];

        $requiredFields = [
            'nic', 'first_name', 'last_name', 'dob', 
            'address', 'email', 'contact', 'medical_history', 
            'allergies', 'emergency_contact_name', 'emergency_contact_no', 
            'emergency_contact_relationship'
        ];

        if (!empty($patientData['nic']) && !preg_match('/^\d{12}$/', $patientData['nic'])) {
            $this->errors[] = 'Invalid NIC format. It must be 12 digits.';
        }

        if (!empty($patientData['email']) && !preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $patientData['email'])) {
            $this->errors[] = 'Invalid email address.';
        }

        if (!empty($patientData['contact']) && !preg_match('/^\d{10}$/', $patientData['contact'])) {
            $this->errors[] = 'Invalid contact number. It must be 10 digits.';
        }

        if (!empty($patientData['emergency_contact_no']) && !preg_match('/^\d{10}$/', $patientData['emergency_contact_no'])) {
            $this->errors[] = 'Invalid emergency contact number. It must be 10 digits.';
        }

        if (!empty($patientData['dob'])) {
            $dob = strtotime($patientData['dob']);
            if (!$dob || $dob >= time()) {
                $this->errors[] = 'Invalid date of birth. Please select a valid past date.';
            }
        }

        return empty($this->errors);
    }

    public function updatePatient($data, $old_nic)
    {
        $data['age'] = $this->calculateAge($data['dob']);

        $query = "
        UPDATE `patient` SET 
            `first_name` = ?, 
            `last_name` = ?, 
            `dob` = ?,
            `age` = ?, 
            `gender` = ?, 
            `address` = ?, 
            `email` = ?, 
            `contact` = ?, 
            `medical_history` = ?, 
            `allergies` = ?,
            `emergency_contact_name` = ?,
            `emergency_contact_no` = ?, 
            `emergency_contact_relationship` = ?
        WHERE `nic` = ?";

        $params = [
            $data['first_name'],
            $data['last_name'],
            $data['dob'],
            $data['age'],
            $data['gender'],
            $data['address'],
            $data['email'],
            $data['contact'],
            $data['medical_history'],
            $data['allergies'],
            $data['emergency_contact_name'],
            $data['emergency_contact_no'],
            $data['emergency_contact_relationship'],
            $old_nic 
        ];

        return $this->query($query, $params);

    }

    public function deletePatient($nic)
    {
        $query = "
        UPDATE patient
        SET account_state = 'Deleted'
        WHERE nic = :nic
        ";

        $data = ['nic' => $nic];
        return $this->query($query, $data);
    }

    public function filterPatients($startAge, $endAge, $gender, $location)
    {
        $query = "SELECT age, COUNT(*) as count FROM $this->table WHERE 1";

        $params = [];

        if (!empty($startAge)) {
            $query .= " AND age >= :startAge";
            $params['startAge'] = $startAge;
        }

        if (!empty($endAge)) {
            $query .= " AND age <= :endAge";
            $params['endAge'] = $endAge;
        }

        if (!empty($gender) && $gender !== "All") {
            $query .= " AND gender = :gender";
            $params['gender'] = $gender;
        }

        if (!empty($location) && $location !== "All") {
            $query .= " AND address LIKE :location";
            $params['location'] = "%$location%";
        }

        $query .= " GROUP BY age";

        error_log("Generated query: " .$query);
        return $this->query($query, $params);
    }


    public function totalPatients(){
        $query = "
        SELECT 
            COUNT(id) as totalPatients
        FROM 
            patient 
        WHERE 
            account_state = 'Active'
        ";

        $result = $this->query($query);
        return $result[0]->totalPatients ?? 0;
    }

    public function getPatientID($nic)
    {
        $query ="SELECT id FROM patient WHERE nic = :nic";
        $id = $nic . 'p';
        $data = ['nic' => $id];

        error_log("Generated query: " . $query);
        error_log("Patient NIC = " . $id);
        $result = $this->query($query, $data);
        return $result[0]->id;
    }



}

