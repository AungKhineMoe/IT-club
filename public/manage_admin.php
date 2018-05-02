<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connect.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php confirm_login(); ?>

<?php $admin_result = find_admin(); ?>	

<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>
	
	<div id = "main">
		<div id = "navigation">
		<br/>
			<a href="admin.php">&laquo; Main Menu</a>
		</div>

		<div id = "page">
			<?php echo message(); ?>
	    <h2>Manage Admint</h2>
	    	<table>
	    		<tr>
	    			<th style = "text-align: left; width: 200px; ">Username</th>
	    			<th colspan="2" style="text-align: left; ">Actions</th>
	    		</tr>
			
		</div>
		<?php while($admin = mysqli_fetch_assoc($admin_result)){ ?>
		<tr>
			<td><?php echo htmlentities($admin["username"]); ?><br/>
				
			</td>
			<td><a href="edit_admin.php?id=<?php echo urlencode($admin["id"]); ?>">Edit</a></td>
			<td><a href="delete_admin.php?id=<?php echo urlencode($admin["id"]); ?>" onclick= "return confirm('Are you sure? '); ">Delete</a></td>
		</tr>
		<?php } ?>
	</table><br/>
			<a href="add_admin.php">Add New Admin</a>
			<hr/>
			
		</div>
	</div>

<?php include("../includes/layouts/footer.php"); ?>
