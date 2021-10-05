<?php
    session_start();
    require "config.php";


    //This section is for inserting things into the cart table after a product has been selected from the view
    if(isset($_POST['pcode']))
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
        
        if(!isset($r['product_code']))
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
        /*$code = $r['product_code'];
        
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
        */
    }


    //makes the Shopping cart icon display the number of cart items on the nav section.
    if(isset($_GET['cartItem']) && isset($_GET['cartItem']) == 'cart-item')
    {
        $stmnt = $conn->prepare("SELECT * FROM cart");
        $stmnt->execute();
        $stmnt->store_result();
        $rows = $stmnt->num_rows;

        echo $rows;
    }


    //This section handles deleting things from the cart. using the id of the product selected from the cart.

    if(isset($_GET['remove']))
    {
        $id = $_GET['remove'];
        $stmnt = $conn->prepare("DELETE FROM cart WHERE id = ?");
        $stmnt->bind_param("i", $id);
        $stmnt->execute();
        
        $_SESSION['showAlert'] = 'block'; //so this carries over to the cart so it can dynamically show the div that confirms that the item was removed.
        $_SESSION['message'] = 'item removed from cart.';
        header('location: cart.php'); //redirects to the cart.
    }

    //this one triggers when the clear cart button is pressed. it clears the entire cart.
    if(isset($_GET['clear']))
    {
        $stmnt = $conn->prepare("DELETE FROM cart");
        $stmnt->execute();
        $_SESSION['showAlert'] = 'block';
        $_SESSION['message'] = 'Cart is cleared.';
        header("location: cart.php"); //redirects to the cart.
    }


    //calculates the grand total taking into account the quantity of products. It then updates cart table.
    if(isset($_POST['qty']))
    {
        $qty = $_POST['qty'];
        $pid = $_POST['pid'];
        $pprice = $_POST['pprice'];

        $tprice = $qty * $pprice;

        $stmnt = $conn->prepare("UPDATE cart SET qty = ?, total_price = ? WHERE id = ?");
        $stmnt->bind_param("isi", $qty, $tprice, $pid);
        $stmnt->execute();

    }   


    //recieves data passed from checkout form, inserts the data into an orders table and prints out a confirmation. 
    if(isset($_POST['action']) && isset($_POST['action']) == 'order')
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $products = $_POST['products'];
        $grand_total = $_POST['grand_total'];
        $address = $_POST['address'];
        $pmode = $_POST['pmode'];

        $data = "";

        //takes the qty from cart table and substracts the stock in products table
        $sql1 = "SELECT qty, product_code FROM cart";
        
        $result = $conn->query($sql1);

        while($row = $result->fetch_assoc())
        {
            
            //echo $row['qty'] . " <br>" . $row['product_code'] . "<br>"; (Just checking that the value comes through.)
            $stocktake = $conn->prepare("UPDATE product SET stock = stock - ? WHERE product_code = ?");
            $stocktake->bind_param("is",$row['qty'] , $row['product_code']);
            $stocktake->execute();
        }
        

        //print out confirmation 
        $stmnt = $conn->prepare("INSERT INTO orders (name, email, phone, address, pmode, products, amount_paid) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmnt->bind_param("sssssss", $name, $email, $phone, $address, $pmode, $products, $grand_total);
        $stmnt->execute();
        $data .= "<div class='text-center'>
                        <h1 class='display-4 mt-2 text-danger'>Your order placed successfully.</h1>
                        <h4 class='bg-danger text-light rounded p-2'>Items Purchased : $products</h4>
                        <h4>Your Name : $name</h4>
                        <h4>Your Email : $email</h4>
                        <h4>Your Phone : $phone</h4>
                        <h4>Total Amount Paid : number_format($grand_total, 2)</h4>
                        <h4>Payment Mode : $pmode</h4>
                    </div>";
        echo $data;

        //We then want to delete all from Cart since it has already been purchased and recorded in the orders table. 

        $snt_sql = "DELETE from cart";

        $snt_res = $conn->query($snt_sql);
    }



   

    
?>   