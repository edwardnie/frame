// Keep track of which names are used so that there are no duplicates
var allSockets = [];
var userNames = (function () {
    var getUserList = function () {
        var names = [];
        var length = allSockets.length;
        for (var i = 0; i < length; i++) {
            names.push(allSockets[i].name);
        }
        return names;
    };

    var checkIn = function (name) {
        for (var i = 0; i < allSockets.length; i++) {
            if (allSockets[i].name == name) return true;
        }
        return false;
    };

    var free = function (id) {
        var i;
        for (i = 0; i < allSockets.length; i++) {
            if (allSockets[i].id == id) {
                allSockets.splice(i, 1);
            }
        }
    };

    var getUser = function (id) {
        var i, user=[];
        for (i = 0; i < allSockets.length; i++) {
            if (allSockets[i].id == id) {
                user = allSockets[i];
                break;
            }
        }
        return user;
    };


    var getUserIndex = function(name){
        var index;
        for (var i = 0; i < allSockets.length; i++) {
            if (allSockets[i].name == name) {
                index = i;
                break;
            }
        }
        return index;
    };

    return {
        free: free,
        getUserList: getUserList,
        checkIn: checkIn,
        getUser: getUser,
        getUserIndex:getUserIndex
    };
}());
//var mysql = require('./mysql');
var cookie = require('./tools');

module.exports = function (socket) {
    socket.on('user:login', function (data, fn) {
        if (!userNames.checkIn(data.user)) {
            var user = {
                socket: socket,
                name: data.user,
                id:socket.id
            };
            allSockets.push(user);

            socket.emit('init', {
                name: data.user,
                users: userNames.getUserList()
            });
            socket.broadcast.emit('user:join', {
                name: data.user
            });
            fn(true);
        } else {
            fn(false);
        }
    });

    // broadcast a user's message to other users
    socket.on('send:message', function (data) {
        socket.broadcast.emit('send:message', {
            user: data.user,
            txt: data.txt
        });
        //var insertSql = 'insert into message(msg,created) values (?,?)';
        //var insertData = [data.txt, 0];
        //mysql.query(insertSql, insertData, function (err, rs) {
        //    if (err) throw err;
        //    console.log(rs);
        //});
    });

    socket.on('send:private_message', function (data) {
        var index = userNames.getUserIndex(data.to_user);
        console.log(data,index);
        allSockets[index].socket.emit('send:private_message', {
            user: data.user,
            txt: data.txt
        });
    });


    // clean up when a user leaves, and broadcast it to other users
    socket.on('disconnect', function () {
        var user = userNames.getUser(socket.id);
        socket.broadcast.emit('user:left', {
            name: user.name
        });
        userNames.free(socket.id);
    });

};
