const FaceDetectRepository = require('../repositories/face-detect.repository')

module.exports = class FaceDetect {
    constructor(app, database) {
        this.app = app;
        this.database = database;
        this.create()
    }

    create() {
        this.app.post('/api/face_detect', async (req, res) => {
            const repository = new FaceDetectRepository(this.database)
            await repository.open();
            const insert = await repository.insert(req.body);
            const face = await repository.get(insert.insertId);
            repository.close();
            res.send(face);
        });
    }
}