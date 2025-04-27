<?php

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

      $query = "SELECT medicine_id, generic_name, brand_name, category, expiry_date, 
                        IF(expiry_date < CURDATE(), 0, quantity_in_stock) AS quantity_in_stock, unit 
                 FROM medicines
                 LIMIT $rowsPerPage OFFSET $offset";

      return $this->db->read($query);
   }

   public function searchForMedicine($searchTerm, $page, $limit)
   {
      $offset = ($page - 1) * $limit;

      $query = "SELECT medicine_id, generic_name, brand_name, category, expiry_date, 
                        IF(expiry_date < CURDATE(), 0, quantity_in_stock) AS quantity_in_stock, unit 
                 FROM medicines
                 WHERE generic_name LIKE :searchTerm
                 LIMIT $limit OFFSET $offset";

      $params = [':searchTerm' => '%' . $searchTerm . '%'];
      $medicines = $this->db->read($query, $params);

      $countQuery = "SELECT COUNT(*) as total FROM medicines WHERE generic_name LIKE :searchTerm";
      $totalRecords = $this->db->read($countQuery, [':searchTerm' => '%' . $searchTerm . '%'])[0]['total'];
      $totalPages = ceil($totalRecords / $limit);

      return ['medicines' => $medicines, 'totalPages' => $totalPages];
   }

   public function getTotalMedicineCount()
   {
      $sql = "SELECT COUNT(*) AS total FROM medicines";
      $result = $this->db->read($sql);

      return isset($result[0]['total']) ? $result[0]['total'] : 0;
   }

   public function updateMedicine($data)
   {
      $query = "UPDATE medicines SET ";
      $params = [];
      foreach ($data as $key => $value) {
         if ($key !== 'medicine_id') {
            $query .= "$key = :$key, ";
            $params[":$key"] = $value;
         }
      }
      $query = rtrim($query, ', ');
      $query .= " WHERE medicine_id = :medicine_id";
      $params[':medicine_id'] = $data['medicine_id'];
      return $this->db->write($query, $params);
   }

   public function deleteMedicine($id)
   {
      $query = "DELETE FROM medicines WHERE medicine_id = :medicine_id";
      return $this->db->write($query, [':medicine_id' => $id]);
   }

   public function addMedicine($data)
   {
      $query = "INSERT INTO medicines (generic_name, brand_name, category, expiry_date, quantity_in_stock, unit) 
                VALUES (:generic_name, :brand_name, :category, :expiry_date, :quantity_in_stock, :unit)";
      return $this->db->write($query, $data);
   }

   public function getMedicines(){

      $query = 'SELECT * FROM medicines';

      return $this->query($query);
   }
}