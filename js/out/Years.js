import { Select } from "./Select.js";
import { ExistingEntries } from "../elmt/ExistingEntries.js";
import * as DB from "../config/dbColumnNames.js";
import * as OUT from "../config/outNames.js";
import * as STORE from "../config/storageNames.js";


export class Years {

    constructor() {
        this.existingEntries = new ExistingEntries();
    }

    createSelector() {
        this.existingEntries.post({ type: "allYears" }, processYearsResult, "json");
    }

    getSelectedValues(){
        return Select.getValue(OUT.categorySelectId).split(",");
    }

    getYearControl() {
        var selected = Select.getValue(OUT.yearSelectId);
        if (selected == DB.yearControlAll) {
            return selected;
        } else {
            return (selected.split(",").length > 1) ? DB.yearControlMultiple : DB.yearControlSingle;
        }
    }

}

function processYearsResult(data) {
    const all = Select.createValue(DB.yearControlAll, "Alle Jahre");
    var values = [all];
    // Adding the values of the DB
    for (const key in data) {
        values.push(Select.createValue(data[key][DB.year], data[key][DB.year]));
    }
    values.push(all);

    Select.create(values, OUT.yearDiv, OUT.yearSelectId);
}