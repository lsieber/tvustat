/**
 * 
 */
import { DisziplinInputForm } from "./DisziplinInputForm.js"
import { loadBasicData, loadCompetitionLocations, loadCompetitionNames } from "./BasicDefinitions.js"
import { FileReaderDisziplin } from "./FileReaderDisziplin.js";
import { FileReaderAthlete } from "./FileReaderAthlete.js";
import { FileReaderCompetition } from "./FileReaderCompetition.js";


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

const competitionModalId = "competitionModal";
const competitionTableId = "competitionInputs";
const competitionStorageName = "athStore";

const competitionFormId = "competitionForm";
window.competitionNameStorage = "compNames";
window.competitionLocationStorage = "compLoc";
window.competitionStorage = "compStore";

const competitionNameIdentifier = "newCompetitionName";
const disabledNameIdentifier = "CompetitionNameDisabled";
const disabledNameIdIdentifier = "CompetitionNameID";
const competitionLocationIdentifier = "newCompetitionLocation";
const disabledLocationIdentifier = "CompetitionLocationDisabled";
const competitionLocationID = "CompetitionLocationID";
const competitionDateFieldIdentifier = "competitionDate";

window.disForm = new DisziplinInputForm(disziplinFormId);
window.athForm = new AthleteInputForm(athleteFormId);
window.compForm = new CompetitionForm(competitionFormId, competitionNameIdentifier, disabledNameIdentifier, disabledNameIdIdentifier, competitionLocationIdentifier, disabledLocationIdentifier, competitionLocationID, competitionDateFieldIdentifier);


const fileReaderDisziplin = new FileReaderDisziplin(inputFileFieldId, disziplinTableId, disziplinStorageName, disziplinModalId);
const fileReaderAthlete = new FileReaderAthlete(inputFileFieldId, athleteTableId, athleteStorageName, athleteModalId);
const fileReaderCompetition = new FileReaderCompetition(inputFileFieldId, competitionTableId, competitionModalId);


function onload() {
  loadBasicData(basicDefintionFile);
  loadCompetitionNames(existingEntriesFile);
  loadCompetitionLocations(existingEntriesFile);
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


function readCompetitions() {
  fileReaderCompetition.loadData();
  fileReaderCompetition.createCompetitionTable();
}
window.readCompetitions = readCompetitions


// function displayDisziplinStorage() {
//   alert(getValuesFromStorage(disziplinStorageName));
// }
// window.displayDisziplinStorage = displayDisziplinStorage

function closeDisziplinModal() {
  $("#" + disziplinModalId).modal('hide');
}
window.closeDisziplinModal = closeDisziplinModal

function closeAthleteModal() {
  $("#" + athleteModalId).modal('hide');
}
window.closeAthleteModal = closeAthleteModal

function closeCompetitionModal() {
  $("#" + competitionModalId).modal('hide');
}
window.closeCompetitionModal = closeCompetitionModal


function openModalWithDisziplin(id) {
  fileReaderDisziplin.openModalWithDisziplin(id, disziplinModalId, disziplinTableId);
}
window.openModalWithDisziplin = openModalWithDisziplin

function openModalWithAthlete(id) {
  fileReaderAthlete.openModalWithAthlete(id, athleteModalId, athleteTableId);
}
window.openModalWithAthlete = openModalWithAthlete

function openModalWithCompetition(id) {
  updateCompetitionInput();
  fileReaderCompetition.openModalWithCompetition(id);
}
window.openModalWithCompetition = openModalWithCompetition


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

function openNextCompetition() {
  var values = getValuesFromStorage(window.competitionStorage);
  var first = true;
  for (const key in values) {
    if (first) {
      openModalWithCompetition(key);
    }
    first = false;
  }
}
window.openNextCompetition = openNextCompetition


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

function insertCompetition() {
  window.compForm.competitionToDB();
}
window.insertCompetition = insertCompetition


function fillDisabledNames() {
  window.compForm.fillDisabledNamesFromSession();
}
window.fillDisabledNames = fillDisabledNames

function insertCompetitionLocation() {
  window.compForm.competitionLocationToDB();
}
window.insertCompetitionLocation = insertCompetitionLocation


function fillDisabledLocations() {
  window.compForm.fillDisabledLocationsFromSession();
}
window.fillDisabledLocations = fillDisabledLocations 