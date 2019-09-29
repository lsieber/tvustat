/**
 * 
 */
import { DisziplinInputForm } from "./DisziplinInputForm.js"
import { Gender } from "../elmt/Gender.js";
import { loadData } from "./BasicDefinitions.js"
import { insertDisziplinsFromFile, getNotRegisteredDisziplins, removeNotRegisteredDisziplinById } from "./DataLoader.js";
import { getSelectedRadioButton } from "./Selection.js";

window.disForm = new DisziplinInputForm();

function onload() {
  loadData('./BasicDefinitions.php');
  updateDisziplinInput();
}
window.onload = onload

function updateDisziplinInput() {
  window.disForm.updateFromSession();
  document.getElementById("disziplin-form-modal").innerHTML = window.disForm.create();
}
window.updateDisziplinInput = updateDisziplinInput

// function printElements() {
//   var genders=[];
//   var defs = JSON.parse(window.sessionStorage.defs);
//   for (var key in defs.genders) {
//     var g = defs.genders[key];
//     genders[g.id] = new Gender(g.id, g.name, g.shortName);
//   }

// }
// window.printElements = printElements

function insertAllDisziplins() {
  insertDisziplinsFromFile();
  printNotInDatabaseDisziplins();
}
window.insertAllDisziplins = insertAllDisziplins

function printNotInDatabaseDisziplins() {
  var notExistingDisziplins = getNotRegisteredDisziplins();

  var string = '<table class="table table-condensed"><tbody>';
  for (const key in notExistingDisziplins) {
    var newS = "<tr onclick='insertDisziplin(" + key + ")'><td> " + key + "</td><td>" + notExistingDisziplins[key] + " </td></tr> ";
    string = string + newS;
  }
  string = string + '</tbody> </table>';
  document.getElementById("disziplinInputs").innerHTML = string;
}

function insertDisziplin(id) {
  addDisziplin(getNotRegisteredDisziplins()[id]);
  removeNotRegisteredDisziplinById(id);
  printNotInDatabaseDisziplins();
}
window.insertDisziplin = insertDisziplin;

function addDisziplin(disziplin) {
  window.disForm.setDisziplinName(disziplin);
  document.getElementById("disziplin-form-modal").innerHTML = window.disForm.create();

  var c = disziplin.substring(1, 0);
  if (c >= '0' && c <= '9') {
    // it is a number
    document.getElementById("timetrue").checked = true;
    document.getElementById("sorting1").checked = true;
    document.getElementById("disziplinType1").checked = true;
  } else {
    // it isn't
    document.getElementById("timefalse").checked = true;
    document.getElementById("sorting2").checked = true;
    document.getElementById("disziplinType2").checked = true;
  }
  document.getElementById("decimal2").checked = true;
  document.getElementById("teamType1").checked = true;

  $("#disziplinInput").modal();
}

function disziplinToDB() {
  var name = document.getElementById(window.disForm.disziplinIdentifier);
  var orderNum = document.getElementById(window.disForm.orderNumberIdentifier);
  var min = document.getElementById(window.disForm.minValIndentifier);
  var max = document.getElementById(window.disForm.maxValIndentifier);
  var sortingId = getSelectedRadioButton(window.disForm.sortingIdentifier);
  var isTime = getSelectedRadioButton(window.disForm.isTimeIdentifier);
  var decimal = getSelectedRadioButton(window.disForm.decimalIdentifier);
  var disziplinType = getSelectedRadioButton(window.disForm.disziplinTypeIdentifier);
  var teamType = getSelectedRadioButton(window.disForm.teamTypeIdentifier);

  if (name.value == "" || orderNum.value == "" /*|| min.value == ""		|| max.value == ""*/) //  
  {
    alert("Bitte Fülle alle Felder aus");
  } else {
    // insert into db
    $.post('insertToDB.php', {
      type: "disziplin",
      "disziplinName": name.value,
      "orderNumber": orderNum.value,
      "minVal" : min.value,
      "maxVal" : max.value,
      "sortingID": sortingId,
      "isTime": isTime,
      "decimalPlaces": decimal,
      "disziplinTypeID": disziplinType,
      "teamTypeID": teamType
    }, function (data) {
      $('#disziplinInputsResult').html("<p>"+data+"</p>");
    });
    $('#disziplinInput').modal('hide');
  }
}
window.disziplinToDB = disziplinToDB

// Get the input field
var input = document.getElementById("#disziplinInput");

// Execute a function when the user releases a key on the keyboard
input.addEventListener("keyup", function(event) {
  // Number 13 is the "Enter" key on the keyboard
  if (event.keyCode === 13) {
    // Cancel the default action, if needed
    event.preventDefault();
    // Trigger the button element with a click
    document.getElementById("#saveAndCloseDisziplin").click();
  }
}); 
