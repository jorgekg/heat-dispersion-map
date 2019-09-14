const { Face } = require('../models');

module.exports = class FaceController {

    constructor(app) {
        this.app = app;
        this.getNextId();
        this.create();
        this.update();
    }

    getNextId() {
        this.app.get('/api/face_next', async (req, res) => {
            try {
                const face = await Face.findOne({
                    where: {
                        personId: null
                    }
                });
                res.send(face ? face : {});
            } catch (err) {
                res.send([]);
            }
        });
    }

    update() {
        this.app.put('/api/face/:id', async (req, res) => {
            try {
                const face = await Face.findOne(req.params.id);
                console.log(req.body);
                face.update({
                    personId: req.body.personId
                });
            } catch (err) {
                res.send([]);
            }
        });
    }

    create() {
        this.app.post('/api/face', async (req, res) => {
            try {
                const face = await Face.create(req.body);
                res.send(face.dataValues);
            } catch (err) {
                res.send([]);
            }
        });
    }
}