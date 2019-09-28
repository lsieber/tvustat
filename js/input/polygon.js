export default class polygon {
    constructor(hoehe, breite) {
        this.hoehe = hoehe;
        this.breite = breite;
    }

    get flaeche() {
        return this.berechneFlaeche();
    }

    berechneFlaeche() {
        return this.hoehe * this.breite;
    }
}


//
