<?php
    require "config.php";

    if(isset($_POST['pid']))
    {
        $pid = $_POST['pid'];
        $pname = $_POST['pname'];
        $pprice = $_POST['pprice'];
        $pimage = $_POST['pimage'];
        $pcode = $_POST['pcode'];
        $pqty = 1;

        $stmnt = $conn->prepare("SELECT product_code FROM cart WHERE product_code=?");
        $stmnt->bind_param("s", $pcode);
        $stmnt->execute();
        $res = $stmnt->get_result();
        $r = $res->fetch_assoc();

        $code = $r['product_code'];

        if (!$code)
        {
            $query = $conn->prepare("INSERT INTO cart (product_name, product_price, product_image, qty, total_price, product_code) VALUES (?, ?, ?, ?, ?,?)");
            $query->bind_param("sssiss", $pname, $pprice, $pimage, $pqty, $pprice, $pcode);
            $query->execute();

            echo '<div class="alert alert-success alert-dismissible mt-2">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Item added to your cart.</strong> 
                </div>';
        }
        else
        {
            echo '<div class="alert alert-danger alert-dismissible mt-2">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>Item already inside of cart.</strong> 
                </div>';   
        }
    }

    if(isset($_GET['cartItem']) && isset($_GET['cartItem']) == 'cart-item')
    {
        $stmnt = $conn->prepare("SELECT * FROM cart");
        $stmnt->execute();
        $stmnt->store_result();
        $rows = $stmnt->num_rows;

        echo $rows;
    }

    if(isset($_GET['remove']))
    {
        $id = $_GET['remove'];
        $stmnt = $conn->prepare("DELETE FROM cart WHERE id = ?");
        $stmnt->bind_param("i", $id);
        $stmnt->execute();
        
        $_SESSION['showAlert'] = 'block';
        $_SESSION['message'] = 'item removed from cart.';
        header('location: cart.php');
    }

?>   