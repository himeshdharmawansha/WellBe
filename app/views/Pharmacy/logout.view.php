<?php

if (isset($_SESSION['USER'])) {
   unset($_SESSION['USER']);
   unset($_SESSION['user_type']);
}

redirect('login');
exit();
