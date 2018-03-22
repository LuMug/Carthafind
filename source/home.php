<?php
ob_start();
session_start();
require_once 'dbconnect.php';

$user = "";

if(isset($_SESSION['user'])) {
	$query = "SELECT `email` FROM `user` WHERE `id`=".$_SESSION['user'];
	$result = $conn->query($query);
	$user = $result->fetch_assoc();
	$user = $user['email'];
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Welcome - <?php echo $user ?></title>
	<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"/>
	<link rel="stylesheet" href="style.css" type="text/css"/>
</head>
<body>
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand">Carthafind</a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-right">
					<?php if($user != "") { ?>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
								<span class="glyphicon glyphicon-user"></span>&nbsp;<?php echo $user ?>&nbsp;<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li><a href="logout.php?logout"><span class="glyphicon glyphicon-log-out"></span>&nbsp;Esci</a></li>
							</ul>
						</li>
					<?php } else { ?>
						<li><a href="login.php?"><span class="glyphicon glyphicon-user"></span>&nbsp;Login</a></li>
					<?php } ?>
				</ul>
			</div>
		</div>
	</nav>

	<div id="wrapper">
		<div class="container">
			<div class="page-header">
				<h3>Carthafind - Strumento di ricerca per progetti SAMT</h3>
			</div>
			<div class="container">
				<p>
					Alcuni dei progetti creati nel nostro istituto dopo la loro conclusione vengono dimenticati.
				</p>
				<p>
					Lo scopo di questo applicativo è schedare e classificare ogni progetto per poi metterli alla portata degli utenti,
					quali possono essere persone sia interne che esterne alla scuola.
				</p>
				<p>
					Potete inoltre tramite la barra di ricerca filtrare i prodotti tramite parole chiavi così da avere solo quelli che più vi interessano.
				</p>
			</div>
			<?php if($user != "") { ?>
				<div class="container" style="padding-top: 3%;">
					<div class="row">
						<div class="col-md-3">
							<form action="#" method="get">
								<div class="input-group">
									<input class="form-control" id="system-search" name="q" placeholder="Cerca per" required>
									<span class="input-group-btn">
										<button type="submit" class="btn btn-default"><i class="glyphicon glyphicon-search"></i></button>
									</span>
								</div>
							</form>
						</div>
					</div>
					<div class="row" style="padding-top: 3%;">
						<div class="col-md-12">
							<table class="table table-hover table-responsive table-list-search">
								<thead>
									<tr>
										<th>Titolo</th>
										<th>Descrizione</th>
										<th>Responsabile</th>
										<th>Autore/i</th>
										<th>Durata</th>
										<th>Voto finale</th>
										<th>Completamento</th>
										<th>Commmenti</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$query = "SELECT * FROM `project`";
									$result = $conn->query($query);

									while($row = $result->fetch_assoc()) {
									?>
										<tr class="project-container" data-toggle="modal" data-target="#projectModal" style="cursor: pointer; user-select: none;"
											onclick="
												$('.modal-title').text('<?php echo $row['title']; ?>');

											"
										>
											<td><?php echo $row['title']; ?></td>
											<td><?php echo $row['description']; ?></td>
											<td>
												<?php
												$query = "SELECT `id_responsible` FROM `project` WHERE `id`='".$row['id']."'";
												$result = $conn->query($query);

												$query = "SELECT `name`, `surname` FROM `user` WHERE `id`='".$row['id_responsible']."'";
												$result = $conn->query($query);
												$row2 = $result->fetch_assoc();

												echo $row2['name']." ".$row2['surname'];
												?>
											</td>
											<td>
												<?php
												$query = "SELECT `id_author` FROM `participates` WHERE `id_project`='".$row['id']."'";
												$result2 = $conn->query($query);

												while($row2 = $result2->fetch_assoc()) {
													$query = "SELECT `name`, `surname` FROM `user` WHERE `id`='".$row2['id_author']."'";
													$result = $conn->query($query);
													$row3 = $result->fetch_assoc();

													echo $row3['name']." ".$row3['surname'].",<br>";
												}
												?>
											</td>
											<td><?php echo $row['length']." ore"; ?></td>
											<td><?php echo $row['final_vote']; ?></td>
											<td><?php echo $row['progress']."%"; ?></td>
											<td><?php echo $row['comment']; ?></td>
										</tr>
									<?php
									}
									?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
	<div class="modal fade" id="projectModal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body">
					<a>doc.pdf</a>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
				</div>
			</div>
		</div>
	</div>
	<footer style="position: absolute;bottom: 0;width: 100%;height: 60px;line-height: 60px; background-color: #f5f5f5">
		<div class="footer-copyright py-3 text-center">
			© 2018 Copyright: Nadir Barlozzo
		</div>
	</footer>

	<script src="assets/jquery-1.11.3-jquery.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/finder.js"></script>
</body>
</html>
<?php ob_end_flush(); ?>
