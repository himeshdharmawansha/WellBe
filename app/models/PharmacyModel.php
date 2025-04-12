<?php

class PharmacyModel extends Model
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

        return empty($this->errors);
    }

    public function getRequestCounts()
    {
        $db = new Database();
        $query = "
            SELECT state, COUNT(*) as count 
            FROM medication_requests 
            WHERE date >= NOW() - INTERVAL 14 DAY
            GROUP BY state
        ";
        $results = $db->read($query);
        $response = [
            'pending' => 0,
            'completed' => 0,
        ];
        foreach ($results as $row) {
            if (isset($response[$row['state']])) {
                $response[$row['state']] = (int)$row['count'];
            }
        }
        return $response;
    }

    public function getRequestsByDay()
    {
        $db = new Database();
        $query = "
            SELECT 
                DAYNAME(date) as day, 
                COUNT(*) as count
            FROM medication_requests
            WHERE date >= NOW() - INTERVAL 7 DAY
            GROUP BY DAYNAME(date)
            ORDER BY FIELD(DAYNAME(date), 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')
        ";
        $results = $db->read($query);
        $days = ['Monday' => 0, 'Tuesday' => 0, 'Wednesday' => 0, 'Thursday' => 0, 'Friday' => 0, 'Saturday' => 0, 'Sunday' => 0];
        foreach ($results as $row) {
            $days[$row['day']] = (int)$row['count'];
        }
        return array_values($days);
    }

    public function getMedicationRequests()
    {
        $db = new Database();
        $query = "SELECT patient_id, state 
                  FROM medication_requests 
                  WHERE state IN ('pending') 
                  ORDER BY FIELD(state,'pending') 
                  LIMIT 20";
        return $db->query($query);
    }

    public function getStock()
    {
        $db = new Database();
        $query = "SELECT generic_name, 
                         (CASE 
                             WHEN quantity_in_stock = 0 OR expiry_date < CURDATE() THEN 'Out of Stock' 
                             ELSE 'In Stock' 
                          END) AS state 
                  FROM medicines LIMIT 20";
        return $db->query($query);
    }

    public function searchMedicine($searchTerm)
    {
        try {
            $db = new Database();
            $query = "SELECT generic_name, 
                            (CASE 
                                WHEN quantity_in_stock = 0 OR expiry_date < CURDATE() THEN 'Out of Stock' 
                                ELSE 'In Stock' 
                             END) AS state 
                     FROM medicines 
                     WHERE generic_name LIKE :searchTerm";
            $params = [':searchTerm' => '%' . $searchTerm . '%'];
            return $db->read($query, $params);
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function generateReport($startDate, $endDate)
    {
        $db = new Database();
        $medicationQuery = "
            SELECT mrd.medication_name, COUNT(mrd.req_id) AS count 
            FROM medication_request_details mrd
            INNER JOIN medication_requests mr ON mrd.req_id = mr.id
            WHERE mr.date BETWEEN :start_date AND :end_date
            GROUP BY mrd.medication_name
            ORDER BY count DESC
            LIMIT 10
        ";
        $medicationData = $db->read($medicationQuery, ['start_date' => $startDate, 'end_date' => $endDate]);

        $requestsQuery = "
            SELECT DATE(mr.date) AS request_date, COUNT(*) AS request_count
            FROM medication_requests mr
            WHERE mr.date BETWEEN :start_date AND :end_date
            GROUP BY DATE(mr.date)
            ORDER BY request_date ASC
        ";
        $requestData = $db->read($requestsQuery, ['start_date' => $startDate, 'end_date' => $endDate]);

        return [
            'medications' => $medicationData,
            'requests' => $requestData
        ];
    }
}