import { Server } from './server/serve'
import express from "express";
import { Middleware } from './middleware/middleware';
import { MetaDataController } from './controllers/meta-data.controller';

new Server();

const metaData = new MetaDataController();
metaData.observableMetaData();

Server.server.use((request: express.Request, response: express.Response, next: any) => {
    new Middleware(request, response, metaData.getMetaData());
    next();
});