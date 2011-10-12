<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Helpfunctions {

	var $CI = null;

	function Helpfunctions()
	{
		$this->CI =& get_instance();

		$this->CI->load->library('session');
		$this->CI->load->database();
		$this->CI->load->helper('url');
	}

	function get_table_fields_as_array($table_name)
		{
			$sql = "show full columns  from $table_name";
			$res=$this->CI->db->query($sql);
			//print_r($res);
			$final_result = array();
			foreach ($res->result() as $row)
				{
				    array_push($final_result,$row->Field);
				}


			return 	$final_result;
		}
	function make_insert_array($table_name,$request_data)
	{
		$all_fields = $this->get_table_fields_as_array($table_name);
		$insert_array = array();
			foreach($all_fields as $field)
				{
					if(@@$request_data["$field"]!='')
						{
							if(is_array($request_data["$field"])){$request_data["$field"] =implode(",", $request_data["$field"]);}
							$insert_array[$field] = $request_data["$field"];
						}
				}
		return $insert_array;

	}

	function getFileUploadError($field)
		{
			$CI =& get_instance();
			$CI->lang->load('upload');
			//print_r($_FILES);
			$error = ( ! isset($_FILES[$field]['error'])) ? 4 : $_FILES[$field]['error'];

			switch($error)
			{
				case 1:	// UPLOAD_ERR_INI_SIZE
					return lang('upload_file_exceeds_limit');
					break;
				case 2: // UPLOAD_ERR_FORM_SIZE
					return lang('upload_file_exceeds_form_limit');
					break;
				case 3: // UPLOAD_ERR_PARTIAL
				   return lang('upload_file_partial');
					break;
				case 4: // UPLOAD_ERR_NO_FILE
				   return FALSE;
				   //return lang('upload_no_file_selected');
				   break;
				case 6: // UPLOAD_ERR_NO_TMP_DIR
					return lang('upload_no_temp_directory');
					break;
				case 7: // UPLOAD_ERR_CANT_WRITE
					return lang('upload_unable_to_write_file');
					break;
				case 8: // UPLOAD_ERR_EXTENSION
					return lang('upload_stopped_by_extension');
					break;

			}

			return FALSE;
		}

   function createthumb($name,$filename,$new_w,$new_h)
		{
			$system=explode(".",$name);
			if (preg_match("/jpg|jpeg|JPG/",$system[1])){$src_img=imagecreatefromjpeg($name);}
			if (preg_match("/png/",$system[1])){$src_img=imagecreatefrompng($name);}
			$old_x=imageSX($src_img);
			$old_y=imageSY($src_img);
			if ($old_x > $old_y)
			{
				$thumb_w=$new_w;
				$thumb_h=$old_y*($new_h/$old_x);
			}
			if ($old_x < $old_y)
			{
				$thumb_w=$old_x*($new_w/$old_y);
				$thumb_h=$new_h;
			}
			if ($old_x == $old_y)
			{
				$thumb_w=$new_w;
				$thumb_h=$new_h;
			}
			$dst_img=ImageCreateTrueColor($thumb_w,$thumb_h);
			imagecopyresampled($dst_img,$src_img,0,0,0,0,$thumb_w,$thumb_h,$old_x,$old_y);
			if (preg_match("/png/",$system[1]))
			{
				imagepng($dst_img,$filename);
			} else {
				imagejpeg($dst_img,$filename);
			}
			imagedestroy($dst_img);
			//imagedestroy($src_img);
		}


		function convert($from_time, $to_time = 0, $include_seconds = true) {
                    // If no 'To' time provided, use current time.
                    if($to_time == 0) { $to_time = time(); }

                    $distance_in_minutes = round(abs($to_time - $from_time) / 60);
                    $distance_in_seconds = round(abs($to_time - $from_time));

                    if ($distance_in_minutes >= 0 and $distance_in_minutes <= 1) {
                            if (!$include_seconds) {
                                    return ($distance_in_minutes == 0) ? 'less than a minute' : '1 minute';
                            } else {
                                    if ($distance_in_seconds >= 0 and $distance_in_seconds <= 4) {
                                            return 'less than 5 seconds';
                                    } elseif ($distance_in_seconds >= 5 and $distance_in_seconds <= 9) {
                                            return 'less than 10 seconds';
                                    } elseif ($distance_in_seconds >= 10 and $distance_in_seconds <= 19) {
                                            return 'less than 20 seconds';
                                    } elseif ($distance_in_seconds >= 20 and $distance_in_seconds <= 39) {
                                            return 'half a minute';
                                    } elseif ($distance_in_seconds >= 40 and $distance_in_seconds <= 59) {
                                            return 'less than a minute';
                                    } else {
                                            return '1 minute';
                                    }
                            }
                    } elseif ($distance_in_minutes >= 2 and $distance_in_minutes <= 44) {
                            return $distance_in_minutes . ' minutes';
                    } elseif ($distance_in_minutes >= 45 and $distance_in_minutes <= 89) {
                            return 'about 1 hour';
                    } elseif ($distance_in_minutes >= 90 and $distance_in_minutes <= 1439) {
                            return 'about ' . round(floatval($distance_in_minutes) / 60.0) . ' hours';
                    } elseif ($distance_in_minutes >= 1440 and $distance_in_minutes <= 2879) {
                            return '1 day';
                    } elseif ($distance_in_minutes >= 2880 and $distance_in_minutes <= 43199) {
                            return 'about ' . round(floatval($distance_in_minutes) / 1440) . ' days';
                    } elseif ($distance_in_minutes >= 43200 and $distance_in_minutes <= 86399) {
                            return 'about 1 month';
                    } elseif ($distance_in_minutes >= 86400 and $distance_in_minutes <= 525599) {
                            return round(floatval($distance_in_minutes) / 43200) . ' months';
                    } elseif ($distance_in_minutes >= 525600 and $distance_in_minutes <= 1051199) {
                            return 'about 1 year';
                    } else {
                            return 'over ' . round(floatval($distance_in_minutes) / 525600) . ' years';
                    }
                }

}
// End of library class
// Location: system/application/libraries/Helpfunctions.php

?>