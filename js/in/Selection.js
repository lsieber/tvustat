
export function getSelectedRadioButtonObject(name) {
	var radioButton = document.getElementsByName(name);
	for (var i = 0; i < radioButton.length; i++) {
		if (radioButton[i].checked) {
			return radioButton[i];
		}
	}
	return null;
}

