<!--
Purpose of webpage: receive request to login from signIn.php, if user entered information matches username and password proceed,
check number of days all items in user's checkout queue have been out, update fines if needed, display mini menu for user
-->

<?php
	session_start();
	include_once('../includes/body.htm');
	echo '<title>My Account | HWL</title>';
	echo '<meta http-equiv="refresh" content="120; url=logout.php?expire">';
	require_once("connect_db.php");

	if($_SESSION['loggedin']) {
		$sql = $con -> query("SELECT * FROM user_accounts WHERE username = '$_SESSION[username]'");
		$results = $sql -> fetch(PDO::FETCH_ASSOC);
		$profilePhoto = $results['user_photo'];
		$_SESSION['outCheck'] = $results['items_out'];
		$_SESSION['requested'] = $results['items_requested'];
	}

	elseif(!$_SESSION['loggedin']) {
		$sql = $con -> query("SELECT * FROM user_accounts WHERE username = '$_POST[username]'");
		$results = $sql -> fetchAll(PDO::FETCH_ASSOC);

		# need loop to check sign in info sent compared to every useraccount row until match is found
		for($i = 0; $i < sizeof($results); $i++) {
			if($results[$i]['username'] == $_POST['username'] && $results[$i]['password'] == $_POST['password']) {
				$_SESSION['loggedin'] = true;
				$_SESSION['username'] = $_POST['username'];
				$profilePhoto = $results[$i]['user_photo'];
				$_SESSION['outCheck'] = $results[$i]['items_out'];
				$_SESSION['requested'] = $results[$i]['items_requested'];
				break;
			}
		}
	}
?>

<h2>My Account</h2>

<div class="row">
	<div class="col-sm-12">
		<br><img src="<?=$profilePhoto?>" <?php if(empty($profilePhoto)) { echo 'style="display: none"'; }?>
		width='250' height='240' alt='profile picture'/>
	</div>
</div>

<?php

	define("FINE", "4.50"); # create constant to hold fines amount

	# re-direct back to sign in page
	if(!$_SESSION['loggedin']) {
		$invalidLogin = urlencode('<br><p style="color: red">Sorry, the information you submitted was invalid. Please try again</p>');
		header("location: signIn.php?message=$invalidLogin");
	}

	elseif($_SESSION['loggedin']) {
		echo '<script>window.addEventListener(onload, switchNav())</script>';

		# enter condition if user has more than 1 item out
		if($_SESSION['outCheck'] > 0) {
			// get the number of items out
			$sql = $con -> query("SELECT items_out FROM user_accounts WHERE username = '$_SESSION[username]'");
			$res = $sql -> fetch(PDO::FETCH_ASSOC);

			// get the smallest ID num
			$sth = $con -> prepare("SELECT min(item_id) FROM items_out WHERE item_holder = '$_SESSION[username]'");
			$sth -> execute();
			$smallest = $sth -> fetchColumn();

			for($i = 0; $i < $res['items_out']; $i++) {
				# update days out on every login
				date_default_timezone_set('America/New_York');
				$sql = $con -> query("SELECT checkout_date FROM items_out WHERE item_holder = '$_SESSION[username]' && item_id = '$smallest'");
				$date = $sql -> fetch(PDO::FETCH_ASSOC);
				$date_out = $date['checkout_date'];
				$days_out = intval(date("d")) - intval($date_out[3] . $date_out[4]);

				# take into account months, so if now its the first day of the next month then days out would be set to 31 not 1 since we add 30 to 1
				if(($date_out[0] . $date_out[1]) < date('m')) {   # if item checkout date month num is less than current date month then item has been out for more than 1 month
					$num_months_out = (int) date('m') - ((int) $date_out[0] . $date_out[1]); # need to cast from string type to int for date('m'), get number of months item has been out, by subtracting current month num from checkout date month num

					# only add months to days out variable if it hasn't been done. If months have already been added don't add months again
					$days_out += ($num_months_out * 30);
				}

				$days_out = strval($days_out);

				$sql = $con -> query("UPDATE items_out SET days_out = '$days_out' WHERE item_holder = '$_SESSION[username]' && item_id = '$smallest'");
				# get full due and current dates
				$sql = $con -> query("SELECT due_date FROM items_out WHERE item_holder = '$_SESSION[username]'");
				$Due = $sql -> fetch(PDO::FETCH_ASSOC);
				$date_due = $Due['due_date'];
				$currentDate = date('m/d/YY');

				if($currentDate > $date_due) {
					$sql = $con -> query("SELECT fines_fees FROM user_accounts WHERE username = '$_SESSION[username]'");
					$fees = $sql -> fetch(PDO::FETCH_ASSOC);
					$fee = $fees['fines_fees'];
					$fee == FINE ? $fee += 0 : $fee += FINE;
					$sql = $con -> query("UPDATE user_accounts SET fines_fees = '$fee' WHERE username = '$_SESSION[username]'");
				}

				$smallest++;
			}
		}

		$_SESSION['items_out'] = $_SESSION['outCheck'];
		$_SESSION['items_requested'] = $_SESSION['requested'];

		$sql = $con -> query("SELECT * FROM user_accounts WHERE username = '$_SESSION[username]'");
		$results = $sql -> fetch(PDO::FETCH_ASSOC);
?>

		<div class="row"><div class="col-sm-12"><br><p>Login successful<br><?="Welcome back, $results[full_name] <br>Email: $results[email]"?></div></div>
		<div class="row"><div class="col-sm-12"><a href="viewCheckouts.php"><?="Checkouts: ($results[items_out])"?></a></div></div>
		<div class="row"><div class="col-sm-12"><a href="viewRequests.php"><?="Requests: ($results[items_requested])"?></a></div></div>
		<div class="row"><div class="col-sm-12"><a href="#" onclick="alertFinesMessage()"><?="Fines/Fees: $$results[fines_fees]"?></a></div></div>
		<div class="row"><div class="col-sm-12"><a href="logout.php">(log out)</a></p></div></div>

<?php
		echo "<form action='editPersonalInfo.php' method='post'>";
		echo "<button style='float: right; display: inline; height: 8%; margin-right: 1%'>Edit Personal Information</button>";
		echo "</form>";
	}

	include_once("../includes/footer2.htm");
?>