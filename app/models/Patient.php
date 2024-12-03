<?php

//user class

class Patient extends Model
{
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
