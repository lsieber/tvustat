
import { getValuesFromStorage, addValueToArrayStorage, getStorageLength, changeValueInArray } from "./SessionStorageHandler.js";
import { getSelectedRadioButton } from "./Selection.js";

export function insertPerformanceFromFields() {
    var athleteIDStore = getSelectedRadioButton(window.athleteRadioName);
    var competitionIDStore = getSelectedRadioButton(window.competitionRadioName);
    var disziplinIDStore = getSelectedRadioButton(window.disziplinRadioName);


    var performance = document.getElementById(window.performanceInput);
    var ranking = document.getElementById(window.rankingInput);
    var wind = document.getElementById(window.windInput);

    if (athleteIDStore == null || competitionIDStore == null || disziplinIDStore == null || performance.value == "") {
        alert("Not Enough Information");
    } else {

        var athlete = getValuesFromStorage(window.athleteStore)[athleteIDStore];
        var athleteID = athlete["athleteID"];
        var competitionID = getValuesFromStorage(window.competitionStore)[competitionIDStore]["competitionID"];
        var disziplin = getValuesFromStorage(window.disziplinStore)[disziplinIDStore];
        var disziplinID = disziplin["disziplinID"];
        var performanceValue = parseFloat(performance.value);
        var data = {
            athleteID: athleteID, //
            disziplinID: disziplinID, //
            competitionID: competitionID, //
            performance: performanceValue, //
            wind: wind.value, //
            placement: ranking.value //
        }
        insertPerformanceWithData(data, athlete, disziplin)
    }
}

export function insertPerformanceWithData(params, athlete, disziplin) {

    params.type = "performance"; // required for the php file to distinguisch whats inserted

    if (athlete["athleteID"] != params["athleteID"] || disziplin["disziplinID"] != disziplin["disziplinID"]) {
        alert("the inSert Failed as we have not the same id for an athlete or a disziplin to check the insertatiion conditions. ")
    } else {
        var minValue = parseFloat(disziplin["minVal"]);
        var maxValue = parseFloat(disziplin["maxVal"]);

        var performanceValue = convert(params.performance);
        if (performanceValue < minValue || performanceValue > maxValue) {
            alert("The Value " + performanceValue + " exteeds the limit for the disziplin " + disziplin["disziplinName"] + " which are: min=" + minValue + ", max=" + maxValue);
        } else if (disziplin["teamTypeID"] !== athlete["teamTypeID"]) {
            alert("The disziplin Team Type is " + disziplin["teamTypeID"] + " but the Athlete " + athlete["fullName"] + " has the type " + athlete["teamTypeID"])
        } else {
            $.post(window.insertFile,
                params, function (data) {
                    if (data.success == "true") {
                        var length;
                        if (window.sessionStorage.getItem(window.insertionResultStore) === null) {
                            length = 0;
                        } else {
                            var length = getStorageLength(window.insertionResultStore);
                        }
                        addValueToArrayStorage(window.insertionResultStore, length, data);


                    } else {
                        document.getElementById(window.inserteationOutput).innerHTML = "<p>" + data.message + "</p>"
                    }
                    if (data["fromFile"] != null) {
                        changeValueInArray(window.inputPerformanceStore, data["fromFile"], "inserted", true);
                    }
                    window.counter = window.counter + 1;;

                }, "json");
        }
    }

    function convert(time) {
        if(typeof time == 'number'){
            return time;
        }
        var parts = time.split(':');
        if (parts.length == 1) {
            return time;
        }
        if (parts.length == 2) {
            var minutes = +parts[0];
            var seconds = +parts[1];
            return (minutes * 60 + seconds).toFixed(2);
        }
        return null;
    }
}
