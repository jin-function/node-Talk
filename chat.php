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
	.ps-y-box { overflow: hidden; position: relative; }
    .ps-y-box .ps__thumb-y { background-color: rgba(0, 0, 0, 0.4)!important; width: 8px!important; border-radius: 0px!important; 
    right: 0px!important;}
    .ps-y-box .ps .ps__rail-y { background-color: transparent!important; width: 10px!important;}

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
    #chat-box .Cbody { width: 100%; height: 420px; margin-bottom: 110px; margin-top: 70px; border-bottom: 1px solid; border-top: 1px solid; border-color:#4a4c51; }

	#chat-box .Cbody #room-list { width:100%; position: absolute; left: 0; top: 0; transition: 0.4s; background: #363636;}
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
    #chat-box .Cbody #room-list ul li p i { font-style: inherit; width: auto; height: 20px; display: block; position: absolute; right: 0; 
    	top: 0; margin: 5px 15px; text-align: center; background: #ff2f2f; line-height: 19px; border-radius: 10px; font-weight: 700;
    	padding: 0px 6px 0px 7px;}
    #chat-box .Cbody #room-list ul li img { margin: 10px; }
    #chat-box .Cbody #room-list ul li span { color:orange; }
    #chat-box .Cbody #room-list ul .test-box { padding-left: 60px; }
    #chat-box .Cbody #room-list ul.active {display: block;}

    #chat-box .Cbody  #messages { list-style-type: none; margin: 0; padding: 10px; background: #363636; }
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
var msg_box		= $('#messages ul');
var msg 		= ''; 	// 송.수신 메세지
var room_html	= ''; 
var join_room	= [];	// 각 채팅방 정보
var RoomData 	= [];	// 이미 참여한 채팅방 목록
var room_num	= 0;

var chatData 	= [];
var New_time 	= '';	// 메세지 송신
var Old_time 	= '';	// 메세지 수신

// AM,PM Format
function TimeFormat(){  //unix time change format
    var date = new Date();
    var year = date.getFullYear();
    var month = date.getMonth() + 1;
    var day = date.getDate();
    var hour = date.getHours();
    var min = date.getMinutes();
    var sec = date.getSeconds();  
    var hour_format = '12';
    var ext = '';

    if(hour_format == '12'){
        if(hour > 12){            
            ext = '오후';
            hour = (hour - 12);
            if(hour < 10){
                result = "0" + hour;
            }else if(hour == 12){
                hour = "00";
                ext = '오전';
            }
        }
        else if(hour < 12){
            result = ((hour < 10) ? "0" + hour : hour);
            ext = '오전';
        }else if(hour == 12){
            ext = '오후';
        }
    }
    if(min < 10){
        min = "0" + min; 
    }
    New_time = ext + ' ' + hour + ':' + min;
    return New_time;
}
TimeFormat(); 

//perfect-scroll
const ps = new PerfectScrollbar('.Cbody', {
    wheelSpeed: 0.2,
    wheelPropagation: true,
    minScrollbarLength: 5
});

//scroll position last line
function ScrollBottom(){
    Cbody_height = Cbody.prop('scrollHeight');
    Cbody.scrollTop(Cbody_height); 
}

// html 태그 문자열 변환
function Convert_Html(str){
    str = str.replace(/</g,"&lt;");
    str = str.replace(/>/g,"&gt;");
    str = str.replace(/\"/g,"&quot;");
    str = str.replace(/\'/g,"&#39;");
    str = str.replace(/\n/g,"<br />");
    return str;
}

//포커싱 이벤트
chat_box.find('#m').focus( function(){
    chat_box.find('.msg-box').addClass('on');
});
chat_box.find('#m').focusout( function(){
    msg = $(this).val();

    if(msg.length > 0){
        chat_box.find('.msg-box').addClass('on');
    } else {
        chat_box.find('.msg-box').removeClass('on');  
    }
});

// 입력 이벤트
chat_box.find('#m').on('keyup', function(){
    msg = $(this).val();

    if(msg.length > 0){
        chat_box.find('.msg-box').addClass('active');
        chat_box.find('.msg-box').find('button').attr('disabled', false);
    } else {
        chat_box.find('.msg-box').removeClass('active');
        chat_box.find('.msg-box').find('button').attr('disabled', true);
    }
});


/* 참여한 채팅방의 정보 */
$.getJSON("/chat/roomlist/"+my_name+"", "", function(data) { 
}).done(function(data) {
    for(var  i = 0; i < data.length; i++){      
        room_html = '<img> </img> <div class="test-box"><h5>'+ data[i].room_name +'</h5> <p> last msg </p> </div>';
        chat_box.find('#room-list ul').append($('<li id="room'+i+'" data-room="'+i+'">').html(room_html));
        join_room.push({'room': data[i].room_name, 'view':false, 'unconfirmed':0}); 
        room_html = '';
    }
    chat_box = $('#chat-box');  //동적으로 생성된 태그를 인지하기 위해 생성후 변수에 다시 저장한다.
    RoomData = data;		    //불러온 방의 정보를 기록한다.
    ChatApp(); 					//방정보를 비동기로 불러오기 때문에 소캣 연결시점을 명시해준다.
});




function ChatApp(){
	$(() => {
		var Sfirst_turn   = true;
        var Gfirst_turn   = true;

		// 모든 메세지를 전송 받기 위해 이전 참여 채팅방 모두에 자동 참가 한다.
        for(var i =0; i < RoomData.length; i++){
            socket.emit('JoinRoom', RoomData[i]);
        }

	    // 기존 채팅방 선택 입장
	    chat_box.find('li').click( function(){
	    	chat_box.find('.Cbody').scrollTop(0); 

	    	var item  = $(this);
	    	var mode  = item.parent('ul').data('mode');
	    	room_num = item.data('room');
	    	chat_box.removeClass().addClass(mode);
	    	Sfirst_turn   = true;
        	Gfirst_turn   = true;
            for(var i =0; i < join_room.length; i++){ //선택한 채팅방을 보고 있음을 저장한다.  
                join_room[i].view = false;  
            }

	    	if(mode == 'join'){ //room join mode
		    	join_room[room_num].view = true;
		    	join_room[room_num].unconfirmed = 0; //메세지 확인시 초기화.
		    	item.find('p i').remove();
        		socket.emit('chat Logs', RoomInfo[room_num]); // 해당 채팅방 로그를 가져온다.
		      	// console.log('룸 선택 입장', RoomData[i]);
	    	} else if (mode == 'rooms'){

	    	}

	    });

	    // 메세지 전송
	    chat_box.find('.Cfooter form').submit(() => {
	    	msg = chat_box.find('#m').val();
	        chat_box.find('#m').val('').focus();
	        socket.emit('chat message', RoomData[room_num], msg, New_time);
	        msg = '';
	        chat_box.find('.msg-box').addClass('on').removeClass('active');
            chat_box.find('.msg-box').attr('disabled', true);
	        return false;
	    });

	    //로그메세지 출력
        socket.on('chat Logs', function(rows){
            for(var i = rows.length-1; i >= 0; i--){
                // 이전 작성 메세지(limit 15)
                if(rows[i].name == my_name){
                    if(Sfirst_turn === true){
                      Old_time = rows[i].msg_time;
                      msg_box.append($('<li class="send_msg first">').html('<span class="msg-time">' + rows[i].msg_time + '</span><p>' + rows[i].msg + '</p>'));
                      Sfirst_turn = false;                 
                      Gfirst_turn = true;
                    } else {
                        if(Old_time != rows[i].msg_time){   
                          Old_time = rows[i].msg_time;      
                          msg_box.append($('<li class="send_msg first">').html('<span class="msg-time">' + rows[i].msg_time + '</span><p>' + rows[i].msg + '</p>'));
                        } else {
                          msg_box.find('li:last').find('.msg-time').remove();
                          msg_box.append($('<li class="send_msg">').html('<span class="msg-time">' + rows[i].msg_time + '</span><p>' + rows[i].msg + '</p>'));
                        }
                    }
                } else {
                    if(Gfirst_turn === true){
                        Old_time = rows[i].msg_time;
                        msg_box.append($('<li class="get_msg first">').html('<img/><div class="test-box"><h5>' + rows[i].name + '</h5><p>' + rows[i].msg + '</p><span class="msg-time">' + rows[i].msg_time + '</span></div>')); 
                        Gfirst_turn = false;         
                        Sfirst_turn = true;
                    } else {
                        if(Old_time != rows[i].msg_time){ 
                            Old_time = rows[i].msg_time;
                            msg_box.append($('<li class="get_msg first">').html('<img/><div class="test-box"><h5>' + rows[i].name + '</h5><p>' + rows[i].msg + '</p><span class="msg-time">' + rows[i].msg_time + '</span></div>')); 
                        } else {
                            msg_box.find('li:last').find('.msg-time').remove();
                            msg_box.append($('<li class="get_msg">').html('<div class="test-box"><p>' + rows[i].msg + '</p><span class="msg-time">' + rows[i].msg_time + '</span></div>'));
                        }
                    }
                }
            }
            ScrollBottom();
        });


	    //메세지 출력
	    socket.on('chat message', (data, msg, Old_time) => {
	    	msg = Convert_Html(msg);    
            TimeFormat();
            for(var i = 0; i < join_room.length; i++){
                if(join_room[i].room == data.room_name){
                    var room = data.room_num;
                    if(join_room[i].view == false){ //보고있는 채팅방과 전송받은 채팅방이 다를때
                        unconfirmed = join_room[i].unconfirmed;
                        unconfirmed++; //미확인 메세지가 전송될때마다 확인전까지 추가해준다.
                        join_room[i].unconfirmed = unconfirmed;
                        chat_box.find('li#room'+i+' h5').html(data.room_name + '<span>'+Old_time+'</span>');
                        chat_box.find('li#room'+i+' p').html(msg + '<i>'+ unconfirmed +'</i>');
                    } else {  // 전송 받은 메세지의 채팅방이 같을떄                          
                        if(data.user_name == my_name){
                            if(Sfirst_turn === true){
                              msg_box.append($('<li class="send_msg first">').html('<span class="msg-time">' + New_time + '</span><p>' + msg + '</p>'));
                              Sfirst_turn = false;                 
                              Gfirst_turn = true;
                            } else {
                                if(Old_time != New_time){          
                                  msg_box.append($('<li class="send_msg first">').html('<span class="msg-time">' + New_time + '</span><p>' + msg + '</p>'));
                                } else {
                                  msg_box.find('li:last').find('.msg-time').remove();
                                  msg_box.append($('<li class="send_msg">').html('<span class="msg-time">' + Old_time + '</span><p>' + msg + '</p>'));
                                }
                            }
                        } else {
                            if(Gfirst_turn === true){
                                msg_box.append($('<li class="get_msg first">').html('<img/><div class="test-box"><h5>' + data.user_name + '</h5><p>' + msg + '</p><span class="msg-time">' + New_time + '</span></div>')); 
                                Gfirst_turn = false;         
                                Sfirst_turn = true;
                            } else {
                                if(Old_time != New_time){ 
                                    msg_boxappend($('<li class="get_msg first">').html('<img/><div class="test-box"><h5>' + data.user_name + '</h5><p>' + msg + '</p><span class="msg-time">' + New_time + '</span></div>')); 
                                } else {
                                    msg_box.find('li:last').find('.msg-time').remove();
                                    msg_box.append($('<li class="get_msg">').html('<div class="test-box"><p>' + msg + '</p><span class="msg-time">' + Old_time + '</span></div>'));
                                }
                            }
                        }
                        ScrollBottom();
                    }
                }                  
            }
	    });


	    //퇴장 알림
	    socket.on('leaveRoom', (data) => {
	        msg_box.append($('<li class="sys_msg">').html('<p><b>' + data + '</b> 님이 채팅방을 종료하였습니다. </p>'));
	    });

	    //입장 알림
	    socket.on('JoinRoom', (data) => {
	        msg_box.append($('<li class="sys_msg">').html('<p><b>' + data + '</b> 님이 채팅방에 접속하였습니다.</p>'));        
	    }); 

	    //완전 퇴장 알림
	    socket.on('deleteRoom', (data) => {
	        msg_box.append($('<li class="sys_msg">').html('<p><b>' + data + '</b> 님이 채팅방을 나갔습니다. </p>'));
	    });

	    //새로 입장 알림
	    socket.on('createRoom', (data) => {
	        msg_box.append($('<li class="sys_msg">').html('<p><b>' + data + '</b> 님이 채팅방에 참여하였습니다.</p>'));        
	    });          
	});
}
</script>