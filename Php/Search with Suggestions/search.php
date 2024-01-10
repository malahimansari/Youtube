<?php 

//establish database connection
$pdo = new PDO("mysql:host=localhost;dbname=inventory", "root", "");

// GET search query from Ajax request
$searchTerm = $_GET['query'];

//prepare sql statement
$search = $pdo->prepare("SELECT * From products WHERE product_name LIKE :searchTerm LIMIT 4");  //only four suggestions appear
$search->bindValue(":searchTerm", $searchTerm . '%');
$search->execute();

//Fetch matching results
$results = $search->fetchAll(PDO::FETCH_ASSOC);

// Return results as JSON
echo json_encode($results);


?>