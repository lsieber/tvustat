
import { addValueToArrayStorage } from "./SessionStorageHandler.js";


/** ********************************************************
 * CREAT COMPETITION LISTING
* ********************************************************/


export function loadCompetitions() {
    $.post(window.existingEntriesFile, { type: "allCompetitions" }, function (data) {
        for (const key in data) {
            addValueToArrayStorage(window.competitionStore, key, data[key]);
        }
        createCompetitionList(data);
    }, "json");
}


function createCompetitionList(values) {
    // var values = getValuesFromStorage(window.competitionStore);
    var idents = ["competitionName", "village", "competitionDate"];
    var html = createRadioSelectorsHtml(values, "competitionID", idents, window.competitionRadioName, "comp", "Competitions", "Search...");
    document.getElementById(window.competitionList).innerHTML = html;
}

function createRadioSelectorsHtml(params, idIdent, valueIdents, name, idPref, label, placeholder) {
    var html = '<div class="form-group"> <label>' + label + '</label>';
    var onkeyUp = 'onkeyup="filterCompetitions()"';
    html += '<input type="text" class="form-control" ' + onkeyUp + ' id="' + window.competitionSearch + '" placeholder="' + placeholder + '"';
    html += "<div " + window.competitionRadios + ">"
    for (const key in params) {
        var id = idPref + params[key][idIdent];
        var value = valueIdents.map(v => params[key][v]).join(",");
        html += '<input type="radio" class="form-control" name="' + name + '" value="' + value + '" id="' + id + '"></input>';
        html += '<label for="' + id + '">' + value + '</label>'
    }
    html += '</div></div>';
    return html;
}

function filterCompetitions(){
    textFilter(window.competitionSearch, window.competitionRadioName);
}
window.filterCompetitions = filterCompetitions


/** ********************************************************
 * CREAT CATEGORY LISTING
* ********************************************************/


export function loadCategories() {
    $.post(window.existingEntriesFile, { type: "allCategories" }, function (data) {
        for (const key in data) {
            addValueToArrayStorage(window.categoryStore, key, data[key]);
        }
        createCategoryList(data);
    }, "json");
}


function createCategoryList(values) {
    var idents = ["categoryStoreName", "village", "categoryStoreDate"];
    var html = createRadioSelectorsHtml(values, "categoryStoreID", idents, window.categoryStoreRadioName, "cat", "Categories", "Search...");
    document.getElementById(window.categoryStoreList).innerHTML = html;
}

function createRadioSelectorsHtml(params, idIdent, valueIdents, name, idPref, label, placeholder) {
    var html = '<div class="form-group"> <label>' + label + '</label>';
    var onkeyUp = 'onkeyup="filterCategories()"';
    html += '<input type="text" class="form-control" ' + onkeyUp + ' id="' + window.categoryStoreSearch + '" placeholder="' + placeholder + '"';
    html += "<div " + window.categoryStoreRadios + ">"
    for (const key in params) {
        var id = idPref + params[key][idIdent];
        var value = valueIdents.map(v => params[key][v]).join(",");
        html += '<input type="radio" class="form-control" name="' + name + '" value="' + value + '" id="' + id + '"></input>';
        html += '<label for="' + id + '">' + value + '</label>'
    }
    html += '</div></div>';
    return html;
}

function filterCategories(){
    textFilter(window.categoryStoreSearch, window.categoryStoreRadioName);
}
window.filterCategories = filterCategories


/** ********************************************************
 *  BASIC FUNCTIONS
 * ********************************************************/

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

function hideTandem(id){
    $('#' + id + ', label[for=' + id + ']').hide();
}

function showTandem(id){
    $('#' + id + ', label[for=' + id + ']').show();
    document.getElementById(id).style ="none";
}

