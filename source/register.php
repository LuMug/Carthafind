<?php
	ob_start();
	session_start();	
	include_once 'dbconnect.php';
	
	if(isset($_SESSION['user'])!= ""){
		header("Location: home.php");
	}

	$name = "";
	$surname = "";
	$email = "";
	$pass = "";
	$nameError = "";
	$surnameError = "";
	$emailError = "";
	$passError = "";

	$error = false;

	if (isset($_POST['btn-signup'])) {
		
		$name = trim($_POST['name']);
		$name = strip_tags($name);
		$name = htmlspecialchars($name);
		
		$email = trim($_POST['email']);
		$email = strip_tags($email);
		$email = htmlspecialchars($email);
		
		$surname = trim($_POST['surname']);
		$surname = strip_tags($surname);
		$surname = htmlspecialchars($surname);
		
		$pass = trim($_POST['pass']);
		$pass = strip_tags($pass);
		$pass = htmlspecialchars($pass);
		
		if (empty($name)) {
			$error = true;
			$nameError = "Inserisci il tuo nome";
		} else if (strlen($name) < 3) {
			$error = true;
			$nameError = "Il nome deve contenere almeno tre lettere";
		} else if (!preg_match("/^[a-zA-Z ]+$/",$name)) {
			$error = true;
			$nameError = "Il nome può solo contenere lettere dell'alfabeto";
		}
		
		if (empty($surname)) {
			$error = true;
			$surnameError = "Inserisci il tuo cognome";
		} else if (strlen($surname) < 3) {
			$error = true;
			$surnameError = "Il cognome deve contenere almeno tre lettere";
		} else if (!preg_match("/^[a-zA-Z ]+$/",$surname)) {
			$error = true;
			$surnameError = "Il cognome può solo contenere lettere dell'alfabeto";
		}
		
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$error = true;
			$emailError = "Inserisci un'email valida";
		} else {
			$query = "SELECT `email` FROM `user` WHERE `email`='$email'";
			$result = $conn->query($query);
			
			$count = $result->num_rows;
			if($count != 0){
				$error = true;
				$emailError = "Quest'email è già in uso";
			}
		}
		
		if (empty($pass)){
			$error = true;
			$passError = "Inserisci una password.";
		} else if(strlen($pass) < 4) {
			$error = true;
			$passError = "La password deve contenere almeno quattro caratteri";
		}
		
		$password = hash('sha256', $pass);
		
		if(!$error) {
			$query = "INSERT INTO `user` (`name`, `surname`, `granted`, `username`, `password`, `email`) VALUES ('".$name."', '".$surname."', 'Normal', 'LL','".$password."','".$email."')";
			$result = $conn->query($query);
				
			if($result) {
				$errTyp = "success";
				$errMSG = "Successfully registered, you may login now";
				unset($name);
				unset($email);
				unset($surname);
				header("Location: login.php");		
			} else {
				$errTyp = "danger";
				$errMSG = "Qualcosa é andato storto, riprova o segnala il problema ad uno degli amministratori";	
			}		
		}	
	}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Reactiongame - Login & Registration</title>
<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"  />
<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>

<div class="container">

	<div id="login-form">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
    
    	<div class="col-md-12">
        
        	<div class="form-group">
            	<h2 class="">Register</h2>
            </div>
                    
            <?php
			if (isset($errMSG)) {	
			?>
				<div class="form-group">
            	<div class="alert alert-<?php echo ($errTyp=="success") ? "success" : $errTyp; ?>">
				<span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
                </div>
            	</div>
            <?php
			}
			?>
            
            <div class="form-group">
            	<div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
            	<input type="text" name="name" class="form-control" placeholder="Nome" maxlength="50" value="<?php echo $name ?>"/>
                </div>
                <span class="text-danger"><?php echo $nameError; ?></span>
            </div>
            
			<div class="form-group">
            	<div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
            	<input type="text" name="surname" class="form-control" placeholder="Cognome" maxlength="50"/>
                </div>
                <span class="text-danger"><?php echo $surnameError; ?></span>
            </div>
			
            <div class="form-group">
            	<div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
            	<input type="email" name="email" class="form-control" placeholder="Email" maxlength="50" value="<?php echo $email ?>"/>
                </div>
                <span class="text-danger"><?php echo $emailError; ?></span>
            </div>
			
			 <div class="form-group">
            	<div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
            	<input type="password" name="pass" class="form-control" placeholder="Password" maxlength="20"/>
                </div>
                <span class="text-danger"><?php echo $passError; ?></span>
            </div>
                        
            <div class="form-group">
            	<button type="submit" class="btn btn-block btn-primary" name="btn-signup">Registrati</button>
            </div>
                        
            <div class="form-group">
            	<a href="login.php">Esegui il login</a>
            </div>  
        </div>
    </form>
    </div>
</div>

</body>
</html>
<?php ob_end_flush(); ?>