<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connect.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php confirm_login(); ?>

<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>
<?php require_once("../includes/validation.php"); ?>
<?php create_page_select();?>
<?php 
	// Subject id was missing or invalid or couldn't found in database
if(!$current_subject){
	redirect_to("manage_content.php");
}

?>
<?php
if (isset($_POST['submit'])){
// Process the form


// Validations
$require_fields = array("menu_name", "position", "visible");
validate_text($require_fields);

$field_max_length = array("menu_name" => 30);
validate_max_length($field_max_length);

if(empty($errors)){
		// If no error update the form
	$id = $current_subject["id"];
	$menu_name = string_escape($_POST["menu_name"]);
	$position = (int) $_POST["position"];
	$visible = (int) $_POST["visible"];
	// Sending Database Query
	$query  = "UPDATE subjects SET ";
	$query .= "menu_name = '{$menu_name}', ";
	$query .= "position = {$position}, ";
	$query .= "visible = {$visible} ";
	$query .= "WHERE id = {$id} ";
	$query .= "LIMIT 1";
	$result = mysqli_query($conn, $query);

	// Query testing
	if($result && mysqli_affected_rows($conn) >= 0){
		// Success
		$_SESSION["message"] = "Subject Updated.";
		redirect_to("manage_content.php");
		}
		else {
			$message = "Subject Update Failed";
		}
	}
 }else{
		// This is probably a GET request
	}// end: $_POST["submit"];

?>


	<div id = "main">
		<div id = "navigation">
			<?php echo navigation($current_subject, $current_page); ?>
		</div>
		<div id = "page">
			<?php
			if(!empty($message)){
				echo "<div class = \"message\">" . htmlentities($message) . "</div>";
			}
			?>
			<?php echo form_errors($errors); ?>
			<h2>Edit Subject: <?php echo htmlentities($current_subject["menu_name"]); ?></h2>
			<form action = "edit_subject.php?subject=<?php echo urlencode($current_subject["id"]); ?>" method="post">
				<p>Menu name:
					<input type="text" name="menu_name" value="<?php echo htmlentities($current_subject["menu_name"]); ?>" />
				</p>
				<p>Position:
					<select name="position">
					<?php
						$subject_set = find_all_subjects(false);
						$subject_count = mysqli_num_rows($subject_set);
						for ($count = 1; $count <= ($subject_count + 1); $count++){
							echo "<option value = \"{$count}\"";
							if($current_subject["position"] == $count){
							echo " selected";
							}
							echo ">{$count}</option>";
						}
					?>
					</select>
				</p>
				<p>Visible:
					<input type="radio" name="visible" value="0" <?php if($current_subject["visible"] == 0){echo "checked";}?> /> NO
					&nbsp;
					<input type="radio" name="visible" value="1" <?php if($current_subject["visible"] == 1){echo "checked";}?>/> Yes
				</p>
				<input type="submit" name="submit" value="Edit Subject" />
			</form>
			<br />
			<a href = "manage_content.php">Cancle</a>
			&nbsp;
			<a href = "delete_subject.php?subject=<?php echo urlencode($current_subject["id"]) ?>" onclick = "return confirm('Are you sure want to delete?');">Delete subject
</a>		</div>
	</div>

<?php include("../includes/layouts/footer.php"); ?>

