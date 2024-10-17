<?php
session_start();


if (!isset($_SESSION['custID'])) {
    header('Location: login_page.php');
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    $productCode = filter_input(INPUT_POST, 'productCode', FILTER_SANITIZE_STRING);

    
    $customerID = $_SESSION['custID'];

   
    $_SESSION['productCode'] = $productCode;


    require_once('../model/database.php');


    if (empty($customerID) || empty($productCode)) {
        echo 'Error: customerID or productCode is empty.<br>';
        echo 'customerID: ' . var_export($customerID, true) . '<br>';
        echo 'productCode: ' . var_export($productCode, true) . '<br>';
        exit();
    }


    $query = 'INSERT INTO registrations (customerID, productCode, registrationDate)
              VALUES (:customerID, :productCode, NOW())';
    $statement = $db->prepare($query);
    $statement->bindValue(':customerID', $customerID);
    $statement->bindValue(':productCode', $productCode);

    try {
        $statement->execute();
        $statement->closeCursor();
    } catch (PDOException $e) {
        
        $_SESSION['error'] = 'You have already registered this product.';
    header('Location: ../errors/error.php');
    exit();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Confirmation</title>
    <link rel="stylesheet" type="text/css" href="../main.css">
</head>


<body>
<header>
    <h1>SportsPro Technical Support</h1>
    <nav>
        <ul>
            <li><a href="../index.php">Back home</a></li>
        </ul>
    </nav>
</header>

<main id="confirmation">
    <h2>Register Product</h2>
    <p>Thank you <?php echo $_SESSION['cutomer']?></p>
    <p>Product (<?php echo isset($_SESSION['productCode']) ? htmlspecialchars($_SESSION['productCode']) : 'Unknown'; ?>) was registered successfully.</p>

    <?php unset($_SESSION['customer']);  ?>
</main>

<footer>
    <p class="copyright">
        &copy; <?php echo date("Y"); ?> SportsPro, Inc.
    </p>
</footer>
</body>
</html>
