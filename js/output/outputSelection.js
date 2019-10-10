import { selectAllValues, unselectAllValues, getSelectedRadioButtonObject, areAllValuesSelected } from "../input/Selection.js"
import { getValuesFromStorage } from "../input/SessionStorageHandler.js";

function selectCategory() {
    var catControl = getSelectedRadioButtonObject(window.catControl);
    if (catControl.id == "all") {
        selectAllValues(window.categoryCheckName);
    }

    if (catControl.id == "men") {
        selectAllGender(1);
    }
    if (catControl.id == "women") {
        selectAllGender(2);
    }
    if (catControl.id == "multiple") {
        var allChecked = areAllValuesSelected(window.categoryCheckName);
        if (allChecked) {
            unselectAllValues(window.categoryCheckName);
        }
    }
    if (catControl.id == "single") {
        unselectAllValues(window.categoryCheckName);
        if (localStorage.lastClickedCategory != null) {
            document.getElementById(localStorage.lastClickedCategory).checked = true;
        }
    }
}
window.selectCategory = selectCategory

function selectAllGender(genderID) {
    var categories = getValuesFromStorage(window.categoryStore);
    var checkboxes = document.getElementsByName(window.categoryCheckName);
    for (var i = 0; i < checkboxes.length; i++) {
        if (categories[checkboxes[i].value].genderID == genderID) {
            checkboxes[i].checked = true;
        } else {
            checkboxes[i].checked = false;
        }
    }
}

function areAllOfGender(genderID) {
    var categories = getValuesFromStorage(window.categoryStore);
    var checkboxes = document.getElementsByName(window.categoryCheckName);
    for (var i = 0; i < checkboxes.length; i++) {
        if (categories[checkboxes[i].value].genderID == genderID) {
            if (checkboxes[i].checked == false) {
                return false;
            }
        } else {
            if (checkboxes[i].checked == true) {
                return false;
            }
        }
    }
    return true;
}


function selectCategoryControl(clickedField) {
    var catControl = getSelectedRadioButtonObject(window.catControl);
    switch (catControl.id) {
        case "all":
        case "men":
        case "women":
            document.getElementById("multiple").checked = true;
            break;

        case "multiple":
            if (areAllOfGender(1)) {
                document.getElementById("men").checked = true;
            }
            if (areAllOfGender(2)) {
                document.getElementById("women").checked = true;
            }
            if (areAllValuesSelected(window.categoryCheckName)) {
                document.getElementById("all").checked = true;
            }
            break;

        case "single":
            unselectAllValues(window.categoryCheckName);
            clickedField.checked = true;
            break;

        default:
            break;
    }
    localStorage.lastClickedCategory = clickedField.id;
}
window.selectCategoryControl = selectCategoryControl

/**
 * YEARS
 */

export function selectLargestYear(){
    var years = getValuesFromStorage(window.yearsStore);
    var checkBoxes = document.getElementsByName(window.yearsCheckName);
    var maxValue = 0;
    var maxId = null;
    for (let i = 0; i < checkBoxes.length; i++) {
        const year = years[checkBoxes[i].value]["YEAR(competitionDate)"];
        if ( year > maxValue) {
            checkBoxes[i].checked = true;
            maxValue =  year;
            if (maxId != null) {
                document.getElementById(maxId).checked = false;
            }
            maxId = checkBoxes[i].id;
        }
    }
}