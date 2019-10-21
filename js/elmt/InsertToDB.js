import { ServerCom } from "./ServerCom.js"
import { insertFile } from "../config/serverFiles.js"

export class InsertToDB extends ServerCom {

    constructor() {
        super(insertFile);
    }

}