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
		<script src="js/main.js"></script>
	</head>
	<body>

		<header></header>

		<main>
			<div class="desktop_view">
				<canvas id="canvas" width="640" height="480" style="display: none;"></canvas>
				<video id="video" autoplay></video>
				<div class="butn-01">
					<button id="snap">Capture</button>
					<button id="new">New</button>
					<form action="saveImage.php" method="post" enctype="multipart/form-data">
						<input type="hidden" name="fileToUpload" id="fileToUpload">
						<input type="submit" value="Upload" name="submit" id="submit" class="btn-2">
					</form>
				</div>
			</div>
			<div class="mobile_view">
				<form action="saveImage2.php" method="post" enctype="multipart/form-data">
					<input type="file" accept="image/*" capture="camera" name="fileToUpload" class="hidden" id="open_camera" />
					<input type="button" id="click_button" value="Open Camera" class="btn-2 margin-btm">
					<input type="submit" value="Upload" name="submit" id="submit2" class="btn-2">
				</form>
			</div>
			<div id="errorMsg"></div>
		</main>

		<footer></footer>

		<script>
			$("#click_button").click(function() {
				$("#open_camera").click();
				$("#submit2").css("display", "block");
			});

			window.addEventListener("DOMContentLoaded", function()
			{
				var errorElement = document.querySelector('#errorMsg');
				var canvas = document.getElementById("canvas"),
				context = canvas.getContext("2d"),
				video = document.getElementById("video"),
				videoObj = window.constraints = {
					audio: false,
					video: true
				};
				errBack = function(error) {
					console.log("Video capture error: ", error.code); 
				};

				function handleSuccess(stream) {
					var videoTracks = stream.getVideoTracks();
					console.log('Got stream with constraints:', constraints);
					console.log('Using video device: ' + videoTracks[0].label);
					stream.oninactive = function() {
						console.log('Stream inactive');
					};
					window.stream = stream; // make variable available to browser console
					video.srcObject = stream;
				}

				function handleError(error) {
					if (error.name === 'ConstraintNotSatisfiedError') {
						errorMsg('The resolution ' + constraints.video.width.exact + 'x' +
						constraints.video.width.exact + ' px is not supported by your device.');
					} else if (error.name === 'PermissionDeniedError') {
						errorMsg('Permissions have not been granted to use your camera and ' +
						'microphone, you need to allow the page access to your devices in ' +
						'order for the demo to work.');
					}
					errorMsg('getUserMedia error: ' + error.name, error);
				}

				function errorMsg(msg, error) {
					errorElement.innerHTML += '<p>' + msg + '</p>';
					if (typeof error !== 'undefined') {
						console.error(error);
					}
				}

				navigator.mediaDevices.getUserMedia(videoObj).then(handleSuccess).catch(handleError);

				document.getElementById("snap").addEventListener("click", function()
				{
					context.drawImage(video, 0, 0, 640, 480);
					$('#video').hide();
					$('#canvas').show();
					$('#snap').hide();
					$('#new').show();
					$('#submit').show();
					$("#fileToUpload").val(canvas.toDataURL());
				});
				document.getElementById("new").addEventListener("click", function()
				{
					$('#video').show();
					$('#canvas').hide();
					$('#snap').show();
					$('#new').hide();
					$('#submit').hide();
				});
			}, false);
		</script>
	</body>
</html>