<?php 
	session_start();

	if(!isset($_SESSION['UserName']))
	{
		header('location:login.php');
	}
	else if($_SESSION['UserRole'] == 'Employee')
	{
		header('location:EmployeeIndex.php');
	}else if($_SESSION['UserRole'] == 'Admin')
	{
		header('location:AdminIndex.php');
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Project Management Space</title>
	<link rel="stylesheet" type="text/css" href="css/styles.css">
</head>
<body>

	<header>
		<h1 id="headerSiteName">
			Project Management Space
		</h1>
	</header>

	<nav>
		<ul>
			<li><a href="ManagerIndex.php">Home</a></li>
			<li></li>
			<li>
				<label id="loggedinuser">
					<?= $_SESSION['UserName'] ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
				</label>
				<a href="logout.php" name="command" value="logout">Log Out</a>
			</li>
		</ul>
	</nav>

	<section id="managerIndexSection">
		<div id="managerIndex">
			<ul id="managerOperations">
				<li>
					<h3>Manage Projects</h3>
				</li>
				<li>
					<a href="CreateNewProject.php">Add a New Project</a>
				</li>
				<li>
					<br>
				</li>
				<li>
					<a href="#ViewProjects.php">Manage Projects</a>
				</li>
				<li>
					<br>
				</li>
				<li>
					<h3>User Settings</h3>
				</li>
				<li>
					<a href="#ResetPassword.php">Change Password</a>
				</li>
			</ul>
		</div>
	</section>

	<footer>
		<div id="copyright">Copyright &copy; 2020 &#124; Chintvan Soni</div>
	</footer>
</body>
</html>