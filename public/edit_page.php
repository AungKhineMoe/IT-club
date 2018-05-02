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
if(!$current_page){
	redirect_to("manage_content.php");
}

?>
<?php
if (isset($_POST['submit'])){
// Process the form


// Validations
	

		// If no error update the form
	$id = $current_page["id"];
	$menu_name = string_escape($_POST["menu_name"]);
	$position = (int) $_POST["position"];
	$visible = (int) $_POST["visible"];
	$content = string_escape($_POST["content"]);

	$require_fields = array("menu_name", "position", "visible", "content");
	validate_text($require_fields);

	$field_max_length = array("menu_name" => 30);
	validate_max_length($field_max_length);
	

	if(empty($errors)){

	// Sending Database Query
	$query  = "UPDATE pages SET ";
	$query .= "menu_name = '{$menu_name}', ";
	$query .= "position = {$position}, ";
	$query .= "visible = {$visible}, ";
	$query .= "content = '{$content}' ";
	$query .= "WHERE id = {$id} ";
	$query .= "LIMIT 1";
	$result = mysqli_query($conn, $query);

	// Query testing
	if($result && mysqli_affected_rows($conn) == 1){
		// Successs
		$_SESSION["message"] = "Page Updated.";
		redirect_to("manage_content.php");
		}
		else {
			$message = "Page Update Failed";
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
			<?php echo message(); ?>
			<?php //$errors = errors();?>
			<?php echo form_errors($errors); ?>
			<h2>Edit page: <?php echo htmlentities($current_page["menu_name"]); ?></h2>
			<form action = "edit_page.php?page=<?php echo urlencode($current_page["id"]); ?>" method="post">
				<p>Menu name:
					<input type="text" name="menu_name" value="<?php echo htmlentities($current_page["menu_name"]); ?>" />
				</p>
				<p>Position:
					<select name="position">
					<?php
						$page_set = find_pages($current_page["subject_id"],false);
						$page_count = mysqli_num_rows($page_set);
						for ($count = 1; $count <= $page_count; $count++){
							echo "<option value = \"{$count}\"";
							if($current_page["position"] == $count){
							echo " selected";
							}
							echo ">{$count}</option>";
						}
					?>
					</select>
				</p>
				<p>Visible:
					<input type="radio" name="visible" value="0" <?php if($current_page["visible"] == 0){echo "checked";}?> /> NO
					&nbsp;
					<input type="radio" name="visible" value="1" <?php if($current_page["visible"] == 1){echo "checked";}?>/> Yes
				</p>
				<p>Content:<br />
					<textarea rows="10" cols="80" name="content"><?php echo htmlentities($current_page["content"]); ?></textarea>
				</p>
				<input type="submit" name="submit" value="Edit Page" />
			</form>
			<br />
			<a href = "manage_content.php?subject=<?php echo urlencode($current_page["id"]); ?>">Cancle</a>
			&nbsp;
			<a href = "delete_page.php?page=<?php echo urlencode($current_page["id"]) ?>" onclick = "return confirm('Are you sure want to delete?');">Delete page</a>
		</div>
	</div>

<?php include("../includes/layouts/footer.php"); ?>

