<?php 
	require 'connect.php';

	session_start();

	$userId = $_SESSION['UserId'];

	$projectQuery = 'SELECT *
				FROM projects
				WHERE Owner = '.$userId.'
				ORDER BY CreatedOn DESC';

	$userQuery = 'SELECT *
				FROM users
				WHERE UserId = '.$userId;

	$statement = $db->prepare($projectQuery);
	$statement->execute();

	$userStatement = $db->prepare($userQuery);
	$userStatement->execute();

	$projectList = $statement->fetchAll();
	$userDetails = $userStatement->fetch();
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

	<section>
		<div id="AllProjects">
			<?php if(count($projectList) >= 1): ?>
				<?php foreach($projectList as $project): ?>
					<div class="ProjectItem">
						<h3>
			          		<a href="ManagerProjectDetail.php?id=<?= $project['ProjectId'] ?>"><?= $project['ProjectName'] ?></a>
				        </h3>
				        <p>
			          		<small>
				            	Created On: <?= date('F j, Y, g:i a', strtotime($project['CreatedOn'])) ?>
			         	 	</small>
			         	 	<small id="ProjectDetailLink">
			         	 		<a href="ManagerProjectDetail.php?id=<?= $project['ProjectId'] ?>">view project</a>
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