var net = require('net');
var md5 = require('./node_modules/blueimp-md5/js/md5').md5;
var tools = require('./routes/tools');
var HOST = '127.0.0.1';
var PORT = 8000;
var SECRET = 'laonie';
// 创建一个TCP服务器实例，调用listen函数开始监听指定端口
// 传入net.createServer()的回调函数将作为”connection“事件的处理函数
// 在每一个“connection”事件中，该回调函数接收到的socket对象是唯一的
net.createServer(function(sock) {
    // 为这个socket实例添加一个"data"事件处理函数
    sock.on('data', function(data) {
        var strData = data.toString();
        var strSign = strData.substr(0, 32);
        var strJson = strData.substr(32);
        if(md5(SECRET+strJson)==strSign){
            var obj = {
                ret:0,
                data:'OK'
            };
            if(sock.writable){
                //回传
                sock.write(JSON.stringify(obj));
            }
            sock.end();
        }else{
            console.log('invalid sign');
        }
    });

}).listen(PORT, HOST);

console.log('Server listening on ' + HOST +':'+ PORT);