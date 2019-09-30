import { FileReaderInternal, parse } from "./FileReaderInternal.js";
import { addValueToStorage, removeValueById, getValuesFromStorage } from "./SessionStorageHandler.js";

export class FileReaderDisziplin extends FileReaderInternal {

    /**
     * 
     * @param {string} fileFieldId 
     */
    constructor(fileFieldId, disziplinTableId, disziplinStorageName, disziplinModalId) {
        super(fileFieldId);
        this.disziplinTableId = disziplinTableId;
        this.store = disziplinStorageName;
        this.disziplinModalId = disziplinModalId;
    }

    loadData() {
        var reader = this.getReaderFromFile();
        reader.onload = function (e) {
            var text = reader.result;
            var array = parse(text);
            var prevEl = null;
            for (let index = 0; index < array.length; index++) {
                const element = array[index];
                if (element[0] === "Nr") { // This is the criteria that the previous line contains a Disziplin Name
                    var disziplinName = prevEl[0];
                    $.post('existing_entries.php', { type: "disziplinExists", disziplin: disziplinName },
                        function (data) {
                            var a = JSON.parse(data);
                            if (a.disziplinExists == "false") {
                                addValueToStorage("disStore", a.disziplinName);
                            }
                        });
                }
                prevEl = element;
            }
        }
    }

    createDisziplinTable() {
        var notExistingDisziplins = getValuesFromStorage(this.store);
        var string = '<table class="table table-condensed"><tbody>';
        for (const disziplinId in notExistingDisziplins) {
            var newS = "<tr onclick='openModalWithDisziplin(" + disziplinId + ")'><td> " + disziplinId + "</td><td>" + notExistingDisziplins[disziplinId] + " </td></tr> ";
            string = string + newS;
        }
        document.getElementById(this.disziplinTableId).innerHTML = string + '</tbody> </table>';
    }

    openModalWithDisziplin(id) {
        // First the Disziplin Name has to be added to the modal
        this.addDisziplinToFrom(getValuesFromStorage(this.store)[id]);
        // Second: Open the modal
        $("#" + this.disziplinModalId).modal(); // Open Modal
        // Make Sure To Call the Remove function after the insertation
        // TODO should be done in the insert Method
        this.removeDisziplinFromList(id);
    }

    removeDisziplinFromList(id) {
        removeValueById(this.store, id);
        this.createDisziplinTable(this.disziplinTableId);
    }

    addDisziplinToFrom(disziplinName) {
        window.disForm.setDisziplinName(disziplinName);
        window.disForm.updateModal();
        window.disForm.selectDisziplinBased(disziplinName)
    }

}







