<?php
session_start();
include "db.php";
// Disable displaying notices
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 0);

// Check if the user is an admin
if (isset($_SESSION['admin_id'])) {
    // Admin session
    $admin_id = $_SESSION['admin_id'];
    $admin_email = $_SESSION['admin_email'];
    $admin_name = $_SESSION['admin_name'];

    // Redirect to the admin dashboard or perform admin-specific actions

} elseif (isset($_SESSION['uid'])) {
    // User session
    $user_id = $_SESSION['uid'];
    $user_name = $_SESSION['name'];

    // Your user-specific code here
    include("db.php");

    // Fetch user details from the database
    $user_query = mysqli_query($con, "SELECT * FROM user_info WHERE user_id='$user_id'");
    $user_data = mysqli_fetch_assoc($user_query);

    // Display user details on the page
    $user_name = $user_data['first_name'];
    $user_email = $user_data['email'];
} else {
    // Redirect to the login page if no session is set
    header("location: index.php");
    exit();
}

include "header.php";

if (isset($_POST['re_password'])) {
    $old_pass = $_POST['old_pass'];
    $new_pass = $_POST['new_pass'];
    $re_pass = $_POST['re_pass'];

    // Fetch the current password from the database
    $password_query = mysqli_query($con, "SELECT password FROM user_info WHERE user_id='$user_id'");
    $password_data = mysqli_fetch_assoc($password_query);
    $current_password = $password_data['password'];

    if (empty($old_pass) || empty($new_pass) || empty($re_pass)) {
        echo "<script>alert('Please fill in all fields.');</script>";
    } elseif ($old_pass != $current_password) {
        echo "<script>alert('Incorrect old password. Please try again.');</script>";
    } elseif ($new_pass != $re_pass) {
        echo "<script>alert('New password and confirm password do not match.');</script>";
    } else {
        // Update the password in the database
        $update_query = mysqli_query($con, "UPDATE user_info SET password='$new_pass' WHERE user_id='$user_id'");
        if ($update_query) {
            echo "<script>alert('Password updated successfully.');</script>";
        } else {
            echo "<script>alert('Failed to update password. Please try again.');</script>";
        }
    }
}

?>

<!-- End Navbar -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title">Update Password</h4>
                        <p class="card-category"></p>
                    </div>
                    <div class="card-body">
                        <form method="post" action="profile.php">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="bmd-label-floating">User Name</label>
                                        <input type="text" class="form-control" value="<?php echo isset($user_name) ? $user_name : ''; ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group bmd-form-group">
                                        <label class="bmd-label-floating">Enter old password</label>
                                        <input type="password" class="form-control" name="old_pass" id="old_pass">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group bmd-form-group">
                                        <label class="bmd-label-floating">Change Password</label>
                                        <input type="password" class="form-control" name="new_pass" id="new_pass">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group bmd-form-group">
                                        <label class="bmd-label-floating">Confirm Password</label>
                                        <input type="password" class="form-control" name="re_pass" id="re_pass">
                                    </div>
                                </div>
                                <button class="btn btn-primary pull-right" type="submit" name="re_password">Update Profile</button>
                                <div class="clearfix"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header card-header-primary">
                <h4 class="card-title">Order Details</h4>
                <p class="card-category"></p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="text-primary">
                            <tr>
                                <th>Order ID</th>
                                <th>User ID</th>
                                <th>First Name</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th>City</th>
                                <th>State</th>
                                <th>ZIP</th>
                                <th>Card Name</th>
                                <th>Card Number</th>
                                <th>Expiry Date</th>
                                <th>CVV</th>
                                <th>Product Count</th>
                                <th>Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch order details from the database
                            $orderQuery = "SELECT * FROM orders_info WHERE user_id = $user_id";
                            $orderResult = mysqli_query($con, $orderQuery);

                            while ($order = mysqli_fetch_assoc($orderResult)) {
                                echo "<tr>";
                                echo "<td>" . $order['order_id'] . "</td>";
                                echo "<td>" . $order['user_id'] . "</td>";
                                echo "<td>" . $order['f_name'] . "</td>";
                                echo "<td>" . $order['email'] . "</td>";
                                echo "<td>" . $order['address'] . "</td>";
                                echo "<td>" . $order['city'] . "</td>";
                                echo "<td>" . $order['state'] . "</td>";
                                echo "<td>" . $order['zip'] . "</td>";
                                echo "<td>" . $order['cardname'] . "</td>";
                                echo "<td>" . $order['cardnumber'] . "</td>";
                                echo "<td>" . $order['expdate'] . "</td>";
                                echo "<td>" . $order['cvv'] . "</td>";
                                echo "<td>" . $order['prod_count'] . "</td>";
                                echo "<td>" . $order['total_amt'] . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

    </div>
</div>

<?php
include "footer.php";
?>
