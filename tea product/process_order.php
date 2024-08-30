<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "teaworld";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: ". $conn->connect_error);
}

// Assuming POST method is used to send form data
$name = $_POST['name'];
$address = $_POST['address'];
$itemCode = $_POST['itemCode'];
$packSize = $_POST['packSize'];
$numPacks = $_POST['numPacks'];

// Fetch item description and price from the database
$sql = "SELECT Description, Price FROM orders WHERE Item_Code =?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $itemCode); // "i" indicates the variable type is integer
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $description = $row['Description'];
    $price = $row['Price'];
} else {
    echo "No item found.";
    exit;
}

// Calculate updated price based on pack size
switch ($packSize) {
    case '200':
        $updatedPrice = $price * 1.5;
        break;
    case 'family':
        $updatedPrice = $price * 3;
        break;
    default:
        $updatedPrice = $price;
}

// Calculate total amount and final amount
$totalAmount = $updatedPrice * $numPacks;
$discount = $totalAmount >= 50? $totalAmount * 0.05 : 0; // Apply 5% discount if total amount is >= 50
$finalAmount = $totalAmount - $discount;

// Display the results
echo "<h2>Order Details</h2>";
echo "Name: $name<br>";
echo "Address: $address<br>";
echo "Item Description: $description<br>";
echo "Pack Size: $packSize<br>";
echo "Number of Packs: $numPacks<br>";
echo "Updated Price: $updatedPrice<br>";
echo "Total Amount: $totalAmount<br>";
echo "Discount: $discount<br>";
echo "Final Amount: $finalAmount<br>";

// Close the database connection
$conn->close();
?>
