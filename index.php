<!DOCTYPE html>
<html>
<head>
	<title>Image processing</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,200;1,100&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Indie+Flower&family=Montserrat:wght@200;400&display=swap" rel="stylesheet">
	<style type="text/css">
		*{
			padding: 0;
			margin: 0;
			font-family: 'Montserrat', sans-serif;
		}

		form{
			display: flex;
			height: 200px;
			align-items: center;
			justify-content: center;
			flex-direction: column;
		}

		form input[type="submit"]{
			margin-top: 10px;
			width: 100px;
			height: 40px;
			background: black;
			color: white;
			border: none;
			border-radius: 4px;
			cursor: pointer;
		}

		form input[type="file"]{
			width: 170px;
			height: 40px;
			opacity: 0;
			position: absolute;
			background: green;
			z-index: -1;
		}

		form input[type="file"] + label{
			width: 150px;
			height: 20px;
			padding: 10px;
			background: black;
			color: white;
			margin-top: 10px;
			border-radius: 4px;
			text-align: center;
			cursor: pointer;
		}

		input[type="file"]:focus + label {
			outline: 1px dotted #000;
			outline: -webkit-focus-ring-color auto 5px;
		}
		
		.output{
			padding-top: 20px;
			padding-left: 50px;
		}

	</style>
</head>
<body>

	<div class="form">


		<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
			<p>upload image of decent size and see what it tells about your image <b>Good luck!</b></p>
			<p><b>WARNING!</b> I request you to upload only .jpg and .jpeg not any other file. It won't work <b>Thanks.</b></p>
			<input type="file" id="file" name="image"  required>
			<label for="file"><i class="fa fa-upload" aria-hidden="true"></i> choose a file</label>
			<input type="submit" name="submit">

		</form>
	</div>

	<script type="text/javascript">
		var input = document.querySelector('#file');
		var label	 = input.nextElementSibling,
		labelVal = label.innerHTML;

		input.addEventListener( 'change', function( e )
		{
			var fileName = '';
			fileName = e.target.value.split( '\\' ).pop();

			if( fileName )
				label.innerHTML = fileName;
			else
				label.innerHTML = labelVal;
		});
	</script>

	<?php
		if(isset($_POST['submit'])){

			$curl = curl_init();
			$cfile = new CURLFile($_FILES['image']['tmp_name'], $_FILES['image']['type'], $_FILES['image']['name']);

			$data = array('myimage' => $cfile);
			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://microsoft-azure-microsoft-computer-vision-v1.p.rapidapi.com/analyze?VisualFeatures=categories,description,tags,color,faces",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => $data,
				CURLOPT_HTTPHEADER => array(
					"content-type: multipart/form-data",
					"x-rapidapi-host: microsoft-azure-microsoft-computer-vision-v1.p.rapidapi.com",
					"x-rapidapi-key: b57a0cbabamshcc6da139552c381p1c550ajsn17605aa32e55"
				),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);
			if ($err) {
				echo "cURL Error #:" . $err;
			} else {
				$responsed = json_decode($response);
				foreach ($responsed as $key => $value) {
					if($key=="description"){
						foreach ($value as $key => $value) {
							if($key=="captions"){
								foreach ($value as $key => $value) {
									foreach ($value as $key => $value) {
										if($key=="text"){
											echo "<p class=\"output\">I see this image as "."<b>".$value."</b></p>";
										}
										if($key=="confidence"){
											echo "<p class=\"output\">I am "."<b>".($value*100)."%</b> sure</p>";
										}

									}
								}
							}
						}
					}
				}
			}
		}


	?>
	
</body>
</html>
