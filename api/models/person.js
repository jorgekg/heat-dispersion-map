module.exports = (sequelize, DataTypes) => {
    const Person = sequelize.define('People', {
        id: {
            allowNull: false,
            autoIncrement: true,
            primaryKey: true,
            type: DataTypes.BIGINT
        }
    });

    return Person;
}
