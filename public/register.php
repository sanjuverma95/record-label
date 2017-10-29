<?php 
session_start();
require_once('functions.php');
?>
<?php require_once('queryfunctions.php');?>
<?php

if(logged_in()){
  redirect_to('index.php');
}

$name =$email =$password =$age =$gender ="";
$errors = array('nameerr' =>'' ,'emailerr' =>'' ,'passworderr' =>'' ,'ageerr' =>'', 'gendererr' =>'');


if (isset($_POST['submit'])){
  $name = check_name();
  $email = check_email();
  $password = check_password();
  $age = check_age();
  $gender = $_POST['gender'];//no check required radio button
    
  $errpresent = 0;
  foreach ($errors as $key => $value) {
   	if($value!=''){
   		$errpresent = 1;
   	}
  }
?>
<?php require_once('connect_db.php');?>
<?php

	if(!$errpresent){
    insert_user();
	}
}
?>
<?php $activePage = "register.php"; ?>
<?php include_once('../layout/header.php');?>

<div id="form">
<form class="form-container col-md-8 col-md-offset-2" method='POST' action='register.php'>
    <br>
	<label>
            <input class="space" type='text' name='name' value='<?php echo "$name" ?>' placeholder='Name'><br><?php echo $errors['nameerr'];?>
    </label><br>
	<label>
        <input class="space" type='email' name='email' value='<?php echo "$email" ?>' placeholder='Email'><br><?php echo $errors['emailerr'];?>
    </label><br>
	<label>
        <input class="space" type='password' name='password' value='' placeholder='Password'><br><?php echo $errors['passworderr'];?>
    </label><br>
	<label>
        <input class="space" type='integer' name='age' value='<?php echo "$age"?>' placeholder='Age'><br>  <?php echo $errors['ageerr'];?>
    </label><br>
	<label class="space" style="padding: 5px 15px; border: 1px solid #ffffff; border-radius: 5px;" >Gender: 
        <label>
            <input type="radio" name="gender"  <?php if (isset($gender) && $gender=="MALE") echo "checked";?> value="MALE" required> 
            Male
        </label>
        
        <label>
            <input type="radio" name="gender"  <?php if (isset($gender) && $gender=="FEMALE") echo "checked";?> value="FEMALE"> 
            Female
        </label>
    </label><br>
    <input class="btn btn-success space" type="submit" name='submit' value='Submit'>
    <br>
</form>
</div>

<style>
    .container{
        margin: 0 auto;
        display: table;
        position: absolute;
        height: 80%;
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