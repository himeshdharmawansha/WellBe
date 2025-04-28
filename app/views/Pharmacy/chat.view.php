<?php $currentUserId = $_SESSION['userid']; ?>
<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>WELLBE</title>
   <link rel="stylesheet" href="<?= ROOT ?>/assets/css/message.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>
   <div class="dashboard-container">
      <?php
      $this->renderComponent('navbar', $active);
      ?>
      <div class="main-content">
         <?php
         $pageTitle = "Chat";
         include $_SERVER['DOCUMENT_ROOT'] . '/WELLBE/app/views/Components/header.php';
         ?>
         <div class="dashboard-content">
            <div class="container">
               <div class="chat-list">
                  <div class="search-bar">
                     <input type="text" id="search-input" placeholder="Search" oninput="searchUsers(this.value)" />
                  </div>
                  <ul id="chat-list">
                     <?php foreach ($user_profile as $user): ?>
                        <?php
                        $roles = [1 => 'pharmacy', 2 => 'lab', 3 => 'admin', 4 => 'patient', 5 => 'doctor', 6 => 'receptionist'];
                        $roleTitle = $roles[$user['role']] ?? 'Unknown';
                        ?>
                        <li>
                           <div class="chat-item <?php echo ($user['unseen_count'] > 0) ? 'unseen' : ''; ?>"
                              data-receiver-id="<?php echo ($user['id']); ?>"
                              onclick="selectChat(this, '<?php echo $user['id']; ?>')">
                              <img src="<?php echo esc($user['image']); ?>" alt="Avatar" class="avatar">
                              <div class="chat-info">
                                 <h4><?php echo esc($user['username']); ?></h4>
                                 <p class="chat-status"><?php echo $roleTitle; ?></p>
                                 <p hidden class="chat-time"><?php echo $user['state'] ? 'Online' : 'Offline'; ?></p>
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
                  <div class="chat-header" id="chat-header">
                     <img id="chat-avatar" src="<?= ROOT ?>/assets/images/users/Profile_default.png" alt="Avatar" class="avatar">
                     <div class="header-info">
                        <h4 id="chat-username">Select a user</h4>
                        <p id="chat-status">Offline</p>
                     </div>
                     <div class="last" id="last" style="visibility: hidden;">
                        <input
                           class="search-bar-message"
                           type="text"
                           id="search-input-message"
                           placeholder="Search"
                           oninput="searchMessage(this.value)" />
                     </div>
                     <button type="button" id="search-button">
                        <i class="fa fa-search" aria-hidden="true"></i>
                     </button>
                  </div>
                  <div class="chat-messages" id="chat-messages">
                  </div>
                  <div class="chat-input" id="chat-input">
                     <div class="upload">
                        <i class="fa-solid fa-paperclip" onclick="showUploadPopup()"></i>
                        <div class="upload-popup" id="upload-popup">
                           <ul>
                              <li onclick="triggerFileUpload('photo')">
                                 <i class="fa-solid fa-image"></i> Photo
                              </li>
                              <li onclick="triggerFileUpload('document')">
                                 <i class="fa-solid fa-file-alt"></i> Document
                              </li>
                           </ul>
                        </div>
                     </div>
                     <input type="text" id="message-input" placeholder="Type a message">
                     <button onclick="sendMessage()">Send</button>
                     <input type="file" id="file-upload" style="display: none;" onchange="handleFileSelection(this.files)">
                     <div class="preview-area" id="preview-area">
                        <div id="preview-content"></div>
                        <div class="caption-send">
                           <input type="text" id="caption-input" placeholder="caption...">
                           <i class="fa-solid fa-paper-plane send-icon" onclick="sendFile()"></i>
                        </div>
                     </div>
                  </div>
                  <!-- Consolidated popup-menu -->
                  <ul id="popup-menu" class="popup-menu" style="display: none; position: absolute; background: #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2); border-radius: 8px; padding: 10px;">
                     <li onclick="editMessage()"><i class="fa-solid fa-pen"></i> Edit message</li>
                     <li id="edit-caption-option" onclick="editCaption()" style="display: none;"><i class="fa-solid fa-pen"></i> Edit caption</li>
                     <li onclick="deleteMessage()"><i class="fas fa-trash-alt"></i> Delete message</li>
                     <li id="close-chat-option" onclick="closeChat()" style="display: none;"><i class="fa-solid fa-times"></i> Close chat</li>
                  </ul>
               </div>
            </div>
         </div>
      </div>
   </div>

   <div id="popup-menu" style="display: none; position: absolute; background: #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2); border-radius: 8px; padding: 10px;">
      <ul style="list-style: none; padding: 0; margin: 0;">
         <li onclick="showDeleteConfirmation()" style="padding: 8px; cursor: pointer;">
            <i class="fas fa-trash-alt"></i> Delete
         </li>
         <li onclick="editMessage()" style="padding: 8px; cursor: pointer;">
            <i class="fa-solid fa-pen"></i> Edit
         </li>
         <li onclick="editCaption()" style="padding: 8px; cursor: pointer; display: none;" id="edit-caption-option">
            <i class="fa-solid fa-pen"></i> Edit Caption
         </li>
      </ul>
   </div>

   <!-- Dim overlay -->
   <div id="dim-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 99;"></div>

   <div id="discard-notification" style="display: none; position: fixed; background: #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2); border-radius: 8px; padding: 15px; z-index: 100;">
      <h4>Discard unsent message?</h4>
      <p>Your message, including attached media, will not be sent if you leave this screen.</p>
      <div class="notification-actions">
         <button onclick="discardFile()" class="discard-btn">Discard</button>
         <button onclick="returnToMedia()" class="return-btn">Return to media</button>
      </div>
   </div>

   <!-- Delete Confirmation Popup -->
   <div id="delete-confirmation" style="display: none; position: fixed; background: #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2); border-radius: 8px; padding: 15px; z-index: 100;">
      <h4>Delete this message?</h4>
      <p>This action cannot be undone.</p>
      <div class="notification-actions">
         <button onclick="confirmDelete()" class="discard-btn">Delete</button>
         <button onclick="cancelDelete()" class="return-btn">Cancel</button>
      </div>
   </div>

   <!-- Error Popup -->
   <div class="popup" id="error-popup">
      <span class="close-btn" onclick="closePopup()">×</span>
      <span id="popup-message"></span>
   </div>

   <script src="<?= ROOT ?>/assets/js/lab/message.js"></script>
   <script>
      let selectedUserId = null;
      let selectedMessage = null;
      let selectedFile = null;
      let selectedFileType = null;
      let unseenCountsMap = {};
      let isMessageSearching = false;

      // Right-click on messages
      document.getElementById('chat-messages').addEventListener('contextmenu', function(event) {
         event.preventDefault();
         const target = event.target.closest('.message');
         if (target) {
            selectedMessage = target;
            showPopupMenu(event.pageX, event.pageY, false); // Not a chat window click
         }
      });

      // Right-click on chat window (excluding messages)
      document.getElementById('chat-window').addEventListener('contextmenu', function(event) {
         event.preventDefault();
         // Only show the menu if the click is not on a message
         if (!event.target.closest('.message')) {
            selectedMessage = null; // Ensure no message is selected
            showPopupMenu(event.pageX, event.pageY, true); // Chat window click
         }
      });

      function showPopup(message, type = 'error') {
         const popup = document.getElementById('error-popup');
         const popupMessage = document.getElementById('popup-message');
         if (!popup || !popupMessage) {
            console.error('Popup elements not found:', {
               popup,
               popupMessage
            });
            return;
         }
         popupMessage.textContent = message;
         popup.className = `popup ${type} active`;

         setTimeout(() => {
            popup.className = 'popup';
         }, 5000);
      }

      function closePopup() {
         const popup = document.getElementById('error-popup');
         if (popup) {
            popup.className = 'popup';
         }
      }

      function getRoleTitle(roleId) {
         const roles = {
            1: 'pharmacy',
            2: 'lab',
            3: 'admin',
            4: 'patient',
            5: 'doctor',
            6: 'receptionist'
         };
         return roles[roleId] || 'Unknown';
      }

      document.addEventListener('DOMContentLoaded', function() {
         const chatInput = document.getElementById('chat-input');
         chatInput.style.display = 'none';
      });

      document.getElementById('chat-messages').addEventListener('contextmenu', function(event) {
         event.preventDefault();
         const target = event.target.closest('.message');
         if (target) {
            selectedMessage = target;
            showPopupMenu(event.pageX, event.pageY);
         }
      });
      const search_input = document.getElementById('last');
      const search = document.getElementById('search-button');
      search.addEventListener('click', function() {
         search_input.style.visibility = "visible";
      });

      document.getElementById('chat-window').addEventListener('click', function(event) {
         if (!event.target.closest('#search-button') && !event.target.closest('#last')) {
            search_input.style.visibility = "hidden";
         }
      });

      function showPopupMenu(x, y, isChatWindowClick) {
         const popupMenu = document.getElementById('popup-menu');
         const editOption = popupMenu.querySelector('li[onclick="editMessage()"]');
         const editCaptionOption = popupMenu.querySelector('#edit-caption-option');
         const deleteOption = popupMenu.querySelector('li[onclick="deleteMessage()"]');
         const closeChatOption = popupMenu.querySelector('#close-chat-option');

         if (isChatWindowClick) {
            // Show only "Close chat" option
            editOption.style.display = 'none';
            editCaptionOption.style.display = 'none';
            deleteOption.style.display = 'none';
            closeChatOption.style.display = 'flex'; // Ensure it's visible and aligned
         } else {
            // Show message-related options, hide "Close chat"
            closeChatOption.style.display = 'none';
            if (selectedMessage) {
               const senderId = selectedMessage.classList.contains('received') ?
                  selectedUserId :
                  <?php echo json_encode($currentUserId); ?>;

               if (senderId !== <?php echo json_encode($currentUserId); ?> || selectedMessage.classList.contains('photo') || selectedMessage.classList.contains('document')) {
                  editOption.style.display = 'none';
               } else {
                  editOption.style.display = 'flex';
               }

               if (senderId === <?php echo json_encode($currentUserId); ?> && (selectedMessage.classList.contains('photo') || selectedMessage.classList.contains('document'))) {
                  editCaptionOption.style.display = 'flex';
               } else {
                  editCaptionOption.style.display = 'none';
               }

               deleteOption.style.display = 'flex';
            }
         }

         popupMenu.style.left = `${x}px`;
         popupMenu.style.top = `${y}px`;
         popupMenu.style.display = 'block';
         document.addEventListener('click', hidePopupMenu);
      }

      function hidePopupMenu() {
         const popupMenu = document.getElementById('popup-menu');
         popupMenu.style.display = 'none';
         document.removeEventListener('click', hidePopupMenu);
      }

      function showDeleteConfirmation() {
         if (!selectedMessage) {
            showPopup("No message selected for deletion.");
            return;
         }

         const confirmationPopup = document.getElementById('delete-confirmation');
         const chatWindow = document.getElementById('chat-window');
         const dimOverlay = document.getElementById('dim-overlay');

         const chatWindowRect = chatWindow.getBoundingClientRect();
         const centerX = chatWindowRect.left + (chatWindowRect.width / 2);
         const centerY = chatWindowRect.top + (chatWindowRect.height / 2);

         const confirmationRect = confirmationPopup.getBoundingClientRect();
         const confirmationWidth = confirmationRect.width;
         const confirmationHeight = confirmationRect.height;

         const verticalOffset = -140;
         const horizontalOffset = -330;
         const adjustedX = centerX - (confirmationWidth / 2) + horizontalOffset;
         const adjustedY = centerY - (confirmationHeight / 2) + verticalOffset;

         confirmationPopup.style.left = `${adjustedX}px`;
         confirmationPopup.style.top = `${adjustedY}px`;
         confirmationPopup.style.display = 'block';
         dimOverlay.style.display = 'block';
         hidePopupMenu();
      }

      function confirmDelete() {
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
                  refreshUnseenCounts([3, 5]);
                  pullMessages();
                  hideDeleteConfirmation();
               } else {
                  showPopup('Error deleting message');
                  hideDeleteConfirmation();
               }
            })
            .catch(error => {
               console.error('An error occurred while deleting the message:', error);
               showPopup('An error occurred while deleting the message: ' + error.message);
               hideDeleteConfirmation();
            });
      }

      function cancelDelete() {
         hideDeleteConfirmation();
      }

      function hideDeleteConfirmation() {
         const confirmationPopup = document.getElementById('delete-confirmation');
         const dimOverlay = document.getElementById('dim-overlay');
         confirmationPopup.style.display = 'none';
         dimOverlay.style.display = 'none';
      }

      function editMessage() {
         if (!selectedMessage) {
            showPopup("No message selected for editing.");
            return;
         }

         const messageId = selectedMessage.getAttribute('data-message-id');
         const currentText = selectedMessage.querySelector('p').textContent;
         const newMessage = prompt("Edit your message:", currentText);

         if (newMessage === null || newMessage.trim() === "") {
            showPopup("Message cannot be empty.");
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
                  refreshUnseenCounts([3, 5]);
                  pullMessages();
                  hidePopupMenu();
               } else {
                  showPopup(data.message || "Error editing message.");
               }
            })
            .catch(error => {
               console.error("Error editing message:", error);
               showPopup("Error editing message: " + error.message);
            });
      }

      function editCaption() {
         if (!selectedMessage) {
            showPopup("No message selected for editing caption.");
            return;
         }

         const messageId = selectedMessage.getAttribute('data-message-id');
         const currentCaption = selectedMessage.querySelector('.caption')?.textContent || '';
         const newCaption = prompt("Edit caption:", currentCaption);

         if (newCaption === null) {
            return;
         }

         fetch(`<?= ROOT ?>/ChatController/editCaption`, {
               method: "POST",
               headers: {
                  "Content-Type": "application/json",
               },
               body: JSON.stringify({
                  messageId: messageId,
                  newCaption: newCaption.trim(),
               }),
            })
            .then(response => response.json())
            .then(data => {
               if (data.status === "success") {
                  refreshUnseenCounts([3, 5]);
                  pullMessages();
                  hidePopupMenu();
               } else {
                  showPopup(data.message || "Error editing caption.");
               }
            })
            .catch(error => {
               console.error("Error editing caption:", error);
            });
      }

      function closeChat() {
         if (!selectedUserId) {
            alert("No chat is currently selected.");
            return;
         }

         selectedUserId = null;
         isMessageSearching = false;
         document.getElementById('search-input-message').value = '';

         // Reset the chat window UI
         document.getElementById('chat-username').textContent = 'Select a user';
         document.getElementById('chat-status').textContent = 'Offline';
         document.getElementById('chat-avatar').src = '<?= ROOT ?>/assets/images/users/Profile_default.png';
         document.getElementById('chat-messages').innerHTML = '';
         document.getElementById('message-input').disabled = true; // Disable input when no chat is selected

         hidePopupMenu();
      }

      function selectChat(chatItem, userId) {
         selectedUserId = userId;
         const username = chatItem.querySelector('.chat-info h4').textContent;
         const userStatus = chatItem.querySelector('.chat-status').textContent;
         const userTime = chatItem.querySelector('.chat-time').textContent;
         const avatarSrc = chatItem.querySelector('.avatar').src;

         document.getElementById('chat-username').textContent = username;
         document.getElementById('chat-status').textContent = userTime;
         document.getElementById('chat-avatar').src = avatarSrc;
         document.getElementById('message-input').disabled = false; // Enable input when a chat is selected
         document.getElementById('chat-input').style.display = 'flex'; // Show the chat-input div

         isMessageSearching = false;
         document.getElementById('search-input-message').value = '';
         startChat(userId);
         markMessagesAsSeen(userId);
      }

      function closeChat() {
         if (!selectedUserId) {
            alert("No chat is currently selected.");
            return;
         }

         selectedUserId = null;
         isMessageSearching = false;
         document.getElementById('search-input-message').value = '';

         // Reset the chat window UI
         document.getElementById('chat-username').textContent = 'Select a user';
         document.getElementById('chat-status').textContent = 'Offline';
         document.getElementById('chat-avatar').src = '<?= ROOT ?>/assets/images/users/Profile_default.png';
         document.getElementById('chat-messages').innerHTML = '';
         document.getElementById('message-input').disabled = true; // Disable input when no chat is selected
         document.getElementById('chat-input').style.display = 'none'; // Hide the chat-input div

         hidePopupMenu();
      }

      function escapeHTML(str) {
         if (!str) return '';
         const div = document.createElement('div');
         div.textContent = str;
         return div.innerHTML;
      }

      function renderMessage(message, chatMessages, isInitialRender = false, unseenCount = 0, index = 0, insertedUnseenLine = {
         value: false
      }, searchQuery = '') {
         const messageDate = new Date(message.date);
         const formattedTime = formatTimeOrDate(messageDate);
         const currentDate = messageDate.toDateString();

         if (isInitialRender) {
            const lastDate = chatMessages.lastDate || null;
            if (lastDate !== currentDate) {
               const dateHeader = document.createElement('div');
               dateHeader.classList.add('date-header');
               dateHeader.innerHTML = `<span>${getDateHeader(messageDate)}</span>`;
               chatMessages.appendChild(dateHeader);
               chatMessages.lastDate = currentDate;
            }
         }

         const div = document.createElement('div');
         div.classList.add('message', message.sender == selectedUserId ? 'received' : 'sent');
         div.setAttribute('data-message-id', message.id);

         function highlightText(text, query) {
            if (!query || !isMessageSearching) return escapeHTML(text);
            const regex = new RegExp(`(${query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
            return escapeHTML(text).replace(regex, '<span class="highlight">$1</span>');
         }

         if (message.type === 'text') {
            div.innerHTML = `
                <p>${highlightText(message.message, searchQuery)}</p>
                <span class="time">${message.edited ? '<span class="edited-label">(edited)</span>' : ''} ${formattedTime}</span>
            `;
         } else if (message.type === 'photo') {
            div.classList.add('photo');
            const fileName = message.file_path.split('/').pop();
            div.innerHTML = `
                <img src="<?= ROOT ?>/${message.file_path}" alt="Photo">
                ${message.caption ? `<div class="caption">${highlightText(message.caption, searchQuery)}</div>` : ''}
                <div class="message-actions">
                    <button onclick="openFile('<?= ROOT ?>/${message.file_path}')">Open</button>
                    <button onclick="downloadFile('<?= ROOT ?>/${message.file_path}', '${escapeHTML(fileName)}')">Save as...</button>
                </div>
                <span class="time">${message.edited ? '<span class="edited-label">(edited)</span>' : ''} ${formattedTime}</span>
            `;
         } else if (message.type === 'document') {
            div.classList.add('document');
            const maxLength = 40;
            let displayName = message.message;
            if (displayName.length > maxLength) {
               displayName = displayName.substring(0, maxLength - 3) + '...';
            }
            const fileSize = message.file_size || 'Unknown';
            let iconClass;
            let fileTypeDisplay = message.file_type || 'Document';
            const extension = message.message.split('.').pop().toLowerCase();
            if (extension === 'doc' || extension === 'docx') {
               iconClass = 'fa-file-word';
               fileTypeDisplay = message.file_type || 'Microsoft Word Document';
            } else if (extension === 'xls' || extension === 'xlsx') {
               iconClass = 'fa-file-excel';
               fileTypeDisplay = message.file_type || 'Microsoft Excel Document';
            } else if (extension === 'pdf') {
               iconClass = 'fa-file-pdf';
               fileTypeDisplay = message.file_type || 'PDF Document';
            } else if (extension === 'txt') {
               iconClass = 'fa-file';
               fileTypeDisplay = message.file_type || 'Text Document';
            } else if (extension === 'csv') {
               iconClass = 'fa-file';
               fileTypeDisplay = message.file_type || 'CSV Document';
            } else if (extension === 'rtf') {
               iconClass = 'fa-file';
               fileTypeDisplay = message.file_type || 'RTF Document';
            } else if (extension === 'zip') {
               iconClass = 'fa-file';
               fileTypeDisplay = message.file_type || 'ZIP Archive';
            } else {
               iconClass = 'fa-file';
               fileTypeDisplay = message.file_type || 'Document';
            }
            const fileName = message.file_path.split('/').pop();
            div.innerHTML = `
                <div class="file-frame">
                    <i class="fa-solid ${iconClass} doc-icon"></i>
                    <p>${highlightText(displayName, searchQuery)}</p>
                </div>
                <div class="file-details">${fileSize}, ${fileTypeDisplay}</div>
                <hr>
                ${message.caption ? `<div class="caption">${highlightText(message.caption, searchQuery)}</div>` : ''}
                <div class="message-actions">
                    <button onclick="openFile('<?= ROOT ?>/${message.file_path}')">Open</button>
                    <button onclick="downloadFile('<?= ROOT ?>/${message.file_path}', '${escapeHTML(fileName)}')">Save as...</button>
                </div>
                <span class="time">${message.edited ? '<span class="edited-label">(edited)</span>' : ''} ${formattedTime}</span>
            `;
         }

         if (isInitialRender && unseenCount > 0 && index === (data.messages.length - unseenCount) && !insertedUnseenLine.value) {
            const unseenLine = document.createElement('div');
            unseenLine.classList.add('unseen-line');
            unseenLine.innerHTML = `<span>${unseenCount} unread message${unseenCount !== 1 ? 's' : ''}</span>`;
            chatMessages.appendChild(unseenLine);
            insertedUnseenLine.value = true;
         }

         chatMessages.appendChild(div);
      }

      async function startChat(receiverId) {
         try {
            const response = await fetch(`<?= ROOT ?>/ChatController/getMessages/${receiverId}`);
            if (!response.ok) {
               throw new Error('Failed to fetch messages');
            }
            const data = await response.json();
            const chatMessages = document.getElementById("chat-messages");
            chatMessages.innerHTML = '';
            chatMessages.lastDate = null;

            const unseenCount = unseenCountsMap[receiverId] || 0;
            let insertedUnseenLine = {
               value: false
            };

            data.messages.forEach((message, index) => {
               renderMessage(message, chatMessages, true, unseenCount, index, insertedUnseenLine);
            });

            chatMessages.scrollTop = chatMessages.scrollHeight;
         } catch (error) {
            console.error('Error fetching messages:', error);
         }
      }

      let lastMessageId = null;

      function pullMessages() {
         if (selectedUserId && !isMessageSearching) {
            fetch(`<?= ROOT ?>/ChatController/getMessages/${selectedUserId}`)
               .then(response => {
                  if (!response.ok) {
                     throw new Error('Failed to fetch messages');
                  }
                  return response.json();
               })
               .then(data => {
                  if (data.messages.length > 0) {
                     const latestMessage = data.messages[data.messages.length - 1];
                     const chatMessages = document.getElementById("chat-messages");
                     chatMessages.innerHTML = '';
                     chatMessages.lastDate = null;

                     data.messages.forEach((message, index) => {
                        renderMessage(message, chatMessages, true);
                     });

                     if (lastMessageId !== latestMessage.id) {
                        chatMessages.scrollTop = chatMessages.scrollHeight;
                        lastMessageId = latestMessage.id;
                     }
                  }
               })
               .catch(error => {
                  console.error('Error fetching messages:', error);
                  showPopup('Error fetching messages: ' + error.message);
               });
         }
      }
      setInterval(pullMessages, 2000);

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
            showPopup("Please select a chat.");
            return;
         }

         const formData = new FormData();
         formData.append('message', message);
         formData.append('receiver', selectedUserId);
         formData.append('type', 'text');

         fetch('<?= ROOT ?>/ChatController/sendMessage', {
               method: 'POST',
               body: formData
            })
            .then(response => {
               if (!response.ok) {
                  throw new Error(`HTTP error! Status: ${response.status}`);
               }
               return response.json();
            })
            .then(data => {
               if (data.status === "success") {
                  messageInput.value = '';
                  const unseenLine = document.querySelector('.unseen-line');
                  if (unseenLine) {
                     unseenLine.remove();
                  }
                  unseenCountsMap[selectedUserId] = 0;
                  pullMessages();
                  refreshUnseenCounts([3, 5]);
               } else {
                  console.error('Server responded with failure:', data);
                  showPopup(data.message || 'Error sending message');
               }
            })
            .catch(error => {
               console.error("Error sending message:", error);
               showPopup(`Error sending message: ${error.message}`);
            });
      }

      function showUploadPopup() {
         const popup = document.getElementById('upload-popup');
         popup.style.display = popup.style.display === 'block' ? 'none' : 'block';
         document.addEventListener('click', hideUploadPopup);
      }

      function hideUploadPopup(event) {
         const popup = document.getElementById('upload-popup');
         const uploadIcon = document.querySelector('.upload');
         if (!uploadIcon.contains(event.target) && !popup.contains(event.target)) {
            popup.style.display = 'none';
            document.removeEventListener('click', hideUploadPopup);
         }
      }

      function triggerFileUpload(type) {
         selectedFileType = type;
         const fileInput = document.getElementById('file-upload');
         if (type === 'photo') {
            fileInput.accept = 'image/*';
         } else if (type === 'document') {
            fileInput.accept = 'application/pdf,.doc,.docx,.xls,.xlsx,.txt,.csv,.rtf,.zip';
         }
         fileInput.click();
      }

      function handleFileSelection(files) {
         if (!selectedUserId) {
            showPopup("Please select a chat.");
            return;
         }

         if (files.length === 0) return;

         const file = files[0];
         const fileType = file.type;

         if (selectedFileType === 'photo' && !fileType.startsWith('image/')) {
            showPopup("Please upload an image file.");
            return;
         } else if (selectedFileType === 'document') {
            const allowedDocumentTypes = [
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
            if (!allowedDocumentTypes.includes(fileType)) {
               showPopup("Invalid document format. Only PDF, Word, Excel, TXT, CSV, RTF, and ZIP files are allowed.");
               return;
            }
         }

         selectedFile = file;

         const previewArea = document.getElementById('preview-area');
         const previewContent = document.getElementById('preview-content');
         previewContent.innerHTML = '';

         if (selectedFileType === 'photo') {
            const img = document.createElement('img');
            img.src = URL.createObjectURL(file);
            previewContent.appendChild(img);
         } else if (selectedFileType === 'document') {
            const div = document.createElement('div');
            div.classList.add('document-preview');
            const maxLength = 40;
            let displayName = file.name;
            if (displayName.length > maxLength) {
               displayName = displayName.substring(0, maxLength - 3) + '...';
            }
            const fileSize = (file.size / (1024 * 1024)).toFixed(1) + ' MB';
            let fileTypeDisplay = 'Document';
            let iconClass;
            if (fileType === 'application/pdf') {
               fileTypeDisplay = 'PDF Document';
               iconClass = 'fa-file-pdf';
            } else if (fileType.includes('msword') || fileType.includes('officedocument.wordprocessingml')) {
               fileTypeDisplay = 'Microsoft Word Document';
               iconClass = 'fa-file-word';
            } else if (fileType.includes('vnd.ms-excel') || fileType.includes('spreadsheetml')) {
               fileTypeDisplay = 'Microsoft Excel Document';
               iconClass = 'fa-file-excel';
            } else if (fileType === 'text/plain') {
               fileTypeDisplay = 'Text Document';
               iconClass = 'fa-file';
            } else if (fileType === 'text/csv') {
               fileTypeDisplay = 'CSV Document';
               iconClass = 'fa-file';
            } else if (fileType === 'application/rtf') {
               fileTypeDisplay = 'RTF Document';
               iconClass = 'fa-file';
            } else if (fileType === 'application/zip') {
               fileTypeDisplay = 'ZIP Archive';
               iconClass = 'fa-file';
            } else {
               fileTypeDisplay = 'Document';
               iconClass = 'fa-file';
            }
            div.innerHTML = `
                <div class="file-frame">
                    <i class="fa-solid ${iconClass} doc-icon"></i>
                    <p>${escapeHTML(displayName)}</p>
                </div>
                <div class="file-details">${fileSize}, ${fileTypeDisplay}</div>
            `;
            previewContent.appendChild(div);
         }

         previewArea.style.display = 'block';
         isNotificationDismissed = false;
         setTimeout(() => {
            document.addEventListener('click', handleOutsideClick);
         }, 0);
      }

      let isNotificationDismissed = false;
      let ignoreNextOutsideClick = false;

      function handleOutsideClick(event) {
         const previewArea = document.getElementById('preview-area');
         const uploadPopup = document.getElementById('upload-popup');
         const uploadIcon = document.querySelector('.upload');
         const notification = document.getElementById('discard-notification');

         if (ignoreNextOutsideClick) {
            ignoreNextOutsideClick = false;
            return;
         }

         if (notification.style.display === 'block') {
            return;
         }

         if (
            !previewArea.contains(event.target) &&
            !uploadPopup.contains(event.target) &&
            !uploadIcon.contains(event.target) &&
            previewArea.style.display === 'block'
         ) {
            showDiscardNotification();
         }
      }

      function showDiscardNotification() {
         const notification = document.getElementById('discard-notification');
         const chatWindow = document.getElementById('chat-window');
         const dimOverlay = document.getElementById('dim-overlay');

         const chatWindowRect = chatWindow.getBoundingClientRect();
         const centerX = chatWindowRect.left + (chatWindowRect.width / 2);
         const centerY = chatWindowRect.top + (chatWindowRect.height / 2);

         const notificationRect = notification.getBoundingClientRect();
         const notificationWidth = notificationRect.width;
         const notificationHeight = notificationRect.height;

         const verticalOffset = -140;
         const horizontalOffset = -330;
         const adjustedX = centerX - (notificationWidth / 2) + horizontalOffset;
         const adjustedY = centerY - (notificationHeight / 2) + verticalOffset;

         notification.style.left = `${adjustedX}px`;
         notification.style.top = `${adjustedY}px`;
         notification.style.display = 'block';
         dimOverlay.style.display = 'block';
         isNotificationDismissed = false;
      }

      function discardFile() {
         const previewArea = document.getElementById('preview-area');
         const fileInput = document.getElementById('file-upload');
         const notification = document.getElementById('discard-notification');
         const dimOverlay = document.getElementById('dim-overlay');

         previewArea.style.display = 'none';
         fileInput.value = '';
         document.getElementById('caption-input').value = '';
         selectedFile = null;
         selectedFileType = null;
         notification.style.display = 'none';
         dimOverlay.style.display = 'none';
         isNotificationDismissed = false;
         document.removeEventListener('click', handleOutsideClick);
      }

      function returnToMedia() {
         const notification = document.getElementById('discard-notification');
         const previewArea = document.getElementById('preview-area');
         const dimOverlay = document.getElementById('dim-overlay');

         notification.style.display = 'none';
         dimOverlay.style.display = 'none';
         previewArea.style.display = 'block';
         ignoreNextOutsideClick = true;
      }

      function sendFile() {
         if (!selectedFile || !selectedUserId) {
            showPopup("Please select a file and a chat.");
            return;
         }

         const caption = document.getElementById('caption-input').value.trim();
         const formData = new FormData();
         formData.append('file', selectedFile);
         formData.append('receiver', selectedUserId);
         formData.append('type', selectedFileType);
         if (caption) {
            formData.append('caption', caption);
         }

         fetch('<?= ROOT ?>/ChatController/sendMessage', {
               method: 'POST',
               body: formData
            })
            .then(response => {
               if (!response.ok) {
                  throw new Error(`HTTP error! Status: ${response.status}`);
               }
               return response.json();
            })
            .then(data => {
               if (data.status === "success") {
                  document.getElementById('file-upload').value = '';
                  document.getElementById('caption-input').value = '';
                  document.getElementById('preview-area').style.display = 'none';
                  selectedFile = null;
                  selectedFileType = null;
                  const unseenLine = document.querySelector('.unseen-line');
                  if (unseenLine) {
                     unseenLine.remove();
                  }
                  unseenCountsMap[selectedUserId] = 0;
                  pullMessages();
                  refreshUnseenCounts([3, 5]);
                  document.removeEventListener('click', handleOutsideClick);
                  isNotificationDismissed = false;
               } else {
                  console.error('Server responded with failure:', data);
                  showPopup('Server responded with failure: ' + (data.message || 'Unknown error'));
               }
            })
            .catch(error => {
               console.error("Error uploading file:", error);
               showPopup("Error uploading file: " + error.message);
            });
      }

      function openFile(url) {
         window.open(url, '_blank');
      }

      function downloadFile(url, fileName) {
         const cleanFileName = fileName.replace(/[^a-zA-Z0-9\.\-_]/g, '_');
         const a = document.createElement('a');
         a.href = url;
         a.download = cleanFileName;
         document.body.appendChild(a);
         a.click();
         document.body.removeChild(a);
      }

      setInterval(refreshUserStatuses, 3000);

      function refreshUserStatuses() {
         const xhr = new XMLHttpRequest();
         xhr.open("GET", "<?= ROOT ?>/ChatController/getUserStatuses", true);
         xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
               if (xhr.status == 200) {
                  const users = JSON.parse(xhr.responseText);
                  users.forEach(user => {
                     const chatItem = document.querySelector(`.chat-item[data-receiver-id="${user.id}"]`);
                     if (chatItem) {
                        const statusElement = chatItem.querySelector('.chat-status');
                        statusElement.textContent = user.state ? 'Online' : 'Offline';
                     }
                  });
               } else {
                  console.error('Failed to refresh user statuses:', xhr.status);
               }
            }
         };
         xhr.send();
      }

      function formatTimeOrDate(messageDate) {
         return messageDate.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
         });
      }

      function getDateHeader(messageDate) {
         const today = new Date();
         const yesterday = new Date(today);
         yesterday.setDate(today.getDate() - 1);
         const oneWeekAgo = new Date(today);
         oneWeekAgo.setDate(today.getDate() - 7);

         const isToday = messageDate.toDateString() === today.toDateString();
         const isYesterday = messageDate.toDateString() === yesterday.toDateString();
         const isWithinWeek = messageDate > oneWeekAgo && messageDate < yesterday;

         if (isToday) {
            return "Today";
         } else if (isYesterday) {
            return "Yesterday";
         } else if (isWithinWeek) {
            return messageDate.toLocaleDateString('en-US', {
               weekday: 'long'
            });
         } else {
            return messageDate.toLocaleDateString('en-GB', {
               day: '2-digit',
               month: '2-digit',
               year: 'numeric'
            });
         }
      }

      function formatChatListDate(messageDate) {
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

      function searchUsers(query) {
         const chatList = document.getElementById('chat-list');

         if (!query.trim()) {
            isSearching = false;
            refreshUnseenCounts([3, 5]);
            return;
         }

         isSearching = true;

         fetch(`<?= ROOT ?>/ChatController/searchUser?query=${encodeURIComponent(query)}`)
            .then(response => {
               if (!response.ok) {
                  throw new Error('Failed to search users');
               }
               return response.json();
            })
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
                     lastMessageDisplay = formatChatListDate(messageDate);
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
                           <p class="chat-status">${getRoleTitle(user.role)}</p>
                           <p hidden class="chat-time">${user.state ? 'Online' : 'Offline'}</p>
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
            .catch(error => {
               console.error("Error searching users:", error);
            });
      }

      function refreshUnseenCounts(roleArray) {
         if (isSearching) return;

         const roles = roleArray.join(',');

         fetch(`<?= ROOT ?>/ChatController/getUnseenCounts?roles=${roles}`)
            .then(response => {
               if (!response.ok) {
                  throw new Error('Failed to fetch unseen counts');
               }
               return response.json();
            })
            .then(users => {
               if (users.error) {
                  console.error("Error:", users.error);
                  return;
               }

               unseenCountsMap = {};
               users.forEach(user => {
                  unseenCountsMap[user.id] = user.unseen_count || 0;
               });

               const chatList = document.getElementById('chat-list');
               chatList.innerHTML = '';

               users.forEach(user => {
                  const unseenClass = user.unseen_count > 0 ? 'unseen' : '';
                  let lastMessageDisplay = '';
                  if (user.last_message_date) {
                     const messageDate = new Date(user.last_message_date);
                     lastMessageDisplay = formatChatListDate(messageDate);
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
                           <p class="chat-status">${getRoleTitle(user.role)}</p>
                           <p hidden class="chat-time">${user.state ? 'Online' : 'Offline'}</p>
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
            .catch(error => {
               console.error("Error fetching unseen counts:", error);
               showPopup("Error fetching unseen counts: " + error.message);
            });
      }

      setInterval(() => {
         if (!isSearching) {
            refreshUnseenCounts([3, 5]);
         }
      }, 1000);

      function searchMessage(query) {
         if (!selectedUserId) {
            alert("Please select a chat.");
            return;
         }

         const chatMessages = document.getElementById("chat-messages");
         chatMessages.innerHTML = '';
         chatMessages.lastDate = null;

         if (!query.trim()) {
            isMessageSearching = false;
            startChat(selectedUserId);
            return;
         }

         isMessageSearching = true;

         fetch(`<?= ROOT ?>/ChatController/searchMessage/${selectedUserId}?query=${encodeURIComponent(query)}`)
            .then(response => {
               if (!response.ok) {
                  throw new Error('Failed to search messages');
               }
               return response.json();
            })
            .then(data => {
               const unseenCount = unseenCountsMap[selectedUserId] || 0;
               let insertedUnseenLine = {
                  value: false
               };

               if (data.messages.length === 0) {
                  chatMessages.innerHTML = '<div class="no-messages">No messages found.</div>';
                  return;
               }

               data.messages.forEach((message, index) => {
                  renderMessage(message, chatMessages, true, unseenCount, index, insertedUnseenLine, query);
               });

               chatMessages.scrollTop = chatMessages.scrollHeight;
            })
            .catch(error => {
               console.error('Error searching messages:', error);
               chatMessages.innerHTML = '<div class="no-messages">Error searching messages.</div>';
            });
      }

      function markMessagesAsSeen(receiverId) {
         fetch(`<?= ROOT ?>/ChatController/markMessagesSeen/${receiverId}`)
            .then(response => {
               if (!response.ok) {
                  throw new Error('Failed to mark messages as seen');
               }
               const chatItem = document.querySelector(`.chat-item[data-receiver-id="${receiverId}"]`);
               if (chatItem) chatItem.classList.remove('unseen');
            })
            .catch(error => {
               console.error("Error marking messages as seen:", error);
            });
      }

      function updateReceivedState() {
         fetch('<?= ROOT ?>/ChatController/updateReceivedState')
            .catch(error => {
               console.error("Error updating timestamps:", error);
            });
      }

      setInterval(updateReceivedState, 3000);

      // Initialize the message input as disabled until a chat is selected
      document.getElementById('message-input').disabled = true;
   </script>
</body>

</html>//1321