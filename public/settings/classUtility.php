<?php
	class Utility
	{
		var $system = "UTM :: Vehicle Reservation Management System";
		var $err_arr;
		var $err_flag;
		var $required_field = "Required (*) field cannot be left blank.";
		var $_email_param;
		var $_db;
		
		function print_m() {
			return "<font style=\"color:#ff0000; padding:3px; font-weight:bold;\">*</font>";
		}
		
		function print_err() {
			$count_arr = count($this->err_arr);
			if($count_arr > 0) {
				$err = array_unique($this->err_arr);
				
				$class = $this->err_flag ? "err_message" : "message";
				$x = "<div id=\"IdErr\" class=\"" . $class . "\"><ul>";
					for($i = 0; $i < count($err); $i++)
						$x .= "<li>" . $err [$i] . "</li>";
				$x .= "</ul></div>";
				return $x;
			}
			else
				return;
		}
		
		function sql_escape_string($text) {
			return str_replace("'", "''", $text);
		}
		
		function mandatory($arr = array()) {
			$count_arr = count($arr);
			if($count_arr > 0) {
				$is_valid = true;
				for($i = 0; $i < $count_arr; $i++)
					if(strlen(trim($arr [$i])) == 0) $is_valid = false;
				
				return $is_valid;
			}
			else
				return;
		}
		
		function isValidEmail($email) {
			return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
		}
		
		function make_thumb($img_name, $filename, $new_w, $new_h)
		{
			//$ext = getExtension($img_name);
			$file = pathinfo($img_name);
			$ext = strtolower($file ['extension']);
	
			if(!strcmp("jpg", $ext) || !strcmp("jpeg", $ext))
				$src_img = imagecreatefromjpeg($img_name);

			if(!strcmp("png", $ext))
				$src_img=imagecreatefrompng($img_name);
				
			if(!strcmp("bmp", $ext))
				$src_img=imagecreatefrompng($img_name);
			
			$old_x = imageSX($src_img);
			$old_y = imageSY($src_img);

			$ratio1 = $old_x/$new_w;
			$ratio2 = $old_y/$new_h;
	
			if($ratio1 > $ratio2)
			{
				$thumb_w = $new_w;
				$thumb_h = $old_y / $ratio1;
			}
			else
			{
				$thumb_h = $new_h;
				$thumb_w = $old_x / $ratio2;
			}

			// we create a new image with the new dimmensions
			$dst_img = ImageCreateTrueColor($thumb_w, $thumb_h);

			// resize the big image to the new created one
			imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $thumb_w, $thumb_h, $old_x, $old_y); 

			// output the created image to the file. Now we will have the thumbnail into the file named by $filename
			if(!strcmp("png", $ext))
				imagepng($dst_img, $filename);
			else
				imagejpeg($dst_img, $filename);

			//destroys source and destination images. 
			imagedestroy($dst_img); 
			imagedestroy($src_img); 
		}
		
		function IsReadOnly($read = false) {
			return $read ? "readonly" : "";
		}
		
		function IsDisabled($disabled = false) {
			return $disabled ? "disabled" : "";
		}
		
		function Role($role, $user_name) {
			$_sql = "SELECT COUNT(*) FROM fl_user_role " .
					"WHERE LOWER(user_name) = '" . strtolower($this->sql_escape_string($user_name)) . "' " .
					"AND LOWER(role) = '" . strtolower($role) . "'";
			$rs = $this->_db->Execute($_sql);
			return $rs->fields [0];
		}
	}
	
	$util = new Utility;
	$util->err_flag = $_SESSION['err_flag'] ?? null;
		$util->err_arr  = $_SESSION['error'] ?? [];
?>
