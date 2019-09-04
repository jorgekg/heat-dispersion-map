import { Server } from "../server/serve";
import express from "express";
import { MetaDataModel } from "../models/meta-data.model";

export class MetaDataController {

    private metaDatas: MetaDataModel[] = [];

    constructor() { }

    public observableMetaData() {
        Server.server.post('/api/meta_data', (request: express.Request, response: express.Response) => {
            const data = request.body as MetaDataModel;
            this.metaDatas.push(data);
            response.send();
        })
    }

    public getMetaData(): MetaDataModel[] {
        return this.metaDatas;
    }

}