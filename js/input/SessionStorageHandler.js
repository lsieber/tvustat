
export function addValueToStorage(storageName, value) {
    if (window.sessionStorage.getItem(storageName) === null) {
        var values = [value];
        addValuesToStorage(storageName,values);
    } else {
        if (!isValueInRegister(storageName, value)) {
            var values = getValuesFromStorage(storageName);
            values.push(value);
            addValuesToStorage(storageName, values);
        }
    }
}


function addValuesToStorage(storageName, values) {
    window.sessionStorage.setItem(storageName, JSON.stringify(values));
    // var val = getValuesFromStorage(storageName);
    // alert(val);
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

export function getValuesFromStorage(storageName) {
    return JSON.parse(window.sessionStorage.getItem(storageName));
}

export function removeValueById(storageName, id) {
    var values = getValuesFromStorage(storageName);
    values.splice(id, 1);
    addValuesToStorage(storageName, values); // Overwrites current value
}