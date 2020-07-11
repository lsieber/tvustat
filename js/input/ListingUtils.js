
import { addValueToArrayStorage } from "./SessionStorageHandler.js";
import { CompetitionForm } from "./CompetitionForm.js";


/** ********************************************************
 * CREAT COMPETITION LISTING
* ********************************************************/


export function loadCompetitions() {
    $.post(window.existingEntriesFile, { type: "allCompetitions" }, function (data) {
        for (const key in data) {
            var v = data[key];
            v["storeID"] = key;
            addValueToArrayStorage(window.competitionStore, key, v);
        }
        if (window.refuseLIsting == null) {
            createCompetitionList(data);
        }
    }, "json");
    // var competitionModal = new CompetitionForm("newCompetition", "nameField", "disNameField", "disNameIdField", "locationField", "disLocField", "disLocIdField", "dateField");
    // competitionModal.
}


function createCompetitionList(values) {
    // var values = getValuesFromStorage(window.competitionStore);
    var idents = ["competitionName", "village", "competitionDate"];
    var html = createRadioSelectorsHtmlComp(values, "competitionID", idents, window.competitionRadioName, "comp", "Competitions", "Search...");
    document.getElementById(window.competitionList).innerHTML = html;
}

function createRadioSelectorsHtmlComp(params, idIdent, valueIdents, name, idPref, label, placeholder) {
    var html = '<div class="form-group"> <label>' + label + '</label>';
    var onkeyUp = 'onkeyup="filterCompetitions()"';
    html += '<input type="text" class="form-control" ' + onkeyUp + ' id="' + window.competitionSearch + '" placeholder="' + placeholder + '"';
    html += "<div " + window.competitionRadios + ">"
    for (const key in params) {
        var idDB = params[key][idIdent];
        var idRadio = idPref + idDB;
        var idStore = key;
        var value = valueIdents.map(v => params[key][v]).join(",");
        html += '<input type="radio" class="form-control" name="' + name + '" value="' + idStore + '" id="' + idRadio + '"></input>';
        html += '<label for="' + idRadio + '">' + value + '</label>'
    }
    html += '</div></div>';
    return html;
}

function filterCompetitions() {
    textFilter(window.competitionSearch, window.competitionRadioName);
}
window.filterCompetitions = filterCompetitions


/** ********************************************************
 * CREAT CATEGORY LISTING
* ********************************************************/


export function loadCategories() {
    $.post(window.existingEntriesFile, { type: "allCategories" }, function (data) {
        for (const key in data) {
            var v = data[key];
            v["storeID"] = key;
            addValueToArrayStorage(window.categoryStore, key, v);
        }
        if (window.refuseLIsting == null) {
            createCategoryList(data);
        }
    }, "json");
}


function createCategoryList(values) {
    var html = createRadioSelectorsHtml(values, "categoryID", ["categoryName"], window.categoryRadioName, "cat", "Categories", window.categoryRadios);
    document.getElementById(window.categoryList).innerHTML = html;
}

/** ********************************************************
 * CREAT Disziplin LISTING
* ********************************************************/


export function loadDisziplins() {
    $.post(window.existingEntriesFile, { type: "allDisziplins" }, function (data) {
        for (const key in data) {
            var v = data[key];
            v["storeID"] = key;
            addValueToArrayStorage(window.disziplinStore, key, v);
        }
        if (window.refuseLIsting == null) {
            createDisziplinList(data);
        }

    }, "json");
}


function createDisziplinList(values) {
    var html = createRadioSelectorsHtml(values, "disziplinID", ["disziplinName"], window.disziplinRadioName, "dis", "Disziplins", window.disziplinRadios);
    document.getElementById(window.disziplinList).innerHTML = html;
}

/** ********************************************************
 * CREAT Athlete LISTING
* ********************************************************/


export function loadAthletes(/*categoryID, year*/) {
    $.post(window.existingEntriesFile, { type: "allAthletes" /*, categoryID: categoryID, year:year*/ }, function (data) {
        for (const key in data) {
            var v = data[key];
            v["storeID"] = key;
            addValueToArrayStorage(window.athleteStore, key, v);
        }
        if (window.refuseLIsting == null) {
            createAthleteList(data);
        }
    }, "json");
}


function createAthleteList(values) {
    var html = createRadioSelectorsHtml(values, "athleteID", ["fullName"], window.athleteRadioName, "ath", "athlete", window.athleteRadios);
    document.getElementById(window.athleteList).innerHTML = html;
}


/** ********************************************************
 * CREAT Source LISTING
* ********************************************************/


export function loadSources() {
    $.post(window.existingEntriesFile, { type: "allSources" }, function (data) {
        for (const key in data) {
            var v = data[key];
            v["storeID"] = key;
            addValueToArrayStorage(window.athleteStore, key, v);
        }
        createSourceSelector(data);
    }, "json");
}


function createSourceSelector(data) {
    var html = '<select class="form-control" id="sources">';
    for (const key in data) {
        html += '<option value='+data[key]["sourceID"]+'>'+ data[key]["sourceName"]+ '</option>';
    }
    document.getElementById("sourceInput").innerHTML = html + '</select>';
}



/** ********************************************************
 *  BASIC FUNCTIONS
 * ********************************************************/

function createRadioSelectorsHtml(params, idIdent, valueIdents, name, idPref, label, radiosDiv) {
    var html = '<div class="form-group"> <label>' + label + '</label>';
    html += "<div " + radiosDiv + ">"
    for (const key in params) {
        var idDB = params[key][idIdent];
        var idRadio = idPref + idDB;
        var idStore = key;
        var value = valueIdents.map(v => params[key][v]).join(", \n");
        html += '<input type="radio" class="form-control" name="' + name + '" value="' + idStore + '" id="' + idRadio + '"></input>';
        html += '<label for="' + idRadio + '">' + value + '</label>'
    }
    html += '</div></div>';
    return html;
}

function textFilter(searchField, radioName) {
    var filter, radios, a;
    filter = document.getElementById(searchField).value.toUpperCase();
    radios = document.getElementsByName(radioName);

    for (var i = 0; i < radios.length; i++) {
        var radio = radios[i];
        a = radio.value;
        if (a.toUpperCase().indexOf(filter) > -1) {
            showTandem(radio.id);

        } else {
            hideTandem(radio.id);

        }
    }
}

function hideTandem(id) {
    $('#' + id + ', label[for=' + id + ']').hide();
}

function showTandem(id) {
    $('#' + id + ', label[for=' + id + ']').show();
    document.getElementById(id).style = "none";
}

