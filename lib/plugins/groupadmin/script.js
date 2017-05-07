function addSelected() {
	var allusers = document.getElementById("allusers");
	var grpusers = document.getElementById("groupusers");
	for (var i = allusers.options.length - 1; i >= 0; i--) {
		if (!allusers.options[i].selected)
			continue;
		var userInput = document.getElementById("users."+allusers.options[i].value);
		userInput.name = 'users[]';
		allusers.options[i].selected = false;
		grpusers.add(allusers.options[i], null);
	}
}

function addall() {
	var allusers = document.getElementById("allusers");
	var grpusers = document.getElementById("groupusers");
	for (var i = allusers.options.length - 1; i >= 0; i--) {
		var userInput = document.getElementById("users."+allusers.options[i].value);
		userInput.name = 'users[]';
		allusers.options[i].selected = false;
		grpusers.add(allusers.options[i], null);
	}
}

function removeSelected() {
	var allusers = document.getElementById("allusers");
	var grpusers = document.getElementById("groupusers");
	for (var i = grpusers.options.length - 1; i >= 0; i--) {
		if (!grpusers.options[i].selected)
			continue;
		var userInput = document.getElementById("users."+grpusers.options[i].value);
		userInput.name = null;
		grpusers.options[i].selected = false;
		allusers.add(grpusers.options[i], null);
	}
}

function removeall() {
	var allusers = document.getElementById("allusers");
	var grpusers = document.getElementById("groupusers");
	for (var i = grpusers.options.length - 1; i >= 0; i--) {
		var userInput = document.getElementById("users."+grpusers.options[i].value);
		userInput.name = null;
		grpusers.options[i].selected = false;
		allusers.add(grpusers.options[i], null);
	}
}