import { ServerCom } from "./ServerCom.js"
import { calculatePointsFile } from "../config/serverFiles.js"
import * as INPUT from "../config/inputNames.js";
import * as STORE from "../config/storageNames.js";
import * as DB from "../config/dbColumnNames.js";

import { getValuesFromStorage } from "../in/SessionStorageHandler.js";
import { getSelectedRadioButtonObject } from "../in/Selection.js";


export class CalculatePoints extends ServerCom {

    constructor() {
        super(calculatePointsFile);
    }


    calculate(field) {
        var disStoreID = field.id.slice(INPUT.performancePrefix.length);
        var disDBId = getValuesFromStorage(STORE.disziplinStore)[disStoreID][DB.disziplinID];

        var a = getSelectedRadioButtonObject(INPUT.athleteInputName);
        if (a == null) {
            alert("select an AThlete Please")
        } else {

            var athleteIDStore = a.id.slice(INPUT.athletePrefix.length);
            var genderID = getValuesFromStorage(STORE.athleteStore)[athleteIDStore][DB.genderID];

            var performance = field.value;

            var e = document.getElementById(INPUT.pointsSchemeSelect);
            var schemeNameId = e.options[e.selectedIndex].value;

            var params = {
                disziplinID: disDBId,
                genderID: genderID,
                performance: performance,
                pointSchemeNameID: schemeNameId
            };

            this.post(params, this.processPoints, "json");
        }
    }

    processPoints(data) {
        var disStoreID = findDisziplinInStore(data[DB.disziplinID]);
        var id = disStoreID[INPUT.storeIdentifier];
        if (time2seconds(document.getElementById(INPUT.performancePrefix + id).value) == time2seconds(data[DB.performance])) {
            document.getElementById(INPUT.pointPrefix + id).value = data["points"];
        } else {
            alert("we could not calculae points");
        }
        var dis = getSelectedRadioButtonObject(INPUT.disziplinInputName);
        var disId = dis.id.slice(INPUT.disziplinPrefix.length);
        document.getElementById(INPUT.performancePrefix + disId).value = sumPoints();
    }


}

function sumPoints() {
    var points = document.getElementsByName(INPUT.pointName);
    var sum = 0;
    for (const key in points) {
        const point = points[key];
        if (point != undefined && point.value != null && point.value != NaN && point.value != "") {
            sum += parseInt(point.value);
        }
    }
    return sum;
}
