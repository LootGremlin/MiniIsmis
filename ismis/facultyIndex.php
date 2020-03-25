<?php
  session_start();
  include("ismis_handler.php");

  if(!isset($_SESSION['id'])){
    header('Location: login.php?error=1');
  }

  $userId=$_SESSION['id'];
  $userInfo=mysqli_query($con, "select * from accounts where Id='$userId'");
  $subjects=mysqli_query($con, "select * from subjects where TeacherId='$userId'");
?>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Faculty Portal</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display|Spartan|Spectral&display=swap" rel="stylesheet">
  </head>
  <body>
    <div class="headerNav">
    <p style="float:left; margin: 20px 20px 10px 20px; font-family: 'Spectral', serif; font-size: 25px;">Mini ISMIS</p>
    <?php
      if($userInfo){
        $row=mysqli_fetch_row($userInfo);
      }
    ?>
    <div class="dropdown">
      <button class="dropbtn"><?php echo $row[1] . " " . $row[2]; ?></button>
      <div class="dropdown-content">
        <a href="login.php?stat=1">Log out</a>
      </div>
    </div>
    </div>
    <form class="navbarstyle" method="GET">
      <button class="tablink" name="section" value="Faculty Load">Faculty Load</button>
      <button class="tablink" name="section" value="List">List of Students</button>
    </form>

    <?php if(isset($_GET['section'])): ?>
    <?php $string=$_GET['section']; ?>
    <!---This section is Faculty's Load--->
    <?php if((strcmp($string, "Faculty Load"))==0): ?>
      <div class="centerItem">
        <?php if($subjects): ?>
          <?php if (mysqli_num_rows($subjects) > 0) : ?>
          <p class="headingBig">Faculty's load</p>
          <p style="margin-bottom: 90px;">Subject's you are currently taking</p>
           <table>
            <tr>
              <th>Subject Code</th>
              <th>Subject</th>
              <th>Schedule</th>
            </tr>
          <?php while($subjRow = mysqli_fetch_array($subjects)) :?>
            <tr>
              <td><?php echo $subjRow['SubjCode']; ?></td>
              <td><?php echo $subjRow['SubjectName']; ?></td>
              <td><?php echo $subjRow['Day'] . " " . $subjRow['StartingTime'] . " - " . $subjRow['EndTime']; ?></td>
            </tr>
          <?php endwhile;?>
          <?php endif; ?>
          <?php endif; ?>
          <?php if((mysqli_num_rows($subjects))==0): ?>
              <p class="headingBig">Not assigned in any subject</p>
          <?php endif; ?>
          </table>
        </div>

        <!---This section is for List--->
      <?php elseif((strcmp($string, "List"))==0): ?>
        <p class="headingBig">List of Students</p>
        <p style="margin-bottom: 90px; text-align: center;">List of subjects assigned and students enrolled under those subjects</p>
        <?php if($subjects): ?>
          <?php if(mysqli_num_rows($subjects)>0):?>
            <?php while($fetchSubj=mysqli_fetch_array($subjects)): ?>
              <div style="text-align:center;">
                <p class="sectionSubject"><?php echo $fetchSubj['SubjectName']; ?></p>
                <p><?php echo $fetchSubj['Day'] . " " . $fetchSubj['StartingTime'] . " - " . $fetchSubj['EndTime']; ?></p>
              </div>
              <?php
                $subId=$fetchSubj['SubjectId'];
                $part=mysqli_query($con, "select * from subjattendees where SubjId='$subId'");
              ?>
              <?php if($part): ?>
                <?php if(mysqli_num_rows($part)==0):?>
                  <div class="none">
                    <p class="sectionSubject" style="text-align: center; padding: 0; color: white; margin: 0;">No student's enrolled</p>
                  </div>
                <?php elseif(mysqli_num_rows($part)>0): ?>
              <div class="centerItem">
                <table>
                  <tr>
                    <th>Student Id</th>
                    <th>Student Name</th>
                  </tr>
                  <?php while($rowPart=mysqli_fetch_array($part)): ?>
                    <?php
                      $studId=$rowPart['StudId'];
                      $studInfo=mysqli_query($con, "select * from accounts where Id='$studId'");
                    ?>
                    <?php if($studInfo): ?>
                      <?php $rowInfo=mysqli_fetch_row($studInfo); ?>
                      <tr>
                        <td><?php echo $rowInfo[0]; ?></td>
                        <td><?php echo $rowInfo[1] . " " . $rowInfo[2]; ?></td>
                      </tr>
                    <?php endif; ?>
                  <?php endwhile; ?>
                <?php endif; ?>
              <?php endif; ?>
                </table>
              </div>
            <?php endwhile; ?>
          <?php endif; ?>
        <?php endif; ?>

      <?php endif; ?>
      <?php endif; ?>
  </body>
</html>
