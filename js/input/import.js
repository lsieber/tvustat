/**
 * 
 */
import { DisziplinInputForm } from "./DisziplinInputForm.js"
import { loadBasicData } from "./BasicDefinitions.js"
import { FileReaderDisziplin } from "./FileReaderDisziplin.js";
import { FileReaderAthlete } from "./FileReaderAthlete.js";

import { getValuesFromStorage } from "./SessionStorageHandler.js";
import { AthleteInputForm } from "./AthleteInputForm.js";

const basicDefintionFile = './BasicDefinitions.php';
const inputFileFieldId = "inputGroupFile01";
const modalResultId = "modalResult";


const disziplinModalId = "disziplinModal";
const disziplinFormId = "disziplinForm";
const disziplinTableId = "disziplinInputs";
const disziplinStorageName = "disStore";

const athleteModalId = "athleteModal";
const athleteFormId = "athleteForm";
const athleteTableId = "athleteInputs";
const athleteStorageName = "athStore";


window.disForm = new DisziplinInputForm(disziplinFormId);
window.athForm = new AthleteInputForm(athleteFormId);

const fileReaderDisziplin = new FileReaderDisziplin(inputFileFieldId, disziplinTableId, disziplinStorageName);
const fileReaderAthlete = new FileReaderAthlete(inputFileFieldId, athleteTableId, athleteStorageName);

function onload() {
  loadBasicData(basicDefintionFile);
  window.disForm.updateModal();
  window.athForm.updateModal();
}
window.onload = onload

function readDisziplins() {
  fileReaderDisziplin.loadData();
  fileReaderDisziplin.createDisziplinTable();
}
window.readDisziplins = readDisziplins

function readAthletes() {
  fileReaderAthlete.loadData();
  fileReaderAthlete.createAthleteTable();
}
window.readAthletes = readAthletes


function displayDisziplinStorage() {
  alert(getValuesFromStorage(disziplinStorageName));
}
window.displayDisziplinStorage = displayDisziplinStorage

function closeDisziplinModal() {
  $("#" + disziplinModalId).modal('hide');
}
window.closeDisziplinModal = closeDisziplinModal

function closeAthleteModal() {
  $("#" + athleteModalId).modal('hide');
}
window.closeAthleteModal = closeAthleteModal

function openModalWithDisziplin(id) {
  fileReaderDisziplin.openModalWithDisziplin(id, disziplinModalId, disziplinTableId);
}
window.openModalWithDisziplin = openModalWithDisziplin

function openModalWithAthlete(id) {
  fileReaderAthlete.openModalWithAthlete(id, athleteModalId, athleteTableId);
}
window.openModalWithAthlete = openModalWithAthlete

function updateDisziplinInput() {
  window.disForm.updateModal();
  // $("#" + disziplinModalId).modal(); // Open Modal

}
window.updateDisziplinInput = updateDisziplinInput

function updateAthleteInput() {
  window.athForm.updateModal();
}
window.updateAthleteInput = updateAthleteInput

function insertDisziplin() {
  var success = window.disForm.disziplinToDB(modalResultId);
  if (success) {

  }
}
window.insertDisziplin = insertDisziplin

function insertAthlete() {
  var success = window.athForm.athleteToDB(modalResultId);
}
window.insertAthlete = insertAthlete


