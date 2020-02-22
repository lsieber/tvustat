import * as OUT from "../config/outNames.js";
import * as DB from "../config/dbColumnNames.js";

function onload() {
    var athleteID = localStorage.athleteIDResults;

    var params = {
        athleteID: athleteID,
        disziplinId: "all",
        year: "all"
    }

    $.post("./athleteResults.php",
    params, function (html) {
        document.getElementById("athleteBestList").innerHTML = html;
    }, "html");

}
window.onload = onload
