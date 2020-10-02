
export class ServerCom {
    /**
     * @param {string} filename
     */
    constructor(filename) {
        this.filename = filename;
    }

    /**
     * 
     * @param {object} params 
     * @param {function} successFunction 
     * @param {string} type "json" or "html" 
     */
    post(params, successFunction, type){
        $.post(this.filename, params, successFunction, type);
    }


}

