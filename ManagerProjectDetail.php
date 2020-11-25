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

	$employeeListStatement = $db->prepare($employeesAssignedQuery);
	$employeeListStatement->execute();

	$statement = $db->prepare($projectQuery);
	$statement->execute();

	$projectDetail = $statement->fetchAll();
	$userList = $statement->fetchAll();

	print_r($userList);

	$employeesAssigned = array();

	/*foreach ($userList as $user) {
		$userQuery = 'SELECT FirstName, LastName
						FROM users
						WHERE UserId = '.$user['UserId'];

		$userStatement = $db->prepare($userQuery);
		$userStatement->execute();

		$userDetail = $userStatement->fetchAll();

		array_push($employeesAssigned, $userDetail[0]['FirstName']." ".$userDetail[0]['LastName']);
	}*/
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
			</ul>
		</div>
	</section>

	<footer>
		<div id="copyright">Copyright &copy; 2020 &#124; Chintvan Soni</div>
	</footer>
</body>
</html>