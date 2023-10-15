<?php
try {
    $dbData = include __DIR__ . '/app/etc/db.php';
    $dbh = new PDO('mysql:host=' . $dbData['host'] . ';dbname=' . $dbData['dbname'], $dbData['username'], $dbData['password'], array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
} catch (Exception $ex) {
    echo json_encode(array());
    exit;
}

$query = isset($_GET['q']) ? trim($_GET['q']) : null;
$store = isset($_GET['s']) ? (int) $_GET['s'] : null;


if (!$store) {
    echo json_encode(array());
    exit;
}

// fetch search query results
$sql = "SELECT query_text FROM catalogsearch_query WHERE store_id = :store_id AND query_text LIKE :query_text ORDER BY popularity DESC LIMIT 10";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':store_id', $store);
$stmt->bindParam(':query_text', $query);
$stmt->execute();
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $result[strtolower($row['query_text'])] = [
        'name' => $row['query_text']
    ];
}

$query = '%' . $query . '%';
$sql = "SELECT DISTINCT name FROM catalog_product_flat_" . $store . " WHERE name LIKE :name ORDER BY name ASC LIMIT 8";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':name', $query);
$stmt->execute();

$result = [];
foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $result[strtolower($row['name'])] = [
        'name' => $row['name']
    ];
}

echo json_encode($result);
