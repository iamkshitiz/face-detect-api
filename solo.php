<?php
	include_once "config.php";

	$path = 'img/solo';
	$files = scandir($path);
	$files = array_diff(scandir($path), array('.', '..'));

	if( isset($files) && !empty($files) )
	{
		foreach($files as $file)
		{
			$ext = pathinfo($file, PATHINFO_EXTENSION);

			$image = "https://www.tweester.in/face_detect/img/solo/".$file;
			$subject_id = basename($image, ".".$ext);
			$gallery_name = "webcontxt";

			$sql = "SELECT * FROM `tbl_solo`";
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();

			if ( $row["subject_id"] != $subject_id )
			{
				$queryUrl = "https://api.kairos.com/enroll";
				$imageObject = '{"image":"'.$image.'", "subject_id":"'.$subject_id.'", "gallery_name":"'.$gallery_name.'"}';
				$APP_ID = "bb41f359";
				$APP_KEY = "c440684822bda3f4fe808a3da71e5cc1";
				$request = curl_init($queryUrl);

				curl_setopt($request, CURLOPT_POST, true);
				curl_setopt($request,CURLOPT_POSTFIELDS, $imageObject);
				curl_setopt($request, CURLOPT_HTTPHEADER, array(
					"Content-type: application/json",
						"app_id:" . $APP_ID,
						"app_key:" . $APP_KEY
					)
				);

				curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
				$response = curl_exec($request);
				$response = json_decode($response);
				curl_close($request);

				$sql = "INSERT INTO tbl_solo (image, gallery_name, subject_id)
				VALUES ('".$image."', '".$gallery_name."', '".$subject_id."')";

				if ($conn->query($sql) === TRUE)
				{
					echo "New record created successfully<br>";
				}
				else
				{
					echo "Error: " . $sql . "<br>" . $conn->error;
				}
			}
		}
	}