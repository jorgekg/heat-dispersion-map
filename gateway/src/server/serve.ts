import express from "express";

export class Server {

    public static server: any;

    constructor() {
        if (!Server.server) {
            Server.server = express();
        }
    }

}