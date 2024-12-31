<?php

 if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user_type'])){

    $_SESSION['user_type'] = $_POST['user_type'];
 }

 ?>