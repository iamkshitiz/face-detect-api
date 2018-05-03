<?php
	include_once "config.php";

	$queryUrl = "https://api.kairos.com/gallery/remove";
	$imageObject = '{"gallery_name":"webcontxt"}';
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

	$queryUrl = "https://api.kairos.com/gallery/list_all";
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
	$response = curl_exec($request);
	$response = json_decode($response);
	curl_close($request);

	echo "<pre>";
	print_r($response);
	exit;