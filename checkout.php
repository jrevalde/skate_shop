<?php
    require "config.php";
    //we need the grand total amount.

    $grand_total = 0;

    $allItems = "";
    $items = array();

    $sql = "SELECT CONCAT(product_name, '(',qty,')')  AS ItemQty, total_price FROM cart"; //formats the content of array into key value pairs.

    $stmnt = $conn->prepare($sql);
    $stmnt->execute();
    $result = $stmnt->get_result();

    while($row = $result->fetch_assoc())
    {
        $grand_total += $row['total_price'];
        $items[] = $row["ItemQty"];
    }

    /*echo $grand_total;
    print_r($items);*/
    $allItems = implode(", ", $items); // this implodes the array to a single string.


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!--font-awesom library-->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

<nav class="navbar navbar-expand-md bg-dark navbar-dark"> <!--START OF NAV-->
  <!-- Brand -->
  <a class="navbar-brand" href="#">Skate Shop</a>

  <!-- Toggler/collapsibe Button -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
    <span class="navbar-toggler-icon"></span>
  </button>

  <!-- Navbar links -->
  <div class="collapse navbar-collapse" id="collapsibleNavbar">
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" href="#">Login</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="index.php">About</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Contact Us</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="topiclist.php">Forum</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="index.php">Products</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Categories</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="cart.php"><i class="fas fa-shopping-cart"><span id="cart-item" class="badge badge-danger"></span></i>
            </a>
        </li>
        
    </ul>
  </div>
</nav> <!--END OF NAV-->

<!--START OF DISPLAY-->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6 px-4 pb-4" id="order">
            <h4 class="text-center text-info p-2">Complet your order.</h4>
            <div class="jumbotron p-3 mb-2 text-center">
                <h6><b>Product/s : <?= $allItems; ?></b></h6>
                <!--<h6 class="lead"><b>Delivery Charge : </b>Free</h6>-->
                <h5><b>Total Amount Payable : $</b><?= number_format($grand_total, 2); ?></h5>

            </div>

            <form action="" method="post" id="placeOrder"> <!--We will use ajax to send the form data to the server and interact with the orders table.-->
                <input type="hidden" name="products" value="<?= $allItems; ?>">
                <input type="hidden" name="grand_total" value="<?= $grand_total; ?>">
                <div class="form-group">
                    <input type="text" name="name" class="form-control" placeholder="Enter name please." required>
                </div>
                <div class="form-group">
                    <input type="email" name="email" class="form-control" placeholder="Enter email please." required>
                </div>
                <div class="form-group">
                    <input type="tel" name="phone" class="form-control" placeholder="Enter phone number please." required>
                </div>
                <div class="form-group">
                    <textarea name="address" id="a" cols="10" rows="3" class="form-control" placeholder="Enter your delivery address here please." required></textarea>
                </div>
                <h6 class="text-center lead">Select Payment Mode</h6>
                <div class="form-group">
                    <select name="pmode" class="form-control" id="" required> 
                        <option value="" selected disabled>Select Payment Mode</option>
                        <option value="cod">Cash on delivery</option>
                        <option value="netbanking">Net Banking</option>
                        <option value="cards">Debit/Credit Card</option>
                    </select>
                </div>
                <div class="form-group">
                    <input type="submit" name="submit" value="Place Order" class="btn btn-danger btn-block">
                </div>
            </form>
        </div>
    </div>
</div>
<!--END OF DISPLAY-->

<!-- Footer -->
<footer class="page-footer font-small blue">

  <!-- Copyright -->
  <div class="footer-copyright text-center py-3">© 2020 Copyright:
    <a href="https://mdbootstrap.com/"> skate_shop.com</a>
  </div>
  <!-- Copyright -->

</footer>
<!-- Footer -->



<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        
        $("#placeOrder").submit(function(e){
            e.preventDefault(); // just stops the page from refreshing.

            $.ajax({
                url: 'action.php',
                method: 'post',
                data: $('form').serialize() + "&action=order",
                success: function(response){
                    $("#order").html(response);
                }
            });
        });

        load_cart_item_number();

        function load_cart_item_number()
        {
            $.ajax({
                url: 'action.php',
                method: 'get',
                data: {cartItem: "cart_item"},
                success: function(response)
                {
                    $("#cart-item").html(response);   
                }
            });
        }
        
    });
</script>
    
</body>
</html>