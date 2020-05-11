<!--
Purpose of webpage: this page will handle 4 operations: cancelling a request, checking out item, moving to next view page, and moving to previous view page 
-->

<?php 
	session_start();
	$con = new PDO('mysql:host=localhost:3306;dbname=librarysite;charset=utf8mb4','root');
	
	# if user wants to cancel there request perform operation 
	if(isset($_POST['cancel'])) {
		if($_POST['cancel']) { 
			# decrement number of requests 
			$sql = $con -> query("SELECT items_Requested FROM useraccounts WHERE username = '$_SESSION[username]'"); 
			$items_Requested = $sql -> fetch(PDO::FETCH_ASSOC);
			$requests = $items_Requested['items_Requested'];
			$requests--;
			
			$sql = $con -> query("UPDATE useraccounts SET items_Requested = '$requests' WHERE username = '$_SESSION[username]'");
			$sql = $con -> query("UPDATE items SET Requested = 'No' WHERE Item_Name = '$_SESSION[checkout2]'");
			$sql = $con -> query("DELETE FROM itemsreq WHERE requester = '$_SESSION[username]' AND Item_Name = '$_SESSION[checkout2]'");
			header('Location: myAccount.php');
		}
	}

	# if next button is clicked go to requestViewNext.php 
	else if(isset($_POST['next'])) {
		if($_POST['next']) {
			$_SESSION['requestViewNext'] = 'req';
			header("Location: viewNextPage.php");
		}
	}
	
	# if previous button is clicked go to requestViewPrevious.php 
	else if(isset($_POST['previous'])) {
		if($_POST['previous']) {
			$_SESSION['requestViewPrevious'] = 'req1';
			header("Location: viewPreviousPage.php");
		}
	}
	
	# if checkout button is clicked go to checkout.php 
	else if( isset($_POST['checkout2'])) {
		header("Location: checkout.php");
	}
?>