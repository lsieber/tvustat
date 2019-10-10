

import { loadCategories, loadYears } from "./CheckboxListingUtils.js"
import { getSelectedCheckboxesValues, selectAllValues, unselectAllValues, getSelectedRadioButtonObject, selectElementByValue, areAllValuesSelected } from "../input/Selection.js"
import { getValuesFromStorage } from "../input/SessionStorageHandler.js";
import "./outputSelection.js";

window.existingEntriesFile = './existing_entries.php';
window.bestListFile = "./bestList.php";
/**
 * Stores
 */
window.categoryStore = "catStore";
window.yearsStore = "years";

/**
 * General
 */
window.bestListFied = "bestList";

/**
 * Category
 */
window.catControl = "categoryFieldsControl"
window.categoryCheckName = "categories";
window.categoryList = "categoryList";
window.categoryChecksName = "categoryOptions";

/**
 * Years
 */
window.yearsCheckName = "years";
window.yearsList = "yearsList";
window.yearsChecksName = "yearsOptions";



function onload() {
    loadCategories();
    loadYears();
}
window.onload = onload


function loadBestList() {
    var categoryControl = getSelectedRadioButtonObject(window.catControl);

    var categoryIDsStore = getSelectedCheckboxesValues(window.categoryCheckName);
    var catStore = getValuesFromStorage(window.categoryStore);
    var categories = categoryIDsStore.map(sId => catStore[sId]["categoryID"]);

    var yearIDsStore = getSelectedCheckboxesValues(window.yearsCheckName);
    var yearStore = getValuesFromStorage(window.yearsStore);
    var years = yearIDsStore.map(sId => yearStore[sId]["YEAR(competitionDate)"]);


    var params = {
        years: years,
        categories: categories,
        categoryControl: categoryControl.id
    };

    if (years.length == 0 ) {
        alert("Bitte wähle ein Jahr aus.");
    }
    if (years.length == 0 ) {
        alert("Bitte wähle ein Jahr aus.");
    }
    

    $.post(window.bestListFile,
        params, function (html) {
            document.getElementById(window.bestListFied).innerHTML = html;
        }, "html");


}
window.loadBestList = loadBestList
