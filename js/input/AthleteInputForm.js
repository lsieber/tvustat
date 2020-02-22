import { InputForm } from "./InputForm.js";
import { getSelectedRadioButton } from "./Selection.js";
import { removeValueById, changeValueInArray, getAthlete } from "./SessionStorageHandler.js";

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
    constructor() {
        super(window.athleteFormId);

        this.id = -1;

        this.nameIdentifier = "fullName";
        this.fullName = "";

        this.licenceIndentifier = "licence";
        this.licence = this.textField("Licence Number", 'type="number" id="' + this.licenceIndentifier + '" class="form-control" placeholder="Enter licence Number"');

        // this.dateIdentifier = "date";
        this.birthDate = "1999-02-03";

        this.dateswitch = '<a class="btn btn-primary" onclick="chageBirthInput()">Birth Year Only</a>'

        this.bornYear = this.textField("Born Year", 'type="number" id="' + bornYearIdentifier + '" class="form-control" placeholder="Enter Born Year"');


        this.activeYearIdentifier = "activeYear";
        var defaultYear = new Date().getFullYear() + 5;
        this.activeYear = this.textField("Active Year", 'type="number" id="' + this.activeYearIdentifier + '" class="form-control" value=' + defaultYear + ' placeholder="Enter Active Year"');

        this.genderIdentifier = "gender";

        this.teamTypeIdentifier = "teamType";

    }

    updateModal() {
        this.updateBasicDefintion();
        document.getElementById(this.formId).innerHTML = this.createHTML();
    }

    createHTML() {
        return this.getFullName() + this.getBirthDate() + this.dateswitch + this.licence + this.activeYear + this.getGender() + this.getTeamType();
    }

    getFullName() {
        return this.textField("Full Name", 'type="text" id="' + this.nameIdentifier + '" value="' + this.fullName + '" class="form-control" placeholder="Enter Full Name"');
    }

    setFullName(fullName) {
        this.fullName = fullName;
    }

    getBirthDate() {
        return this.textField("Birth Date", 'type="date" id="' + dateIdentifier + '" value="' + this.birthDate + '" class="form-control" placeholder="Enter Birth Date"');
    }

    setBirthDate(birthDate) {
        this.birthDate = birthDate;
    }

    getGender() {
        return this.getSimpleRadio("Gender", this.genderIdentifier, this.genderOptions);
    }

    getTeamType() {
        return this.getSimpleRadio("Athlete is", this.teamTypeIdentifier, this.teamTypeOptions);
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
