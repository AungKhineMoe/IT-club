<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connect.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php confirm_login(); ?>

<?php include("../includes/layouts/header.php"); ?>
<?php create_page_select(true);?>	
	<div id = "main">
		<div id = "navigation">
			<br />

			<?php echo public_navigation($current_subject, $current_page); ?>
		<br />
		</div>

		<div id = "page">
			<?php if ($current_page) { ?>
			<h2><?php echo htmlentities($current_page["menu_name"]); ?></h2>
				<?php echo nl2br(htmlentities($current_page["content"])); ?>
			</div><br/>
			<br/>
		<?php } else { ?>
			<p>Welcome to Webster IT Club</p>
		<?php }?>
		</div>
	</div>

<?php include("../includes/layouts/footer.php"); ?>
