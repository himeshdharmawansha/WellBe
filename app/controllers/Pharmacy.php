<?php

class Pharmacy extends Controller
{
    protected $pharmacyModel;

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
        $this->pharmacyModel = new PharmacyModel(); // Use the renamed model class

        if (!isset($_SESSION['USER']) || $_SESSION['user_type'] !== "pharmacy") {
            redirect('login');
            exit;
        }
    }

    public function index()
    {
        $data = [
            'title' => 'Dashboard Page',
            'username' => 'John Doe',
        ];
        $this->view('Pharmacy/dashboard', 'dashboard', $data);
    }

    public function requests()
    {
        $this->view('Pharmacy/requests', 'requests');
    }

    public function chat()
    {
        $data = [
            'title' => 'Dashboard Page',
            'username' => 'John Doe',
        ];
        $this->view('Pharmacy/chat', 'chat', $data);
    }

    public function report()
    {
        $this->view('Pharmacy/report', 'report');
    }

    public function medicationDetails()
    {
        $this->view('Pharmacy/medicationDetails', 'requests');
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

    public function getRequestCounts()
    {
        $counts = $this->pharmacyModel->getRequestCounts();
        echo json_encode($counts);
    }

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