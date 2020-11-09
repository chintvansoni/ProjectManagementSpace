function trim(str) 
{
	// Uses a regex to remove spaces from a string.
	return str.replace(/^\s+|\s+$/g,"");
}

function hideErrors()
{
	// Get an array of error elements
	let error = document.getElementsByClassName("error");

	// Loop through each element in the error array
	for ( let i = 0; i < error.length; i++ )
	{
		// Hide the error element by setting it's display style to "none"
		error[i].style.display = "none";
	}
}

function validate(e)
{
	hideErrors();

	if(formHasErrors())
	{
		e.preventDefault();

		return false;
	}

	return true;
}

function formHasErrors()
{	
	let errorFlag = false;
	let requiredFieldsList = ["ProjectName","ProjectDescription"];

	for(let i=0 ; i<requiredFieldsList.length ; i++)
	{		

		let requiredTextField = document.getElementById(requiredFieldsList[i]);

		if(!formFieldHasInput(requiredTextField))
		{
			errorFlag = raiseAndSetFocusToError(requiredTextField, requiredFieldsList[i] + "_error", errorFlag);
		}
	}

	return errorFlag;
}

function formFieldHasInput(element)
{	
	if(element.value == null || trim(element.value) == "")
	{
		return false;
	}	

	return true;
}

function raiseAndSetFocusToError(formElement, elementId, errorFlag)
{
	if(window.innerWidth < 740)
		document.getElementById(elementId).setAttribute("style", "display:block");
	else
		document.getElementById(elementId).setAttribute("style", "display:inline");
	
	if(!errorFlag){
		formElement.focus();
		formElement.select();
	}
	return true;
}

function load()
{
	hideErrors();

	let submitButton = document.getElementById('createProject');
	submitButton.addEventListener("click", validate);
}

document.addEventListener("DOMContentLoaded", load);