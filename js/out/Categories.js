import { Select } from "./Select.js";
import { ExistingEntries } from "../elmt/ExistingEntries.js";
import * as DB from "../config/dbColumnNames.js";
import * as OUT from "../config/outNames.js";
import * as STORE from "../config/storageNames.js";



export class Categories {

    constructor() {
        this.existingEntries = new ExistingEntries();
    }

    createSelector() {
        this.existingEntries.post({ type: "allOutputCategories" }, processOutputCategoriesResult, "json");
        this.existingEntries.post({ type: "allCategories" }, processCategoriesResult, "json");
    }

    getSelectedValue() {
        return Select.getValue(OUT.categorySelectId);
    }

    getSelectedValues() {
        return this.getSelectedValue().split(",");
    }

    getCategoryControl() {
        var selected = this.getSelectedValue();
        if (selected == DB.categoryControlAll || selected == DB.categoryControlMen || selected == DB.categoryControlWomen) {
            return selected;
        } else {
            return (selected.split(",").length > 1) ? DB.categoryControlMultiple : DB.categoryControlSingle;
        }
    }

}

function processOutputCategoriesResult(data) {
    const all = Select.createValue(DB.categoryControlAll, "Alle Kategorien");
    const allMen = Select.createValue(DB.categoryControlMen, "Alle Männer");
    const allWomen = Select.createValue(DB.categoryControlWomen, "Alle Frauen");
    var values = [all, allMen, allWomen];

    // Adding the values of the DB
    for (const key in data) {
        values.push(Select.createValue(data[key][DB.categoryIDs], data[key][DB.outputCategoryName]));
    }

    // values.push(allMen);
    // values.push(allWomen);
    // values.push(all);

    Select.create(values, OUT.categoryDiv, OUT.categorySelectId);
}


function processCategoriesResult(data) {

}
