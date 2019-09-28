export function loadData(phpFile){
    $.post(phpFile, {}, function(data) {
        window.sessionStorage.user = JSON.stringify(JSON.parse(data));
        window.sessionStorage.defs = data;
    });
}




// import {Gender} from "../elmt/Gender.js";

// export class BasicDefinitions{
//     constructor(){
//         this.phpFile = './BasicDefinitions.php'; 
//         this.genders = [];
//         this.loadData();
//     }

//     loadData(){
//         $.post(this.phpFile, {}, function(data) {

//             this.json = JSON.parse(data);
//             window.sessionStorage.user = JSON.stringify(this.json);
//             // alert(data);
//             // var gen = this.json.genders;
//             // for(var key in gen) {
//             //     var g = gen[key];
//             //     this.genders[g.id] = new Gender(g.id, g.name, g.shortName);
//             // }
//             alert("saved to")
//         });
//     }
// }