
// import * as INPUT from "../config/outNames.js";
// import * as FILES from "../config/serverFiles.js";
// import * as STORE from "../config/storageNames.js";
// import * as DB from "../config/dbColumnNames.js";

import { createAthleteRadio, createTeamRadio, getAthleteValue, getTeamValue } from "./Results.js";
import { Categories } from "./Categories.js";
import { Disziplins } from "./Disziplins.js";
import { Years } from "./Years.js";
// import { createDisziplinSelector } from "./Disziplins.js";
// import { createYearSelector } from "./Years.js";


const categories = new Categories();
const disziplins = new Disziplins();
const years = new Years();

function onload() {
    createAthleteRadio();
    createTeamRadio();
    categories.createSelector();
    disziplins.createSelector();
    years.createSelector();
}
window.onload = onload


function fillTopNumber(selectedTopField) {
    document.getElementById("topNumber").value = selectedTopField.value;
    selectedTopField.selected = false;
}
window.fillTopNumber = fillTopNumber

function loadBestList() {
    console.log(categories.getCategoryControl);
    console.log(categories.getSelectedValues());
    console.log(disziplins.getSelectedValues());
    console.log(years.getYearControl());
    console.log(getAthleteValue());
    console.log(getTeamValue());
    console.log();

    var params = {
        years: years.getSelectedValues(),
        yearsControl: years.getYearControl(),
        categories: categories.getSelectedValues(),
        categoryControl: categories.getCategoryControl(),
        top: document.getElementById("topNumber").value,
        keepPerson: getAthleteValue(),
        keepTeam: getTeamValue(),
        disziplins: disziplins.getSelectedValues()
    };

    if (years.length == 0) {
        alert("Bitte wähle ein Jahr aus.");
    }
    if (topNumber <= 0 || topNumber > 10000) {
        alert("Bitte wähle eine Wert für die Anzahl Resultate zwischen 1 und 10000");
    }


    $.post("./bestList.php",
        params, function (html) {
            document.getElementById("bestList").innerHTML = html;
        }, "html");




}
window.loadBestList = loadBestList
