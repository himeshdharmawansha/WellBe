<?php

//user class

class editProf extends Model
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
        'e_wallet',
    ];

    public function editProfile($id, $data)
    {
        $query = "UPDATE {$this->table} 
                  SET first_name = :first_name,
                      last_name = :last_name,
                      contact = :contact,
                      email = :email,
                      address = :address,
                      medical_history = :medical_history,
                      allergies = :allergies,
                    --   gender = :gender
                  WHERE id = :id";

        $data['id'] = $id;

        return $this->query($query, $data);
    }


    public function getById($id)
    {
        $query = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $data = ['id' => $id];
        return $this->query($query, $data)[0] ?? null;
    }


}