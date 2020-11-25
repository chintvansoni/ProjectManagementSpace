function load()
{	
	const selectedSortOrder = document.getElementById('sortProjectList');

	var url = window.location.href;

	if(url.indexOf("projectname") != -1){
		selectedSortOrder.selectedIndex = 1;
	}else if(url.indexOf("createdon") != -1){
		selectedSortOrder.selectedIndex = 0;
	}

	selectedSortOrder.addEventListener("change", () => {
		const selectedIndex = selectedSortOrder.selectedIndex;
		const selectedSortValue = selectedSortOrder.options[selectedIndex].value;

		window.location.replace("ViewProjects.php?sort=".concat(selectedSortValue));
	});
}

document.addEventListener("DOMContentLoaded", load);