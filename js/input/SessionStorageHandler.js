
export function addValueToArrayStorage(storageName, id, array) {
    if (window.sessionStorage.getItem(storageName) === null) {
        var values = {id:array};
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

export function removeValueById(storageName, id) {
    var values = getValuesFromStorage(storageName);
    delete values[id];
    addValuesToStorage(storageName, values); // Overwrites current value
}