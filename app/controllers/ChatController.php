<?php

require_once __DIR__ . '/../models/Chat.php';

class ChatController extends Controller
{
   private $chatModel;

   public function __construct()
   {
      error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
      $this->chatModel = new Chat();
   }

   public function index()
   {
      echo "Welcome to the ChatController!";
   }

   public function deleteMessage($messageId, $isSender)
   {
      $result = $this->chatModel->deleteMessage($messageId, $isSender);
      if ($result) {
         echo json_encode(["status" => "success"]);
      } else {
         echo json_encode(["status" => "error", "message" => "Could not delete the message."]);
      }
   }

   public function getLastMessageDates()
   {
      $result = $this->chatModel->getLastMessageDates();
      echo json_encode($result);
   }

   public function getMessages($receiver)
   {
      $messages = $this->chatModel->getMessages($receiver);
      echo json_encode([
         "status" => "success",
         "messages" => $messages,
      ]);
   }

   public function getReceiverUsername($receiver)
   {
      return $this->chatModel->getReceiverUsername($receiver);
   }

   public function getUnseenCounts()
   {
      $rolesParam = isset($_GET['roles']) ? $_GET['roles'] : '';
      $roles = explode(',', $rolesParam);
      $roles = array_map('trim', $roles);
      $roles = array_filter($roles, 'is_numeric');

      if (empty($roles)) {
         echo json_encode(['error' => 'Invalid or missing roles parameter']);
         return;
      }

      try {
         $result = $this->chatModel->getUnseenCounts($roles);
         echo json_encode($result);
      } catch (Exception $e) {
         echo json_encode(['error' => $e->getMessage()]);
      }
   }

   public function UnseenCounts($roles)
   {
      if (empty($roles)) {
         echo json_encode(['error' => 'Invalid or missing roles parameter']);
         return null;
      }

      try {
         $result = $this->chatModel->getUnseenCounts($roles);
         return $result;
      } catch (Exception $e) {
         return ['error' => $e->getMessage()];
      }
   }

   public function getuser_profiletatuses()
   {
      return $this->chatModel->getuser_profiletatuses();
   }

   public function markMessagesSeen($receiver)
   {
      return $this->chatModel->markMessagesSeen($receiver);
   }

   public function sendMessage()
   {
      if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
         echo json_encode(['error' => 'Invalid request method']);
         return;
      }

      $receiver = $_POST['receiver'] ?? null;
      $message = $_POST['message'] ?? null;
      $type = $_POST['type'] ?? 'text';
      $caption = $_POST['caption'] ?? null;
      $filePath = null;
      $fileType = null;
      $fileSize = null;

      if (!$receiver) {
         echo json_encode(["status" => "error", "message" => "Receiver is required."]);
         return;
      }

      if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
         $file = $_FILES['file'];
         $allowedImageTypes = ['image/jpeg', 'image/png', 'image/gif'];
         $allowedDocTypes = [
            'application/pdf',                                     
            'application/msword',                                
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
            'application/vnd.ms-excel',                           
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
            'text/plain',                                        
            'text/csv',                                         
            'application/rtf',                                  
            'application/zip'                                   
         ];
         $fileMimeType = mime_content_type($file['tmp_name']);
         $maxSize = 5 * 1024 * 1024; // 5MB

         if ($file['size'] > $maxSize) {
            echo json_encode(['error' => 'File size exceeds 5MB limit']);
            return;
         }

         $fileSize = number_format($file['size'] / (1024 * 1024), 1) . ' MB';

         if ($fileMimeType === 'application/pdf') {
            $fileType = 'PDF Document';
         } elseif ($fileMimeType === 'application/msword' || $fileMimeType === 'application/vnd.openxmlformats-officedocument.wordprocessingml.document') {
            $fileType = 'Microsoft Word Document';
         } elseif ($fileMimeType === 'application/vnd.ms-excel' || $fileMimeType === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
            $fileType = 'Microsoft Excel Document';
         } elseif ($fileMimeType === 'text/plain') {
            $fileType = 'Text Document';
         } elseif ($fileMimeType === 'text/csv') {
            $fileType = 'CSV Document';
         } elseif ($fileMimeType === 'application/rtf') {
            $fileType = 'RTF Document';
         } elseif ($fileMimeType === 'application/zip') {
            $fileType = 'ZIP Archive';
         } else {
            $fileType = 'Document';
         }

         $fileName = time() . '_' . basename($file['name']);
         $uploadDir = __DIR__ . '/../../public/assets/chats/';

         if ($type === 'photo') {
            if (!in_array($fileMimeType, $allowedImageTypes)) {
               echo json_encode(["status" => "error", "message" => "Invalid photo format. Only JPEG, PNG, and GIF are allowed."]);
               return;
            }
            $uploadDir .= 'photos/';
         } elseif ($type === 'document') {
            if (!in_array($fileMimeType, $allowedDocTypes)) {
               echo json_encode(["status" => "error", "message" => "Invalid document format. Only PDF, Word, Excel, TXT, CSV, RTF, and ZIP files are allowed."]);
               return;
            }
            $uploadDir .= 'documents/';
         } else {
            echo json_encode(["status" => "error", "message" => "Invalid type specified."]);
            return;
         }

         if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0755, true)) {
               error_log("Failed to create directory: $uploadDir", 3, __DIR__ . '/../../logs/error.log');
               echo json_encode(['error' => 'Failed to create upload directory']);
               return;
            }
         }

         $filePath = $uploadDir . $fileName;

         if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            echo json_encode(['error' => 'Failed to move file']);
            return;
         }

         $filePath = 'assets/chats/' . ($type === 'photo' ? 'photos/' : 'documents/') . $fileName;
         $message = $fileName;
      } elseif ($type === 'text' && !$message) {
         echo json_encode(["status" => "error", "message" => "Message is required for text type."]);
         return;
      }

      try {
         $response = $this->chatModel->sendMessage($receiver, $message, $type, $filePath, $caption, $fileType, $fileSize);

         if ($response) {
            echo json_encode(["status" => "success", "message" => "Message sent."]);
         } else {
            echo json_encode(["status" => "error", "message" => "Error sending message."]);
         }
      } catch (Exception $e) {
         error_log("Failed to send message: " . $e->getMessage(), 3, __DIR__ . '/../../logs/error.log');
         echo json_encode(['error' => 'Failed to send message: ' . $e->getMessage()]);
      }
   }

   public function searchUser()
   {
      $query = $_GET['query'] ?? '';
      $result = $this->chatModel->searchUser($query);
      if ($result) {
         echo json_encode($result);
      } else {
         echo json_encode(["error" => "No users found"]);
      }
   }

   public function userDetails($currentUserId)
   {
      return $this->chatModel->userDetails($currentUserId);
   }

   public function updateReceivedState($receiver)
   {
      return $this->chatModel->updateRecievedState($receiver);
   }

   public function editMessage()
   {
      $data = json_decode(file_get_contents('php://input'), true);

      if (isset($data['messageId'], $data['newMessage'])) {
         $messageId = $data['messageId'];
         $newMessage = $data['newMessage'];

         $result = $this->chatModel->editMessage($messageId, $newMessage);

         if ($result) {
            echo json_encode(["status" => "success", "message" => "Message edited successfully."]);
         } else {
            echo json_encode(["status" => "error", "message" => "Could not edit the message."]);
         }
      } else {
         echo json_encode(["status" => "error", "message" => "Invalid input."]);
      }
   }

   public function editCaption()
   {
      $data = json_decode(file_get_contents('php://input'), true);

      if (isset($data['messageId'], $data['newCaption'])) {
         $messageId = $data['messageId'];
         $newCaption = $data['newCaption'];

         $result = $this->chatModel->editCaption($messageId, $newCaption);

         if ($result) {
            echo json_encode(["status" => "success", "message" => "Caption edited successfully."]);
         } else {
            echo json_encode(["status" => "error", "message" => "Could not edit the caption."]);
         }
      } else {
         echo json_encode(["status" => "error", "message" => "Invalid input."]);
      }
   }

   public function loggedin()
   {
      $this->chatModel->setLoggedIn($_SESSION['userid']);
   }

   public function searchMessage($receiver)
   {
      $query = $_GET['query'] ?? '';
      if (empty($query)) {
         echo json_encode(["status" => "success", "messages" => []]);
         return;
      }

      $messages = $this->chatModel->searchMessage($receiver, $query);
      echo json_encode([
         "status" => "success",
         "messages" => $messages
      ]);
   }
}
