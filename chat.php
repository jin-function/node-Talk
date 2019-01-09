<!DOCTYPE html>
<html>
<head>
	<title> Chat app </title>
    <link rel="stylesheet" type="text/css" href="/STATIC/css/style.css"/>
    <link rel="stylesheet" type="text/css" href="/STATIC/css/perfect-scrollbar.css">
    <link rel="stylesheet" type="text/css" href="/STATIC/css/chat.css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script type="text/javascript" src="/STATIC/Script/js/socket.io.js"></script>
	<script type="text/javascript" src="/STATIC/Script/js/perfect-scrollbar.min.js"></script>
</head>

<body>
<div id="chat-box" class="rooms" oncontextmenu="return false" ondragstart="return false" onselectstart="return false"> 

    <div class="Cheader">
        <h3 class="title"> Node Talk </h3>

        <div class="room-state">
            <ul class="move" data-mode="search">
            	<li><i class="fa fa-search"></i></li>
            </ul>
            <ul class="move" data-mode="rooms">
                <li><i class="fa fa-comment"></i></li>
            </ul>
        </div>
    </div>

    <div class="Cbody ps-y-box">                     
        <article id="search" class="rooms">
        	<div class="search-box">
        		<i class="fa fa-search"></i>
        		<input type="text" name="keyword" placeholder="방 이름">
        		<!-- <button> 검색 </button> -->
        	</div>
        	<div class="desc"> <p><span>채팅방</span></p> <hr></div>
            <ul class="move" data-mode="searchJoin">
                <div class="coment"></div>
            </ul>
        </article>
        <article id="room-list" class="rooms">
            <ul class="move" data-mode="join"></ul>
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
</script>
<script type="text/javascript" src="/STATIC/Script/chat/app.js"></script>