<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0)
{   
	header('location:index.php');
}
else{ 
  // Get the search term from the query parameter
  $field = $_GET['field'];
  $searchTerm = $_GET['q']; 
// SQL query to search for matching records

  if ($field=='a') {
    $sql = "SELECT * FROM author WHERE name LIKE '%$searchTerm%' LIMIT 5";
    $result = $dbcon->query($sql);

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          echo '<div class="result-item">' . $row['name'] . '</div>';
      }
    } else {
      echo '<div class="result-item">No results found</div>';
    }  
  } elseif($field=='b'){
    $sql = "SELECT * FROM publishersr WHERE pubname LIKE '%$searchTerm%' LIMIT 5";
    $result = $dbcon->query($sql);

    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          echo '<div class="result-item">' . $row['pubname'] . '</div>';
      }
    } else {
      echo '<div class="result-item">No results found</div>';
    }
  } elseif($field=='c'){
    $sql = "SELECT * FROM countries WHERE name LIKE '$searchTerm%' LIMIT 5";
    $result = $dbcon->query($sql);
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
          echo '<div class="result-item">' . $row['name'] . '</div>';
      }
    } else {
      echo '<div class="result-item">No results found</div>';
    }
  }

// Close connectionnotepad
}
$dbcon->close();
?>