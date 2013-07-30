<html>
    <head>
    	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" >
    	<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.3.min.js"></script>
       	<script type="text/javascript" src="http://dev.junaio.com/arel/js/arel.js"></script>
    	<script type="text/javascript" src="arelGLUE4.js"></script>
    	<script type="text/javascript">
    		var amountOfCards = <?php echo $_GET['amntCards']; ?>    	
    	</script>
    	<link href="style.css" rel="stylesheet" type="text/css" />
    	<title>First Arel</title>
    </head>
	<body style="background-color:transparent">
		<div class="infoPlayer1"><img src="images/music-memory-01.png" width="100%" /></div>
		<div class="infoPlayer2" style="display: none;"><img src="images/music-memory-02.png" width="100%" /></div>
		<div class="player1">
			<div class="scorePlayer1">Score: 0</div>
			<div class="turnsPlayer1">Turn : 0</div>
		</div>
		<div class="player2">
			<div class="scorePlayer2">Score: 0</div>
			<div class="turnsPlayer2">Turn : 0</div>
		</div>
		<div class="result" style="display: none;"></div>
		<div class="restart" style="display: none;" ontouchstart="restart(this)" ontouchend="style.backgroundColor='#333'">Start Again</div>
		<div class="info" style="display: none;">Please hold your phone over the music pattern to get started.<br /><br /><img src="images/pattern.jpg" /></div>		
	</body>            
</html>