<?php
    include "config.php";

    //check to see if we're showing the form or adding the post
    if(!$_POST)
    {
        //showing the form; check for required item in query string
        if (!isset($_GET['post_id']))
        {
            header("Location: topiclist.php");
            exit;
        }
    }

    //create safe values for use
    $safe_post_id = $conn->real_escape_string($_GET['post_id']);
    
    $verify_sql = "SELECT ft.topic_id, ft.topic_title FROM forum_posts AS fp LEFT JOIN forum_topics AS ft.topic_id = ft.topic_id WHERE fp.post_id = '".$safe_post_id."'";
    $verify_res = $conn->query($verify_sql);

    if ($verify_res->num_rows < 1)
    {
        //this post or topic does not exist
        header("Location: topiclist.php");
        exit;
    }
    else
    {
        //get the topic id and title
        while($topic_info = mysqli_fetch_array($verify_res))
        {
            $topic_id = $topic_info['topic_id'];
            $topic_title = stripslashes($topic_info['topic_title']);
        }
    
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><h1>Post your reply in <?php echo $topic_title; ?></h1></title>

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

<div class="container">
    <div id="message"></div>
    <div class="row mt-2 pb-3">
       <h1>Post your reply in <?php echo $_SERVER['PHP_SELF']; ?></h1>
       <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <p><label for="post_owner">Your Email Address:</label>
            <input type="email" id="post_owner" name="post_owner" size="40" maxlength="150" required>    
        </p>
        <p>
            <label for="post_text">Post Text:</label><br>
            <textarea name="post_text" id="post_text" cols="40" rows="8" placeholder="Please write a post..." required></textarea>
        </p>
        <input type="hidden" name="topic_id" value="<?php echo $topic_id; ?>">
        <button type="sobmit" name="sobmit" value="sobmit">Add Post</button>
       </form>
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

<?php
    }
    //free result
    mysqli_free_result($verify_res);

    //close connection to MySql
    $conn->close();

}
else if ($_POST)
{
    //check for required items from form
    if ((!$_POST['topic_id']) || (!$_POST['post_text']) || (!$_POST['post_owner']))
    {
        header("Location: topiclist.php");
        exit;
    }

    //create safe values for use
    $safe_topic_id = $conn->real_escape_string($_POST['topic_id']);
    $safe_post_text = $conn->real_escape_string($_POST['post_text']);
    $safe_post_owner = $conn->real_escape_string($_POST['post_owner']);

    //add the post
    $add__post_sql = "INSERT INTO forum_posts (topic_ic, post_text, post_create_time, post_owner) VALUES ('".$safe_topic_id."', '".$safe_post_text."', now(), '".$safe_post_owner."')";

    $add_post_res = $conn->query($add__post_sql);

    //close connection to MySQL

    $conn->close();
    //redirect user to topic
    header("Location: showtopic.php?topic_id=".$_POST['topic_id']);
    exit;
}    
?>