/**
 * 
 */
import { loadBasicData } from "./BasicDefinitions.js"
import { loadCompetitions, loadCategories, loadDisziplins, loadAthletes } from "./ListingUtils.js"



import { insertPerformanceFromFields } from "./InsertPerformanceUtils.js";

import { FileReaderPerformance } from "./FileReaderPerformance.js";


/**
 * Files
 */
window.basicDefintionFile = './BasicDefinitions.php';
window.existingEntriesFile = './existing_entries.php';
window.insertFile = './insertToDB.php';

/**
 * Session Storage names
 */
window.competitionStore = "compStore";
window.disziplinStore = "disStore"
window.categoryStore = "catStore";
window.athleteStore = "athStore";
window.insertionResultStore = "insertionResultStore";
window.inputPerformanceStore = "inputPerformanceStore"

/**
 * Field names
 */
// COMPETITION
window.competitionList = "competitionList";
window.competitionRadioName = "competitions";
window.competitionSearch = "competitionSearch";
window.competitionRadios = "competitionRadios";


// category
window.categoryList = "categoryList";
window.categoryRadioName = "categories";
window.categoryRadios = "categoryRadios";

// disziplin
window.disziplinList = "disziplinList";
window.disziplinRadioName = "disziplins";
window.disziplinRadios = "disziplinRadios";

// disziplin
window.athleteList = "athleteList";
window.athleteRadioName = "athletes";
window.athleteRadios = "athleteRadios";


// disziplin
window.performanceInput = "performanceInput";
window.windInput = "windInput";
window.rankingInput = "rankingInput";

// INPUT FILE
const inputFileFieldId = "inputFile";
const performanceFileReader = new FileReaderPerformance(inputFileFieldId);

// OUTPUT FIELDS
window.inserteationOutput = "inserteationOutput";

/**
 * 
 */
function onload() {
  loadBasicData(basicDefintionFile);
  loadCompetitions();
  loadCategories();
  loadDisziplins();
  loadAthletes();
  // createCompetitionList();
}
window.onload = onload

function loadPerformances() {
  performanceFileReader.loadData();
}
window.loadPerformances = loadPerformances

function insertPerfectMatches() {
  performanceFileReader.insertPerfectMatches();
}
window.insertPerfectMatches = insertPerfectMatches

function insertPerformance() {
  insertPerformanceFromFields()
}
window.insertPerformance=insertPerformance;


// $(document).ready(function () {
//   $("#" + window.competitionSearch).on("keyup", function () {
//       var value = $(this).val().toLowerCase();
//       $("#" + window.competitionRadios + " *").filter(function () {
//           $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
//       });
//   });
// });