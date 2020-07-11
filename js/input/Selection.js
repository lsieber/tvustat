/**Works for checkboxes, RadioButtons??? */
export function selectElementByValue(name, value) {
	var element = document.getElementsByName(name);
	for (var i = 0; i < element.length; i++) {
		if (element[i].value == value) {
			element[i].checked = true;
		}
	}
}

/**Works for checkboxes */
export function unselectAllValues(name) {
	var checkboxes = document.getElementsByName(name);
	for (var i = 0; i < checkboxes.length; i++) {
		checkboxes[i].checked = false;
	}
}

/**Works for checkboxes */
export function selectAllValues(name) {
	var checkboxes = document.getElementsByName(name);
	for (var i = 0; i < checkboxes.length; i++) {
		checkboxes[i].checked = true;
	}
}

/**Works for checkboxes */
export function areAllValuesSelected(name) {
	var checkboxes = document.getElementsByName(name);
	for (var i = 0; i < checkboxes.length; i++) {
		if(!checkboxes[i].checked){
			return false;
		}
	}
	return true;
}

export function getSelectedRadioButton(name) {
	var object = getSelectedRadioButtonObject(name);
	if (object != null) {
		return object.value;
	}
	return null;
}

export function getSelectedRadioButtonObject(name) {
	var radioButton = document.getElementsByName(name);

	for (var i = 0; i < radioButton.length; i++) {

		if (radioButton[i].checked) {
			return radioButton[i];
		}
	}

	return null;
}

export function getSelectedCheckboxes(name) {
	var ids = [];
	var checkboxes = document.getElementsByName(name);
	for (var i = 0; i < checkboxes.length; i++) {
		if (checkboxes[i].checked) {
			ids.push(checkboxes[i].id);
		}
	}
	return ids;
}

export function getSelectedCheckboxesValues(name) {
	var objects = [];
	var checkboxes = document.getElementsByName(name);
	var currentID = 0;
	for (var i = 0; i < checkboxes.length; i++) {
		if (checkboxes[i].checked) {
			objects[currentID] = checkboxes[i].value;
			currentID++;
		}
	}
	return objects;
}
