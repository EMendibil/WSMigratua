<?php
	// // Datuak eskuratzeko konstanteak ...
	DEFINE("_HOST_", "localhost");
	DEFINE("_PORT_", "8080");
	DEFINE("_USERNAME_", "root");
	DEFINE("_DATABASE_", "quiz");
	DEFINE("_PASSWORD_", "");
	require_once 'database.php';

	$method = $_SERVER['REQUEST_METHOD'];
	$resource = $_SERVER['REQUEST_URI'];

	try {
		$cnx = Database::Konektatu();
		switch ($method) {
		case 'GET': // GET eskaera bat tratatzeko kodea
			$sql = "SELECT * FROM vip";
			$data = Database::GauzatuKontsulta($cnx, $sql);
			if (isset($data[0])){
				echo json_encode($data[0]);
			}else {
				echo "Ez dago VIP erabiltzailerik.";
			}
			break;
		case 'POST': // idem POST
			$arguments = $_POST;
			$result = 0;
			$eposta = $arguments['eposta'];
			$sql = "INSERT INTO vip(eposta) VALUES ('$eposta');";
			$num=Database::GauzatuEzKontsulta($cnx, $sql);
			if ($num==0){
				echo json_encode(array('VIP da dagoeneko.' => $eposta));
			}else {
				echo json_encode(array('VIP sortua' => $eposta));
			}
			break;
		case 'DELETE': //idel DELETE
			$arguments = $_REQUEST;
			$eposta=$arguments['eposta'];
			$sql = "DELETE FROM vip WHERE eposta = $eposta;";
			$result = Database::GauzatuEzKontsulta($cnx, $sql);
			if ($result == 0){
				echo json_encode(array('Ez dago helbide elektronikoa' => $eposta));
			} else {
				echo json_encode(array('Row deleted' => $eposta));
			}
			break;
	}

	Database::Deskonektatu($cnx);

	} catch (Exception $ex) {
		header('HTTP/1.0 400 Bad Request');
	}
?>