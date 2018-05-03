<?php
	ini_set('memory_limit', '-1');

	$rawData = $_POST['fileToUpload'];
	$filteredData = explode(',', $rawData);
	$unencoded = base64_decode($filteredData[1]);
	$fileName = "img/uploads/".md5( rand(0, 99999) );
	$randomName = $fileName.'.png';

	$fp = fopen($randomName, 'w');
	fwrite($fp, $unencoded);
	fclose($fp);

	$randomName = "https://www.tweester.in/face_detect/".$randomName;

	$queryUrl = "https://api.kairos.com/v2/media?source=".$randomName;
	$APP_ID = "bb41f359";
	$APP_KEY = "c440684822bda3f4fe808a3da71e5cc1";
	$request = curl_init($queryUrl);

	curl_setopt($request, CURLOPT_POST, true);
	curl_setopt($request, CURLOPT_HTTPHEADER, array(
		"Content-type: application/json",
			"app_id:" . $APP_ID,
			"app_key:" . $APP_KEY
		)
	);

	curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
	$response2 = curl_exec($request);
	$response2 = json_decode($response2);
	curl_close($request);

	if( isset($response->images[0]->faces) && !empty($response->images[0]->faces) )
	{
		$i = 0;
		$allImages = "";
		foreach($response->images[0]->faces as $face)
		{
			$randomName2 = $fileName.'_'.$i.'.png';

			$allImages[$i] = $randomName2;

			$im = imagecreatetruecolor($face->width,$face->height);
			imagepng($im,$randomName2,9);
			imagedestroy($im);

			$sourceImage = imagecreatefrompng($randomName);
			$destinationImage = imagecreatefrompng($randomName2);

			imagecopy($destinationImage, $sourceImage, 0, 0, $face->topLeftX, $face->topLeftY, $face->width, $face->height);
			imagepng($destinationImage, $randomName2);

			imagedestroy($destinationImage);
			imagedestroy($sourceImage);

			$i++;
		}
	}
?>
<!doctype html>
<html class="no-js" lang="">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="x-ua-compatible" content="ie=edge">
		<title>Face Detection</title>
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<link rel="manifest" href="site.webmanifest">
		<link rel="apple-touch-icon" href="icon.png">

		<link rel="stylesheet" href="css/main.css">

		<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
		<script src="https://safi.me.uk/typewriterjs/js/typewriter.js"></script>
		<script src="js/main.js"></script>
	</head>
	<body>

		<header></header>

		<main>
<?php
	if( isset($response2) && !empty($response2) )
	{
		foreach($response2->frames[0]->people as $people)
		{
			$emotions = "";
			$emotions["anger"] = $people->emotions->anger;
			$emotions["disgust"] = $people->emotions->disgust;
			$emotions["fear"] = $people->emotions->fear;
			$emotions["joy"] = $people->emotions->joy;
			$emotions["sadness"] = $people->emotions->sadness;
			$emotions["surprise"] = $people->emotions->surprise;
			$emotion_key[] = array_search(max($emotions), $emotions);
		}
	}

	if( isset($response->images[0]->faces) && !empty($response->images[0]->faces) )
	{
		$i = 0;
		foreach($response->images[0]->faces as $face)
		{
			if($face->attributes->asian > 0.5)
				$txtEthnicity = 'Ethnicity: Asian';
			if($face->attributes->black > 0.5)
				$txtEthnicity = 'Ethnicity: African-American';
			if($face->attributes->white > 0.5)
				$txtEthnicity = 'Ethnicity: Caucasian';
			if($face->attributes->hispanic > 0.5)
				$txtEthnicity = 'Ethnicity: Spanish';
			if($face->attributes->other > 0.5)
				$txtEthnicity = 'Ethnicity: Other';

			$txtEmotion = 'Emotion:'.$emotion_key[$i];
?>
			<div class="photo-box">
				<img src='<?php echo $allImages[$i]; ?>'>
				<div class="age" id="age<?php echo $i; ?>"></div>
				<div class="gender" id="gender<?php echo $i; ?>"></div>
				<div class="ethnicity" id="ethnicity<?php echo $i; ?>"></div>
				<div class="emotion" id="emotion<?php echo $i; ?>"></div>
			</div>
<script>
	txtAge = 'Age: <?php echo $face->attributes->age; ?>';
	txtGender = 'Gender: <?php echo ($face->attributes->gender->type == "M") ? "Male" : "Female"; ?>';
	txtEthnicity = '<?php echo $txtEthnicity; ?>';
	txtEmotion = '<?php echo $txtEmotion; ?>';

	var age<?php echo $i; ?> = document.getElementById('age<?php echo $i; ?>');
	var typewriterAge<?php echo $i; ?> = new Typewriter(age<?php echo $i; ?>, {
		loop: false
	});
	typewriterAge<?php echo $i; ?>.typeString(txtAge).start();

	var gender<?php echo $i; ?> = document.getElementById('gender<?php echo $i; ?>');
	var typewriterGender<?php echo $i; ?> = new Typewriter(gender<?php echo $i; ?>, {
		loop: false
	});
	typewriterGender<?php echo $i; ?>.typeString(txtGender).start();

	var ethnicity<?php echo $i; ?> = document.getElementById('ethnicity<?php echo $i; ?>');
	var typewriterEthnicity<?php echo $i; ?> = new Typewriter(ethnicity<?php echo $i; ?>, {
		loop: false
	});
	typewriterEthnicity<?php echo $i; ?>.typeString(txtEthnicity).start();

	var emotion<?php echo $i; ?> = document.getElementById('emotion<?php echo $i; ?>');
	var typewriterEmotion<?php echo $i; ?> = new Typewriter(emotion<?php echo $i; ?>, {
		loop: false
	});
	typewriterEmotion<?php echo $i; ?>.typeString(txtEmotion).start();
</script>
<?php
			$i++;
		}
	}
?>
		</main>

		<footer></footer>

	</body>
</html>