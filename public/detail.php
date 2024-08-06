<?php

require __DIR__ . '/../vendor/autoload.php';

use src\WildfireDatabase;

$fires = [];
$resultsPerPage = 20;
$totalResults = 0;
$pageNumber = $_GET['page'] ?? 1;
$limitStartNumber = ($pageNumber - 1) * $resultsPerPage;

$forestName = $_GET['forest'];
$forestNameUrlParam = urlencode($forestName);

try {
    $database = new WildfireDatabase();
    $totalResults = $database->getFiresInForestCount($forestName);
    $fires = $database->getFiresInForest($forestName, $limitStartNumber, $resultsPerPage);
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Forest Fires in <?php echo $forestName ?></title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<h1>Fires in <?php echo $forestName ?></h1>
<table>
    <tr>
        <th>FPA_ID</th>
        <th>Fire Name</th>
        <th>Date Discovered</th>
        <th>Cause</th>
    </tr>
    <?php
    foreach ($fires as $fire) : ?>
        <tr>
            <td><?= $fire->fpa_id ?></td>
            <td><?= $fire->name ?></td>
            <td><?= $fire->datetime ?></td>
            <td><?= $fire->cause ?></td>
        </tr>
    <?php endforeach; ?>
</table>
<nav class="pagination-block">
    <?php
    $totalPages = ceil($totalResults / $resultsPerPage);
    $prevPage = $pageNumber > 1 ? $pageNumber - 1 : 1;
    $nextPage = $pageNumber < $totalPages ? $pageNumber + 1 : $totalPages;
    ?>
    <a class="pagination" href="?forest=<?= $forestNameUrlParam ?>&page=1">First</a>
    <a class="pagination" href="?forest=<?= $forestNameUrlParam ?>&page=<?= $prevPage ?>">Prev</a>
    <span class="pagination current-page"><?= $pageNumber ?></span>
    <a class="pagination" href="?forest=<?= $forestNameUrlParam ?>&page=<?= $nextPage ?>">Next</a>
    <a class="pagination" href="?forest=<?= $forestNameUrlParam ?>&page=<?= $totalPages ?>">Last</a>
    <span class="pagination current-page">Total: <?= $totalPages ?></span>
</nav>
<button class="back-button" onclick="location.href='index.php';">Back to Forests</button>
</body>
</html>

