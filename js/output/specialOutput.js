
import { loadYears } from "./CheckboxListingUtils.js"
import { getSelectedCheckboxesValues } from "../input/Selection.js"
import { getValuesFromStorage } from "../input/SessionStorageHandler.js";

window.specialOutputFile = "specialOutput.php"
window.existingEntriesFile = "existing_entries.php"
window.outputField = "output";
window.competitionListField = "competitionList";
window.competitionListModal = "performancesForCompetition";

function onload() {
    loadYears();
}
window.onload = onload

function loadCompetitionList(competitionId) {
    $.post(window.specialOutputFile, { type: "competitionList", competitionID: competitionId }, function (data) {
        document.getElementById(window.competitionListField).innerHTML = data;
        $("#" + window.competitionListModal).modal(); // Open Modal
    }, "html");
}
window.loadCompetitionList = loadCompetitionList
 
function loadCompetitions() {
    var yearIDsStore = getSelectedCheckboxesValues(window.yearsCheckName);
    var yearStore = getValuesFromStorage(window.yearsStore);
    var years = yearIDsStore.map(sId => yearStore[sId]["YEAR(competitionDate)"]);

    $.post(window.existingEntriesFile, { type: "competitionsForYears", years: years }, function (data) {
        // document.getElementById(window.outputField).innerHTML = JSON.stringify(data);
        var rowIdentifiers = {
            "competitionID": "ID",
            "competitionName": "Name",
            "village": "Ort",
            "competitionDate": "Datum",
            "numberPerformances": "Leistungen"
        };
        tableCreate(data, rowIdentifiers);
    }, "json");
}
window.loadCompetitions = loadCompetitions

function tableCreate(array, rowIdentifiers) {
    var body = document.body,
        tbl = document.createElement('table');
    tbl.className = "table table-striped";

    //HEADERS
    // create row for table head
    var row = document.createElement("tr")
    // append it to table
    tbl.appendChild(row);
    // get kesys from first object and iterate
    Object.values(rowIdentifiers).forEach(function (v) {
        // create th
        var cell = document.createElement("th");
        // append to tr
        row.appendChild(cell);
        // update th content as key value
        cell.innerHTML = v;
    });

    // VALUES
    for (const rowId in array) {
        var row = array[rowId];
        var tr = tbl.insertRow();
        tr.onclick = function () {
            return function () {
                var id = this.cells[0].innerHTML;
                alert("id:" + id);
                loadCompetitionList(id);
            };
        }(row);

        for (const columnId in rowIdentifiers) {
            var column = row[columnId];
            var td = tr.insertCell();
            td.appendChild(document.createTextNode(column));
        }
    }
    // body.appendChild(tbl);

    document.getElementById(window.outputField).appendChild(tbl);
}


/** **********************************
 * ATHLETES
 * **********************************/ 

 window.athleteOutput = "AthleteResults";

function loadAthleteHTML(athleteID, outputFieldId) {
    $.post(window.specialOutputFile, { type: "resultsAthlete", athleteID: athleteID }, function (data) {
        document.getElementById(outputFieldId).innerHTML = data;
    }, "html");
}

function loadAthlete(athleteID) {
    
    loadAthleteHTML(athleteID, window.athleteOutput);
}
window.loadAthlete = loadAthlete

