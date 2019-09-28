/**
 * 
 */
import { DisziplinInputForm } from "./DisziplinInputForm.js"
import { Gender } from "../elmt/Gender.js";
import { loadData } from "./BasicDefinitions.js"
import { insertDisziplinsFromFile } from "./DataLoader.js";

function onload() {
  loadData('./BasicDefinitions.php');
  modifyDisziplinModal();
}
window.onload = onload

function printElements() {
  var genders=[];
  var defs = JSON.parse(window.sessionStorage.defs);
  for (var key in defs.genders) {
    var g = defs.genders[key];
    genders[g.id] = new Gender(g.id, g.name, g.shortName);
  }

}
window.printElements = printElements;

function insertAllDisziplins() {
  insertDisziplinsFromFile();
}
window.insertAllDisziplins = insertAllDisziplins


function modifyDisziplinModal() {
  var disForm = new DisziplinInputForm();
  document.getElementById("disziplin-form-modal").innerHTML = disForm.create();
  for (const key in disForm.getDefaultCheckedIds) {
    document.getElementById(key+disForm.getDefaultCheckedIds.key).checked = true;
  }
}

