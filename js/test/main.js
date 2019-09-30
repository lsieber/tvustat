import {module} from "./module.js"

function printMain(){
    alert("main");
    var mod = new module();
    mod.printModule();
}
window.printMain = printMain