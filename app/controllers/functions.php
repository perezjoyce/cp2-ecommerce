<?php 
    // require_once '../sources/pdo/src/PDO.class.php';
    // require_once "../../config.php";

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

    //COUNT ITEMS IN CART
    function itemsInCart($conn,$cartSession) {
        $sql = " SELECT SUM(quantity) as 'itemsInCart' FROM tbl_carts WHERE cart_session = ? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$cartSession]);
        $row = $statement->fetch();
        $itemsInCart = $row['itemsInCart'];

        return  $itemsInCart;
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

    // CHANGE BEGINNING LETTER TO UPPERCASE --> ucwords?
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

        return strtoupper($username);
    }

    // GET FIRST NAME
    function getFirstName ($conn,$userId) {
        $sql = " SELECT * FROM tbl_users WHERE `id`=? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$userId]);
        $row = $statement->fetch();
        $firstName = $row['first_name'];

        return strtoupper($firstName);
    }

    // GET LASTNAME
    function getLastName ($conn,$userId) {
        $sql = " SELECT * FROM tbl_users WHERE `id`=? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$userId]);
        $row = $statement->fetch();
        $lastName = $row['last_name'];

        return strtoupper($lastName);
    }

    // GET NAME FROM BILLIG ID
    function getWhoWillPay ($conn,$billingAddressId){
        $sql = " SELECT * FROM tbl_addresses WHERE `id`=? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$billingAddressId]);
        $row = $statement->fetch();
        $name = $row['name'];

        return $name;
    }

    // GET STORE NAME
    function getStore ($conn,$storeId) {
        $sql = " SELECT * FROM tbl_stores WHERE `id`=? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$storeId]);
        if($statement->rowCount()) {
            return $statement->fetch();
        }

        throw new \Exception("No store with ID: $storeId");
    }

    // GET STORE NAME
    function getStoreName ($conn,$userId) {
        $sql = " SELECT * FROM tbl_stores WHERE `user_id`=? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$userId]);
        $row = $statement->fetch();
        $storeName = $row['name'];

        return $storeName;
    }

    // GET STORE LOGO
    function getStoreLogo ($conn,$userId) {
        $sql = " SELECT * FROM tbl_stores WHERE `user_id`=? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$userId]);
        $row = $statement->fetch();
        $storeLogo = $row['logo'];

        return $storeLogo;
    }

    // GET AVERAGE RATING PER STORE
    function getAverageStoreRating ($conn, $storeId) {
       
        $sql = "SELECT i.store_id, AVG(product_rating) as 'averageRating' FROM tbl_ratings LEFT JOIN tbl_items i ON product_id = i.id WHERE store_id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$storeId]);
        $row = $statement->fetch();
        $averageStoreRating = $row['averageRating'];	
        $averageStoreRating = round($averageStoreRating,1);

        return $averageStoreRating;
                        
    }

    // GET STORE DESCRIPTION
    function getStoreDescription ($conn,$userId) {
        $sql = " SELECT * FROM tbl_stores WHERE `user_id`=? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$userId]);
        $row = $statement->fetch();
        $storeDescription = $row['description'];

        return $storeDescription;
    }

    // GET STORE DESCRIPTION
    function getStoreAddress ($conn,$userId) {
        $sql = " SELECT * FROM tbl_stores WHERE `user_id`=? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$userId]);
        $row = $statement->fetch();
        $storeAddress = $row['store_address'];

        return $storeAddress;
    }

    // GET STORE BUSINESS HOURS
    function getStoreHours ($conn,$userId) {
        $sql = " SELECT * FROM tbl_stores WHERE `user_id`=? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$userId]);
        $row = $statement->fetch();
        $storeHours = $row['hours'];

        return $storeHours;
    }

    // GET STORE ID FROM USER ID
    function getStoreId ($conn,$userId) {
        $sql = " SELECT * FROM tbl_stores WHERE `user_id`=? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$userId]);
        $row = $statement->fetch();
        $storeId = $row['id'];

        return $storeId;
    }

    // COUNT STORE FOLLOWERS 
    function countFollowers ($conn, $storeId) {
        $sql = " SELECT COUNT(*) AS 'followers' FROM tbl_followers WHERE store_id = ? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$storeId]);
        $row = $statement->fetch();
        $storeFollowers = $row['followers'];

        return $storeFollowers;
    }

    // GET MEMBERSHIP DATE
    function getMembershipDate($conn, $storeId) {
        
        $sql = "SELECT DATE_FORMAT(date_created, '%M %d, %Y') AS 'dateJoined' FROM tbl_stores WHERE id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$storeId]);
        $row = $statement->fetch();
        $dateJoined = $row['dateJoined'];	
        $month = substr($dateJoined,0,3);
        $daysYear = substr(strstr($dateJoined," "), 1);
        $dateJoined = $month." ".$daysYear;

        return $dateJoined;
                    
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

    // GET STOCKS
    function getTotalProductStocks ($conn,$productId){
        $sql = "SELECT SUM(variation_stock) as 'totalStocksAvailable'  FROM tbl_variations WHERE product_id = ?";
            $statement = $conn->prepare($sql);
            $statement->execute([$productId]);
            $row = $statement->fetch();
            $totalStocksAvailable = $row['totalStocksAvailable'];

            return $totalStocksAvailable;
    }

    // SHOW SHIPPING FEE
    function displayShippingFee($conn,$productId) {
        $sql = "SELECT s.standard_shipping, i.store_id, i.id FROM tbl_stores s 
        JOIN tbl_items i ON i.store_id = s.id WHERE i.id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$productId]);
        $row = $statement->fetch();
        $shippingFee = $row['standard_shipping'];
        $shippingFee = number_format((float)$shippingFee, 2, '.', ',');    
        return $shippingFee;

    }

    
    // SHOW MINIMUM AMOUNT REQUIRED TO AVAIL FREE SHIPPING
    function displayFreeShippingMinimum($conn,$productId) {
        $sql = "SELECT s.free_shipping_minimum, i.store_id, i.id FROM tbl_stores s 
        JOIN tbl_items i ON i.store_id = s.id WHERE i.id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$productId]);
        $row = $statement->fetch();
        $freeShippingMinimum =$row['free_shipping_minimum'];
        if($freeShippingMinimum != 0 || !$freeShippingMinimum ){
            return $freeShippingMinimum;
        }
    }

    // SHOW SHIPPING FEE FROM STORE ID
    function displayStoreShippingFee($conn,$storeId) {
        $sql = "SELECT s.standard_shipping, i.store_id, i.id FROM tbl_stores s 
        JOIN tbl_items i ON i.store_id = s.id WHERE store_id = ? GROUP BY store_id";
        $statement = $conn->prepare($sql);
        $statement->execute([$storeId]);
        $row = $statement->fetch();
        $storeShippingFee = $row['standard_shipping'];
        $storeShippingFee = number_format((float)$storeShippingFee, 2, '.', ',');    
        return $storeShippingFee;
    }

    // SHOW FREE SHIPPING FROM STORE ID
    function displayStoreFreeShipping($conn,$storeId, $format=true) {
        $sql = "        SELECT s.free_shipping_minimum, i.store_id, i.id FROM tbl_stores s 
        JOIN tbl_items i ON i.store_id = s.id WHERE store_id = ? GROUP BY store_id";
        $statement = $conn->prepare($sql);
        $statement->execute([$storeId]);
        $row = $statement->fetch();
        $storeFreeShipping =$row['free_shipping_minimum'];

        if($format) : 
            $storeFreeShipping = number_format((float)$storeFreeShipping, 2, '.', ',');    
        endif;

        if( $storeFreeShipping != 0 || !$storeFreeShipping){
            return $storeFreeShipping;
        }
    }

    //DISPLAY GRANDTOTAL (WITHOUT SHIPPING)
    function displayGrandTotal($conn, $cartSession) {
        $sql = "SELECT c.cart_session, SUM(i.price * c.quantity) 
        AS 'grandTotal' 
        FROM tbl_items i 
        JOIN tbl_carts c 
        JOIN tbl_variations v 
        ON v.product_id=i.id 
        AND c.variation_id=v.id 
        WHERE c.cart_session = ? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$cartSession]);
        $row = $statement->fetch();
        $grandTotal = $row['grandTotal'];
        $grandTotal = number_format((float)$grandTotal, 2, '.', '');    
        
        return $grandTotal;
    }

    //DIPLAY GRANDTOTAL OR SELLER IN CART (NO SHIPPING)
    function displayGrandTotalOfSeller($conn, $cartSession, $storeId) {
        $sql = "SELECT c.cart_session, i.store_id, SUM(i.price * c.quantity) 
        AS 'grandTotal' 
        FROM tbl_items i 
        JOIN tbl_carts c 
        JOIN tbl_variations v 
        ON v.product_id=i.id 
        AND c.variation_id=v.id 
        WHERE c.cart_session=? AND store_id=?";
        $statement = $conn->prepare($sql);
        $statement->execute([$cartSession, $storeId]);
        $row = $statement->fetch();
        $grandTotal = $row['grandTotal'];
        $grandTotal = number_format((float)$grandTotal, 2, '.', '');    
        return $grandTotal;
    }

    // DISPLAY BREADCRUMB 
    function displayBreadcrumbs ($conn,$productId,$origin) {
        $sql = "SELECT i.name as 'product_name', i.brand_id 
        as 'brand_id', i.store_id, c.name as 'category_name', c.parent_category_id, c.id 
        AS 'category_id',b.brand_name as 'brand_name' FROM tbl_ratings r 
        JOIN tbl_categories c JOIN tbl_items i JOIN tbl_brands b ON i.category_id = c.id 
        AND r.product_id = i.id AND i.brand_id=b.id WHERE product_id = ? GROUP BY product_id";

        $statement = $conn->prepare($sql);
        $statement->execute([$productId]);
        $row = $statement->fetch();
        $productName = $row['product_name'];
        $brandId = $row['brand_id']; //could be used to link to brand page later
        $categoryName = $row['category_name'];
        $parentCategoryId = $row['parent_category_id']; // to fetch parent name later on
        $brandName = $row['brand_name'];
        $storeId = $row['store_id'];


        $sql = "SELECT * FROM tbl_categories WHERE id = ? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$parentCategoryId]);
        $row = $statement->fetch();
        $parentCategoryName = $row['name'];

        $whereUserIsFrom = "";
        $url = "";
        $arrow ="";

        if($origin == BASE_URL . "/app/views/index.php"){
            $whereUserIsFrom = "Home";
            $url = "index.php";
            $arrow = "<i class='fas fa-angle-right text-gray'></i>";
            $vanish = "";
        }elseif($origin == BASE_URL . "/app/views/catalog.php?id=$productId"){
            $whereUserIsFrom = "Catalog";
            $url = "catalog.php?id=$productId";
            $arrow = "<i class='fas fa-angle-right text-gray'></i>";
            $vanish = "";
        }elseif($origin == BASE_URL . "/app/views/store-add-product.php?id=$storeId") {
            $whereUserIsFrom ="My Store";
            $url = "";
            $vanish = "";
        }else {
            $whereUserIsFrom ="";
            $url = "";
            $vanish = "vanish-sm vanish-md vanish-lg";
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
                <i class='fas fa-angle-right text-gray $vanish'></i>
                <a href='#' class='text-gray'>
                    &nbsp;$categoryName&nbsp;
                </a>
            </span>
            <span>
                <i class='fas fa-angle-right text-gray $vanish'></i>
                <a href='#' class='text-gray'>
                    &nbsp;$brandName&nbsp;
                </a>
            </span>
            <span>
                <i class='fas fa-angle-right text-gray $vanish'></i>
                <a href='#' class='text-gray'>
                    &nbsp;$productName
                </a>
            </span>
            
            ";
    

    }

    // GET REGION NAME
    function getRegionName($conn, $sRegionId){
        $sql = "SELECT regDesc FROM tbl_regions WHERE id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$sRegionId]);	
        $row = $statement->fetch();
        $sRegionName = $row['regDesc'];

        return  $sRegionName;
    }

    // GET PROVINCE NAME
    function getProvinceName($conn, $sProvId){
        $sql = "SELECT provDesc FROM tbl_provinces WHERE id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$sProvId]);	
        $row = $statement->fetch();
        $sProvName = $row['provDesc'];
        $sProvName = ucwords(strtolower($sProvName));

        return  $sProvName;
    }

    // GET CITY NAME 
    function getCityName($conn, $sCityId){
        $sql = "SELECT citymunDesc FROM tbl_cities WHERE id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$sCityId]);	
        $row = $statement->fetch();
        $sCityName = $row['citymunDesc'];
        $sCityName = ucwords(strtolower($sCityName));

        return $sCityName;
    }

    // GET BRGY NAME
    function getBrgyName($conn, $sBrgyId){
        $sql = "SELECT brgyDesc FROM tbl_barangays WHERE id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$sBrgyId]);	
        $row = $statement->fetch();
        $sBrgyName = $row['brgyDesc'];
        $sBrgyName = ucwords(strtolower($sBrgyName));

        return $sBrgyName;
    }

    //GET MODE OF PAYMENT
    function getModeOfPayment($conn, $paymentModeId){
        $sql = "SELECT `name` FROM tbl_payment_modes WHERE id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$paymentModeId]);	
        $row = $statement->fetch();
        $paymentModeName = $row['name'];

        if($paymentModeName == 'COD') {
            $paymentModeName = 'Cash On Delivery (COD)';
        }

        return $paymentModeName;
    }

    function getModeOfPaymentShort($conn, $paymentModeId){
        $sql = "SELECT `name` FROM tbl_payment_modes WHERE id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$paymentModeId]);	
        $row = $statement->fetch();
        $paymentModeName = $row['name'];

        return $paymentModeName;
    }


    //GET STATUS OF ORDER
    function displayOrderStatus($conn,$statusId) {
        $sql = "SELECT * FROM tbl_status WHERE id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$statusId]);	
        $row = $statement->fetch();
        $status = $row['name'];
        $status = ucfirst($status);
        
        // var_dump($status);die();
        return $status;
    }

    // CHANGE WORD INSIDE THE PRODUCT RATING
    function changeWordInsideProductRatingButton($conn,$productId){

        $userId = $_SESSION['id'];
        $sql = " SELECT r.product_id, r.user_id, r.rating_is_final, ri.is_final 
        FROM tbl_ratings r JOIN tbl_rating_images ri 
        ON ri.rating_id=r.id WHERE r.product_id=? 
        AND r.user_id=? ";
        $statement = $conn->prepare($sql);
        $statement->execute([$productId, $userId]);
        $count = $statement->rowCount();

        if($count) {
        $row = $statement->fetch();
        $finalRatingScore = $row['rating_is_final'];
        $isFinal = $row['is_final']; // DONE WHEN SUBMIT BUTTON IS CLICKED. ALL DATA INSIDE IS CONSIDERED FINAL WHETHER FILLED OUT OR NOT.

            if($finalRatingScore == 0 && $finalImages == 0){
                echo "<small class='text-gray font-weight-light'>REVIEW PRODUCT</small>";
            }else {
                echo "<small class='text-gray font-weight-light'>REVIEWED</small>";
            }

        } else {
            echo "<small class='text-gray font-weight-light'>REVIEW PRODUCT</small>";
        }
    }

    function getUser($conn, $userId) {
        $sql = "SELECT * FROM tbl_users WHERE id=?";
        $statement = $conn->prepare($sql);
        $statement->execute([$userId]);
        if($statement->rowCount()) {
            $row = $statement->fetch();
            return $row;
        }

        throw new \Exception("No user fetched");
    }

    function getCurrentFile() {
        return basename($_SERVER['PHP_SELF']);
    }

    function isCurrentPage($pageName) {
        if(strpos(getCurrentFile(), $pageName) !== false) {
            return true;
        }
    }

    // GET NAME FROM SHIPPING ADDRESS ID
    function getNameFromShippingAddressId($conn,$shippingAddressId){
        $sql = "SELECT * FROM tbl_addresses WHERE id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$shippingAddressId]);	
        $row = $statement->fetch();
        $recepient = $row['name'];
        $recepient = ucwords(strtolower($recepient));
       
        return $recepient;
    }

    // GET CATEGORY NAME
    function getCategoryRow($conn,$productSubCategoryId){
        $sql = "SELECT * FROM tbl_categories WHERE id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$productSubCategoryId]);	
        $row = $statement->fetch();
   
        return $row;
    }

    // GET PARENT CATEGORY
    function getCategoryName($conn,$categoryId){
        $sql = "SELECT * FROM tbl_categories WHERE id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$categoryId]);	
        $row = $statement->fetch();
        $categoryName = $row['name'];
   
        return $categoryName;
    }

    

    // GET BRAND NAME
    function getBrandName($conn,$brandId){
        $sql = "SELECT * FROM tbl_brands WHERE id = ?";
        $statement = $conn->prepare($sql);
        $statement->execute([$brandId]);	
        $row = $statement->fetch();
        $brandName = $row['brand_name'];
   
        return $brandName;
    }

    // CONVERT TO DECIMAL
    function convertToDecimal($number){
       echo number_format((float)$number, 2, '.', ',');  
    }

    // GET LAST LOGIN
    function getLastLogin($conn, $userId){
        $sql3 = "SELECT last_login FROM tbl_users WHERE id = ?";
        $statement3 = $conn->prepare($sql3);
        $statement3->execute([$userId]);	
        $row3 = $statement3->fetch();
        $lastLogin = $row3['last_login'];
        $datetime1 = new DateTime($lastLogin);
        $datetime2 = new DateTime();
        $interval = $datetime1->diff($datetime2);
        $ago = "";

        
        if($interval->format('%w') != 0) {
            $ago = $interval->format('Active %w weeks ago');
        } else {
            if($interval->format('%d') != 0) {
                $ago = $interval->format('Active %d days ago ');
            } else {
                if($interval->format('%h') != 0) {
                    $ago = $interval->format('Active %h hrs ago');
                } elseif($interval->format('%i') != 0) {
                    $ago = $interval->format('Active %i minutes ago');
                } else {
                    $ago = "
                    <small>
                        <i class='fas fa-circle text-success'>&nbsp;</i>
                    </small>
                    Active Now
                    ";
                }
            }
            
        }

        echo $ago;
    }

//SHOW PRIMARY PRODUCT IMAGE
function showPrimaryProductImage($conn,$productId){
    $sql = "SELECT * FROM tbl_product_images WHERE id = ? AND is_primary = 1";
    $statement = $conn->prepare($sql);
    $statement->execute([$productId]);	
    $row = $statement->fetch();
    $url = $row['url'];

    return $url;
}
