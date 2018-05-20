<?php
ob_start();
session_start();
include_once 'dbconnect.php';

function getToken($length){
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    $max = strlen($codeAlphabet);

    for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[random_int(0, $max-1)];
    }

    return $token;
}

function sendMail($id, $email, $token) {
	$subject = "Conferma registrazione per Carthafind.ch";
	$to = $email;
	$headers = "From: decima@carthafind.ch\r\n";
	$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
	$body="Perfavore vada al seguente <a href='localhost:3600/Carthafind/verify.php?id=".$id."&token=".$token
		."'>link</a> per completare l'attivazione del suo account Carthafind";
	mail($to, $subject, $body, $headers);
}

if(isset($_SESSION['user'])!= ""){
	header("Location: home.php");
}

$name = "";
$surname = "";
$email = "";
$username = "";
$pass = "";
$nameError = "";
$surnameError = "";
$emailError = "";
$usernameError = "";
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

	$username = trim($_POST['username']);
	$username = strip_tags($username);
	$username = htmlspecialchars($username);

	$pass = trim($_POST['pass']);
	$pass = strip_tags($pass);
	$pass = htmlspecialchars($pass);

	if (empty($name)) {
		$error = true;
		$nameError = "Inserisci il tuo nome.";
	} else if (strlen($name) < 3) {
		$error = true;
		$nameError = "Il nome deve contenere almeno tre lettere.";
	} else if (!preg_match("/^[a-zA-Z ]+$/",$name)) {
		$error = true;
		$nameError = "Il nome può solo contenere lettere dell'alfabeto.";
	}

	if (empty($surname)) {
		$error = true;
		$surnameError = "Inserisci il tuo cognome.";
	} else if (strlen($surname) < 3) {
		$error = true;
		$surnameError = "Il cognome deve contenere almeno tre lettere.";
	} else if (!preg_match("/^[a-zA-Z ]+$/",$surname)) {
		$error = true;
		$surnameError = "Il cognome può solo contenere lettere dell'alfabeto.";
	}

	if (empty($username)) {
		$error = true;
		$usernameError = "Inserisci il tuo cognome.";
	} else if (strlen($username) < 3) {
		$error = true;
		$usernameError = "Il nome utente deve contenere almeno tre lettere.";
	}
	
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$error = true;
		$emailError = "Inserisci un'email valida.";
	} elseif(strpos($email, 'samtrevano.ch') == false && strpos($email, 'edu.ti.ch') == false) {
		$error = true;
		$emailError = "Inserisci un'email con dominio edu.ti.ch o samtrevano.ch";
	} else {
		$query = "SELECT `email` FROM `user` WHERE `email`='".$email."'";
		$result = $conn->query($query);

		$count = $result->num_rows;
		if($count != 0){
			$error = true;
			$emailError = "Quest'email è già in uso.";
		}
	}

	if (empty($pass)){
		$error = true;
		$passError = "Inserisci una password.";
	} else if(strlen($pass) < 4) {
		$error = true;
		$passError = "La password deve contenere almeno quattro caratteri.";
	}

	$password = hash('sha256', $pass);

	if(!$error) {
		$query = "INSERT INTO `user` (`name`, `surname`, `granted`, `username`, `password`, `email`) VALUES
		('".$name."', '".$surname."', 'Normal', '".$username."','".$password."','".$email."')";
		$result = $conn->query($query);

		if($result) {
			$outType = "success";
			$outMessage = "Manca poco, controlla la tua email per confermare la registrazione.";

			$query = "SELECT `id` FROM `user` WHERE `email`='".$email."'";
			$result = $conn->query($query);
			$row = $result->fetch_assoc();

			$token = getToken(16);
			$id = $row['id'];
			$query = "INSERT INTO `verify` (`id`, `token`) VALUES ('".$id."', '".$token."')";
			$result = $conn->query($query);

			sendMail($id, $email, $token);
			
			unset($name);
			unset($surname);
			unset($email);
			unset($username);
		} else {
			$outType = "danger";
			$outMessage = "Qualcosa è andato storto, riprova o segnala il problema ad uno degli amministratori.";
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Carthafind - Registrazione</title>
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
					if (isset($outMessage)) {
					?>
						<div class="form-group">
							<div class="alert alert-<?php echo ($outType=="success") ? "success" : $outType; ?>">
								<span class="glyphicon glyphicon-info-sign"></span> <?php echo $outMessage; ?>
							</div>
						</div>
					<?php
					}
					?>

					<div class="form-group">
						<hr/>
					</div>

					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
							<input type="text" name="name" class="form-control" placeholder="Nome" maxlength="20" value="<?php echo $name ?>"/>
						</div>
						<span class="text-danger"><?php echo $nameError; ?></span>
					</div>

					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
							<input type="text" name="surname" class="form-control" placeholder="Cognome" maxlength="20" value="<?php echo $surname ?>"/>
						</div>
						<span class="text-danger"><?php echo $surnameError; ?></span>
					</div>

					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
							<input type="email" name="email" class="form-control" placeholder="Email (edu.ti.ch o samtrevano.ch)" maxlength="50" value="<?php echo $email ?>"/>
						</div>
						<span class="text-danger"><?php echo $emailError; ?></span>
					</div>

					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-eye-open"></span></span>
							<input type="text" name="username" class="form-control" placeholder="Username" maxlength="20" value="<?php echo $username ?>"/>
						</div>
						<span class="text-danger"><?php echo $usernameError; ?></span>
					</div>

					<div class="form-group">
						<div class="input-group">
							<span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
							<input type="password" name="pass" class="form-control" placeholder="Password" maxlength="20"/>
						</div>
						<span class="text-danger"><?php echo $passError; ?></span>
					</div>

					<div class="form-group">
						<hr/>
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
	<footer style="position: absolute;bottom: 0;width: 100%;height: 60px;line-height: 60px; background-color: #f5f5f5">
		<div class="footer-copyright py-3 text-center">
			© 2018 Copyright: Nadir Barlozzo
		</div>
	</footer>
</body>
</html>
<?php ob_end_flush(); ?>
