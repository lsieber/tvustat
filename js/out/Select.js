
export class Select{

    static create(values, divName, selectId) {
        // <label for="sel1">Select list (select one):</label>
        var html = "<select class='form-control' id='" + selectId + "'>";
        for (const key in values) {
            var v = values[key];
            html += Select.createOneOption(v.displayValue, v.id);
        }
        html += "</select>";
        document.getElementById(divName).innerHTML = html;
    }

    static createOneOption(oText, oValue) {
        return '<option value="'+oValue+'">' + oText + '</option>';
    }

    static createValue(id, displayValue) {
        return { id: id, displayValue: displayValue};
    }

    /**
     * 
     * @param {string} selectId 
     * @return {string} string representation of the value 
     */
    static getValue(selectId){
        var e = document.getElementById(selectId);
        return e.options[e.selectedIndex].value;
    }


}