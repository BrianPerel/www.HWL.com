<!-- Purpose of webpage: this page will handle 4 operations: cancelling a request, checking out item, moving to next view page, and moving to previous view page -->

<?php 
	session_start();
	require("../includes/connect_db.php");
	
	# if user wants to cancel there request perform operation 
	if(isset($_POST['cancel']) && $_POST['cancel']) {
		# decrement number of requests 
		$sql = $con -> query("SELECT items_requested FROM user_accounts WHERE username = '$_SESSION[username]'");  # select number of request in useraccount 
		$items_requested = $sql -> fetch(PDO::FETCH_ASSOC);
		$requests = $items_requested['items_requested'] - 1;
		$sql = $con -> query("UPDATE user_accounts SET items_requested = '$requests' WHERE username = '$_SESSION[username]'"); # update number of items requested in useraccount
		
		$sql = $con -> query("SELECT item_name FROM items_requested WHERE requester = '$_SESSION[username]' AND item_name = '$_SESSION[checkout2]'"); # retrieve current number of items out 
		$itemReq = $sql -> fetch(PDO::FETCH_ASSOC);
		$item_name = $itemReq['item_name'];
		$sql = $con -> query("UPDATE items SET requested = 'No' WHERE item_name = '$item_name'"); # update status to available of item of which request was cancelled 
		$sql = $con -> query("DELETE FROM items_requested WHERE requester = '$_SESSION[username]' AND item_name = '$_SESSION[checkout2]'"); # delete item from item request table 
		header('Location: myAccount.php');
	}

	# if next button is clicked go to requestViewNext.php 
	else if(isset($_POST['next']) && $_POST['next']) {
		$_SESSION['requestViewNext'] = 'req';
		header("Location: viewNextPage.php");
	}
	
	# if previous button is clicked go to requestViewPrevious.php 
	else if(isset($_POST['previous']) && $_POST['previous']) {
		$_SESSION['requestViewPrevious'] = 'req1';
		header("Location: viewPreviousPage.php");
	}
	
	# if checkout button is clicked go to checkout.php 
	else if(isset($_POST['checkout2'])) {
		header("Location: checkout.php");
	}
?>