<?php
$model = new Model;
if (isset($_SESSION['USER'])) {
   unset($_SESSION['USER']);
   unset($_SESSION['user_type']);
}
$model->logout();
redirect('landing');
exit();
