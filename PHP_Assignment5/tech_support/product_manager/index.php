<?php
session_start();
require('../model/database.php');
$queryProducts = 'SELECT * FROM products';
$statement = $db-> prepare($queryProducts);
$statement-> execute();
$products = $statement->fetchAll();
$statement-> closeCursor();

?>

<!DOCTYPE html>
<html>


<head>
    <title>SportsPro Technical Support</title>
    <link rel="stylesheet" type="text/css"
          href="../main.css">
</head>

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
    <table>
      <tr>
        <th>CODE</th>
        <th>Product name</th>
        <th>Version</th>
        <th>Release Date</th>
        <th>&nbsp</th> 
      </tr>
        <?php foreach($products as $product):?> 
         <tr>
          <td><?php echo $product['productCode'];?></td>
          <td><?php echo $product['name'];?></td>
          <td><?php echo $product['version'];?></td>
          <td>
            <?php
                $releaseDate = new DateTime($product['releaseDate']);
                echo $releaseDate->format('n-j-Y');
                ?>
            <td>
            <form action = "delete_product.php" method = "post">
            <input type="hidden" name = "code" value = "<?php echo $product['productCode'];?>"/>  
            <input type="submit" value = "Delete"/>
            </form>
          </td> 
         </tr>
        <?php endforeach; ?> 
    </table>
    <p class = "option"><a href="add_product_form.php">Add product</a></p>
</main>
    
<?php include '../view/footer.php'; ?>