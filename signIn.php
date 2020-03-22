<!DOCTYPE html>

<html>
	<head>
		<title>Sign in | HWL</title>
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
			<a href="search.php">Search</a>
			<a href="#about">About</a>
			<a href="https://www.framingham.edu/" target="_blank">myFramingham.edu</a>
		</div>
		
		<h3>My Account Login:</h3><br>
		
		<form action='myAccount.php' method='post' style='border: solid black 1px; width: 20%; padding: 1%' autocomplete="off">
			<label>Username:</label><br>
			<input type="text" name="username" placeholder="Username" size="30" autocomplete="false" required></input><br><br>
			
			<label>Password:</label><br>
			<input name="password" placeholder="Password" type="text" size="30" autocomplete="false" required></input><br>
			
			<br><input type="submit">
		</form>		
		
		<?php 
			# print invalid login message upon failed login 
			if(isset($_GET['Message'])){
				echo $_GET['Message'];
			}
		?> 
		
		<div style="margin-top: 18%"></div>
		
		<div class="footer">
			<p>By: Brian Perel &copy; <script type="text/javascript">var current_year = new Date(); document.write(current_year.getFullYear());</script></p>
		</div>
	
	</body>
</html>