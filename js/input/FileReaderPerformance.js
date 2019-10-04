import { FileReaderInternal, parse } from "./FileReaderInternal.js";
import { addValueToArrayStorage, removeValueById, getValuesFromStorage } from "./SessionStorageHandler.js";
import { insertPerformanceWithData } from "./InsertPerformanceUtils.js";

export class FileReaderPerformance extends FileReaderInternal {

    /**
     * 
     * @param {string} fileFieldId 
     */
    constructor(fileFieldId) {
        super(fileFieldId);
    }

    loadData() {
        var reader = this.getReaderFromFile();
        reader.onload = function (e) {
            var text = reader.result;
            var array = parse(text);


            var fullNameIndex, birthDateIndex, compNameIndex, compDateIndex, villageIndex, performanceIndex, windIndex, rankingIndex;

            const defaultIndex = -1;
            fullNameIndex = birthDateIndex = compNameIndex = compDateIndex = villageIndex = performanceIndex = windIndex = rankingIndex = defaultIndex;

            var prevEl = null;
            var disziplinName;
            var storageIndex = 0;
            for (let index = 0; index < array.length - 1; index++) {
                const element = array[index];
                if (element[0] === "Nr") { // This is the criteria that the current Line Contains a Header
                    fullNameIndex = birthDateIndex = compNameIndex = compDateIndex = villageIndex = performanceIndex = windIndex = rankingIndex = defaultIndex;
                    var disziplinName = prevEl[0];
                    for (const key in element) {
                        const e = element[key];
                        if (e == "Name") {
                            fullNameIndex = key;
                        }
                        if (e.substring(0, 4) == "Geb.") {
                            birthDateIndex = key;
                        }
                        if (e == "Wettkampf") {
                            compNameIndex = key;
                        }
                        if (e.substring(0, 5) == "Datum") {
                            compDateIndex = key;
                        }
                        if (e == "Ort") {
                            villageIndex = key;
                        }
                        if (e == "Resultat") {
                            performanceIndex = key;
                        }
                        if (e == "Wind") {
                            windIndex = key;
                        }
                        if (e == "Rang") {
                            rankingIndex = key;
                        }
                    }
                } else {
                    if (fullNameIndex != -1 && element[fullNameIndex] != "") {
                        var r = (rankingIndex == -1) ? "" : element[rankingIndex];
                        var w = (windIndex == -1) ? "" : element[windIndex];
                        var data = { //
                            "inserted": false, //
                            "fromFile": storageIndex, //
                            "performance": element[performanceIndex],//
                            "wind": w, //
                            "placement": r, //
                            "fullName": element[fullNameIndex], //
                            "date": element[birthDateIndex], //
                            "competitionName": element[compNameIndex], //
                            "competitionDate": element[compDateIndex].substring(0, 10), //
                            "village": element[villageIndex],//
                            "disziplinName": disziplinName //
                        }

                        addValueToArrayStorage(window.inputPerformanceStore, storageIndex, data);
                        storageIndex++;

                    }
                }
                prevEl = element;

            }
        }
    }

    insertPerfectMatches() {
        var values = getValuesFromStorage(window.inputPerformanceStore);

        for (const key in values) {

            var p = values[key];
            if (p["inserted"] == false) {

                var athlete = this.getAthlete(p);
                var disziplin = this.getDisziplin(p);
                var competition = this.getCompetition(p);
                alert("Lets  macth" + athlete.fullName);

                if (athlete != null && disziplin != null && competition != null) {
                    alert()
                    p["athleteID"] = athlete["athleteID"];
                    p["disziplinID"] = disziplin["disziplinID"];
                    p["competitionID"] = competition["competitionID"];
                    insertPerformanceWithData(p, athlete, disziplin);
                }
            }


        }
    }



    getAthlete(performance) {
        var values = getValuesFromStorage(window.athleteStore);
        for (const key in values) {
            var a = values[key];
            if (performance.fullName == a.fullName && performance.date == a.date) {
                return a;
            }
        }
        return null;
    }

    getDisziplin(performance) {
        var values = getValuesFromStorage(window.disziplinStore);
        for (const key in values) {
            var d = values[key];
            if (performance.disziplinName == d.disziplinName) {
                return d;
            }
        }
        return null;
    }


    getCompetition(performance) {
        var values = getValuesFromStorage(window.competitionStore);
        for (const key in values) {
            var c = values[key];
            // alert(performance.competitionName + c.competitionName);
            // alert(performance.village + c.village);
            // alert(performance.competitionDate + c.competitionDate);

            if (performance.competitionName == c.competitionName && performance.village == c.village && performance.competitionDate == c.competitionDate) {
                return c;
            }
        }
        return null;
    }


}

