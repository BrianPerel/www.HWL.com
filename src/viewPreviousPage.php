<!--
Purpose of page: receive request from viewNextPage.php and update page variable values like item number and db item_id value,
then display appropriate db data
-->

<?php
	session_start();
	include_once("../includes/body.htm");
	echo '<title>Search | HWL</title>';
	echo '<script>window.addEventListener(onload, switchNav())</script>';
	require_once("connect_db.php");

	# get num of items out, I wrote these statements to prevent itemN++ from continuing to increment if page is refreshed
	$sql = $con -> query("SELECT * FROM items_out WHERE item_holder = '$_SESSION[username]'");
	$numOfItems = $sql -> rowCount(PDO::FETCH_ASSOC);

	if(isset($_SESSION['requestViewPrevious'])) {
		if($_SESSION['requestViewPrevious'] == 'req1') {
			$_SESSION['varR']--;
			$sth = $con -> prepare("SELECT min(item_id) FROM items_requested WHERE requester = '$_SESSION[username]'");
			$sth -> execute();
			$smallest = $sth -> fetchColumn();
			$smallest += $_SESSION['varR'];
			$_SESSION['smallestReq'] = $smallest;

			$sql = $con -> query("SELECT * FROM items_requested WHERE requester = '$_SESSION[username]' AND item_id = '$smallest'");
			$results = $sql -> fetch(PDO::FETCH_ASSOC);
			$_POST['item_name'] = $results['item_name'];

			$sql = $con -> query("SELECT * FROM items WHERE item_name = '$_POST[item_name]'");
			$results = $sql -> fetch(PDO::FETCH_ASSOC);
		}
		else {
			$_SESSION['var']--;
			$sth = $con -> prepare("SELECT min(item_id) FROM items_out WHERE item_holder = '$_SESSION[username]'");
			$sth -> execute();
			$smallest = $sth -> fetchColumn();
			$smallest += $_SESSION['var'];
			$_SESSION['smallest'] = $smallest;

			$sql = $con -> query("SELECT * FROM items_out WHERE item_holder = '$_SESSION[username]' AND item_id = '$smallest'");
			$results = $sql -> fetch(PDO::FETCH_ASSOC);
			$_POST['item_name'] = $results['item_name'];

			$sql = $con -> query("SELECT * FROM items WHERE item_name = '$_POST[item_name]'");
			$results = $sql -> fetch(PDO::FETCH_ASSOC);
		}
	}

	else {
		$_SESSION['var']--;
		$sth = $con->prepare("SELECT min(item_id) FROM items_out WHERE item_holder = '$_SESSION[username]'");
		$sth -> execute();
		$smallest = $sth -> fetchColumn();
		$smallest += $_SESSION['var'];
		$_SESSION['smallest'] = $smallest;

		$sql = $con -> query("SELECT * FROM items_out WHERE item_holder = '$_SESSION[username]' AND item_id = '$smallest'");
		$results = $sql -> fetch(PDO::FETCH_ASSOC);
		$_POST['item_name'] = $results['item_name'];

		$sql = $con -> query("SELECT * FROM items WHERE item_name = '$_POST[item_name]'");
		$results = $sql -> fetch(PDO::FETCH_ASSOC);
	}

	$item_photo = $results['item_photo'];
	echo "<h2 align=center>$_POST[item_name]</h2>";

?>

<br><img src="<?=$item_photo; ?>" <?php if(empty($results)) { echo 'style="display: none"'; }?> width='250' height='230' alt='profile picture'/>

<?php
	function displayTable() {
		global $results;

		echo '<table align="center" width="50%" height="120%" border=solid black 1px style="background-color: #DCDCDC">';
		echo "<tr><td>Title: $results[item_name]</td></tr>";
		echo "<tr><td>Author: $results[author]</td></tr>";
		echo "<tr><td>ISBN: $results[ISBN]</td></tr>";
		echo "<tr><td>Item: $results[item_type]</td></tr>";
		echo "<tr><td>Publication info: $results[publication_info]</td></tr>";
		echo "<tr><td>Year released: $results[year_of_release]</td></tr>";
		echo "<tr><td>General Audience: $results[general_audience]</td></tr>";
		echo "<tr><td>Summary: $results[summary]</td></tr>";
		echo "<tr><td>Col No: $results[col_no]</td></tr>";
		echo "<tr><td>Price: $$results[price]</td></tr>";
		echo "<tr><td>Location: $results[location]</td></tr>";
		echo "<tr><td>Status: $results[status]</td></tr>";
		echo '</table><br><br>';
	}

	if(isset($_SESSION['requestViewPrevious'])) {
		if($_SESSION['requestViewPrevious'] == 'req1') {

			if($_SESSION['itemN'] < $numOfItems) {
				$_SESSION['itemN']--;
			}

			echo "<p style='margin-right: 45%'>Item #$_SESSION[itemN]</p>";

			displayTable();

			echo '<form action="cancelRequest.php" method="post" style="display: inline"><center>';

			if($results['status'] == 'Available' && !(isset($_GET['check_items_out'])) && (isset($_SESSION['num']) && $_SESSION['num'] < 3)) {
				echo "<input style='margin-right: 0.5%'; display: inline' name='checkout2' type='submit' value='Checkout Item'></input>";
			}

			echo "<input name='cancel' type='submit' value='Cancel Request' style='display: inline; margin-left: 1%; margin-right: 1.5%'></input>";

			$sql = $con -> query("SELECT * FROM user_accounts WHERE username = '$_SESSION[username]'");
			$item = $sql -> fetch(PDO::FETCH_ASSOC);
			if($item['items_requested'] > 1) {
				if($_SESSION['smallestReq'] != $_SESSION['smallestNumReq']) {
					echo "<input name='previous' type='submit' value='Previous Page' style='display: inline; margin-right: 1.5%'></input>";
				}

				if($_SESSION['smallestReq'] != $_SESSION['largestNumReq']) {
					echo "<input name='next' type='submit' value='Next Page' style='display: inline'></input><center>";
				}
			}
			echo '</form>';
		}
		else {
			if($_SESSION['itemN'] < $numOfItems) {
				$_SESSION['itemN']--;
			}

			echo "<p style='margin-right: 45%'>Item #$_SESSION[itemN]</p>";

			displayTable();

			$sql = $con -> query("SELECT * FROM items_out WHERE item_holder = '$_SESSION[username]' AND item_id = '$_SESSION[smallest]'");
			$results2 = $sql -> fetch(PDO::FETCH_ASSOC);
			echo '<table align="center" width="50%" height="120%" border=solid black 1px style="background-color: #DCDCDC">';
			echo "<tr><td>Date checked-out: $results2[checkout_date]</td></tr>";
			echo "<tr><td>Days item has been out: $results2[days_out]</td></tr>";
			echo "<tr><td>Due date: $results2[due_date]</td></tr>";
			echo "<tr><td>Renewed: $results2[renewed]</td></tr>";
			echo '</table><br>';

			$_SESSION['checkout2'] = $results['item_name'];

			echo "<form action='check-in.php' method='post'>";
				echo "<center><input name='checkIn' type='submit' value='Check-in Item' style='display: inline; margin-right: 1.5%'></input>";

				$sql = $con -> query("SELECT renewed FROM items_out WHERE item_holder = '$_SESSION[username]'");
				$item = $sql -> fetch(PDO::FETCH_ASSOC);
				if($item['renewed'] == "No") {
					echo "<input name='renew' type='submit' value='Renew Item' style='display: inline; margin-right: 1.5%'></input>";
				}

				$sql = $con -> query("SELECT * FROM items_out WHERE item_holder = '$_SESSION[username]'");
				$item = $sql -> fetchAll(PDO::FETCH_ASSOC);

				if($item > 1) {
					if($_SESSION['smallest'] != $_SESSION['smallestNum']) {
						echo "<input name='previous' type='submit' value='Previous Page' style='display: inline; margin-right: 1.5%'></input>";
					}

					echo "<input name='next' type='submit' value='Next Page' style='display: inline'></input><center>";
				}
			echo "</form>";
		}
	}
	else {
		if($_SESSION['itemN'] <= $numOfItems) {
			$_SESSION['itemN']--;
		}

		echo "<p style='margin-right: 45%'>Item #$_SESSION[itemN]</p>";

		displayTable();

		$sql = $con -> query("SELECT * FROM items_out WHERE item_holder = '$_SESSION[username]' AND item_id = '$_SESSION[smallest]'");
		$results2 = $sql -> fetch(PDO::FETCH_ASSOC);
		echo '<table align="center" width="50%" height="120%" border=solid black 1px style="background-color: #DCDCDC">';
		echo "<tr><td>Date checked-out: $results2[checkout_date]</td></tr>";
		echo "<tr><td>Days item has been out: $results2[days_out]</td></tr>";
		echo "<tr><td>Due date: $results2[due_date]</td></tr>";
		echo "<tr><td>Renewed: $results2[renewed]</td></tr>";
		echo '</table><br>';

		$_SESSION['checkout2'] = $results['item_name'];

		echo "<form action='check-in.php' method='post'>";
			echo "<center><input name='checkIn' type='submit' value='Check-in Item' style='display: inline; margin-right: 1.5%'></input>";

			$sql = $con -> query("SELECT renewed FROM items_out WHERE item_holder = '$_SESSION[username]'");
			$item = $sql -> fetch(PDO::FETCH_ASSOC);

			if($item['renewed'] == "No") {
				echo "<input name='renew' type='submit' value='Renew Item' style='display: inline; margin-right: 1.5%'></input>";
			}

			$sql = $con -> query("SELECT * FROM items_out WHERE item_holder = '$_SESSION[username]'");
			$item = $sql -> fetchAll(PDO::FETCH_ASSOC);

			if($item > 1) {
				if($_SESSION['smallest'] != $_SESSION['smallestNum']) {
					echo "<input name='previous' type='submit' value='Previous Page' style='display: inline; margin-right: 1.5%'></input>";
				}

				echo "<input name='next' type='submit' value='Next Page' style='display: inline'></input><center>";
			}
		echo "</form>";
		}

	echo '<div style="margin-bottom: 4%"></div>';
	include_once('../includes/footer2.htm');
?>