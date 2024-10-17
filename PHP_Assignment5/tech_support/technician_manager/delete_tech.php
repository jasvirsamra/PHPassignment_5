<?php

session_start();
require('../model/database.php'); 


$techID = filter_input(INPUT_POST, 'techID', FILTER_VALIDATE_INT);

if ($techID && !empty($techID)) {
    
    $query = "DELETE FROM technicians WHERE techID = :techID";
    $statement = $db->prepare($query);
    $statement->bindValue(':techID', $techID);
    
    try {
        $statement->execute();
        $statement->closeCursor();
        echo "Product deleted successfully."; 
        header("Location: index.php");
        exit;
        
    } catch (PDOException $e) {
        
        echo "Error executing query: " . $e->getMessage();
        exit;
    }
} else {
    echo "Error: No product code received or code is invalid.";
    exit;
}
?>
