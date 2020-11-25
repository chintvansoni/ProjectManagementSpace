<?php 
	require 'connect.php';

	session_start();

	if(!isset($_SESSION['UserName']))
	{
		header('location:login.php');
	}
	else if($_SESSION['UserRole'] == 'Employee')
	{
		header('location:EmployeeIndex.php');
	}
	else if($_SESSION['UserRole'] == 'Admin')
	{
		header('location:AdminIndex.php');
	}

	if(!isset($_GET['sort']) || strlen($_GET['sort']) == 0 || $_GET['sort'] == 'createdon'){
		$orderBy = 'CreatedOn DESC';
	}
	else if($_GET['sort'] == 'projectname'){
		$orderBy = 'ProjectName ASC';
	}else{
		header("location:ViewProjects.php");
	}

	$userId = $_SESSION['UserId'];

	$projectQuery = 'SELECT *
				FROM projects
				WHERE Owner = '.$userId.'
				ORDER BY '.$orderBy;

	$userQuery = 'SELECT *
				FROM users
				WHERE UserId = '.$userId;

	$statement = $db->prepare($projectQuery);
	$statement->execute();
	$projectList = $statement->fetchAll();

	$statement = $db->prepare($userQuery);
	$statement->execute();
	$userDetails = $statement->fetch();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Project Management Space</title>
	<link rel="stylesheet" type="text/css" href="css/styles.css">
	<script src="js/sortingScript.js"></script>
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

	<section>
		<div id="AllProjects">
			<div id="SearchSortOperations">
				<span id="SearchUtility">
					<input type="text" name="SearchProjectName" id="SearchProjectName">
					<input type="button" name="SearchProject" id="SearchProject" value="Search">
				</span>
				<span id="SortUtility">
					Sort by: 
					<select id="sortProjectList" name="sortProjectList">
						<option value="createdon" selected>Created On ASC</option>
						<option value="projectname">Project Name</option>
					</select>
				</span>
			</div>
		</div>
		<br>
		<div id="AllProjects">
			<?php if(count($projectList) >= 1): ?>
				<?php foreach($projectList as $project): ?>
					<div class="ProjectItem">
						<h3>
			          		<a href="ViewProjectDetail.php?id=<?= $project['ProjectId'] ?>"><?= $project['ProjectName'] ?></a>
				        </h3>
				        <p>
			          		<small>
				            	Created On: <?= date('F j, Y, g:i a', strtotime($project['CreatedOn'])) ?>
			         	 	</small>
			         	 	<small id="ProjectDetailLink">
			         	 		<a href="ViewProjectDetail.php?id=<?= $project['ProjectId'] ?>">view project</a>
			         	 	</small>
			         	 	<br>
			         	 	<small>
				        			Owner: <?= $userDetails['FirstName'].' '.$userDetails['LastName'] ?>
				        	</small>
				        </p>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</section>

	<footer>
		<div id="copyright">Copyright &copy; 2020 &#124; Chintvan Soni</div>
	</footer>
</body>
</html>