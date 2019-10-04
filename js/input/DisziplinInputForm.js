import { InputForm } from "./InputForm.js";
import { getSelectedRadioButton } from "./Selection.js";
import { loadDisziplins } from "./ListingUtils.js"



export class DisziplinInputForm extends InputForm {
    constructor() {
        super(window.disziplinFormId);

        this.id = -1;
        this.disziplinIdentifier = "disziplin";
        this.disziplinName = "TEST";

        this.orderNumberIdentifier = "orderNumber";
        this.orderNumber = this.textField("Order Number", 'type="number" value="1000" id="' + this.orderNumberIdentifier + '" class="form-control" placeholder="Enter Order Number"');

        this.minValIndentifier = "minVal";
        this.minVal = this.textField("Minimal Value", 'type="number" step="0.01" value="0" id="' + this.minValIndentifier + '" class="form-control" placeholder="Enter Minimal Value"');

        this.maxValIndentifier = "maxVal";
        this.maxVal = this.textField("Maximal Value", 'type="number" step="0.01" value="1000" id="' + this.maxValIndentifier + '" class="form-control" placeholder="Enter Maximal Value"');

        this.sortingIdentifier = "sorting";

        this.isTimeIdentifier = "time";
        this.timeOptions = { true: "Time", false: "Distance" };

        this.decimalOptions = { 0: "No Digits", 1: "1 Digit", 2: "2 Digits" };
        this.decimalIdentifier = "decimal";
        this.isDecimal = this.getSimpleRadio("DecimalMeasures", this.decimalIdentifier, this.decimalOptions);

        this.disziplinTypeIdentifier = "disziplinType";

        this.teamTypeIdentifier = "teamType";

    }

    updateModal() {
        this.updateBasicDefintion();
        document.getElementById(window.disziplinFormId).innerHTML = this.createHTML();
    }

    createHTML() {
        return this.getDisziplin() + this.orderNumber + this.minVal + this.maxVal + this.getSorting() + this.getIsTime() + this.isDecimal + this.getDisziplinType() + this.getTeamType();
    }

    selectDisziplinBased(disziplinName) {
        var c = disziplinName.substring(1, 0);
        if (c >= '0' && c <= '9') {
            // if it is a number
            document.getElementById("timetrue").checked = true;
            document.getElementById("sorting1").checked = true;
            document.getElementById("disziplinType1").checked = true;
        } else {
            // it isn't
            document.getElementById("timefalse").checked = true;
            document.getElementById("sorting2").checked = true;
            document.getElementById("disziplinType2").checked = true;
        }
        document.getElementById("decimal2").checked = true;
        document.getElementById("teamType1").checked = true;
    }

    getId() {
        return this.id;
    }

    getIsTime() {
        return this.getSimpleRadio("Measurement", this.isTimeIdentifier, this.timeOptions);
    }
    getDisziplin() {
        return this.disziplin = this.textField("Disziplin", 'type="text" id="' + this.disziplinIdentifier + '" value="' + this.disziplinName + '" class="form-control" placeholder="Enter Disziplin Name"');
        ;
    }

    setId(id) {
        this.id = id;
    }

    setDisziplinName(disziplinName) {
        this.disziplinName = disziplinName;
    }

    getSorting() {
        return this.getSimpleRadio("Sorting", this.sortingIdentifier, this.sortOptions);
    }

    getDisziplinType() {
        return this.getSimpleRadio("Disziplin Type", this.disziplinTypeIdentifier, this.disziplinTypeOptions);
    }

    getTeamType() {
        return this.getSimpleRadio("Athlete is", this.teamTypeIdentifier, this.teamTypeOptions);
    }

    disziplinToDB(resultFieldId) {
        var name = document.getElementById(this.disziplinIdentifier);
        var orderNum = document.getElementById(this.orderNumberIdentifier);
        var min = document.getElementById(this.minValIndentifier);
        var max = document.getElementById(this.maxValIndentifier);
        var sortingId = getSelectedRadioButton(this.sortingIdentifier);
        var isTime = getSelectedRadioButton(this.isTimeIdentifier);
        var decimal = getSelectedRadioButton(this.decimalIdentifier);
        var disziplinType = getSelectedRadioButton(this.disziplinTypeIdentifier);
        var teamType = getSelectedRadioButton(this.teamTypeIdentifier);
        

        if (name.value == "" || orderNum.value == "" || min.value == ""  || max.value == "" || parseFloat(min.value) <= 0 || parseFloat(min.value) >= parseFloat(max.value)){
            alert("Bitte Fülle alle Felder Korrekt aus. use , not . for min and max");
            return false;
        } else {
            // insert into db
            $.post('insertToDB.php', {
                type: "disziplin",
                "disziplinName": name.value,
                "orderNumber": orderNum.value,
                "minVal": min.value,
                "maxVal": max.value,
                "sortingID": sortingId,
                "isTime": isTime,
                "decimalPlaces": parseFloat(decimal),
                "disziplinTypeID": disziplinType,
                "teamTypeID": teamType
            }, function (data) {
                var r = JSON.parse(data);
                $("#"+window.resultFieldId).html("<p>" + r.message + "</p>");
                loadDisziplins();
                window.readDisziplins();
            });
        }
    }

}
