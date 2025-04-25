<?php

class Pharmacy extends Controller
{
    protected $pharmacyModel;
    protected $medicationRequestModel;
    private $profileModel;
    private $chatModel;

    private $data = [
        'elements' => [
            'dashboard' => ["fas fa-tachometer-alt", "Dashboard"],
            'requests' => ["fas fa-list", "Requests"],
            'medicines' => ["fa-solid fa-tablets", "Medicines"],
            'chat' => ["fa-solid fa-comment-dots", "Chat"],
            'report' => ["fa-solid fa-chart-simple", "Report"],
            'logout' => ["fas fa-sign-out-alt", "Logout"]
        ],
        'userType' => 'pharmacy'
    ];

    public function __construct()
    {
        
        if (!isset($_SESSION['USER']) || $_SESSION['user_type'] !== "pharmacy") {
            redirect('login');
            exit;
        }
        $this->pharmacyModel = new PharmacyModel();
        $this->medicationRequestModel = new MedicationRequest();
        $this->profileModel = new ProfileModel();
        $this->chatModel = new Chat();
    }

    public function index()
    {
        $data = [
            'requestCounts' => $this->pharmacyModel->getRequestCounts(), // Fetch request counts
        ];
        $this->view('Pharmacy/dashboard', 'dashboard', $data);
    }

    public function requests()
    {
        // Fetch all requests
        $requests = $this->medicationRequestModel->getAll();

        // Separate pending and completed requests
        $pendingRequests = array_filter($requests, function ($request) {
            return $request['state'] === 'pending';
        });
        $completedRequests = array_filter($requests, function ($request) {
            return $request['state'] === 'completed';
        });

        $data = [
            'pendingRequests' => array_values($pendingRequests),
            'completedRequests' => array_values($completedRequests)
        ];

        $this->view('Pharmacy/requests', 'requests', $data);
    }

    public function getRequestsJson()
    {
        $requests = $this->medicationRequestModel->getAll();
        header('Content-Type: application/json');
        echo json_encode($requests);
        exit;
    }

    private function UnseenCounts($roles)
    {
        if (empty($roles)) {
            return ['error' => 'Invalid or missing roles parameter'];
        }

        try {
            $result = $this->chatModel->getUnseenCounts($roles);
            return $result;
        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function chat()
    {
        // Fetch unseen counts using the local UnseenCounts method
        $unseenCounts = $this->UnseenCounts([3, 5]);
        $user_profile = $unseenCounts;
        if (!is_array($user_profile)) {
            $user_profile = [];
        }

        // Fetch all profiles
        $profiles = $this->profileModel->getAll();
        if (!empty($profiles) && !isset($profiles['error'])) {
            $profileMap = [];
            foreach ($profiles as $profile) {
                $profileMap[$profile->id] = $profile;
            }
            foreach ($user_profile as &$user) {
                if (isset($user['id']) && isset($profileMap[$user['id']])) {
                    $user['image'] = ROOT . '/assets/images/users/' . $profileMap[$user['id']]->image;
                } else {
                    $user['image'] = ROOT . '/assets/images/users/Profile_default.png';
                }
            }
            unset($user);
        }

        // Pass data to the view
        $data = [
            'user_profile' => $user_profile
        ];
        $this->view('Pharmacy/chat', 'chat', $data);
    }

    public function report()
    {
        $this->view('Pharmacy/report', 'report');
    }

    public function medicationDetails()
    {
        $pharmacyModel = new PharmacyModel();
        
        $requestID = isset($_GET['ID']) ? esc($_GET['ID']) : null;
        $doctorID = isset($_GET['doctor_id']) ? esc($_GET['doctor_id']) : null;
        $patientID = isset($_GET['patient_id']) ? esc($_GET['patient_id']) : null;

        $data = [
            'active' => 'requests',
            'requestID' => $requestID,
            'doctorID' => $doctorID,
            'patientID' => $patientID
        ];

        if ($requestID && $doctorID && $patientID) {
            $medicationData = $pharmacyModel->getMedicationDetails($requestID);
            $data['medicationDetails'] = $medicationData['medicationDetails'];
            $data['additionalRemarks'] = $medicationData['additionalRemarks'];
        }

        $this->view('Pharmacy/medicationDetails','medicationDetails', $data);
    }

    public function Medicines()
    {
        $this->view('Pharmacy/medicines', 'medicines');
    }

    public function logout()
    {
        $this->view('Lab/logout', 'logout');
    }

    public function renderComponent($component, $active)
    {
        $elements = $this->data['elements'];
        $userType = $this->data['userType'];
        $filename = "../app/views/Components/{$component}.php";
        require $filename;
    }

    // public function getRequestCounts()
    // {
    //     $counts = $this->pharmacyModel->getRequestCounts();
    //     echo json_encode($counts);
    // }

    public function getRequestsByDay()
    {
        $requestsByDay = $this->pharmacyModel->getRequestsByDay();
        echo json_encode($requestsByDay);
    }

    public function medicationRequests()
    {
        $requests = $this->pharmacyModel->getMedicationRequests();
        echo json_encode($requests);
    }

    public function getStock()
    {
        $stock = $this->pharmacyModel->getStock();
        echo json_encode($stock);
    }

    public function getMedicines()
    {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $rowsPerPage = 9;
        $medicineModel = new Medicine();
        $medicines = $medicineModel->getAllMedicines($page, $rowsPerPage);
        if (!is_array($medicines)) {
            $medicines = [];
        }
        $totalRecords = $medicineModel->getTotalMedicineCount();
        $totalPages = ceil($totalRecords / $rowsPerPage);
        echo json_encode([
            "medicines" => $medicines,
            "totalPages" => $totalPages
        ]);
    }

    public function searchForMedicine()
    {
        $searchTerm = $_GET['query'] ?? '';
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 9;
        try {
            $medicineModel = new Medicine();
            $data = $medicineModel->searchForMedicine($searchTerm, $page, $limit);
            echo json_encode($data);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function searchMedicine()
    {
        $searchTerm = $_GET['query'] ?? '';
        $results = $this->pharmacyModel->searchMedicine($searchTerm);
        echo json_encode($results);
    }

    public function updateMedicine()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $medicineModel = new Medicine();
        $success = $medicineModel->updateMedicine($data);
        echo json_encode(['success' => $success]);
    }

    public function deleteMedicine()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $medicineModel = new Medicine();
        $success = $medicineModel->deleteMedicine($data['medicine_id']);
        echo json_encode(['success' => $success]);
    }

    public function addMedicine()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $medicineModel = new Medicine();
        $success = $medicineModel->addMedicine($data);
        echo json_encode(['success' => $success]);
    }

    public function generateReport()
    {
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;
        if (!$startDate || !$endDate) {
            echo json_encode(['error' => 'Invalid date range']);
            return;
        }
        $reportData = $this->pharmacyModel->generateReport($startDate, $endDate);
        echo json_encode($reportData);
    }
}
