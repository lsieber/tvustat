import { FileReaderInternal, parse } from "./FileReaderInternal.js";
import { getValuesFromStorage, getDisziplin, addValueToArrayStorage, getStorageLength } from "./SessionStorageHandler.js";

export class FileReaderDisziplin extends FileReaderInternal {

    loadData() {
        var reader = this.getReaderFromFile();

        reader.onload = function (e) {
            var htmlhead = '<table><tbody>'
            var html = htmlhead; // 

            var text = reader.result;
            var array = parse(text);
            var prevEl = null;
            for (let index = 0; index < array.length; index++) {
                const element = array[index];
                if (element[0] === "Nr") { // This is the criteria that the previous line contains a Disziplin Name
                    var disziplinName = prevEl[0];

                    var newDisziplin = { disziplinName: disziplinName, inserted: false };
                    var disziplin = getDisziplin(newDisziplin);
                    if (disziplin == null) {
                        var storeIndex = getStorageLength(window.disziplinStore);
                        newDisziplin.storeID = storeIndex;
                        addValueToArrayStorage(window.disziplinStore, storeIndex, newDisziplin)
                        html += "<tr onclick='openModalWithDisziplin(" + storeIndex + ")'><td>" + storeIndex + " </td><td>" + newDisziplin.disziplinName + " </td></tr> ";
                    } else {
                        if ("inserted" in disziplin) {
                            if (disziplin["inserted"] == false) {
                                html += "<tr onclick='openModalWithDisziplin(" + disziplin.storeID + ")'><td>" + disziplin.storeID + newDisziplin.disziplinName + " </td></tr> ";
                            }
                        }
                    }
                }
                prevEl = element;
            }

            if (htmlhead == html) {
                document.getElementById(window.modalResultId).innerHTML = "<h3> ALL Disziplins are in the Data Base</h3>";
            } else {
                document.getElementById(window.disziplinTableId).innerHTML = html + '</tbody> </table>';
            }
        }
    }

    openModalWithDisziplin(id) {
        // First the Disziplin Name has to be added to the modal
        this.addDisziplinToFrom(getValuesFromStorage(window.disziplinStore)[id]["disziplinName"]);
        // Second: Open the modal
        $("#" + window.disziplinModalId).modal(); // Open Modal
        // Make Sure To Call the Remove function after the insertation
    }

    addDisziplinToFrom(disziplinName) {
        window.disForm.setDisziplinName(disziplinName);
        window.disForm.updateModal();
        window.disForm.selectDisziplinBased(disziplinName)
    }

}







