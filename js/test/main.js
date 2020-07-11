import { module } from "./module.js"

function printMain() {
    alert("main");
    var mod = new module();
    mod.printModule();
}
window.printMain = printMain

function test() {
    alert("Lets Change");
    var text = "TEST";
    document.body.innerHTML = document.body.innerHTML.replace(new RegExp(text, "g"), athleteLink("Lukas Sieber", "12"));
    // replaceText("*", "TEST", "JUHU", "g");
    // document.getElementById("testfield").innerHTML.replace("TEST", "Best");

}
window.test = test

function athleteLink(name, id) {
    var link = '<form method="post" action="athleteResults.php" class="inline">';
    link += '<input type="hidden" name="athleteName" value="'+name+'">';
    link += '  <button type="submit" name="athleteID" value="'+id+'" class="link-button">';
    link += name;
    link += '</button >';
    link += '</form >';
    return link;
}

function replaceText(selector, text, newText, flags) {
    var matcher = new RegExp(text, flags);
    var elems = document.querySelectorAll(selector), i;

    for (i = 0; i < elems.length; i++)
        if (!elems[i].childNodes.length)
            elems[i].innerHTML = elems[i].innerHTML.replace(matcher, newText);
}