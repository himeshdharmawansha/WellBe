<?php
require_once(__DIR__ . "/../../controllers/ChatController.php");
require_once(__DIR__ . "/../../models/ProfileModel.php");

$he = new ChatController();
$profileModel = new ProfileModel();

$unseenCounts = $he->UnseenCounts([1, 2, 4, 5]);
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
   <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Admin/message.css">
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
         <li onclick="editCaption()" style="padding: 8px; cursor: pointer; display: none;" id="edit-caption-option">
            <i class="fa-solid fa-pen"></i> Edit Caption
         </li>
      </ul>
   </div>

   <!-- Add this just before the discard-notification div -->
   <div id="dim-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 99;"></div>

   <div id="discard-notification" style="display: none; position: fixed; background: #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2); border-radius: 8px; padding: 15px; z-index: 100;">
      <h4>Discard unsent message?</h4>
      <p>Your message, including attached media, will not be sent if you leave this screen.</p>
      <div class="notification-actions">
         <button onclick="discardFile()" class="discard-btn">Discard</button>
         <button onclick="returnToMedia()" class="return-btn">Return to media</button>
      </div>
   </div>

   <div id="discard-notification" style="display: none; position: fixed; background: #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.2); border-radius: 8px; padding: 15px; z-index: 100;">
      <h4>Discard unsent message?</h4>
      <p>Your message, including attached media, will not be sent if you leave this screen.</p>
      <div class="notification-actions">
         <button onclick="discardFile()" class="discard-btn">Discard</button>
         <button onclick="returnToMedia()" class="return-btn">Return to media</button>
      </div>
   </div>

   <script src="<?= ROOT ?>/assets/js/Admin/message.js"></script>
   <script>
      let selectedUserId = null;
      let selectedMessage = null;
      let selectedFile = null;
      let selectedFileType = null;

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
         const editCaptionOption = popupMenu.querySelector('#edit-caption-option');

         if (selectedMessage) {
            const senderId = selectedMessage.classList.contains('received') ?
               selectedUserId :
               <?php echo json_encode($currentUserId); ?>;

            // Show/hide "Edit" option for text messages
            if (senderId !== <?php echo json_encode($currentUserId); ?> || selectedMessage.classList.contains('photo') || selectedMessage.classList.contains('document')) {
               editOption.style.display = 'none';
            } else {
               editOption.style.display = 'block';
            }

            // Show/hide "Edit Caption" option for photo and document messages
            if (senderId === <?php echo json_encode($currentUserId); ?> && (selectedMessage.classList.contains('photo') || selectedMessage.classList.contains('document'))) {
               editCaptionOption.style.display = 'block';
            } else {
               editCaptionOption.style.display = 'none';
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
                     refreshUnseenCounts([1, 2, 4, 5]);
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
                  refreshUnseenCounts([1, 2, 4, 5]);
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

      function editCaption() {
         if (!selectedMessage) {
            alert("No message selected for editing caption.");
            return;
         }

         const messageId = selectedMessage.getAttribute('data-message-id');
         const currentCaption = selectedMessage.querySelector('.caption')?.textContent || '';
         const newCaption = prompt("Edit caption:", currentCaption);

         if (newCaption === null) {
            return; // User canceled the prompt
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
                  refreshUnseenCounts([1, 2, 4, 5]);
                  pollMessages();
                  hidePopupMenu();
               } else {
                  alert(data.message || "Error editing caption.");
               }
            })
            .catch(error => {
               console.error("Error editing caption:", error);
               alert("Error editing caption.");
            });
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

      function escapeHTML(str) {
         const div = document.createElement('div');
         div.textContent = str;
         return div.innerHTML;
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

                  if (message.type === 'text') {
                     div.innerHTML = `
                  <p>${escapeHTML(message.message)}</p>
                  <span class="time">${message.edited ? '<span class="edited-label">(edited)</span>' : ''} ${formattedDate}</span>
               `;
                  } else if (message.type === 'photo') {
                     div.classList.add('photo');
                     div.innerHTML = `
                  <img src="<?= ROOT ?>/${message.file_path}" alt="Photo">
                  ${message.caption ? `<div class="caption">${escapeHTML(message.caption)}</div>` : ''}
                  <div class="message-actions">
                     <button onclick="openFile('<?= ROOT ?>/${message.file_path}')">Open</button>
                     <button onclick="downloadFile('<?= ROOT ?>/${message.file_path}', '${escapeHTML(message.message)}')">Save as...</button>
                  </div>
                  <span class="time">${message.edited ? '<span class="edited-label">(edited)</span>' : ''} ${formattedDate}</span>
               `;
                  } else if (message.type === 'document') {
                     div.classList.add('document');
                     // Truncate file name to 40 characters and append "..."
                     const maxLength = 40;
                     let displayName = message.message;
                     if (displayName.length > maxLength) {
                        displayName = displayName.substring(0, maxLength - 3) + '...';
                     }
                     // Use placeholder values for file size
                     const fileSize = message.file_size || '1.7 MB'; // Replace with actual file size
                     // Determine the icon and file type display based on file extension from message.message
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
                     div.innerHTML = `
                  <div class="file-frame">
                     <i class="fa-solid ${iconClass} doc-icon"></i>
                     <p>${escapeHTML(displayName)}</p>
                  </div>
                  <div class="file-details">${fileSize}, ${fileTypeDisplay}</div>
                  <hr>
                  ${message.caption ? `<div class="caption">${escapeHTML(message.caption)}</div>` : ''}
                  <div class="message-actions">
                     <button onclick="openFile('<?= ROOT ?>/${message.file_path}')">Open</button>
                     <button onclick="downloadFile('<?= ROOT ?>/${message.file_path}', '${escapeHTML(message.message)}')">Save as...</button>
                  </div>
                  <span class="time">${message.edited ? '<span class="edited-label">(edited)</span>' : ''} ${formattedDate}</span>
               `;
                  }

                  chatMessages.appendChild(div);
               });
               chatMessages.scrollTop = chatMessages.scrollHeight;
            }
         } catch (error) {
            console.error('Error fetching messages:', error);
         }
      }

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

                        if (message.type === 'text') {
                           div.innerHTML = `
                        <p>${escapeHTML(message.message)}</p>
                        <span class="time">${message.edited ? '<span class="edited-label">(edited)</span>' : ''} ${formattedDate}</span>
                     `;
                        } else if (message.type === 'photo') {
                           div.classList.add('photo');
                           div.innerHTML = `
                        <img src="<?= ROOT ?>/${message.file_path}" alt="Photo">
                        ${message.caption ? `<div class="caption">${escapeHTML(message.caption)}</div>` : ''}
                        <div class="message-actions">
                           <button onclick="openFile('<?= ROOT ?>/${message.file_path}')">Open</button>
                           <button onclick="downloadFile('<?= ROOT ?>/${message.file_path}', '${escapeHTML(message.message)}')">Save as...</button>
                        </div>
                        <span class="time">${message.edited ? '<span class="edited-label">(edited)</span>' : ''} ${formattedDate}</span>
                     `;
                        } else if (message.type === 'document') {
                           div.classList.add('document');
                           // Truncate file name to 40 characters and append "..."
                           const maxLength = 40;
                           let displayName = message.message;
                           if (displayName.length > maxLength) {
                              displayName = displayName.substring(0, maxLength - 3) + '...';
                           }
                           // Use placeholder values for file size
                           const fileSize = message.file_size || '1.7 MB'; // Replace with actual file size
                           // Determine the icon and file type display based on file extension from message.message
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
                           div.innerHTML = `
                        <div class="file-frame">
                           <i class="fa-solid ${iconClass} doc-icon"></i>
                           <p>${escapeHTML(displayName)}</p>
                        </div>
                        <div class="file-details">${fileSize}, ${fileTypeDisplay}</div>
                        <hr>
                        ${message.caption ? `<div class="caption">${escapeHTML(message.caption)}</div>` : ''}
                        <div class="message-actions">
                           <button onclick="openFile('<?= ROOT ?>/${message.file_path}')">Open</button>
                           <button onclick="downloadFile('<?= ROOT ?>/${message.file_path}', '${escapeHTML(message.message)}')">Save as...</button>
                        </div>
                        <span class="time">${message.edited ? '<span class="edited-label">(edited)</span>' : ''} ${formattedDate}</span>
                     `;
                        }

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

         const formData = new FormData();
         formData.append('message', message);
         formData.append('receiver', selectedUserId);
         formData.append('type', 'text');

         fetch('<?= ROOT ?>/ChatController/sendMessage', {
               method: 'POST',
               body: formData
            })
            .then(response => response.json())
            .then(data => {
               if (data.status === "success") {
                  messageInput.value = '';
                  pollMessages();
                  refreshUnseenCounts([1, 2, 4, 5]);
               } else {
                  alert(data.message || 'Error sending message');
               }
            })
            .catch(error => {
               console.error("Error sending message:", error);
               alert("Error sending message.");
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
            alert("Please select a chat.");
            return;
         }

         if (files.length === 0) return;

         const file = files[0];
         const fileType = file.type;

         if (selectedFileType === 'photo' && !fileType.startsWith('image/')) {
            alert("Please upload an image file.");
            return;
         } else if (selectedFileType === 'document') {
            const allowedDocumentTypes = [
               'application/pdf', // PDF
               'application/msword', // Word (.doc)
               'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // Word (.docx)
               'application/vnd.ms-excel', // Excel (.xls)
               'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // Excel (.xlsx)
               'text/plain', // Text (.txt)
               'text/csv', // CSV (.csv)
               'application/rtf', // RTF (.rtf)
               'application/zip' // ZIP (.zip)
            ];
            if (!allowedDocumentTypes.includes(fileType)) {
               alert("Invalid document format. Only PDF, Word, Excel, TXT, CSV, RTF, and ZIP files are allowed.");
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
            // Truncate file name to 40 characters and append "..."
            const maxLength = 40;
            let displayName = file.name;
            if (displayName.length > maxLength) {
               displayName = displayName.substring(0, maxLength - 3) + '...';
            }
            // Calculate file size in MB
            const fileSize = (file.size / (1024 * 1024)).toFixed(1) + ' MB';
            // Determine file type and icon
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

         // Reset the flag when a new file is selected
         isNotificationDismissed = false;

         // Add click event listener to detect clicks outside the preview area
         setTimeout(() => {
            document.addEventListener('click', handleOutsideClick);
         }, 0);
      }

      let isNotificationDismissed = false; // This should already be at the top of your <script> tag
      let ignoreNextOutsideClick = false; // Add this new flag to prevent immediate re-triggering

      function handleOutsideClick(event) {
         const previewArea = document.getElementById('preview-area');
         const uploadPopup = document.getElementById('upload-popup');
         const uploadIcon = document.querySelector('.upload');
         const notification = document.getElementById('discard-notification');

         // If we should ignore this click (e.g., right after clicking "Return to media"), skip processing
         if (ignoreNextOutsideClick) {
            ignoreNextOutsideClick = false; // Reset the flag after ignoring the click
            return;
         }

         // If the notification is already visible, don't show it again
         if (notification.style.display === 'block') {
            return;
         }

         // Check if the click is outside the preview area, upload popup, and upload icon
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
         const dimOverlay = document.getElementById('dim-overlay'); // Get the overlay

         // Get the dimensions and position of the chat window
         const chatWindowRect = chatWindow.getBoundingClientRect();

         // Calculate the center position
         const centerX = chatWindowRect.left + (chatWindowRect.width / 2);
         const centerY = chatWindowRect.top + (chatWindowRect.height / 2);

         // Get the dimensions of the notification to offset it so it's centered
         const notificationRect = notification.getBoundingClientRect();
         const notificationWidth = notificationRect.width;
         const notificationHeight = notificationRect.height;

         // Custom position: adjust these offsets to position the notification
         const verticalOffset = -140; // Moves the notification 50px above the center (negative = up, positive = down)
         const horizontalOffset = -330; // Moves the notification horizontally (negative = left, positive = right)
         const adjustedX = centerX - (notificationWidth / 2) + horizontalOffset;
         const adjustedY = centerY - (notificationHeight / 2) + verticalOffset;

         // Position the notification
         notification.style.left = `${adjustedX}px`;
         notification.style.top = `${adjustedY}px`;
         notification.style.display = 'block';

         // Show the dim overlay
         dimOverlay.style.display = 'block';

         // Reset the dismissed flag when showing the notification
         isNotificationDismissed = false;
      }

      function discardFile() {
         console.log("discardFile called");
         const previewArea = document.getElementById('preview-area');
         const fileInput = document.getElementById('file-upload');
         const notification = document.getElementById('discard-notification');
         const dimOverlay = document.getElementById('dim-overlay'); // Get the overlay

         if (!previewArea || !fileInput || !notification || !dimOverlay) {
            console.error("One or more elements not found:", {
               previewArea,
               fileInput,
               notification,
               dimOverlay
            });
            return;
         }

         // Clear the preview and reset the file input
         previewArea.style.display = 'none';
         fileInput.value = '';
         document.getElementById('caption-input').value = '';
         selectedFile = null;
         selectedFileType = null;
         notification.style.display = 'none';

         // Hide the dim overlay
         dimOverlay.style.display = 'none';

         // Reset the flag since the preview area is closed
         isNotificationDismissed = false;

         // Remove the outside click listener
         document.removeEventListener('click', handleOutsideClick);
      }

      function returnToMedia() {
         console.log("returnToMedia called");
         const notification = document.getElementById('discard-notification');
         const previewArea = document.getElementById('preview-area');
         const dimOverlay = document.getElementById('dim-overlay'); // Get the overlay

         if (!notification || !previewArea || !dimOverlay) {
            console.error("Elements not found:", {
               notification,
               previewArea,
               dimOverlay
            });
            return;
         }

         // Hide the notification
         notification.style.display = 'none';

         // Hide the dim overlay
         dimOverlay.style.display = 'none';

         // Ensure the preview area remains visible
         previewArea.style.display = 'block';

         // Prevent the next outside click from re-showing the notification
         ignoreNextOutsideClick = true;
      }

      function sendFile() {
         if (!selectedFile || !selectedUserId) {
            alert("Please select a file and a chat.");
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
            .then(response => response.json())
            .then(data => {
               if (data.status === "success") {
                  document.getElementById('file-upload').value = '';
                  document.getElementById('caption-input').value = '';
                  document.getElementById('preview-area').style.display = 'none';
                  selectedFile = null;
                  selectedFileType = null;
                  pollMessages();
                  refreshUnseenCounts([1, 2, 4, 5]);
                  // Remove the outside click listener after sending
                  document.removeEventListener('click', handleOutsideClick);
                  // Reset the flag since the preview area is closed
                  isNotificationDismissed = false;
               } else {
                  alert(data.message || 'Error uploading file');
               }
            })
            .catch(error => {
               console.error("Error uploading file:", error);
               alert("Error uploading file.");
            });
      }

      function openFile(url) {
         window.open(url, '_blank');
      }

      function downloadFile(url, fileName) {
         // Clean the file name to remove any unwanted characters or paths
         const cleanFileName = fileName.replace(/[^a-zA-Z0-9\.\-_]/g, '_');
         const a = document.createElement('a');
         a.href = url;
         a.download = cleanFileName; // Use the cleaned file name for the "Save As" dialog
         document.body.appendChild(a);
         a.click();
         document.body.removeChild(a);
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
            refreshUnseenCounts([1, 2, 4, 5]);
         }
      }, 1000);

      function searchUsers(query) {
         const chatList = document.getElementById('chat-list');

         if (!query.trim()) {
            isSearching = false;
            refreshUnseenCounts([1, 2, 4, 5]);
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