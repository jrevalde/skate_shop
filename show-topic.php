<?php
    include "config.php";

    //Check required info from the query string
    $c_id = $_GET['c_id'];
    if(!isset($_GET['topic_id']))
    {   
        echo "<h1>Big nuts</h1>";
        //header("Location: topiclist.php");
        exit;
    }

    //clean the values to be used

    $safe_topic_id = $conn->real_escape_string($_GET['topic_id']);
    
    //verify the topic exists
    $verify_topic_sql = "SELECT topic_title FROM forum_topics WHERE topic_id = '" . $safe_topic_id. "'";
    $verify_topic_res = $conn->query($verify_topic_sql);

    if ($verify_topic_res->num_rows < 1)
    {
        //the topic doesn't exist
        $display_block = "<p><em>You have selected an invalid topic.<br>
        Please <a href='topiclist.php'>try again</a>.
        </em></p>";
    }
    else
    {
        //get the topic title
        while($topic_info = $verify_topic_res->fetch_assoc())
        {
            $topic_title = stripslashes($topic_info['topic_title']);
        }
        //gather the posts

        $get_posts_sql = "SELECT post_id, post_text, DATE_FORMAT(post_create_time,
        '%b %e %Y<br>%r') AS fmt_post_create_time, post_owner FROM forum_posts WHERE topic_id = '". $safe_topic_id."'
        ORDER BY post_create_time ASC";

        $get_posts_res = $conn->query($get_posts_sql);

        //create the display string
        $display_block = <<<EOT
            

            <div class="table-responsive mt-2">
            <table class='table table-bordered table-striped text-center'>
                <thead>
                    <tr>
                        <td colspan="7">
                            <h4 class="text-center text-info m-0">Showing posts for the <strong>$topic_title</strong> topic:</h4>
                        </td>
                    </tr>    
                    <tr>
                        <th>Author</th>
                        <th>Post</th>
                    </tr>
                </thead>
               
        EOT;

        while($posts_info = $get_posts_res->fetch_assoc())
        {
            $post_id = $posts_info['post_id'];
            $post_text = nl2br(stripslashes($posts_info['post_text'])); //this function inserts line-breaks where newlines (\n) appear in the string.
            $post_create_time = $posts_info['fmt_post_create_time'];
            $post_owner = stripslashes($posts_info['post_owner']);

            //add to display
            $display_block .= <<<EOT
            <tbody>
                <tr>
                    <td>
                        $post_owner<br>
                        created on: <br>$post_create_time
                    </td>
                    <td>
                        $post_text<br>
                        <a href='replytopost.php?post_id=$post_id & c_id=$c_id'><strong>REPLY TO POST</strong></a>
                    </td>
                </tr>
            </tbody>

            EOT;
        }

        //free results

        mysqli_free_result($get_posts_res);
        mysqli_free_result($verify_topic_res);

        //close connection to MySql
        $conn->close();

        //close up the table

        $display_block .= "</table></div>";
    }


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts in Topic</title>

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
    <div id="message"></div>
    <div class="row mt-2 pb-3">
        <h1>Posts in Topic</h1>
        <?php echo $display_block; ?>
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