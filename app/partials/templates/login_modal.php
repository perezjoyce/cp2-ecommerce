<form 
action="../controllers/process_login.php?redirectUrl=<?= isset($_GET['redirectUrl']) 
? $_GET['redirectUrl'] 
: null ?>" 
method="POST" 
id="form_login">
    
    <div class="my-5">Registered? Login now.</div>

    <div class="form-group">
        <label>Username</label>
        <!-- change name to id -->
        <input type="text" class="form-control" id="username" name="username" autocomplete="username">
        <p class="validation text-danger"></p>
    </div>

    <div class="form-group mb-5">
        <label>Password</label>
        <input type="password" class="form-control" id="password" name="password" autocomplete="password">
        <p class="validation text-danger"></p>
    </div>

    <p id="error_message"></p>

    <!-- if input type is submit, this will automatically submit input to users.php hence change this to button, type to button and remove value SO THAT you can employ validation -->
    <!-- indicate id for button -->
    <button type="button" class="btn btn-block btn-outline-success mb-5" id="btn_login">LOGIN</button>

</form>