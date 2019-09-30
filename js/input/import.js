/**
 * 
 */
import { DisziplinInputForm } from "./DisziplinInputForm.js"
import { loadBasicData, loadCompetitionLocations, loadCompetitionNames } from "./BasicDefinitions.js"
import { FileReaderDisziplin } from "./FileReaderDisziplin.js";
import { FileReaderAthlete } from "./FileReaderAthlete.js";

import { getValuesFromStorage } from "./SessionStorageHandler.js";
import { AthleteInputForm } from "./AthleteInputForm.js";
import { CompetitionForm } from "./CompetitionForm.js";

const basicDefintionFile = './BasicDefinitions.php';
const existingEntriesFile = './existing_entries.php';
const inputFileFieldId = "inputGroupFile01";
window.modalResultId = "modalResult";


const disziplinModalId = "disziplinModal";
const disziplinFormId = "disziplinForm";
const disziplinTableId = "disziplinInputs";
const disziplinStorageName = "disStore";

const athleteModalId = "athleteModal";
const athleteFormId = "athleteForm";
const athleteTableId = "athleteInputs";
const athleteStorageName = "athStore";

const competitionFormId = "competitionForm";
const competitionNamesStorage = "compNames";
const competitionNameIdentifier = "newCompetitionName";
const competitionLocationStorage = "compLoc";

window.disForm = new DisziplinInputForm(disziplinFormId);
window.athForm = new AthleteInputForm(athleteFormId);
window.compForm = new CompetitionForm(competitionFormId, competitionNamesStorage, competitionLocationStorage, competitionNameIdentifier);


const fileReaderDisziplin = new FileReaderDisziplin(inputFileFieldId, disziplinTableId, disziplinStorageName, disziplinModalId);
const fileReaderAthlete = new FileReaderAthlete(inputFileFieldId, athleteTableId, athleteStorageName, athleteModalId);

function onload() {
  loadBasicData(basicDefintionFile);
  loadCompetitionNames(existingEntriesFile, competitionNamesStorage);
  loadCompetitionLocations(existingEntriesFile, competitionLocationStorage);
  // window.disForm.updateModal();
  // window.athForm.updateModal();
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


function openNextAthlete() {
  var values = getValuesFromStorage(athleteStorageName);
  var first = true;
  for (const key in values) {
    if (first) {
      openModalWithAthlete(key);
    }
    first = false;
  }
}
window.openNextAthlete = openNextAthlete

function updateDisziplinInput() {
  window.disForm.updateModal();
  // $("#" + disziplinModalId).modal(); // Open Modal

}
window.updateDisziplinInput = updateDisziplinInput

function updateAthleteInput() {
  window.athForm.updateModal();
}
window.updateAthleteInput = updateAthleteInput

function updateCompetitionInput() {
  window.compForm.updateModal();
}
window.updateCompetitionInput = updateCompetitionInput

function insertDisziplin() {
  window.disForm.disziplinToDB(modalResultId);
}
window.insertDisziplin = insertDisziplin

function insertAthlete() {
  window.athForm.athleteToDB();
}
window.insertAthlete = insertAthlete

function insertCompetitionName() {
  window.compForm.competitionNameToDB();
}
window.insertCompetitionName = insertCompetitionName
