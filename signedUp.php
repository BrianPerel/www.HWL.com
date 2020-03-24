<!DOCTYPE html>

<html>
	<head>
		<title>Registration Completed | HWL</title>
		<meta name="author" content="Brian Perel">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/main.css">
		<link rel="stylesheet" href="icons/favicon.css">	
	</head>
	
	<body>
		<center><div class="class1">
			<h2>Henry Whittemore Library</h2>
			<a href="index.htm"><img src="icons/1.jpg" alt="Smiley face" width="100px" height="70px" style="padding-top: 1%"></img></a>
			<h2>Inventory Management System</h2><br><br>
		</div>

		<div class="class2">
			<a href="index.php">Home</a>
			<a href="signIn.php">Sign-in</a>
			<a href="signUp.php">Sign-up</a>
			<a href="advSearch.php">Search</a>
			<a href="#BodyText">About</a>
			<a href="#contact">Contact Us</a>
			<a href="https://www.framingham.edu/" target="_blank">myFramingham.edu</a>
		</div>
		
		<?php 
			$con = new PDO('mysql:host=localhost:3306;dbname=librarysite;charset=utf8mb4','root');
			$insert_check = $con -> query("SELECT * FROM useraccounts WHERE username = '$_POST[username]' OR email = '$_POST[email]' 
			OR password = '$_POST[password]' OR full_Name = '$_POST[fname]' OR phone_Number = '$_POST[pNum]'");
			
			if(!(preg_match("/@/", $_POST['email']))) {
				echo 'error';
			}

			if($insert_check -> rowcount() > 0) {
				$err = urlencode('<br><p style="color: red">Error Creating the Account! An account with certain information you entered already exists</p>');
				header("Location: signUp.php?signUpError=" . $err);
				die;
			}
			
			else {
				$sql = $con -> query("INSERT INTO useraccounts (username, email, password, full_Name, phone_Number, items_Out, items_Requested, messages) 
				VALUES ('$_POST[username]', '$_POST[email]', '$_POST[password]', '$_POST[fname]', '$_POST[pNum]', '0', '0', '0')");
			}
		?>
		
		<center><h4 style='margin-bottom: 31%'>Thank you for joining our online library community. Enjoy access to thousands of movies, books, cd's, and ebook's.<br><br>
		<a href='signIn.php' >Sign in here</a></h4></center>
		
		<div class="footer">
			<p>By: Brian Perel &copy; <script type="text/javascript">var current_year = new Date(); document.write(current_year.getFullYear());</script></p>
		</div>
		
	</body>
</html>