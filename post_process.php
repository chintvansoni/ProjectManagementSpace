<?php 

require 'connect.php';
include 'utility\ImageResize.php';
include 'utility\ImageResizeException.php';
use \Gumlet\ImageResize;

session_start();

$process_command = $_POST['command'];


if($process_command == 'registerUser'){
	$post_firstname = filter_input(INPUT_POST, 'PMSUserFirstName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$post_lastname = filter_input(INPUT_POST, 'PMSUserLastName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$post_username = filter_input(INPUT_POST, 'PMSUserName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$post_password = filter_input(INPUT_POST,'PMSPassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$post_usertype = $_POST['PMSUserRole'];
	
	$password = password_hash($post_password, PASSWORD_DEFAULT);

	$query = 'INSERT INTO users(FirstName, LastName, UserName, Password, UserType, Status)
			VALUES(:firstname, :lastname, :username, :password, :usertype, :status)';

	$statement = $db->prepare($query);
	$statement->bindValue(':firstname',$post_firstname);
	$statement->bindValue(':lastname',$post_lastname);
	$statement->bindValue(':username',$post_username);
	$statement->bindValue(':password',$password);
	$statement->bindValue(':usertype',$post_usertype);
	$statement->bindValue(':status', 1);
	$statement->execute();

	$result = $db->lastInsertId();

	if($result == 0){
		$_SESSION['register_error'] = true;
		header('location:NewRegistration.php');
	}
	else{
		$_SESSION['register_error'] = false;
		header("Location:AdminIndex.php");
	}
}
else if($process_command == 'Log In'){

	if(!isset($_POST['PMS_Login_Username']) || !isset($_POST['PMS_Login_password']) ||
		strlen($_POST['PMS_Login_Username']) == 0 || strlen($_POST['PMS_Login_password']) == 0 )
	{
		$_SESSION['login_error'] = true;
		header('location:login.php');
	}

	$post_username = filter_input(INPUT_POST, 'PMS_Login_Username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$post_password = filter_input(INPUT_POST,'PMS_Login_password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	$query = 'SELECT *
			FROM USERS
			WHERE UserName = \''.$post_username.'\'';

	$statement = $db->prepare($query);
	$statement->execute();

	$users= $statement->fetchAll();

	if(COUNT($users) != 1 || !password_verify($post_password, $users[0]['Password']))
	{
		$_SESSION['login_error'] = true;
		header('location:login.php');
	}else{
		$_SESSION['login_error'] = false;

		$_SESSION['UserId'] = $users[0]['UserId'];
		$_SESSION['UserName'] = $post_username;
		$userrole = $users[0]['UserType'];
		$_SESSION['UserRole'] = $userrole;

		if($userrole == "Admin")
		{
			header('location:AdminIndex.php');
		}else if($userrole == "Manager")
		{
			header('location:ManagerIndex.php');
		}else{
			header('location:EmployeeIndex.php');
		}
	}
}
else if($process_command == "CreateNewProject"){
	
	$post_projectname = filter_input(INPUT_POST, 'ProjectName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$post_projectdescription = filter_input(INPUT_POST,'ProjectDescription', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$ERDiagramImage = upload_to_server($post_projectname);

	if(!is_null($ERDiagramImage))
	{
		$ERDiagramImage = substr($ERDiagramImage, strpos($ERDiagramImage, "uploads"));
	}

	date_default_timezone_set('EST');
	$post_timestamp = date('Y-m-d H:i:s');

	$ProjectOwner = $_SESSION['UserId'];

	$query = "INSERT INTO projects (ProjectName, Description, Owner, CreatedOn, ERDiagram) 
				VALUES (:projectname, :description, :owner, :time_stamp, :ERDiagram)";

		$statement = $db->prepare($query);
		$statement->bindValue(':projectname', $post_projectname);
		$statement->bindValue(':description', $post_projectdescription);
		$statement->bindValue(':owner', $ProjectOwner);
		$statement->bindValue(':time_stamp', $post_timestamp);
		$statement->bindValue(':ERDiagram', $ERDiagramImage);

		$statement->execute();

	$ProjectId = $db->lastInsertId();

	$query = "INSERT INTO projectsassigned (ProjectId, UserId) 
				VALUES (:projectId, :userId)";

		$statement = $db->prepare($query);
		$statement->bindValue(':projectId', $ProjectId);
		$statement->bindValue(':userId', $ProjectOwner);

		$statement->execute();
	$_SESSION['a'] = $ProjectId;
	header("Location: ManagerIndex.php");
}
else if($process_command == "EditProject"){
	
	$post_projectname = filter_input(INPUT_POST, 'EditProjectName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$post_projectdescription = filter_input(INPUT_POST,'EditProjectDescription', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	date_default_timezone_set('EST');
	$post_timestamp = date('Y-m-d H:i:s');

	$projectID = filter_input(INPUT_POST, 'projectID', FILTER_SANITIZE_NUMBER_INT);

	$query = 'SELECT ProjectName
				FROM projects
				WHERE ProjectId = '.$projectID;

	$statement = $db->prepare($query);
	$statement->execute();
	$project = $statement->fetchAll();

	if(isset($_POST['RemoveERDiagram'])){
		$filepath = "uploads\\".$_SESSION['UserName']."\\".$project[0]['ProjectName'];
		deleteDirectory($filepath);
	}

	$ERDiagramImage = upload_to_server($post_projectname);

	if(!is_null($ERDiagramImage) || isset($_POST['RemoveERDiagram']))
	{	
		if(!is_null($ERDiagramImage)){
			$ERDiagramImage = substr($ERDiagramImage, strpos($ERDiagramImage, "uploads"));	
		}
		
		$query = 'UPDATE projects 
				SET ProjectName = :projectname, 
					Description = :description,
					ERDiagram = :erdiagram
				WHERE projectID = :projectID';
	}
	else{
		$query = 'UPDATE projects 
				SET ProjectName = :projectname, 
					Description = :description
				WHERE projectID = :projectID';
	}

	

	$statement = $db->prepare($query);
	$statement->bindValue(':projectname', $post_projectname);
	$statement->bindValue(':description', $post_projectdescription);
	$statement->bindValue(':projectID', $projectID);
	
	if(!is_null($ERDiagramImage) || isset($_POST['RemoveERDiagram']))
	{
		$statement->bindValue(':erdiagram', $ERDiagramImage);
	}
	
	$result = $statement->execute();

	if($result == 0){
		header('location:EditProjectDetails.php?id='.$projectID);
	}
	else{
		header("Location: ManagerIndex.php");
	}
}
else if($process_command == "AssignEmployee"){

	$projectID = filter_input(INPUT_POST, 'ProjectSelector', FILTER_SANITIZE_NUMBER_INT);
	$userID = filter_input(INPUT_POST, 'EmployeeSelector', FILTER_SANITIZE_NUMBER_INT);

	$query = 'INSERT INTO projectsassigned (ProjectId, UserId)
				VALUES(:ProjectID, :UserID)';

	$statement = $db->prepare($query);
	$statement->bindValue(':ProjectID', $projectID);
	$statement->bindValue(':UserID', $userID);
	$result = $statement->execute();

	if($result == 0){
		$_SESSION['assign_error'] = true;
		header('location:AssignEmployees.php');
	}
	else{
		$_SESSION['assign_error'] = false;
		header("Location: ManagerIndex.php");
	}
}
else if($command == "logout")
{
	session_destroy();
	header('location:login.php');
}

function file_upload_path($original_filename, $subfolder_name, $upload_folder_name = 'uploads') {
       	$upload_folder_name = $upload_folder_name."\\".$_SESSION['UserName']."\\".$subfolder_name;

       	if (!file_exists($upload_folder_name)) {
    		mkdir($upload_folder_name, 0777, true);
		}

       $current_folder = dirname(__FILE__);
       $path_segments = [$current_folder, $upload_folder_name, basename($original_filename)];
       return join(DIRECTORY_SEPARATOR, $path_segments);
    }

// Checks for valid file and mime type 
function isvalid_file_type($temporary_path, $new_path) {
    $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png'];
    $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];
    
    $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
	$file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
    
    if($file_extension_is_valid)
    {	
    	$actual_mime_type = getimagesize($temporary_path)['mime'];
    	$mime_type_is_valid = in_array($actual_mime_type, $allowed_mime_types);
    }
    
    return $file_extension_is_valid && $mime_type_is_valid;
}

function upload_to_server($ERDiagram){

	$file_path = NULL;

	$image_upload_detected = isset($_FILES['erdiagramfile']) && ($_FILES['erdiagramfile']['error'] === 0);
	
	if ($image_upload_detected) {
        $image_filename       = $_FILES['erdiagramfile']['name'];
        $temporary_image_path = $_FILES['erdiagramfile']['tmp_name'];
        $new_image_path       = file_upload_path($image_filename, $ERDiagram);

        if (isvalid_file_type($temporary_image_path, $new_image_path)) { 
            // Preparing image names
        	$image_name = pathinfo($image_filename, PATHINFO_FILENAME);
        	$image_extension = pathinfo($image_filename, PATHINFO_EXTENSION);
        	$image_directory = pathinfo($new_image_path, PATHINFO_DIRNAME).'\\';
        	$file_path = $image_directory.strtolower(str_replace(" ", "_", $ERDiagram)).'_image.'.$image_extension;

        	// Using ImageResize to convert image file dimensions and save the files
        	$image = new ImageResize($temporary_image_path);
			$image->resizeToWidth(600);
			$image->save($file_path);
        }
    }

    return $file_path;
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