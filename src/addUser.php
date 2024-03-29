<!--
Purpose of webpage: receive user form from signup.php and process it; capitalize first letters of first and last name.
Perform duplicate query check, make sure recaptcha tool was checked, trim whitespace from username, email, and password.
Attach default or custom image. Finally insert all data into db and display message with link
-->

<?php
	session_start();
	include_once("../includes/body.htm");
	echo '<title>Registration Completed | HWL</title>';
	require_once("connect_db.php");

	# capitalize the first letter of every word from fname form input field
	$fname = ucwords($_POST['fname']);

	# do a db search to check: if any existing records match info recieved from signUp form, assign response to variable
	$insert_check = $con -> query("SELECT * FROM user_accounts WHERE username = '$_POST[username]' OR email = '$_POST[email]'
	OR password = '$_POST[password]' OR full_name = '$fname' OR phone_number = '$_POST[pNum]'");

	# if duplicate account found, return error
	if($insert_check -> rowcount() > 0) {
		$err2 = urlencode('<br><p style="color: red">Error Creating the Account! An account with that information already exists</p>');
		header("Location: signUp.php?signUpError=$err2");
		die;
	}
	# insert data into table if no errors found and info doens't already exist in db
	else {
		if(isset($_POST['g-recaptcha-response'])) {
			$captcha = $_POST['g-recaptcha-response'];
		}

		if(!$captcha) {
			$err2 = '<p style="color: red">Please check the the captcha form.</p>';
			header("Location: signUp.php?signUpError=$err2");
			die;
		}

		# if file size of file field is 0, then user didn't upload image so upload default
		if($_FILES['photo']['size'] == 0) {
			$imgLink = "../images/default-picture.png";
		}
		else {
			# upload photo to db user account. Curl allows us to send requests to a server
			$img = $_FILES['photo']; # access file uploaded to submitted form
			$filename = $img['tmp_name']; # access $img object attribute need variable name used
			$openimg = fopen($filename, "r"); # open file in read mode
			$data = fread($openimg, filesize($filename)); # read content of file and its size to variable data
			$pvars = array("image" => base64_encode($data)); #this array is the POST data for the curl / base64 encoding lets you read data like image pixels correctly across the server without corruption of data
			$icurl = curl_init(); # begin curl cmd

			# using imagebb API key to store image on the imagebb site
			curl_setopt($icurl, CURLOPT_URL, 'https://api.imgbb.com/1/upload?key=94d704f859c00d48f65cb46a87875a09');
			curl_setopt($icurl, CURLOPT_HEADER, false);
			curl_setopt($icurl, CURLOPT_POST, true);
			curl_setopt($icurl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($icurl, CURLOPT_POSTFIELDS, $pvars);
			$upload = curl_exec($icurl); # execute curl cmd
			curl_close($icurl); # close curl cmd
			$imgJSON = json_decode($upload);
			$imgLink = $imgJSON -> data -> display_url;	# create variable with url from imagebb upload
		}

		# trim whitespace from post data fields
		$_POST['username'] = trim($_POST['username']);
		$_POST['email'] = trim($_POST['email']);
		$_POST['password'] = trim($_POST['password']);

		# insert record into table
		$sql = $con -> query("INSERT INTO user_accounts (username, email, password, full_name, phone_number, items_out, items_requested, messages, user_photo)
		VALUES ('$_POST[username]', '$_POST[email]', '$_POST[password]', '$fname', '$_POST[pNum]', '0', '0', '0', '$imgLink')");

		$sql = $con -> query("SELECT * FROM user_accounts WHERE username = '$_POST[username]'");
		$results = $sql -> fetch(PDO::FETCH_ASSOC);
		$num = $results['user_id'];
	}

	# create PHP object $user and store post data in its variables
	@$user -> username = "$_POST[username]";
	$user -> email = "$_POST[email]";
	$user -> password = "$_POST[password]";
	$user -> fullName = "$fname";
	$user -> phone_number = "$_POST[pNum]";

	# function used to encode PHP object to JSON format

	# if a folder named JSON doesn't exist in root directory then create JSON folder
	if(!file_exists('../JSON')) {
		mkdir("../JSON", 0755);
	}

	# every file name should be different to avoid overwriting, create the file and store JSON formatted object in it
	$name = "user#$num.json";
	$file = fopen("../JSON/$name", "w");
	fwrite($file, json_encode($user));
	fclose($file);

	echo "<center><h4>Thank you for joining our online library community. Enjoy access to thousands of movies, books, cd's, and ebook's.<br><br>
	<a href='signIn.php'><u>Login here</u></a></h4><br>
	<img src='../images/lib.jpg' width='60%' height='60%'></img></center>";

	include_once("../includes/footer2.htm");
?>