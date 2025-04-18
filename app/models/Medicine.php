<?php

//medicine class
class Medicine extends Model
{

   protected $table = 'medicines';
   private $db;

   protected $allowedColumns = [

      'medicine_id',
      'generic_name',
      'brand_name',
      'category',
      'expiry_date',
      'quantity_in_stock',
      'unit',
   ];

   public function __construct()
   {
      $this->db = new Database();
   }

   public function getAllMedicines($page = 1, $rowsPerPage = 9)
   {
      $offset = ($page - 1) * $rowsPerPage;

      // Use direct variable substitution instead of named parameters for LIMIT & OFFSET
      $query = "SELECT generic_name, brand_name, category, expiry_date, 
                        IF(expiry_date < CURDATE(), 0, quantity_in_stock) AS quantity_in_stock, unit 
                 FROM medicines
                 LIMIT $rowsPerPage OFFSET $offset";

      return $this->db->read($query);
   }

   public function searchForMedicine($searchTerm, $page, $limit)
   {
      $offset = ($page - 1) * $limit;

      // Corrected SQL Query (No named parameters for LIMIT and OFFSET)
      $query = "SELECT generic_name, brand_name, category, expiry_date, 
                        IF(expiry_date < CURDATE(), 0, quantity_in_stock) AS quantity_in_stock, unit 
                 FROM medicines
              WHERE generic_name LIKE :searchTerm 
              LIMIT $limit OFFSET $offset"; // Directly inserting limit and offset

      $params = [':searchTerm' => '%' . $searchTerm . '%'];
      $medicines = $this->db->read($query, $params);

      // Get total count
      $countQuery = "SELECT COUNT(*) as total FROM medicines WHERE generic_name LIKE :searchTerm";
      $totalRecords = $this->db->read($countQuery, [':searchTerm' => '%' . $searchTerm . '%'])[0]['total'];
      $totalPages = ceil($totalRecords / $limit);

      return ['medicines' => $medicines, 'totalPages' => $totalPages];
   }

   public function getTotalMedicineCount()
   {
      $sql = "SELECT COUNT(*) AS total FROM medicines";  // Adjust this query to your table and column names
      $result = $this->db->read($sql);

      // Check if the result is not empty and return the total count
      return isset($result[0]['total']) ? $result[0]['total'] : 0;
   }
}
