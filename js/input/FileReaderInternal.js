
export class FileReaderInternal {


    getReaderFromFile() {
        var input = document.getElementById(window.inputFileFieldId);
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
        reader.readAsText(file);
        return reader;
    }



}

// const separators = [';'];
// const regexp = new RegExp(separators.join('|'),'g');

export function parse(text) {
    var array = [];
    var lines = text.split("\n");
    for (let index = 0; index < lines.length; index++) {
        array[index] = lines[index].split(";");
    }
    // return array.slice(0,-1); // Remove the last line as it is empty
    return array;
}