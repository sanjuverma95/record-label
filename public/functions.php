<?php

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function check_name(){
  global $errors;

  if (empty($_POST["name"])) {
      $errors['nameerr'] = "Name is required";
    } else {
      $name = test_input($_POST["name"]);
      // check if name only contains letters and whitespace
      if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
        $errors['nameerr']= "Only letters and white space allowed";
      }
    }
    return $name;
}


function check_email(){
  global $errors;
  if (empty($_POST["email"])) {
       $errors['emailerr'] = "Email is required";
   } else {
       $email = test_input($_POST["email"]);
      // check if e-mail address is well-formed
      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $errors['emailerr'] = "Invalid email format";
      }
      return $email;
    }
    return '';
    
}

function check_password(){
  global $errors;

  if (empty($_POST["password"])) {
      $errors['passworderr'] = "Password is required";
    } else {
      $password = test_input($_POST["password"]);
      if (strlen($_POST["password"])<8) {
          $errors['passworderr'] = "Invalid password.Minimum 8 characters required";
      }
      $password=password_hash($password, PASSWORD_BCRYPT);
      return $password;
    }

    return '';
}


function check_age(){
  global $errors;

  if (!is_numeric($_POST["age"])) {
        $errors['ageerr'] = "Invalid age";
        $age = NULL;
    }else {
      $age = test_input($_POST["age"]);
    }
    return $age;
}

function redirect_to($page){
  header("Location: $page");
  exit;
}

function logged_in(){
  return isset($_SESSION['user_id']);
}

function confirm_logged_in(){
  if(!logged_in())
    redirect_to('login.php');
}

function check_year(){
  global $errors;

  if (!is_numeric($_POST["year"])) {
        $errors['yearerr'] = "Invalid Year";
        $year = NULL;
    }else {
      $year = test_input($_POST["year"]);
    }
    return $year;
}

function check_title(){
  global $errors;
  $title = "";
  if (empty($_POST["title"])) {
      $errors['titleerr'] = "Title is required";
  } else {
      $title = test_input($_POST["title"]);
      // check if name only contains letters and whitespace
      if (!preg_match("/^[a-zA-Z ]*$/",$title)) {
        $errors['titleerr']= "Only letters and white space allowed";
      }
    }
    return $title;
}

function check_title_song(){
  global $errors;
  $title = "";
  if (empty($_POST["title"])) {
      $errors['titleerr'] = "Title is required";
  } else {
      $title = test_input($_POST["title"]);
      // check if name only contains letters and whitespace
      if (!preg_match("/^[a-zA-Z0-9 ]*$/",$title)) {
        $errors['titleerr']= "Only letters and white space allowed";
      }
    }
    return $title;
}



function delTree($dir) {
   $files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file) {
      (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
  }
?>