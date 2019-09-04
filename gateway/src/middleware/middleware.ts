import express from "express";
import { MetaDataModel } from "../models/meta-data.model";

export class Middleware {

    constructor(request: express.Request, response: express.Response, metaData: MetaDataModel[]) {
        const url = request.originalUrl;
    }
    
}