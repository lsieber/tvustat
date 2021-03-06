/**
 * 
 */
import { loadBasicData } from "./BasicDefinitions.js"
import { loadCompetitions, loadCategories, loadDisziplins, loadAthletes } from "./DataLoader.js"
import { InsertToDB } from "../elmt/InsertToDB.js";
import { ExistingEntries } from "../elmt/ExistingEntries.js";
import { CalculatePoints } from "../elmt/CalculatePoints.js";
import { AthleteInputForm } from "./AthleteInputForm.js";
import { addValueToArrayStorage, getValuesFromStorage } from "./SessionStorageHandler.js";
import { getSelectedRadioButtonObject } from "./Selection.js";


import * as INPUT from "../config/inputNames.js";
import * as FILES from "../config/serverFiles.js";
import * as STORE from "../config/storageNames.js";
import * as DB from "../config/dbColumnNames.js";

const athleteForm = new AthleteInputForm(INPUT.athleteFormId);
const insertToDb = new InsertToDB();
const existingEntries = new ExistingEntries();
const pointCalculator = new CalculatePoints();

// INPUT FILE
window.inputFileFieldId = "inputFile";

// OUTPUT FIELDS
window.inserteationOutput = "inserteationOutput";
window.counter = 0;
/**
 * 
 */
function onload() {
  loadBasicData(FILES.basicDefinitionFile);
  loadYear();
  loadCompetitions();
  loadCategories();
  loadDisziplins();
  // loadAthletes();
  loadSources();
  updateAthleteInput();
  document.getElementById(INPUT.detailDiv).innerHTML = '<input type="text" class="form-control" id=' + INPUT.detailInput + '><label for='+INPUT.detailInput+'>Detail</label>';
}
window.onload = onload

function loadYear() {
  const html = '<label>Year</label> <input type="number" min="1970" max="2040" class="form-control" id="' + INPUT.yearInput + '" onchange="loadCompetitions()">';
  document.getElementById(INPUT.yearDiv).innerHTML = html;
  document.getElementById(INPUT.yearInput).value = new Date().getFullYear();
}

// function loadPerformances() {
//   performanceFileReader.loadData();
// }
// window.loadPerformances = loadPerformances

// function insertPerfectMatches() {
//   performanceFileReader.insertPerfectMatches();
// }
// window.insertPerfectMatches = insertPerfectMatches


/************************************************************
 * *******************************************************
 *************************************************************/
export function insertPerformance() {
  
  var performances = document.getElementsByName(INPUT.performanceInputName);
  for (const key in performances) {
    var p = performances[key];
    if (p.id != undefined) {
      insertPerformanceField(p.id);
    }
  }
}
window.insertPerformance = insertPerformance;

export function insertPerformanceField(perfId) {
  var athleteIDStore = getSelectedRadioButtonObject(INPUT.athleteInputName).id.slice(INPUT.athletePrefix.length);
  var competitionIDStore = getSelectedRadioButtonObject(INPUT.competitionInputName).id.slice(INPUT.competitionPrefix.length);
  var disziplinIDStore = perfId.slice(INPUT.performancePrefix.length);

  var performance = document.getElementById(perfId);
  var ranking = document.getElementById(INPUT.rankingInput);
  var wind = document.getElementById(INPUT.windInput);
  var e = document.getElementById(INPUT.sourceSelect);
  var sourceID = e.options[e.selectedIndex].value;
  if (sourceID == "NULL") {
    sourceID = null;
  }
  var manualTiming = document.getElementById("manualTimingInput").checked;

  if (athleteIDStore == null || competitionIDStore == null || disziplinIDStore == null || performance.value == "") {
    // alert("Not Enough Information");
  } else {
    var athlete = getValuesFromStorage(STORE.athleteStore)[athleteIDStore];
    var athleteID = athlete[DB.athleteID];
    var competitionID = getValuesFromStorage(STORE.competitionStore)[competitionIDStore][DB.competitionID];
    var disziplin = getValuesFromStorage(STORE.disziplinStore)[disziplinIDStore];
    var disziplinID = disziplin[DB.disziplinID];
    var detail = null;
    if ( disziplin[DB.multiIds] != null && document.getElementById(INPUT.detailInput) != null && document.getElementById(INPUT.detailInput).value != "") {
      detail = document.getElementById(INPUT.detailInput).value;
    }

    var performanceValue = performance.value;

    var data = {
      athleteID: athleteID, //
      disziplinID: disziplinID, //
      competitionID: competitionID, //
      performance: performanceValue, //
      wind: wind.value, //
      placement: ranking.value, //
      sourceID: sourceID, // 
      detailDescription: detail, //
      manualTiming: manualTiming
    }

    insertPerformanceWithData(data, athlete, disziplin);

    document.getElementById(INPUT.detailInput).value = "";

  }
}
window.insertPerformance = insertPerformance;


function insertPerformanceWithData(params, athlete, disziplin) {

  params.type = "performance"; // required for the php file to distinguisch whats inserted

  if (athlete["athleteID"] != params["athleteID"] || disziplin["disziplinID"] != disziplin["disziplinID"]) {
    alert("the inSert Failed as we have not the same id for an athlete or a disziplin to check the insertatiion conditions. ")
  } else {
    var minValue = parseFloat(disziplin["minVal"]);
    var maxValue = parseFloat(disziplin["maxVal"]);

    var performanceValue = time2seconds(params.performance);
    if (performanceValue < minValue || performanceValue > maxValue) {
      alert("The Value " + performanceValue + " exteeds the limit for the disziplin " + disziplin["disziplinName"] + " which are: min=" + minValue + ", max=" + maxValue);
    } else if (disziplin["teamTypeID"] !== athlete["teamTypeID"]) {
      alert("The disziplin Team Type is " + disziplin["teamTypeID"] + " but the Athlete " + athlete["fullName"] + " has the type " + athlete["teamTypeID"])
    } else {
      insertToDb.post(params, processPerformanceData, "json");
    }
  }
}

function processPerformanceData(data) {
  var output = data.message;
  if (data.success == true) {
    output += "</br>" + data[DB.disziplinName] + " " + data[DB.athleteName] + " " + data[DB.performance];
    output += "</br><a onclick=deleteLastPerformance(" + data[DB.performanceID] + ")>" + "löschen </a>";
  }
  document.getElementById(INPUT.insertionOutput).innerHTML = output;

}

function createMultipleDetail() {
  const outcategory = getSelectedRadioButtonObject(INPUT.categoryInputName);
  const outcategoryStoreID = outcategory.id.slice(INPUT.categoryPrefix.length);

  const categoryID = getValuesFromStorage(STORE.outputCategoryStore)[outcategoryStoreID][DB.categoryIDs].split(",")[0];

  const genderID = getValuesFromStorage(STORE.categoryStore)[categoryID][DB.genderID];

  const e = document.getElementById(INPUT.pointsSchemeSelect);
  const schemeNameId = e.options[e.selectedIndex].value;

  const schemeID = getSchemeID(schemeNameId, genderID);

  const multipleIDStore = getSelectedRadioButtonObject(INPUT.disziplinInputName).id.slice(INPUT.disziplinPrefix.length);

  var detail = [];

  const performances = document.getElementsByName(INPUT.performanceInputName);
  for (const key in performances) { // each performance belongs to a disziplin which is stored in the performance field id
    const p = performances[key];
    if (p.id != undefined) {
      var disStoreID = p.id.slice(INPUT.performancePrefix.length);
      if (disStoreID != multipleIDStore) { // if the disziplin is not the multiple
        var disziplinDBID = getValuesFromStorage(STORE.disziplinStore)[disStoreID][DB.disziplinID];
        if (p.value != "") {
          detail.push(createDetailOf(disziplinDBID, schemeID, p.value));
        }
      }
    }
  }
  return detail.join("/");
}

function getSchemeID(schemeNameId, genderID) {
  // alert(schemeNameId + ",   " + genderID);
  var schemeStore = getValuesFromStorage(STORE.definitionsStore)[STORE.pointSchemesStore];
  for (const key in schemeStore) {
    if (schemeStore[key][DB.pointSchemeNameID] == schemeNameId && schemeStore[key][DB.genderID] == genderID) {
      return schemeStore[key][DB.pointSchemeID];
    }
  }
  return null;
}

function createDetailOf(disziplinDBID, schemeID, value) {
  if (value != "") {
    var p = getValuesFromStorage(STORE.definitionsStore)[STORE.pointParameterStore];
    // alert(schemeID + ", " + disziplinDBID);
    var pointParameter = p[schemeID][disziplinDBID];
    return pointParameter[DB.shortDisziplinName] + " " + value;
  }
  return "";
}



function onSelectionChange() {

  athleteForm.setActiveYear(document.getElementById(INPUT.yearInput).value);

  var dIDs = getDIDs();
  var a = getSelectedRadioButtonObject(INPUT.athleteInputName);
  var c = getSelectedRadioButtonObject(INPUT.competitionInputName);

  // var d = getSelectedRadioButtonObject(INPUT.disziplinInputName);

  if (a != null && c != null && dIDs != undefined && Object.keys(dIDs).length > 0) {
    var competitionStoreID = c.id.slice(INPUT.competitionPrefix.length);
    var compDate = getValuesFromStorage(STORE.competitionStore)[competitionStoreID][DB.competitionDate];

    var params = {
      type: "performancesDisAthComp",
      athleteID: a.value,
      competitionID: c.value,
      disziplinID: dIDs
    };
    var dateParts = compDate.split(".");

    if (dateParts.length == 1) {
      params.type = "performancesDisAthYear";
      params.year = compDate;
    }

    if (params.athleteID != null && params.competitionID != null && params.disziplinID != null) {
      existingEntries.post(params, processExistingPerformances, "json");
    }

  }
}
window.onSelectionChange = onSelectionChange

function getDIDs() {
  var performances = document.getElementsByName(INPUT.performanceInputName);
  // pIds = [];
  var dIDs = {};
  for (const key in performances) {
    var p = performances[key];
    if (p.id != undefined) {
      // pids.push(p.id)
      var dStoreId = p.id.slice(INPUT.performancePrefix.length);
      var dDBId = getValuesFromStorage(STORE.disziplinStore)[dStoreId][DB.disziplinID];
      dIDs[dStoreId] = dDBId;
    }
  }
  return dIDs;
}

function processExistingPerformances(data) {
  for (const disStoreID in data) {
    var html = "";
    var disPerfs = data[disStoreID];
    if (disPerfs.length > 0) {
      for (let i = 0; i < disPerfs.length; i++) {
        html += "<p>DB: " + disPerfs[i][DB.performance] + "</p>";
      }
    }
    document.getElementById(INPUT.performanceInputPrefix + disStoreID).innerHTML = html;
  }


}


function time2seconds(time) {
  if (typeof time == 'number') {
    return time;
  }
  var parts = time.split(':');
  if (parts.length == 1) {
    return time;
  }
  if (parts.length == 2) {
    var minutes = +parts[0];
    var seconds = +parts[1];
    return (minutes * 60 + seconds).toFixed(2);
  }
  return null;
}
window.time2seconds = time2seconds

/**
 * POINT Calculation
 */

function calcualtePoints(field) {
  pointCalculator.calculate(field);
  var detail = createMultipleDetail();
  createDetailInputHtm();
  document.getElementById(INPUT.detailInput).value = detail;

}
window.calcualtePoints = calcualtePoints

function createDetailInputHtml(){
  document.getElementById(INPUT.detailDiv).innerHTML = '<input type="text" class="form-control" id=' + INPUT.detailInput + '>';
}

/**
 * Disziplin Filter
 */

function showOnlyMultiple() {
  var disizplins = document.getElementsByName(INPUT.disziplinInputName);

  disizplins.forEach(d => {
    var disStoreID = d.id.slice(INPUT.disziplinPrefix.length);
    var dbdisziplin = getValuesFromStorage(STORE.disziplinStore)[disStoreID];
    var multiIds = dbdisziplin[DB.multiIds];
    if (multiIds == null) {
      hideTandem(d.id);
    } else {
      showTandem(d.id);
    }
  });
}
window.showOnlyMultiple = showOnlyMultiple

function showAllDisziplins() {
  document.getElementsByName(INPUT.disziplinInputName).forEach(d => {
    showTandem(d.id);
  });
}
window.showAllDisziplins = showAllDisziplins
function hideTandem(id) {
  $('#' + id + ', label[for=' + id + ']').hide();
}

function showTandem(id) {
  $('#' + id + ', label[for=' + id + ']').show();
  document.getElementById(id).style = "none";
}



/******************************************************************************** */
/****************************** Athlete Input *********************************** */
/******************************************************************************** */


function closeAthleteModal() {
  $("#" + INPUT.athleteModalId).modal('hide');
}
window.closeAthleteModal = closeAthleteModal

function insertAthlete() {
  athleteForm.athleteToDB();
  loadAthletes();
}
window.insertAthlete = insertAthlete

function updateAthleteInput() {
  athleteForm.updateModal();
  var categories = getSelectedRadioButtonObject(INPUT.categoryInputName);
  if (categories != null) {
    athleteForm.checkGender(getValuesFromStorage(STORE.categoryStore)[categories.value.split(",")[0]][DB.genderID]);
  }

  athleteForm.checkIndividual();
}
window.updateAthleteInput = updateAthleteInput

function deleteLastPerformance(performanceId) {

  // insert into db
  $.post('deleteElements.php', {
    "ID": performanceId
  }, function (data) {
    document.getElementById(INPUT.insertionOutput).innerHTML = data.message;
  }, "json");

}
window.deleteLastPerformance = deleteLastPerformance

function loadSimilarAthletes(inputField) {
  var existingEntries = new ExistingEntries();
  existingEntries.post({ type: "similarAthlete", athleteName: inputField.value }, processSimilarAthletes, "json");
}
window.loadSimilarAthletes = loadSimilarAthletes

function processSimilarAthletes(data) {
  var html = "";
  for (const key in data) {
    var athlete = data[key];
    html += createAthleteChanger(athlete);
  }
  document.getElementById(INPUT.existingAthletesDiv).innerHTML = html;
}


const birthInd = "birth";
const actInd = "act";
const unsureDateInd = "unsureDate";
const unsureYearInd = "unsureYear";
const minYearInd = "minYear";
const maxYearInd = "maxYear";

function createAthleteChanger(athlete) {
  var athleteId = athlete[DB.athleteID];


  var name = athlete[DB.athleteName];
  var birthDate = athlete[DB.atheletBirth].split("-")[0];

  var html = '<div class="form-inline">';
  html += p(name + ", " + birthDate);

  html += col('<label for="' + birthInd + athleteId + '">Jahrgang</label><input type="number" class="form-control" value=' + birthDate + ' id="' + birthInd + athleteId + '" style="width: 5em"></input>');
  var activeYear = athlete[DB.activeYear];
  html += col('<label for="' + actInd + athleteId + '">Aktiv Jahr</label><input type="number" class="form-control" value=' + activeYear + ' id="' + actInd + athleteId + '" style="width: 5em"></input>');

  var unsureDate = athlete[DB.unsureDate];
  var unsureYear = athlete[DB.unsureYear];

  var dateChecked = (unsureDate == 1) ? "checked" : "";
  html += col('<input type="checkbox" id="' + unsureDateInd + athleteId + '" value="' + athleteId + '" name=unsureDate"' + athleteId + '" ' + dateChecked + '><label for="' + unsureDateInd + athleteId + '">' + "Geburtstag unklar" + '</label>');
  var yearChecked = (unsureYear == 1) ? "checked" : "";
  html += col('<input type="checkbox" id="' + unsureYearInd + athleteId + '" value="' + athleteId + '" name=unsureYear"' + athleteId + '" ' + yearChecked + '><label for="' + unsureYearInd + athleteId + '">' + "Jahrgang unklar" + '</label>');

  var minYear = athlete[DB.minYear];
  html += col('<label for="' + minYearInd + athleteId + '">Min Jahr</label><input type="number" class="form-control" value=' + minYear + ' id="' + minYearInd + athleteId + '" style="width: 5em"></input>');
  var maxYear = athlete[DB.maxYear];
  html += col('<label for="' + maxYearInd + athleteId + '">Max Jahr</label><input type="number" class="form-control" value=' + maxYear + ' id="' + maxYearInd + athleteId + '" style="width: 5em"></input>');


  //  "<form>" + athleteDescription +unsureDate + unsureYear + minYear + maxYear + activeYear + "</form>";
  html += col('<a class="btn btn-secondary" onclick="updateAthlete(' + athleteId + ')">Save</a>');

  return html + '</div>'
}

function updateAthlete(athleteId) {
  var birth = document.getElementById(birthInd + athleteId).value;
  var activeYear = document.getElementById(actInd + athleteId).value;
  var unsureDate = document.getElementById(unsureDateInd + athleteId).checked;
  var unsureYear = document.getElementById(unsureYearInd + athleteId).checked;
  var minYear = document.getElementById(minYearInd + athleteId).value;
  var maxYear = document.getElementById(maxYearInd + athleteId).value;


  var param = {};
  param[DB.athleteID] = athleteId;
  param[DB.activeYear] = activeYear;
  param[DB.unsureDate] = unsureDate;
  param[DB.unsureYear] = unsureYear;
  param[DB.minYear] = minYear;
  param[DB.maxYear] = maxYear;
  param[DB.atheletBirth] = birth;

  $.post('updateAthlete.php', param, function (data) {
    console.log(data);
  }, "json");

}
window.updateAthlete = updateAthlete

function p(value) {
  return '<p>' + value + '</p>';
}

function col(value) {
  return '<div class="form-group">' + value + '</div>';
}
