<!--
Purpose of program: prompt user to fill out sign up form, once filled out send the data form to addUser.php so that it can be added to the library database
-->

<?php
	session_start();
	include_once("../includes/body.htm");
?>

<title>Sign up | HWL</title>
<h3>Create your account</h3>
<p>Join the network</p>

<center><form class="form justify-content-center" style="border: solid 0.1px; width: 70%; margin: 1% 0% 1% 0%; padding: 2% 3% 2% 3%; background-color: #DCDCDC" action="addUser.php" method="POST" enctype="multipart/form-data" autocomplete="off">
	<label>Username:</label><br>
	<input class="form-control" autofocus type="text" name="username" placeholder="Username" autocomplete="off" size="32" required></input><br><br>

	<label>Email:</label><br>
	<input class="form-control" type="email" name="email" placeholder="name@domain.com" size="32" autocomplete="off" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required></input><br><br>

	<label>Password: </label><br>
	<input class="form-control" type="password" id="pass" name="password" placeholder="Password" autocomplete="off" size="32" required></input><br/>
	<!-- An element (checkbox) to toggle between password visibility -->
	<input type="checkbox" onclick="togglePasswordVisibility()">&nbsp;Show Password</input><br><br>

	<label>Full Name: (example: John Doe)</label><br>
	<input class="form-control" type="text" name="fname" placeholder="Full Name" size="32" autocomplete="off" pattern="^(\w\w+)\s(\w+)$" required></input><br><br>

	<label>Phone Number:</label><br>
	<input class="form-control" type="tel" name="pNum" placeholder="111-222-3333" size="32" autocomplete="off" pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}" maxlength="12" required></input><br><br><br>

	<label>Upload Profile Picture (Optional)</label><br>
	<input class="form-control" type="file" name="photo" autocomplete='off' style="padding-bottom: 4%" class="chose"></input><br>

	<br><p>By creating an account, you agree to the terms of service</p>

	<label for="terms">Agree to terms of service:</label>
	<input type="checkbox" name="terms" value="terms" required>

	&nbsp;&nbsp;<u><a href="../includes/terms.htm" target="_blank">View terms of service</a></u><br><br>

	<?php include_once("../includes/recaptcha_connect.htm"); ?>

	<input type="submit" class="btn btn-primary">
</form></center>

<?php
	# print invalid login message upon failed login
	if(isset($_GET['signUpError'])) {
		echo "<center>$_GET[signUpError]</center>";
	}

	include_once("../includes/footer2.htm");
?>