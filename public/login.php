<?php session_start();
?>
<?php require_once('functions.php');
if(logged_in()){
  redirect_to('index.php');
}
?>
<?php require_once('queryfunctions.php');?>
<?php require_once('connect_db.php');?>
<?php
$email =$password =$error ="";

if (isset($_POST['submit'])){
  $email = $_POST['email'];
  $password = $_POST['password'];
  
  $found_user = attempt_login($email, $password);

  if($found_user){
    //mark user as logged in using session
    $_SESSION['user_id']= $found_user['uid'];
    $_SESSION['user_name']= $found_user['name'];
    //redirect to home page of user
    redirect_to("index.php?success=2");
  }else{
    $error = 'Email/Password Incorrect';
  }

}
?>

<?php $activePage = "login.php"; ?>
<?php include_once('../layout/header.php');?>
<div id="form">
<form class="form-container col-md-8 col-md-offset-2" method='POST' action='login.php'>
  <br>
  <label><input class="space" type='email' name='email' value='<?php echo "$email" ?>' placeholder='Email' required></label><br>
  <label><input class="space" type='password' name='password' value='' placeholder='Password' required></label><br>
  <label class="space"><?php echo "$error";?></label><br>
  <input class="btn btn-success space" type='submit' name='submit' value='Login'>
  <br>
</form>
</div>

<style>
    .container{
        margin: 0 auto;
        display: table;
        position: absolute;
        height: 100%;
        width: 100%;
        background: none;
    }
    #form{
        display: table-cell;
        vertical-align: middle;
    }
    
    .form-container{
        background-color: rgba(26, 26, 26, 0.75);
    }
</style>
<?php include_once('../layout/footer.php');?>