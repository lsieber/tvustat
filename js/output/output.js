

import { loadCategories, loadYears, createCategoryListFromStore, createOutputCategoryListFromStore } from "./CheckboxListingUtils.js"
import { getSelectedCheckboxesValues, selectAllValues, unselectAllValues, getSelectedRadioButtonObject, selectElementByValue, areAllValuesSelected } from "../input/Selection.js"
import { getValuesFromStorage } from "../input/SessionStorageHandler.js";
import "./outputSelection.js";

window.existingEntriesFile = './existing_entries.php';
window.bestListFile = "./bestList.php";
/**
 * Stores
 */
window.outputCategoryStore = "outputCatStore";
window.categoryStore = "catStore";
window.yearsStore = "years";

/**
 * General
 */
window.bestListFied = "bestList";

/**
 * Output Category
 */
window.catControl = "categoryFieldsControl"
window.outputCategoryCheckName = "outputCategories";
window.outputCategoryList = "outputCategoryList";
window.outputCategoryChecksName = "outputCategoryOptions";

/**
 * Category
 */
window.categoryByDbId = "categoryById"
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
    loadYears();
    loadCategories();
}
window.onload = onload

function changeCatList() {
    if (window.sessionStorage.showOutputCat == "true") {
        createCategoryListFromStore();
        window.sessionStorage.showOutputCat = false;
    }
    else {
        createOutputCategoryListFromStore();
        window.sessionStorage.showOutputCat = true;
    }
}
window.changeCatList = changeCatList

function loadBestList() {
    var categoryControl = getSelectedRadioButtonObject(window.catControl);

    var categoryIDsStore = getSelectedCheckboxesValues(window.categoryCheckName);
    var categories = [];
    var i = 0;
    for (let j = 0; j < categoryIDsStore.length; j++) {
        var cats = categoryIDsStore[j].split(",");
        for (let n = 0; n < cats.length; n++) {
            categories[i] = cats[n];
            i += 1;  
        }
    }
    
    alert(JSON.stringify(categories));

    var yearIDsStore = getSelectedCheckboxesValues(window.yearsCheckName);
    var yearStore = getValuesFromStorage(window.yearsStore);
    var years = yearIDsStore.map(sId => yearStore[sId]["YEAR(competitionDate)"]);

    var disziplins = [];

    var params = {
        years: years,
        categories: categories,
        categoryControl: categoryControl.id,
        top: 1000,
        keepPerson: "ATHLETE",
        keepTeam: "YEARATHLETE",
        disziplins: disziplins
    };

    if (years.length == 0) {
        alert("Bitte wähle ein Jahr aus.");
    }
    if (years.length == 0) {
        alert("Bitte wähle ein Jahr aus.");
    }


    $.post(window.bestListFile,
        params, function (html) {
            document.getElementById(window.bestListFied).innerHTML = html;
        }, "html");


}
window.loadBestList = loadBestList
