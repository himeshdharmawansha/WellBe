<?php
// app/models/Chat.php

require_once __DIR__ . '/../core/model.php';

class Chat extends Model
{

   protected $table = 'message';

   // public function __construct()
   // {
   //    parent::__construct();
   // }

   // Delete message (sender or receiver)
   public function deleteMessage($messageId, $isSender)
   {
      $currentUserId = $_SESSION['userid'];
      $query = $isSender
         ? "DELETE FROM message WHERE id = :messageId AND sender = :currentUserId"
         : "UPDATE message SET deleted_receiver = 1 WHERE id = :messageId AND receiver = :currentUserId";

      return $this->write($query, ['messageId' => $messageId, 'currentUserId' => $currentUserId]);
   }

   // Get the last message date between the current user and other user_profile
   public function getLastMessageDates()
   {
      $currentUserId = $_SESSION['userid'];
      $query = "SELECT user_profile.id, 
                          DATE_FORMAT((SELECT date FROM message 
                                       WHERE (sender = user_profile.id AND receiver = :currentUserId) 
                                       OR (sender = :currentUserId AND receiver = user_profile.id) 
                                       ORDER BY date DESC LIMIT 1), '%d/%m/%Y') AS date
                   from user_profile
                   WHERE user_profile.id != :currentUserId";
      return $this->query($query, ['currentUserId' => $currentUserId]);
   }

   // Get messages between two user_profile
   public function getMessages($receiver)
   {
      $sender = $_SESSION['userid'];
      $updateSeenQuery = "UPDATE message SET seen = 1 WHERE sender = :receiver AND receiver = :sender AND seen = 0";
      $this->query($updateSeenQuery, ['receiver' => $receiver, 'sender' => $sender]);

      $query = "SELECT * FROM message 
             WHERE ((sender = :sender AND receiver = :receiver AND deleted_sender = 0) 
                   OR (receiver = :sender AND sender = :receiver AND deleted_receiver = 0)) 
             ORDER BY date ASC";
      return $this->readn($query, ['sender' => $sender, 'receiver' => $receiver]);
   }

   // Get the username of the receiver
   public function getReceiverUsername($receiver)
   {
      $query = "SELECT username from user_profile WHERE id = :receiver";
      $receiverData = $this->query($query, ['receiver' => $receiver]);
      return $receiverData ? $receiverData[0]->username : 'Unknown User';
   }

   public function getUnseenCounts($arr)
   {
      $currentUserId = $_SESSION['userid'];

      // Check if roles array is empty
      if (empty($arr)) {
         throw new Exception("Role array cannot be empty");
      }

      // Dynamically generate placeholders for the IN clause
      $rolePlaceholders = implode(',', array_fill(0, count($arr), '?'));

      // SQL query with placeholders
      $query = "SELECT user_profile.*, 
           (SELECT seen FROM message 
            WHERE (sender = user_profile.id AND receiver = ?) 
            OR (sender = ? AND receiver = user_profile.id) 
            ORDER BY date DESC LIMIT 1) AS seen,
           (SELECT date FROM message 
            WHERE (sender = user_profile.id AND receiver = ?) 
            OR (sender = ? AND receiver = user_profile.id) 
            ORDER BY date DESC LIMIT 1) AS last_message_date,
           (SELECT COUNT(*) FROM message 
            WHERE sender = user_profile.id AND receiver = ? AND seen = 0) AS unseen_count
           FROM user_profile
           WHERE user_profile.id != ? AND user_profile.role IN ($rolePlaceholders)
           ORDER BY 
               unseen_count DESC,   
               last_message_date DESC";

      // Prepare the parameters for the placeholders
      $params = array_merge(
         array_fill(0, 4, $currentUserId), // Bind currentUserId for seen and last_message_date
         [$currentUserId],                // Bind currentUserId for unseen_count condition
         [$currentUserId],                // Bind currentUserId for id != condition
         $arr                             // Bind each role value for the IN clause
      );

      // Execute the query
      return $this->readn($query, $params);
   }



   // Get the status of all user_profile
   public function getuser_profiletatuses()
   {
      $currentUserId = $_SESSION['userid'];
      $query = "SELECT user_profile.id, user_profile.state from user_profile WHERE user_profile.id != :currentUserId";
      return $this->query($query, ['currentUserId' => $currentUserId]);
   }

   // Mark messages as seen
   public function markMessagesSeen($receiver)
   {
      $sender = $_SESSION['userid'];  // Current logged-in user
      $query = "UPDATE message SET seen = 1 WHERE sender = :receiver AND receiver = :sender AND seen = 0";
      return $this->query($query, ['receiver' => $receiver, 'sender' => $sender]);
   }

   // update Recieved State
   public function updateRecievedState($receiver, $sender)
   {
      $updateQuery = "UPDATE message SET received = 1 WHERE receiver = :receiver AND received = 0";
      return $this->query($updateQuery, ['receiver' => $_SESSION['userid']]);
   }

   // Send a new message
   public function sendMessage($receiver, $message)
   {
      $sender = $_SESSION['userid'];
      date_default_timezone_set('Asia/Colombo');
      $date = date("Y-m-d H:i:s");
      $query = "INSERT INTO message (sender, receiver, message, date) VALUES (:sender, :receiver, :message, :date)";
      return $this->write($query, [
         'sender' => $sender,
         'receiver' => $receiver,
         'message' => $message,
         'date' => $date,

      ]);
   }

   public function userDetails($currentUserId)
   {
      $query = "SELECT user_profile.*, 
          (SELECT seen FROM message 
           WHERE (sender = user_profile.id AND receiver = :currentUserId) 
           OR (sender = :currentUserId AND receiver = user_profile.id) 
           ORDER BY date DESC LIMIT 1) AS seen,
          (SELECT date FROM message 
           WHERE (sender = user_profile.id AND receiver = :currentUserId) 
           OR (sender = :currentUserId AND receiver = user_profile.id) 
           ORDER BY date DESC LIMIT 1) AS last_message_date,
          (SELECT COUNT(*) FROM message WHERE sender = user_profile.id AND receiver = :currentUserId AND seen = 0) AS unseen_count
          from user_profile
          WHERE user_profile.id != :currentUserId";

      return $this->query($query, ['currentUserId' => $currentUserId]);
   }

   public function editMessage($messageId, $newMessage)
   {
      $currentUserId = $_SESSION['userid'];
      $query = "UPDATE message SET message = :newMessage, edited = 1 WHERE id = :messageId AND sender = :currentUserId";
      return $this->write($query, [
         'newMessage' => $newMessage,
         'messageId' => $messageId,
         'currentUserId' => $currentUserId,
      ]);
   }
}
