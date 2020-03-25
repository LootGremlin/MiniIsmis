<?php
	include("ismis_handler.php");

	if(isset($_GET['subj_id']) && isset($_GET['user_id'])){
		$delete_subj = $_GET['subj_id'];
		$delete_user = $_GET['user_id'];
		$sql = "delete from subjattendees WHERE SubjId='$delete_subj' and StudId='$delete_user'";
		if (mysqli_query($con, $sql)) {
	    	echo "<script>alert('Record updated successfully')</script>";
	    	echo "<script>window.open('studentIndex.php?section=Student+Load','_self')</script>";
		} else {
	    	echo "Error deleting record: " . mysqli_error($con);
		}
	}elseif(isset($_GET['subj_id']) && isset($_GET['teacher_id'])){
		$delete_subj = $_GET['subj_id'];
		$delete_teacher = $_GET['teacher_id'];
		$sql = "update subjects SET TeacherId=NULL WHERE SubjectId='$delete_subj'";
		if (mysqli_query($con, $sql)) {
	    	echo "<script>alert('Record updated successfully')</script>";
	    	echo "<script>window.open('adminIndex.php?section=Create+Subject','_self')</script>";
		} else {
	    	echo "Error deleting record: " . mysqli_error($con);
		}
	}elseif(isset($_GET['subjectid']) && isset($_GET['teacherid'])){
		$delete_subj = $_GET['subj_id'];
		$updateTeacher = $_GET['teacher_id'];
		$sql = "update subjects SET TeacherId='$updateTeacher' WHERE SubjectId='$delete_subj'";
		if (mysqli_query($con, $sql)) {
	    	echo "<script>alert('Record updated successfully')</script>";
	    	echo "<script>window.open('adminIndex.php?section=Create+Subject','_self')</script>";
		} else {
	    	echo "Error deleting record: " . mysqli_error($con);
		}
	}elseif(isset($_GET['subj_id'])){
		$delete_subj = $_GET['subj_id'];
		$sql = "delete from subjects WHERE SubjectId='$delete_subj'";
		if (mysqli_query($con, $sql)) {
	    	echo "<script>alert('Record updated successfully')</script>";
	    	echo "<script>window.open('adminIndex.php?section=Subjects+List','_self')</script>";
		} else {
	    	echo "Error deleting record: " . mysqli_error($con);
		}
	}

?>
