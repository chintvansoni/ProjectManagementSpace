<?php 

require 'connect.php';

session_start();

$process_command = $_POST['command'];

if($process_command == 'Log In'){

	if(!isset($_POST['PMS_Login_Username']) || !isset($_POST['PMS_Login_password']) ||
		strlen($_POST['PMS_Login_Username']) == 0 || strlen($_POST['PMS_Login_password']) == 0 )
	{
		$_SESSION['login_error'] = true;
		header('location:login.php');
	}

	$post_username = filter_input(INPUT_POST, 'PMS_Login_Username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$post_password = filter_input(INPUT_POST,'PMS_Login_password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	$query = 'SELECT *
			FROM USERS
			WHERE UserName = \''.$post_username.'\'';

	$statement = $db->prepare($query);
	$statement->execute();

	$users= $statement->fetchAll();

	if(COUNT($users) != 1 || !password_verify($post_password, $users[0]['Password']))
	{
		$_SESSION['login_error'] = true;
		header('location:login.php');
	}else{
		$_SESSION['login_error'] = false;

		$_SESSION['UserId'] = $users[0]['UserId'];
		$_SESSION['UserName'] = $post_username;
		$userrole = $users[0]['UserType'];
		$_SESSION['UserRole'] = $userrole;

		if($userrole == "Admin")
		{
			header('location:AdminIndex.php');
		}else if($userrole == "Manager")
		{
			header('location:ManagerIndex.php');
		}else{
			header('location:EmployeeIndex.php');
		}
	}
}
else if($process_command == "CreateNewProject"){
	
	$post_projectname = filter_input(INPUT_POST, 'ProjectName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$post_projectdescription = filter_input(INPUT_POST,'ProjectDescription', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	date_default_timezone_set('EST');
	$post_timestamp = date('Y-m-d H:i:s');

	$ProjectOwner = $_SESSION['UserId'];

	$query = "INSERT INTO projects (ProjectName, Description, Owner, CreatedOn) 
				VALUES (:projectname, :description, :owner, :time_stamp)";

		$statement = $db->prepare($query);
		$statement->bindValue(':projectname', $post_projectname);
		$statement->bindValue(':description', $post_projectdescription);
		$statement->bindValue(':owner', $ProjectOwner);
		$statement->bindValue(':time_stamp', $post_timestamp);

		$statement->execute();

	$ProjectId = $db->lastInsertId();

	$query = "INSERT INTO projectsassigned (ProjectId, UserId) 
				VALUES (:projectId, :userId)";

		$statement = $db->prepare($query);
		$statement->bindValue(':projectId', $ProjectId);
		$statement->bindValue(':userId', $ProjectOwner);

		$statement->execute();
	$_SESSION['a'] = $ProjectId;
	header("Location: index.php");
}
else if($command == "logout")
{
	session_destroy();
	header('location:login.php');
}
?>