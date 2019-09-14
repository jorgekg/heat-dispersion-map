const { Person } = require('../models');
const { Face } = require('../models');

module.exports = class PersonController {

    constructor(app) {
        this.app = app;
        this.create();
    }

    create() {
        this.app.post('/api/people/:faceId', async (req, res) => {
            try {
                const person = await Person.create(req.body);
                const face = await Face.findOnde(req.params.faceId);
                face.update({
                    personId: person.dataValues.id
                });
                res.send(person.dataValues);
            } catch (err) {
                res.send([]);
            }
        });
    }

}