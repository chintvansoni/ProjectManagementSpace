function validateRegistrationDetails(e)
{
	hideErrors();

	if(registerFormHasErrors() || !passwordsMatch())
	{
		e.preventDefault();

		return false;
	}
	return true;
}

function registerFormHasErrors()
{	
	let requiredFieldsList = ["PMSUserFirstName","PMSUserLastName","PMSUserName","PMSPassword","PMSRePassword"];

	return validateForm(requiredFieldsList);
}

function passwordsMatch(){
	let password = document.getElementById("PMSPassword").value;
	let repassword = document.getElementById("PMSRePassword").value;

	if(password.localeCompare(repassword) == 0){
		return true;
	}else{
		document.getElementById("PasswordMismatch").setAttribute("style", "display:inline");
		return false;
	}

}

function load()
{
	hideErrors();
	let createUserButton = document.getElementById('registerUser');
	createUserButton.addEventListener("click", validateRegistrationDetails);
}

document.addEventListener("DOMContentLoaded", load);