import { selectAllValues, unselectAllValues, getSelectedRadioButtonObject, areAllValuesSelected } from "../input/Selection.js"
import { getValuesFromStorage } from "../input/SessionStorageHandler.js";

function selectCategory() {
    var catControl = getSelectedRadioButtonObject(window.catControl);
    if (catControl.id == "all") {
        selectAllValues(window.categoryCheckName);
    }
    if (catControl.id == "men") {
        selectAllGender(1, window.categoryCheckName);
    }
    if (catControl.id == "women") {
        selectAllGender(2, window.categoryCheckName);
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

function selectCategoryControl(clickedField) {
    var catControl = getSelectedRadioButtonObject(window.catControl);
    switch (catControl.id) {
        case "all":
        case "men":
        case "women":
            document.getElementById("multiple").checked = true;
            break;

        case "multiple":
            selectFromMultiple(window.categoryCheckName);
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

function selectFromMultiple(name) {
    if (areAllOfGender(1)) {
        document.getElementById("men").checked = true;
    }
    if (areAllOfGender(2)) {
        document.getElementById("women").checked = true;
    }
    if (areAllValuesSelected(name)) {
        document.getElementById("all").checked = true;
    }
}

function selectAllGender(genderID, name) {
    var categories = getValuesFromStorage(window.categoryByDbId);
    var checkboxes = document.getElementsByName(name);
    for (var i = 0; i < checkboxes.length; i++) {
        var ids = checkboxes[i].value.split(",");
        for (let index = 0; index < ids.length; index++) {
            if (categories[ids[index]].genderID == genderID) {
                checkboxes[i].checked = true;
            } else {
                checkboxes[i].checked = false;
            }
        }
    }
}

function areAllOfGender(genderID) {
    var categories = getValuesFromStorage(window.categoryByDbId);
    var checkboxes = document.getElementsByName(window.categoryCheckName);
    for (var i = 0; i < checkboxes.length; i++) {
        var ids = checkboxes[i].value.split(",");
        for (let index = 0; index < ids.length; index++) {
            if (categories[ids[index]].genderID == genderID) {
                if (checkboxes[i].checked == false) {
                    return false;
                }
            } else {
                if (checkboxes[i].checked == true) {
                    return false;
                }
            }
        }

    }
    return true;
}


/**
 * YEARS
 */


function selectYears() {
    var yearControl = getSelectedRadioButtonObject(window.yearControl);
    if (yearControl.id == "yall") {
        selectAllValues(window.yearsCheckName);
    }
    if (yearControl.id == "ymultiple") {
        var allChecked = areAllValuesSelected(window.yearsCheckName);
        if (allChecked) {
            unselectAllValues(window.yearsCheckName);
        }
    }
    if (yearControl.id == "ysingle") {
        unselectAllValues(window.yearsCheckName);
        if (localStorage.lastClickedYear != null) {
            document.getElementById(localStorage.lastClickedYear).checked = true;
        }
    }
}
window.selectYears = selectYears

function selectYearControl(clickedField) {
    var yearControl = getSelectedRadioButtonObject(window.yearControl);
    switch (yearControl.id) {
        case "yall":
            document.getElementById("ymultiple").checked = true;
            break;

        case "ymultiple":
            if (areAllValuesSelected(window.yearsCheckName)) {
                document.getElementById("yall").checked = true;
            }
            break;

        case "ysingle":
            unselectAllValues(window.yearsCheckName);
            clickedField.checked = true;
            break;

        default:
            break;
    }
    if (clickedField.checked == true) {
        localStorage.lastClickedYear = clickedField.id;
    }

}
window.selectYearControl = selectYearControl


export function selectLargestYear() {
    var years = getValuesFromStorage(window.yearsStore);
    var checkBoxes = document.getElementsByName(window.yearsCheckName);
    var maxValue = 0;
    var maxId = null;
    for (let i = 0; i < checkBoxes.length; i++) {
        const year = years[checkBoxes[i].value]["YEAR(competitionDate)"];
        if (year > maxValue) {
            checkBoxes[i].checked = true;
            maxValue = year;
            if (maxId != null) {
                document.getElementById(maxId).checked = false;
            }
            maxId = checkBoxes[i].id;
        }
    }
}