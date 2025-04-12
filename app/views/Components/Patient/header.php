<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Document</title>
   <style>
      .main-header {
         display: flex;
         justify-content: space-between;
         align-items: center;
         padding: 20px;
         background-color: #f0f8ff;
         border-bottom: 2px solid #d1d9f1;
      }

      .header-left h1 {
         font-size: 24px;
         color: #172554;
      }

      .header-right {
         display: flex;
         align-items: center;
      }

      .user-details {
         display: flex;
         align-items: center;
         margin-right: 20px;
         margin-left: 20px;
      }

      .user-avatar {
         width: 40px;
         height: 40px;
         border-radius: 50%;
         background-color: #d1d9f1;
         margin-right: 10px;
      }

      .user-info p {
         margin: 0;
         font-size: 14px;
         color: #172554;
      }

      .notification-icon {
         position: relative;
         padding-top: 6px;
         font-size: 28px;
         color: #a0a0a0;
      }

      .notification-badge {
         position: absolute;
         top: 3px;
         right: -4px;
         width: 14px;
         height: 14px;
         background-color: red;
         border-radius: 50%;
         border: 2px solid white;
      }

      /* Modal styles */
      #notificationModal {
         display: none;
         position: fixed;
         z-index: 999;
         left: 0;
         top: 0;
         width: 100%;
         height: 100%;
         background-color: rgba(0, 0, 0, 0.6);
         justify-content: center;
         align-items: center;
         font-family: sans-serif;
      }

      .modal-content {
         background-color: #fff;
         padding: 20px;
         border-radius: 16px;
         text-align: center;
         width: 350px;
         box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
      }

      .modal-content h3 {
         margin-bottom: 10px;
         font-size: 18px;
      }

      .modal-buttons {
         display: flex;
         flex-direction: column;
         gap: 10px;
         margin-top: 20px;
      }

      .modal-buttons button {
         padding: 10px;
         border: none;
         border-radius: 8px;
         cursor: pointer;
         font-size: 16px;
      }

      .btn-primary {
         background-color: #007bff;
         color: white;
      }

      .btn-danger {
         background-color: #dc3545;
         color: white;
      }
   </style>
</head>

<body>
   <header class="main-header">
      <div class="header-left">
         <h1><?php echo isset($pageTitle) ? $pageTitle : ''; ?></h1>
      </div>
      <div class="header-right">
         <div class="user-details">
           
            <div class="user-info">
               <p style="font-weight: bold;"><?= $_SESSION['USER']->first_name; ?> <?= $_SESSION['USER']->last_name; ?></p>
               <p style="padding-top:4px;color:#989898">Patient</p>
            </div>
         </div>
      </div>
   </header>

   <!-- Modal HTML -->
   <div id="notificationModal">
      <div class="modal-content" id="step1">
         <h3 id="appointmentMessage">Your appointment has been rescheduled.</h3>
         <div class="modal-buttons">
            <button class="btn-primary" onclick="showManageOptions()">Manage Appointment</button>
         </div>
      </div>
      <div class="modal-content" id="step2" style="display: none;">
         <h3>What would you like to do?</h3>
         <div class="modal-buttons">
            <button class="btn-primary" onclick="handleReschedule()">Reschedule</button>
            <button class="btn-danger" onclick="handleCancel()">Cancel Appointment</button>
         </div>
      </div>
   </div>
</body>

<script>
   const socket = new WebSocket('ws://localhost:8080');

   socket.addEventListener('open', () => {
      const userId = <?php echo json_encode($_SESSION['USER']->nic); ?>;
      socket.send(JSON.stringify({ type: 'register', userId }));
   });

   socket.addEventListener('message', (event) => {
      const message = event.data;
      document.getElementById('appointmentMessage').innerText = message;
      showModal();
   });

   function showModal() {
      document.getElementById('notificationModal').style.display = 'flex';
      document.getElementById('step1').style.display = 'block';
      document.getElementById('step2').style.display = 'none';
   }

   function showManageOptions() {
      document.getElementById('step1').style.display = 'none';
      document.getElementById('step2').style.display = 'block';
   }

   function handleReschedule() {
      document.getElementById('notificationModal').style.display = 'none';
      window.location.href = `http://localhost/WellBe/public/patient/doc_appointment`;
   }

   function handleCancel() {
      document.getElementById('notificationModal').style.display = 'none';
      alert('Appointment canceled.');
      const userId = <?php echo json_encode($_SESSION['USER']->id); ?>;
      window.location.href = `http://localhost/WellBe/public/patient/refund`;
   }
</script>

</html>
