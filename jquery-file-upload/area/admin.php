<?php
/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */
require('UploadHandler.php');
require('extend.php');
$options_local = array (
	'db_table' => 'prop_photos',
	'script_url' => ($https ? 'https://' : 'http://').$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'], 
	'upload_dir' => $_SERVER['DOCUMENT_ROOT'].'/upload/server/php/files/', 
	'upload_url' => ($https ? 'https://' : 'http://').$_SERVER['SERVER_NAME'].'/upload/server/php/files/', 
	'max_number_of_files' => 20, 
	'accept_file_types' => '/\.(gif|jpe?g|png)$/i', 
	'min_width' => 390,
	'min_height' => 390,
	'image_versions' => array(
		'' => array(
			'max_width' => 390,
			'max_height' => 390
		),
		'slide' => array(
			'max_width' => 390,
			'max_height' => 390
		),
		'thumbnail' => array(
			'max_width' => 115,
			'max_height' => 115
		),
		'mobile' => array(
			'max_width' => 60,
			'max_height' => 60
		)
	)
);
$options = array_merge_recursive($options_global, $options_local);
class LocalUploadHandler extends GlobalUploadHandler {
	public function post($print_response = true) {
		parent::post();
    }
	protected function handle_form_data($file, $index) {
		$file->description = @$_REQUEST['description'][$index];
	}
    protected function handle_file_upload($uploaded_file, $name, $size, $type, $error,
            $index = null, $content_range = null) {
        $file = parent::handle_file_upload(
            $uploaded_file, $name, $size, $type, $error, $index, $content_range
        );
		if (empty($file->error)) {
			//Get the sort order first
			$index = 1;
			$sql = 'SELECT `p_order` FROM `'
                .$this->options['db_table'].'` WHERE `id_prop`=? ORDER BY `p_order` DESC LIMIT 1';
			$query = $this->db->prepare($sql);
			$query->bind_param('s', $this->get_user_id());
			$query->execute();
            $query->bind_result(
                $p_order
            );
			while ($query->fetch()) {
			    $index = $p_order+1;
		    }
			//Insert new row
			$sql = 'INSERT INTO `'.$this->options['db_table']
                .'` (`photo`, `description`, `id_prop`, `p_order`)'
                .' VALUES (?, ?, ?, ?)';
            $query = $this->db->prepare($sql);
            $query->bind_param(
                'sssi',
                $file->name,
                $file->description,
				$this->get_user_id(),
				$index
            );
            $query->execute();
			$file->id = $this->db->insert_id;
		}
		return $file;
	}
	protected function set_additional_file_properties($file) {
		parent::set_additional_file_properties($file);
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		    $sql = 'SELECT `id`, `p_order`, `description` AS description FROM `'
                .$this->options['db_table'].'` WHERE `id_prop`="'.$this->get_user_id().'" AND `photo`=?';
            $query = $this->db->prepare($sql);
            $query->bind_param('s', $file->name);
            $query->execute();
            $query->bind_result(
                $id,
                $p_order,
                $description
            );
            while ($query->fetch()) {
			    $file->id = $id;
                $file->order = $p_order;
                $file->description = $description;
		    }
        }
	}
	public function delete($print_response = true) {
        $response = parent::delete(false);
        foreach ($response as $name => $deleted) {
            if ($deleted) {
                //Note - Maybe you can update only second record to be 1 if 1st one is deleted
				$sql = 'DELETE FROM `'
                    .$this->options['db_table'].'` WHERE `id_prop`="'.$this->get_user_id().'" AND `photo`=?';
                $query = $this->db->prepare($sql);
                $query->bind_param('s', $name);
                $query->execute();
            }
        } 
        return $this->generate_response($response, $print_response);
    }
}
$upload_handler = new LocalUploadHandler($options);