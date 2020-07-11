import { Radio } from "./Radio.js";

import * as OUT from "../config/outNames.js";
import { getSelectedRadioButton, getSelectedRadioButtonObject } from "./Selection.js";


export function createManualTimingRadio() {
    const electrical = Radio.createValue("E", "Nur Elektronisch");
    const electricalOrHand = Radio.createValue("EORH", "Bestes Ergebnis Elektroinisch oder Hand");
    const electriclAndHand = Radio.createValue("EANDH", "Beide Ergebnisse Elektronisch und Hand");
    const hand = Radio.createValue("H", "Nur Handstoppung");

    var values = { electrical, electricalOrHand, electriclAndHand, hand };
    Radio.create(OUT.manualTimingDiv, OUT.manualTimingRadioName, values);
    Radio.selectDefault(OUT.manualTimingRadioName, electricalOrHand);
}

export function getManualTimingValue() {
    return getSelectedRadioButton(OUT.manualTimingRadioName);
}

export function createAthleteRadio() {
    const all = Radio.createValue("ALL", "Alle eines Athleten");
    const sb = Radio.createValue("YEARATHLETE", "Bestes je Jahr und Athlet (SB)");
    const pb = Radio.createValue("ATHLETE", "Bestes eines Athleten (PB)");

    var values = { all, sb, pb };
    Radio.create(OUT.athleteResultsDiv, OUT.athleteResultsRadioName, values);
    Radio.selectDefault(OUT.athleteResultsRadioName, pb);
}


export function getAthleteValue() {
    return getSelectedRadioButton(OUT.athleteResultsRadioName);
}


export function createTeamRadio() {
    const all = Radio.createValue("ALL", "Alle eines Teams");
    const sb = Radio.createValue("YEARATHLETE", "Bestes je Jahr und Team (SB)");
    const pb = Radio.createValue("ATHLETE", "Bestes eines Team (PB)");

    var values = { all, sb, pb };
    Radio.create(OUT.teamResultsDiv, OUT.teamResultsRadioName, values);
    Radio.selectDefault(OUT.teamResultsRadioName, sb);
}

export function getTeamValue() {
    return getSelectedRadioButton(OUT.teamResultsRadioName);
}


