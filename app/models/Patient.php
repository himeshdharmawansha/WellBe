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
    ];

    public function validate_first_form($data)
    {
        $this->errors = [];

        // Validate full name
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

        // Validate date of birth and age
        if (empty($data['dob'])) {
            $this->errors['dob'] = "Date of Birth is required";
        }

        if (!empty($data['age']) && !is_numeric($data['age'])) {
            $this->errors['age'] = "Age must be a number";
        }

        // Validate gender
        if (empty($data['gender'])) {
            $this->errors['gender'] = "Gender is required";
        } elseif (!in_array($data['gender'], ['M', 'F'])) {
            $this->errors['gender'] = "Gender must be either M or F";
        }

        // Validate address
        if (empty($data['address'])) {
            $this->errors['address'] = "Address is required";
        }

        // Validate email
        if (empty($data['email'])) {
            $this->errors['email'] = "Email Address is required";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = "Invalid Email Address";
        }

        // Validate contact number
        if (empty($data['contact'])) {
            $this->errors['contact'] = "Contact Number is required";
        } elseif (!preg_match("/^[0-9]{10}$/", $data['contact'])) {
            $this->errors['contact'] = "Contact Number must be 10 digits";
        }


        return empty($this->errors);
    }

    public function validate_second_form($data){

        $this->errors = [];

        // Validate medical history
        if (empty($data['medical_history'])) {
            $this->errors['medical_history'] = "Medical History is required";
        }

        // Validate allergies
        if (empty($data['allergies'])) {
            $this->errors['allergies'] = "Allergies field is required";
        }

        // Validate emergency contact name
        if (empty($data['emergency_contact_name'])) {
            $this->errors['emergency_contact_name'] = "Emergency Contact Name is required";
        } elseif (!preg_match("/^[a-zA-Z\s]+$/", $data['emergency_contact_name'])) {
            $this->errors['emergency_contact_name'] = "Emergency Contact Name must contain only letters and spaces";
        }

        // Validate emergency contact number
        if (empty($data['emergency_contact_no'])) {
            $this->errors['emergency_contact_no'] = "Emergency Contact Number is required";
        } elseif (!preg_match("/^[0-9]{10}$/", $data['emergency_contact_no'])) {
            $this->errors['emergency_contact_no'] = "Emergency Contact Number must be 10 digits";
        }

        // Validate emergency contact relationship
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

    public function validate($patientData, $step = 1)
    {
        $this->errors = [];

        if ($step === 1) {
            // Required fields for doctorForm1
            $requiredFields = [
                'nic', 'first_name', 'last_name', 'dob', 
                'address', 'email', 'contact'
            ];
        } else {
            // Required fields for doctorForm2
            $requiredFields = [
                'medical_history', 'allergies', 
                'emergency_contact_name', 'emergency_contact_no', 'emergency_contact_relationship'
            ];
        }

        // Step-specific validations
        if ($step === 1) {
            // Validate NIC format (12 digits)
            if (!empty($patientData['nic']) && !preg_match('/^\d{12}$/', $patientData['nic'])) {
                $this->errors[] = 'Invalid NIC format. It must be 12 digits.';
            }

            // Validate email format manually
            if (!empty($patientData['email']) && !preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $patientData['email'])) {
                $this->errors[] = 'Invalid email address.';
            }


            // Validate contact number (10 digits)
            if (!empty($patientData['contact']) && !preg_match('/^\d{10}$/', $patientData['contact'])) {
                $this->errors[] = 'Invalid contact number. It must be 10 digits.';
            }

            
            // Validate date of birth (must be a past date)
            if (!empty($patientData['dob'])) {
                $dob = strtotime($patientData['dob']);
                if (!$dob || $dob >= time()) {
                    $this->errors[] = 'Invalid date of birth. Please select a valid past date.';
                }
            }
        }

        if ($step === 2) {
            // Validate emergency contact number (10 digits)
            if (!empty($patientData['emergency_contact']) && !preg_match('/^\d{10}$/', $patientData['emergency_contact'])) {
                $this->errors[] = 'Invalid emergency contact number. It must be 10 digits.';
            }

        }

        return empty($this->errors);
    }

    public function addPatient($data)
    {
        // Calculate the age based on the date of birth
        $data['age'] = $this->calculateAge($data['dob']);
        $patient_pw = 'patient123';
        $id = $data['nic'] . 'p';

        // Build the SQL query using the provided data
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

        // Debug the query
        //echo "Generated Query: <pre>$query</pre>";

        // Execute the query
        return $this->query($query);

    }

    public function getErrors()
    {
        return $this->errors;
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
        ";
        return $this->query($query); // Use the query method to execute and fetch data
    }

    public function getPatientById($nic)
    {
        $query = "SELECT * FROM patient WHERE nic = :nic";
        $data = ['nic' => $nic]; // Bind the NIC parameter

        $result = $this->query($query, $data); // Execute query with binding
        return $result ? $result[0] : null; // Return the first result (single doctor) or null if not found
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

        // Validate NIC format (12 digits)
        if (!empty($patientData['nic']) && !preg_match('/^\d{12}$/', $patientData['nic'])) {
            $this->errors[] = 'Invalid NIC format. It must be 12 digits.';
        }

        // Validate email format manually
        if (!empty($patientData['email']) && !preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $patientData['email'])) {
            $this->errors[] = 'Invalid email address.';
        }


        // Validate contact number (10 digits)
        if (!empty($patientData['contact']) && !preg_match('/^\d{10}$/', $patientData['contact'])) {
            $this->errors[] = 'Invalid contact number. It must be 10 digits.';
        }

        // Validate emergency contact number (10 digits)
        if (!empty($patientData['emergency_contact_no']) && !preg_match('/^\d{10}$/', $patientData['emergency_contact_no'])) {
            $this->errors[] = 'Invalid emergency contact number. It must be 10 digits.';
        }

        // Validate date of birth (must be a past date)
        if (!empty($patientData['dob'])) {
            $dob = strtotime($patientData['dob']);
            if (!$dob || $dob >= time()) {
                $this->errors[] = 'Invalid date of birth. Please select a valid past date.';
            }
        }

        // Return true if no errors, false otherwise
        return empty($this->errors);
    }

    public function updatePatient($data, $old_nic)
    {
        // Calculate the age based on the date of birth
        $data['age'] = $this->calculateAge($data['dob']);

        // SQL query with positional placeholders
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
            $data['medical_history'],
            $data['allergies'],
            $data['emergency_contact_name'],
            $data['emergency_contact_no'],
            $data['emergency_contact_relationship'],
            $old_nic // NIC for the WHERE condition
        ];

        // Debug the query
        //echo "Generated Query: <pre>$query</pre>";

        // Execute the query with parameter binding
        return $this->query($query, $params);

    }

    public function deletePatient($nic)
    {
        $query = "DELETE FROM patient WHERE nic = :nic";
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
        //return $this->query($query);
    }

}
