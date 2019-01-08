
<?php
    session_start(); 
    require_once "../controllers/connect.php";
    
    $cartSession = $_SESSION['cart_session'];
    $sql = " SELECT * FROM tbl_carts WHERE cart_session=?";
    $statment = $conn->prepare($sql);
    $statment->execute([$cartSession]);
    //$result = mysqli_query($conn, $sql);
    //$productCount = mysqli_num_rows($result);
    $productCount = $statment->rowCount();
?>

                        <ul class="navbar-nav ml-auto" id="navbar-nav">
                            
                            <!-- CATALOG -->
                            <li class="nav-item mr-5">
                                <a class="nav-link text-light" href="catalog.php">
                                <i class="fas fa-book"></i>
                                Catalog
                                </a>
                            </li>

                            <!-- CART -->
                            <li class="nav-item mr-5">
                                <a class='nav-link modal-link-big text-light' 
                                    href='#' 
                                    data-id='<?= $_GET['id'] ?>' 
                                    data-url='../partials/templates/cart_modal.php' 
                                    role='button'
                                    id='cartModal'>

                                    <i class="fas fa-cart-plus"></i>
                                        Cart
                                    <span id="item-count">

                                    <?php 
                                        if ($productCount) {
                                        echo "<span class='badge border-0 circle'>" . $productCount . "</span>";
                                        } elseif ($productCount == 0){
                                        echo "";
                                        } else {
                                        echo "";
                                        }
                                    ?>

                                    </span>
                                </a>
                            </li>
                            
                            <!-- LOGOUT AND MY ACCOUNT -->
                                    <?php if(isset($_SESSION['id'])) { 
                                        $id = $_SESSION['id'];
                            
                                        echo "
                            <li class='nav-item mr-5'>
                                <a class='nav-link modal-link text-light' href='../controllers/process_logout.php' role='button'>
                                    <i class='fas fa-sign-in-alt'></i>
                                    Logout
                                </a>
                            </li>

                            <li class='nav-item'>
                                <a class='nav-link text-light' href='profile.php?id=$id'>
                                    <i class='fas fa-user'></i>
                                    My Account
                                </a>
                            </li>";

                                    } else { 

                            echo "
                            <li class='nav-item mr-5'>
                                <a class='nav-link modal-link text-light' href='#'' data-url='../partials/templates/login_modal.php' role='button'>
                                    <i class='fas fa-sign-in-alt'></i>
                                    Login
                                </a>
                            </li>

                            <li class='nav-item'>
                                <a class='nav-link text-light' href='register.php'>
                                    <i class='fas fa-user'></i>
                                    Register
                                </a>
                            </li>";

                                    } ?>


                        </ul>