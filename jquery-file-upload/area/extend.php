<?php
/*
 * Global variables and extensions for all 
 * jquery upload areas.
 */
$https = !empty($_SERVER['HTTPS']) && strcasecmp($_SERVER['HTTPS'], 'on') === 0;

$options_global = array (
	'user_dirs' => true,
	'db_host' => "localhost",
	'db_user' => "cj3wilso_rent254",
	'db_pass' => "57575757aA",
	'db_name' => "cj3wilso_rent254",
	'image_versions' => array(
		'' => array(
			'jpeg_quality' => 80,
			'png_quality' => 8,
			'auto_orient' => true,
			'crop' => true
		),
		'slide' => array(
			'jpeg_quality' => 80,
			'png_quality' => 8,
			'crop' => true
		),
		'thumbnail' => array(
			'jpeg_quality' => 80,
			'png_quality' => 8,
			'crop' => true
		)
	)
);

class GlobalUploadHandler extends UploadHandler {
	protected function get_unique_filename($file_path, $name, $size, $type, $error,
		$index, $content_range) {
		while(is_dir($this->get_upload_path($name))) {
			$name = $this->upcount_name($name);
		}
		// Keep an existing filename if this is part of a chunked upload:
		$uploaded_bytes = $this->fix_integer_overflow(intval($content_range[1]));
		while(is_file($this->get_upload_path($name))) {
			if ($uploaded_bytes === $this->get_file_size(
				$this->get_upload_path($name))) {
					break;
			}
			$name = $this->upcount_name($name);
		}
		//Get extension
		$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
		//Only UTF8 allowed
		$name = iconv('UTF-8', 'ASCII//TRANSLIT', $name);
		//Replace spaces, underscores and the extension with dashes
		$replace = array(" ", ".".$ext, "_");
		$name = str_replace($replace, "-", strtolower($name));
		//Remove all non-alphanumerical except dashes
		$name = preg_replace('/[^a-zA-Z0-9\-\.-]/si', '', $name);  
		//Add back the extension and add unique ID
		$name = $name.uniqid().'.'.$ext;
		return $name;
    }
	
	protected function get_user_id() {
		@session_start();
        return $_SESSION['prop'];
    }
	
	protected function initialize() {
		$this->db = new mysqli(
            $this->options['db_host'],
            $this->options['db_user'],
            $this->options['db_pass'],
            $this->options['db_name']
        );
        parent::initialize();
		$this->db->close();
    }
	
	protected function gd_create_scaled_image($file_name, $version, $options) {
        if (!function_exists('imagecreatetruecolor')) {
            error_log('Function not found: imagecreatetruecolor');
            return false;
        }
        list($file_path, $new_file_path) =
            $this->get_scaled_image_file_paths($file_name, $version);
        $type = strtolower(substr(strrchr($file_name, '.'), 1));
        switch ($type) {
            case 'jpg':
            case 'jpeg':
                $src_func = 'imagecreatefromjpeg';
                $write_func = 'imagejpeg';
                $image_quality = isset($options['jpeg_quality']) ?
                    $options['jpeg_quality'] : 75;
                break;
            case 'gif':
                $src_func = 'imagecreatefromgif';
                $write_func = 'imagegif';
                $image_quality = null;
                break;
            case 'png':
                $src_func = 'imagecreatefrompng';
                $write_func = 'imagepng';
                $image_quality = isset($options['png_quality']) ?
                    $options['png_quality'] : 9;
                break;
            default:
                return false;
        }
        $src_img = $this->gd_get_image_object(
            $file_path,
            $src_func,
            !empty($options['no_cache'])
        );
        $image_oriented = false;
        if (!empty($options['auto_orient']) && $this->gd_orient_image(
                $file_path,
                $src_img
            )) {
            $image_oriented = true;
            $src_img = $this->gd_get_image_object(
                $file_path,
                $src_func
            );
        }
        $max_width = $img_width = imagesx($src_img);
        $max_height = $img_height = imagesy($src_img);
        if (!empty($options['max_width'])) {
            $max_width = $options['max_width'];
        }
        if (!empty($options['max_height'])) {
            $max_height = $options['max_height'];
        }
        $scale = min(
            $max_width / $img_width,
            $max_height / $img_height
        );
        if ($scale >= 1) {
            if ($image_oriented) {
                return $write_func($src_img, $new_file_path, $image_quality);
            }
            if ($file_path !== $new_file_path) {
                return copy($file_path, $new_file_path);
            }
            return true;
        }
        if (empty($options['crop'])) {
            $new_width = $img_width * $scale;
            $new_height = $img_height * $scale;
            $dst_x = 0;
            $dst_y = 0;
            $new_img = imagecreatetruecolor($new_width, $new_height);
        } else {
            if (($img_width / $img_height) >= ($max_width / $max_height)) {
                $new_width = $img_width / ($img_height / $max_height);
                $new_height = $max_height;
            } else {
                $new_width = $max_width;
                $new_height = $img_height / ($img_width / $max_width);
            }
            $dst_x = 0 - ($new_width - $max_width) / 2;
            $dst_y = 0 - ($new_height - $max_height) / 2;
            $new_img = imagecreatetruecolor($max_width, $max_height);
        }
		
		/*ONLY THING THAT CHANGED IN ORGINAL FUNCTION!!!!*/
		//Make progressive
		imageinterlace($new_img, 1);
		/*END ONLY THING THAT CHANGED*/
		
        // Handle transparency in GIF and PNG images:
        switch ($type) {
            case 'gif':
            case 'png':
                imagecolortransparent($new_img, imagecolorallocate($new_img, 0, 0, 0));
            case 'png':
                imagealphablending($new_img, false);
                imagesavealpha($new_img, true);
                break;
        }
        $success = imagecopyresampled(
            $new_img,
            $src_img,
            $dst_x,
            $dst_y,
            0,
            0,
            $new_width,
            $new_height,
            $img_width,
            $img_height
        ) && $write_func($new_img, $new_file_path, $image_quality);
        $this->gd_set_image_object($file_path, $new_img);
        return $success;
    }
}