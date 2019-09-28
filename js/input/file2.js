/**
 * 
 */
// require('file1.js');
import polygon from './polygon.js'

// class Polygon {
//     constructor(hoehe, breite) {
//         this.hoehe = hoehe;
//         this.breite = breite;
//     }

//     get flaeche() {
//         return this.berechneFlaeche();
//     }

//     berechneFlaeche() {
//         return this.hoehe * this.breite;
//     }
// }

function alertHey() {
    alert("Hey");
}


function executeQuadrat() {
    const quadrat = new polygon(10, 10);
    alert("Fläche:" + quadrat.flaeche);
    alert("Hey");
}
window.executeQuadrat = executeQuadrat
