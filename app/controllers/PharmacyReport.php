<?php


class PharmacyReport extends Controller
{

	public function index() {}

	public function generateReport()
	{
		// Database connection (replace with your connection logic)
		$db = new Database();

		// Query to get the top 10 medications by count in the last 30 days
		$medicationQuery = "
        SELECT mrd.medication_name, COUNT(mrd.req_id) AS count 
        FROM medication_request_details mrd
        INNER JOIN medication_requests mr ON mrd.req_id = mr.id
        WHERE mr.date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
        GROUP BY mrd.medication_name
        ORDER BY count DESC
        LIMIT 10
    ";
		$medicationData = $db->read($medicationQuery);

		// Query to get the daily request counts for the last 30 days
		$requestsQuery = "
        SELECT DATE(mr.date) AS request_date, COUNT(*) AS request_count
        FROM medication_requests mr
        WHERE mr.date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
        GROUP BY DATE(mr.date)
        ORDER BY request_date ASC
    ";
		$requestData = $db->read($requestsQuery);

		// Return data as JSON
		echo json_encode([
			'medications' => $medicationData,
			'requests' => $requestData
		]);
	}
}
