import { FileReaderInternal, parse } from "./FileReaderInternal.js";
import { addValueToArrayStorage, getStorageLength, getValuesFromStorage, getCompetition } from "./SessionStorageHandler.js";

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
            var htmlhead = '<table><tbody>'
            var html = htmlhead; // 
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
                            var newCompetition = { competitionName: compName, competitionDate: compDate, village: compVilage, inserted: false };
                            var competition = getCompetition(newCompetition);
                            if (competition == null) {
                                var storeIndex = getStorageLength(window.competitionStore);
                                newCompetition.storeID = storeIndex;
                                addValueToArrayStorage(window.competitionStore, storeIndex, newCompetition)
                                html += "<tr onclick='openModalWithCompetition(" + storeIndex + ")'><td>" + storeIndex + " </td><td>" + newCompetition.competitionDate + " </td><td>" + newCompetition.competitionName + " </td><td>" + newCompetition.village + " </td></tr> ";
                            } else {
                                if ("inserted" in competition) {
                                    if (competition["inserted"] == false) {
                            html += "<tr onclick='openModalWithCompetition(" + competition.storeID + ")'><td>" + competition.storeID + " </td><td>" + competition.competitionDate + " </td><td>" + competition.competitionName + " </td><td>" + competition.village + " </td></tr> ";
                                    }
                                }
                            }
     
                        }
                    }
                }

            }
            
            if (htmlhead == html) {
                document.getElementById(window.modalResultId).innerHTML = "<h3> ALL Competitions are in the Data Base</h3>";
            } else {
                document.getElementById(window.competitionTableId).innerHTML = html + '</tbody> </table>';
            }
        }
    }

    openModalWithCompetition(id) {
        // First the Competition Name has to be added to the modal
        this.addCompetitionToFrom(getValuesFromStorage(window.competitionStore)[id]);

        // Second: Open the modal
        $("#" + window.competitionModalId).modal(); // Open Modal
        // Make Sure To Call the Remove function after the insertation
    }


    addCompetitionToFrom(competition) {
        window.compForm.setValueCompDate(this.parseDate(competition.competitionDate));
        window.compForm.setValueCompName(competition.competitionName);
        window.compForm.setValueCompLocation(competition.village);
    }

    parseDate(input) {
        var parts = input.match(/(\d+)/g);
        // note parts[1]-1
        // return new Date(parts[2], parts[1]-1, parts[0]);
        return parts[2] + "-" + parts[1] + "-" + parts[0];
    }
}
