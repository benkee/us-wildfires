<?php

require __DIR__ . '/../vendor/autoload.php';

use src\WildfireDatabase;

$forests = [];
$resultsPerPage = 30;
$totalResults = 0;
$pageNumber = $_GET['page'] ?? 1;
$limitStartNumber = ($pageNumber - 1) * $resultsPerPage;

$searchValue = ($_GET['query'] ?? '');
$searchQuery = '%' . $searchValue . '%';

try {
    $database = new WildfireDatabase();
    $totalResults = $database->getForestCount($searchQuery);
    $forests = $database->getForests($searchQuery, $limitStartNumber, $resultsPerPage);
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>US Wildfire Data</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<main>
    <header>
        <h1>List of Forests</h1>
    </header>
    <section>
        <form method="get" action="">
            <input type="text" name="query" placeholder="Search by Forests"
                   value="<?= $_GET['query'] ?? '' ?>" aria-label="Search">
            <input type="submit" value="Search" role="button">
        </form>
    </section>
    <section>
        <ul>
            <?php foreach ($forests as $forestName): ?>
                <li>
                    <a href="detail.php?forest=<?= urlencode($forestName) ?>">
                        <?= $forestName ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <nav>
        <?php
        $totalPages = ceil($totalResults / $resultsPerPage);
        $prevPage = $pageNumber > 1 ? $pageNumber - 1 : 1;
        $nextPage = $pageNumber < $totalPages ? $pageNumber + 1 : $totalPages;
        ?>
        <a class="pagination" href="?query=<?= $searchValue ?>&page=1">First</a>
        <a class="pagination" href="?query=<?= $searchValue ?>&page=<?= $prevPage ?>">Prev</a>
        <span class="pagination current-page"><?= $pageNumber ?></span>
        <a class="pagination" href="?query=<?= $searchValue ?>&page=<?= $nextPage ?>">Next</a>
        <a class="pagination" href="?query=<?= $searchValue ?>&page=<?= $totalPages ?>">Last</a>
        <span class="pagination current-page">Total: <?= $totalPages ?></span>
    </nav>
</main>
</body>
</html>