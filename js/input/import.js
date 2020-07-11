/**
 * 
 */
import { DisziplinInputForm } from "./DisziplinInputForm.js"
import { loadBasicData, loadCompetitionLocations, loadCompetitionNames } from "./BasicDefinitions.js"
import { FileReaderDisziplin } from "./FileReaderDisziplin.js";
import { FileReaderAthlete } from "./FileReaderAthlete.js";
import { FileReaderCompetition } from "./FileReaderCompetition.js";
import { loadCompetitions, loadDisziplins, loadAthletes } from "./ListingUtils.js"


import { getValuesFromStorage } from "./SessionStorageHandler.js";
import { AthleteInputForm } from "./AthleteInputForm.js";
import { CompetitionForm } from "./CompetitionForm.js";


/**
 * FILES
 */
window.basicDefintionFile = './BasicDefinitions.php';
window.existingEntriesFile = './existing_entries.php';

/**
 * FIELDS 
 * 
 */
// GENERAL
window.inputFileFieldId = "inputGroupFile01";
window.modalResultId = "modalResult";
window.counter = 0;

// DISZIPLIN
window.disziplinModalId = "disziplinModal";
window.disziplinFormId = "disziplinForm";
window.disziplinTableId = "disziplinInputs";

// ATHLETES
window.athleteModalId = "athleteModal";
window.athleteFormId = "athleteForm";
window.athleteTableId = "athleteInputs";

// COMPETITION
window.competitionModalId = "competitionModal";
window.competitionTableId = "competitionInputs";
window.competitionFormId = "competitionForm";

/**
 * STORAGES
 *  */
window.competitionNameStorage = "compNames";
window.competitionLocationStorage = "compLoc";
window.competitionStore = "allcompStore";
window.disziplinStore = "alldisStore"
window.athleteStore = "allathStore";
window.refuseLIsting = true;

/**
 * MISC
 */
const competitionNameIdentifier = "newCompetitionName";
const disabledNameIdentifier = "CompetitionNameDisabled";
const disabledNameIdIdentifier = "CompetitionNameID";
const competitionLocationIdentifier = "newCompetitionLocation";
const disabledLocationIdentifier = "CompetitionLocationDisabled";
const competitionLocationID = "CompetitionLocationID";
const competitionDateFieldIdentifier = "competitionDate";

/**
 * FORMS
 */
window.disForm = new DisziplinInputForm();
window.athForm = new AthleteInputForm();
window.compForm = new CompetitionForm(competitionFormId, competitionNameIdentifier, disabledNameIdentifier, disabledNameIdIdentifier, competitionLocationIdentifier, disabledLocationIdentifier, competitionLocationID, competitionDateFieldIdentifier);

/**
 * FILE READERs
 */
const fileReaderDisziplin = new FileReaderDisziplin();
const fileReaderAthlete = new FileReaderAthlete();
const fileReaderCompetition = new FileReaderCompetition(competitionTableId, competitionModalId);


function onload() {
  loadBasicData(window.basicDefintionFile);
  loadCompetitionNames(window.existingEntriesFile);
  loadCompetitionLocations(window.existingEntriesFile);
  loadCompetitions();
  loadDisziplins();
  loadAthletes();
  // window.disForm.updateModal();
  // window.athForm.updateModal();
}
window.onload = onload

/**
 * Disziplin Functions
 */
function readDisziplins() {
  fileReaderDisziplin.loadData();
}
window.readDisziplins = readDisziplins


function closeDisziplinModal() {
  $("#" + disziplinModalId).modal('hide');
}
window.closeDisziplinModal = closeDisziplinModal


function openModalWithDisziplin(id) {
  fileReaderDisziplin.openModalWithDisziplin(id, disziplinModalId, disziplinTableId);
}
window.openModalWithDisziplin = openModalWithDisziplin

function updateDisziplinInput() {
  window.disForm.updateModal();
}
window.updateDisziplinInput = updateDisziplinInput

function insertDisziplin() {
  window.disForm.disziplinToDB(modalResultId);
}
window.insertDisziplin = insertDisziplin
/**
 * Athlete Functions
 */
function readAthletes() {
  fileReaderAthlete.loadData();
}
window.readAthletes = readAthletes


function closeAthleteModal() {
  $("#" + athleteModalId).modal('hide');
}
window.closeAthleteModal = closeAthleteModal

function openModalWithAthlete(id) {
  fileReaderAthlete.openModalWithAthlete(id)
}
window.openModalWithAthlete = openModalWithAthlete


function openNextAthlete() {
  fileReaderAthlete.openModalWithAthlete(window.athleteInModalStoreID + 1);
}
window.openNextAthlete = openNextAthlete

function notInsertedElement(element) {
  if ("inserted" in element) {
    return element["inserted"] == false
  }
}

function updateAthleteInput() {
  window.athForm.updateModal();
}
window.updateAthleteInput = updateAthleteInput

function insertAthlete() {
  window.athForm.athleteToDB();
}
window.insertAthlete = insertAthlete


/**
 * Competition Funcitons
 */
function readCompetitions() {
  fileReaderCompetition.loadData();
}
window.readCompetitions = readCompetitions


function closeCompetitionModal() {
  $("#" + competitionModalId).modal('hide');
}
window.closeCompetitionModal = closeCompetitionModal

function openModalWithCompetition(id) {
  updateCompetitionInput();
  fileReaderCompetition.openModalWithCompetition(id);
}
window.openModalWithCompetition = openModalWithCompetition


function openNextCompetition() {
  var values = getValuesFromStorage(window.competitionStorage).filter(notInsertedElement);
  var first = true;
  for (const key in values) {
    if (first) {
      openModalWithCompetition(key);
    }
    first = false;
  }
}
window.openNextCompetition = openNextCompetition


function updateCompetitionInput() {
  window.compForm.updateModal();
}
window.updateCompetitionInput = updateCompetitionInput


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