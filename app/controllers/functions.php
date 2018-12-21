<?php 

    //HIDE EMAIL
    function hide_email($email){
        $em   = explode("@",$email);
        $name = implode(array_slice($em, 0, count($em)-1), '@');
        $len  = floor(strlen($name)/2);
    
        return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);   
    }

    //HIDE NUMBER
    function hide_number($number){
        $mask_number =  str_repeat("*", strlen($number)-4) . substr($number, -4);
        return $mask_number;
    }

    //RESIZE PROFILE IMAGE
    function resize_image($file, $w, $h, $crop=FALSE) {
        list($width, $height) = getimagesize($file);
        $r = $width / $height;
        if ($crop) {
            if ($width > $height) {
                $width = ceil($width-($width*abs($r-$w/$h)));
            } else {
                $height = ceil($height-($height*abs($r-$w/$h)));
            }
            $newwidth = $w;
            $newheight = $h;
        } else {
            if ($w/$h > $r) {
                $newwidth = $h*$r;
                $newheight = $h;
            } else {
                $newheight = $w/$r;
                $newwidth = $w;
            }
        }
        $src = imagecreatefromjpeg($file);
        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    
        return $dst;
    }

    // GET WISHLIST COUNT PER USER
    function getWishlishtCount($conn) {

        @$userId = $_SESSION['id'];
        if($userId) {
            $sql = " SELECT * FROM tbl_wishlists WHERE user_id=?";
            //$result = mysqli_query($conn, $sql);
            $statement = $conn->prepare($sql);
            $statement->execute([$userId]);
            $count = $statement->rowCount();
            return $count;
        } 
        return 0;
       
    }

    // GET WISHLIST COUNT PER PRODUCT
    function getProductWishlishtCount($conn, $productId) {

        if($productId) {
            $sql = " SELECT * FROM tbl_wishlists WHERE product_id=?";
            $statement = $conn->prepare($sql);
            $statement->execute([$productId]);
            return $statement->rowCount();
        } 
        return 0;
       
    }


    // CHECK IF IN WISHLIST
    function checkIfInWishlist($conn,$productId) {

        if(isset($_SESSION['id'])) {
            $userId = $_SESSION['id'];
            if($userId) {
                $sql = " SELECT * FROM tbl_wishlists WHERE user_id=? AND product_id =?";
                $statement = $conn->prepare($sql);
                $statement->execute([$userId, $productId]);
                return $statement->rowCount();
                
            } 
            return 0;
        }
       
       
    }

    // CHECK IF IN CART
    function checkIfInCart($conn, $productId) {

        if(isset($_SESSION['cart_session'])){
            $cartSession = $_SESSION['cart_session'];
            $sql = " SELECT * FROM tbl_carts WHERE cart_session=? AND item_id=?";
            $statement = $conn->prepare($sql);
            $statement->execute([$cartSession, $productId]);
            return $statement->rowCount();
        } 
        return 0;
    }
    
    // COUNT NUMBER OF RATINGS PER PRODUCT
    function  countRatingsPerProduct($conn, $productId) {

        $sql = " SELECT * FROM tbl_ratings WHERE product_id=? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$productId]);
        return $statement->rowCount();

        return $count;
    }

    // GET AVERANGE PRODUCT REVIEWS -- to be deleted when other pages are fixed
    function getAveProductReview($conn, $productId) {
        $sql = " SELECT AVG(product_rating) as productAverage FROM tbl_ratings WHERE product_id=? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$productId]);
        $row = $statement->fetch();
        $averageProductReview = $row['productAverage'];
        return round($averageProductReview, 2);

    }

    // DISPLAY USER RATING OF PRODUCT -- not yet used
    function displayUserRating($conn, $userId, $productId) {
        $sql = " SELECT * FROM tbl_ratings WHERE `user_id`=? AND product_id=? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$userId, $productId]);
        $row = $statement->fetch();
        $rating = $row['product_rating'];

        return $rating;
    }

    // CHANGE BEGINNING LETTER TO UPPERCASE
    function capitalizeFirstLetter($word) {
        return str_replace('( ', '(', ucwords(str_replace('(', '( ', ucwords(strtolower($word)))));
    }

    // GET USERNAME
    function getUsername ($conn,$userId) {
        $sql = " SELECT * FROM tbl_users WHERE `id`=? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$userId]);
        $row = $statement->fetch();
        $username = $row['username'];

        return ucwords($username);
    }

    // GET PROFILE PIC 
    function getProfilePic ($conn,$userId) {
        $sql = " SELECT * FROM tbl_users WHERE `id`=? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$userId]);
        $row = $statement->fetch();
        $profile_pic = $row['profile_pic'];

        return $profile_pic;
    }

    // DISPLAY BREADCRUMB 
    function displayBreadcrumbs ($conn,$productId,$origin) {
        $sql = "SELECT i.name as 'product_name', i.brand_id as 'brand_id',c.name as 'category_name', c.parent_category_id, c.id AS 'category_id',b.brand_name as 'brand_name' FROM tbl_ratings r JOIN tbl_categories c JOIN tbl_items i JOIN tbl_brands b ON i.category_id = c.id AND r.product_id = i.id AND i.brand_id=b.id WHERE product_id = ? GROUP BY product_id";
        $statement = $conn->prepare($sql);
        $statement->execute([$productId]);
        $row = $statement->fetch();
        $productName = $row['product_name'];
        $brandId = $row['brand_id']; //could be used to link to brand page later
        $categoryName = $row['category_name'];
        $parentCategoryId = $row['parent_category_id']; // to fetch parent name later on
        $brandName = $row['brand_name'];


        $sql = "SELECT * FROM tbl_categories WHERE id = ? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$parentCategoryId]);
        $row = $statement->fetch();
        $parentCategoryName = $row['name'];

        $whereUserIsFrom = "";
        $url = "";
        $arrow ="";

        if($origin == "http://localhost/tuitt/cp2-ecommerce/app/views/index.php"){
            $whereUserIsFrom = "Home";
            $url = "index.php";
            $arrow = "<i class='fas fa-angle-right text-gray'></i>";
        }elseif($origin == "http://localhost/tuitt/cp2-ecommerce/app/views/catalog.php?id=$productId"){
            $whereUserIsFrom = "Catalog";
            $url = "catalog.php?id=$productId";
            $arrow = "<i class='fas fa-angle-right text-gray'></i>";
        }else {
            $whereUserIsFrom ="";
            $url = "";
        }

        // var_dump($origin);die();
        
            echo "
        
            <span clas>
                <a href='$url' class='text-gray'>
                    &nbsp;$whereUserIsFrom&nbsp; 
                </a>
                $arrow
            </span>
            <span>
                <a href='#' class='text-gray'>
                    &nbsp;$parentCategoryName&nbsp;
                </a>
            </span>
            <span>
                <i class='fas fa-angle-right text-gray'></i>
                <a href='#' class='text-gray'>
                    &nbsp;$categoryName&nbsp;
                </a>
            </span>
            <span>
                <i class='fas fa-angle-right text-gray'></i>
                <a href='#' class='text-gray'>
                    &nbsp;$brandName&nbsp;
                </a>
            </span>
            <span>
                <i class='fas fa-angle-right text-gray'></i>
                <a href='#' class='text-gray'>
                    &nbsp;$productName
                </a>
            </span>
            
            ";
        

        

    }