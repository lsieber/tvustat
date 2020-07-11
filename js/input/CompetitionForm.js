import { InputForm } from "./InputForm.js";
import { getSelectedRadioButton } from "./Selection.js";
import { getValuesFromStorage, addValueToArrayStorage, addValueToStorage, removeValueById } from "./SessionStorageHandler.js";
import { loadCompetitionNames } from "./BasicDefinitions.js";



export class CompetitionForm extends InputForm {
    constructor(formId, nameField, disabledNameField, disabledNameIdField, locationField, disabledLocationField, disabledLocationIdField, dateField) {
        super(formId);
        this.competitonNamesListId = "competitonNames";
        this.competitonLocationListId = "competitonLocations";

        this.dateField = dateField;
        this.namesStore = window.competitionNameStorage;
        this.locStore = window.competitionLocationStorage
        this.nameField = nameField;
        this.disabledNameField = disabledNameField;
        this.disabledNameIdField = disabledNameIdField;
        this.locationField = locationField;
        this.disabledLocationField = disabledLocationField;
        this.disabledLocationIdField = disabledLocationIdField;
    }


    setValueCompDate(competitionDate) {
        document.getElementById(this.dateField).value = competitionDate;

    }
    setValueCompName(competitionName) {
        document.getElementById(this.nameField).value = competitionName;
        this.fillDisabledNamesFromSession();
    }
    setValueCompLocation(village) {
        document.getElementById(this.locationField).value = village;
        this.fillDisabledLocationsFromSession();
    }

    fillDisabledLocationsFromSession() {
        var locationField = document.getElementById(this.locationField);
        var disLocationField = document.getElementById(this.disabledLocationField);
        var disLocationIDField = document.getElementById(this.disabledLocationIdField);
        var locations = getValuesFromStorage(window.competitionLocationStorage);
        for (const id in locations) {
            var location = locations[id];
            
            if (location.village == locationField.value) {
                disLocationField.value = locationField.value;
                disLocationIDField.value = location.competitionLocationID;
                break;
            } else {
                disLocationField.value = locationField.value;
                disLocationIDField.value = "This is not a Location of the Database";
            }
        }
    }


    fillDisabledNamesFromSession() {

        var nameField = document.getElementById(this.nameField);
        var disNameField = document.getElementById(this.disabledNameField);
        var disNameIDField = document.getElementById(this.disabledNameIdField);
        var names = getValuesFromStorage(window.competitionNameStorage);
        for (const id in names) {
            var name = names[id]
            if (name.competitionName == nameField.value) {
                disNameField.value = nameField.value;
                disNameIDField.value = name.competitionNameID;
                break;
            } else {
                disNameField.value = nameField.value;
                disNameIDField.value = "This is not a CompetitionName from the Database";
            }

        }
    }

    updateModal() {
        this.updateCompetitionNamesFromSession();
        this.updateCompetitionLocationsFromSession();
        this.fillDisabledNamesFromSession();
        this.fillDisabledLocationsFromSession();
    }

    updateCompetitionLocationsFromSession() {
        var options = {};
        var locations = getValuesFromStorage(this.locStore);
        for (const id in locations) {
            var compLoc = locations[id];
            options[compLoc.competitionLocationID] = compLoc.village;
        }
        document.getElementById(this.competitonLocationListId).innerHTML = this.getTagedOptions(options);
    }

    updateCompetitionNamesFromSession() {
        var options = {};
        var names = getValuesFromStorage(this.namesStore);
        for (const id in names) {
            var compName = names[id];
            options[compName.competitionNameID] = compName.competitionName;
        }
        document.getElementById(this.competitonNamesListId).innerHTML = this.getTagedOptions(options);
    }

    competitionNameToDB() {
        var competitionName = document.getElementById(this.nameField);

        if (competitionName.value == "") //  
        {
            alert("Bitte Fülle alle Felder Korrekt aus");
            return false;
        } else {
            // insert into db
            $.post('insertToDB.php', {
                type: "competitionName",
                competitionName: competitionName.value
            }, function (data) {
                if (data.success = "true") {

                    addValueToArrayStorage(window.competitionNameStorage, data.competitionNameID, data);
                    window.compForm.updateCompetitionNamesFromSession();
                }
                fillDisabledNames();
            }, "json");
        }
    }

    competitionLocationToDB() {
        var competitionLocation = document.getElementById(this.locationField);
        if (competitionLocation.value == "") //  
        {
            alert("Bitte Fülle alle Felder Korrekt aus");
            return false;
        } else {
            // insert into db
            $.post('insertToDB.php', {
                type: "competitionLocation",
                village: competitionLocation.value,
                facility: ""
            }, function (data) {
                if (data.success = "true") {
                    addValueToArrayStorage(window.competitionLocationStorage, data.competitionLocationID, data);
                    window.compForm.updateCompetitionLocationsFromSession();
                }
                fillDisabledLocations();
            }, "json");
        }
    }

    competitionToDB() {
        var nameId = document.getElementById(this.disabledNameIdField);
        var date = document.getElementById(this.dateField);
        var locationId = document.getElementById(this.disabledLocationIdField);

        if (date.value == "" || date == null || locationId == null || nameId == null) //  
        {
            alert("Bitte Fülle alle Felder Korrekt aus");
            return false;
        } else {
            // insert into db
            $.post('insertToDB.php', {
                type: "competition",
                competitionNameID: nameId.value,
                locationNameID: locationId.value,
                competitionDate: date.value
            }, function (data) {
               
            }, "json");
        }
    }

}
