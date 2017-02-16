var mysql = require('mysql');
var setting = require("../config/mysql");
var connection;
if (setting.pool) {//连接池
    connection = mysql.createPool({
        host: setting.host,
        port: setting.port,
        database: setting.db_name,
        user: setting.username,
        password: setting.password
        //waitForConnections: true, //当连接池没有连接或超出最大限制时，设置为true且会把连接放入队列，设置为false会返回error
        //connectionLimit:10, //连接数限制，默认：10
        //queueLimit: 0 //最大连接请求队列限制，设置为0表示不限制，默认：0
    });
} else {
    connection = mysql.createConnection({//普通链接
        host: setting.host,
        port: setting.port,
        database: setting.db_name,
        user: setting.username,
        password: setting.password
    });
}


if(!setting.pool)connection.connect();

//connection.connect();
//var insertSql = 'insert into message(msg,created) values (?,?)';
//var insertData =['this is a insert test',timestamp];
//insert test
//mysql.query(insertSql,insertData, function (err, rs) {
//    if (err) throw err;
//    console.log(rs);
//});

//delete test
//var deleteSql = "delete from message where id=?";
//var deleteData = [1];
//mysql.query(deleteSql,deleteData, function (err, rs) {
//    if (err) throw err;
//    console.log(rs);
//});

//update test
//var updateSql = "update message set msg=? where id=?";
//var updateData = ['fuck test',2];
//mysql.query(updateSql,updateData, function (err, rs) {
//    if (err) throw err;
//    console.log(rs);
//});

//select test
//var selectSql = "select * from message";
//mysql.query(selectSql, function (err, rs) {
//    if (err) throw err;
//    var item;
//    for (item in rs){
//        console.log(rs[item]);
//    }
//});

module.exports = connection;
