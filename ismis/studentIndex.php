<?php
  session_start();
  include("ismis_handler.php");

  if(!isset($_SESSION['id'])){
    header('Location: login.php?error=1');
  }

  $userId=$_SESSION['id'];
  $userInfo=mysqli_query($con, "select * from accounts where Id='$userId'");
  $subjects=mysqli_query($con, "select * from subjattendees where StudId='$userId'");
?>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Student Portal</title>
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
      <button class="tablink" name="section" value="Student Load">Student Load</button>
      <button class="tablink" name="section" value="Enroll">Enroll</button>
    </form>

    <?php if(isset($_GET['section'])): ?>
    <?php $string=$_GET['section']; ?>
    <!---This section is Student's Load--->
    <?php if((strcmp($string, "Student Load"))==0): ?>
      <div class="centerItem">
        <?php if($subjects): ?>
          <?php if (mysqli_num_rows($subjects) > 0) : ?>
          <p class="headingBig">Student's load</p>
          <p style="margin-bottom: 90px;">Subject's you are currently taking</p>
           <table>
            <tr>
              <th>Subject Code</th>
              <th>Subject</th>
              <th>Time</th>
              <th>Day</th>
              <th>Teacher</th>
              <th>Remove</th>
              <th>Replace</th>
            </tr>
          <?php while($subjRow = mysqli_fetch_array($subjects)) :?>
            <?php
              $subjId=$subjRow['SubjId'];
              $subjectName=mysqli_query($con, "select * from subjects where SubjectId='$subjId'");
            ?>
            <?php if($subjectName): ?>
              <?php $subjectInfo=mysqli_fetch_row($subjectName); ?>
                <?php
                  $teacherId=$subjectInfo[7];
                  $teacher=mysqli_query($con, "select * from accounts where Id='$teacherId'");
                ?>
                <?php if($teacher): ?>
                  <?php $teacherName=mysqli_fetch_row($teacher); ?>
                    <tr>
                      <td><?php echo $subjectInfo[6]; ?></td>
                      <td><?php echo $subjectInfo[1]; ?></td>
                      <td><?php echo $subjectInfo[2] . " - " . $subjectInfo[3]; ?></td>
                      <td><?php echo $subjectInfo[4]; ?></td>
                      <td><?php echo $teacherName[1] . " " . $teacherName[2]; ?></td>
                      <td><a style="color:white;" href="delete.php?subj_id=<?php echo $subjectInfo[0] . "&user_id=" . $userId; ?>">Remove</a></td>
                      <td><a style="color:white;" href="studentIndex.php?section=Enroll&subj_id=<?php echo $subjectInfo[0] . "&user_id=" . $userId; ?>">Replace</a></td>
                    </tr>
                <?php endif; ?>
            <?php endif; ?>
          <?php endwhile;?>
          <?php endif; ?>
          <?php endif; ?>
          <?php if((mysqli_num_rows($subjects))==0): ?>
              <p class="headingBig">Not enrolled in any subject</p>
          <?php endif; ?>
          </table>
        </div>

        <!---This section is for Student's Enroll--->
      <?php elseif((strcmp($string, "Enroll"))==0): ?>
        <p class="headingBig">Available Subjects</p>
        <center>
          <p style="margin-bottom: 90px;">Available subjects that will appear below are subjects that you haven't enrolled yet or subjects that aren't full yet. Select a subject to add.</p>
        </center>
        <?php if(isset($_GET['error'])): ?>
          <p style="font-family: 'Spartan';text-align: center; color: red;">Subject selected conflicts with your schedule</p>
        <?php endif; ?>
        <form method="POST">
          <table>
            <tr>
              <th>Course Code</th>
              <th>Subject Name</th>
              <th>Schedule</th>
              <th>Faculty</th>
              <th>Num of Students</th>
              <th>Add</th>
            </tr>
            <?php $subjects=mysqli_query($con, "select * from subjects"); ?>
            <?php if($subjects): ?>
              <?php if((mysqli_num_rows($subjects))>0): ?>
                <?php while($subjectInfo=mysqli_fetch_array($subjects)): ?>
                  <?php
                    $subjId=$subjectInfo['SubjectId'];
                    $getNumStud=mysqli_query($con, "select * from subjattendees where SubjId='$subjId'");
                  ?>
                    <?php if($getNumStud):?>
                      <?php $num=mysqli_num_rows($getNumStud);?>
                      <?php $getTaken=mysqli_query($con, "select * from subjattendees where SubjId='$subjId' and StudId='$userId'"); ?>
                      <?php if($getTaken): ?>
                        <?php $result=mysqli_num_rows($getTaken); ?>
                      <?php endif; ?>
                      <?php if($num!=$subjectInfo['MaxStud'] && $result==0): ?>
                      <tr>
                        <td><?php echo $subjectInfo['SubjCode'];?></td>
                        <td><?php echo $subjectInfo['SubjectName'];?></td>
                        <td><?php echo $subjectInfo['Day'] . " " . $subjectInfo['StartingTime'] . " - " . $subjectInfo['EndTime']; ?></td>
                        <?php if($subjectInfo['TeacherId']==0): ?>
                          <td>---</td>
                        <?php else: ?>
                        <?php
                          $specId=$subjectInfo['TeacherId'];
                          $teacher=mysqli_query($con, "select FirstName, LastName from accounts where Id='$specId'");
                          if($teacher):
                            while($teachRow=mysqli_fetch_array($teacher)):
                        ?>
                          <td><?php echo $teachRow['FirstName'] . " " . $teachRow['LastName']; ?></td>
                            <?php endwhile; ?>
                          <?php endif; ?>
                        <?php endif; ?>
                        <td><?php echo $num . " / " . $subjectInfo['MaxStud']; ?></td>
                        <td><input class="checkboxStyle" type="radio" name="subject" value="<?php echo $subjectInfo['SubjectId']; ?>" required></td>
                      </tr>
                      <?php endif; ?>
                    <?php endif; ?>
                <?php endwhile; ?>
              <?php endif; ?>
            <?php endif; ?>
          </table>
        <center style="margin-top: 50px;">
        	<input style="font-size: 15px; background-color: #333103; color: white; cursor:pointer;" type="submit" value="Enroll" name="enrollSubmit" class="buttonstyle">
        </center>
        </form>
      <?php endif; ?>
      <?php endif; ?>
  </body>
  <?php
    if (isset($_POST['enrollSubmit'])){
      $subject=$_POST['subject'];
      $studentId=$_SESSION['id'];
      $stat=0;

      if(isset($_GET['subj_id'])){
        $prevIdSubj=$_GET['subj_id'];
        $getSched=mysqli_query($con, "select * from subjattendees where StudId='$studentId' and SubjId!='$prevIdSubj'");
      }else{
        $getSched=mysqli_query($con, "select * from subjattendees where StudId='$studentId'");
      }

      if($getSched){

        if(mysqli_num_rows($getSched)>0){
          while($row=mysqli_fetch_array($getSched)){
            $specId=$row['SubjId'];
            $getCurrent=mysqli_query($con, "select * from subjects where SubjectId='$specId'");
            if($getCurrent){
              $currentInfo=mysqli_fetch_row($getCurrent);
              $getSubj=mysqli_query($con, "select * from subjects where SubjectId='$subject'");
              if($getSubj){
                $getInfo=mysqli_fetch_row($getSubj);
                $getCapacity=mysqli_query($con, "select * from subjattendees where SubjId='$subject'");
                if($getCapacity){
                  $totalNum=mysqli_num_rows($getCapacity);
                  if($getInfo[2]==$currentInfo[2] && $getInfo[4]==$currentInfo[4]){
                    $stat=1;
                    break;
                  }elseif($getInfo[5]==$totalNum){
                    $stat=1;
                    break;
                  }
                }
              }
            }
          }
        }
      }

      if($stat!=1){
        $insert_subj = "insert into subjattendees (SubjId, StudId) values ('$subject', '$studentId')";

        $run_user = mysqli_query($con, $insert_subj);

        if($run_user){
          if(isset($_GET['subj_id']) && isset($_GET['user_id'])){
            header('Location: delete.php?subj_id=' . $_GET['subj_id'] . "&user_id=" . $_GET['user_id']);
          }else{
            echo "<script>alert('Successfully enrolled')</script>";
            echo "<script>window.open('studentIndex.php?section=Enroll', '_self')</script>";
          }
        }else{
          echo "<script>alert('Something went wrong')</script>";
        }
      }else{
        header('Location: studentIndex.php?section=Enroll&stud_id='. $studentId ."&error=1");
      }
    }
  ?>
</html>
