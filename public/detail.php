<?php

require __DIR__ . '/../vendor/autoload.php';

use src\WildfireDatabase;

$fires = [];
$resultsPerPage = 15;
$totalResults = 0;
$pageNumber = $_GET['page'] ?? 1;
$limitStartNumber = ($pageNumber - 1) * $resultsPerPage;

$forestName = $_GET['forest'];
$forestNameUrlParam = urlencode($forestName);

enum FireSizes
{
    case A;
    case B;
    case C;
    case D;
    case E;
    case F;
    case G;
}

$fireSizeToImageCount = [
    FireSizes::A->name => 1,
    FireSizes::B->name => 2,
    FireSizes::C->name => 3,
    FireSizes::D->name => 4,
    FireSizes::E->name => 5,
    FireSizes::F->name => 6,
    FireSizes::G->name => 7,
];

$fireSizeToTooltip = [
    FireSizes::A->name => '0 - 0.25 Acres',
    FireSizes::B->name => '0.26-9.9 Acres',
    FireSizes::C->name => '10.0-99.9 Acres',
    FireSizes::D->name => '100-299 Acres',
    FireSizes::E->name => '300 to 999 Acres',
    FireSizes::F->name => '1000 to 4999 Acres',
    FireSizes::G->name => '5000+ Acres',
];

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
        <th>Fire Size</th>
        <th>Cause</th>
        <th>Location</th>
    </tr>
    <?php
    foreach ($fires as $fire) : ?>
        <tr>
            <td><?= $fire->fpa_id ?></td>
            <td><?= $fire->name ?></td>
            <td><?= $fire->datetime ?></td>
            <?php
            $imageCount = $fireSizeToImageCount[$fire->fire_size];
            $tooltip = $fireSizeToTooltip[$fire->fire_size];
            echo '<td>';
            for ($i = 0; $i < $imageCount; $i++) {
                echo '<img class="fire-image" src="assets/fire.png" alt="fire" title="' . $tooltip . '">';
            }
            echo '</td>';
            ?>
            <td><?= $fire->cause ?></td>
            <td><a href="https://www.google.com/maps/search/?api=1&query=<?= $fire->latitude ?>,<?= $fire->longitude ?>"
                   target="_blank"><?= $fire->latitude . ', ' . $fire->longitude ?></a></td>
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

