<?php
    $var = $this->data;
    foreach ($var as $name => $value) {
        ${$name}=$value;
    }
    // var_dump($var["room"]["level_id"]);
    $game = new GameController();
    $map = $game->get_map($room['level_id']);
    $star = $game->get_star($room['level_id']);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" type="text/css" href="../../public/css/style.css"/>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="../../public/css/app.css">
	
	<script type="text/javascript" src="../../public/js/app.js"></script>
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    
	<title>MBD</title>
</head>
<body style="position: relative;">
    <canvas id="game" style="float: left; margin-right: 30px; max-height: 100%;"></canvas>
	<div style="padding-top: 200px;">
		<table>
			<tbody>
				<tr id="F1">
					<th>F1</th>
					<td class="click box option" id="o1">_</td>
					<td class="click box option" id="o2">_</td>
					<td class="click box option" id="o3">_</td>
					<td class="click box option" id="o4">_</td>
					<td class="click box option" id="o5">_</td>
					<td class="click box option" id="o6">_</td>
					<td class="click box option" id="o7">_</td>
					<td class="click box option" id="o8">_</td>
					<td class="click box option" id="o9">_</td>
					<td class="click box option" id="o10">_</td>
				</tr>
				<tr id="F2" style="margin-top: 30px !important;">
					<th>F2</th>
					<td class="click box option" id="o11">_</td>
					<td class="click box option" id="o12">_</td>
					<td class="click box option" id="o13">_</td>
					<td class="click box option" id="o14">_</td>
					<td class="click box option" id="o15">_</td>
					<td class="click box option" id="o16">_</td>
					<td class="click box option" id="o17">_</td>
					<td class="click box option" id="o18">_</td>
					<td class="click box option" id="o19">_</td>
					<td class="click box option" id="o20">_</td>
				</tr>
			</tbody>
		</table>
	</div>
	<button id="play">PLAY</button>
	<table style="border: solid thin;position: absolute;" id="option">
		<tbody>
			<tr>
				<td class="click box show" style="background-color: #fff;"><i class="fas fa-chevron-up" data="lurus"></i></td>
				<td class="click box show" style="background-color: #fff;"><i class="fas fa-chevron-right" data="kanan"></i></td>
				<td class="click box show" style="background-color: #fff;"><i class="fas fa-chevron-left" data="kiri"></i></td>
				<td class="click box show" style="background-color: #fff;">F1</td>
				<td class="click box show" style="background-color: #fff;">F2</td>
			</tr>
		</tbody>
	</table>



	


	<script type="text/javascript">
		let games;
		let map = JSON.parse('<?php echo $map; ?>').data;
		let star = JSON.parse('<?php echo $star; ?>').data;
		let step = 0;
		let levell = <?php echo $room['level_id']; ?>

		window.onload = function(){
			if(map == undefined || star == undefined){
				setTimeout(window.onload,1000);
			}

			games = new Game(<?php echo $room['room_id']; ?>,map,star,<?php echo Auth::user()['user_id']; ?>);
			games.run();

			$('#option').hide();

			for(var id=1;id<=20;id++){
				$('#o'+id).click(function(){
					var pos = $(this).position();
					var lleft = pos.left + 550;
					var ttop = pos.top - $('#option').height() + 190;
					$('#option').css({left:lleft,top:ttop});
					$('#option').show();
					var caller = $(this).attr('id');
					console.log(caller);
					$('.show.click.box').click({param:caller},set);
				});
			}
		}

		function set(event){
			var caller = event.data.param
			event.data.param = undefined;
			console.log(caller);
			var temp = $(this).html();
			$('#'+caller).html(temp);
		}

		$('#play').click(function(){
			var arr = [];
			$('#F1').children('td').each(function(i){
				if($(this).html()!='_' && $(this).html()!= 'F1' && $(this).html()!= 'F2'){
					arr.push($(this).children().attr("data"))
				} else if($(this).html()=='F1') {
					alert('Infinity Recursive Found!!');
					// reload
				} else if($(this).html()=='F2'){
					$('#F2').children('td').each(function(i){
						arr.push($(this).children().attr("data"));
					});
				}
			});
			step = arr.length;
			play_games(arr,0);
		});

		function play_games(arr,i){
			if(i < arr.length){
				games.Pemain().handle(arr[i]);
				setTimeout(play_games,100,arr,i+1);	
			}
			else{
				return;
			}
		}

	</script>

	<div class="modal fade" id="ratingroom" tabindex="-1" role="dialog" aria-labelledby="ratingroomlabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
		    <div class="modal-content">
		    	<form id="ratingroomform" action="/coc/room/rate" method="POST">
		            <div class="modal-header" style="flex-direction: row;">
		                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		                    <span aria-hidden="true">&times;</span>
		                </button>
		            </div>
		            <div class="modal-body">
		            	<div id="congrats">
		            	</div>
		            	<input type="text" placeholder="difficulty" name="difficulty">
		            	<input type="text" placeholder="fun" name="fun">
		            	<input type="hidden" value="" name="levelid" id="levelid">
		            </div>
		            <div class="modal-footer">
		                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		                <button class="submitrating btn btn-primary" type="submit" name="submit">Rate!</button>
		            </div>
		        </form>
		    </div>
		</div>
    </div>
</body>