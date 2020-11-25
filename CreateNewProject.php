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
	<script src="js/addProjectScript.js"></script>
	<script src="js/script.js"></script>
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
				<a href="post_process.php" name="command" value="logout">Log Out</a>
			</li>
		</ul>
	</nav>

	<section id="CreateNewProject">
		<div id="CreateProjectHeading">
			<h1>Create a New Project</h1>
		</div>

		<form id="newProjectForm" action="post_process.php" method="POST" enctype="multipart/form-data">
			<fieldset id="ProjectInfo">
				<ul>
					<li>
						<label for="ProjectName">Project Name: </label>
						<input id="ProjectName" name="ProjectName" type="text" />
						<span class="errorMessage error" id="ProjectName_error">* Required field</span>
					</li>
					<li>
						<label for="ProjectDescription">Project Description: </label>
						<textarea id="ProjectDescription" name="ProjectDescription" rows="5"></textarea>
						<span class="errorMessage error" id="ProjectDescription_error">* Required field</span>
					</li>
					<li>
						<label for="erdiagramfile">ER Diagram: </label>
						<input id="erdiagramfile" name="erdiagramfile" type="file" />
					</li>
				</ul>

				<p class="center">
					<button type="submit" id="createProject" name="command" value="CreateNewProject">Create Project</button>
				</p>
			</fieldset>
		</form>
	</section>

	<footer>
		<div id="copyright">Copyright &copy; 2020 &#124; Chintvan Soni</div>
	</footer>
</body>
</html>