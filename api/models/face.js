module.exports = (sequelize, DataTypes) => {
    const Face = sequelize.define('Face', {
        id: {
            allowNull: false,
            autoIncrement: true,
            primaryKey: true,
            type: DataTypes.BIGINT
        }
    });

    return Face;
}
