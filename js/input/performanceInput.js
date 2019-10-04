/**
 * 
 */
import { loadBasicData } from "./BasicDefinitions.js"
import { loadCompetitions, loadCategories} from "./ListingUtils.js"

import { getValuesFromStorage } from "./SessionStorageHandler.js";

/**
 * Files
 */
window.basicDefintionFile = './BasicDefinitions.php';
window.existingEntriesFile = './existing_entries.php';

/**
 * Session Storage names
 */
window.competitionStore = "compStore";
window.disziplinStore = "disStore"
window.categoryStore = "compStore";


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
window.categorySearch = "categorySearch";
window.categoryRadios = "categoryRadios";


/**
 * 
 */
function onload() {
  loadBasicData(basicDefintionFile);
  loadCompetitions();
  loadCategories();
  // createCompetitionList();
}
window.onload = onload




// $(document).ready(function () {
//   $("#" + window.competitionSearch).on("keyup", function () {
//       var value = $(this).val().toLowerCase();
//       $("#" + window.competitionRadios + " *").filter(function () {
//           $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
//       });
//   });
// });