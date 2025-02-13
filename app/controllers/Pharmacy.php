<?php

class Pharmacy extends Controller
{

   private $data = [
      'elements' => [
         'dashboard' => ["fas fa-tachometer-alt", "Dashboard"],
         'requests' => ["fas fa-list", "Requests"],
         'chat' => ["fa-solid fa-comment-dots", "Chat"],
         'report' => ["fa-solid fa-chart-simple", "Report"],
         'medicines' => ["fa-solid fa-tablets", "Medicines"],
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
      $db = new Database();
      $query = "
           SELECT state, COUNT(*) as count 
           FROM medication_requests 
           WHERE date >= NOW() - INTERVAL 14 DAY
           GROUP BY state
       ";

      $results = $db->read($query);

      // Structure the data for easier use in the frontend
      $response = [
         'pending' => 0,
         'completed' => 0,
      ];

      foreach ($results as $row) {
         if (isset($response[$row['state']])) {
            $response[$row['state']] = (int)$row['count'];
         }
      }

      echo json_encode($response);
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

      // Initialize days of the week
      $days = ['Monday' => 0, 'Tuesday' => 0, 'Wednesday' => 0, 'Thursday' => 0, 'Friday' => 0, 'Saturday' => 0, 'Sunday' => 0];

      // Populate data from the query
      foreach ($results as $row) {
         $days[$row['day']] = (int)$row['count'];
      }

      // Return the counts in a simple format
      echo json_encode(array_values($days));
   }

   public function medicationRequests()
   {
      // Database connection
      $db = new Database();

      // Query to fetch the medication requests
      $query = "SELECT patient_id, state 
                  FROM medication_requests 
                  WHERE state IN ('pending') 
                  ORDER BY FIELD(state,'pending') 
                  LIMIT 20";

      $requests = $db->query($query);
      echo json_encode($requests);
   }

   public function getStock()
   {
      $db = new Database(); // Assuming Database class is available
      $query = "SELECT generic_name, 
                           (CASE 
                               WHEN quantity_in_stock = 0 OR expiry_date < CURDATE() THEN 'Out of Stock' 
                               ELSE 'In Stock' 
                            END) AS state 
                    FROM medicines LIMIT 20";

      $requests = $db->query($query);
      echo json_encode($requests);
   }

   public function getMedicines()
{
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $rowsPerPage = 9;

    $medicineModel = new Medicine();
    $medicines = $medicineModel->getAllMedicines($page, $rowsPerPage);

    // Ensure medicines is an array
    if (!is_array($medicines)) {
        $medicines = [];
    }

    // Get the total number of records (assuming this is a count query)
    $totalRecords = $medicineModel->getTotalMedicineCount();  // Implement this method to return the count of medicines
    $totalPages = ceil($totalRecords / $rowsPerPage);

    // Send a proper JSON response
    echo json_encode([
        "medicines" => $medicines,
        "totalPages" => $totalPages
    ]);
}

   
  public function searchForMedicine() {
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
     $searchTerm = $_GET['query'] ?? ''; // Get the search term from the query string

     try {
        $db = new Database(); // Instantiate the Database class
        $query = "SELECT generic_name, 
                            (CASE 
                                WHEN quantity_in_stock = 0 OR expiry_date < CURDATE() THEN 'Out of Stock' 
                                ELSE 'In Stock' 
                             END) AS state 
                     FROM medicines 
                     WHERE generic_name LIKE :searchTerm";

        $results = [':searchTerm' => '%' . $searchTerm . '%'];
        $requests = $db->read($query, $results);
        echo json_encode($requests);
     } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
     }
  }
}
