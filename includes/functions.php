<?php

	function confirm_login(){
		 if (!log_in()) {
	redirect_to("login.php");
		}

	}

	function log_in(){
		return isset($_SESSION['admin_id']);
	}

	function attempt_login($username, $password){

		$admin = find_admin_username($username);
		if($admin){
			// Found Admin, check password;
			if(password_check($password, $admin["hashed_password"])){
				// Password Match
				return $admin;
			}else{
				return false;
			}

		}else{
			// Admin not found
			return false;
		}
	}
	
	function password_encrypt($password){
		$hash_format = "$2y$10$"; 	// Tells PHP to BlowFish with a "cost" of 10;
		$salt_length = 22; 				// Blowfish salts should be 22 char or mor;
		$salt = generate_salt($salt_length);
		$format_salt = $hash_format . $salt;
		$hash = crypt($password, $format_salt);
		return $hash;
	}

	function generate_salt($length){
		// MD5 returns 32 characters
		$unique_random_string = md5(uniqid(mt_rand(), true));

		// Vaild charcter for a salt are (a-zA-z0-9./)
		$base64_string = base64_encode($unique_random_string);

		// But not '+' which is vaild in base64 encoding
		$modified_base64_string = str_replace('+', '.', $base64_string);

		// Truncate string to the correct length
		$salt = substr($modified_base64_string, 0, $length);
		return $salt;
	}

	function password_check($password, $existing_hash){
		// Existing hash contains format ans salt at start
		$hash = crypt($password, $existing_hash);
		if($hash === $existing_hash){
			return true;
		}else{
			return false;
		}
	}

	function string_escape($string){
		global $conn;
		$escape_string = mysqli_real_escape_string($conn, $string);
		return $escape_string;
	}

	function default_subject($subject_id){
		$page_set = find_pages($subject_id);

		if($first_page = mysqli_fetch_assoc($page_set)){
			return $first_page;
		}else{
			return null;
		}
	}

	function create_page_select($public=false){
		global $current_subject;
		global $current_page;
	if (isset($_GET["subject"])){
		$current_subject = find_subject_by_id($_GET["subject"], $public);
		if($current_subject && $public){
		$current_page = default_subject($current_subject["id"]);
	}else {$current_page = null; }
	}elseif (isset($_GET["page"])){
		$current_subject = null;
		$current_page = find_page_by_id($_GET["page"], $public);	
	}else{
		$current_subject = null;
		$current_page = null;
	}

	}
	function find_page_by_id($page_id, $public=true){
		global $conn;

		$safe_page_id = mysqli_real_escape_string($conn, $page_id);

		$query = "SELECT *"; 
		$query .= " FROM pages";
		$query .= " WHERE id = {$safe_page_id}";
		if($public){
			$query .= " AND visible = 1";
		}
		$query .= " LIMIT 1";
		$page_result = mysqli_query($conn, $query);
		confirm_query($page_result);
		if($page = mysqli_fetch_assoc($page_result)){
			return $page;
		}else {
		return null;
		}
	}

	function find_subject_by_id($subject_id, $public = true) {
		global $conn;
		
		$safe_subject_id = mysqli_real_escape_string($conn, $subject_id);
		
		$query  = "SELECT * ";
		$query .= "FROM subjects ";
		$query .= "WHERE id = {$safe_subject_id} ";
		if($public){
			$query .= "AND visible = 1 ";
		}
		$query .= "LIMIT 1";
		$subject_result = mysqli_query($conn, $query);
		confirm_query($subject_result);
		if($subject = mysqli_fetch_assoc($subject_result)) {
			return $subject;
		} else {
			return null;
		}
	}

	function form_errors($errors=array()){
		$output = "";
		if(!empty($errors)){
			$output .= "<div class=\"error\">";
			$output .= "Please fix the following errors:";
			$output .= "<ul>";
			foreach($errors as $key => $error){
				$output .= "<li>";
				$output .= htmlentities($error);
				$output .= "</ls>";
			}
			$output .= "</ul>";
			$output .= "</div>";
		}
		return $output;
	}


	function redirect_to($new_location){
		header("Location: " . $new_location);
		exit;
	}

	function confirm_query($result_set){
		if (!$result_set){
			die ("Database query failed! ");
		}
	}

	function find_all_subjects($public=true){
		global $conn;
		$query = "SELECT *"; 
		$query .= " From subjects";
		if($public){
		$query .= " Where visible = 1";
	}
		$query .= " ORDER BY position ASC";
		$subject_result = mysqli_query($conn, $query);
		// Function call for query testing
		confirm_query($subject_result);	
		return $subject_result;		
	}

	function find_pages($subject_id, $public = true) {
		global $conn;
		$query = "SELECT *"; 
		$query .= " FROM pages";
		$query .= " WHERE subject_id = {$subject_id}";
		if($public){
		$query .= " AND visible = 1";
	}
		$query .= " ORDER BY position ASC";
		$page_result = mysqli_query($conn, $query);
		confirm_query($page_result);
		return $page_result;
	}
	// navigation take two arguments
	// currently selected subject id;
	// currently selected page idl
	function navigation($subject_array, $page_array){
		$html_string  = "<ul class =\"subjects\">";
		 $subject_result = find_all_subjects(false);
				// Show output Database
				while ($subject = mysqli_fetch_assoc($subject_result)){
			
					$html_string .= "<li";
					if ($subject_array && $subject["id"] == $subject_array["id"]) {
						$html_string .= " class=\"selected\"";
					}
					$html_string .= ">";
				$html_string .= "<a href =\"manage_content.php?subject=";
				$html_string .= urlencode($subject["id"]); 
				$html_string .= "\">";
				$html_string .= htmlentities($subject["menu_name"]);
				$html_string .= "</a></li>";
				$page_result = find_pages($subject["id"],false); 
				$html_string .= "<ul class =\"pages\">";
				 while ($page = mysqli_fetch_assoc($page_result)){ 
					 $html_string .= "<li";
						if ($page_array && $page["id"] == $page_array["id"]){ 
							$html_string .= " class=\"selected\"";
						}
						$html_string .= ">";
					
				$html_string .= "<a href =\"manage_content.php?page=";
				$html_string .= urlencode($page["id"]);
				$html_string .= "\">";
				$html_string .= htmlentities($page["menu_name"]);
				$html_string .= "</a></li>";	
			}
				 mysqli_free_result($page_result); // release the page data.
			$html_string .= "</ul>";
		
	 } 
		 mysqli_free_result($subject_result); // release the subject data. 
		$html_string .= "</ul>";
		return $html_string;
	}

	function find_admin(){
		global $conn;
		$query = "SELECT * FROM admin ";
		$query .= "ORDER BY username ASC";
		$admin_result = mysqli_query($conn, $query);
		confirm_query($admin_result);
		return $admin_result;
	}

	function find_admin_id($admin_id){
		global $conn;
		$safe_admin_id = mysqli_real_escape_string($conn, $admin_id);
		$query = "SELECT * FROM admin ";
		$query .= "WHERE id = {$safe_admin_id} ";
		$query .= "LIMIT 1";
		$admin_result = mysqli_query($conn, $query);
		confirm_query($admin_result);
		if($admin = mysqli_fetch_assoc($admin_result)){
			return $admin;
		}else{
			return null;
		}
	}

	function find_admin_username($username){
		global $conn;
		$safe_username = mysqli_real_escape_string($conn, $username);
		$query = "SELECT * FROM admin ";
		$query .= "WHERE username = '{$safe_username}' ";
		$query .= "LIMIT 1";
		$admin_result = mysqli_query($conn, $query);
		confirm_query($admin_result);
		if($admin = mysqli_fetch_assoc($admin_result)){
			return $admin;
		}else{
			return null;
		}
	}

	function public_navigation($subject_array, $page_array){
		$html_string  = "<ul class =\"subjects\">";
		 $subject_result = find_all_subjects();
				// Show output Database
				while ($subject = mysqli_fetch_assoc($subject_result)){
			
					$html_string .= "<li";
					if ($subject_array && $subject["id"] == $subject_array["id"]) {
						$html_string .= " class=\"selected\"";
					}
					$html_string .= ">";
				$html_string .= "<a href =\"index.php?subject=";
				$html_string .= urlencode($subject["id"]); 
				$html_string .= "\">";
				$html_string .= htmlentities($subject["menu_name"]);
				$html_string .= "</a>";
				

				if ($subject_array["id"] == $subject["id"] || $page_array["subject_id"] == $subject["id"]){
				$page_result = find_pages($subject["id"]); 
				$html_string .= "<ul class =\"pages\">";
				 while ($page = mysqli_fetch_assoc($page_result)){ 
					 $html_string .= "<li";
						if ($page_array && $page["id"] == $page_array["id"]){ 
							$html_string .= " class=\"selected\"";
						}
						$html_string .= ">";
					
				$html_string .= "<a href =\"index.php?page=";
				$html_string .= urlencode($page["id"]);
				$html_string .= "\">";
				$html_string .= htmlentities($page["menu_name"]);
				$html_string .= "</a></li>";	
			}
				$html_string .= "</ul>";
				 mysqli_free_result($page_result); // release the page data.
			}
			$html_string .= "</li>";
		
	 } 
		 mysqli_free_result($subject_result); // release the subject data. 
		$html_string .= "</ul>";
		return $html_string;
	}

?>