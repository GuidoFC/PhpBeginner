<?php
	$conn = new mysqli("mariadb", "root", "1234", "myapp");
	if($conn->connect_error) {
		die("Connection failed: ". $conn->connect_error);
	}
	echo "Connected";
?>
