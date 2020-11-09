<?php 
	require 'connect.php';

	session_start();

	$userId = $_SESSION['UserId'];

	$query = 'SELECT *
				FROM projects
				WHERE Owner = {$userId}
				ORDER BY CreatedOn DESC';

	$statement = $db->prepare($query);
	$statement->execute();

	$projectList = $statement->fetchAll();
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
		<?php if(count($projectList) >= 1): ?>
			<?php foreach($projectList as $project): ?>
				<div>
					
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
	</section>

	<footer>
		<div id="copyright">Copyright &copy; 2020 &#124; Chintvan Soni</div>
	</footer>
</body>
</html>