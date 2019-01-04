const fs = require('fs');
const http = require('http');
const https = require('https');
const socket = require( 'socket.io' );
const express = require('express');
const app = express();

app.get('/', function (req, res) {
    res.setHeader('Access-Control-Allow-Origin', 'http://192.168.1.31');
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, PATCH, DELETE');
    res.setHeader('Access-Control-Allow-Headers', 'X-Requested-With,content-type');
    res.setHeader('Access-Control-Allow-Credentials', true);
    res.send('hello')
})

const httpServer = http.createServer(app);
var io = socket.listen( httpServer );

var room = '';

//소캣 연결
io.on('connection', (socket) => {

	//연결 끊김
    socket.on('disconnect', () => { 
        console.log('user disconnected', socket.id);
    });

    //채팅방 입장
    socket.on('JoinRoom', (data, name) => {
    	room = data;
        socket.join(room, () => {//room join 
	        // 채팅방 입장유저 알림
	        io.in(room).emit('JoinRoom', name);
	    	console.log('join user' + name + 'room' + room);
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
    socket.on('chat message', (name, msg) => {
        //참여room 모두에게 메세지 전송
        io.in(room).emit('chat message', name, msg);  
    });
});


httpServer.listen(3535, () => {
    console.log('HTTP Server running on port 3535');
});