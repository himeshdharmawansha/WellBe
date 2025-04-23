<?php

class TestRequests extends Controller
{
    protected $testRequestModel;
    protected $emailModel;

    public function __construct()
    {
        $this->testRequestModel = new TestRequest();
        $this->emailModel = new Email();
    }

    public function index() {}

    public function getRequestsJson()
    {
        $requests = $this->testRequestModel->getAll();
        header('Content-Type: application/json');
        echo json_encode($requests);
        exit;
    }

    public function searchRequestsByPatientId()
    {
        $searchTerm = isset($_GET['patient_id']) ? htmlspecialchars($_GET['patient_id']) : '';

        if (!empty($searchTerm)) {
            $requests = $this->testRequestModel->searchByPatientId($searchTerm);
            header('Content-Type: application/json');
            echo json_encode($requests);
            exit;
        } else {
            echo json_encode(['error' => 'No patient ID provided.']);
            exit;
        }
    }

    public function updateState()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!empty($data['requestID']) && isset($data['state'])) {
            $requestID = htmlspecialchars($data['requestID']);
            $newState = htmlspecialchars($data['state']);
            $testName = htmlspecialchars($data['testName']);
            $this->testRequestModel->updateState($requestID, $newState, $testName);
            echo json_encode(['success' => true, 'message' => 'State updated successfully.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Invalid input.']);
        }
    }

    public function getTestDetails($requestID)
    {
        return $this->testRequestModel->getTestDetails($requestID);
    }

    public function updateRequestDetails()
    {
        $response = ['success' => false];

        try {
            $data = $_POST;
            $files = $_FILES;

            // Validate patientID
            if (!isset($data['patientID']) || empty($data['patientID'])) {
                throw new Exception("Patient ID is missing or invalid.");
            }

            // Validate tests
            if (!isset($data['tests']) || empty($data['tests'])) {
                throw new Exception("No test details provided.");
            }

            $tests = json_decode($data['tests'], true);
            if (!is_array($tests) || empty($tests)) {
                throw new Exception("Invalid test details format.");
            }

            $requestID = $data['requestID'];
            $patientID = $data['patientID'];

            // Validate requestID
            if (!isset($requestID) || empty($requestID)) {
                throw new Exception("Request ID is missing or invalid.");
            }

            $this->testRequestModel->updateRequestDetails($requestID, $tests, $files, $patientID);
            $response['success'] = true;
        } catch (Exception $e) {
            $response['error'] = $e->getMessage();
        }

        echo json_encode($response);
        exit;
    }

    public function deleteFile()
    {
        $response = ['success' => false];
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['requestID'], $data['testName'])) {
            $requestID = $data['requestID'];
            $testName = $data['testName'];

            $this->testRequestModel->deleteFile($requestID, $testName);
            $response['success'] = true;
        }

        echo json_encode($response);
    }

    public function getFileUrl()
    {
        $response = ['success' => false];
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['requestID'], $data['testName'])) {
            $requestID = $data['requestID'];
            $testName = $data['testName'];

            $fileUrl = $this->testRequestModel->getFileUrl($requestID, $testName);
            if ($fileUrl) {
                $response['fileUrl'] = $fileUrl;
                $response['success'] = true;
            }
        }

        echo json_encode($response);
    }

    //  public function sendCompletionEmail()
    //  {
    //      $response = ['success' => false];
    //      $data = json_decode(file_get_contents('php://input'), true);

    //      if (isset($data['requestID'], $data['patientID'], $data['testNames'])) {
    //          // $requestID = htmlspecialchars($data['requestID']);
    //          // $patientID = htmlspecialchars($data['patientID']);
    //          // $testNames = $data['testNames'];
    //          $requestID = htmlspecialchars($data['requestID']);
    //          $patientID = htmlspecialchars($data['patientID']);
    //          $testNames = $data['testNames'];

    //          echo "<script>
    //          console.log('Request ID: " . $requestID . "');
    //          console.log('Patient ID: " . $patientID . "');
    //          console.log('Test Names: " . json_encode($testNames) . "');
    //      </script>";


    //          // Fetch patient details
    //          $patientDetails = $this->testRequestModel->getPatientDetails($patientID);
    //          if ($patientDetails && isset($patientDetails['email'], $patientDetails['name'])) {
    //              $patientName = $patientDetails['name'];
    //              $patientEmail = $patientDetails['email'];
    //              $testList = implode(', ', $testNames);
    //              $message = "
    //                  <h3>Dear $patientName,</h3>
    //                  <p>We are pleased to inform you that your lab test report(s) for Request ID: $requestID are now ready. The following tests have been completed:</p>
    //                  <ul>
    //                      <li>$testList</li>
    //                  </ul>
    //                  <p>Please visit our portal or contact our support team to access your report.</p>
    //                  <p>Thank you for choosing WELLBE Lab Services.</p>
    //                  <p>Best regards,<br>WELLBE Lab Services Team</p>
    //              ";

    //              $result = $this->emailModel->send(
    //                  $patientName,
    //                  'benshekniel@gmail.com', // Sender email
    //                  $message,
    //                  $patientEmail
    //              );
    //              $response['success'] = true;
    //              $response['message'] = $result;
    //          } else {
    //              $response['error'] = "Patient details not found.";
    //          }
    //      } else {
    //          $response['error'] = "Invalid input data.";
    //      }

    //      echo json_encode($response);
    //      exit;
    //  }


    public function sendCompletionEmail()
    {
        $response = ['success' => false];
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['requestID'], $data['patientID'], $data['testNames'])) {
            $requestID = htmlspecialchars($data['requestID']);
            $patientID = htmlspecialchars($data['patientID']);
            $testNames = $data['testNames'];

            // Log for debugging
            error_log("Request ID: $requestID");
            error_log("Patient ID: $patientID");
            error_log("Test Names: " . implode(', ', $testNames));

            // Fetch patient details
            $patientDetails = $this->testRequestModel->getPatientDetails($patientID);

            // Since getPatientDetails returns an array of rows, access the first row
            if (!empty($patientDetails) && is_array($patientDetails) && isset($patientDetails[0]['first_name'], $patientDetails[0]['email'])) {
                $patientName = $patientDetails[0]['first_name'];
                $patientEmail = $patientDetails[0]['email'];
                $testList = implode(', ', $testNames);

                $message = "
                   <p>
                   <h3>Dear $patientName,</h3>
                   <p>We are pleased to inform you that your lab test report(s) for Request ID: $requestID are now ready. The following tests have been completed:</p>
                   <ul><li>$testList</li></ul>
                   <p>Please visit our portal or contact our support team to access your report.</p>
                   <p>Thank you for choosing WELLBE Lab Services.</p>
                   <p>Best regards,<br>WELLBE Lab Services Team
               ";

                // Send email using the email model
                $result = $this->emailModel->send(
                    $patientName,
                    'himeshdharmawansha1119@gmail.com', // Sender email (this will be overridden by the Email model)
                    $message,
                    $patientEmail // Receiver email (this will be overridden by the Email model)
                );

                // Check the result (which is a string, not a boolean)
                if ($result === "Message sent successfully.") {
                    $response['success'] = true;
                    $response['message'] = 'Email sent successfully';
                } else {
                    $response['error'] = "Failed to send email: $result";
                }
            } else {
                $response['error'] = "Patient details not found or invalid.";
            }
        } else {
            $response['error'] = "Invalid input data.";
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
}
