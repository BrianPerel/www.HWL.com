<!DOCTYPE html>

<html>
	<head>
		<title>Sign up | HWL</title>
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
		
		<div class="create">
		<h3>Create your account</h3>
		<p>Join the network</p>
		
		<form style="border: solid 0.1px; margin: 1% 32% 1% 32%; padding: 2% 0% 2% 0%" action="signedUp.php" method="POST">
		
			<label>Username:</label><br>
			<input type="text" name="username" placeholder="Username" size="30" autocomplete='off' required></input><br><br>
			
			<label>Email:</label><br>
			<input type="email" name="email" placeholder="Email" size="30" autocomplete='off' required></input><br><br>
			
			<label>Password:</label><br>
			<input type="text" name="password" placeholder="Password" size="30" autocomplete='off' required></input><br><br>
			
			<label>Full Name:</label><br>
			<input type="text" name="fname" placeholder="Full Name" size="30" autocomplete='off' required></input><br><br>
			
			<label>Phone Number</label><br>
			<input type="text" name="pNum" placeholder="Phone Number" size="30" autocomplete='off' required></input>
			
			<br><p>By creating an account, you agree to the <a href='#a'>Terms of Service<a></p>
			
			<label for="terms">Agree to terms: </label>
			<input type="checkbox" name="terms" value="terms" required><br><br>
			
			<input type="submit">

		</form>
		
		<?php 
			# print invalid login message upon failed login 
			if(isset($_GET['signUpError'])){
				echo $_GET['signUpError'];
			}			
		?> 
		</div>
		
		<div class="footer">
			<p>By: Brian Perel &copy; <script type="text/javascript">var current_year = new Date(); document.write(current_year.getFullYear());</script></p>
		</div>
		
	</body>
</html>