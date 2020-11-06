

import { InputForm } from "./InputForm.js";
import { addValueToArrayStorage, getValuesFromStorage } from "./SessionStorageHandler.js";
import { InsertToDB } from "../elmt/InsertToDB.js";

import * as INPUT from "../config/inputNames.js";
import * as STORE from "../config/storageNames.js";
import * as DB from "../config/dbColumnNames.js";

const insertToDb = new InsertToDB();

export class CompetitionForm extends InputForm{
    constructor() {
        super(INPUT.competitionFormId);

        this.locationIdentifier = INPUT.cFormLocationIdentifier;
        this.dateIdentifier = INPUT.cFormDateIdentifier; 
        this.nameIdentifier = INPUT.cFormNameIdentifier;
    }

    competitionToDb(){
        var name = document.getElementById(this.nameIdentifier);
        var date = document.getElementById(this.dateIdentifier);
        var location = document.getElementById(this.locationIdentifier);

        if (date.value == "" || date == null || location == null || name == null) //  
        {
            alert("Bitte Fülle alle Felder Korrekt aus");
            return false;
        } else {
            var params = {
                type: "competitionFromValue",
                competitionName: name.value,
                village: location.value,
                facility: "",
                competitionDate: date.value
            }
            insertToDb.post(params, function (data) {
                loadCompetitions();
            }, "json")
        }
    }

    updateModal() {
        this.getAllLocations();
        this.getAllNames();
        document.getElementById(this.formId).innerHTML = this.createHTML();

        autocomplete(document.getElementById(this.locationIdentifier), this.locations);
        autocomplete(document.getElementById(this.nameIdentifier), this.names);

    }


    getAllLocations(){
        var locs = getValuesFromStorage(STORE.cFormAllLocations);
        this.locations = Object.values(locs).map(a => a[DB.competitionVillage]);
    }

    getAllNames(){
        var names = getValuesFromStorage(STORE.cFormAllNames);
        this.names = Object.values(names).map(a => a[DB.competitionName]);
    }

    getLocation() {
        return this.textField("Ort", 'type="text" id="' + this.locationIdentifier + '" class="form-control" placeholder="Ort" ');
    }

    getName() {
        return this.textField("Wettkampf Name", 'type="text" id="' + this.nameIdentifier + '" class="form-control" placeholder="Wettkampf Name" ');
    }

    getDate() {
        return this.textField("Wettkampf Datum", 'type="date" id="' + this.dateIdentifier + '" class="form-control" placeholder="Wettkampf Datum"');
    }


    createHTML(){
        return this.getLocation() + this.getName() + this.getDate();
    }
}

function autocomplete(inp, arr) {
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", function(e) {
        var a, b, i, val = this.value;
        /*close any already open lists of autocompleted values*/
        closeAllLists();
        if (!val) { return false;}
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
          if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
            /*create a DIV element for each matching element:*/
            b = document.createElement("DIV");
            /*make the matching letters bold:*/
            b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
            b.innerHTML += arr[i].substr(val.length);
            /*insert a input field that will hold the current array item's value:*/
            b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
            /*execute a function when someone clicks on the item value (DIV element):*/
                b.addEventListener("click", function(e) {
                /*insert the value for the autocomplete text field:*/
                inp.value = this.getElementsByTagName("input")[0].value;
                /*close the list of autocompleted values,
                (or any other open lists of autocompleted values:*/
                closeAllLists();
            });
            a.appendChild(b);
          }
        }
    });
    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function(e) {
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