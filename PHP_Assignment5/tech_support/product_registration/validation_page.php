<?php
session_start();
require_once('../model/database.php');

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

if (empty($email)) {
    $_SESSION['error'] = 'Please enter a valid email address.';
    header("Location: ../errors/error.php");
    die();
}


$query = 'SELECT * FROM customers WHERE email = :email';
$statement = $db->prepare($query);
$statement->bindValue(':email', $email);
$statement->execute();
$customer = $statement->fetch();
$statement->closeCursor();



if ($customer) {
    $_SESSION['customer'] = $customer['firstName'] . ' ' . $customer['lastName'];
    $_SESSION['custID'] = $customer['customerID'];
    $_SESSION['email'] = $email;
    

    header("Location: registration_form.php");
    die();
} else {
    $_SESSION['error'] = 'Invalid email. No customer found with this email address.';
    header("Location: ../errors/error.php");
    die();
}
?>
