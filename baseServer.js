const fs = require('fs');
const http = require('http');
const https = require('https');
const socket = require( 'socket.io' );
const express = require('express');
const app = express();


/* DB 연결 */
var mysql      = require('mysql');
var connection = mysql.createConnection({
    host     : '192.168.1.31',
    user     : 'name',
    password : 'pass',
    port     : 3306,
    database : 'db'
});
connection.connect(); //메세지는 계속 항상 받는다.

let ALL_room  = []; // 전체 채팅방
let join_user = [];

//전체 채팅방 목록
connection.query('SELECT room_num, count(*) AS joiner, room_state, room_name FROM chat_rooms GROUP BY room_num, room_state, room_name', function(err, rows, fields) {
    if (err){
        console.log('Error while performing Query.', err);
    } else {
        for(var i = 0; i < rows; i++){
            ALL_room.push({"room_num" : rows[i].room_num, "room_state" : rows[i].room_state, "room_name" : rows[i].room_name, 'user_name': ''});
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
const io = socket.listen( httpServer );

let room = '';

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
            } else {
                io.in(socket.id).emit('chat Logs', rows);
            }
        });
    });

    // 방 검색
    socket.on('find Room', () => {
    	io.in(socket.id).emit('find Room', ALL_room);
    });

    // 검색 룸 접속
    socket.on('first join', (data) => {
    	io.in(socket.id).emit('first join');
    	var room_num  = connection.escape(parseInt(data.room_num));
        var type      = connection.escape(data.room_state);
    	var room_name = connection.escape(data.room_name);
        var user_name = connection.escape(data.user_name);
        
        // log 저장
        var query = "INSERT INTO chat_rooms (room_num, room_state, room_name, user_name) VALUES ("+room_num+","+type+","+room_name+","+user_name+")";        
        connection.query(query, function(err){
            if(err){
                console.log(err , 'fail');
            } else {
                console.log(data , 'sussecs');
            }
        });
    });

    //채팅방 입장
    socket.on('JoinRoom', (data) => {
    	room = data.room_state + data.room_name;
        socket.join(room, () => {//room join 
	        io.in(room).emit('JoinRoom', data.user_name);   // 입장유저 알림
	    	console.log('user ' + data.user_name +' enters the '+ room +' room');
	    });
    });

    //퇴장
    socket.on('leaveRoom', (data) => {
      	socket.leave(room, () => {
        	io.in(room).emit('leaveRoom', data); // 퇴장유저 알림
      		console.log('leave user' + socket.id);
      	});
    });

    //전송받은 메세지 
    socket.on('chat message', (data, msg, Old_time) => {
        io.in(room).emit('chat message', data, msg, Old_time);  //참여room 모두에게 메세지 전송

        var room_num  = connection.escape(parseInt(data.room_num));
        var user_name = connection.escape(data.user_name);
        var message   = connection.escape(msg);
        var type      = connection.escape(data.room_state);
        var msg_time  = connection.escape(Old_time);
        
        // log 저장
        var query = "INSERT INTO chat_logs (room, room_type, name, msg, msg_time) VALUES ("+room_num+","+type+", "+user_name+", "+message+", "+msg_time+")";        
        connection.query(query, function(err){
            if(err){
                console.log(err , 'fail');
            } else {
                console.log(data, 'sussecs');
            }
        });
    });
});


httpServer.listen(3535, () => { //포트 연결 확인
    console.log('HTTP Server running on port 3535');
});