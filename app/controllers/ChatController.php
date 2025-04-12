<?php

require_once __DIR__ . '/../models/Chat.php';

class ChatController extends Controller
{
   private $chatModel;

   public function __construct()
   {
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
      $data = json_decode(file_get_contents('php://input'), true);

      if (isset($data['receiver']) && isset($data['message'])) {
         $receiver = $data['receiver'];
         $message = $data['message'];

         $response = $this->chatModel->sendMessage($receiver, $message);

         if ($response) {
            echo json_encode(["status" => "success", "message" => "Message sent."]);
         } else {
            echo json_encode(["status" => "error", "message" => "Error sending message."]);
         }
      } else {
         echo json_encode(["status" => "error", "message" => "Missing required parameters."]);
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

   public function updateRecievedState($receiver, $sender)
   {
      return $this->chatModel->updateRecievedState($receiver, $sender);
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

   public function loggedin()
   {
      $this->chatModel->setLoggedIn($_SESSION['userid']);
   }
}