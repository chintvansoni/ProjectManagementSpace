<?php 
session_start();
if(!isset($_SESSION['login_error']))
{
	$_SESSION['login_error'] = false;
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

	<section>

		<div id="adminlogin">
			<a href="adminLogin.php">admin</a>
		</div>

		<form id="UserLoginForm" action="post_process.php" method="post">

			<h2>
				Login
			</h2>
			<p>
				<label for="PMS_Login_Username">username: </label>
				<input type="text" id="PMS_Login_Username" name="PMS_Login_Username">
			</p>
			<p>
				<label for="PMS_Login_password">password: </label>
				<input type="password" id="PMS_Login_password" name="PMS_Login_password">
			</p>

			<?php if($_SESSION['login_error']): ?>
				<p>
					<label id="error_message">
						Invalid username or password. Please try again!!!
					</label>
				</p>
			<?php 
			$_SESSION['login_error'] = false;
			endif; ?>

			<p class="center">
					<button type="submit" name="command" value="Log In">Log In</button>
			</p>	

		</form>
	</section>

	<footer>
		<div id="copyright">Copyright &copy; 2020 &#124; Chintvan Soni</div>
	</footer>
</body>
</html>