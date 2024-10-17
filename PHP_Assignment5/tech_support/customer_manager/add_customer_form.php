<?php
session_start();
require_once('../model/database.php');

// Getting data from the form submission
$firstName = filter_input(INPUT_POST, 'firstName');
$lastName = filter_input(INPUT_POST, 'lastName');
$address = filter_input(INPUT_POST, 'address');
$city = filter_input(INPUT_POST, 'city');
$state = filter_input(INPUT_POST, 'state');
$postalCode = filter_input(INPUT_POST, 'postalCode');
$countryCode = filter_input(INPUT_POST, 'countryCode');
$phone = filter_input(INPUT_POST, 'phone');
$email = filter_input(INPUT_POST, 'email');
$password = filter_input(INPUT_POST, 'password');
$custID = filter_input(INPUT_POST, 'custID', FILTER_VALIDATE_INT);

// Fetch existing customer data if custID is available
if ($custID) {
    $query = 'SELECT * FROM customers WHERE customerID = :custID';
    $statement = $db->prepare($query);
    $statement->bindValue(':custID', $custID);
    $statement->execute();
    $customer = $statement->fetch();
    $statement->closeCursor();


    // If customer data is fetched, populate the variables
    if ($customer) {
        $firstName = $customer['firstName'];
        $lastName = $customer['lastName'];
        $address = $customer['address'];
        $city = $customer['city'];
        $state = $customer['state'];
        $postalCode = $customer['postalCode'];
        $countryCode = $customer['countryCode'];
        $phone = $customer['phone'];
        $email = $customer['email'];
        $password = $customer['password'];
    }
}

$queryCountries = 'SELECT * FROM countries';
$statement = $db->prepare($queryCountries);
$statement->execute();
$countries = $statement->fetchAll();  // Fetch all countries as an array
$statement->closeCursor();
?>

<!DOCTYPE html>
<html lang="en">

<!-- The head section -->
<head>
    <meta charset="UTF-8">
    <title>SportsPro Technical Support</title>
    <link rel="stylesheet" type="text/css" href="../main.css">
</head>

<!-- The body section -->
<body>
<header>
    <h1>SportsPro Technical Support</h1>
    <p>Sports management software for the sports enthusiast</p>
    <nav>
        <ul>
            <li><a href="../index.php">Home</a></li>
        </ul>
    </nav>
</header>
<main>
    <!-- Set the form action to the correct script and specify POST method -->
    <form action="add_customer.php" method="post">
        <div id="data">
            <div class="labs">
                <label for="firstName">First name:</label>
                <input type="text" name="firstName" ><br>
            </div>

            <div class="labs">
                <label for="lastName">Last name:</label>
                <input type="text" name="lastName" ><br>
            </div>

            <div class="labs">
                <label for="address">Address:</label>
                <input type="text" name="address"><br>
            </div>

            <div class="labs">
                <label for="city">City:</label>
                <input type="text" name="city" ><br>
            </div>

            <div class="labs">
                <label for="state">State:</label>
                <input type="text" name="state"><br>
            </div>

            <div class="labs">
                <label for="postalCode">Postal Code:</label>
                <input type="text" name="postalCode"><br>
            </div>

            <div class="labs">
                <label for="countryCode">Country Code:</label>
                <select name="countryCode" id="countryCode">
                    <?php foreach ($countries as $country): ?>
                        <option value="<?php echo $country['countryCode']; ?>" 
                            <?php if ($country['countryCode'] == $countryCode) echo 'selected'; ?>>
                            <?php echo $country['countryName']; ?>
                        </option>
                    <?php endforeach; ?>
                </select><br>
            </div>

            <div class="labs">
                <label for="phone">Phone:</label>
                <input type="text" name="phone"><br>
            </div>

            <div class="labs">
                <label for="email">Email:</label>
                <input type="text" name="email"><br>
            </div>

            <div class="labs">
                <label for="password">Password:</label>
                <input type="text" name="password"><br>
            </div>

            <div class="button">
                <input type="submit" value="Add Customer">
            </div>
        </div>
    </form>
</main>

<?php include '../view/footer.php'; ?>
</body>
</html>
