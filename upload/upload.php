<?php
/* 
 *******************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.	
 | @ Author   : ArslanHassan										
 | @ Software : ClipBucket , � PHPBucket.com
 | @ Modified : June 14, 2009 by Arslan Hassan
 *******************************************************************
*/

define("THIS_PAGE","upload");
define("PARENT_PAGE","upload");


/*if(isset($_POST['embed_code'])){
	$_POST['embed_code'] = base64_encode($_POST['embed_code']);
}*/

//var_dump($_POST);die();

require 'includes/config.inc.php';

//var_dump($_POST);die();
//$userquery->logincheck();

$pages->page_redir();
subtitle('upload');

//Checking if user is guest 
if(userid())
	$verify_logged_user = true;
else
	$verify_logged_user = false;

//dump(has_access('allow_video_upload',false,$verify_logged_user));

if(has_access('allow_video_upload',false,$verify_logged_user))
{
	
	$file_name = time().RandomString(5);
	assign('file_name',$file_name);
			
	$step = 1;
	/*if(!empty($_POST)){
			
			foreach ($_POST as $key => $value) {
				echo "{$key} : ";
				dump(($value));
			}
			
		}*/

	if(isset($_POST['submit_data']))
	{
		$Upload->validate_video_upload_form();
		if(empty($eh->error_list))
		{
			$step=2;
		}
	}
	
	if(isset($_POST['submit_upload']))
	{

		if(!$_POST['file_name'])
			$_POST['file_name'] = time().RandomString(5);
		{
			$vid = $Upload->submit_upload();
			//echo $db->db_query;
			//Call file so it can activate video
			$Upload->do_after_video_upload($vid);
            $query = $db->update(tbl("video"),array("file_directory"),array( date("Y/m/d")),"videoid='$vid'");
			echo '<div class="alert alert-success embed_video">
   			Video has been Embeded succesfully ..
    			</div>';
    			
			if(!error())
			$step=3;
		}
	}
	
	//Assigning Form Name [RECOMMEND for submitting purpose]
	Assign('upload_form_name','UploadForm');
		   
	//Adding Uploading JS Files
	$Cbucket->add_js(array('swfupload/swfupload.js'=>'uploadactive'));
	$Cbucket->add_js(array('swfupload/plugins/swfupload.queue.js'=>'uploadactive'));
	$Cbucket->add_js(array('swfupload/plugins/handlers.js'=>'uploadactive'));
	$Cbucket->add_js(array('swfupload/plugins/fileprogress.js'=>'uploadactive'));
}else
{
	$userquery->logincheck('allow_video_upload',true);
}

Assign('step',$step);
Assign('extensions', $Cbucket->get_extensions());
subtitle(lang('upload'));
//Displaying The Template
template_files('upload.html');
display_it();

?>