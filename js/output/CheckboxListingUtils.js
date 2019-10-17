
import { addValueToArrayStorage, getValuesFromStorage } from "../input/SessionStorageHandler.js";

import { selectLargestYear } from "./outputSelection.js";

/** ********************************************************
 * CREAT CATEGORY LISTING
* ********************************************************/

function loadOutputCategories() {
    $.post(window.existingEntriesFile, { type: "allOutputCategories" }, function (data) {
        for (const key in data) {
            var v = data[key];
            v["storeID"] = key;

            // var categories = v["categoryIDs"].split(",");
            // var catStore = getValuesFromStorage(window.categoryStore);
            // var categoriesStoreIDs = {};


            // categories.forEach(catID => {
            //     for (let index = 0; index < catStore.length; index++) {
            //         if (catStore[index]["categoryID"] == catID) {
            //             categoriesStoreIDs[catID] = catStore[index]["categoryID"];
            //         }
            //     }
            // });
            // v["categoriesStoreIDs"] = categoriesStoreIDs;
            // alert(Object.values(categoriesStoreIDs).join(","));
            addValueToArrayStorage(window.outputCategoryStore, key, v);
            window.sessionStorage.showOutputCat = true;
        }
        createOutputCategoryList(data);
        selectLastClickedCategory();
    }, "json");
}

function selectLastClickedCategory() {
    if (localStorage.lastClickedCategory != null) {
        if (document.getElementById(localStorage.lastClickedCategory) != null) {
            document.getElementById(localStorage.lastClickedCategory).checked = true;
        }
    }
}
function createOutputCategoryList(values) {
    var afterLabelHTML = createCategoryControl();
    var html = createCheckboxSelectorsHtml(values, "outputCategoryID", "categoryIDs", ["outputCategoryName"], window.categoryCheckName, "cat", "Kategorie", window.categoryChecksName, afterLabelHTML, 'onclick="selectCategoryControl(this)"');
    document.getElementById(window.categoryList).innerHTML = html;
}

export function loadCategories() {
    $.post(window.existingEntriesFile, { type: "allCategories" }, function (data) {
        for (const key in data) {
            var v = data[key];
            // v["storeID"] = key;
            // addValueToArrayStorage(window.categoryStore, key, v);
            addValueToArrayStorage(window.categoryByDbId, v["categoryID"], v);
        }
        loadOutputCategories();
    }, "json");
}

export function createCategoryListFromStore() {
    createCategoryList(getValuesFromStorage(window.categoryByDbId), "categoryID", "categoryID", ["categoryName"]);
}
export function createOutputCategoryListFromStore() {
    createCategoryList(getValuesFromStorage(window.outputCategoryStore), "outputCategoryID", "categoryIDs", ["outputCategoryName"]);
}

function createCategoryList(values, idIdent, valueIdent, labelIdents) {
    var html = createCheckboxes(values, idIdent, valueIdent, labelIdents, window.categoryCheckName, "cat", 'onclick="selectCategoryControl(this)"');
    document.getElementById(window.categoryChecksName).innerHTML = html;
    selectLastClickedCategory();
}


function createCategoryControl() {

    var html = "<div class='checkbox-control' id='categoryControl'>";
    html += createRadio("all", "Alle", "selectCategory()");
    html += createRadio("men", "Männer", "selectCategory()");
    html += createRadio("women", "Frauen", "selectCategory()");
    html += createRadio("multiple", "Mehrere", "selectCategory()");
    html += createRadio("single", "Einzeln", "selectCategory()");
    html += "</div>";
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
    var html = createCheckboxSelectorsHtml(values, "YEAR(competitionDate)", "storeID", ["YEAR(competitionDate)"], window.yearsCheckName, "year", "Jahr", window.yearsChecksName, "", "");
    document.getElementById(window.yearsList).innerHTML = html;
}



/** ********************************************************
 *  BASIC FUNCTIONS
 * ********************************************************/

function createCheckboxSelectorsHtml(params, idIdent, valueIdent, valueIdents, name, idPref, label, radiosDiv, afterLabelHTML, addidtionAttributes) {
    var html = '<div class="form-group"> <label>' + label + '</label>';
    html += afterLabelHTML;
    html += "<div id='" + radiosDiv + "'>";
    html += createCheckboxes(params, idIdent, valueIdent, valueIdents, name, idPref, addidtionAttributes);
    html += '</div></div>';
    return html;
}

function createCheckboxes(params, idIdent, valueIdent, valueIdents, name, idPref, addidtionAttributes) {
    var html = "";
    for (const key in params) {
        var id = idPref + params[key][idIdent];
        var value = params[key][valueIdent];
        var text = valueIdents.map(v => params[key][v]).join(", \n");
        html += '<input type="checkbox" class="form-control" name="' + name + '" value="' + value + '" id="' + id + '" ' + addidtionAttributes + '></input>';
        html += '<label for="' + id + '" >' + text + '</label>';
    }
    return html;
}
