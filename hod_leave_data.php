<?php
	$value=$_POST['value'];
	$roll_no=$_POST['roll'];
	$strdate=$_POST['date'];
	$endate=$_POST['edate'];
	$link = mysqli_connect('localhost', 'root', '');  
	if(!$link)
	{ 
		die('Failed to connect to server: ' . mysqli_error($link)); 
	}
	$db = mysqli_select_db($link,'file tracking'); 
	if(!$db)
	{ 
		die("Unable to select database"); 
	}

	$query="SELECT casual, medical FROM leave_student";
		$result = mysqli_query($link, $query);
		$query1="SELECT type FROM application_student WHERE roll_no='$roll_no' and strdate='$strdate'";
		$result1 = mysqli_query($link, $query1);
		$info=mysqli_fetch_array($result);
		$info1=mysqli_fetch_array($result1);
		$leave=$info1['type'];
		if($result){
			if($info1['type']=='casual')
				$sum=$info['casual'];
			if($info1['type']=='medical')
				$sum=$info['medical'];
		}
		else
			$sum=0;
		$diff = date_diff(date_create($strdate), date_create($endate));
		$diff=$diff->format("%R%a");
		$leave_left=$sum-$diff;
	if($value == 2)
	{
		$query="UPDATE application_student SET result='Rejected' WHERE roll_no='$roll_no' and strdate='$strdate'";
		$result1 = mysqli_query($link, $query);
		$query="UPDATE application_student SET status='HOD' WHERE roll_no='$roll_no' and strdate='$strdate'";
		$result = mysqli_query($link, $query);
		if($result1 and $result)
			echo'<a class="ui red label">Rejected</a>';
	}

	if($value == 1)
	{
		$query="UPDATE application_student SET status='HOD' WHERE roll_no='$roll_no' and strdate='$strdate'";
		$result1 = mysqli_query($link, $query);
		$query="UPDATE application_student SET result='Accepted' WHERE roll_no='$roll_no' and strdate='$strdate'";
		$result=mysqli_query($link, $query);
		if($result1 and $result)
			echo'<a class="ui green label">Accepted</a>';
		if($info1['type']=='casual')
				$query="UPDATE leave_student SET casual='$leave_left' WHERE roll_no='$roll_no'";
			if($info1['type']=='medical')
				$query="UPDATE leave_student SET medical='$leave_left' WHERE roll_no='$roll_no'";
		$result=mysqli_query($link, $query);

	}

	if($value == 3)
	{

		if($sum >= $diff and $diff > 0)
			echo'<a class="ui green label">Eligible</a>';
		else
			echo'<a class="ui red label">Not Eligible</a>';
	}
?>