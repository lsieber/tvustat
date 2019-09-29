export class InputForm {

    textField(label, inputAtributes) {
        return '<div class="form-group"> <label>' + label + '</label> <input ' + inputAtributes + '> </div>';
    }

    selList(label, options) {
        return ' <div class="form-group"> <label for="sel1">' + label + '</label> <select class="form-control" id="sel1">' + this.getTagedOptions(options) + '</select > </div >';
    }

    getTagedOptions(options) {
        var o = "";
        for (const key in options) {
            o = o + "<option id='" + key + "'>" + options[key] + "</option>";
        }
        return o;
    }

    getSimpleRadio(label, name, options) {

        var o = '<div class="form-group"><label>' + label + '</label>';
        for (const key in options) {
            o = o + this.getOneRadio(name, name + key, key, options[key]);
        }
        o = o + "</di>";
        return o;
    }

    getOneRadio(name, id, value, radioLabel) {
        return '<input type="radio" id="' + id + '" value="' + value + '" name="' + name + '"><label for="' + id + '">' + radioLabel + '</label>';
    }

}
