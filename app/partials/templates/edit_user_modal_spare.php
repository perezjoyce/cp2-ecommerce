<?php 
    // TO DISPLAY CURRENT USER DATA
    require_once '../../../config.php';
    require_once '../../sources/pdo/src/PDO.class.php';
    require_once '../../controllers/functions.php';
    require_once '../../controllers/connect.php';

    $id = $_GET['id'];
    
    $sql = "SELECT * FROM tbl_users WHERE id = ? ";
    $statement = $conn->prepare($sql);
    $statement->execute([$id]);
    $row = $statement->fetch(); 
      $id = $row['id'];
      $fname = $row['first_name'];
      $lname = $row['last_name'];
      $email = $row['email'];
      $username = $row['username'];
      $password = sha1($row['password']);

  ?>

  <form action="../controllers/process_edit_email.php" method="POST" id="form_edit_user">
    <!-- GET ID TO BE PASSED ON TO PROCESS_EDIT_USER -->
    <input type="hidden" name="id" id="id" value="<?= $id ?>">


    <div class="text-center my-5">Edit Profile</div>

    <div class="form-group">
      <label>First Name</label>
      <input type="text" class="form-control" id="fname" name="fname" value="<?= $fname ?>">
      <p class="validation text-danger"></p>
    </div>

    <div class="form-group">
      <label>Last Name</label>
      <input type="text" class="form-control" id="lname" name="lname" value="<?= $lname ?>">
      <p class="validation text-danger"></p>
    </div>

    <div class="form-group">
      <label>Email</label>
      <input type="email" class="form-control" id="email" name="email" value="<?= $email ?>">
      <p class="validation text-danger"></p>
    </div>
        
    <div class="form-group">
      <label>Username</label>
      <input type="text" class="form-control" id="username" name="username" value="<?= $username ?>">
      <p class="validation text-danger"></p>
    </div>

    <div class="form-group">
      <label>Password</label>
      <input type="password" class="form-control" id="password" name="password">
      <p class="validation text-danger"></p>
    </div>

    <p id="edit_user_error"></p>

    <div class="d-flex flex-lg-row flex-md-row flex-sm-column my-5">
      <button type="submit" class="btn btn-outline-success mr-1 flex-fill" id="btn_edit_user" value="submit">SAVE CHANGES</button>
    </div>

  
    
  </form>