<?php
    include "config.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a topic</title>

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


<!--START OF ADD TOPIC FORM-->
<div class="container">

    <div class="row justify-content-center">
        <div class="col-lg-6 px-4 pb-4">
            <h1>Add a Topic for <?php echo $_GET['c_title']; ?></h1>
            <form action="do_addtopic.php" method="post" class="needs-validation">
                <input type="hidden" value="<?php echo $_GET['c_id']; ?>" name="c_id">

                <div class="form-group">
                    <label for="topic_owner">Email address:</label>
                    <input type="email" class="form-control" placeholder="Enter email" id="topic_owner" name="topic_owner" required>
                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>
            
                <div class="form-group">
                    <label for="topic_title">Topic Title:</label>
                    <input type="text" class="form-control" name="topic_title" id="topic_title" placeholder="Enter topic title please." required>
                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                <div class="form-group">
                    <textarea name="post_text" class="form-control" id="post_text" cols="10" rows="3" placeholder="Write message here please." required></textarea>
                    <div class="valid-feedback">Valid.</div>
                    <div class="invalid-feedback">Please fill out this field.</div>
                </div>

                

                <button type="submit" value="submit" class="btn btn-primary btn-block">Submit</button>
            </form>
        </div>
    </div>
    

    
</div>
<!--END OF ADD TOPIC FORM-->


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
    
<script>
// Disable form submissions if there are invalid fields
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Get the forms we want to add validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>


</body>
</html>