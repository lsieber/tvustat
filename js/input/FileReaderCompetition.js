import { FileReaderInternal, parse } from "./FileReaderInternal.js";
import { addValueToArrayStorage, removeValueById, getValuesFromStorage } from "./SessionStorageHandler.js";

export class FileReaderCompetition extends FileReaderInternal {

    /**
     * 
     * @param {string} fileFieldId 
     */
    constructor(fileFieldId, competitionTableId, competitionModalId) {
        super(fileFieldId);
        this.competitionTableId = competitionTableId;
        this.competitionModalId = competitionModalId;

    }

    loadData() {
        var reader = this.getReaderFromFile();
        reader.onload = function (e) {
            var text = reader.result;
            var array = parse(text);
            var nameIndex = -1; // this throws an exeption if not changed before usage
            var dateIndex = -1;
            var villageIndex = -1;

            for (let index = 0; index < array.length - 1; index++) {
                const element = array[index];
                if (element[0] === "Nr") { // This is the criteria that the current Line Contains a Header
                    for (const key in element) {
                        const e = element[key];
                        if (e == "Wettkampf") {
                            nameIndex = key;
                        }
                        if (e.substring(0, 5) == "Datum") {
                            dateIndex = key;
                        }
                        if (e == "Ort") {
                            villageIndex = key;
                        }
                    }
                } else {
                    if (nameIndex != -1) {
                        var compName = element[nameIndex];
                        var compDate = element[dateIndex].substring(0,10);
                        var compVilage = element[villageIndex];                        
                        if (compName != null && compName != "") {
                            // existing_entries JSON.stringify(
                            $.post('existing_entries.php', { "type": "competitionExists", "competitionName": compName, "competitionDate": compDate, "village": compVilage },
                                function (a) {
                                    // var a = JSON.parse(data);
                                    if (a.competitionExists == "false") {
                                        var comboName = a.competitionName+a.village+a.competitionDate;
                                        addValueToArrayStorage(window.competitionStorage, comboName.hashCode(), a);
                                    }
                                }, "json");
                        }
                    }
                }

            }
        }
    }


    createCompetitionTable() {
        var notExistingCompetitions = getValuesFromStorage(window.competitionStorage);
        var string = '<table class="table table-condensed"><tbody>';
        for (const hash in notExistingCompetitions) {
            var competition = notExistingCompetitions[hash];
            var newS = "<tr onclick='openModalWithCompetition(" + hash+ ")'><td>" + hash + " </td><td>" + competition.competitionName + " </td> <td>" + competition.village + " </td><td>" + competition.competitionDate + " </td></tr> ";
            string = string + newS;
        }
        document.getElementById(this.competitionTableId).innerHTML = string + '</tbody> </table>';
    }

    openModalWithCompetition(hash) {
        // First the Competition Name has to be added to the modal
        var notExistingCompetitions = getValuesFromStorage(window.competitionStorage);
        var competition = notExistingCompetitions[hash];
        this.addCompetitionToFrom(competition);
        // Second: Open the modal
        $("#" + this.competitionModalId).modal(); // Open Modal
        // Make Sure To Call the Remove function after the insertation
        removeValueById(window.competitionStorage, hash);
    }

    removeCompetitionFromList(id) {
        removeValueById(window.competitionStorage, id);
        this.createCompetitionTable(this.competitionTableId);
    }

    addCompetitionToFrom(competition) {
        window.compForm.setValueCompDate(competition.competitionDate);
        window.compForm.setValueCompName(competition.competitionName);
        window.compForm.setValueCompLocation(competition.village);
    }

}


String.prototype.hashCode = function(max){
    var hash = 0;
    if (!this.length) return hash;
    for (var i = 0; i < this.length; i++) {
      var char = this.charCodeAt(i);
      hash = ((hash<<5)-hash)+char;
      hash = hash & hash; // Convert to 32bit integer
    }
    return Math.abs(max?hash%max:hash);
  };
  