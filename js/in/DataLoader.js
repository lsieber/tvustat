
import * as DB from "../config/dbColumnNames.js";
import * as INPUT from "../config/inputNames.js";
import * as STORE from "../config/storageNames.js";

import { ExistingEntries } from "../elmt/ExistingEntries.js";
import { addValueToArrayStorage, getValuesFromStorage } from "./SessionStorageHandler.js";
import { getSelectedRadioButtonObject } from "./Selection.js";


/**
 * GENERAL FUNCTIONS
 */

const existingEntries = new ExistingEntries();


/** ********************************************************
 * CREAT COMPETITION LISTING
* ********************************************************/


export function loadCompetitions() {
    var year = document.getElementById(INPUT.yearInput).value;
    existingEntries.post({ type: "competitionsForYears", years: [year] }, processCompetitionResult, "json");
}
window.loadCompetitions = loadCompetitions

function processCompetitionResult(data) {
    addToStorage(data, window.competitionStore);
    var html = createRadios(INPUT.competitionList, createRadioParamsCompetition(data));
    document.getElementById(INPUT.competitionDiv).innerHTML = html;
}

function createRadioParamsCompetition(data) {
    var params = [];
    for (const key in data) {
        const value = data[key];
        params[key] = {};
        params[key].id = INPUT.competitionPrefix + value[INPUT.storeIdentifier];
        params[key].name = INPUT.competitionInputName;
        params[key].value = value[DB.competitionID];
        params[key].text = value[DB.competitionName] + ", " + value[DB.competitionVillage] + ", " + value[DB.competitionDate];
        params[key].additional = " onclick=onSelectionChange()";
    }
    return params;
}

/** ********************************************************
 * CREAT Category LISTING
* ********************************************************/


export function loadCategories() {
    existingEntries.post({ type: "allOutputCategories" }, processOutputCategoriesResult, "json");
    existingEntries.post({ type: "allCategories" }, processCategoriesResult, "json");
}
window.loadCategories = loadCategories

function processCategoriesResult(data) {
    for (const key in data) {
        addValueToArrayStorage(STORE.categoryStore, data[key][DB.categoryID], data[key]);
    }
}

function processOutputCategoriesResult(data) {
    addToStorage(data, STORE.outputCategoryStore);
    var html = createRadios(INPUT.categoryList, createRadioParamsCategories(data));
    document.getElementById(INPUT.categoryDiv).innerHTML = html;
}

function createRadioParamsCategories(data) {
    var params = [];
    for (const key in data) {
        const value = data[key];
        params[key] = {};
        params[key].id = INPUT.categoryPrefix + value[INPUT.storeIdentifier];
        params[key].name = INPUT.categoryInputName;
        params[key].value = value[DB.categoryIDs];
        params[key].text = value[DB.outputCategoryName];
        params[key].additional = " onclick=loadAthletes()";
    }
    return params;
}

/** ********************************************************
 * CREAT Disziplins LISTING
* ********************************************************/


export function loadDisziplins() {
    existingEntries.post({ type: "allDisziplins", categories: [] }, processDisziplinResult, "json");
}
window.loadDisziplins = loadDisziplins

function processDisziplinResult(data) {
    addToStorage(data, STORE.disziplinStore);
    var html = createRadios(INPUT.disziplinList, createRadioParamsDisziplin(data));
    document.getElementById(INPUT.disziplinDiv).innerHTML = html;
}

function createRadioParamsDisziplin(data) {
    var params = [];
    for (const key in data) {
        const value = data[key];
        params[key] = {};
        params[key].id = INPUT.disziplinPrefix + value[INPUT.storeIdentifier];
        params[key].name = INPUT.disziplinInputName;
        params[key].value = value[DB.disziplinID];
        params[key].text = value[DB.disziplinName];
        params[key].additional = " onclick=changePerformanceInput(),onSelectionChange()";
    }
    return params;
}


/** ********************************************************
 * CREAT Athlete LISTING
* ********************************************************/

export function loadAthletes() {
    var year = document.getElementById(INPUT.yearInput).value;
    existingEntries.post({ type: "athletesforCategory", categories: getSelectedCategories(), year: year }, processAthleteResult, "json");
}
window.loadAthletes = loadAthletes

function processAthleteResult(data) {
    addToStorage(data, STORE.athleteStore);
    var html = createRadios(INPUT.athleteList, createRadioParamsAthlete(data));
    document.getElementById(INPUT.athleteDiv).innerHTML = html;
    colorUnsureBirthDates(data);
}

function createRadioParamsAthlete(data) {
    var params = [];
    for (const key in data) {
        const value = data[key];
        // alert(value[DB.activeYear] + ", " + document.getElementById(INPUT.yearInput).value);
        if (value[DB.activeYear] >= document.getElementById(INPUT.yearInput).value) {
            params[key] = {};
            params[key].id = INPUT.athletePrefix + value[INPUT.storeIdentifier];
            params[key].name = INPUT.athleteInputName;
            params[key].value = value[DB.athleteID];
            params[key].text = value[DB.athleteName];
            params[key].additional = " onclick=onSelectionChange()";
        }
    }
    return params;
}

function colorUnsureBirthDates(data) {
    for (const key in data) {
        const value = data[key];
        var inputID = INPUT.athletePrefix + value[INPUT.storeIdentifier];

         if (value[DB.unsureDate] != null){
             if(value[DB.unsureDate] == 1){
                $('label[for="'+inputID+'"]').css('color', 'blue');
             }
         }
         if (value[DB.unsureYear] != null){
             if(value[DB.unsureYear] == 1){
                $('label[for="'+inputID+'"]').css('color', 'red');
            }
         }
    }
}


/** ********************************************************
 * CREAT Source LISTING
* ********************************************************/

export function loadSources() {
    existingEntries.post({ type: "allSources" }, processSourceResult, "json");
}
window.loadSources = loadSources

function processSourceResult(data) {
    addToStorage(data, STORE.sourceStore);
    var html = '<select class="form-control" id="' + INPUT.sourceSelect + '"><option value="NULL">Ohne Quelle</option>';
    for (const key in data) {
        html += '<option value=' + data[key]["sourceID"] + '>' + data[key]["sourceName"] + '</option>';
    }
    html += '<option value="NULL">Ohne Quelle</option>';
    document.getElementById(INPUT.sourceDiv).innerHTML = html + '</select>';
}

/** ********************************************************
 * CREAT POINT SCHEME NAMES  LISTING
* ********************************************************/

function createPointSchemeNamesSelector() {
    var html = '<label>Point Scheme</label><select class="form-control" id="' + INPUT.pointsSchemeSelect + '">';

    var pointSNs = getValuesFromStorage(STORE.definitionsStore)[STORE.pointSchemeNamesStore];
    for (const key in pointSNs) {
        const pointSN = pointSNs[key];
        html += '<option value=' + pointSN[DB.pointSchemeNameID] + '>' + pointSN[DB.pointSchemeName] + '</option>';
    }
    document.getElementById(INPUT.pointSchemeDiv).innerHTML = html + '</select>';
}

/** ********************************************************
 * CREAT Performance Inputs
* ********************************************************/

function changePerformanceInput() {
    var disziplinStoreId = getSelectedRadioButtonObject(INPUT.disziplinInputName).id.slice(INPUT.disziplinPrefix.length);
    var dbdisziplin = getValuesFromStorage(STORE.disziplinStore)[disziplinStoreId];
    var multiIds = dbdisziplin[DB.multiIds];

    /******************************************************************************3 */
    // AS Soon As we want the Multiple iput again you have to enable the following 20 lines:
    /************************************************* */
    
    // if (multiIds == null) {
        document.getElementById(INPUT.performanceDiv).innerHTML = createPerformanceInput(dbdisziplin[INPUT.storeIdentifier], INPUT.performanceInputName, dbdisziplin[DB.disziplinName], "");
    // } else {
    //     createPointSchemeNamesSelector();
    //     const ids = multiIds.split(",");
    //     var html = "";
    //     for (let i = 0; i < ids.length; i++) {
    //         const id = ids[i];
    //         const singleDisziplin = findDisziplinInStore(id);
    //         html += createPerformanceInput(singleDisziplin[INPUT.storeIdentifier], INPUT.performanceInputName, singleDisziplin[DB.disziplinName], " onInput=calcualtePoints(this)");
    //     }
    //     html += createPerformanceInput(dbdisziplin[INPUT.storeIdentifier], INPUT.performanceInputName, dbdisziplin[DB.disziplinName], "");
    //     document.getElementById(INPUT.performanceDiv).innerHTML = html;
    // }
}
window.changePerformanceInput = changePerformanceInput;

function findDisziplinInStore(dbId) {
    const storeDiszs = getValuesFromStorage(STORE.disziplinStore);
    for (const key in storeDiszs) {
        var storedis = storeDiszs[key];
        if (storedis[DB.disziplinID] == dbId) {
            return storedis;
        }
    }
    return null;
}
window.findDisziplinInStore = findDisziplinInStore

function createPerformanceInput(id, name, label, additional) {
    var lastIsterted = getValuesFromStorage(STORE.performanceStore);
    var value = "";
    if (lastIsterted != null) {
        if (id in lastIsterted) {
            value = " value='" + lastIsterted[id] + "'";
        }
    }
    var html = '<label>' + label + '</label> <div id=' + INPUT.performanceInputPrefix + id + ' ></div><input type="text" class="form-control" ';
    html += 'id="' + INPUT.performancePrefix + id + '" name="' + name + '" placeholder="Enter Performance"';
    html += value + additional + ' onchange="registerPerformance(this)" >'
    html += '<input type="hidden" name=' + INPUT.pointName + ' id="' + INPUT.pointPrefix + id + '">';
    return html;
}

function registerPerformance(input) {
    addValueToArrayStorage(STORE.performanceStore, input.id.slice(INPUT.performancePrefix.length), input.value);
}
window.registerPerformance = registerPerformance

/** *********************************
 * GENERAL FUNCTIONS
 * ***********************************/

// function yearOfDate(dbDate) {
//     return dbDate.slice(-4);
// }

function getSelectedCategories() {
    return getSelectedRadioButtonObject(INPUT.categoryInputName).value.split(",");
}

function addToStorage(data, storeName) {
    for (const key in data) {
        var v = data[key];
        v[INPUT.storeIdentifier] = key;
        addValueToArrayStorage(storeName, key, v);
    }
}

function createRadios(divId, params) {
    var type = "radio";
    var html = "<div id='" + divId + "'>";
    for (const key in params) {
        const v = params[key];
        html += '<input type="' + type + '" class="form-control" name="' + v.name + '" value="' + v.value + '" id="' + v.id + '"' + v.additional + '></input>';
        html += '<label for="' + v.id + '">' + v.text + '</label>'
    }
    return html + '</div>';
}
