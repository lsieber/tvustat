
export function insertDisziplinsFromFile() {
    var reader = getCustomFileName();

    reader.onload = function (e) {
        var text = reader.result;
        var array = parse(text);

        var previousElement = null;
        for (let index = 0; index < array.length; index++) {
            const element = array[index];
            if (element[0] == "Nr") {
                checkDisziplin(previousElement[0]);
            }
            previousElement = element;
        }
    }
}

function getCustomFileName() {
    var input = document.getElementById('inputGroupFile01');
    if (!input) {
        alert("Um, couldn't find the imgfile element.");
    }
    else if (!input.files) {
        alert("This browser doesn't seem to support the `files` property of file inputs.");
    }
    else if (!input.files[0]) {
        alert("Please select a file before clicking 'Load'");
    }
    var file = input.files[0];
    var reader = new FileReader();
    var text;
    reader.readAsText(file);

    return reader;
}

function checkDisziplin(disziplinName) {
    $.post('existing_entries.php', { type: "disziplinExists", disziplin: disziplinName },
        function (data) {
            var a = JSON.parse(data);
            if (a.disziplinExists == "false") {
                createNewDisziplinInput(a.disziplinName);
            }
        });
}

function parse(text) {
    var array = [];
    var lines = text.split("\n");
    for (let index = 0; index < lines.length; index++) {
        array[index] = lines[index].split(";");
    }

    return array;
}

function createNewDisziplinInput(disziplinName) {
    alert(disziplinName);
}