import { Select } from "./Select.js";
import { ExistingEntries } from "../elmt/ExistingEntries.js";
import * as DB from "../config/dbColumnNames.js";
import * as OUT from "../config/outNames.js";
import * as STORE from "../config/storageNames.js";


export class Disziplins {

    constructor() {
        this.existingEntries = new ExistingEntries();
    }

    createSelector() {
        this.existingEntries.post({ type: "allDisziplins" }, processDisziplinsResult, "json");
    }

    getSelectedValues(){
        return Select.getValue(OUT.disziplinSelectId).split(",");
    }

}

function processDisziplinsResult(data) {
    const all = Select.createValue("all", "Alle Kategorien");
    const allMen = Select.createValue("men", "Alle Männer");
    const allWomen = Select.createValue("women", "Alle Frauen");
    var values = [all, allMen, allWomen];

    // Adding the values of the DB
    for (const key in data) {
        values.push(Select.createValue(data[key][DB.categoryIDs], data[key][DB.outputCategoryName]));
    }
    values.push(all);

    Select.create(values, OUT.disziplinDiv, OUT.disziplinSelectId);
}
