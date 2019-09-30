import { InputForm } from "./InputForm.js";
import { getSelectedRadioButton } from "./Selection.js";



export class AthleteInputForm extends InputForm {
    constructor(formId) {
        super(formId);

        this.id = -1;

        this.nameIdentifier = "fullName";
        this.fullName = "";

        this.licenceIndentifier = "licence";
        this.licence = this.textField("Licence Number", 'type="number" id="' + this.licenceIndentifier + '" class="form-control" placeholder="Enter licence Number"');

        this.dateIdentifier = "date";
        this.birthDate = null;

        this.genderIdentifier = "gender";

        this.teamTypeIdentifier = "teamType";

    }

    updateModal() {
        this.updateBasicDefintion();
        document.getElementById(this.formId).innerHTML = this.createHTML();
    }

    createHTML() {
        return this.getFullName() + this.getBirthDate() + this.licence + this.getGender() + this.getTeamType();
    }

    getFullName() {
        return this.textField("Full Name", 'type="text" id="' + this.nameIdentifier + '" value="' + this.fullName + '" class="form-control" placeholder="Enter Full Name"');
    }

    setFullName(fullName) {
        this.fullName = fullName;
    }

    getBirthDate() {
        return this.textField("Birth Date", 'type="date" id="' + this.dateIdentifier + '" value="' + this.birthDate + '" class="form-control" placeholder="Enter Birth Date"');
    }

    setBirthDate(birthDate){
        this.birthDate = birthDate;
    }
    
    getGender(){
        return this.getSimpleRadio("Gender", this.genderIdentifier, this.genderOptions);
    }

    getTeamType() {
        return this.getSimpleRadio("Athlete is", this.teamTypeIdentifier, this.teamTypeOptions);
    }

    selectDefault() {
        document.getElementById("teamType1").checked = true;
        document.getElementById("gender1").checked = true;
    }

    getId() {
        return this.id;
    }

    setId(id) {
        this.id = id;
    }

    athleteToDB() {
        var fullName = document.getElementById(this.nameIdentifier);
        var date = document.getElementById(this.dateIdentifier);
        var licenceNumber = document.getElementById(this.licenceIndentifier);
        var genderID = getSelectedRadioButton(this.genderIdentifier);
        var teamTypeID = getSelectedRadioButton(this.teamTypeIdentifier);

        if (fullName.value == "" || date.value == "" || genderID == null || teamTypeID == null ) //  
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
                "teamTypeID": teamTypeID
            }, function (data) {
                alert(data);
                // var r = JSON.parse(data);
                $("#" + window.modalResultId).html("<p>" + data.message + "</p>");
                // return (r.success === "true");
            }, "json");
        }
    }

}
