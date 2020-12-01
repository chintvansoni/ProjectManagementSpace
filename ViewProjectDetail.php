<?php 
	require 'connect.php';

	session_start();

	$userId = $_SESSION['UserId'];
	$projectID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

	$projectQuery = 'SELECT *
				FROM projects
				WHERE ProjectId = '.$projectID;

	$employeesAssignedQuery = 'SELECT userid
				FROM projectsassigned
				WHERE projectid = '.$projectID;

	$statement = $db->prepare($employeesAssignedQuery);
	$statement->execute();
	$userList = $statement->fetchAll();

	$statement = $db->prepare($projectQuery);
	$statement->execute();
	$projectDetail = $statement->fetchAll();

	$projectOwnerQuery = 'SELECT FirstName, LastName
							FROM users
							WHERE UserId = '.$projectDetail[0]['Owner'];
	
	$statement = $db->prepare($projectOwnerQuery);
	$statement->execute();
	$ownerDetail = $statement->fetchAll();

	$employeesAssigned = array();

	foreach ($userList as $user) {
		$userQuery = 'SELECT FirstName, LastName
						FROM users
						WHERE UserId = '.$user['userid'];
		
		$statement = $db->prepare($userQuery);
		$statement->execute();
		$userDetail = $statement->fetchAll();

		array_push($employeesAssigned, $userDetail[0]['FirstName']." ".$userDetail[0]['LastName']);
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Project Management Space</title>
	<link rel="stylesheet" type="text/css" href="css/styles.css">
	<?php if(strlen($projectDetail[0]['ERDiagram']) != 0): ?>
		<script src="js/modalscript.js"></script>
	<?php endif; ?>
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
		<div id="ProjectView">
			<h2 id="ProjectName">
				<?= $projectDetail[0]['ProjectName'] ?>
			</h2>

			<ul id="ProjectDetails">
				<li>
					<h4>
						Project Description
					</h4>
					<p>
						<?= $projectDetail[0]['Description'] ?>
					</p>
				</li>

				<li>
					<h4>
						Project Owner
					</h4>

					<p>
						<?= $ownerDetail[0]['FirstName']." ".$ownerDetail[0]['LastName'] ?>
					</p>
				</li>

				<li>
					<h4>
						Employees Assigned
					</h4>

					<p>
						<ul id="EmployeesAssigned">
							<?php foreach($employeesAssigned as $employee): ?>
								<li>
									<?= $employee ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</p>
				</li>

				<li>
					<h4>
						ER Diagram
					</h4>
					<p>
					<?php if(strlen($projectDetail[0]['ERDiagram']) != 0): ?>
						<img id="ProjectERDiagram" name="ProjectERDiagram" src="<?= $projectDetail[0]['ERDiagram'] ?>">
						<div id="myModal" class="modal">

						<!-- The Close Button -->
						<span class="close">&times;</span>

						<!-- Modal Content (The Image) -->
						<img class="modal-content" id="img01">
						</div>
					<?php else: ?>
						No ER Diagram Found
					<?php endif; ?>
					</p>
				</li>
				<li>
					<h4>
						Project Tasks
					</h4>

					<p>
						To be inserted
					</p>
				</li>
			</ul>
			<?php if($_SESSION['UserRole'] == "Manager"): ?>
				<p class="center">
					<a href="EditProjectDetails.php?id=<?= $projectID ?>">Edit Item</a>
					&nbsp;
					<a href="DeleteProject.php?id=<?= $projectID ?>">Delete Item</a>
				</p>
			<?php endif; ?>
		</div>
	</section>

	<footer>
		<div id="copyright">Copyright &copy; 2020 &#124; Chintvan Soni</div>
	</footer>
</body>
</html>