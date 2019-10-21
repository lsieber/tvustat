
export function loadDisziplinList(){


    $.post(window.existingEntriesFile, { type: "allDisziplins" }, function (data) {
        var options = "";
        options += '<option value="all">Alle</option>';
        for (const key in data) {
            var v = data[key];
            options += '<option value='+v["disziplinID"]+'>'+ v["disziplinName"]+ '</option>';
            // v["storeID"] = key;
            // addValueToArrayStorage(window.disziplinStore, key, v);
        }
        options += '<option value="all">Alle</option>';

        var html = '<div class="form-group">' + '<label for="disziplins"><h3>Disziplin</h3></label>';
        // html += '<input type="checkbox" id="allDisziplinCheck" checked="true" onclick="clearOptions()">Alle</input>'
        html += '<select class="form-control" id="disziplins">';
        html += options;

        html += '</select> </div>';

        document.getElementById("disziplinSelection").innerHTML = html;
    }, "json");
}