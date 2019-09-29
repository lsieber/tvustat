import { InputForm } from "./InputForm.js";

export class DisziplinInputForm extends InputForm {
    constructor() {
        super()

        this.id = -1;
        this.disziplinIdentifier = "disziplin";
        this.disziplinName = "TEST";

        this.orderNumberIdentifier = "orderNumber";
        this.orderNumber = this.textField("Order Number", 'type="number" value="1000" id="' + this.orderNumberIdentifier + '" class="form-control" placeholder="Enter Order Number"');

        this.minValIndentifier = "minVal";
        this.minVal = this.textField("Minimal Value", 'type="number" value="0" id="' + this.minValIndentifier + '" class="form-control" placeholder="Enter Minimal Value"');

        this.maxValIndentifier = "maxVal";
        this.maxVal = this.textField("Maximal Value", 'type="number" value="1000" id="' + this.maxValIndentifier + '" class="form-control" placeholder="Enter Maximal Value"');

        this.sortingIdentifier = "sorting";
        this.sortOptions = { 1: "ascending", 2: "descending" };

        this.isTimeIdentifier = "time";
        this.timeOptions = { true: "Time", false: "Distance" };

        this.decimalOptions = { 0: "No Digits", 1: "1 Digit", 2: "2 Digits" };
        this.decimalIdentifier = "decimal";
        this.isDecimal = this.getSimpleRadio("DecimalMeasures", this.decimalIdentifier, this.decimalOptions);

        this.disziplinTypeIdentifier = "disziplinType";
        this.disziplinTypeOptions = { 1: "Track", 2: "Field", 3: "Multiple" };

        this.teamTypeOptions = { 1: "INI", 2: "Zäme" };
        this.teamTypeIdentifier = "teamType";

    }

    create() {
        // alert(this.disziplinName + " ....... " + this.disziplin);

        return this.getDisziplin() + this.orderNumber + this.minVal + this.maxVal + this.getSorting() + this.getIsTime() + this.isDecimal + this.getDisziplinType() + this.getTeamType();
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



    setSortOptions(sortOptions) {
        this.sortOptions = sortOptions;
    }

    setDisziplinTypeOptions(disziplinTypeOptions) {
        this.disziplinTypeOptions = disziplinTypeOptions;
    }

    setTeamTypeOptions(teamTypeOptions) {
        this.teamTypeOptions = teamTypeOptions;
    }

    updateFromSession() {
        var def = JSON.parse(window.sessionStorage.defs);
        var s = {};
        for (const key in def.sortings) {
            s[key] = def.sortings[key].direction;
        }
        this.setSortOptions(s);

        var d = {};
        for (const key in def.disziplinTypes) {
            d[key] = def.disziplinTypes[key].type;
        }
        this.setDisziplinTypeOptions(d);

        var t = {};
        for (const key in def.teamTypes) {
            t[key] = def.teamTypes[key].type;
        }
        this.setTeamTypeOptions(t);
    }

}
