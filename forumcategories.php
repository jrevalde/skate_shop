<?php
include "config.php";

//gather the categories

$get_categories_sql = "SELECT *
FROM categories";

$get_category_res = $conn->query($get_categories_sql);

if ($get_category_res->num_rows < 1) //checks if there are any topics
{
    $display_block = "<p><em>No Categories exist.</em></en></p>";
}
else
{
    //create the display string.
    $display_block = <<<EOT
    <div class="table-responsive mt-2">
    <table class='table table-bordered table-striped text-center'>
        <thead>
            <tr>
                <td colspan="7">
                    <h4 class="text-center text-info m-0">Forum Categories</h4>
                </td>
            </tr>        
            <tr>
                <th>
                    Category Title
                </th>
                <th>
                    # of Topics
                </th>
                <th>
                    Description                    
                </th>
            </tr>

        </thead>
 
    EOT;

    while($category_info = $get_category_res->fetch_assoc())
    {
        $c_id = $category_info['c_id'];
        $c_title = stripslashes($category_info['c_title']);
        $c_desc = $category_info['c_desc'];
        

        //get number of topics
        $get_num_topics_sql = "SELECT COUNT(c_id) AS topic_count FROM
        forum_topics WHERE c_id = '". $c_id."'";

        $get_num_topics_res = $conn->query($get_num_topics_sql);

        while($topics_info = $get_num_topics_res->fetch_assoc())
        {
            $num_topics = $topics_info['topic_count'];
        }

        //add to display
        $display_block .= <<<EOT
        <tbody>
            <tr>
                <td>
                    <a href='topiclist.php?c_id=$c_id & c_title=$c_title'><strong>$c_title</strong></a><br>
                    
                </td>
                <td>$num_topics</td>
                <td>
                    <p>$c_desc</p>
                </td>
            </tr>            
        </tbody>

        EOT;
    }

    //free results
    mysqli_free_result($get_category_res);
    mysqli_free_result($get_num_topics_res);

    //close connection to MySQL
    $conn->close();

    //close the table

    $display_block .= "</table></div>";
}
?>

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
            <a class="nav-link  active" href="forumcategories.php">Forum</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="index.php">Products</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="cart.php"><i class="fas fa-shopping-cart"><span id="cart-item" class="badge badge-danger"></span></i>
            </a>
        </li>
        
    </ul>
  </div>
</nav> <!--END OF NAV-->




<!--START OF CATEGORIES DISPLAY-->
<div class="container">

    <div class="row mt-2 pb-3">
        <?= $display_block; ?>
    </div>

    
</div>
<!--END OF CATEGORIES DISPLAY-->




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