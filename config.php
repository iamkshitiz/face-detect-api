<?php
	error_reporting(E_ALL);
	ini_set('display_errors', 1);
	ini_set('memory_limit', '-1');

	$servername = "localhost";
	$username = "tweester_face_de";
	$password = "b@R=+ulUL1?t";
	$dbname = "tweester_face_detect";

	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error)
	{
		die("Connection failed: " . $conn->connect_error);
	}

	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}