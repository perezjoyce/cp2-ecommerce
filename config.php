<?php
session_start();
define('BASE_DIR', dirname(__FILE__));
require_once( BASE_DIR . '/vendor/autoload.php');
define('BASE_URL', 'http://mamaroo.herokuapp.com');
// define ('DEFAULT_PROFILE' , 'http://png.pngtree.com/svg/20161021/user_avatar_35720.png');
define ('DEFAULT_PROFILE' , BASE_URL."/app/assets/images/user_default_img.png" );
define ('DEFAULT_STORE' , BASE_URL."/app/assets/images/default_store_img.jpg" );
// define ('DEFAULT_PRODUCT_IMG' , 'http://via.placeholder.com/350x25');
date_default_timezone_set('Asia/Manila');

require_once BASE_DIR . '/app/controllers/functions.php';
require_once BASE_DIR . '/app/sources/pdo/src/PDO.class.php';

//set values
$host = "tk3mehkfmmrhjg0b.cbetxkdyhwsb.us-east-1.rds.amazonaws.com";
$db_username = "jian4ja40d0tnwib";
$db_password = "dlri4m7vxgpl7w0f";
$db_name = "p4877qn7awrf6o0z";

$conn = new PDO("mysql:host=$host;dbname=$db_name",$db_username,$db_password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// $host = "localhost";
// $db_username = "root";
// $db_password = "";
// $db_name = "db_demoStoreNew";

//@todo: Should be on a separate config file that is ignored by git
$stripe = [
  "secret_key"      => "sk_test_aDXjl0xtprhGg8R2qOX7sB2v",
  "publishable_key" => "pk_test_9E7pw13w0KyKbxHiDhAYGT57",
];

\Stripe\Stripe::setApiKey($stripe['secret_key']);

// use Aws\S3\S3Client;

// $client = S3Client::factory(array(
//     'profile' => '<profile in your aws credentials file>'
// ));


// WHEN IN WEB 
// session_start();

// require_once('vendor/autoload.php');

// define('BASE_URL', 'https://shoperoo.000webhostapp.com/');
// define('BASE_DIR', '/');
// // define ('DEFAULT_PROFILE' , 'https://png.pngtree.com/svg/20161021/user_avatar_35720.png');
// define ('DEFAULT_PROFILE' , BASE_URL."/app/assets/images/user_default_img.png" );
// define ('DEFAULT_PRODUCT_IMG' , 'https://via.placeholder.com/350x25');
// date_default_timezone_set('Asia/Manila');

// $host = "localhost";
// $db_username = "id8404897_db_demostorenew";
// $db_password = "";
// $db_name = "id8404897_db_demostorenew";

// //@todo: Should be on a separate config file that is ignored by git
// $stripe = [
//   "secret_key"      => "sk_test_bcP36XRzX0jZ99KxNI4aa3IL",
//   "publishable_key" => "pk_test_akuLqp7kIjCHQQJoK1oB7ol6",
// ];

// \Stripe\Stripe::setApiKey($stripe['secret_key']);