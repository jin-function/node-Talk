<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<link rel="stylesheet" type="text/css" href="/STATIC/css/style.css"/>
<link rel="stylesheet" type="text/css" href="/STATIC/css/perfect-scrollbar.css">
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="/STATIC/Script/js/socket.io.js"></script>
<script type="text/javascript" src="/STATIC/Script/js/perfect-scrollbar.min.js"></script>
<style>
	ul {list-style: none;}
	textarea {min-height:100px; max-width: 100%; display: block; margin-bottom: 5px;}
	button { width: 100%; height: 40px; background: #3489ff; color: #fff; border: 0px solid #d9d9d9; cursor: pointer;}


	/* chat-box mode */
	#chat-box { width: 420px; margin:0 auto; padding: 15px; background: #ddd;}
	 
	#chat-box #room-list,
	#chat-box #messages { display: none; }

	#chat-box.rooms #room-list,
	#chat-box.join #messages { display: block; }

	#chat-box .Cheader { height: 50px; background: #36342f;  margin-bottom:5px; text-align: center; line-height: 25px; color:#fff;}
	#chat-box .Cbody   { height: 300px; background: #36342f; margin-bottom:5px; } 
	/*#chat-box .Cbody*/
	#chat-box .Cbody article,
	#chat-box .Cbody ul{ height: 100%; background: #fff;}
	#chat-box .Cbody #messages ul li:nth-child(odd){ background: #ddd; }
	#chat-box .Cbody #messages ul li:nth-child(even){ background: #999; }	
	#chat-box .Cbody #room-list ul li { line-height: 40px; padding:0px 15px; border-bottom: 1px solid #eee; cursor: pointer;} 
	#chat-box .Cbody #room-list ul li:hover { background: #eee;}

	#chat-box .Cfooter { height: 140px; background: #36342f; } 

</style>
<body>
<div id="chat-box" class="rooms" oncontextmenu="return false" ondragstart="return false" onselectstart="return false"> 

    <div class="Cheader">
        <h3> Node chat </h3>

        <div class="room-state">
            <ul>
                <li> 오픈 채팅 </li>
            </ul>
        </div>
    </div>

    <div class="Cbody ps-y-box">                     
        <article id="room-list">
            <ul data-mode="join">
            	<li> room1 </li>
            	<li> room2 </li>
            </ul>
        </article>
        <article id="messages">
        	<ul></ul>
        </article>                  
    </div>

    <div class="Cfooter">
        <form action="">
            <div class="msg-box">
                <textarea id="m" autocomplete="off"/> </textarea>
                <button> 전송 </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
<script type="text/javascript">
var url = 'http://192.168.1.31';
var port = 3535;
var socket = io.connect( url +':'+ port);

const my_name     = prompt('What your name');
var chat_box      = $('#chat-box');
var msg_box		  = $('#messages');
var msg 		  = ''; 


$(() => {
    // 기존 채팅방 선택 입장
    chat_box.find('li').click( function(){ 
    	var item  = $(this);
    	var mode  = item.parent('ul').data('mode');
    	chat_box.removeClass().addClass(mode);

    	if(mode == 'join'){
    		room_name = item.text();
	        socket.emit('JoinRoom', room_name, my_name);
	      	console.log('룸 선택 입장', room_name, my_name, mode);
    	}
    });

    // 메세지 전송
    chat_box.find('.Cfooter form').submit(() => {
    	msg = $('#m').val();
        socket.emit('chat message',my_name, msg);
        $('#m').val('');
        return false;
    });

    //메세지 출력
    socket.on('chat message', (name, msg) => {
    	msg_box.find('ul').append($('<li class="get_msg">').html('<div class="test-box"><p>'+name+':' + msg + '</p><span class="msg-time"> </span></div>'));
    });

    //퇴장 알림
    socket.on('leaveRoom', (data) => {
        msg_box.find('ul').append($('<li class="sys_msg">').html('<p><b>' + data + '</b> 님이 채팅방을 종료하였습니다. </p>'));
    });

    //입장 알림
    socket.on('JoinRoom', (data) => {
        msg_box.find('ul').append($('<li class="sys_msg">').html('<p><b>' + data + '</b> 님이 채팅방에 접속하였습니다.</p>'));        
    }); 

    //완전 퇴장 알림
    socket.on('deleteRoom', (data) => {
        msg_box.find('ul').append($('<li class="sys_msg">').html('<p><b>' + data + '</b> 님이 채팅방을 나갔습니다. </p>'));
    });

    //새로 입장 알림
    socket.on('createRoom', (data) => {
        msg_box.find('ul').append($('<li class="sys_msg">').html('<p><b>' + data + '</b> 님이 채팅방에 참여하였습니다.</p>'));        
    });          
});

</script>