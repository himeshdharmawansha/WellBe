<?php

require_once __DIR__ . '/../core/model.php';

class Chat extends Model
{
   protected $table = 'message';

   public function deleteMessage($messageId, $isSender)
   {
      $currentUserId = $_SESSION['userid'];
      $query = $isSender
         ? "DELETE FROM message WHERE id = :messageId AND sender = :currentUserId"
         : "UPDATE message SET deleted_receiver = 1 WHERE id = :messageId AND receiver = :currentUserId";

      return $this->write($query, ['messageId' => $messageId, 'currentUserId' => $currentUserId]);
   }

   public function getLastMessageDates()
   {
      $currentUserId = $_SESSION['userid'];
      $query = "SELECT user_profile.id, 
                       DATE_FORMAT((SELECT date FROM message 
                                    WHERE (sender = user_profile.id AND receiver = :currentUserId) 
                                    OR (sender = :currentUserId AND receiver = user_profile.id) 
                                    ORDER BY date DESC LIMIT 1), '%d/%m/%Y') AS date
                FROM user_profile
                WHERE user_profile.id != :currentUserId";
      return $this->query($query, ['currentUserId' => $currentUserId]);
   }

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

   public function getReceiverUsername($receiver)
   {
      $query = "SELECT username FROM user_profile WHERE id = :receiver";
      $receiverData = $this->query($query, ['receiver' => $receiver]);
      return $receiverData ? $receiverData[0]->username : 'Unknown User';
   }

   public function getUnseenCounts($arr)
   {
      $currentUserId = $_SESSION['userid'];

      if (empty($arr)) {
         throw new Exception("Role array cannot be empty");
      }

      $rolePlaceholders = implode(',', array_fill(0, count($arr), '?'));

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

      $params = array_merge(
         array_fill(0, 4, $currentUserId),
         [$currentUserId],
         [$currentUserId],
         $arr
      );

      return $this->read($query, $params);
   }

   public function getuser_profiletatuses()
   {
      $currentUserId = $_SESSION['userid'];
      $query = "SELECT user_profile.id, user_profile.state FROM user_profile WHERE user_profile.id != :currentUserId";
      return $this->query($query, ['currentUserId' => $currentUserId]);
   }

   public function markMessagesSeen($receiver)
   {
      $sender = $_SESSION['userid'];
      $query = "UPDATE message SET seen = 1 WHERE sender = :receiver AND receiver = :sender AND seen = 0";
      return $this->query($query, ['receiver' => $receiver, 'sender' => $sender]);
   }

   public function updateRecievedState($receiver, $sender)
   {
      $updateQuery = "UPDATE message SET received = 1 WHERE receiver = :receiver AND received = 0";
      return $this->query($updateQuery, ['receiver' => $_SESSION['userid']]);
   }

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
                (SELECT COUNT(*) FROM message 
                 WHERE sender = user_profile.id AND receiver = :currentUserId AND seen = 0) AS unseen_count
                FROM user_profile
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

   public function searchUser($query)
   {
      $querySql = "SELECT * FROM user_profile WHERE username LIKE :query AND role = 3";
      return $this->read($querySql, [':query' => '%' . $query . '%']);
   }

   public function setLoggedIn($userId)
   {
      $updateStateQuery = "UPDATE user_profile SET state = 1 WHERE id = :userid";
      $this->write($updateStateQuery, ['userid' => $userId]);
   
      $updateQuery = "UPDATE message SET received = 1 WHERE receiver = :receiver AND received = 0";
      $this->write($updateQuery, ['receiver' => $userId]);
   
      return true;
   }
}