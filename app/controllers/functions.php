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

    // GET WISHLIST
    function getWishlishtCount($conn) {

        $userId = $_SESSION['id'];
        if($userId) {
            $sql = " SELECT * FROM tbl_wishlists WHERE user_id=$userId";
            $result = mysqli_query($conn, $sql);
            $count = mysqli_num_rows($result);

            return $count;
        } 
        return 0;
       
    }


    // CHECK IF IN WISHLIST
    function checkIfInWishlist($conn,$productId) {

        $userId = $_SESSION['id'];
        if($userId) {
            $sql = " SELECT * FROM tbl_wishlists WHERE user_id=$userId AND product_id = $productId";
            $result = mysqli_query($conn, $sql);
            $countr = mysqli_num_rows($result);

            return $countr;
        } 
        return 0;
       
    }


    	



?>