import { addValueToArrayStorage } from "./SessionStorageHandler.js";

export function loadBasicData(phpFile) {
    $.post(phpFile, {}, function (data) {
        window.sessionStorage.defs = data;
    });
}


export function loadCompetitionNames(phpFile) {
    $.post(phpFile, { type: "allCompetitionNames" },
        function (data) {
            for (const key in data) {
                // alert(key + data[key].name);
                addValueToArrayStorage(window.competitionNameStorage, key, data[key]);

            }
        }, "json");
}

export function loadCompetitionLocations(phpFile) {
    $.post(phpFile, { type: "allCompetitionLocations" }, function (data) {
        for (const key in data) {
            addValueToArrayStorage(window.competitionLocationStorage, key, data[key]);
        }
    }, "json");
}

