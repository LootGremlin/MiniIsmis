<?php
  session_start();
  include("ismis_handler.php");

  if(!isset($_SESSION['id'])){
    header('Location: login.php?error=1');
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Admin Portal</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=Playfair+Display|Spartan|Spectral&display=swap" rel="stylesheet">
  </head>
  <body>
    <div class="headerNav">
    <p style="float:left; margin: 20px 20px 10px 20px; font-family: 'Spectral', serif; font-size: 25px;">Mini ISMIS</p>
    <div class="dropdown">
      <button class="dropbtn">Admin's Portal</button>
      <div class="dropdown-content">
        <a href="login.php?stat=1">Log out</a>
      </div>
    </div>
    </div>
    <form class="navbarstyle" method="GET">
      <button class="tablink" name="section" value="Create Subject">Create or Assign</button>
      <button class="tablink" name="section" value="Subjects List">Offered Subjects</button>
      <button class="tablink" name="section" value="Students per Subject">Students enrolled</button>
      <button class="tablink" name="section" value="Enroll Student">Enroll Student</button>
    </form>

    <?php if(isset($_GET['section'])): ?>
      <?php $string=$_GET['section']; ?>
      <!---This section is for Creating or Assigning subjects--->
      <?php if((strcmp($string, "Create Subject"))==0): ?>
        <?php if(!isset($_GET['subj_id'])): ?>
        <p class="headingBig">Create or Assign</p>
        <p style="margin-bottom: 90px; text-align:center;">Choose to create a new subject or assign a teacher to an existing one</p>
        <p class="sectionSubject" style="text-align: center;">Edit Faculty Assignment</p>
        <div class="centerItem">
          <table>
            <tr>
              <th>Course Code</th>
              <th>Subject Name</th>
              <th>Schedule</th>
              <th>Faculty</th>
              <th>Remove Faculty</th>
              <th>Replace Faculty</th>
            </tr>
            <?php $subjects=mysqli_query($con, "select * from subjects")?>
            <?php if($subjects): ?>
              <?php if((mysqli_num_rows($subjects))>0): ?>
                <?php while($row=mysqli_fetch_array($subjects)): ?>
                  <tr>
                    <td><?php echo $row['SubjCode']; ?></td>
                    <td><?php echo $row['SubjectName']; ?></td>
                    <td><?php echo $row['Day'] . " " . $row['StartingTime'] . " - " . $row['EndTime']; ?></td>
                    <?php if($row['TeacherId']==0): ?>
                    <td>---</td>
                    <?php else: ?>
                    <?php
                      $specId=$row['TeacherId'];
                      $teacher=mysqli_query($con, "select FirstName, LastName from accounts where Id='$specId'");
                      if($teacher):
                        while($teachRow=mysqli_fetch_array($teacher)):
                    ?>
                    <td><?php echo $teachRow['FirstName'] . " " . $teachRow['LastName']; ?></td>
                        <?php endwhile; ?>
                      <?php endif; ?>
                    <?php endif; ?>
                    <?php if($row['TeacherId']!=0): ?>
                      <td><a style="color:white;" href="delete.php?subj_id=<?php echo $row['SubjectId'] . "&teacher_id=" . $row['TeacherId']; ?>">Remove</a></td>
                      <td><a style="color:white;" href="adminIndex.php?section=Create+Subject&subj_id=<?php echo $row['SubjectId'] . "&teacher_id=" . $specId; ?>">Replace</a></td>
                    <?php endif; ?>
                  </tr>
                <?php endwhile; ?>
              <?php endif; ?>
            <?php endif; ?>
          </table>
        </div>
        <div class="row">
          <div class="column">
            <p class="sectionSubject" style="text-align: center;">Create Subject</p>
            <div class="cardInsideForm">
              <form method="POST">
                <label>Subject Name</label><br>
                <input type="text" name="subject" required><br><br>
                <label>Starting Time</label><br>
                <input type="text" name="startTime" required><br><br>
                <label>End Time</label><br>
                <input type="text" name="endTime" required><br><br>
                <label>Day</label><br>
                <input type="text" name="day" required><br><br>
                <label>Max No.of Students</label><br>
                <input type="text" name="max" required><br><br>
                <label>Course Code</label><br>
                <input type="text" name="code" required><br><br>
                <input type="submit" class="buttonstyle" name="submitForm" value="Create" style="text-align:center;">
              </form>
            </div>
          </div>
          <div class="column">
            <p class="sectionSubject" style="text-align: center;">Assign Teacher</p>
            <?php if(isset($_GET['error'])): ?>
              <p style="font-family: 'Spartan';text-align: center; color: red;">Conflict's with teacher's schedule</p>
            <?php endif; ?>
            <div class="cardInsideForm" style="height: 396px;">
            <form method="POST" style="margin-top: 90px;">
              <label>Subject</label><br>
              <select name="subject" class="dropStyleLong" required>
                <?php if(isset($_GET['subj_id'])): ?>
                  <?php
                    $sId=$_GET['subj_id'];
                    $specificSubj=mysqli_query($con, "select * from subjects where SubjectId='$sId'");
                    if($specificSubj){
                      $suRow=mysqli_fetch_row($specificSubj);
                    }
                  ?>
                  <option value="<?php echo $sId; ?>" selected><?php echo $suRow[1] . " (" . $suRow[4] . " " . $suRow[2] . " - " . $suRow[3] . ")"; ?></option>
                <?php else: ?>
                <option value="">Select Subject</option>
                <?php endif; ?>
                <?php $subjectRow=mysqli_query($con, "select * from subjects"); ?>
                <?php if($subjectRow): ?>
                  <?php if((mysqli_num_rows($subjectRow))>0): ?>
                    <?php while($subjectInfo=mysqli_fetch_array($subjectRow)): ?>
                      <option value="<?php echo $subjectInfo['SubjectId']; ?>"><?php echo $subjectInfo['SubjectName'] . " (" . $subjectInfo['Day'] . " " . $subjectInfo['StartingTime'] . " - " . $subjectInfo['EndTime'] . ")"; ?></option>
                    <?php endwhile; ?>
                  <?php endif; ?>
                <?php endif; ?>
              </select><br><br><br>
                <label>Teacher</label><br>
                <select name="teacher" class="dropStyle" required>
                  <option value="">Select teacher</option>
                  <?php $teachers=mysqli_query($con, "select * from accounts where UserType='Faculty'"); ?>
                  <?php if($teachers): ?>
                    <?php if((mysqli_num_rows($teachers))>0): ?>
                      <?php while($teacherInfo=mysqli_fetch_array($teachers)): ?>
                        <option value="<?php echo $teacherInfo['Id']; ?>"><?php echo $teacherInfo['FirstName'] . " " . $teacherInfo['LastName'];?></option>
                      <?php endwhile; ?>
                    <?php endif; ?>
                  <?php endif; ?>
                </select><br><br><br>
                <input type="submit" class="buttonstyle" name="assignTeacher" value="Assign" style="text-align:center;">
              </form>
            </div>
          </div>
        </div>
      <?php else: ?>
        <p class="sectionSubject" style="text-align: center;">Assign Teacher</p>
        <div class="cardInsideForm">
        <form method="POST">
          <label>Subject</label><br>
          <select name="subject" class="dropStyleLong" required>
            <?php if(isset($_GET['subj_id'])): ?>
              <?php
                $sId=$_GET['subj_id'];
                $specificSubj=mysqli_query($con, "select * from subjects where SubjectId='$sId'");
                if($specificSubj){
                  $suRow=mysqli_fetch_row($specificSubj);
                }
              ?>
              <option value="<?php echo $sId; ?>" selected><?php echo $suRow[1] . " (" . $suRow[4] . " " . $suRow[2] . " - " . $suRow[3] . ")"; ?></option>
            <?php else: ?>
            <option value="">Select Subject</option>
            <?php endif; ?>
            <?php $subjectRow=mysqli_query($con, "select * from subjects"); ?>
            <?php if($subjectRow): ?>
              <?php if((mysqli_num_rows($subjectRow))>0): ?>
                <?php while($subjectInfo=mysqli_fetch_array($subjectRow)): ?>
                  <option value="<?php echo $subjectInfo['SubjectId']; ?>"><?php echo $subjectInfo['SubjectName'] . " (" . $subjectInfo['Day'] . " " . $subjectInfo['StartingTime'] . " - " . $subjectInfo['EndTime'] . ")"; ?></option>
                <?php endwhile; ?>
              <?php endif; ?>
            <?php endif; ?>
          </select><br><br>
            <label>Teacher</label><br>
            <select name="teacher" class="dropStyle" required>
              <option value="">Select teacher</option>
              <?php $teachers=mysqli_query($con, "select * from accounts where UserType='Faculty'"); ?>
              <?php if($teachers): ?>
                <?php if((mysqli_num_rows($teachers))>0): ?>
                  <?php while($teacherInfo=mysqli_fetch_array($teachers)): ?>
                    <option value="<?php echo $teacherInfo['Id']; ?>"><?php echo $teacherInfo['FirstName'] . " " . $teacherInfo['LastName'];?></option>
                  <?php endwhile; ?>
                <?php endif; ?>
              <?php endif; ?>
            </select><br><br>
            <input type="submit" class="buttonstyle" name="assignTeacher2" value="Assign" style="text-align:center;">
          </form>
        </div>
        <?php if(isset($_GET['error'])): ?>
          <p style="font-family: 'Spartan';text-align: center; color: red;">Conflict's with teacher's schedule</p>
        <?php endif; ?>
      <?php endif; ?>

      <!---This section is for Subjects Lists--->
      <?php elseif((strcmp($string, "Subjects List"))==0): ?>
        <p class="headingBig">Offered Courses</p>
        <div class="centerItem">
          <table>
            <tr>
              <th>Course Code</th>
              <th>Subject Name</th>
              <th>Schedule</th>
              <th>Faculty</th>
              <th>Remove Subject</th>
            </tr>
            <?php $subjects=mysqli_query($con, "select * from subjects")?>
            <?php if($subjects): ?>
              <?php if((mysqli_num_rows($subjects))>0): ?>
                <?php while($row=mysqli_fetch_array($subjects)): ?>
                  <tr>
                    <td><?php echo $row['SubjCode']; ?></td>
                    <td><?php echo $row['SubjectName']; ?></td>
                    <td><?php echo $row['Day'] . " " . $row['StartingTime'] . " - " . $row['EndTime']; ?></td>
                    <?php if($row['TeacherId']==0): ?>
                    <td>---</td>
                    <?php else: ?>
                    <?php
                      $specId=$row['TeacherId'];
                      $teacher=mysqli_query($con, "select FirstName, LastName from accounts where Id='$specId'");
                      if($teacher):
                        while($teachRow=mysqli_fetch_array($teacher)):
                    ?>
                    <td><?php echo $teachRow['FirstName'] . " " . $teachRow['LastName']; ?></td>
                        <?php endwhile; ?>
                      <?php endif; ?>
                    <?php endif; ?>
                    <td><a style="color:white;" href="delete.php?subj_id=<?php echo $row['SubjectId']; ?>">Remove</a></td>
                  </tr>
                <?php endwhile; ?>
              <?php endif; ?>
            <?php endif; ?>
          </table>
        </div>

        <!---This section is for List of Student's under subjects--->
      <?php elseif((strcmp($string, "Students per Subject"))==0): ?>
        <p class="headingBig">Students enrolled</p>
        <div class="centerItem">
          <?php $subject=mysqli_query($con, "select * from subjects");?>
          <?php if($subject): ?>
            <?php if((mysqli_num_rows($subject))>0): ?>
              <?php while($row=mysqli_fetch_array($subject)): ?>
                <p class="sectionSubject"><?php echo $row['SubjectName']; ?></p>
                <p><?php echo $row['Day'] . " " . $row['StartingTime'] . " - " . $row['EndTime']; ?></p>
                <?php
                  $subjId=$row['SubjectId'];
                  $classes=mysqli_query($con, "select * from subjattendees where SubjId='$subjId'");
                ?>
                <?php if($classes): ?>
                  <?php if(mysqli_num_rows($classes)==0):?>
                    <div class="none">
                      <p class="sectionSubject" style="text-align: center; padding: 0; color: white; margin: 0;">No student's enrolled</p>
                      <button type="button" name="enrollStudent" class="buttonstyle" onclick="window.open('adminIndex.php?section=Enroll+Student&subj_id=<?php echo $row['SubjectId'];?>', '_self')">Enroll a student</button>
                    </div>
                  <?php elseif((mysqli_num_rows($classes))>0): ?>
                    <table>
                      <tr>
                        <th>Id Number</th>
                        <th>Student Name</th>
                      </tr>
                    <?php while($classRow=mysqli_fetch_array($classes)): ?>
                      <tr>
                        <?php
                          $studId=$classRow['StudId'];
                          $student=mysqli_query($con, "select * from accounts where Id='$studId'");
                        ?>
                        <?php if($student): ?>
                        <?php $studRow=mysqli_fetch_row($student); ?>
                        <td><?php echo $studRow['0']?></td>
                        <td><?php echo $studRow['1'] . " " . $studRow['2']; ?></td>
                      </tr>
                      <?php endif; ?>
                    <?php endwhile; ?>
                    </table>
                  <?php endif; ?>
                <?php endif; ?>
              <?php endwhile; ?>
            <?php endif; ?>
          <?php endif; ?>
        </div>

      <?php elseif((strcmp($string, "Enroll Student"))==0): ?>
        <?php if(isset($_GET['stud_id'])): ?>
          <?php
            $studId=$_GET['stud_id'];
            $fetchStud=mysqli_query($con, "select * from accounts where Id='$studId'");
            $fetchSched=mysqli_query($con, "select * from subjattendees where StudId='$studId'");
          ?>
          <?php if($fetchStud): ?>
            <?php $result=mysqli_fetch_row($fetchStud); ?>
            <p class="headingBig"><?php echo $result[1] . " " . $result[2] . "'s Load"; ?></p>
            <center>
              <p style="margin-bottom: 90px;"></p>
            </center>
            <?php if($fetchSched): ?>
              <?php if(mysqli_num_rows($fetchSched)>0): ?>
                <table>
                  <tr>
                    <th>Subject Code</th>
                    <th>Subject Name</th>
                    <th>Schedule</th>
                    <th>Teacher</th>
                  </tr>
                <?php while($schedRow=mysqli_fetch_array($fetchSched)): ?>
                  <?php
                    $subj=$schedRow['SubjId'];
                    $fetchSubj=mysqli_query($con, "select * from subjects where SubjectId='$subj'");
                  ?>
                  <?php if($fetchSubj): ?>
                    <?php $subjectInfo=mysqli_fetch_row($fetchSubj); ?>
                    <?php
                      $teachId=$subjectInfo[7];
                      $fetchTeacher=mysqli_query($con, "select * from accounts where Id='$teachId'");
                    ?>
                    <?php if($fetchTeacher): ?>
                      <?php $teacherInfo=mysqli_fetch_row($fetchTeacher); ?>
                      <tr>
                        <td><?php echo $subjectInfo[6]; ?></td>
                        <td><?php echo $subjectInfo[1]; ?></td>
                        <td><?php echo $subjectInfo[4] . " " . $subjectInfo[2] . " - " . $subjectInfo[3]; ?></td>
                        <?php if(mysqli_num_rows($fetchTeacher)==0): ?>
                          <td>---</td>
                        <?php else: ?>
                          <td><?php echo $teacherInfo[1] . " " . $teacherInfo[2];?></td>
                        <?php endif;?>
                      </tr>
                    <?php endif;?>
                  <?php endif; ?>
                <?php endwhile; ?>
                </table>
              <?php endif; ?>
            <?php endif; ?>
          <?php endif; ?>
          <?php if(isset($_GET['error'])): ?>
            <p style="font-family: 'Spartan';text-align: center; color: red;">Conflict's with Student's schedule</p>
          <?php endif; ?>
          <form method="POST" style="text-align:center; margin-top: 100px; margin-bottom: 100px;">
            <label style="color: #333103;">Student Id</label><br>
            <input type="text" name="studId" value="<?php echo $_GET['stud_id']; ?>" readonly><br><br>
            <label style="color: #333103;">Subject</label><br>
            <select name="subject" class="dropStyleLong">
              <?php if(isset($_GET['subj_id'])): ?>
                <?php
                  $sId=$_GET['subj_id'];
                  $specificSubj=mysqli_query($con, "select * from subjects where SubjectId='$sId'");
                  if($specificSubj){
                    $suRow=mysqli_fetch_row($specificSubj);
                  }
                ?>
                <option value="<?php echo $sId; ?>" selected><?php echo $suRow[1] . " (" . $suRow[4] . " " . $suRow[2] . " - " . $suRow[3] . ")"; ?></option>
              <?php else: ?>
              <option selected="">Select Subject</option>
              <?php endif; ?>
              <?php $subjectRow=mysqli_query($con, "select * from subjects"); ?>
              <?php if($subjectRow): ?>
                <?php if((mysqli_num_rows($subjectRow))>0): ?>
                  <?php while($subjectInfo=mysqli_fetch_array($subjectRow)): ?>
                    <option value="<?php echo $subjectInfo['SubjectId']; ?>"><?php echo $subjectInfo['SubjectName'] . " (" . $subjectInfo['Day'] . " " . $subjectInfo['StartingTime'] . " - " . $subjectInfo['EndTime'] . ")"; ?></option>
                  <?php endwhile; ?>
                <?php endif; ?>
              <?php endif; ?>
            </select><br><br>
            <input type="submit" name="select" value="Select">
          </form>

        <?php else: ?>
          <p class="headingBig">Enroll a student</p>
          <center>
            <p style="margin-bottom: 90px;">A list of students is provided below as a guide to search for a specific Id</p>
          </center>
          <p class="sectionSubject" style="text-align: center;">Search Student Id</p>
          <div class="cardInsideForm">
            <form method="POST">
              <input type="text" name="studId" required><br><br>
              <input type="submit" class="buttonstyle" name="searchId" value="Search" style="text-align:center;">
            </form>
          </div>

          <div class="centerItem">
            <?php $students=mysqli_query($con, "select * from accounts where UserType='Student'"); ?>
            <?php if($students): ?>
              <?php if (mysqli_num_rows($students) > 0) : ?>
              <p class="headingBig">List of Students</p>
               <table>
                <tr>
                  <th>Student Id</th>
                  <th>Student Name</th>
                </tr>
              <?php while($studRow = mysqli_fetch_array($students)) :?>
                <tr>
                  <td><?php echo $studRow['Id']; ?></td>
                  <td><?php echo $studRow['FirstName'] . " " . $studRow['LastName']; ?></td>
                </tr>
              <?php endwhile;?>
              <?php endif; ?>
            <?php endif; ?>
              <?php if((mysqli_num_rows($students))==0): ?>
                  <p class="headingBig">No Students Found</p>
              <?php endif; ?>
              </table>
            </div>
        <?php endif; ?>

      <?php endif; ?>
    <?php endif; ?>

  </body>
  <?php
    if(isset($_POST['submitForm'])){
      $subjName=$_POST['subject'];
      $start=$_POST['startTime'];
      $end=$_POST['endTime'];
      $day=$_POST['day'];
      $code=$_POST['code'];
      $capacity=$_POST['max'];

      $insert_user="insert into subjects (SubjectName, StartingTime, EndTime, Day, MaxStud, SubjCode, TeacherId) values ('$subjName', '$start', '$end', '$day', '$capacity', '$code', '$teacherId')";
      $run_user = mysqli_query($con, $insert_user);

      if($run_user){
        echo "<script>alert('Successfully added')</script>";
        echo "<script>window.open('adminIndex.php?section=Create+Subject', '_self')</script>";
      }else{
        echo "<script>alert('Something Went Wrong')</script>";
      }
    }

    if(isset($_POST['assignTeacher'])){
      $subjectId=$_POST['subject'];
      $teacherId=$_POST['teacher'];
      $stat=0;

      $checkSched=mysqli_query($con, "select * from subjects where TeacherId='$teacherId'");

      if($checkSched){
        if((mysqli_num_rows($checkSched))>0){
          while($fetchTeach=mysqli_fetch_array($checkSched)){
            $retrieveSubj=mysqli_query($con, "select * from subjects where SubjectId='$subjectId'");
            if($retrieveSubj){
              $fetchSubj=mysqli_fetch_row($retrieveSubj);
              if($fetchSubj[2]==$fetchTeach['StartingTime'] && $fetchSubj[4]==$fetchTeach['Day'] && $fetchSubj[3]==$fetchTeach['EndTime']){
                $stat=1;
                break;
              }
            }
          }
        }
      }

      if($stat!=1){
        $updateRow=mysqli_query($con, "update subjects SET TeacherId='$teacherId' WHERE SubjectId='$subjectId'");

        if($updateRow){
          if(isset($_GET['subj_id']) && isset($_GET['teacher_id'])){
            header('Location: delete.php?subj_id=' . $_GET['subj_id'] . "&teacher_id=" . $_GET['teacher_id']);
          }else{
            echo "<script>alert('Successfully assigned')</script>";
            echo "<script>window.open('adminIndex.php?section=Create+Subject', '_self')</script>";
          }
        }else{
          echo "<script>alert('Something went wrong')</script>";
        }
      }else{
        echo "<script>window.open('adminIndex.php?section=Create+Subject&error=1', '_self')</script>";
      }
    }

    if(isset($_POST['assignTeacher2'])){
      $subjectId=$_POST['subject'];
      $teacherId=$_POST['teacher'];
      $stat=0;

      $checkSched=mysqli_query($con, "select * from subjects where TeacherId='$teacherId'");

      if($checkSched){
        if((mysqli_num_rows($checkSched))>0){
          while($fetchTeach=mysqli_fetch_array($checkSched)){
            $retrieveSubj=mysqli_query($con, "select * from subjects where SubjectId='$subjectId'");
            if($retrieveSubj){
              $fetchSubj=mysqli_fetch_row($retrieveSubj);
              if($fetchSubj[2]==$fetchTeach['StartingTime'] && $fetchSubj[4]==$fetchTeach['Day'] && $fetchSubj[3]==$fetchTeach['EndTime']){
                $stat=1;
                break;
              }
            }
          }
        }
      }

      if($stat!=1){
        $updateRow=mysqli_query($con, "update subjects SET TeacherId='$teacherId' WHERE SubjectId='$subjectId'");

        if($updateRow){
          if(isset($_GET['subj_id']) && isset($_GET['teacher_id'])){
            header('Location: delete.php?subjectid=' . $_GET['subj_id'] . "&teacherid=" . $_GET['teacher_id']);
          }else{
            echo "<script>alert('Successfully assigned')</script>";
            echo "<script>window.open('adminIndex.php?section=Create+Subject', '_self')</script>";
          }
        }else{
          echo "<script>alert('Something went wrong')</script>";
        }
      }else{
        echo "<script>window.open('adminIndex.php?section=Create+Subject&error=1&subj_id=1', '_self')</script>";
      }
    }

    if(isset($_POST['searchId'])){
      $student=$_POST['studId'];

      $get=mysqli_query($con, "select * from accounts where Id='$student' and UserType='Student'");
      if($get){
        if(mysqli_num_rows($get)==0){
          echo "<script>alert('Id Not Found. Please refer to the list of students')</script>";
        }else{
          if(isset($_GET['subj_id'])){
            header('Location: adminIndex.php?section=Enroll+Student&stud_id=' . $student . "&subj_id=" . $_GET['subj_id']);
          }else{
            header('Location: adminIndex.php?section=Enroll+Student&stud_id=' . $student);
          }
        }
      }
    }

    if(isset($_POST['select'])){
      $studId=$_POST['studId'];
      $subjId=$_POST['subject'];
      $stat=0;

      $getSched=mysqli_query($con, "select * from subjattendees where StudId='$studId'");


      if($getSched){

        if(mysqli_num_rows($getSched)>0){
          while($row=mysqli_fetch_array($getSched)){
            $specId=$row['SubjId'];
            $getCurrent=mysqli_query($con, "select * from subjects where SubjectId='$specId'");
            if($getCurrent){
              $currentInfo=mysqli_fetch_row($getCurrent);
              $getSubj=mysqli_query($con, "select * from subjects where SubjectId='$subjId'");
              if($getSubj){
                $getInfo=mysqli_fetch_row($getSubj);
                $getCapacity=mysqli_query($con, "select * from subjattendees where SubjId='$subjId'");
                if($getCapacity){
                  $totalNum=mysqli_num_rows($getCapacity);
                  if($getInfo[2]==$currentInfo[2] && $getInfo[4]==$currentInfo[4] && $getInfo[3]==$currentInfo[3]){
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
        $insertRow=mysqli_query($con, "insert into subjattendees (SubjId, StudId) values ('$subjId', '$studId')");

        if($insertRow){
          echo "<script>alert('Successfully assigned')</script>";
          echo "<script>window.open('adminIndex.php?section=Students+per+Subject', '_self')</script>";
        }else{
          echo "<script>alert('Something went wrong')</script>";
        }
      }else{
        header('Location: adminIndex.php?section=Enroll+Student&stud_id='. $studId ."&error=1");
      }
    }
  ?>
</html>
