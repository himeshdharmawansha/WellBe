<?php

class PharmacyReport extends Controller
{
    public function index() {}

    public function generateReport()
    {
        $db = new Database();

        // Get start and end dates from URL parameters
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;

        if (!$startDate || !$endDate) {
            echo json_encode(['error' => 'Invalid date range']);
            return;
        }

        // Adjust the queries to use the provided date range
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

        echo json_encode([
            'medications' => $medicationData,
            'requests' => $requestData
        ]);
    }
}
