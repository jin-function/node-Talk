
const my_name   	= prompt('What your name');
var chat_box   		= $('#chat-box');
var msg_box			= $('#messages ul');
var msg 			= ''; 	// 송.수신 메세지
var room_html		= ''; 
var join_room		= [];	// 각 채팅방 정보
var RoomData 		= [];	// 이미 참여한 채팅방 목록
var findRoomData	= [];	// 검색한 채팅방 목록
var room_num	 	= 0;

var chatData 		= [];
var New_time 		= '';	// 메세지 송신
var Old_time 		= '';	// 메세지 수신
var msg_count       = 0;

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
    Cbody_height = chat_box.find('.Cbody').prop('scrollHeight');
    chat_box.find('.Cbody').scrollTop(Cbody_height); 
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


function JoinRoom(data, i){
    room_html = '<img> </img> <div class="test-box"><h5>'+ data.room_name +'</h5> <p> last msg </p> </div>';
    chat_box.find('#room-list ul').append($('<li id="room'+i+'" data-room="'+i+'">').html(room_html));
    join_room.push({'room': data.room_name, 'view':false, 'unconfirmed':0}); 
    room_html = '';
}

function findRoom(data){
	findRoomData = data;
	if(RoomData){
		for(var i =0; i < RoomData.length; i++){
			for(var j = 0; j < data.length; j++){
				var my_room 	= RoomData[i].room_state + RoomData[i].room_name; 
				var find_room	= data[j].room_state + data[j].room_name;
				if(my_room == find_room){
					findRoomData.splice(j, 1);
				} 
			}
		}
	}
	for(var i = 0; i < findRoomData.length; i++){
		findRoomData[i].user_name = my_name;
		room_html = '<img> </img> <div class="test-box"><h5>'+ findRoomData[i].room_name +'</h5></div>';
		chat_box.find('#search ul').append($('<li id="search'+i+'" data-room="'+i+'" class="show">').html(room_html));
	}	
}


/* 참여한 채팅방의 정보 */
$.getJSON("/chat/roomlist/"+my_name+"", "", function(data) { 
}).done(function(data) {
    for(var  i = 0; i < data.length; i++){  
        var roominfo =  data[i]; 
        JoinRoom(roominfo, i);
    }
    RoomData = data;            //불러온 방의 정보를 기록한다.
    ChatApp();                  //방정보를 비동기로 불러오기 때문에 소캣 연결시점을 명시해준다.

    var row =  RoomData.length;
    if(row == 0){ //참여 정보가 없을경우 검색페이지
        chat_box.removeClass().addClass('search');
    }

    var row2 = $('#search .move li');
    if(row2.length == 0){ //방이 없을 경우
        $('.coment').html('<p><i class="fas fa-exclamation-circle"></i> 참여 가능한 채팅방이 없습니다. </p>');
    }
});


function ChatApp(){
	$(() => {
		var Sfirst_turn   = true;
        var Gfirst_turn   = true;

		// 모든 메세지를 전송 받기 위해 이전 참여 채팅방 모두에 자동 참가 한다.
        for(var i =0; i < RoomData.length; i++){
            socket.emit('JoinRoom', RoomData[i]);
        }

        //미 참여 방목록을 보여준다.
	    socket.emit('find Room');
	    socket.on('find Room', function(rows){
	    	findRoom(rows);
	    });

        //검색 목록을 보여준다.
        $('input[name=keyword]').keyup(function(){
            var k = $(this).val();
            var item = $("#search > ul");
            var temp = item.find("li > div > h5:contains('"+ k +"')");
            item.find("li").removeClass('show');
            $(temp).parents('li').addClass('show');

            var li =  item.find("li.show");
            var row = li.length;
            var coment = $('.coment');
            if(row == 0){
                item.find("li").removeClass('show');
                coment.html('<p><i class="fas fa-exclamation-circle"></i> 검색 결과가 없습니다. </p>');
            } else if( k == 0 ){
                item.find("li").addClass('show');
                coment.html('');
            } else {
                coment.html('');
            }
        });

	    
	    // 기존 채팅방 선택 입장 - document지정은 동적 생성 요소를 인지하기 위함.
	    $(document).on("click", "#chat-box .move li", function(){
	    	chat_box.find('.Cbody').scrollTop(0); 
	    	var item  = $(this);
	    	var mode  = item.parent('ul').data('mode');
            var title = '';
            room_num = item.data('room');
            chat_box.removeClass().addClass(mode);
            Sfirst_turn   = true;
            Gfirst_turn   = true;
            msg_box.html(''); // 이전 채팅 내용 초기화.
            for(var i =0; i < join_room.length; i++){ //선택한 채팅방을 보고 있음을 저장한다.  
                join_room[i].view = false;  
            }

	    	if(mode == 'join'){ //room join mode
		    	join_room[room_num].view = true;
		    	join_room[room_num].unconfirmed = 0; //메세지 확인시 초기화.
		    	var num = item.find('p i').text();
                item.find('p i').remove();
                msg_count -= parseInt(num);
                socket.emit('chat Logs', RoomData[room_num]); // 해당 채팅방 로그를 가져온다.
                // console.log('룸 선택 입장', RoomData[i]);
                title = RoomData[room_num].room_name;
                chat_box.find('h3.title').text(title);
                if(msg_count > 0){    
                    chat_box.find('.room-state ul:nth-child(2) li i').html('<i class="ball">'+ msg_count +'</i>');
                } else {
                    chat_box.find('.room-state ul:nth-child(2) li i').html('');
                }
	    	} else if (mode == 'rooms' || mode == 'search'){
                chat_box.find('h3.title').text(' Node Chat ');
	    	} else if (mode == 'searchJoin'){
	    		socket.emit('JoinRoom', findRoomData[room_num]); //검색 방에 접속.
	    		socket.emit('chat Logs', findRoomData[room_num]); // 해당 채팅방 로그를 가져온다.
	    		socket.emit('first join', findRoomData[room_num]);
	    		RoomData.push(findRoomData[room_num]);
                title = findRoomData[room_num].room_name;
                chat_box.find('h3.title').text(title);
                item.remove(); // 방 목록에서 제거
	    	}
	    });

	    // 새로운 방 참여
	    socket.on('first join', function(){
            var i = RoomData.length; 
	    	JoinRoom(findRoomData[room_num], i-1);
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
            msg_count = 0;
            for(var i = 0; i < join_room.length; i++){
                if(join_room[i].room == data.room_name){
                    var room = data.room_num;
                    if(join_room[i].view == false){ //보고있는 채팅방과 전송받은 채팅방이 다를때
                        unconfirmed = join_room[i].unconfirmed;
                        unconfirmed++; //미확인 메세지가 전송될때마다 확인전까지 추가해준다.
                        join_room[i].unconfirmed = unconfirmed;
                        chat_box.find('li#room'+i+' h5').html(data.room_name + '<span>'+Old_time+'</span>'); //목록에 현재 상태를 업데이트 해준다.
                        chat_box.find('li#room'+i+' p').html(msg + '<i class="ball">'+ unconfirmed +'</i>');
                    } else {  // 전송 받은 메세지의 채팅방이 같을떄
                        chat_box.find('li#room'+i+' h5').html(data.room_name + '<span>'+Old_time+'</span>');
                        chat_box.find('li#room'+i+' p').html(msg);                          
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
                msg_count += join_room[i].unconfirmed;
            }
            // nav all msg ball
            if(msg_count > 0){
                console.log(msg_count);
                chat_box.find('.room-state ul:nth-child(2) li i').html('<i class="ball">'+ msg_count +'</i>');
            }
	    });


	    //퇴장 알림
	    socket.on('leaveRoom', (data) => {
	        msg_box.append($('<li class="sys_msg">').html('<p><b>' + data + '</b> 님이 채팅방을 종료하였습니다. </p>'));
	        ScrollBottom();
	    });

	    //입장 알림
	    socket.on('JoinRoom', (data) => {
	    	if(data != my_name){
	        	msg_box.append($('<li class="sys_msg">').html('<p><b>' + data + '</b> 님이 채팅방에 접속하였습니다.</p>'));        
	    	}
	    	ScrollBottom();
	    }); 

	    //완전 퇴장 알림
	    socket.on('deleteRoom', (data) => {
	        msg_box.append($('<li class="sys_msg">').html('<p><b>' + data + '</b> 님이 채팅방을 나갔습니다. </p>'));
	        ScrollBottom();
	    });

	    //새로 입장 알림
	    socket.on('createRoom', (data) => {
	    	if(data != my_name){
	        	msg_box.append($('<li class="sys_msg">').html('<p><b>' + data + '</b> 님이 채팅방에 참여하였습니다.</p>'));
	        }
	        ScrollBottom();       
	    });          
	});
}