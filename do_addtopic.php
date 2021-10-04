<?php
require "config.php";

//Check for required fields from the form

if (!$_POST['topic_owner'] || !$_POST['topic_title'] || !$_POST['post_text'] || !$_POST['c_id'])
{
    header("Location: addtopic.php");
    exit;
}

//clean the values to be inputed into the databse

$clean_topic_owner =  $conn->real_escape_string($_POST['topic_owner']);   
$clean_topic_title = $conn->real_escape_string($_POST['topic_title']);   
$clean_post_text = $conn->real_escape_string($_POST['post_text']);    
$c_id = $_POST['c_id'];
//Query for inserting into forum_topics

$add_topic_sql = "INSERT INTO forum_topics (topic_title, topic_create_time, topic_owner, c_id)
VALUES ('".$clean_topic_title ."', now(), '".$clean_topic_owner."', '".$c_id."')";

$add_topic_res = $conn->query($add_topic_sql);

/*get id of the last query. retrieves the primary key ID of last inserted record into database. it gets Id value from forum topics table.  
It will become the entry the entry for the topic_id field in the forum posts table.*/
$topic_id = $conn->insert_id;

//Creating and issuing the second query to insert into forum_posts

$add_post_sql = "INSERT INTO forum_posts (topic_id, post_text, post_create_time, post_owner, c_id) VALUES
('".$topic_id."', '".$clean_post_text."',
now(), '".$clean_topic_owner."', '".$c_id."')";

$add_post_res = $conn->query($add_post_sql);

//close connection to Mysql

$conn->close(); 

//Send a nice message to the user.
$display_block = "<p>The <strong>" .$_POST['topic_title']. "</strong>topic has been created.</p>";
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
            <a class="nav-link" href="#">Login</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">About</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Contact Us</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="forumcategories.php">Forum</a>
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

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6 px-4 pb-4">
            <h1>New Topic has been Added</h1>
            <?php echo $display_block; ?>
        </div>

    </div>
</div>

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