<?php
$forests = [];

try {
    $db = new PDO('sqlite:../database/us-wildfires.sqlite');

    $stmt = $db->prepare("SELECT DISTINCT NWCG_REPORTING_UNIT_NAME FROM fires");
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
</main>
</body>
</html>