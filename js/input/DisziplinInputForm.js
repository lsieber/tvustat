import { InputForm } from "./InputForm.js";

export class DisziplinInputForm extends InputForm {
    constructor() {
        super()
        // this.id = id;
        this.disziplin = this.textField("Disziplin", 'type="text" class="form-control" placeholder="Enter Disziplin Name"');

        this.orderNumber = this.textField("Order Number", 'type="number" class="form-control" placeholder="Enter Order Number"');

        var sortOptoins = { 1: "ascending", 2: "descending" };
        this.sorting = this.getSimpleRadio("Sorting", "sorting", sortOptoins);

        var timeOptions = { true: "Time", false: "Distance" };
        this.isTime = this.getSimpleRadio("Measurement", "time", timeOptions);

        var decimalOptions = { 0: "No Digits", 1: "1 Digit", 2: "2 Digits" };
        this.decimalName = "decimal";
        this.isDecimal = this.getSimpleRadio("DecimalMeasures", this.decimalName, decimalOptions);

        var disziplinTypeOptions = { 1: "Track", 2: "Field", 3: "Multiple" };
        this.disziplinType = this.getSimpleRadio("Disziplin Type", "disziplinType", disziplinTypeOptions);

        var teamTypeOptions = { 1: "Individual", 2: "Team" };
        this.teamTypeName = "teamType";
        this.teamType = this.getSimpleRadio("Athlete is", this.teamType, teamTypeOptions);

        this.output = { decimalName: 2, teamTypeName: 1 };

    }

    create() {
        return this.disziplin + this.orderNumber + this.sorting + this.isTime + this.isDecimal + this.disziplinType + this.teamType;
    }

    getDefaultCheckedIds() {
        return this.output;
    }

}
