import * as OUT from "../config/outNames.js";
import * as DB from "../config/dbColumnNames.js";

import { createAthleteRadio, createTeamRadio, getAthleteValue, getTeamValue, createManualTimingRadio, getManualTimingValue } from "./Results.js";
import { Categories } from "./Categories.js";
import { Disziplins } from "./Disziplins.js";
import { Years } from "./Years.js";
// import { createDisziplinSelector } from "./Disziplins.js";
// import { createYearSelector } from "./Years.js";


const categories = new Categories();
const disziplins = new Disziplins();
const years = new Years();

function onload() {
    categories.createSelector();
    disziplins.createSelector();
    years.createSelector();
    createAthleteRadio();
    createTeamRadio();
    createManualTimingRadio();
}
window.onload = onload


function fillTopNumber(selectedTopField) {
    document.getElementById(OUT.topNumberField).value = selectedTopField.value;
    selectedTopField.selected = false;
}
window.fillTopNumber = fillTopNumber

function loadBestList() {

    var topNumber = document.getElementById(OUT.topNumberField).value;

    if (!(topNumber > 0 && topNumber < 10001)) {
        alert("Bitte wähle eine Wert für die Anzahl Resultate zwischen 1 und 10000");
        topNumber = 30;
        document.getElementById(OUT.topNumberField).value = topNumber;
    }

    var catCont = categories.getCategoryControl();
    var isLargeCategoryGroup = (catCont == DB.categoryControlAll || catCont == DB.categoryControlMen || catCont == DB.categoryControlWomen);
    if (isLargeCategoryGroup && years.getYearControl() == DB.yearControlAll && disziplins.getSelectedValues()[0] == DB.disziplinsAll) {
        alert("Zur Zeit können nicht gleichzeitig alle Kategorien, Jahre und Disziplinen gewählt werden. Dies übersteigt die Kapazität des Servers. Wir arbeiten an einer Lösung. Bitte wähle mindestens eine Option.")
    }

    var params = {
        years: years.getSelectedValues(),
        yearsControl: years.getYearControl(),
        categories: categories.getSelectedValues(),
        categoryControl: categories.getCategoryControl(),
        top: topNumber,
        keepPerson: getAthleteValue(),
        keepTeam: getTeamValue(),
        manualTiming: getManualTimingValue(),
        disziplins: disziplins.getSelectedValues()
    };

    $.post("./bestList.php",
        params, function (html) {
            document.getElementById("bestList").innerHTML = html;
        }, "html");

}
window.loadBestList = loadBestList

function openAthlete(athleteID){
    localStorage.athleteIDResults = athleteID;
    open("athletePage.html");
}
window.openAthlete = openAthlete;

