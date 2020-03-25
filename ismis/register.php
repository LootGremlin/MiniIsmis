<?php
  session_start();
  include("ismis_handler.php");
  $id = mysqli_insert_id($con);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display|Spartan&display=swap" rel="stylesheet">
  </head>
  <body>
    <div class="container">
    <p class="headingBig">Register</p>
    <div class="card">
      <form method="POST">
        <label>First Name</label><br>
        <input type="text" name="firstName" required><br><br>
        <label>Last Name</label><br>
        <input type="text" name="lastName" required><br><br>
        <label>Email</label><br>
        <input type="text" name="email" required><br><br>
        <label>Password</label><br>
        <input type="password" name="userPass" required><br><br>
        <label>Account Type</label><br>
        <select name="userType" class="dropStyle">
          <option selected="">Faculty or Student</option>
            <option value="Faculty">Faculty</option>
            <option value="Student">Student</option>
        </select><br><br>
        <input type="submit" class="buttonstyle" name="submitForm" value="Register">
      </form>
      <br>
      <br>
      <p class="textFormat">Already have an account? Click <a class="textFormat" href="login.php">here</a><p>
    </div>
    </div>
  </body>
  <?php
    if(isset($_POST['submitForm'])){
      $fName=$_POST['firstName'];
      $lName=$_POST['lastName'];
      $email=$_POST['email'];
      $role=$_POST['userType'];
      $pass=md5($_POST['userPass']);

      $insert_user="insert into accounts (FirstName, LastName, UserType, HashPass, Email) values ('$fName', '$lName', '$role', '$pass', '$email')";

      $run_user = mysqli_query($con, $insert_user);

      if($run_user){
        $id=mysqli_insert_id($con);
        if((strcmp($role, "Student"))==0){
          if(!isset($_SESSION['id'])){
            $_SESSION['id']=$id;
          }
          header('location: studentIndex.php?user_id=' . $id);
        }elseif((strcmp($role, "Faculty"))==0){
          if(!isset($_SESSION['id'])){
            $_SESSION['id']=$id;
          }
          header('location: teacherIndex.php?user_id=' . $id);
        }
      }else{
  			echo "<script>alert('Something Went Wrong')</script>";
  		}
    }
  ?>
</html>
