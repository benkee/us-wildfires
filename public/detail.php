<?php
$forestName = $_GET['forest'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Forest Fires in <?php echo htmlspecialchars($forestName, ENT_QUOTES, 'UTF-8'); ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h1>Fires in <?php echo htmlspecialchars($forestName, ENT_QUOTES, 'UTF-8'); ?></h1>
<button class="back-button" onclick="location.href='index.php';">Back to Forests</button>
</body>
</html>
