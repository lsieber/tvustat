import * as OUT from "../config/outNames.js";
import * as DB from "../config/dbColumnNames.js";
import * as STORE from "../config/storageNames.js";

import { getValuesFromStorage, addValueToStorage, addValueToArrayStorage } from "../in/SessionStorageHandler.js";
import { getAthleteValue } from "./Results.js"
import { Categories } from "./Categories.js";

const categories = new Categories();

function onload() {
    categories.createSelector()

    onResultsClick(document.getElementById(OUT.athleteResultsDiv));
    onResultsClick(document.getElementById(OUT.categoryDiv))
    //loadBestList();

    $.post("./existing_entries.php", { type: "allAthletes" }, function (data) {
        var athletes = []
        for (const key in data) {
            var athlete = data[key];
            var visibleName = athlete[DB.athleteName] + " " + athlete[DB.atheletBirth];
            athletes.push(visibleName);
            addValueToArrayStorage("athletes", visibleName, athlete[DB.athleteID]);
        }
        writeSelectedNames();

        autocomplete(document.getElementById("athleteSearchField"), athletes);
    }, "json");

}
window.onload = onload

function loadBestList() {
    var athleteIDs = getValuesFromStorage("athleteIDResults");

    var params = {
        athleteIDs: athleteIDs,
        keepPerson: getAthleteValue(),
        disziplinId: "all",
        categories: categories.getSelectedValues(),
        categoryControl: categories.getCategoryControl(),
        year: "all"
    }
    if (athleteIDs != null) {
        $.post("./athleteResults.php",
            params, function (html) {
                document.getElementById("athleteBestList").innerHTML = html;
            }, "html");
    }
}
window.loadBestList = loadBestList

function onResultsClick(inp) {
    inp.addEventListener("change", function (e) {
        localStorage.keepAthleteResults = getAthleteValue();
        window.loadBestList()
    });
}

function writeSelectedNames() {
    var athleteNames = "";
    var selectedIds = getValuesFromStorage(STORE.selectedAthletesStore);
    var allAthletes = getValuesFromStorage("athletes");
    for (const key in selectedIds) {
        for (const keyAthletes in allAthletes) {
            if (selectedIds[key] == allAthletes[keyAthletes]) {
                athleteNames += '<div class="nameContainer">';
                athleteNames += '<div class="selectedAthleteName"><p>' + keyAthletes + '</p></div>';
                athleteNames += '<div class="removeAthlete"> <a onclick="unselectAthlete(' + selectedIds[key] + ')">löschen</a> </div></div>';
            }
        }
    }
    document.getElementById("selectedNames").innerHTML = athleteNames;
}
window.writeSelectedNames = writeSelectedNames

function unselectAthlete(athleteId) {
    var selectedIds = getValuesFromStorage(STORE.selectedAthletesStore);
    const index = selectedIds.indexOf(athleteId);
    if (index > -1) {
        selectedIds.splice(index, 1);
    }
    window.sessionStorage.removeItem(STORE.selectedAthletesStore);
    for (const key in selectedIds) {
        addValueToStorage(STORE.selectedAthletesStore, selectedIds[key]);
    }
    writeSelectedNames();
    loadBestList();
}
window.unselectAthlete = unselectAthlete

function autocomplete(inp, arr) {
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", function (e) {
        var a, b, i, val = this.value;
        /*close any already open lists of autocompleted values*/
        closeAllLists();
        if (!val) { return false; }
        currentFocus = -1;
        /*create a DIV element that will contain the items (values):*/
        a = document.createElement("DIV");
        a.setAttribute("id", this.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");
        /*append the DIV element as a child of the autocomplete container:*/
        this.parentNode.appendChild(a);
        /*for each item in the array...*/
        for (i = 0; i < arr.length; i++) {
            /*check if the item starts with the same letters as the text field value:*/
            if (arr[i].toUpperCase().includes(val.toUpperCase())) {
                /*create a DIV element for each matching element:*/
                b = document.createElement("DIV");
                /*make the matching letters bold:*/
                b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                b.innerHTML += arr[i].substr(val.length);
                /*insert a input field that will hold the current array item's value:*/
                b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                /*execute a function when someone clicks on the item value (DIV element):*/
                b.addEventListener("click", function (e) {
                    /*insert the value for the autocomplete text field:*/
                    inp.value = this.getElementsByTagName("input")[0].value;
                    /*close the list of autocompleted values,
                    (or any other open lists of autocompleted values:*/
                    closeAllLists();
                    /**Add the value to the session Storage */
                    addValueToStorage(STORE.selectedAthletesStore, parseInt(getValuesFromStorage("athletes")[inp.value]));
                    window.loadBestList();
                    window.writeSelectedNames();

                    inp.value = "";
                });
                a.appendChild(b);
            }
        }
    });
    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function (e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
            /*If the arrow DOWN key is pressed,
            increase the currentFocus variable:*/
            currentFocus++;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode == 38) { //up
            /*If the arrow UP key is pressed,
            decrease the currentFocus variable:*/
            currentFocus--;
            /*and and make the current item more visible:*/
            addActive(x);
        } else if (e.keyCode == 13) {
            /*If the ENTER key is pressed, prevent the form from being submitted,*/
            e.preventDefault();
            if (currentFocus > -1) {
                /*and simulate a click on the "active" item:*/
                if (x) x[currentFocus].click();
            }
        }
    });
    function addActive(x) {
        /*a function to classify an item as "active":*/
        if (!x) return false;
        /*start by removing the "active" class on all items:*/
        removeActive(x);
        if (currentFocus >= x.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (x.length - 1);
        /*add class "autocomplete-active":*/
        x[currentFocus].classList.add("autocomplete-active");
    }
    function removeActive(x) {
        /*a function to remove the "active" class from all autocomplete items:*/
        for (var i = 0; i < x.length; i++) {
            x[i].classList.remove("autocomplete-active");
        }
    }
    function closeAllLists(elmnt) {
        /*close all autocomplete lists in the document,
        except the one passed as an argument:*/
        var x = document.getElementsByClassName("autocomplete-items");
        for (var i = 0; i < x.length; i++) {
            if (elmnt != x[i] && elmnt != inp) {
                x[i].parentNode.removeChild(x[i]);
            }
        }
    }
    /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function (e) {
        closeAllLists(e.target);
    });
} 
