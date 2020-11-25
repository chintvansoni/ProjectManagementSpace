function validateCreateProjectDetails(e)
{
	hideErrors();

	if(createProjectFormHasErrors())
	{
		e.preventDefault();

		return false;
	}

	return true;
}

function createProjectFormHasErrors()
{	
	let requiredFieldsList = ["ProjectName","ProjectDescription"];

	return validateForm(requiredFieldsList);
}

function load()
{
	hideErrors();

	let createProjectButton = document.getElementById('createProject');
	createProjectButton.addEventListener("click", validateCreateProjectDetails);
}

document.addEventListener("DOMContentLoaded", load);