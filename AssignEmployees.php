<?php 
	session_start();

	require 'connect.php';

	if(!isset($_SESSION['assign_error']))
	{
		$_SESSION['assign_error'] = false;
	}

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

	$userId = $_SESSION['UserId'];

	$query = 'SELECT ProjectId, ProjectName
				FROM projects
				WHERE Owner = '.$userId;

	$statement = $db->prepare($query);
	$statement->execute();

	$ProjectList = $statement->fetchAll();

	$query = 'SELECT UserId, FirstName, LastName
				FROM users
				WHERE lower(UserType) = lower(\'Employee\')';

	$statement = $db->prepare($query);
	$statement->execute();

	$EmployeeList = $statement->fetchAll();
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
				<a href="logout.php">Log Out</a>
			</li>
		</ul>
	</nav>

	<section id="CreateNewProject">
		<div id="CreateProjectHeading">
			<h1>Assign Employees to Project</h1>
		</div>

		<form id="employeeAssignForm" action="post_process.php" method="POST">
			<fieldset id="ProjectInfo">
				<ul id="AssignEmployees">
					<li>
						<label for="ProjectSelector">Project Name: </label>
						<select id="ProjectSelector" name="ProjectSelector">
							<?php foreach($ProjectList as $project): ?>
								<option value="<?= $project['ProjectId'] ?>"><?= $project['ProjectName'] ?></option>
							<?php endforeach; ?>
						</select>
					</li>
					<li>
						<label for="EmployeeSelector">Employee: </label>
						<select id="EmployeeSelector" name="EmployeeSelector">
							<?php foreach($EmployeeList as $employee): ?>
								<option value="<?= $employee['UserId'] ?>"><?= $employee['FirstName']." ".$employee['LastName'] ?></option>
							<?php endforeach; ?>
						</select>
					</li>
					<?php if($_SESSION['assign_error']): ?>
						<label class="errorMessage error">
							Employee is already assigned to this project!
						</label>
					<?php 
					$_SESSION['assign_error'] = false;
					endif; ?>
				</ul>

				<p class="center">
					<button type="submit" id="assignEmployee" name="command" value="AssignEmployee">Assign Employee</button>
				</p>
			</fieldset>
		</form>
	</section>

	<footer>
		<div id="copyright">Copyright &copy; 2020 &#124; Chintvan Soni</div>
	</footer>
</body>
</html>