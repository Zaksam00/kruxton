<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include('./db_connect.php');
ob_start();
// if(!isset($_SESSION['system'])){
	$system = $conn->query("SELECT * FROM system_settings limit 1")->fetch_array();
	foreach($system as $k => $v){
		$_SESSION['system'][$k] = $v;
	}
// }
ob_end_flush();
?>
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title><?php echo $_SESSION['system']['name'] ?></title>
 	

<?php include('./header.php'); ?>
<?php 
if(isset($_SESSION['login_id']))
header("location:index.php?page=home");

?>

</head>
<style>
	body{
		width: 100%;
	    height: calc(100%);
	    position: fixed;
	    top:0;
	    left: 0;
	    background-image: url('assets/uploads/background.webp');
	    background-size: cover;
	}
	main#main{
		width:100%;
		height: calc(100%);
		display: flex;
	}

  /* Split the forms into two columns */
  .left-column {
    width: 50%;
    padding: 0 10px;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .right-column {
    width: 50%;
    padding: 0 10px;
    display: flex;
    justify-content: center;
    align-items: center;
  }
</style>

<body class="bg-dark">
  <main id="main">
    <div class="align-self-center w-100">
      <div id="login-center" class="row justify-content-start">
        <!-- Left Column: PIN Form -->
        <div class="col-md-6 left-column">
          <div class="card">
            <div class="card-body py-5 px-1">
              <h4 class="text-dark text-center mb-5">
                <img src="assets/uploads/logo.png" width="300px" >
              </h4>
					<form id="pin-form">
						<div class="form-group">

						<div class="input-group mb-2" >
							<div class="input-group-prepend ">
								<div class="input-group-text  bg-transparent border-0"><i class="fa fa-key"></i></div>
							</div>
							<input type="password" id="pin" name="pin" class="form-control border-0" placeholder="PIN">
						</div>


						<div class="numeric-keypad">
									<div class="row">
										<div class="col-md-4 keypad-button" data-value="1">1</div>
										<div class="col-md-4 keypad-button" data-value="2">2</div>
										<div class="col-md-4 keypad-button" data-value="3">3</div>
									</div>
									<div class="row">
										<div class="col-md-4 keypad-button" data-value="4">4</div>
										<div class="col-md-4 keypad-button" data-value="5">5</div>
										<div class="col-md-4 keypad-button" data-value="6">6</div>
									</div>
									<div class="row">
										<div class="col-md-4 keypad-button" data-value="7">7</div>
										<div class="col-md-4 keypad-button" data-value="8">8</div>
										<div class="col-md-4 keypad-button" data-value="9">9</div>
									</div>
									<div class="row">
										<div class="col-md-4"></div>
										<div class="col-md-4 keypad-button" data-value="0">0</div>
										<div class="col-md-4"></div>
									</div>
								</div>
				
						</div>
						<center><button class="btn col-md-12 btn-primary">Enter PIN</button></center>
					</form>
            </div>
          </div>
        </div>
        
        <!-- Right Column: Login Form -->
        <div class="col-md-6 right-column">
          <div class="card">
            <div class="card-body py-5 px-1">
              <h4 class="text-dark text-center mb-5">
                <img src="assets/uploads/logo.png" width="300px" >
              </h4>
                	<form id="login-form" >
  						<div class="form-group">
  						<div class="input-group mb-2" >
				        <div class="input-group-prepend ">
				          <div class="input-group-text  bg-transparent border-0"><i class="fa fa-user"></i></div>
				        </div>
				        <input type="email"  pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" id="username" name="username" class="form-control border-0" placeholder="Username">
				      </div>
  						</div>
  						<div class="form-group">
							<div class="input-group mb-2" >
				        <div class="input-group-prepend ">
				          <div class="input-group-text  bg-transparent border-0"><i class="fa fa-lock"></i></div>
				        </div>
				        <input type="password" id="password" name="password" class="form-control border-0" placeholder="Password">
				      </div>
  						</div>
  						<div class="form-check py-3">
						    <input type="checkbox" class="form-check-input" id="exampleCheck1">
						    <label class="form-check-label mt-1" for="exampleCheck1"> Remember me</label>
						  </div>
  						<center><button class="btn col-md-12 btn-primary">Login</button></center>
  					</form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
	$('#login-form').submit(function(e){
		e.preventDefault()
		$('#login-form button[type="button"]').attr('disabled',true).html('Logging in...');
		if($(this).find('.alert-danger').length > 0 )
			$(this).find('.alert-danger').remove();
		$.ajax({
			url:'ajax.php?action=login',
			method:'POST',
			data:$(this).serialize(),
			error:err=>{
				console.log(err)
		$('#login-form button[type="button"]').removeAttr('disabled').html('Login');

			},
			success:function(resp){
				if(resp == 1){
					location.href ='index.php?page=home';
				}else{
					$('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
					$('#login-form button[type="button"]').removeAttr('disabled').html('Login');
				}
			}
		})
	})
</script>	

<script>
	$(document).ready(function() {
        // Handle numeric keypad button clicks
        $('.keypad-button').click(function() {
            var currentValue = $('#pin').val();
            var digit = $(this).data('value');
            $('#pin').val(currentValue + digit);
        });
    });
</script>
</html>
