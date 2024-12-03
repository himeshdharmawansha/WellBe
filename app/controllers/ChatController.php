<?php
// app/controllers/ChatController.php

require_once __DIR__ . '/../models/Chat.php';

class ChatController
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


   public function getuser_profiletatuses()
   {
      return $this->chatModel->getuser_profiletatuses();
   }

   public function markMessagesSeen($receiver)
   {
      return $this->chatModel->markMessagesSeen($receiver);
   }

   public function sendMessage($receiver, $message)
   {
      $response = $this->chatModel->sendMessage($receiver, $message);
      if ($response) {
         echo json_encode(["status" => "success", "message" => "Message sent."]);
      } else {
         echo json_encode(["status" => "error", "message" => "Error sending message."]);
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
}
