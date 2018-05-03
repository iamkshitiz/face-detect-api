<?php
	include_once "config.php";

	$sql = "SELECT * FROM tbl_solo";
	$result = $conn->query($sql);

	if ($result->num_rows > 0)
	{
		$path = 'img/group';
		$files = scandir($path);
		$files = array_diff(scandir($path), array('.', '..'));

		while($row = $result->fetch_assoc())
		{
			if( isset($files) && !empty($files) )
			{
				foreach($files as $file)
				{
					$image = "https://www.tweester.in/face_detect/img/group/".$file;
					$tbl_id = $row["ID"];
					$subject_id = $row["subject_id"];
					$gallery_name = $row["gallery_name"];

					$queryUrl = "https://api.kairos.com/verify";
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

					if( isset($response->images) && !empty($response->images) )
					{
						foreach($response->images as $image2)
						{
							if( $image2->transaction->confidence > 0.6 )
							{
								$sql = "INSERT INTO tbl_group (image, gallery_name, tbl_id, subject_id)
								VALUES ('".$image."', '".$gallery_name."', '".$tbl_id."', '".$subject_id."')";

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
				}
			}
		}
	}
	else
	{
		echo "No Solo Photos";
	}