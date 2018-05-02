<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connect.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_login(); ?>

<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>
<?php create_page_select();?>	
	<div id = "main">
		<div id = "navigation">
			<?php echo navigation($current_subject, $current_page); ?>
		</div>
		<div id = "page">
			<?php echo message(); ?>
			<?php $errors = errors();?>
			<?php echo form_errors($errors); ?>
			<h2>Create Page</h2>
			<form action = "create_page.php" method="post">
				<p>Menu name:
					<input type="text" name="menu_name" value="" />
				</p>
				<p>Position:
					<select name="position">
					<?php
						$subject_set = find_all_subjects(false);
						$current_subject = mysqli_fetch_assoc($subject_set);
						$page_set = find_pages($current_subject["id"],false);
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
			<a href = "manage_content.php">Cancle</a>
		</div>
	</div>

<?php include("../includes/layouts/footer.php"); ?>

