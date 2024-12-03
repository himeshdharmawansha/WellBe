<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
    <link rel="stylesheet" href="<?= ROOT ?>/assets/css/navbar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-logo">
                <img src="<?= ROOT ?>/assets/images/logo.png">
                <h2>WELLBE</h2>
            </div>
            <ul class="sidebar-menu">
                <?php
                foreach ($elements as $key => $element) {
                    // Determine the active class for the current element
                    $activeClass = ($element[1] === ucfirst($active)) ? 'active' : '';

                    // Define the href path for each element
                    $href = ROOT . "/{$userType}/{$key}";;

                    echo '<a style="color: inherit;" href="' . $href . '">';
                    echo    '<li class="' . $activeClass . '">';
                    echo        '<i class="' . $element[0] . '"></i>';
                    echo        '<span class="menu-text">' . $element[1] . '</span>';
                    echo    '</li>';
                    echo '</a>';
                }
                ?>
            </ul>
        </div>
    </div>
</body>

</html>