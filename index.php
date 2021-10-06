<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>skate_shop</title>

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
            <a class="nav-link" href="#">About</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Contact Us</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="forumcategories.php">Forum</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="index.php">Products</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="cart.php"><i class="fas fa-shopping-cart"><span id="cart-item" class="badge badge-danger"></span></i>
            </a>
        </li>
        
    </ul>
  </div>
</nav> <!--END OF NAV-->




<!--START OF PRODUCT CATEGORIES DISPLAY-->
<div class="container">
    <div class="table-responsive mt-2">
        <table class='table table-bordered table-striped text-center'>
            <thead>
                <tr>
                    <td colspan="7">
                        <h4 class="text-center text-info m-0">PRODUCT CATEGORIES</h4>
                    </td>
                </tr>
                <tr>
                    <th>Category</th>
                    <th>Desc</th>
                </tr>
            </thead>

            <tbody>
                <?php 

                include "config.php";
                $stmnt = $conn->prepare("SELECT * FROM product_categories");

                $stmnt->execute();

                $result = $stmnt->get_result(); //store the result of query into a variable.

                while($row = $result->fetch_assoc()):
                ?>
                    <tr>
                       
                        <td>
                            <img src="<?php echo $row['pc_image']; ?>" alt="img of skate product"> <br>
                            <strong><a href="products.php?pc_id=<?= $row['pc_id'] ?>"><?php echo $row['pc_title']; ?></a></strong>
                        </td>
                        <td>
                            <?php echo $row['pc_desc']; ?>
                        </td>
                    </tr>


                <?php endwhile; ?>
            </tbody>
        </table>   
    </div>
   
</div>
<!--END OF PRODUC CATEGORIES DISPLAY-->




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