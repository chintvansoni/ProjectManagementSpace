<?php 
	session_start();

	if(!isset($_SESSION['register_error']))
	{
		$_SESSION['register_error'] = true;
	}

	if(!isset($_SESSION['UserName']))
	{
		header('location:login.php');
	}
	else if($_SESSION['UserRole'] == 'Employee')
	{
		header('location:EmployeeIndex.php');
	}else if($_SESSION['UserRole'] == 'Manager')
	{
		header('location:ManagerIndex.php');
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Project Management Space</title>
	<link rel="stylesheet" type="text/css" href="css/styles.css">
	<script src="js/newRegistration.js"></script>
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
				<a href="logout.php">Log Out</a>
			</li>
		</ul>
	</nav>

	<section id="CreateNewProject">
		<div id="CreateProjectHeading">
			<h1>New Registration</h1>
		</div>

		<form id="CreateNewUserForm" action="post_process.php" method="POST">
			<fieldset id="CreateNewUser">
				<ul>
					<li>
						<label for="PMSUserFirstName">FirstName: </label>
						<input id="PMSUserFirstName" name="PMSUserFirstName" type="text" />
						<span class="errorMessage error" id="PMSUserFirstName_error">* Required field</span>
					</li>
					<li>
						<label for="PMSUserLastName">LastName: </label>
						<input id="PMSUserLastName" name="PMSUserLastName" type="text" />
						<span class="errorMessage error" id="PMSUserLastName_error">* Required field</span>
					</li>
					<li>
						<label for="PMSUserName">UserName: </label>
						<input id="PMSUserName" name="PMSUserName" type="text" />
						<span class="errorMessage error" id="PMSUserName_error">* Required field</span>
					</li>
					<li>
						<label for="PMSPassword">Password: </label>
						<input id="PMSPassword" name="PMSPassword" type="password" />
						<span class="errorMessage error" id="PMSPassword_error">* Required field</span>
					</li>
					<li>
						<label for="PMSRePassword">Re-Password: </label>
						<input id="PMSRePassword" name="PMSRePassword" type="password" />
						<span class="errorMessage error" id="PMSRePassword_error">* Required field</span>
						<span class="errorMessage error" id="PasswordMismatch">* Passwords do not match</span>
					</li>
					<li>
						<label for="PMSUserRole">User Role: </label>
						<select id="PMSUserRole" name="PMSUserRole">
							<option value="Admin">Administrator</option>
							<option value="Manager">Manager</option>
							<option value="Employee">Employee</option>
						</select>
					</li>
					<?php if($_SESSION['register_error']): ?>
						<label class="errorMessagec errorc">
							Username alreade exists... Please Try another!!!
						</label>
					<?php 
					$_SESSION['register_error'] = false;
					endif; ?>
				</ul>

				<p class="center">
					<button type="submit" id="registerUser" name="command" value="registerUser">Register User</button>
				</p>
			</fieldset>
		</form>
	</section>

	<footer>
		<div id="copyright">Copyright &copy; 2020 &#124; Chintvan Soni</div>
	</footer>
</body>
</html>