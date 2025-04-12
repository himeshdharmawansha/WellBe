<?php
require_once(__DIR__ . "/../../controllers/ChatController.php");
require_once(__DIR__ . "/../../models/ProfileModel.php");

$he = new ChatController();
$profileModel = new ProfileModel();

$unseenCounts = $he->UnseenCounts([1,2,4,5]);
$user_profile = $unseenCounts;
if (!is_array($user_profile)) {
   $user_profile = [];
}
$currentUserId = $_SESSION['userid'];

$profiles = $profileModel->getAll();
if (!empty($profiles) && !isset($profiles['error'])) {
   $profileMap = [];
   foreach ($profiles as $profile) {
      $profileMap[$profile->id] = $profile;
   }
   foreach ($user_profile as &$user) {
      if (isset($user['id']) && isset($profileMap[$user['id']])) {
         $user['image'] = ROOT . '/assets/images/users/' . $profileMap[$user['id']]->image;
      } else {
         $user['image'] = ROOT . '/assets/images/users/Profile_default.png';
      }
   }
   unset($user);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>WELLBE</title>
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
         $pageTitle = "Chat";
         include $_SERVER['DOCUMENT_ROOT'] . '/WELLBE/app/views/Components/header.php';
         ?>
         <div class="dashboard-content">
            <div class="container">
               <div class="chat-list">
                  <div class="search-bar">
                     <input
                        type="text"
                        id="search-input"
                        placeholder="Search"
                        oninput="searchUsers(this.value)" />
                  </div>
                  <ul id="chat-list">
                     <?php foreach ($user_profile as $user): ?>
                        <li>
                           <div class="chat-item <?php echo ($user['unseen_count'] > 0) ? 'unseen' : ''; ?>"
                              data-receiver-id="<?php echo ($user['id']); ?>"
                              onclick="selectChat(this, '<?php echo $user['id']; ?>')">
                              <img src="<?php echo htmlspecialchars($user['image']); ?>" alt="Avatar" class="avatar">
                              <div class="chat-info">
                                 <h4><?php echo htmlspecialchars($user['username']); ?></h4>
                                 <p class="chat-status"><?php echo $user['state'] ? 'Online' : 'Offline'; ?></p>
                              </div>
                              <div class="chat-side">
                                 <span class="time" id="time-<?php echo $user['id']; ?>">
                                    <?php
                                    if (!empty($user['last_message_date'])) {
                                       $lastMessageDate = new DateTime($user['last_message_date']);
                                       $today = new DateTime('today');
                                       $yesterday = (clone $today)->modify('-1 day');
                                       
                                       if ($lastMessageDate->format('Y-m-d') === $today->format('Y-m-d')) {
                                          echo $lastMessageDate->format('h:i A');
                                       } elseif ($lastMessageDate->format('Y-m-d') === $yesterday->format('Y-m-d')) {
                                          echo 'Yesterday';
                                       } else {
                                          echo $lastMessageDate->format('d/m/Y');
                                       }
                                    }
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
                     <img id="chat-avatar" src="<?= ROOT ?>/assets/images/users/Profile_default.png" alt="Avatar" class="avatar">
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
               <?php echo json_encode($currentUserId); ?>;

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
                     refreshUnseenCounts([1,2,4,5]);
                     pollMessages();
                     hidePopupMenu();
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
         const userStatus = chatItem.querySelector('.chat-status').textContent;
         const avatarSrc = chatItem.querySelector('.avatar').src;

         document.getElementById('chat-username').textContent = username;
         document.getElementById('chat-status').textContent = userStatus;
         document.getElementById('chat-avatar').src = avatarSrc;

         startChat(userId);
         markMessagesAsSeen(userId);
      }

      async function startChat(receiverId) {
         try {
         const response = await fetch(`<?= ROOT ?>/ChatController/getMessages/${receiverId}`);
         if (response.ok) {
            const data = await response.json();
            const chatMessages = document.getElementById("chat-messages");
            chatMessages.innerHTML = '';
            data.messages.forEach(message => {
            const messageDate = new Date(message.date);
            const formattedDate = formatTimeOrDate(messageDate);

            const div = document.createElement('div');
            div.classList.add('message', message.sender == receiverId ? 'received' : 'sent');
            div.setAttribute('data-message-id', message.id);

            div.innerHTML = `
                  <p>${message.message}</p>
                  <span class="time">${message.edited ? '<span class="edited-label">(edited)</span>' : ''} ${formattedDate}</span>
               `;
            chatMessages.appendChild(div);
            });
            chatMessages.scrollTop = chatMessages.scrollHeight;
         }
         } catch (error) {
         console.error('Error fetching messages:', error);
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
               const messageDate = new Date(message.date);
               const formattedDate = formatTimeOrDate(messageDate);

               const div = document.createElement('div');
               div.classList.add('message', message.sender == selectedUserId ? 'received' : 'sent');
               div.setAttribute('data-message-id', message.id);

               div.innerHTML = `
                  <p>${message.message}</p>
                  <span class="time">${message.edited ? '<span class="edited-label">(edited)</span>' : ''} ${formattedDate}</span>
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

         fetch(`<?= ROOT ?>/ChatController/editMessage`, {
            method: "POST",
            headers: {
            "Content-Type": "application/json",
            },
            body: JSON.stringify({
            messageId: messageId,
            newMessage: newMessage.trim(),
            }),
         })
         .then(response => response.json())
         .then(data => {
            if (data.status === "success") {
            refreshUnseenCounts([1,2,4,5]);
            pollMessages();
            hidePopupMenu();
            } else {
            alert(data.message || "Error editing message.");
            }
         })
         .catch(error => {
            console.error("Error editing message:", error);
            alert("Error editing message.");
         });
      }

      document.getElementById('message-input').addEventListener('keypress', function(event) {
         if (event.key === 'Enter' && !event.shiftKey) {
         event.preventDefault();
         sendMessage();
         }
      });

      function sendMessage() {
         const messageInput = document.getElementById('message-input');
         const message = messageInput.value.trim();

         if (!message) return;
         if (!selectedUserId) {
         alert("Please select a chat.");
         return;
         }

         const data = {
         message: message,
         receiver: selectedUserId
         };

         fetch('<?= ROOT ?>/ChatController/sendMessage', {
            method: 'POST',
            headers: {
            'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
         })
         .then(response => response.json())
         .then(data => {
            if (data.status === "success") {
            messageInput.value = '';
            pollMessages();
            refreshUnseenCounts([1,2,4,5]);
            } else {
            alert('Error sending message');
            }
         })
         .catch(error => console.error("Error:", error));
      }

      setInterval(refreshUserStatuses, 3000);

      function refreshUserStatuses() {
         const xhr = new XMLHttpRequest();
         xhr.open("GET", "<?= ROOT ?>/ChatController/getUserStatuses", true);
         xhr.onreadystatechange = function() {
         if (xhr.readyState == 4 && xhr.status == 200) {
            const users = JSON.parse(xhr.responseText);
            users.forEach(user => {
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

      function formatTimeOrDate(messageDate) {
         const today = new Date();
         const yesterday = new Date(today);
         yesterday.setDate(today.getDate() - 1);
         
         const isToday = messageDate.toDateString() === today.toDateString();
         const isYesterday = messageDate.toDateString() === yesterday.toDateString();

         if (isToday) {
         return messageDate.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
         });
         } else if (isYesterday) {
         return "Yesterday";
         } else {
         return messageDate.toLocaleDateString('en-GB', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
         });
         }
      }

      let isSearching = false;

      function refreshUnseenCounts(roleArray) {
         try {
            if (isSearching) return;

            const roles = roleArray.join(',');
            fetch(`<?= ROOT ?>/ChatController/getUnseenCounts?roles=${roles}`)
               .then(response => response.json())
               .then(users => {
                  if (users.error) {
                     console.error("Error:", users.error);
                     return;
                  }

                  const chatList = document.getElementById('chat-list');
                  const existingItems = new Map(
                     Array.from(chatList.querySelectorAll('.chat-item')).map(item => [
                        item.getAttribute('data-receiver-id'),
                        item
                     ])
                  );

                  // Sort users by last_message_date (newest first)
                  users.sort((a, b) => {
                     const dateA = a.last_message_date ? new Date(a.last_message_date) : new Date(0);
                     const dateB = b.last_message_date ? new Date(b.last_message_date) : new Date(0);
                     return dateB - dateA;
                  });

                  users.forEach(user => {
                     const userId = user.id.toString();
                     const existingItem = existingItems.get(userId);
                     const unseenClass = user.unseen_count > 0 ? 'unseen' : '';
                     let lastMessageDisplay = '';
                     if (user.last_message_date) {
                        const messageDate = new Date(user.last_message_date);
                        lastMessageDisplay = formatTimeOrDate(messageDate);
                     }

                     const profileImageUrl = user.image ?
                        '<?= ROOT ?>/assets/images/users/' + user.image :
                        '<?= ROOT ?>/assets/images/users/Profile_default.png';

                     if (existingItem) {
                        // Update existing item
                        existingItem.className = `chat-item ${unseenClass}`;
                        const statusElement = existingItem.querySelector('.chat-status');
                        if (statusElement.textContent !== (user.state ? 'Online' : 'Offline')) {
                           statusElement.textContent = user.state ? 'Online' : 'Offline';
                        }
                        const timeElement = existingItem.querySelector('.time');
                        if (timeElement.textContent !== lastMessageDisplay) {
                           timeElement.textContent = lastMessageDisplay;
                        }
                     } else {
                        // Add new item at the top
                        const chatItemHTML = `
                           <li>
                              <div class="chat-item ${unseenClass}" 
                                   data-receiver-id="${user.id}" 
                                   onclick="selectChat(this, '${user.id}')">
                                 <img src="${profileImageUrl}" alt="Avatar" class="avatar">
                                 <div class="chat-info">
                                    <h4>${user.username}</h4>
                                    <p class="chat-status">${user.state ? 'Online' : 'Offline'}</p>
                                 </div>
                                 <div class="chat-side">
                                    <span class="time" id="time-${user.id}">${lastMessageDisplay}</span>
                                    <span class="circle"></span>
                                 </div>
                              </div>
                           </li>
                        `;
                        chatList.insertAdjacentHTML('afterbegin', chatItemHTML);
                     }
                     existingItems.delete(userId);
                  });

                  // Remove items no longer in the user list
                  existingItems.forEach(item => {
                     item.parentElement.remove();
                  });
               })
               .catch(error => console.error("Error fetching unseen counts:", error));
         } catch (error) {
            console.error('Error in refreshUnseenCounts:', error);
         }
      }

      setInterval(() => {
         if (!isSearching) {
         refreshUnseenCounts([1,2,4,5]);
         }
      }, 1000);

      function searchUsers(query) {
         const chatList = document.getElementById('chat-list');

         if (!query.trim()) {
         isSearching = false;
         refreshUnseenCounts([1,2,4,5]);
         return;
         }

         isSearching = true;

         fetch(`<?= ROOT ?>/ChatController/searchUser?query=${encodeURIComponent(query)}`)
         .then(response => response.json())
         .then(user_profile => {
            chatList.innerHTML = '';

            if (user_profile.error || user_profile.length === 0) {
            chatList.innerHTML = `<li></li>`;
            return;
            }

            user_profile.forEach(user => {
            const unseenClass = user.unseen_count > 0 ? 'unseen' : '';
            let lastMessageDisplay = '';

            if (user.last_message_date) {
               const messageDate = new Date(user.last_message_date);
               lastMessageDisplay = formatTimeOrDate(messageDate);
            }

            const profileImageUrl = user.image ? 
               '<?= ROOT ?>/assets/images/users/' + user.image : 
               '<?= ROOT ?>/assets/images/users/Profile_default.png';

            const chatItemHTML = `
               <li>
               <div class="chat-item ${unseenClass}" 
                  data-receiver-id="${user.id}" 
                  onclick="selectChat(this, '${user.id}')">
                  <img src="${profileImageUrl}" alt="Avatar" class="avatar">
                  <div class="chat-info">
                     <h4>${user.username}</h4>
                     <p class="chat-status">${user.state ? 'Online' : 'Offline'}</p>
                  </div>
                  <div class="chat-side">
                     <span class="time" id="time-${user.id}">${lastMessageDisplay}</span>
                     <span class="circle"></span>
                  </div>
               </div>
               </li>
               `;

            chatList.insertAdjacentHTML('beforeend', chatItemHTML);
            });
         })
         .catch(error => console.error("Error searching users:", error));
      }

      function markMessagesAsSeen(receiverId) {
         fetch(`<?= ROOT ?>/ChatController/markMessagesSeen/${receiverId}`)
         .then(() => {
            const chatItem = document.querySelector(`.chat-item[data-receiver-id="${receiverId}"]`);
            if (chatItem) chatItem.classList.remove('unseen');
         });
      }

      function updateReceivedState() {
         fetch('<?= ROOT ?>/ChatController/updateReceivedState')
         .catch(error => console.error("Error updating timestamps:", error));
      }

      setInterval(updateReceivedState, 3000);
      </script>
   </body>

</html>