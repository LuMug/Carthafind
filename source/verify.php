<?php
    require_once 'dbconnect.php';
    
    $outMessage = "";
    $outType = "";

    if(isset($_GET['id']) && isset($_GET['token'])) {
        $id = $_GET['id'];
        $token = $_GET['token'];

        $query = "SELECT * FROM `verify` WHERE `id` ='".$id."' AND `token` ='".$token."'";
		$result = $conn->query($query);
        $count = $result->num_rows;

        if($count != 0) {
            $query = "UPDATE `user` SET `granted` = 'Registered' WHERE `id` ='".$id."'";
            $result = $conn->query($query);

            $query = "DELETE FROM `verify` WHERE `id` ='".$id."'";
            $result = $conn->query($query);

            $outType = "success";
            $outMessage = "Ha confermato la sua registrazione con successo! Ora può effettuare il login.";
        }
        else {
            $outType = "danger";
            $outMessage = "Codice di attivazione non valido.";
        }
    }
    else {
        $outType = "danger";
        $outMessage = "Richiesta della pagina invalida.";
    }
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Carthafind - Verifica</title>
	<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"/>
	<link rel="stylesheet" href="style.css" type="text/css"/>
</head>
<body>
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<a class="navbar-brand">Carthafind</a>
			</div>
		</div>
	</nav>

    <div id="wrapper" style="margin-top: 3%">
    	<div class="container">
            <div class="col-md-12">
                <div class="alert alert-<?php echo ($outType=="success") ? "success" : $outType; ?>">
                    <span class="glyphicon glyphicon-info-sign"></span> <?php echo $outMessage; ?>
                </div>
            </div>
    	</div>
    </div>
	<footer style="position: absolute;bottom: 0;width: 100%;height: 60px;line-height: 60px; background-color: #f5f5f5">
		<div class="footer-copyright py-3 text-center">
			© 2018 Copyright: Nadir Barlozzo
		</div>
	</footer>
</body>
</html>
