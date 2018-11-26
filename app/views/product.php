<?php include "../partials/header.php";?>
<?php require_once "../controllers/connect.php";?>

 <?php 
           
  $id = $_GET['id'];
  $sql = "SELECT * FROM tbl_items WHERE id = $id";

  $result = mysqli_query($conn,$sql);
  $row = mysqli_fetch_assoc($result);

  $name = $row['name'];     
  $price = $row['price'];
  $description = $row['description'];
  $item_img = $row['img_path'];
  
?>
    <!-- PAGE CONTENT -->
    <div class="container-fluid">
      <div class="row pt-5 mt-5">
 
        <div class='container d-inline-flex'>
          <div class='d-flex flex-row mr-5'>
              <img src=' <?= $item_img ?> '>
          </div>
          
          <div class='d-flex flex-row'>
            <div class='d-flex flex-column'>
              <div class='card-title font-weight-bold'> <?= $name ?> </div>
              <div class='mb-5'>&#8369; <?= $price ?> </div>
              <div class='my-5'> <?= $description ?> </div>
              
              <div class='d-flex flex-row'>
                <button class='btn btn-outline-primary mt-3 flex-fill mr-2'>
                  <i class='fas fa-cart-plus'></i>
                  Add to Cart
                </button>

                <button class='btn btn-outline-danger mt-3 flex-fill'>
                  <i class="far fa-heart"></i>
                  Add to Wish List
                </button>
              </div>

            </div>
          </div>
        </div>

      </div>
      <!-- /.ROW -->

    </div>
    <!-- /.PAGE CONTENT -->

<!-- /FOOTER -->
<?php include_once "../partials/footer.php";?>

<!-- MODAL -->
<?php include_once "../partials/modal_container.php"; ?>