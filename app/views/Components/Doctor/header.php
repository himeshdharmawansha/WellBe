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
   </style>
</head>

<body>
   <header class="main-header">
      <div class="header-left">
         <h1><?php echo isset($pageTitle) ? $pageTitle : ''; ?></h1>
      </div>
      <div class="header-right">
         <div class="notification-icon">
            <i class="fas fa-bell"></i>
            <span class="notification-badge"></span>
         </div>
         <div class="user-details">
            <div class="user-avatar"></div>
            <div class="user-info">
               <p style="font-weight: bold;"><?php echo $_SESSION['USER']->first_name  ?></p>
               <p style="padding-top:4px;color:#989898">Doctor</p>
            </div>
         </div>
      </div>
   </header>
</body>

</html>