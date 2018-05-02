<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connect.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php confirm_login(); ?>


<?php 
	$admin = find_admin_id($_GET["id"]);

	if(!$admin){
		redirect_to("manage_admin.php");
	}


?>
<?php

	$id = $admin["id"];
		// Sending Database Query
	$query  = "DELETE FROM admin ";
	$query .= "WHERE id = {$id} ";
	$query .= "LIMIT 1";
	$result = mysqli_query($conn, $query);

	// Query testing
	if($result && mysqli_affected_rows($conn) == 1){
		// Success
		$_SESSION["message"] = "Admin Deleted.";
		redirect_to("manage_admin.php");
		}
		else {
			$message = "Admin Delete Failed";
		}
	

?>








<?php include("../includes/layouts/footer.php"); ?>