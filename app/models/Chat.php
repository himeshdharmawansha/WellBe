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
      $query = "SELECT id, state FROM user_profile WHERE id != :currentUserId";
      return $this->query($query, ['currentUserId' => $currentUserId]);
   }

   public function markMessagesSeen($receiver)
   {
      $sender = $_SESSION['userid'];
      $query = "UPDATE message SET seen = 1 WHERE sender = :receiver AND receiver = :sender AND seen = 0";
      return $this->write($query, ['receiver' => $receiver, 'sender' => $sender]);
   }

   public function sendMessage($receiver, $message, $type, $filePath = null, $caption = null, $fileType = null, $fileSize = null)
   {
      $sender = $_SESSION['userid'];
      $query = "INSERT INTO message (sender, receiver, message, type, file_path, caption, file_type, file_size, date, seen, deleted_sender, deleted_receiver, edited) 
                VALUES (:sender, :receiver, :message, :type, :file_path, :caption, :file_type, :file_size, NOW(), 0, 0, 0, 0)";
      $params = [
         'sender' => $sender,
         'receiver' => $receiver,
         'message' => $message,
         'type' => $type,
         'file_path' => $filePath,
         'caption' => $caption,
         'file_type' => $fileType,
         'file_size' => $fileSize
      ];
      return $this->write($query, $params);
   }

   public function searchUser($query)
   {
      $currentUserId = $_SESSION['userid'];
      $searchTerm = "%$query%";
      $sql = "SELECT user_profile.*, 
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
              WHERE user_profile.id != :currentUserId 
              AND (user_profile.username LIKE :searchTerm )";
      return $this->readn($sql, [
         'currentUserId' => $currentUserId,
         'searchTerm' => $searchTerm
      ]);
   }

   public function userDetails($currentUserId)
   {
      $query = "SELECT * FROM user_profile WHERE id = :currentUserId";
      $result = $this->query($query, ['currentUserId' => $currentUserId]);
      return $result ? $result[0] : null;
   }

   public function updateRecievedState($receiver, $sender)
   {
      $query = "UPDATE message 
                SET received = 1 
                WHERE sender = :sender 
                AND receiver = :receiver 
                AND received = 0";
      return $this->write($query, ['sender' => $sender, 'receiver' => $receiver]);
   }

   public function editMessage($messageId, $newMessage)
   {
      $currentUserId = $_SESSION['userid'];
      $query = "UPDATE message 
                SET message = :newMessage, edited = 1 
                WHERE id = :messageId 
                AND sender = :currentUserId 
                AND type = 'text'";
      return $this->write($query, [
         'newMessage' => $newMessage,
         'messageId' => $messageId,
         'currentUserId' => $currentUserId
      ]);
   }

   public function editCaption($messageId, $newCaption)
   {
      $currentUserId = $_SESSION['userid'];
      $query = "UPDATE message 
                SET caption = :newCaption, edited = 1 
                WHERE id = :messageId 
                AND sender = :currentUserId 
                AND (type = 'photo' OR type = 'document')";
      return $this->write($query, [
         'newCaption' => $newCaption,
         'messageId' => $messageId,
         'currentUserId' => $currentUserId
      ]);
   }

   public function setLoggedIn($userId)
   {
      $query = "UPDATE user_profile SET state = 1 WHERE id = :userId";
      return $this->write($query, ['userId' => $userId]);
   }
}