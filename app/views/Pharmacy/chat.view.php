<?php
// session_start();

// if (!isset($_SESSION['userid'])) {
//    header("Location: login.php");
//    exit();
// }

require_once(__DIR__ . "/../../core/Database.php");
$DB = new Database();
$currentUserId = $_SESSION['userid'];

// Query to get user_profile and unseen status of the last message, excluding the logged-in user
$query = "SELECT user_profile.*, 
               (SELECT seen 
               FROM message 
               WHERE (sender = user_profile.id AND receiver = :currentUserId) 
                  OR (sender = :currentUserId AND receiver = user_profile.id) 
               ORDER BY date DESC LIMIT 1) AS seen,
               
               (SELECT date 
               FROM message 
               WHERE (sender = user_profile.id AND receiver = :currentUserId) 
                  OR (sender = :currentUserId AND receiver = user_profile.id) 
               ORDER BY date DESC LIMIT 1) AS last_message_date,
               
               (SELECT COUNT(*) 
               FROM message 
               WHERE sender = user_profile.id AND receiver = :currentUserId AND seen = 0) AS unseen_count
         FROM user_profile
         WHERE user_profile.id != :currentUserId  
         AND user_profile.role = :role          
         ORDER BY 
         unseen_count DESC,                     
         last_message_date DESC;                
         ";
$user_profile = $DB->readn($query, ['currentUserId' => $currentUserId, 'role' => 3]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard</title>
   <link rel="stylesheet" href="<?= ROOT ?>/assets/css/lab/message.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>
   <div class="dashboard-container">
      <!-- Sidebar -->
      <?php
      $this->renderComponent('navbar', $active);
      ?>
      <!-- Main Content -->
      <div class="main-content">
         <!-- Top Header -->
         <?php
         $pageTitle = "Chat"; // Set the text you want to display
         include $_SERVER['DOCUMENT_ROOT'] . '/MVC/app/views/Components/Lab/header.php';
         ?>
         <div class="dashboard-content">
            <div class="container">
               <div class="chat-list">
                  <div class="search-bar">
                     <input type="text" placeholder="Search">
                  </div>
                  <ul id="chat-list">
                     <?php foreach ($user_profile as $user): ?>
                        <li>
                           <div class="chat-item <?php echo ($user['unseen_count'] > 0) ? 'unseen' : ''; ?>"
                              data-receiver-id="<?php echo $user['id']; ?>"
                              onclick="selectChat(this, <?php echo $user['id']; ?>)">
                              <div class="avatar"></div>
                              <div class="chat-info">
                                 <h4><?php echo htmlspecialchars($user['username']); ?></h4>
                                 <p class="chat-status"><?php echo $user['state'] ? 'Online' : 'Offline'; ?></p>
                              </div>
                              <div class="chat-side">
                                 <span class="time" id="time-<?php echo $user['id']; ?>">
                                    <?php
                                    echo !empty($user['last_message_date'])
                                       ? date('d/m/Y', strtotime($user['last_message_date']))
                                       : '';
                                    ?>
                                 </span>
                                 <span class="circle"></span>
                              </div>
                           </div>
                        </li>
                     <?php endforeach; ?>
                  </ul>
               </div>

               <div class="chat-window" id="chat-window">
                  <div class="chat-header">
                     <div class="avatar"></div>
                     <div class="header-info">
                        <h4 id="chat-username">Select a user</h4>
                        <p id="chat-status">Offline</p>
                     </div>
                  </div>
                  <div class="chat-messages" id="chat-messages">
                  </div>
                  <div class="chat-input">
                     <div class="upload"><i class="fa-solid fa-paperclip"></i></div>
                     <input type="text" id="message-input" placeholder="Type a message">
                     <button onclick="sendMessage()">Send</button>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <!-- Popup Menu for Message Options -->
   <div id="popup-menu" style="display: none; position: absolute; background: #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2); border-radius: 8px; padding: 10px;">
      <ul style="list-style: none; padding: 0; margin: 0;">
         <li onclick="deleteMessage()" style="padding: 8px; cursor: pointer;">
            <i class="fas fa-trash-alt"></i> Delete
         </li>
         <li onclick="editMessage()" style="padding: 8px; cursor: pointer;">
            <i class="fa-solid fa-pen"></i> Edit
         </li>
      </ul>
   </div>

   <script src="<?= ROOT ?>/assets/js/lab/message.js"></script>
   <script>
      let selectedUserId = null;
      let selectedMessage = null;

      document.getElementById('chat-messages').addEventListener('contextmenu', function(event) {
         event.preventDefault();
         const target = event.target.closest('.message');
         if (target) {
            selectedMessage = target;
            showPopupMenu(event.pageX, event.pageY);
         }
      });

      function showPopupMenu(x, y) {
         const popupMenu = document.getElementById('popup-menu');
         const editOption = popupMenu.querySelector('li[onclick="editMessage()"]');

         if (selectedMessage) {
            const senderId = selectedMessage.classList.contains('received') ?
               selectedUserId :
               <?php echo json_encode($currentUserId); ?>; // Assume current user is the sender for 'sent' messages

            // Hide edit option if the sender is not the current user
            if (senderId !== <?php echo json_encode($currentUserId); ?>) {
               editOption.style.display = 'none';
            } else {
               editOption.style.display = 'block';
            }
         }

         popupMenu.style.left = `${x}px`;
         popupMenu.style.top = `${y}px`;
         popupMenu.style.display = 'block';
         document.addEventListener('click', hidePopupMenu);
      }


      function hidePopupMenu() {
         document.getElementById('popup-menu').style.display = 'none';
         document.removeEventListener('click', hidePopupMenu);
      }

      function deleteMessage() {
         if (!selectedMessage) {
            alert("No message selected for deletion.");
            return;
         }

         const confirmDelete = window.confirm("Are you sure you want to delete this message?");
         if (confirmDelete) {
            const messageId = selectedMessage.getAttribute('data-message-id');
            const isSender = selectedMessage.classList.contains('sent');

            fetch(`<?= ROOT ?>/ChatController/deleteMessage/${messageId}/${isSender ? 1 : 0}`)
               .then(response => {
                  if (!response.ok) {
                     throw new Error('Failed to delete message');
                  }
                  return response.json();
               })
               .then(data => {
                  if (data.status === "success") {
                     pollMessages(); // Refresh the messages list after deletion
                     hidePopupMenu(); // Close any context menus or popups
                  } else {
                     alert('Error deleting message');
                  }
               })
               .catch(error => {
                  console.error('An error occurred while deleting the message:', error);
                  alert('An error occurred while deleting the message.');
               });
         }
      }



      function selectChat(chatItem, userId) {
         selectedUserId = userId;
         const username = chatItem.querySelector('.chat-info h4').textContent;
         const user_profiletatus = chatItem.querySelector('.chat-status').textContent;

         document.getElementById('chat-username').textContent = username;
         document.getElementById('chat-status').textContent = user_profiletatus;

         startChat(userId);

      }

      async function startChat(receiverId) {
         try {
            const response = await fetch(`<?= ROOT ?>/ChatController/getMessages/${receiverId}`);
            if (response.ok) {
               const data = await response.json();
               const chatMessages = document.getElementById("chat-messages");
               chatMessages.innerHTML = '';
               data.messages.forEach(message => {
                  const div = document.createElement('div');
                  // window.alert(message.sender);
                  div.classList.add('message', message.sender == receiverId ? 'received' : 'sent');
                  div.setAttribute('data-message-id', message.id);

                  div.innerHTML = `
                           <p>${message.message}</p>
                           <span class="time">${message.edited ? '<span class="edited-label">(edited)</span>' : ''} ${message.date}</span>
                           
                        `;
                  chatMessages.appendChild(div);
               });
               chatMessages.scrollTop = chatMessages.scrollHeight;
            } else {
               console.error('Failed to fetch messages. Status:', response.status);
            }
         } catch (error) {
            console.error('An error occurred while fetching messages:', error);
         }
      }

      let lastMessageId = null;

      function pollMessages() {
         if (selectedUserId) {
            fetch(`<?= ROOT ?>/ChatController/getMessages/${selectedUserId}`)
               .then(response => response.json())
               .then(data => {
                  if (data.messages.length > 0) {
                     const latestMessage = data.messages[data.messages.length - 1];
                     const chatMessages = document.getElementById("chat-messages");
                     chatMessages.innerHTML = '';
                     data.messages.forEach(message => {
                        // window.alert(selectedUserId);
                        const div = document.createElement('div');
                        div.classList.add('message', message.sender == selectedUserId ? 'received' : 'sent');
                        div.setAttribute('data-message-id', message.id);

                        div.innerHTML = `
                           <p>${message.message}</p>
                           <span class="time">${message.edited ? '<span class="edited-label">(edited)</span>' : ''} ${message.date}</span>
                           
                        `;

                        chatMessages.appendChild(div);
                     });
                     if (lastMessageId !== latestMessage.id) {
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                        lastMessageId = latestMessage.id;
                     }
                  }
               });
         }
      }

      setInterval(pollMessages, 3000);

      function editMessage() {
         if (!selectedMessage) {
            alert("No message selected for editing.");
            return;
         }

         const messageId = selectedMessage.getAttribute('data-message-id');
         const currentText = selectedMessage.querySelector('p').textContent;
         const newMessage = prompt("Edit your message:", currentText);

         if (newMessage === null || newMessage.trim() === "") {
            alert("Message cannot be empty.");
            return;
         }

         fetch(`<?= ROOT ?>/ChatController/editMessage/${messageId}/${encodeURIComponent(newMessage)}`)
            .then(response => {
               if (!response.ok) {
                  throw new Error('Failed to edit message');
               }
               return response.json();
            })
            .then(data => {
               if (data.status === "success") {
                  pollMessages(); // Refresh the messages list after editing
                  hidePopupMenu(); // Close any context menus or popups
               } else {
                  alert('Error editing message');
               }
            })
            .catch(error => {
               console.error('An error occurred while editing the message:', error);
               alert('An error occurred while editing the message.');
            });
      }

      function sendMessage() {
         const message = document.getElementById('message-input').value;
         if (!selectedUserId) {
            alert("Please select a chat.");
            return;
         }
         fetch(`<?= ROOT ?>/ChatController/sendMessage/${selectedUserId}/${message}`)
            .then(response => response.json())
            .then(data => {
               if (data.status === "success") {
                  document.getElementById('message-input').value = '';
                  pollMessages();
               } else {
                  alert('Error sending message');
               }
            });
      }


      setInterval(refreshuser_profiletatuses, 3000);

      function refreshuser_profiletatuses() {
         const xhr = new XMLHttpRequest();
         xhr.open("GET", "<?= ROOT ?>/ChatController/getuser_profiletatuses", true);
         xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
               const user_profile = JSON.parse(xhr.responseText);
               user_profile.forEach(user => {
                  const chatItem = document.querySelector(`.chat-item[data-receiver-id="${user.id}"]`);
                  if (chatItem) {
                     const statusElement = chatItem.querySelector('.chat-status');
                     statusElement.textContent = user.state ? 'Online' : 'Offline';
                  }
               });
            }
         };
         xhr.send();
      }

      function updateChatTimestamps() {
         fetch('<?= ROOT ?>/ChatController/getLastMessageDates')
            .then(response => response.json())
            .then(dates => {
               dates.forEach(item => {
                  const timeElement = document.getElementById(`time-${item.id}`);
                  if (timeElement) {
                     timeElement.textContent = item.date;
                  }
               });
            })
            .catch(error => console.error("Error updating timestamps:", error));
      }

      // Call the update function every 3 seconds
      setInterval(updateChatTimestamps, 3000);

      function refreshUnseenCounts(roleArray) {
         const roles = roleArray.join(','); // Serialize roles into a comma-separated string

         fetch(`<?= ROOT ?>/ChatController/getUnseenCounts?roles=${roles}`)
            .then(response => response.json())
            .then(user_profile => {
               if (user_profile.error) {
                  console.error("Error:", user_profile.error);
                  return;
               }

               const chatList = document.getElementById('chat-list');
               chatList.innerHTML = ''; // Clear current list

               user_profile.forEach(user => {
                  const unseenClass = user.unseen_count > 0 ? 'unseen' : '';
                  const lastMessageDate = user.last_message_date ?
                     new Date(user.last_message_date).toLocaleDateString('en-GB') :
                     '';

                  const chatItemHTML = `
                  <li>
                     <div class="chat-item ${unseenClass}" 
                        data-receiver-id="${user.id}" 
                        onclick="selectChat(this, ${user.id})">
                        <div class="avatar"></div>
                        <div class="chat-info">
                           <h4>${user.username}</h4>
                           <p class="chat-status">${user.state ? 'Online' : 'Offline'}</p>
                        </div>
                        <div class="chat-side">
                           <span class="time" id="time-${user.id}">${lastMessageDate}</span>
                           <span class="circle"></span>
                        </div>
                     </div>
                  </li>
               `;

                  // Append each user item to the chat list
                  chatList.insertAdjacentHTML('beforeend', chatItemHTML);
               });
            })
            .catch(error => console.error("Error fetching unseen counts:", error));
      }

      // Set interval to poll unseen message counts every 3 seconds
      // Poll unseen counts every 3 seconds
      setInterval(() => refreshUnseenCounts([3]), 3000);

      // Mark messages as seen when chat is opened
      function markMessagesAsSeen(receiverId) {
         fetch(`<?= ROOT ?>/ChatController/markMessagesSeen/${receiverId}`)
            .then(() => {
               const chatItem = document.querySelector(`.chat-item[data-receiver-id="${receiverId}"]`);
               if (chatItem) chatItem.classList.remove('unseen'); // Remove unseen indicator
            });
      }

      setInterval(refreshUnseenCounts, 3000);

      function updateReceivedState() {
         fetch('<?= ROOT ?>/ChatController/updateReceivedState')
            .catch(error => console.error("Error updating timestamps:", error));
      }

      // Call the update function every 3 seconds
      setInterval(updateReceivedState, 3000);
   </script>
</body>

</html>