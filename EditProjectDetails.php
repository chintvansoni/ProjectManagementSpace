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

	require 'connect.php';

	$projectID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

	$projectDetailQuery = 'SELECT *
						FROM projects
						WHERE ProjectID = '.$projectID;

	$statement = $db->prepare($projectDetailQuery);
	$statement->execute();

	$projectDetail = $statement->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Project Management Space</title>
	<link rel="stylesheet" type="text/css" href="css/styles.css">
	<script src="js/editProjectScript.js"></script>
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
			<h1>Edit Project Details</h1>
		</div>

		<form id="updateProjectForm" action="post_process.php" method="POST" enctype="multipart/form-data">
			<fieldset id="ProjectInfo">
				<ul>
					<li>
						<label for="EditProjectName">Project Name: </label>
						<input id="EditProjectName" name="EditProjectName" type="text" value="<?= $projectDetail[0]['ProjectName'] ?>"/>
						<span class="errorMessage error" id="EditProjectName_error">* Required field</span>
					</li>
					<li>
						<label for="EditProjectDescription">Project Description: </label>
						<textarea id="EditProjectDescription" name="EditProjectDescription" rows="5"><?= $projectDetail[0]['Description'] ?>
						</textarea>
						<span class="errorMessage error" id="EditProjectDescription_error">* Required field</span>
					</li>
					<li>
						<label for="erdiagramfile">Upload ER Diagram: </label>
						<input id="erdiagramfile" name="erdiagramfile" type="file" /> (Optional)
					</li>
				</ul>
					<?php if(strlen($projectDetail[0]['ERDiagram']) != 0): ?>
						<input type="checkbox" id="RemoveERDiagram" name="RemoveERDiagram">
						<label for="RemoveERDiagram">Remove Existing Diagram</label>
					<?php endif; ?>

				<p class="center">
					<input type="text" name="projectID" id="projectID" value="<?= $projectID ?>" hidden>
					<button type="submit" id="editProject" name="command" value="EditProject">Update Project</button>
				</p>
			</fieldset>
		</form>
	</section>

	<footer>
		<div id="copyright">Copyright &copy; 2020 &#124; Chintvan Soni</div>
	</footer>
</body>
</html>