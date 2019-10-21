import { ServerCom } from "./ServerCom.js"
import { existingEntriesFile } from "../config/serverFiles.js"

export class ExistingEntries extends ServerCom {

    constructor() {
        super(existingEntriesFile);
    }

}