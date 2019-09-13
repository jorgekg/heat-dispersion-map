module.exports = class FaceDetectRepository {
    constructor(database) {
        this.database = database;
        this.conn = null;
    }

    async open() {
        this.conn = await this.database.getConnection();
    }

    close() {
        this.conn.close();
    }

    async get(id) {
        try {
            return await this.conn.query("SELECT * FROM face_detect WHERE id = ?", id);
        } catch (err) {
            if (this.conn) {
                this.conn.end();
            }
            throw err;
        }
    }

    async insert() {
        try {
            return await this.conn.query("INSERT INTO face_detect (name) values('image')");
        } catch (err) {
            if (this.conn) {
                this.conn.end();
            }
            throw err;
        }
    }
}