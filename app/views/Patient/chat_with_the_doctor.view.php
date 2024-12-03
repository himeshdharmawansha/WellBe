<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Message</title>
   <link rel="stylesheet" href="<?= ROOT ?>/assets/css/Patient/message.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
</head>

<body>
   <div class="dashboard-container">
   <?php
        $this->renderComponent('navbar', $active);
        ?>
       

      <!-- Main Content -->
      <div class="main-content">
      <?php
            $pageTitle = "Message"; // Set the text you want to display
            include $_SERVER['DOCUMENT_ROOT'] . '/MVC/app/views/Components/Patient/header.php';
            ?>

         <!-- Dashboard Content -->
         <div class="dashboard-content">
            <div class="container">
               <div class="chat-list">
                  <div class="search-bar">
                     <input type="text" placeholder="Search">
                  </div>
                  <ul>
                     <li>
                        <div class="chat-item">
                           <div class="avatar"></div>
                           <div class="chat-info">
                              <h4>Kumari Siriwardana</h4>
                              <p>Sent attachment</p>
                           </div>
                           <span class="time">9:00am</span>
                        </div>
                     </li>
                     <li>
                        <div class="chat-item">
                           <div class="avatar"></div>
                           <div class="chat-info">
                              <h4>Rumesh Kannangara</h4>
                              <p>Sent attachment</p>
                           </div>
                           <span class="time">9:00am</span>
                        </div>
                     </li>
                  </ul>
               </div>

               <div class="chat-window">
                  <div class="chat-header">
                     <div class="avatar"></div>
                     <div class="header-info">
                        <h4>Kumari Siriwardana</h4>
                        <p>Online</p>
                     </div>
                  </div>
                  <div class="chat-messages">
                     <div class="message received">
                        <p>ullamco veniam, quis nostrud exer labor...</p>
                        <span class="time">11:20pm</span>
                     </div>
                     <div class="message sent">
                        <p>Lorem ipsum dolor sit amet, consectetu...</p>
                        <span class="time">11:25am</span>
                     </div>
                     <div class="message received">
                        <p>ullamco veniam, quis nostrud exer labor...</p>
                        <span class="time">11:26pm</span>
                     </div>
                     <div class="message sent">
                        <p>ullamco veniam, quis nostrud exer labor...</p>
                        <span class="time">11:25am</span>
                     </div>
                  </div>
                  <div class="chat-input">
                     <input type="text" placeholder="Type a message">
                     <button>Send</button>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>

   <script src="../Message/message.js"></script>
</body>

</html>