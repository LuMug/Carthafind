<?php
ob_start();
session_start();
require_once 'dbconnect.php';

$user = "";

if(isset($_SESSION['user'])) {
	$query = "SELECT `username`, `granted` FROM `user` WHERE `id`=".$_SESSION['user'];
	$result = $conn->query($query);
	$user = $result->fetch_assoc();
}

//UPDATE
$title = "";
$description = "";
$responsible = "";
$authors = "";
$length = "";
$final_vote = "";
$progress = "";
$comment = "";
$keywords = "";
$doc_path = "";
$titleError = "";
$descriptionError = "";
$responsibleError = "";
$authorsError = "";
$lengthError = "";
$final_voteError = "";
$progressError = "";
$commentError = "";
$keywordsError = "";
$doc_pathError = "";

$error = false;

if(isset($_POST['btn-login'])) {

	$title = trim($_POST['title']);
	$title = strip_tags($title);
	$title = htmlspecialchars($title);

	$description = trim($_POST['description']);
	$description = strip_tags($description);
	$description = htmlspecialchars($description);
	
	$responsible = $_POST['responsible'];
	
	$authors = $_POST['authors'];
	
	$length = trim($_POST['length']);
	$length = strip_tags($length);
	$length = htmlspecialchars($length);
	
	$final_vote = trim($_POST['final_vote']);
	$final_vote = strip_tags($final_vote);
	$final_vote = htmlspecialchars($final_vote);
	
	$progress = trim($_POST['progress']);
	$progress = strip_tags($progress);
	$progress = htmlspecialchars($progress);
	
	$comment = trim($_POST['comment']);
	$comment = strip_tags($comment);
	$comment = htmlspecialchars($comment);
	
	//UPDATE
	$keywords = $_POST['keywords'];
	
	$extensions = array("pdf", "doc", "docx");
	
	$explodedExt = explode('.', $_FILES['doc_path']['name']);	
	$file_ext = strtolower(end($explodedExt));
	$doc_path = $_FILES['doc_path']['name'];
	
	//UPDATE
	if(empty($_POST['keywords'])) {
		$error = true;
		$keywordsError = "Inserisci almeno una parola chiave.";
	}
	
	
	if(!isset($_FILES['doc_path'])) {
		$error = true;
		$doc_pathError = "Carica un file di documentazione (.pdf o .doc/x).";
	}
	else if(in_array($file_ext, $extensions) == false) {
		$error = true;
		$doc_pathError = "Il file di documentazione può avere solo le estensioni pdf, doc o docx.";
	}
	
	if(empty($title)){
		$error = true;
		$titleError = "Inserisci un titolo.";
	}
	else if(strlen($title) < 3) {
		$error = true;
		$titleError = "Il titolo deve essere formato da almeno tre lettere.";
	}
	
	if(empty($description)){
		$error = true;
		$descriptionError = "Inserisci una descrizione.";
	}
	else if(strlen($description) < 10) {
		$error = true;
		$descriptionError = "La descrizione deve essere composta da almeno 10 caratteri.";
	}
	
	if(empty($length)){
		$error = true;
		$lengthError = "Inserisci una durata.";
	}
	
	if(empty($final_vote)){
		$error = true;
		$final_voteError = "Inserisci un voto finale.";
	}
	
	if(empty($progress)){
		$error = true;
		$progressError = "Inserisci una % di completamento.";
	}
				
	if (!$error) {	
		$uploaddir = 'C:/xampp/htdocs/Carthafind/documentations/';
		$userfile_tmp = $_FILES['doc_path']['tmp_name'];
		$userfile_name = $_FILES['doc_path']['name'];
		move_uploaded_file($userfile_tmp, $uploaddir . $userfile_name);
				
		$responsible = explode(" ", $responsible);
			
		$query = "SELECT `id` FROM `user` WHERE `name` ='".$responsible[0]."' AND `surname`='".$responsible[1]."'";
		$result = $conn->query($query);
		$id_responsible = $result->fetch_assoc();
		$id_responsible = $id_responsible['id'];
		
		$query = "INSERT INTO `project` (`title`, `description`, `length`, `final_vote`, `progress`, `comment`, `id_responsible`, `doc_path`) VALUES
		('".$title."', '".$description."',".$length.",".$final_vote.",".$progress.",'".$comment."',".$id_responsible.",'documentations/".$doc_path."')";
		$conn->query($query);
		
		$query = "SELECT `id` FROM `project` ORDER BY `id` DESC LIMIT 1";
		$result = $conn->query($query);
		$projectId = $result->fetch_assoc();
		$projectId = $projectId['id'];
		
		$keywords = explode(",", $keywords);
		
		foreach($keywords as $key) {
			$key = strtolower($key);
		
			$query = "SELECT `id` FROM `keyword` WHERE `name`='".$key."'";
			$result = $conn->query($query);
			
			$kId = 0;
			
			if($result->num_rows > 0) {
				$kId = $result->fetch_assoc();
				$kId = $kId['id'];
			} 
			else {
				$query = "INSERT INTO `keyword` (`name`) VALUES ('".$key."')";
				$conn->query($query);
				
				$query = "SELECT `id` FROM `keyword` ORDER BY `id` DESC LIMIT 1";
				$result2 = $conn->query($query);
				$kId = $result2->fetch_assoc();
				$kId = $kId['id'];
			}
		
			$query = "INSERT INTO `contains` (`id_project`, `id_keyword`) VALUES (".$projectId.",".$kId.")";
			$conn->query($query);
		}
		
		$authors = explode(",", $authors);
		
		foreach($authors as $auth) {
			$authA = explode(" ", $auth);
			
			if($authA[0] != "") {
				$query = "SELECT `id` FROM `user` WHERE `name`='".$authA[0]."' AND `surname`='".$authA[1]."'";
				$result2 = $conn->query($query);
				$authI = $result2->fetch_assoc();
				$authI = $authI['id'];
				
				$query = "INSERT INTO `participates` (`id_project`, `id_author`) VALUES (".$projectId.",".$authI.")";
				$conn->query($query);
			}
		}
		
		//UPDATE
		unset($title);
		unset($description);
		unset($responsible);
		unset($authors);
		unset($length);
		unset($final_vote);
		unset($progress);
		unset($comment);
		unset($keywords);
		unset($doc_path);
		header("Location: http://localhost:3600/Carthafind/home.php#form-title");
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Benvenuto <?php if($user != "") { echo " - ".$user['username']; } ?></title>
	<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"/>
	<link rel="stylesheet" href="assets/css/carthafind.css" type="text/css"/>
	<link href="assets/lib/css/multi-select.css" media="screen" rel="stylesheet" type="text/css">
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
								<span class="glyphicon glyphicon-user"></span>&nbsp;<?php echo $user['username']; ?>&nbsp;<span class="caret"></span>
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
				<?php
				if($user == "") {
					echo "<p><b>Per iniziare, esegui il login premendo il bottone in alto a destra o registrati con il collegamento
					che potrai trovare su quella pagina.</b></p>";
				}	
				?>
			</div>
			<?php if($user != "") { ?>
				<?php if($user['granted'] == "Registered" || $user['granted'] == "Administrator") { ?>
					<div class="container" style="padding-top: 3%;">
						<div class="row">
							<div class="col-md-3">
								<form action="#" method="get">
									<div class="input-group">
										<input class="form-control" id="system-search" name="q" placeholder="Cerca per" autocomplete="off">
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
											$query = "SELECT `id_keyword` FROM `contains` WHERE `id_project`='".$row['id']."'";
											$keyIds = $conn->query($query);
											
											$metadata = "";
											
											while($key = $keyIds->fetch_assoc()) {
												$query = "SELECT `name` FROM `keyword` WHERE `id`='".$key['id_keyword']."'";
												$kname = $conn->query($query);
												$kname = $kname->fetch_assoc();
												$kname = $kname['name'];
												
												$metadata .= $kname.",";
											}
										?>
											<!-- UPDATE -->
											<tr class="project-container" data-toggle="modal" data-target=".projectModal" style="cursor: pointer; user-select: none;"
												data-title="<?php echo $row['title']; ?>" data-file="<?php echo $row['doc_path']; ?>"
												data-keywords="<?php echo $metadata; ?>">
												
												<td><?php echo $row['title']; ?></td>
												<td><?php echo $row['description']; ?></td>
												<td>
													<?php
													$query = "SELECT `id_responsible` FROM `project` WHERE `id`='".$row['id']."'";
													$result2 = $conn->query($query);
													$query = "SELECT `name`, `surname` FROM `user` WHERE `id`='".$row['id_responsible']."'";
													$result2 = $conn->query($query);
													$row2 = $result2->fetch_assoc();

													echo $row2['name']." ".$row2['surname'];
													?>
												</td>
												<td>
													<?php
													$query = "SELECT `id_author` FROM `participates` WHERE `id_project`='".$row['id']."'";
													$result3 = $conn->query($query);

													while($row2 = $result3->fetch_assoc()) {
														$query = "SELECT `name`, `surname` FROM `user` WHERE `id`='".$row2['id_author']."'";
														$result4 = $conn->query($query);
														$row3 = $result4->fetch_assoc();

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
				<?php
				}
				?>
				<?php if($user['granted'] == "Administrator") { ?>
					<div class="container" style="padding-top: 3%;">
						<div>
							<form method="post" action="<?php echo htmlspecialchars("http://localhost:3600/Carthafind/home.php#form-title"); ?>" autocomplete="off" id="insert" enctype="multipart/form-data">
								<div class="col-md-12">
									<div class="page-header" id="form-title">
										<h3>Aggiungi un progetto</h3>
									</div>
									
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><span class="glyphicon glyphicon-font"></span></span>
											<input type="text" name="title" class="form-control"
												placeholder="Titolo" value="<?php echo $title; ?>" maxlength="40"/>
										</div>
										<span class="text-danger"><?php echo $titleError; ?></span>
									</div>
									
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
											<input type="text" name="description" class="form-control"
												placeholder="Descrizione" value="<?php echo $description; ?>" maxlength="200"/>
										</div>
										<span class="text-danger"><?php echo $descriptionError; ?></span>
									</div>
									
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
											<select class="btn btn-default form-control" name="responsible">
												<?php
												$query = "SELECT `id` FROM `teacher`";
												$result = $conn->query($query);
												
												while($row = $result->fetch_assoc()) {
												?>
													<option>
														<?php 
														$query = "SELECT `name`, `surname` FROM `user` WHERE `id`='".$row['id']."'";;
														$result2 = $conn->query($query);
														$row2 = $result2->fetch_assoc();
														
														echo $row2['name']." ".$row2['surname'];
														?>
													</option>
												<?php
												}
												?>
											</select>
										</div>
										<span class="text-danger"><?php echo $responsibleError; ?></span>
									</div>
									
									<div class="form-group">
										<div class="input-group">
											<select class="btn btn-default form-control" id='authors-select' multiple='multiple'>
												<?php
												$query = "SELECT `id` FROM `author`";
												$result = $conn->query($query);
												
												while($row = $result->fetch_assoc()) {
												?>
													<option>
														<?php 
														$query = "SELECT `name`, `surname` FROM `user` WHERE `id`='".$row['id']."'";;
														$result2 = $conn->query($query);
														$row2 = $result2->fetch_assoc();
														
														echo $row2['name']." ".$row2['surname'];
														?>
													</option>
												<?php
												}
												?>
											</select>
											<input type="text" name="authors" id="authors" style="display:none" value="">
										</div>
										<span class="text-danger"><?php echo $authorsError; ?></span>
									</div>
									
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><span class="glyphicon glyphicon-time"></span></span>
											<input type="number" name="length" class="form-control" onkeypress="return isNumberKey(event)"
												placeholder="Durata (In ore)" value="<?php echo $length; ?>" maxlength="200"/>
										</div>
										<span class="text-danger"><?php echo $lengthError; ?></span>
									</div>
									
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><span class="glyphicon glyphicon-ok"></span></span>
											<input type="text" name="final_vote" class="form-control" onkeypress="return isNumberKey(event)"
												placeholder="Voto finale" value="<?php echo $final_vote; ?>" maxlength="200"/>
										</div>
										<span class="text-danger"><?php echo $final_voteError; ?></span>
									</div>
									
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><span class="glyphicon glyphicon-stats"></span></span>
											<input type="text" name="progress" class="form-control" onkeypress="return isNumberKey(event)"
												placeholder="Completamento (In numero intero da 1 a 100)" value="<?php echo $progress; ?>" maxlength="200"/>
										</div>
										<span class="text-danger"><?php echo $progressError; ?></span>
									</div>
									
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><span class="glyphicon glyphicon-comment"></span></span>
											<textarea name="comment" form="insert" class="form-control"
												placeholder="Commenti..." value="<?php echo $comment; ?>"></textarea>
										</div>
										<span class="text-danger"><?php echo $commentError; ?></span>
									</div>
									
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><span class="glyphicon glyphicon-th-large"></span></span>
											<textarea name="keywords" style="display:none" form="insert" class="tagarea"
												placeholder="Parole chiave, separate dal carattere spazio" value="<?php echo $keywords; ?>">
												SAMT
											</textarea>
										</div>
										<span class="text-danger"><?php echo $keywordsError; ?></span>
									</div>
									
									<div class="form-group">
										<div class="input-group">
											<span class="input-group-addon"><span class="glyphicon glyphicon-paperclip"></span></span>
											<label class="btn btn-default btn-file">
												<input type="hidden" name="MAX_FILE_SIZE" value="30000000000000"/>
												<input type="file" name="doc_path"/>
											</label>
										</div>
										<span class="text-danger"><?php echo $doc_pathError; ?></span>
									</div>
									
									<div class="form-group">
										<hr/>
									</div>
									
									<div class="form-group">
										<button type="submit" class="btn btn-block btn-primary" name="btn-login">Invia</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				<?php } ?>
			<?php } ?>
		</div>
	</div> 
	<div class="modal fade projectModal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" id="doc_title"></h4>
				</div>
				<div class="modal-body">
					<a id="doc_file" download></a>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
				</div>
			</div>
		</div>
	</div>
	<footer style="position: <?php if($user == ""){ echo "absolute"; } else { echo "relative"; } ?>;bottom: 0;width: 100%;height: 60px;line-height: 60px; background-color: #f5f5f5">
		<div class="footer-copyright py-3 text-center">
			© 2018 Copyright: Nadir Barlozzo
		</div>
	</footer>

	<script src="assets/jquery-1.11.3-jquery.min.js"></script>
	<script src="assets/js/bootstrap.min.js"></script>
	<script src="assets/js/carthafind.js"></script>
	<script src="assets/lib/js/jquery.multi-select.js"></script>
	
</body>
</html>
<?php ob_end_flush(); ?>
