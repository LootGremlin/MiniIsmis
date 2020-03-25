<?php
  $con=mysqli_connect('localhost', 'root', '', 'ismis');

  if(!$con){
    echo "<script>alert('Error connection with database')</script>";
  }

 ?>
