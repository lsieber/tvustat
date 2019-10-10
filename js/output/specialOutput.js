

window.outputField = "output";

function onload() {
    loadYears();
}
window.onload = onload


function loadCompetitions() {
    var yearIDsStore = getSelectedCheckboxesValues(window.yearsCheckName);
    var yearStore = getValuesFromStorage(window.yearsStore);
    var years = yearIDsStore.map(sId => yearStore[sId]["YEAR(competitionDate)"]);

    $.post(window.existingEntriesFile, { type: "competitionsForYears", years : years}, function (data) {
        document.getElementById(window.outputField).innerHTML = JSON.stringify(data);
    }, "json");
}
window.loadCompetitions = loadCompetitions