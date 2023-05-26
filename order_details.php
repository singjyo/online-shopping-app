<?php
session_start();
include "db.php";
// Disable displaying notices
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);

if (isset($_SESSION["uid"]) && isset($_GET["order_id"])) {
    include "header.php";

    $user_id = $_SESSION["uid"];
    $order_id = $_GET["order_id"];

    // Fetch order details from the database based on $order_id
    $orderQuery = "SELECT * FROM orders_info WHERE order_id = $order_id AND user_id = $user_id";
    $orderResult = mysqli_query($con, $orderQuery);

    if (mysqli_num_rows($orderResult) == 1) {
        $order = mysqli_fetch_assoc($orderResult);

        // Display the order details to the user
        echo "Order ID: " . $order['order_id'] . "<br>";
        echo "User ID: " . $order['user_id'] . "<br>";
        echo "First Name: " . $order['f_name'] . "<br>";
        echo "Email: " . $order['email'] . "<br>";
        echo "Address: " . $order['address'] . "<br>";
        echo "City: " . $order['city'] . "<br>";
        echo "State: " . $order['state'] . "<br>";
        echo "ZIP: " . $order['zip'] . "<br>";
        echo "Card Name: " . $order['cardname'] . "<br>";
        echo "Card Number: " . $order['cardnumber'] . "<br>";
        echo "Expiry Date: " . $order['expdate'] . "<br>";
        echo "CVV: " . $order['cvv'] . "<br>";
        echo "Product Count: " . $order['prod_count'] . "<br>";
        echo "Total Amount: " . $order['total_amt'] . "<br>";

        // Fetch order products from the database
        $orderProductsQuery = "SELECT op.product_id, op.qty, op.amt, p.product_image 
                               FROM order_products op 
                               INNER JOIN products p ON op.product_id = p.product_id 
                               WHERE op.order_id = $order_id";
        $orderProductsResult = mysqli_query($con, $orderProductsQuery);

        if (mysqli_num_rows($orderProductsResult) > 0) {
            echo "<h2>Order Products:</h2>";

            while ($product = mysqli_fetch_assoc($orderProductsResult)) {
                echo "Product ID: " . $product['product_id'] . "<br>";
                echo "Quantity: " . $product['qty'] . "<br>";
                echo "Amount: " . $product['amt'] . "<br>";
                //echo "Product Image: <br>";
                //echo "<img src='" . $product['product_image'] . "' alt='Product Image'><br>";
                echo "<hr>";
    
            }
        } else {
            echo "No products found for this order.";
        }
    } else {
        echo "Invalid order ID.";
    }
    include "footer.php";

} else {
    // Redirect the user to the login page or any other appropriate page
    header("Location: index.php");
    exit();
}
?>

<!-- Back Button -->
<button onclick="goBack()">Go Back</button>

<script>
    function goBack() {
        window.history.back();
    }
</script>
