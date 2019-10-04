import { FileReaderInternal, parse } from "./FileReaderInternal.js";
import { addValueToArrayStorage, getValuesFromStorage, getAthlete, addValueToStorage, getStorageLength } from "./SessionStorageHandler.js";

export class FileReaderAthlete extends FileReaderInternal {

    loadData() {

        var reader = this.getReaderFromFile();
        reader.onload = function (e) {
            var html = '<table><tbody>'; // 
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
                            var newAthlete = { fullName: fullName, date: date, inserted: false };
                            var athlete = getAthlete(newAthlete);
                            if (athlete == null) {
                                var storeIndex = getStorageLength(window.athleteStore);
                                newAthlete.storeID = storeIndex;
                                addValueToArrayStorage(window.athleteStore, storeIndex, newAthlete)
                                html += "<tr onclick='openModalWithAthlete(" + storeIndex + ")'><td>" + storeIndex + " </td><td>" + newAthlete.fullName + " </td><td>" + newAthlete.date + " </td></tr> ";
                            } else {
                                if ("inserted" in athlete) {
                                    if (athlete["inserted"] == false) {
                                        html += "<tr onclick='openModalWithAthlete(" + athlete.storeID + ")'><td>" + athlete.storeID + " </td><td>" + athlete.fullName + " </td><td>" + athlete.date + " </td></tr> ";
                                    }
                                }
                            }
                        }

                    }
                }

            }
            document.getElementById(window.athleteTableId).innerHTML = html + '</tbody> </table>';
        }
    }


    openModalWithAthlete(storeID) {
        // First the Athlete Name has to be added to the modal
        var athlete = getValuesFromStorage(window.athleteStore)[storeID];
        this.addAthleteToFrom(athlete);
        // Second: Open the modal
        $("#" + window.athleteModalId).modal(); // Open Modal
        // Make Sure To Call the Remove function after the insertation
    }


    addAthleteToFrom(athlete) {
        window.athForm.setFullName(athlete.fullName);
        window.athForm.setBirthDate(this.parseDate(athlete.date).toString());
        window.athForm.updateModal();
        window.athForm.selectDefault();
    }
    parseDate(input) {
        var parts = input.match(/(\d+)/g);
        // note parts[1]-1
        // return new Date(parts[2], parts[1]-1, parts[0]);
        return parts[2] + "-" + parts[1] + "-" + parts[0];
    }
}

