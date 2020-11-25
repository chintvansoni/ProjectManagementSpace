<?php 
session_start();

require 'connect.php';

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

else{
	$projectID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

	if(!isset($projectID) || strlen($projectID) == 0 || !filter_var($projectID, FILTER_VALIDATE_INT))
	{
		header("Location: ManagerIndex.php");
	}

	$folderQuery = 'SELECT ProjectName
					FROM projects
					WHERE ProjectId = '.$projectID;

	$statement = $db->prepare($folderQuery);
	$result = $statement->execute();

	$project = $statement->fetchall();

	deleteDirectory("uploads\\".$_SESSION['UserName']."\\".$project[0]['ProjectName']);

	$query = 'DELETE FROM projects
				WHERE ProjectID = '.$projectID;

	$statement = $db->prepare($query);
	$result = $statement->execute();

	if($result){
		header('location: ManagerIndex.php');
	}
	else{
		header('location: ErrorPage.php');
	}
}

function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }

    }

    return rmdir($dir);
}

?>