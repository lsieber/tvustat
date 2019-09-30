import { subModule } from "./subModul.js"

export class module {

    printModule() {
        alert("module");
        var sub = new subModule();
        sub.printSubModule();

    }
}


// function printTest() {
//     var mod = new module();
//     mod.printModule();
//     // var sub = new subModule();
//     // sub.printSubModule();

//     alert("hhah0");
// }
// window.printTest = printTest