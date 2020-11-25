function validateUpdateProjectDetails(e)
{
	hideErrors();

	if(updateProjectFormHasErrors())
	{
		e.preventDefault();

		return false;
	}

	return true;
}

function updateProjectFormHasErrors()
{	
	let requiredFieldsList = ["EditProjectName","EditProjectDescription"];

	return validateForm(requiredFieldsList);
}

function load()
{
	hideErrors();

	let updateProjectButton = document.getElementById('editProject');
	updateProjectButton.addEventListener("click", validateUpdateProjectDetails);
}

document.addEventListener("DOMContentLoaded", load);