<?php
	$errors = array();

	function name_text($fieldname){
		$fieldname = str_replace("_", " ", $fieldname);
		$fieldname = ucfirst($fieldname);
		return $fieldname;
	}


	// using trim() so that empty spaces don't count // using === to avoid false positive // empty() to make string "0" empty
	function has_value($value){
		return isset($value) && $value !== "";
	}

	function validate_text($require_text){
		global $errors;
		foreach($require_text as $required){
			$value = trim($_POST[$required]);
			if(!has_value($value)){
				$errors[$required] = name_text($required) . " can't be blank";
			}
		}
	}

	// checking input string length // max length

	function max_length($value, $max){
		return strlen($value) <= $max;
	}

	function validate_max_length($required_max_length){
		global $errors;
		// Expects an assoc. array
		foreach ($required_max_length as $required => $max){
			$value = trim($_POST[$required]);
			if (!max_length($value, $max)){
				$errors[$required] = name_text($required) . " is too long";
			}
		}
	}

	// * inclusion in a set
	function has_inclusion_in($value, $set){
		return in_array($value, $set);
	}

?>