<!-- Purpose of page: recieve request from viewNextPage.php and update page variable values like item number and db itemID value, then display appropriate db data --> 

<?php
	session_start(); 
	include("../includes/body.htm");
	echo '<title>Search | HWL</title>';
	echo '<script>window.addEventListener(onload, switchNav())</script>';
	require("../includes/connect_db.php");
	
	# get num of items out, I wrote these statements to prevent itemN++ from continuing to increment if page is refreshed 
	$sql = $con -> query("SELECT * FROM itemsout WHERE item_Holder = '$_SESSION[username]'");
	$numOfItems = $sql -> rowCount(PDO::FETCH_ASSOC);

	if(isset($_SESSION['requestViewPrevious'])) {
		if($_SESSION['requestViewPrevious'] == 'req1') { 
			$_SESSION['varR']--;
			$sth = $con -> prepare("SELECT min(itemID) FROM itemsreq WHERE requester = '$_SESSION[username]'");
			$sth -> execute();
			$smallest = $sth -> fetchColumn();
			$smallest += $_SESSION['varR'];
			$_SESSION['smallestReq'] = $smallest; 
			
			$sql = $con -> query("SELECT * FROM itemsreq WHERE requester = '$_SESSION[username]' AND itemID = '$smallest'");
			$results = $sql -> fetch(PDO::FETCH_ASSOC);
			$_POST['item_name'] = $results['item_Name']; 
			
			$sql = $con -> query("SELECT * FROM items WHERE Item_Name = '$_POST[item_name]'");
			$results = $sql -> fetch(PDO::FETCH_ASSOC);
		}
		
		else {
			$_SESSION['var']--;
			$sth = $con->prepare("SELECT min(itemID) FROM itemsout WHERE item_Holder = '$_SESSION[username]'");
			$sth -> execute();
			$smallest = $sth -> fetchColumn();
			$smallest += $_SESSION['var'];
			$_SESSION['smallest'] = $smallest;
			
			$sql = $con -> query("SELECT * FROM itemsout WHERE item_Holder = '$_SESSION[username]' AND itemID = '$smallest'");
			$results = $sql -> fetch(PDO::FETCH_ASSOC);
			$_POST['item_name'] = $results['item_Name']; 
				
			$sql = $con -> query("SELECT * FROM items WHERE Item_Name = '$_POST[item_name]'");
			$results = $sql -> fetch(PDO::FETCH_ASSOC);
		}
	}

	else {
		$_SESSION['var']--;
		$sth = $con->prepare("SELECT min(itemID) FROM itemsout WHERE item_Holder = '$_SESSION[username]'");
		$sth -> execute();
		$smallest = $sth -> fetchColumn();
		$smallest += $_SESSION['var'];
		$_SESSION['smallest'] = $smallest;
		
		$sql = $con -> query("SELECT * FROM itemsout WHERE item_Holder = '$_SESSION[username]' AND itemID = '$smallest'");
		$results = $sql -> fetch(PDO::FETCH_ASSOC);
		$_POST['item_name'] = $results['item_Name']; 
			
		$sql = $con -> query("SELECT * FROM items WHERE Item_Name = '$_POST[item_name]'");
		$results = $sql -> fetch(PDO::FETCH_ASSOC);
	}
	
	$photo = $results['photo'];
	echo "<h2 align=center>$_POST[item_name]</h2>";
	
?>

<br><img src="<?=$photo; ?>" <?php if(sizeof($results) == 0) { echo 'style="display: none"'; }?> width='250' height='230' alt='profile picture'/>

<?php 
	function displayTable() {
		global $results;
		
		echo '<table align="center" width="50%" height="120%" border=solid black 1px style="background-color: #DCDCDC">';
		echo "<tr><td>Title: $results[Item_Name]</td></tr>";
		echo "<tr><td>Author: $results[Author]</td></tr>";
		echo "<tr><td>ISBN: $results[ISBN]</td></tr>";
		echo "<tr><td>Item: $results[Item_Type]</td></tr>";
		echo "<tr><td>Publication info: $results[Publication_Info]</td></tr>";
		echo "<tr><td>Year released: $results[Year_of_Release]</td></tr>";
		echo "<tr><td>General Audience: $results[General_Audience]</td></tr>";
		echo "<tr><td>Summary: $results[Summary]</td></tr>";
		echo "<tr><td>Col No: $results[Col_No]</td></tr>";
		echo "<tr><td>Price: $$results[Price]</td></tr>";
		echo "<tr><td>Location: $results[Location]</td></tr>";
		echo "<tr><td>Status: $results[Status]</td></tr>";
		echo '</table><br><br>';
	}

	if(isset($_SESSION['requestViewPrevious'])) {
		if($_SESSION['requestViewPrevious'] == 'req1') {
			if($_SESSION['itemN'] < $numOfItems) $_SESSION['itemN']--;
			echo "<p style='margin-right: 45%'>Item #$_SESSION[itemN]</p>";
				
			displayTable();
			
			echo '<form action="cancelRequest.php" method="post" style="display: inline"><center>';
			
			if($results['Status'] == 'Available' && !(isset($_GET['check_items_out'])) && (isset($_SESSION['num']) && $_SESSION['num'] < 3)) {
				echo "<input style='margin-right: 0.5%'; display: inline' name='checkout2' type='submit' value='Checkout Item'></input>";
			}
		
			echo "<input name='cancel' type='submit' value='Cancel Request' style='display: inline; margin-left: 1%; margin-right: 1.5%'></input>";
			
			$sql = $con -> query("SELECT * FROM useraccounts WHERE username = '$_SESSION[username]'");
			$item = $sql -> fetch(PDO::FETCH_ASSOC); 
			if($item['items_Requested'] > 1) {
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
			if($_SESSION['itemN'] < $numOfItems) $_SESSION['itemN']--;
			echo "<p style='margin-right: 45%'>Item #$_SESSION[itemN]</p>";
				
			displayTable(); 
			
			$sql = $con -> query("SELECT * FROM itemsout WHERE item_Holder = '$_SESSION[username]' AND itemID = '$_SESSION[smallest]'");
			$results2 = $sql -> fetch(PDO::FETCH_ASSOC);
			echo '<table align="center" width="50%" height="120%" border=solid black 1px style="background-color: #DCDCDC">';
			echo "<tr><td>Date checked-out: $results2[checkout_Date]</td></tr>";
			echo "<tr><td>Days item has been out: $results2[days_Out]</td></tr>";
			echo "<tr><td>Due date: $results2[due_Date]</td></tr>";
			echo "<tr><td>Renewed: $results2[renewed]</td></tr>";
			echo '</table><br>';
			
			$_SESSION['checkout2'] = $results['Item_Name'];
			
			echo "<form action='check-in.php' method='post'>";
				echo "<center><input name='checkIn' type='submit' value='Check-in Item' style='display: inline; margin-right: 1.5%'></input>";
				
				$sql = $con -> query("SELECT renewed FROM itemsout WHERE item_Holder = '$_SESSION[username]'");
				$item = $sql -> fetch(PDO::FETCH_ASSOC); 
				$renewed = $item['renewed'];
				if($renewed == "No") {
					echo "<input name='renew' type='submit' value='Renew Item' style='display: inline; margin-right: 1.5%'></input>";
				}
				
				$sql = $con -> query("SELECT * FROM itemsout WHERE item_Holder = '$_SESSION[username]'");
				$item = $sql -> fetchAll(PDO::FETCH_ASSOC); 
				if($item > 1) {
					if($_SESSION['smallest'] != $_SESSION['smallestNum']) {
						echo "<input name='previous' type='submit' value='Previous Page' style='display: inline; margin-right: 1.5%'></input>";
					}
					
					echo "<input name='next' type='submit' value='Next Page' style='display: inline'></input><center>";
				}
			echo "</form>";
		}
	} else {
		if($_SESSION['itemN'] < $numOfItems) $_SESSION['itemN']--;
		echo "<p style='margin-right: 45%'>Item #$_SESSION[itemN]</p>";
			
		displayTable();
		
		$sql = $con -> query("SELECT * FROM itemsout WHERE item_Holder = '$_SESSION[username]' AND itemID = '$_SESSION[smallest]'");
		$results2 = $sql -> fetch(PDO::FETCH_ASSOC);
		echo '<table align="center" width="50%" height="120%" border=solid black 1px style="background-color: #DCDCDC">';
		echo "<tr><td>Date checked-out: $results2[checkout_Date]</td></tr>";
		echo "<tr><td>Days item has been out: $results2[days_Out]</td></tr>";
		echo "<tr><td>Due date: $results2[due_Date]</td></tr>";
		echo "<tr><td>Renewed: $results2[renewed]</td></tr>";
		echo '</table><br>';
		
		$_SESSION['checkout2'] = $results['Item_Name'];
		
		echo "<form action='check-in.php' method='post'>";
			echo "<center><input name='checkIn' type='submit' value='Check-in Item' style='display: inline; margin-right: 1.5%'></input>";
			
			$sql = $con -> query("SELECT renewed FROM itemsout WHERE item_Holder = '$_SESSION[username]'");
			$item = $sql -> fetch(PDO::FETCH_ASSOC); 
			$renewed = $item['renewed'];
			if($renewed == "No") {
				echo "<input name='renew' type='submit' value='Renew Item' style='display: inline; margin-right: 1.5%'></input>";
			}
			
			$sql = $con -> query("SELECT * FROM itemsout WHERE item_Holder = '$_SESSION[username]'");
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
	include('../includes/footer2.htm');
?>