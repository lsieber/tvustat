import { FileReaderInternal, parse } from "./FileReaderInternal.js";
import { addValueToStorage, removeValueById, getValuesFromStorage } from "./SessionStorageHandler.js";

export class FileReaderAthlete extends FileReaderInternal {

    /**
     * 
     * @param {string} fileFieldId 
     */
    constructor(fileFieldId, athleteTableId, athleteStorageName) {
        super(fileFieldId);
        this.athleteTableId = athleteTableId;
        this.store = athleteStorageName;
    }

    loadData() {
        var reader = this.getReaderFromFile();
        reader.onload = function (e) {
            var text = reader.result;
            var array = parse(text);
            var nameIndex = -1; // this throws an exeption if not changed before usage
            
            for (let index = 0; index < array.length; index++) {
                const element = array[index];
                if (element[0] === "Nr") { // This is the criteria that the current Line Contains a Header
                    for (const key in element) {
                            const e = element[key];
                            if(e == "Name"){
                                nameIndex = key;
                            }
                    }
                } else{
                    if(nameIndex != -1){
                        var fullName = element[nameIndex];
                        if(fullName != "") {
                            $.post('existing_entries.php', { type: "athleteExists", fullName: fullName },
                            function (data) {
                                var a = JSON.parse(data);
                                if (a.athleteExists == "false") {
                                    addValueToStorage("athStore", a.fullName);
                                }
                            });
                        }
                    } 
                }
               
            }
        }
    }

    createAthleteTable() {
        var notExistingAthletes = getValuesFromStorage(this.store);
        var string = '<table class="table table-condensed"><tbody>';
        for (const athleteId in notExistingAthletes) {
            var newS = "<tr onclick='openModalWithAthlete(" + athleteId + ")'><td> " + athleteId + "</td><td>" + notExistingAthletes[athleteId] + " </td></tr> ";
            string = string + newS;
        }
        document.getElementById(this.athleteTableId).innerHTML = string + '</tbody> </table>';
    }

    openModalWithAthlete(id, athleteModalId) {
        // First the Athlete Name has to be added to the modal
        this.addAthleteToFrom(getValuesFromStorage(this.store)[id]);
        // Second: Open the modal
        $("#" + athleteModalId).modal(); // Open Modal
        // Make Sure To Call the Remove function after the insertation
        // TODO should be done in the insert Method
        removeAthleteFromList(id);
    }

    removeAthleteFromList(id) {
        removeValueById(this.store, id);
        this.createAthleteTable(this.athleteTableId);
    }

    addAthleteToFrom(athletName) {
        window.athForm.setAthleteName(athleteName);
        window.athForm.updateModal();
        window.athForm.selectAthleteBased(athleteName)
    }

}







