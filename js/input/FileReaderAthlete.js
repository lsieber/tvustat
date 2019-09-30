import { FileReaderInternal, parse } from "./FileReaderInternal.js";
import { addValueToArrayStorage, removeValueById, getValuesFromStorage } from "./SessionStorageHandler.js";

export class FileReaderAthlete extends FileReaderInternal {

    /**
     * 
     * @param {string} fileFieldId 
     */
    constructor(fileFieldId, athleteTableId, athleteStorageName, athleteModalId) {
        super(fileFieldId);
        this.athleteTableId = athleteTableId;
        this.store = athleteStorageName;
        this.athleteModalId = athleteModalId;

        this.dateId = "date";
    }

    loadData() {

        var reader = this.getReaderFromFile();
        reader.onload = function (e) {
            var text = reader.result;
            var array = parse(text);
            var nameIndex = -1; // this throws an exeption if not changed before usage
            var dateIndex = -1;
            for (let index = 0; index < array.length - 1; index++) {
                const element = array[index];
                if (element[0] === "Nr") { // This is the criteria that the current Line Contains a Header
                    for (const key in element) {
                        const e = element[key];
                        if (e == "Name") {
                            nameIndex = key;
                        }
                        if (e.substring(0, 4) == "Geb.") {
                            dateIndex = key;
                        }
                    }
                } else {
                    if (nameIndex != -1) {
                        var fullName = element[nameIndex];
                        var date = element[dateIndex];
                        if (fullName != "") {
                            // existing_entries JSON.stringify(
                            $.post('existing_entries.php', { type: "athleteExists", fullName: fullName, date: date },
                                function (a) {
                                    // var a = JSON.parse(data);
                                    // alert(a.athleteExists);
                                    if (a.athleteExists == "false") {
                                        addValueToArrayStorage("athStore", a.fullName.hashCode(), a);
                                    }
                                }, "json");
                        }
                    }
                }

            }
        }
    }


    createAthleteTable() {
        var notExistingAthletes = getValuesFromStorage(this.store);
        var string = '<table class="table table-condensed"><tbody>';
        for (const hash in notExistingAthletes) {
            var athlete = notExistingAthletes[hash];
            var newS = "<tr onclick='openModalWithAthlete(" + hash+ ")'><td>" + hash + " </td><td>" + athlete.fullName + " </td><td>" + athlete.date + " </td></tr> ";
            string = string + newS;
        }
        document.getElementById(this.athleteTableId).innerHTML = string + '</tbody> </table>';
    }

    openModalWithAthlete(hash) {
        // First the Athlete Name has to be added to the modal
        var notExistingAthletes = getValuesFromStorage(this.store);
        var athlete = notExistingAthletes[hash];
        this.addAthleteToFrom(athlete);
        // Second: Open the modal
        $("#" + this.athleteModalId).modal(); // Open Modal
        // Make Sure To Call the Remove function after the insertation
        // TODO should be done in the insert Method
        this.removeAthleteFromList(hash);
    }

    removeAthleteFromList(id) {
        removeValueById(this.store, id);
        this.createAthleteTable(this.athleteTableId);
    }

    addAthleteToFrom(athlete) {
        window.athForm.setFullName(athlete.fullName);
        window.athForm.setBirthDate(athlete.date);
        window.athForm.updateModal();
        window.athForm.selectDefault();
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
  