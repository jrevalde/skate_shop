<?php 
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>

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
            <a class="nav-link" href="#">Forum</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="index.php">Products</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Categories</a>
        </li>
        <li class="nav-item">
            <a class="nav-link  active" href="cart.php"><i class="fas fa-shopping-cart"><span id="cart-item" class="badge badge-danger"></span></i>
            </a>
        </li>
        
    </ul>
  </div>
</nav> <!--END OF NAV-->


<!--START OF DISPLAY SECTION-->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!--This is a dynamic alert-->
        <div style="display: <?php if(isset($_SESSION['showAlert'])){echo $_SESSION['showAlert'];} else {echo 'none';} unset($_SESSION['showAlert']); ?>" class="alert alert-success alert-dismissible mt-3"><!--Display-->
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong><?php if(isset($_SESSION['message'])){echo $_SESSION['message'];} unset($_SESSION['showAlert']); ?></strong> 
        </div>


            <div class="table-responsive mt-2">
                <table class="table table-bordered table-striped text-center">
                    <thead>
                    <tr>
                        <td colspan="7">
                            <h4 class="text-center text-info m-0">Products in Cart</h4>
                        </td>
                    </tr>

                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                        <th>
                            <a href="action.php?clear=all" class="badge-danger badge p-1" onclick="return confirm('Are you sure you want to clear the cart?');">
                                <i class="fas fa-trash"></i>&nbsp;&nbsp;Clear Cart <!--When it's clicked it will pass a variable 'clear' as a get request to action.php-->
                            </a>
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                        <?php include "config.php"; 
                        $stmnt = $conn->prepare("SELECT * FROM cart");
                        $stmnt->execute();
                        $result = $stmnt->get_result();
                        $grand_total = 0;

                        while($row = $result->fetch_assoc()):
                        ?>
                        
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <input type="hidden" class="pid" value="<?= $row['id'] ?>">
                            <td><img src="<?= $row['product_image'] ?>" width="50" alt="Image of skate-deck"></td>
                            <td><?= $row['product_name'] ?></td>
                            <td>$<?= number_format($row['product_price'], 2); ?></td>
                            <input type="hidden" class="pprice" value="<?=$row['product_price']?>">
                            <td>
                                <input type="number" class="form-control itemQty" value="<?= $row['qty'] ?>" style="width: 75px;">
                            </td>
                            <td>$<?= number_format($row['total_price'], 2); ?></td>
                            <td>
                                <a href="action.php?remove=<?= $row['id'] ?>" class="text-danger lead" onclick="return confirm('Do you want to remove this item?');">
                                <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php $grand_total += $row['total_price']; ?>
                        <?php endwhile; ?>

                        <!--The footer/bottom section of the table-->
                        <tr>
                            <td colspan="3" >
                                <a href="index.php" class="btn btn-success"><i class="fas fa-cart_plus"></i>&nbsp;&nbsp;Continue Shopping</a>
                            </td>
                            <td colspan="2">
                                <b>Grand Total</b>
                            </td>
                            <td>
                                <b>$<?= number_format($grand_total, 2); ?></b> 
                            </td>
                            <td>
                                <a href="checkout.php" class="btn btn-info <?= ($grand_total > 1)?"":"disabled"; ?>"><i class="far fa-credit-card"></i>&nbsp;&nbsp;Checkout</a> <!--Cart button will disable if the grand total is less than 1-->
                            </td>
                        </tr>
                        <!--End of table bottom section-->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!--END OF DISPLAY SECTION-->


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
        //send product price, quantity and id to the server. from the server the cart will be updated with total price and quantity.        
        $(".itemQty").on('change', function(){
            var $el = $(this).closest("tr"); //tr is used because all the input tagas are inside the tr tag.

            var pid = $el.find(".pid").val();
            var pprice = $el.find(".pprice").val();
            var qty = $el.find(".itemQty").val();
            location.reload(true);
            $.ajax({
                url: "action.php",
                method: "post",
                cache: false,
                data: {qty: qty, pid: pid, pprice: pprice},    
                success: function(response)
                {
                   
                    console.log(response);
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