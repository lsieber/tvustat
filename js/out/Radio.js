
export class Radio {

    static create(divName,name,  values) {
        var html = "";
        for (const key in values) {
            var v = values[key];
            html += Radio.createOneRadio(name, Radio.createRadioId(name, v.id), v.displayValue, v.id);
        }
        document.getElementById(divName).innerHTML = html;
    }


    static createOneRadio(name, rId, displayValue, rValue) {
        var v = rValue != '' ? ' value="' + rValue + '"' : '';
        return '<input type="radio" class="form-control" name="' + name + '" ' + v + ' id="' + rId + '"><label for="' + rId + '">' + displayValue + '</label>';
    }

    static createValue(id, displayValue) {
        return { id: id, displayValue: displayValue };
    }

    static createRadioId(name, id) {
        return name + id;
    }

    static selectDefault(name, value) {
        var id = Radio.createRadioId(name, value.id);
        document.getElementById(id).checked = true;
    }


}