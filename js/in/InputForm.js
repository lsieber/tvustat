export class InputForm {

    constructor(formId){
        this.formId = formId;

        this.genderOptions = { 1: "Male", 2: "Female", 3:"Mixed" };

        this.sortOptions = { 1: "ascending", 2: "descending" };

        this.disziplinTypeOptions = { 1: "Track", 2: "Field", 3: "Multiple" };

        this.teamTypeOptions = { 1: "Individula", 2: "Team" };
    }
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
        o = o + "</div>";
        return o;
    }

    getOneRadio(name, id, value, radioLabel) {
        return '<input type="radio" id="' + id + '" value="' + value + '" name="' + name + '"><label for="' + id + '">' + radioLabel + '</label>';
    }

    setGenderOptions(genderOptions) {
        this.genderOptions = genderOptions;
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

    updateBasicDefintion() {
        var def = JSON.parse(window.sessionStorage.defs);
        var g = {};
        for (const key in def.genders) {
            g[key] = def.genders[key].name;
        }
        this.setGenderOptions(g);

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
