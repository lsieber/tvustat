import { InputForm } from "./InputForm.js";
import { getSelectedRadioButton, selectElementByValue } from "./Selection.js";
import { removeValueById, changeValueInArray, getAthlete } from "./SessionStorageHandler.js";
import * as INPUT from "../config/inputNames.js";

const bornYearIdentifier = "bornYear";
const dateIdentifier = "date";

function chageBirthInput() {

    var dateField = document.getElementById(dateIdentifier);
    if (dateField.type == "date") {
        dateField.value = 2000;
        dateField.type = "number";
        dateField.placeholder = "Enter Birth Year";
    } else if (dateField.type == "number") {
        dateField.value = new Date();
        dateField.type = "date";
        dateField.placeholder = "Enter Birth Date";
    }
}
window.chageBirthInput = chageBirthInput

export class AthleteInputForm extends InputForm {
    constructor(athleteFormId) {
        super(athleteFormId);

        this.id = -1;

        this.nameIdentifier = "fullName";
        this.fullName = "";

        this.existingAthletsDivName = INPUT.existingAthletesDiv;

        this.licenceIndentifier = "licence";
        this.licence = this.textField("Lizenz Nummer (normal leer)", 'type="number" id="' + this.licenceIndentifier + '" class="form-control" placeholder="Enter licence Number"');

        // this.dateIdentifier = "date";
        this.birthDate = "1988";

        this.dateswitch = '<a class="btn btn-primary" onclick="chageBirthInput()">Genaues Geburtsdatum</a>'

        this.bornYear = this.textField("Jahrgang", 'type="number" id="' + bornYearIdentifier + '" class="form-control" placeholder="Enter Born Year"');

        this.minYearIdentifier = "minYear";
        this.maxYearIdentifier = "maxYear";
        this.unsureBirthDateIdentifier = "unklarer Jahrgang"
        this.minYear = this.textField("Min Jahr", 'type="number" id="' + this.minYearIdentifier + '" class="form-control" placeholder="Enter Min Year"');
        this.maxYear = this.textField("Max Jahr", 'type="number" id="' + this.maxYearIdentifier + '" class="form-control" placeholder="Enter Max Year"');


        this.activeYearIdentifier = "activeYear";
        this.activeYear = new Date().getFullYear() + 5;

        this.genderIdentifier = "gender";

        this.teamTypeIdentifier = "teamType";

    }


    createUnsureBirthDate() {
        var html = '<a class="btn btn-secondary" data-toggle="collapse" href="#collapseUnsureBirthDate">Unklarer Jahrgang!</a>';
        html += '<div id="collapseUnsureBirthDate" class="collapse out">';

        html += this.textField("Min Jahr", 'type="number" id="' + this.minYearIdentifier + '" class="form-control" placeholder="Enter Max Year"');
        html += this.textField("Max Jahr", 'type="number" id="' + this.maxYearIdentifier + '" class="form-control" placeholder="Enter Min Year"');

        html += '</div>';
        return html;
    }



    updateModal() {
        this.updateBasicDefintion();
        document.getElementById(this.formId).innerHTML = this.createHTML();
    }

    createHTML() {
        return this.getFullName() + this.getExistingAthletesDiv() + this.getBirthDate() + this.dateswitch + this.createUnsureBirthDate() + this.licence + this.getActiveYear() + this.getGender() + this.getTeamType();
    }

    getFullName() {
        return this.textField("Ganzer Name (Vorname Nachname)", 'type="text" id="' + this.nameIdentifier + '" value="' + this.fullName + '" class="form-control" placeholder="Vorname Nachname" oninput="loadSimilarAthletes(this)"');
    }

    setFullName(fullName) {
        this.fullName = fullName;
    }

    getExistingAthletesDiv() {
        return "<div id='" + this.existingAthletsDivName + "'></div>";
    }

    getBirthDate() {
        return this.textField("Jahrgang", 'type="number" id="' + dateIdentifier + '" value="' + this.birthDate + '" class="form-control" placeholder="Geburtsdatum"');
    }

    setBirthDate(birthDate) {
        this.birthDate = birthDate;
    }

    setActiveYear(activeYear) {
        this.activeYear = activeYear;
    }

    getActiveYear() {
        return this.textField("Letztes Aktiv Jahr", 'type="number" id="' + this.activeYearIdentifier + '" class="form-control" value=' + this.activeYear + ' placeholder="Jahr"');
    }

    getGender() {
        return this.getSimpleRadio("Geschlecht", this.genderIdentifier, this.genderOptions);
    }

    checkGender(genderID) {
        selectElementByValue(this.genderIdentifier, genderID);
    }

    checkIndividual() {
        selectElementByValue(this.teamTypeIdentifier, 1);
    }

    getTeamType() {
        return this.getSimpleRadio("Athlet ist: (normal ist individual)", this.teamTypeIdentifier, this.teamTypeOptions);
    }

    selectDefault() {
        document.getElementById("teamType1").checked = true;
        document.getElementById("gender2").checked = true;
    }

    getId() {
        return this.id;
    }

    setId(id) {
        this.id = id;
    }

    athleteToDB() {
        var fullName = document.getElementById(this.nameIdentifier);
        var date = document.getElementById(dateIdentifier);
        var minYear = document.getElementById(this.minYearIdentifier);
        var maxYear = document.getElementById(this.maxYearIdentifier);
        var licenceNumber = document.getElementById(this.licenceIndentifier);
        var genderID = getSelectedRadioButton(this.genderIdentifier);
        var teamTypeID = getSelectedRadioButton(this.teamTypeIdentifier);
        var activeYear = document.getElementById(this.activeYearIdentifier);
        if (fullName.value == "" || date.value == "" || genderID == null || teamTypeID == null || activeYear == null) //  
        {
            alert("Bitte Fülle alle Felder Korrekt aus");
            return false;
        } else {
            // insert into db
            $.post('insertToDB.php', {
                type: "athlete",
                "fullName": fullName.value,
                "date": date.value,
                "minYear": minYear.value,
                "maxYear": maxYear.value,
                "licenceNumber": licenceNumber.value,
                "genderID": genderID,
                "teamTypeID": teamTypeID,
                "activeYear": activeYear.value
            }, function (data) {
                // if (data.success == true) {
                //     var athlete = getAthlete(data);
                //     alert(JSON.stringify(athlete));
                //     changeValueInArray(window.athleteStore, athlete.storeID, "inserted", "true");
                //     alert("Heurecka");
                // }
            }, "json");
        }
    }

}
