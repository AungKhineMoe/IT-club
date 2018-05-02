<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connect.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php confirm_login(); ?>

<?php 
	$current_subject = find_subject_by_id($_GET["subject"], false);
	// Subject id was missing or invalid or couldn't found in database
if(!$current_subject){
	redirect_to("manage_content.php");
}
	$pages_set = find_pages($current_subject["id"]);
	if(mysqli_num_rows($pages_set) > 0){
		$_SESSION["message"] = "Can't Delete Subject with Pages!";
		redirect_to("manage_content.php?subject={$current_subject["id"]}");
	}

	$id = $current_subject["id"];
	$query = "DELETE FROM subjects ";
	$query .= "WHERE id = {$id} ";
	$query .= "LIMIT 1";
	$result = mysqli_query($conn, $query);

	if($result && mysqli_affected_rows($conn) == 1){
		// Success
		$_SESSION["message"] = "Subject Deleted.";
		redirect_to("manage_content.php");
	}
	else{
		$_SESSION["message"] = "Subject Delete Failed.";
		redirect_to("manage_content.php?subject={$id}");
	}
?>