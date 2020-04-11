<?php 
	include("body.htm");
	echo '<title>Registration Completed | HWL</title>';

	# below we create a PHP Data Object, $con gets the object values 
	$con = new PDO('mysql:host=localhost:3306;dbname=librarysite;charset=utf8mb4','root');
	# search check: if any existing records match info recieved from signUp form, assign response to variable 
	# '->' is an object feature, $con is object data type  
	
	$fname = $_POST['fname'];
	$nameCapitalized = ucwords($fname);
		
	$insert_check = $con -> query("SELECT * FROM useraccounts WHERE username = '$_POST[username]' OR email = '$_POST[email]' 
	OR password = '$_POST[password]' OR full_Name = '$nameCapitalized' OR phone_Number = '$_POST[pNum]'");

	# if duplicate account found, return error 
	if($insert_check -> rowcount() > 0) {
		$err2 = urlencode('<br><p style="color: red">Error Creating the Account! An account with that information already exists</p>');
		header("Location: signUp.php?signUpError=" . $err2);
		die;
	}
	# insert data into table if no errors found and info doens't already exist in db 
	else {
		/* $_FILES can be thought of as a special type of $_POST (can only exist with enctype="multipart/formdata attribute").
		When you upload a file with enctype set, it is stored as a tmp file on the server
		until we go to another page*/
		$img = $_FILES['InternPhoto'];
		$filename = $img['tmp_name'];
		
		if($filename == '') {
			if(isset($_POST['g-recaptcha-response'])) $captcha=$_POST['g-recaptcha-response'];

			if(!$captcha){
				$err2 = '<p style="color: red">Please check the the captcha form.</p>';
				header('Location: signUp.php?err2=' . $err2);
				die;
			}
			
			$sql = $con -> query("INSERT INTO useraccounts (username, email, password, full_Name, phone_Number, items_Out, items_Requested, messages, profile_Photo) 
			VALUES ('$_POST[username]', '$_POST[email]', '$_POST[password]', '$nameCapitalized', '$_POST[pNum]', '0', '0', '0', NULL)");
		} else {
			# upload photo to db user account. Curl allows us to send requests to a server 
			try {
				$img = $_FILES['InternPhoto']; # access file uploaded to submitted form 
				$filename = $img['tmp_name'];
				$openimg = fopen($filename, "r"); # open file in read mode 
				$data = fread($openimg, filesize($filename)); # read content of file and its size to variable data 
				$pvars = array("image" => base64_encode($data)); #this array is the POST data for the curl / base64 encoding lets you read data like image pixels correctly across the server without corruption of data
				$icurl = curl_init(); # begin curl cmd 

				# using imagebb API key 
				curl_setopt($icurl, CURLOPT_URL, 'https://api.imgbb.com/1/upload?key=94d704f859c00d48f65cb46a87875a09'); # use api to store image on imagebb site 
			
				curl_setopt($icurl, CURLOPT_HEADER, false);
				curl_setopt($icurl, CURLOPT_POST, true);
				curl_setopt($icurl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($icurl, CURLOPT_POSTFIELDS, $pvars);
				$upload = curl_exec($icurl);
				curl_close($icurl); # close curl cmd 
				$imgJSON = json_decode($upload);
				$imgLink = $imgJSON -> data -> display_url;	# create variable with url from imagebb upload 			
			}
			catch(Exception $e) {
				echo $e -> getMessage();		
			}
			
			$sql = $con -> query("INSERT INTO useraccounts (username, email, password, full_Name, phone_Number, items_Out, items_Requested, messages, profile_Photo) 
			VALUES ('$_POST[username]', '$_POST[email]', '$_POST[password]', '$nameCapitalized', '$_POST[pNum]', '0', '0', '0', '$imgLink')");
		}
	}
	
	echo "<center><h4 style='margin-bottom: 31%'>Thank you for joining our online library community. Enjoy access to thousands of movies, books, cd's, and ebook's.<br><br>";
	echo "<a href='signIn.php' ><u>Sign in here</u></a></h4></center>";

	include("footer.htm");
?>