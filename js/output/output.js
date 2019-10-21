

import { loadCategories, loadYears, createCategoryListFromStore, createOutputCategoryListFromStore } from "./CheckboxListingUtils.js"
import { getSelectedCheckboxesValues, selectAllValues, unselectAllValues, getSelectedRadioButtonObject, selectElementByValue, areAllValuesSelected } from "../input/Selection.js"
import { getValuesFromStorage } from "../input/SessionStorageHandler.js";
import { loadDisziplinList } from "./AdditionalSettings.js";

import "./outputSelection.js";
import { loadAthletes } from "../input/ListingUtils.js";

window.existingEntriesFile = './existing_entries.php';
window.bestListFile = "./bestList.php";
/**
 * Stores
 */
window.outputCategoryStore = "outputCatStore";
window.categoryStore = "catStore";
window.yearsStore = "years";
window.athleteStore = "athleteStore";

/**
 * General
 */
window.bestListFied = "bestList";

/**
 * Output Category
 */
window.catControl = "categoryFieldsControl";
window.outputCategoryCheckName = "outputCategories";
window.outputCategoryList = "outputCategoryList";
window.outputCategoryChecksName = "outputCategoryOptions";

/**
 * Category
 */
window.categoryByDbId = "categoryById";
window.categoryCheckName = "categories";
window.categoryList = "categoryList";
window.categoryChecksName = "categoryOptions";

/**
 * Years
 */
window.yearControl = "yearFieldsControl";
window.yearsCheckName = "years";
window.yearsList = "yearsList";
window.yearsChecksName = "yearsOptions";



function onload() {
    loadYears();
    loadCategories();
    loadDisziplinList();
    window.refuseLIsting = true;
    loadAthletes();
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

function fillTopNumber(selectedTopField) {
    document.getElementById("topNumber").value = selectedTopField.value;
}
window.fillTopNumber = fillTopNumber

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

    var yearIDsStore = getSelectedCheckboxesValues(window.yearsCheckName);
    var yearStore = getValuesFromStorage(window.yearsStore);
    var years = yearIDsStore.map(sId => yearStore[sId]["YEAR(competitionDate)"]);

    var e = document.getElementById("disziplins");
    var disziplin = e.options[e.selectedIndex].value;
    var disziplins = [];
    if (disziplin != "all") {
        disziplins.push(disziplin);
    }

    var keepPerson = getSelectedRadioButtonObject("athleteResults").value;
    var keepTeam = getSelectedRadioButtonObject("teamResults").value;
    var topNumber = document.getElementById("topNumber").value;

    var params = {
        years: years,
        categories: categories,
        categoryControl: categoryControl.id,
        top: topNumber,
        keepPerson: keepPerson,
        keepTeam: keepTeam,
        disziplins: disziplins
    };

    if (years.length == 0) {
        alert("Bitte wähle ein Jahr aus.");
    }
    if (topNumber <= 0 || topNumber > 10000) {
        alert("Bitte wähle eine Wert für die Anzahl Resultate zwischen 1 und 10000");
    }


    $.post(window.bestListFile,
        params, function (html) {
            document.getElementById(window.bestListFied).innerHTML = html;
            // const athletes = getValuesFromStorage(window.athleteStore);
            // for (const key in athletes) {
            //     const athleteName = athletes[key]["fullName"];
            //     const replace = athleteLink(athleteName, athletes[key]["athleteID"]);
            //     document.body.innerHTML = document.body.innerHTML.replace(new RegExp(athleteName, "g"), replace);
            // }
        }, "html");

}
window.loadBestList = loadBestList


function athleteLink(name, id) {
    var link = '<form method="post" action="athleteResults.php" class="inline">';
    link += '<input type="hidden" name="athleteName" value="' + name + '">';
    link += '  <button type="submit" name="athleteID" value="' + id + '" class="link-button">';
    return link + name + '</button >' + '</form >';
}


