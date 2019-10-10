
import { addValueToArrayStorage } from "../input/SessionStorageHandler.js";

import { selectLargestYear } from "./outputSelection.js";

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
        createCategoryList(data);
        if (localStorage.lastClickedCategory != null) {
            document.getElementById(localStorage.lastClickedCategory).checked = true;
        }
    }, "json");
}


function createCategoryList(values) {
    var afterLabelHTML = createCategoryControl();
    var html = createCheckboxSelectorsHtml(values, "categoryID", ["categoryName"], window.categoryCheckName, "cat", "Kategorie", window.categoryChecksName, afterLabelHTML, 'onclick="selectCategoryControl(this)"');
    document.getElementById(window.categoryList).innerHTML = html;
}

function createCategoryControl() {

    var html = "";
    html += createRadio("all", "Alle", "selectCategory()");
    html += createRadio("men", "Männer", "selectCategory()");
    html += createRadio("women", "Frauen", "selectCategory()");
    html += createRadio("multiple", "Mehrere", "selectCategory()");
    html += createRadio("single", "Einzeln", "selectCategory()");

    return html;
}

function createRadio(id, value, onclick) {
    var begin = '<input type="radio" class="form-control" name="' + window.catControl + '"';
    var html = begin + 'id="' + id + '" onclick="' + onclick + '" checked="true"></input>';
    html += '<label for="' + id + '">' + value + '</label>'
    return html;
}

/** ********************************************************
 *  YEAR FUNCTIONS
 * ********************************************************/

export function loadYears() {
    $.post(window.existingEntriesFile, { type: "allYears" }, function (data) {
        for (const key in data) {
            var v = data[key];
            v["storeID"] = key;
            addValueToArrayStorage(window.yearsStore, key, v);
        }
        createYearsList(data);
        selectLargestYear();
    }, "json");
}



function createYearsList(values) {
    var html = createCheckboxSelectorsHtml(values, "YEAR(competitionDate)", ["YEAR(competitionDate)"], window.yearsCheckName, "year", "Jahr", window.yearsChecksName, "", "");
    document.getElementById(window.yearsList).innerHTML = html;
}



/** ********************************************************
 *  BASIC FUNCTIONS
 * ********************************************************/

function createCheckboxSelectorsHtml(params, idIdent, valueIdents, name, idPref, label, radiosDiv, afterLabelHTML, addidtionAttributes) {
    var html = '<div class="form-group"> <label>' + label + '</label>';
    html += afterLabelHTML;
    html += "<div " + radiosDiv + ">"
    for (const key in params) {
        var idDB = params[key][idIdent];
        var idRadio = idPref + idDB;
        var idStore = key;
        var value = valueIdents.map(v => params[key][v]).join(", \n");
        html += '<input type="checkbox" class="form-control" name="' + name + '" value="' + idStore + '" id="' + idRadio + '" ' + addidtionAttributes + '></input>';
        html += '<label for="' + idRadio + '" >' + value + '</label>'
    }
    html += '</div></div>';
    return html;
}
