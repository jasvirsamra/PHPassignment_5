<?php
session_start();
require_once('../model/database.php');


// Initialize variables for form data and errors
$firstName = $lastName = $address = $city = $state = $postalCode = $countryCode = $phone = $email = $password = $custID = '';
$errors = [];

// Getting data from form and validating inputs
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch data and sanitize inputs
    $firstName = filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING);
    $lastName = filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
    $state = filter_input(INPUT_POST, 'state', FILTER_SANITIZE_STRING);
    $postalCode = filter_input(INPUT_POST, 'postalCode', FILTER_SANITIZE_STRING);
    $countryCode = filter_input(INPUT_POST, 'countryCode', FILTER_SANITIZE_STRING);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
    $custID = filter_input(INPUT_POST, 'custID', FILTER_VALIDATE_INT);

    // Validation
    if (empty($firstName) || strlen($firstName) > 50) {
        $errors['firstName'] = "Required and should be less than 50 characters.";
    }
    if (empty($lastName) || strlen($lastName) > 50) {
        $errors['lastName'] = "Last name is required and should be less than 50 characters.";
    }
    if (empty($address) || strlen($address) > 50) {
        $errors['address'] = "Address is required and should be less than 50 characters.";
    }
    if (empty($city) || strlen($city) > 50) {
        $errors['city'] = "City is required and should be less than 50 characters.";
    }
    if (empty($state) || strlen($state) > 50) {
        $errors['state'] = "State is required and should be less than 50 characters.";
    }
    if (empty($postalCode) || strlen($postalCode) > 20) {
        $errors['postalCode'] = "Postal code is required and should be less than 20 characters.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Please enter a valid email.";
    }
    if (strlen($password) < 6 || strlen($password) > 20) {
        $errors['password'] = "Password must be between 6 and 20 characters.";
    }
    if (!empty($phone) && !preg_match('/^\d{3}\-\d{3}-\d{4}$/', $phone)) {
        $errors['phone'] = "Phone number must be in the format (999) 999-9999.";
    }

    // If no errors, update the database
    if (empty($errors)) {
        $query = "UPDATE customers SET 
            firstName = :firstName, 
            lastName = :lastName, 
            address = :address, 
            city = :city, 
            state = :state, 
            postalCode = :postalCode, 
            countryCode = :countryCode, 
            email = :email, 
            phone = :phone, 
            password = :password
        WHERE customerID = :custID"; 

        $statement = $db->prepare($query);
        $statement->bindValue(':firstName', $firstName);
        $statement->bindValue(':lastName', $lastName);
        $statement->bindValue(':address', $address);
        $statement->bindValue(':city', $city);
        $statement->bindValue(':state', $state);
        $statement->bindValue(':postalCode', $postalCode);
        $statement->bindValue(':countryCode', $countryCode);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':phone', $phone);
        $statement->bindValue(':password', $password);
        $statement->bindValue(':custID', $custID);
        $statement->execute();
        $statement->closeCursor();

        // Set session to show customer name
        $_SESSION['customer'] = $firstName . ' ' . $lastName;

        // Redirect to confirmation page
        header("Location: confirmation.php");
        die();
    }
}

// Fetch country list for the dropdown
$queryCountries = 'SELECT * FROM countries';
$statement = $db->prepare($queryCountries);
$statement->execute();
$countries = $statement->fetchAll();
$statement->closeCursor();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Customer Information</title>
    <link rel="stylesheet" type="text/css" href="../main.css">
</head>

<body>
<header>
    <h1>Edit Customer Information</h1>
</header>

<main>
<form action="edit_customer.php" method="post">
    <div id="data">

        <div class="labs">
            <label for="firstName">First name:</label>
            <input type="text" name="firstName" value="<?php echo htmlspecialchars($firstName); ?>">
            <?php if (isset($errors['firstName'])): ?>
                <span class="error">
                    <span class="message"><?php echo $errors['firstName']; ?></span>
                </span>
            <?php endif; ?>
        </div>

        <div class="labs">
            <label for="lastName">Last name:</label>
            <input type="text" name="lastName" value="<?php echo htmlspecialchars($lastName); ?>">
            <?php if (isset($errors['lastName'])): ?>
                <span class="error">
                    <span class="message"><?php echo $errors['lastName']; ?></span>
                </span>
            <?php endif; ?>
        </div>

        <div class="labs">
            <label for="address">Address:</label>
            <input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>">
            <?php if (isset($errors['address'])): ?>
                <span class="error">
                    <span class="message"><?php echo $errors['address']; ?></span>
                </span>
            <?php endif; ?>
        </div>

        <div class="labs">
            <label for="city">City:</label>
            <input type="text" name="city" value="<?php echo htmlspecialchars($city); ?>">
            <?php if (isset($errors['city'])): ?>
                <span class="error">
                    <span class="message"><?php echo $errors['city']; ?></span>
                </span>
            <?php endif; ?>
        </div>

        <div class="labs">
            <label for="state">State:</label>
            <input type="text" name="state" value="<?php echo htmlspecialchars($state); ?>">
            <?php if (isset($errors['state'])): ?>
                <span class="error">
                    <span class="message"><?php echo $errors['state']; ?></span>
                </span>
            <?php endif; ?>
        </div>

        <div class="labs">
            <label for="postalCode">Postal Code:</label>
            <input type="text" name="postalCode" value="<?php echo htmlspecialchars($postalCode); ?>">
            <?php if (isset($errors['postalCode'])): ?>
                <span class="error">
                    <span class="message"><?php echo $errors['postalCode']; ?></span>
                </span>
            <?php endif; ?>
        </div>

        <div class="labs">
            <label for="countryCode">Country Code:</label>
            <select name="countryCode" id="countryCode">
                <?php foreach ($countries as $country): ?>
                    <option value="<?php echo htmlspecialchars($country['countryCode']); ?>" 
                        <?php if ($country['countryCode'] == $countryCode) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($country['countryName']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="labs">
            <label for="phone">Phone:</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
            <?php if (isset($errors['phone'])): ?>
                <span class="error">
                    <span class="message"><?php echo $errors['phone']; ?></span>
                </span>
            <?php endif; ?>
        </div>

        <div class="labs">
            <label for="email">Email:</label>
            <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>">
            <?php if (isset($errors['email'])): ?>
                <span class="error">
                    <span class="message"><?php echo $errors['email']; ?></span>
                </span>
            <?php endif; ?>
        </div>

        <div class="labs">
            <label for="password">Password:</label>
            <input type="text" name="password" value="<?php echo htmlspecialchars($password); ?>">
            <?php if (isset($errors['password'])): ?>
                <span class="error">
                    <span class="message"><?php echo $errors['password']; ?></span>
                </span>
            <?php endif; ?>
        </div>

        <input type="hidden" name="custID" value="<?php echo htmlspecialchars($custID); ?>">

        <div class="button">
            <input type="submit" value="Update">
        </div>
    </div>
</form>

</main>
<?php include '../view/footer.php'; ?>

</body>
</html>
