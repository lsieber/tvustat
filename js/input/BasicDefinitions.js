import { addValueToStorage, addValueToArrayStorage } from "./SessionStorageHandler.js";

export function loadBasicData(phpFile){
    $.post(phpFile, {}, function(data) {
        window.sessionStorage.defs = data;
    });
}


export function loadCompetitionNames(phpFile, storageName){
    $.post(phpFile, {type:"allCompetitionNames"}, function(data) {
        for (const key in data) {
            addValueToArrayStorage(storageName,key, data[key]);
        }
    }, "json");
}

export function loadCompetitionLocations(phpFile, storageName){
    $.post(phpFile, {type:"allCompetitionLocations"}, function(data) {
        window.sessionStorage.compLoc = data;
    }, "json");
}