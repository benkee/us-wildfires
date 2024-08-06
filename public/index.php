<?php
$forests = [];
$resultsPerPage = 30;
$totalResults = 0;
$pageNumber = $_GET['page'] ?? 1;
$limitStartNumber = ($pageNumber - 1) * $resultsPerPage;

try {
    $db = new PDO('sqlite:../database/us-wildfires.sqlite');

    $countStmt = $db->prepare("SELECT COUNT(DISTINCT NWCG_REPORTING_UNIT_NAME) FROM fires");
    $countStmt->execute();
    $totalResults = $countStmt->fetchColumn();

    $stmt = $db->prepare("SELECT DISTINCT NWCG_REPORTING_UNIT_NAME FROM fires LIMIT :start, :limit");
    $stmt->bindParam(':start', $limitStartNumber, PDO::PARAM_INT);
    $stmt->bindParam(':limit', $resultsPerPage, PDO::PARAM_INT);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $forests[] = $row['NWCG_REPORTING_UNIT_NAME'];
    }
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
        <a class="pagination" href="?page=1">First</a>
        <a class="pagination" href="?page=<?= $prevPage ?>">Prev</a>
        <span class="pagination current-page"><?= $pageNumber ?></span>
        <a class="pagination" href="?page=<?= $nextPage ?>">Next</a>
        <a class="pagination" href="?page=<?= $totalPages ?>">Last</a>
        <span class="pagination current-page">Total: <?= $totalPages ?></span>
    </nav>
</main>
</body>
</html>