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
            $sql = " SELECT * FROM tbl_wishlists WHERE user_id=$userId";
            $result = mysqli_query($conn, $sql);
            $count = mysqli_num_rows($result);

            return $count;
        } 
        return 0;
       
    }

    // GET WISHLIST COUNT PER PRODUCT
    function getProductWishlishtCount($conn, $productId) {

        if($productId) {
            $sql = " SELECT * FROM tbl_wishlists WHERE product_id=$productId";
            $result = mysqli_query($conn, $sql);
            $count = mysqli_num_rows($result);

            return $count;
        } 
        return 0;
       
    }


    // CHECK IF IN WISHLIST
    function checkIfInWishlist($conn,$productId) {

        if(isset($_SESSION['id'])) {
            $userId = $_SESSION['id'];
            if($userId) {
                $sql = " SELECT * FROM tbl_wishlists WHERE user_id=$userId AND product_id = $productId";
                $result = mysqli_query($conn, $sql);
                $countr = mysqli_num_rows($result);
    
                return $countr;
            } 
            return 0;
        }
       
       
    }

    // CHECK IF IN CART
    function checkIfInCart($conn, $productId) {

        if(isset($_SESSION['cart_session'])){
            $cartSession = $_SESSION['cart_session'];
            $sql = " SELECT * FROM tbl_carts WHERE cart_session='$cartSession' AND item_id=$productId";
                $result = mysqli_query($conn, $sql);
                $countc = mysqli_num_rows($result);
                return $countc;
        } 
        return 0;
    }
    
    // COUNT NUMBER OF RATINGS PER PRODUCT
    function  countRatingsPerProduct($conn, $productId) {

        $sql = " SELECT * FROM tbl_ratings WHERE product_id=$productId ";
        $result = mysqli_query($conn, $sql);
        $count = mysqli_num_rows($result);

        return $count;
    }

    // GET AVERANGE PRODUCT REVIEWS
    function getAveProductReview($conn, $productId) {
        $sql = " SELECT AVG(product_rating) FROM tbl_ratings WHERE product_id=$productId ";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $averageProductReview = $row['AVG(product_rating)'];

        return round($averageProductReview, 2);
    }

    // DISPLAY USER RATING OF PRODUCT -- not yet used
    function displayUserRating($conn, $userId, $productId) {
        $sql = " SELECT * FROM tbl_ratings WHERE `user_id`=$userId AND product_id=$productId ";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        $rating = $row['product_rating'];

        return $rating;
    }