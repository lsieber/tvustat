
export function getSelectedRadioButtonObject(name) {
	var radioButton = document.getElementsByName(name);
	for (var i = 0; i < radioButton.length; i++) {
		if (radioButton[i].checked) {
			return radioButton[i];
		}
	}
	return null;
}

export function getSelectedRadioButton(name) {
	var object = getSelectedRadioButtonObject(name);
	if (object != null) {
		return object.value;
	}
	return null;
}

