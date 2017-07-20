module.exports = function(err,connection){
    connection.query( 'SELECT * FROM message;', function(err, result) {
        console.log(result);
        connection.release();
    });
};
