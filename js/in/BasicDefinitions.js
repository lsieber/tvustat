import { addValueToArrayStorage } from "./SessionStorageHandler.js";
import * as STORE from "../config/storageNames.js";

export function loadBasicData(phpFile) {
    $.post(phpFile, {}, function (data) {
        window.sessionStorage[STORE.definitionsStore] = data;
    });
}
