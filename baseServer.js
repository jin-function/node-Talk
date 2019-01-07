const fs = require('fs');
const http = require('http');
const https = require('https');
const socket = require( 'socket.io' );
const express = require('express');
const app = express();

/* DB 연결 */
var mysql      = require('mysql');
var connection = mysql.createConnection({
    host     : 'localhost',
    user     : 'root',
    password : 'password',
    port     : 3306,
    database : 'database'
});
connection.connect(); //메세지는 계속 항상 받는다.

let ALL_room  = []; // 전체 채팅방
let join_user = [];

//전체 채팅방 목록
connection.query('SELECT room_num, count(*) AS joiner, room_state, room_name FROM chat_rooms GROUP BY room_num, room_state, room_name', function(err, rows, fields) {
    if (err){
        console.log('Error while performing Query.', err);
        throw err;
    } else {
        for(var i = 0; i < rows.length; i++){
            ALL_room.push({"state" : rows[i].room_state, "room" : rows[i].room_name, "num" : rows[i].room_num});
            console.log(ALL_room);
        }
    }
});

app.get('/', function (req, res) {
    res.setHeader('Access-Control-Allow-Origin', 'http://192.168.1.31');
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, PATCH, DELETE');
    res.setHeader('Access-Control-Allow-Headers', 'X-Requested-With,content-type');
    res.setHeader('Access-Control-Allow-Credentials', true);
    res.send('hello');
});

const httpServer = http.createServer(app);
var io = socket.listen( httpServer );

var room = '';

//소캣 연결
io.on('connection', (socket) => {

	//연결 끊김
    socket.on('disconnect', () => { 
        console.log('user disconnected', socket.id);
    });

    socket.on('chat Logs', (data) => {
        //이전 작성된 메세지. 
        var query = "SELECT * FROM chat_logs WHERE room = "+ data.room_num +" AND room_type = '"+ data.room_state +"' ORDER BY id DESC LIMIT 15";
        connection.query(query , function(err, rows, fields){
            if (err){
                console.log('Error while performing Query.', err);
                throw err;
            } else {
                io.in(socket.id).emit('chat Logs', rows);
            }
        });
    });

    //채팅방 입장
    socket.on('JoinRoom', (data) => {
    	room = data.room_state + data.room_name;
        socket.join(room, () => {//room join 
	        // 채팅방 입장유저 알림
	        io.in(room).emit('JoinRoom', data.user_name);
	    	console.log('user ' + data.user_name +' enters the '+ room +' room');
	    });
    });

    //퇴장
    socket.on('leaveRoom', (data) => {
      	socket.leave(room, () => {
      		// 퇴장유저 알림
      		console.log('leave user' + socket.id);
        	io.in(room).emit('leaveRoom', data);
      	});
    });

    //전송받은 메세지 
    socket.on('chat message', (data, msg, Old_time) => {
        //참여room 모두에게 메세지 전송
        io.in(room).emit('chat message', data, msg, Old_time);  
    });
});


httpServer.listen(3535, () => {
    console.log('HTTP Server running on port 3535');
});