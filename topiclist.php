<?php
include "config.php";

//gather the topics

$get_topics_sql = "SELECT topic_id, topic_title, DATE_FORMAT(topic_create_time, '%b, %e, %Y at %r') AS
fmt_topic_create_time, topic_owner FROM forum_topics
ORDER BY topic_create_time DESC";

$get_topic_res = $conn->query($get_topics_sql);

if ($get_topic_res->num_rows < 1) //checks if there are any topics
{
    $display_block = "<p><em>No topics exist.</em></en></p>";
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
                    <h4 class="text-center text-info m-0">Topics in Forum</h4>
                </td>
            </tr>        
            <tr>
                <th>
                    TOPIC TITLE
                </th>
                <th>
                    # of Posts
                </th>
            </tr>

        </thead>
 
    EOT;

    while($topic_info = $get_topic_res->fetch_assoc())
    {
        $topic_id = $topic_info['topic_id'];
        $topic_title = stripslashes($topic_info['topic_title']);
        $topic_create_time = $topic_info['fmt_topic_create_time'];
        $topic_owner = stripslashes($topic_info['topic_owner']);

        //get number of posts
        $get_num_posts_sql = "SELECT COUNT(post_id) AS post_count FROM
        forum_posts WHERE topic_id = '". $topic_id."'";

        $get_num_posts_res = $conn->query($get_num_posts_sql);

        while($posts_info = $get_num_posts_res->fetch_assoc())
        {
            $num_posts = $posts_info['post_count'];
        }

        //add to display
        $display_block .= <<<EOT
        <tbody>
            <tr>
                <td>
                    <a href='show-topic.php?topic_id=$topic_id'><strong>$topic_title</strong></a><br>
                    Created on $topic_create_time by $topic_owner
                </td>
                <td>$num_posts</td>
            </tr>            
        </tbody>

        EOT;
    }

    //free results
    mysqli_free_result($get_topic_res);
    mysqli_free_result($get_num_posts_res);

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
            <a class="nav-link" href="#">Login</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="index.php">About</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Contact Us</a>
        </li>
        <li class="nav-item">
            <a class="nav-link  active" href="#">Forum</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="index.php">Products</a>
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

<div class="container">
    <div id="message"></div>
    <div class="row mt-2 pb-3">
        <?= $display_block; ?>
        <p>Would you like to <a href="addtopic.php">add a topic</a>?</p>
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