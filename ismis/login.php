<?php
  session_start();
  include("ismis_handler.php");

  if(isset($_GET['stat'])){
    session_unset();
    session_destroy();
    header('Location: login.php');
  }

  if(isset($_GET['error'])){
    echo "<script>alert('Log in first to access')</script>";
    echo "<script>window.open('login.php', '_self')</script>";
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Log-in</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display|Spartan&display=swap" rel="stylesheet">
  </head>
  <body>
    <div class="container">
    <p class="headingBig">Login to your account</p><br><br>
    <div class="card">
      <form method="POST">
        <center>
        <label style="color:white;">ID number</label><br>
        <input type="text" name="idNum" required><br><br>
        <label style="color:white;">Password</label><br>
        <input type="password" name="userPass" required><br><br>
        <input type="submit" class="buttonstyle" name="submitForm" value="Log-in">
        </center>
      </form>
      <br>
      <br>
      <p class="textFormat">New to the school? Click <a class="textFormat" href="register.php">here</a> to register<p>
    </div>
    </div>
  </body>
  <?php
    if(isset($_POST['submitForm'])){
      $userName=$_POST['idNum'];
      $pass=md5($_POST['userPass']);

      if((strcmp($userName, "Admin"))==0){
        $userName=1;
      }

      $result=mysqli_query($con, "select * from accounts where HashPass='$pass' and Id='$userName'");

      if($result){
        if(mysqli_num_rows($result)==0){
          echo "<script>alert('Password or Id is invalid')</script>";
        }else{
          $row=mysqli_fetch_row($result);
          if((strcmp($row[3], "Student"))==0){
            if(!isset($_SESSION['id'])){
              $_SESSION['id']=$row[0];
            }
            header('location: studentIndex.php?user_id=' . $row[0]);
          }elseif((strcmp($row[3], "Faculty"))==0){
            if(!isset($_SESSION['id'])){
              $_SESSION['id']=$row[0];
            }
            header('location: facultyIndex.php?user_id=' . $row[0]);
          }elseif((strcmp($row[3], "Admin"))==0){
            if(!isset($_SESSION['id'])){
              $_SESSION['id']="admin";
            }
            header('location: adminIndex.php?user_id=admin');
          }
        }
      }else{
  			echo "<script>alert('Something Went Wrong')</script>";
  		}
    }
  ?>
</html>
