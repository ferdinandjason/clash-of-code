<script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
	crossorigin="anonymous"></script>

<?php
    $var = $this->data;
    foreach ($var as $name => $value) {
        ${$name}=$value;
    }

    $rooms = $room_control->get_all_room();
    if(Auth::user()){
        $my_rooms = $room_control->get_room(Auth::user()['user_id']);
    }
    else{
        $my_rooms = [];
    }
?>
<!DOCTYPE html>
<html>
<head>
	<?php include('head.php'); ?>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="public/css/style.css"/>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

	<title>MBD</title>
</head>
<body>

	<div class="page-intro">
		<div class="vertical-center">
			<div class="page-container">
				<img src="public/images/coc2.png" alt>
				<div class="button-login">
					<?php if(!Auth::user()): ?>
						<button type="submit" class="btn btn-outline-dark btn-block" data-toggle="modal" data-target="#loginModal">Log In</button>
						<button type="submit" class="btn btn-outline-dark btn-block" data-toggle="modal" data-target="#signupModal">Sign Up</button>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>

	<div <?php if(!Auth::user()): ?> hidden <?php endif; ?> class="container rooms">
		<div class="row">
			<div class="col-12 availableroom">
				<div class="card wallet" style="top: 50px;">
     				<div class="overlay"></div>
  					<div class="circle"></div>
  					<div class="col">
  						<?php if(Auth::user()): ?> 
	  						<?php foreach ($rooms as $room) : ?>
								<?php if(!Room::is_joined_room(Auth::user()['user_id'],$room['room_id'])): ?>
									<div class="col-md-2" style="float: left;">
										<div class="roomimg" style="margin-top: 5px; position: relative;">
											<img src="public/images/room1.png" style="max-width: 100%;" alt>
											<div class="roomname" style="position: absolute; top: 10%; left: 10%; font-family: Lato; color: #fff">
												<?php echo $room['name']."<br>"?>
												Lv <?php echo $room['level_id'] ?>
											</div>
											
											<button type="button" class="joinroombutton" data-id="<?php echo $room['room_id'] ?>" data-toggle="modal" data-target="#joinroom" style="border: 0; background: transparent;">
												<img src="public/images/room2.png" style="max-width: 100%; margin-top: -2px;" alt="join room">
											</button>
											
										</div>						
									</div>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php endif; ?>
  					</div>
  						
 				</div>
 				
			</div>
		</div>
		<div class="row">
			<div class="col-8 joinedroom" style="margin-top: 70px;">
				<a href="#" class="card wallet">
     				<div class="overlay"></div>
  					<div class="circle"></div>
  					<div class="col">
  						<?php if(Auth::user()): ?> 
	  						<?php foreach ($my_rooms as $room) : ?>
									<div class="col-md-2" style="float: left;">
										<div class="roomimg" style="margin-top: 5px; position: relative;">
											<img src="public/images/room1.png" style="max-width: 100%;" alt>
											<div class="roomname" style="position: absolute; top: 10%; left: 10%; font-family: Lato; color: #fff">
												<?php echo $room['name']."<br>"?>
											</div>
										</div>						
									</div>
							<?php endforeach; ?>
						<?php endif; ?>
  					</div>
				</a>
			</div>
		</div>
	</div>

	<div <?php if(!Auth::user()): ?> hidden <?php endif; ?> class="createroom" style="position: fixed;">
		<button type="button" class="btn btn-sm btn-light" data-toggle="modal" data-target="#createroom">
        	Create New Room
        </button>
	</div>

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>

	<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="modaltitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
			    <div class="modal-header">
			    	<h5 class="modal-title" id="modaltitle">Log In</h5>
			    	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			    		<span aria-hidden="true">&times;</span>
				    </button>
			    </div>
                <form action="login" method="POST">
                    <div class="modal-body">
                        <?php include('login_form.php'); ?>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Log In</button>
                    </div>
                </form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="signupModal" tabindex="-1" role="dialog" aria-labelledby="modaltitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
			    <div class="modal-header">
			    	<h5 class="modal-title" id="modaltitle">Sign Up</h5>
			    	<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			    		<span aria-hidden="true">&times;</span>
				    </button>
			    </div>
                <form action="register" method="POST">
                    <div class="modal-body">
                        <?php include('register_form.php'); ?>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Sign Up</button>
                    </div>
                </form>
			</div>
		</div>
	</div>

	<div class="modal fade" id="joinroom" tabindex="-1" role="dialog" aria-labelledby="joinroomlabel" aria-hidden="true">
		<div class="modal-dialog modal-sm" role="document">
		    <div class="modal-content">
		        <form id="joinroomform" action="room/join" method="POST">
		            <div class="modal-header" style="flex-direction: row;">
		                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		                    <span aria-hidden="true">&times;</span>
		                </button>
		            </div>
		            <div class="modal-body">
		            	<input type="hidden" name="roomjoin" value="" id="roomjoin">
						<input type="password" placeholder="Password" name="password">
		            </div>
		            <div class="modal-footer">
		                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		                <button class="btn btn-primary" type="submit" name="submit">Join Room</button>
		            </div>
		        </form>
		    </div>
		</div>
    </div>

    <div class="modal fade" id="createroom" tabindex="-1" role="dialog" aria-labelledby="createroomlabel" aria-hidden="true">
		<div class="modal-dialog modal-sm" role="document">
		    <div class="modal-content">
		        <form id="createroomform" action="room/create" method="POST">
		            <div class="modal-header" style="flex-direction: row;">
		                <h5 class="modal-title">Create New Room</h5>
		                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		                    <span aria-hidden="true">&times;</span>
		                </button>
		                    	
		            </div>
		            <div class="modal-body">
		            	<label for="name"><b>Room Name</b></label>
		            	<br>
						<input type="text" placeholder="Enter Room Name" name="name" required>
						<br>
						<label for="password"><b>Password</b></label>
						<br>
						<input type="password" placeholder="Kosong apabila public" name="password">
		            </div>
		            <div class="modal-footer">
		                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
		                <button class="btn btn-primary" type="submit" name="submit">Create Room</button>
		            </div>
		        </form>
		    </div>
		</div>
    </div>
</body>
</html>

<script type="text/javascript">
	$(document).ready(function() {
		$(document).on("click", ".joinroombutton", function () {
		     var roomid = $(this).data('id');
		     $(".modal-body #roomjoin").val(roomid);
		});
	});
</script>
