
export function addValueToArrayStorage(storageName, id, array) {
    if (window.sessionStorage.getItem(storageName) === null) {
        var values = {};
        values[id] = array;
        addValuesToStorage(storageName, values);
    } else {
        var values = getValuesFromStorage(storageName);
        values[id] = array;
        addValuesToStorage(storageName, values);
    }
}

export function addValueToStorage(storageName, value) {
    if (window.sessionStorage.getItem(storageName) === null) {
        var values = [value];
        addValuesToStorage(storageName, values);
    } else {
        if (!isValueInRegister(storageName, value)) {
            var values = getValuesFromStorage(storageName);
            values.push(value);
            addValuesToStorage(storageName, values);
        }
    }
}
function isValueInRegister(storageName, value) {
    var values = getValuesFromStorage(storageName);
    for (const key in values) {
        const element = values[key];
        if (element === value) {
            return true;
        }
    }
    return false;
}



function addValuesToStorage(storageName, values) {
    window.sessionStorage.setItem(storageName, JSON.stringify(values));
    // var val = getValuesFromStorage(storageName);
    // alert(val);
}


export function getValuesFromStorage(storageName) {
    return JSON.parse(window.sessionStorage.getItem(storageName));
}

export function changeValueInArray(storageName, key, identifier, newValue) {
    var object = getValuesFromStorage(storageName)[key];
    object[identifier] = newValue;
    addValueToArrayStorage(storageName, key, object);
}


export function getStorageLength(storageName) {
    return Object.keys(getValuesFromStorage(storageName)).length;
}

export function removeValueById(storageName, id) {
    var values = getValuesFromStorage(storageName);
    delete values[id];
    addValuesToStorage(storageName, values); // Overwrites current value
}

export function getAthlete(nameAndDate) {
    var values = getValuesFromStorage(window.athleteStore);
    for (const key in values) {
        var a = values[key];
        if (nameAndDate.fullName == a.fullName && nameAndDate.date == a.date) {
            return a;
        }
    }
    return null;
}

export function getDisziplin(disziplinNameArray) {
    var values = getValuesFromStorage(window.disziplinStore);
    for (const key in values) {
        var d = values[key];
        if (disziplinNameArray.disziplinName == d.disziplinName) {
            return d;
        }
    }
    return null;
}

export function getCompetition(nameDateVillage) {
    var values = getValuesFromStorage(window.competitionStore);
    for (const key in values) {
        var c = values[key];
        if (nameDateVillage.competitionName == c.competitionName && nameDateVillage.village == c.village && nameDateVillage.competitionDate == c.competitionDate) {
            return c;
        }
    }
    return null;
}