<?php
// app/controllers/ChatController.php

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
      json_encode($result);
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
      // Check if 'roles' parameter exists in the request (query string)
      $rolesParam = isset($_GET['roles']) ? $_GET['roles'] : '';

      // Validate and process the roles
      $roles = explode(',', $rolesParam); // Convert the comma-separated string to an array
      $roles = array_map('trim', $roles); // Remove any whitespace
      $roles = array_filter($roles, 'is_numeric'); // Ensure all roles are numeric

      // Check if roles are valid
      if (empty($roles)) {
         echo json_encode(['error' => 'Invalid or missing roles parameter']);
         return;
      }

      try {
         // Fetch unseen counts from the model
         $result = $this->chatModel->getUnseenCounts($roles);
         echo json_encode($result);
      } catch (Exception $e) {
         // Handle any errors gracefully
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
         // Fetch unseen counts from the model
         $result = $this->chatModel->getUnseenCounts($roles);
         return $result; // Return the result instead of rendering directly

      } catch (Exception $e) {
         // Handle errors gracefully
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
      // Get the raw POST data
      $data = json_decode(file_get_contents('php://input'), true);

      // Check if message and receiver are provided
      if (isset($data['receiver']) && isset($data['message'])) {
         $receiver = $data['receiver'];
         $message = $data['message'];

         // Call the model's sendMessage method
         $response = $this->chatModel->sendMessage($receiver, $message);

         if ($response) {
            // Respond with success
            echo json_encode(["status" => "success", "message" => "Message sent."]);
         } else {
            // Respond with error
            echo json_encode(["status" => "error", "message" => "Error sending message."]);
         }
      } else {
         // Respond if the required data is missing
         echo json_encode(["status" => "error", "message" => "Missing required parameters."]);
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

   public function editMessage($messageId, $newMessage)
   {
      $result = $this->chatModel->editMessage($messageId, $newMessage);
      if ($result) {
         echo json_encode(["status" => "success", "message" => "Message edited successfully."]);
      } else {
         echo json_encode(["status" => "error", "message" => "Could not edit the message."]);
      }
   }

   public function loggedin()
   {
      $DB = new Database();
      // Update user state to 1 (logged in)
      $updateStateQuery = "UPDATE user_profile SET state = 1 WHERE id = :userid";
      $DB->write($updateStateQuery, ['userid' => $_SESSION['userid']]);

      // Update messages as received
      $updateQuery = "UPDATE message SET received = 1 WHERE receiver = :receiver AND received = 0";
      $DB->write($updateQuery, ['receiver' => $_SESSION['userid']]);
   }
}
