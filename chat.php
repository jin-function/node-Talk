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
	textarea { min-height:100px; max-width: 100%; display: block;}
	ul li img { background: url(../STATIC/img/profile.png) no-repeat -1px -2px; width: 40px; height: 40px; position: absolute; top: 0; left: 0; background-size: auto; border-radius: 50%; border: 1px solid #ddd;}
    ul li .test-box { padding-left: 45px; }
    ul li .test-box p {}
    ul li .test-box h5 {}

	/*#chat-box mode */
	/* chat box */
    #chat-box { width: 350px; position: relative; border:1px solid; border-radius: 3px; border-color:#2f3030; background: #363636;
      margin: 0 auto; font-size: 12px; overflow: hidden; color:#fff;}
	 
	#chat-box #room-list,
	#chat-box #messages { display: none; }

	#chat-box.rooms .Cbody #room-list,	#chat-box.rooms .Cbody #room-list ul, 
	#chat-box.join .Cbody #messages, #chat-box.join .Cbody #messages ul { display: block; }

    #chat-box .Cheader  { width: 100%; height: 70px; position: absolute; top: 0; left: 0; padding: 10px; 
      background: rgba(0, 0, 0, 0.4); box-shadow: 0px 2px 5px #262626; z-index: 5;}
    /* state */
    #chat-box .Cheader .room-state { font-weight: 700; font-size: 17px; }
    #chat-box .Cheader .room-state ul { position: absolute; bottom: 0; padding: 5px 0px;}
    #chat-box .Cheader .room-state ul li { display: inline-block; padding: 5px; position: relative; margin-right: 10px; 
      color:#9b9b9b; cursor: pointer}
    #chat-box .Cheader .room-state ul li:before{content: ""; position: absolute; background: #9b9b9b; left: 45%; height: 2px; width: 0%; 
     transition: all .3s; opacity: 0.7; bottom: 0px;}
    #chat-box .Cheader .room-state ul li.active,
    #chat-box .Cheader .room-state ul li:hover {color:#ffb01f;}
    #chat-box .Cheader .room-state ul li.active:before,
    #chat-box .Cheader .room-state ul li:hover:before { left: 0px; width: 100%; background:#ffb01f;}


	/*#chat-box .Cbody*/
    #chat-box .Cbody { width: 100%; height: 420px; margin-bottom: 110px; margin-top: 70px; border-bottom: 1px solid; border-top: 1px solid; border-color:#4a4c51; position: relative;}

	#chat-box .Cbody article,
	#chat-box .Cbody ul{ height: 100%;}

	#chat-box .Cbody #room-list { width:100%; position: absolute; left: 0; top: 0; transition: 0.4s; background: #363636; z-index: 9999;}
    #chat-box .Cbody #room-list ul li { position: relative; padding: 10px 0px; cursor: pointer; border-top:1px solid transparent;}
    #chat-box .Cbody #room-list ul li:hover { background: #474747; border-top: 1px solid #626262;}
	room-list { width:100%; position: absolute; left: 0; top: 0; transition: 0.4s; background: #363636; z-index: 9999;}
    #chat-box .Cbody #room-list ul {width:100%; /*padding: 10px 0px;*/ display: none;}
    #chat-box .Cbody #room-list ul li { position: relative; padding: 10px 0px; cursor: pointer; border-top:1px solid transparent;}
    #chat-box .Cbody #room-list ul li:hover { background: #474747; border-top: 1px solid #626262;}
    #chat-box .Cbody #room-list ul li * { font-family: auto; }
    #chat-box .Cbody #room-list ul li h5 { font-size: 12px; position: relative; white-space: nowrap; overflow: hidden;
        text-overflow: ellipsis; padding-right: 100px;}
    #chat-box .Cbody #room-list ul li h5 span { color: #999; position: absolute; right: 10px; font-weight: 300; bottom: 0; }
    #chat-box .Cbody #room-list ul li p { position: relative; padding-top: 5px; white-space: nowrap; height: 30px; overflow: hidden; 
        text-overflow: ellipsis; padding-right: 100px;}
    #chat-box .Cbody #room-list ul li p i { font-style: inherit; width: 20px; height: 20px; display: block; position: absolute; right: 0; top: 0; margin: 5px 15px; text-align: center; background: #ff2f2f; line-height: 19px; border-radius: 10px; font-weight: 700;}
    #chat-box .Cbody #room-list ul li img { margin: 10px; }
    #chat-box .Cbody #room-list ul li span { color:orange; }
    #chat-box .Cbody #room-list ul .test-box { padding-left: 60px; }
    #chat-box .Cbody #room-list ul.active {display: block;}

    #chat-box .Cbody  #messages {
      list-style-type: none; margin: 0; padding: 10px; background: #363636;
    }
    #chat-box .Cbody #messages li { position: relative; font-family: auto; font-size: 11px; }
    
    #chat-box .Cbody #messages li span {vertical-align: bottom;}
    #chat-box .Cbody #messages li p {margin: 4px 10px; padding: 7px; border-radius:3px; background: #aaa; max-width: 60%; display: inline-block; color: #000; font-family: 돋움; position: relative; box-shadow: 0px 0px 8px #000000; text-align: left; 
    	word-break:break-all; white-space: pre-line;}
    #chat-box .Cbody #messages li.first p { margin-top:10px; }
    #chat-box .Cbody #messages li.first p:before { content: ''; position: absolute; top: 5px;
   		border-top: 0px solid transparent; border-bottom: 9px solid transparent; }
    #chat-box .Cbody #messages li.send_msg.first p:before { border-right: 0px solid transparent; border-left: 9px solid #ebce1b; 
    	right: -9px; }
    #chat-box .Cbody #messages li.get_msg.first p:before { border-right: 9px solid #fff; border-left: 0px solid transparent; left: -9px; }
    #chat-box .Cbody #messages li.get_msg.first .test-box {margin-top: 10px;}
	 /* send msg */
    #chat-box .Cbody #messages li.send_msg { text-align: right; }
    #chat-box .Cbody #messages li.send_msg p {background: #ebce1b; /*#ffdf1b*/}
	 /* get msg */
    #chat-box .Cbody #messages li.get_msg { text-align: left;}
    #chat-box .Cbody #messages li.get_msg .test-box p { background: #fff; max-width: 70%;}
    #chat-box .Cbody #messages li.get_msg .test-box h5 {padding-left: 10px; font-size:1em; font-weight: 300; font-family: 돋움;}
	 /* sys msg */
    #chat-box .Cbody #messages li.sys_msg { text-align: center; }
    #chat-box .Cbody #messages li.sys_msg p{ padding:5px; margin:5px 0px; color: #9398aa; background: rgba(0, 0, 0, 0.4); min-width: 100%;
      box-shadow: none; text-align: center;}
    #chat-box .Cbody #messages li.sys_msg p b{ color:orange; }


	#chat-box .Cfooter { width: 100%; height: 110px; position: absolute; bottom: 0; background: #202020; padding: 12px; background:#fff;} 
	#chat-box .Cfooter form { width: 100%; /*height: 100%;*/}
    #chat-box .Cfooter form .msg-box { /*height: 40px;*/ position: relative; }
    #chat-box .Cfooter form .msg-box textarea { resize: none; border: 0; min-height: 70px; padding: 10px; width: 80%; 
      margin-right: 10px; outline: 0;}
    #chat-box .Cfooter form .msg-box button { position: absolute; top:0; right: 0; width: 50px; padding: 6px 8px;
      border: 1px solid #ccc; border-radius: 3px; color: #ccc; background: #eee; }
    #chat-box .Cfooter form .msg-box.on button { background: #ffe022; color: #c3b245; border-color:#c3b245; }
    #chat-box .Cfooter form .msg-box.on.active button { color:#000; cursor: pointer;}

</style>
<body>
<div id="chat-box" class="rooms" oncontextmenu="return false" ondragstart="return false" onselectstart="return false"> 

    <div class="Cheader">
        <h3> Node chat </h3>

        <div class="room-state">
            <ul data-mode="rooms">
                <li> Talk </li>
            </ul>
        </div>
    </div>

    <div class="Cbody ps-y-box">                     
        <article id="room-list">
            <ul data-mode="join"></ul>
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

const my_name   = prompt('What your name');
var chat_box    = $('#chat-box');
var msg_box		= $('#messages');
var msg 		= ''; 
var room_html	= ''; 
var join_room	= [];
var RoomData 	= [];

/* 참여한 채팅방의 정보 */
$.getJSON("/chat/roomlist/"+my_name+"", "", function(data) { 
}).done(function(data) {
    for(var  i = 0; i < data.length; i++){      
        room_html = '<img> </img> <div class="test-box"><h5>'+ data[i].room_name +'</h5> <p> last msg </p> </div>';
        chat_box.find('#room-list ul').append($('<li id="room'+ data[i].room_num +'" data-room="'+i+'">').html(room_html));
        join_room.push({'room': data[i].room_name, 'view':false, 'unconfirmed':0});
        room_html = '';
    }
    chat_box = $('#chat-box'); //동적으로 생성된 태그를 인지하기 위해 생성후 변수에 다시 저장한다.
    RoomData = data;
    ChatApp();
});

function ChatApp(){
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
}
</script>