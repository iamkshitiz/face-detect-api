<?php
	include_once "config.php";

	if( isset($_REQUEST["tbl_id"]) && !empty($_REQUEST["tbl_id"]) )
	{
		$sql = "SELECT * FROM tbl_group WHERE tbl_id = ".$_REQUEST["tbl_id"];
		$result = $conn->query($sql);

		if ($result->num_rows > 0)
		{
			while($row = $result->fetch_assoc())
			{
				$all_images[] = $row["image"];
			}
		}
		else
		{
			echo "No Photos";
		}
	}
	else
	{
		exit;
	}
?>
<!doctype html>
<html class="no-js" lang="">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<title></title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<link rel="manifest" href="site.webmanifest">
		<link rel="apple-touch-icon" href="icon.png">

		<link rel="stylesheet" href="css/normalize.css">
		<link rel="stylesheet" href="css/main.css">
	</head>
	<body>
		<div>
<?php
	if( isset($all_images) && !empty($all_images) )
	{
?>
		<ul>
<?php
			foreach($all_images as $image)
			{
?>
			<li><img src="<?php echo $image; ?>"></li>
<?php
			}
?>
		</ul>
<?php
	}
?>
		</div>
		<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
		<script src="js/main.js"></script>
	</body>
</html>