<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connect.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_login(); ?>


<?php require_once("../includes/validation.php"); ?>
<?php create_page_select();?>
<?php
if (!$current_subject){
	redirect_to("menage_content.php");
}
?>


<?php
if (isset($_POST['submit'])){
// Process the form

// Validations
$require_fields = array("menu_name", "position", "visible", "content");
validate_text($require_fields);

$field_max_length = array("menu_name" => 30);
validate_max_length($field_max_length);

if(empty($errors)){
$subject_id = $current_subject["id"];
$menu_name = string_escape($_POST["menu_name"]);
$position = (int) $_POST["position"];
$visible = (int) $_POST["visible"];
$content = string_escape($_POST["content"]);


// Sending Database Query
	$query  = "INSERT INTO pages (";
    $query .= "  subject_id, menu_name, position, visible, content";
    $query .= ") VALUES (";
    $query .= "  {$subject_id}, '{$menu_name}', {$position}, {$visible}, '{$content}'";
    $query .= ")";
	$result = mysqli_query($conn, $query);

// Query testing
if($result){
	// Success
	$_SESSION["message"] = "Page Created.";
	redirect_to("manage_content.php?subject=" . urlencode($current_subject["id"]));
}
else{
	// Fail
	$_SESSION["message"] = "Page Creation Failed";
	//redirect_to("new_page.php");
}
}
}else{

}
?>
<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>
<div id = "main">
		<div id = "navigation">
			<?php echo navigation($current_subject, $current_page); ?>
		</div>
		<div id = "page">
			<?php echo message(); ?>
			<?php echo form_errors($errors); ?>
			
			<h2>Create Page</h2>
			<form action = "create_page.php?subject=<?php echo urlencode($current_subject["id"]); ?>" method="post">
				<p>Menu name:
					<input type="text" name="menu_name" value="" />
				</p>
				<p>Position:
					<select name="position">
					<?php
						$page_set = find_pages($current_subject["id"]);
						$page_count = mysqli_num_rows($page_set);
						for ($count = 1; $count <= ($page_count + 1); $count++){
							echo "<option value = \"{$count}\">{$count}</option>";
						}
					?>
					</select>
				</p>
				<p>Visible:
					<input type="radio" name="visible" value="0" /> NO
					&nbsp;
					<input type="radio" name="visible" value="1" /> Yes
				</p>
				<p>Content:<br />
					<textarea rows="10" cols="80" name="content"></textarea>
				</p>
				<input type="submit" name="submit" value="Create Page" />
			</form>
			<br />
			<a href = "manage_content.php?subject=<?php echo urlencode($current_subject["id"]); ?>">Cancle</a>
		</div>
	</div>


<?php include("../includes/layouts/footer.php"); ?>