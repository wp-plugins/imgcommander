<?php
add_action( 'admin_menu', 'imgcommander_config_page' );
add_action( 'admin_head', 'imgcommander_header');

function imgcommander_config_page() {
	if ( function_exists('add_submenu_page') )
	{
		add_submenu_page('plugins.php', __('IMG.Commander Configuration'), __('IMG.Commander'), 'manage_options', 'imgcommander-key-config', 'imgcommander_conf');
		add_media_page(__('IMG.Commander'), __('IMG.Commander'), 'upload_files', 'imgcommander-page', 'imgcommander_main');
	}
}

function imgcommander_main()
{
	require_once dirname( __FILE__ ) . '/browser.php';
}

function imgcommander_conf()
{
	if (isset($_POST['key']))
	{
		update_option('imgcommander_api_key', $_POST['key']);
	}

	if (isset($_POST['caption_template']))
	{
		update_option('imgcommander_caption_template', stripslashes($_POST['caption_template']));
	}

	if (isset($_POST['search_by_keywords']) && $_POST['search_by_keywords'])
		update_option('imgcommander_search_by_keywords', true);
	else
		update_option('imgcommander_search_by_keywords', false);

	if (isset($_POST['licenses']))
	{
		$a = array();
		foreach ($_POST['licenses'] as $key=>$value) $a[] = $key;
		$licenses = implode(",",$a); if (!$licenses) $licenses = "4,6,3,2,1,5,7";
		update_option('imgcommander_licenses_to_search', $licenses);
	}

	require_once dirname( __FILE__ ) . '/pages/settings.php';
}

function imgcommander_header()
{
	$imgcommander_api_key = get_option('imgcommander_api_key');
	echo "<script> 
	var imgcommander_plugin_url = \"".plugins_url()."/imgcommander/\";
	addLoadEvent(function() { 
			var media_bar = document.getElementById('wp-content-media-buttons');
			if (typeof(media_bar) != 'undefined' && media_bar) 
			{
				var oHead = document.getElementsByTagName('HEAD').item(0);
				var oScript= document.createElement(\"script\");
				oScript.type = \"text/javascript\";
				oScript.src = \"".plugins_url()."/imgcommander/i.js\";
				oHead.appendChild( oScript);
			}
	});
	</script>";
}



function imgcommander_security_hash()
{
	$current_user = wp_get_current_user();
	if (!$current_user) die("Invalid security hash");
	return md5($current_user->user_email.$current_user->ID.get_option('imgcommander_api_key'));
}


function imgcommander_upload($url, $title = "", $post_id = 48, $caption = "")
{
	$post_id = (int)$post_id;

	$time = current_time('mysql');
	if ( $post = get_post($post_id) ) {
		if ( substr( $post->post_date, 0, 4 ) > 0 )
			$time = $post->post_date;
	}

	$img = @file_get_contents($url);
	if (!$img)
		return false;
	if ( ! ( ( $uploads = wp_upload_dir($time) ) && false === $uploads['error'] ) )
		return false;

	$slug = preg_replace('~[^\\pL\d]+~u', '-', $title);
  	$slug = trim($slug, '-');
  	$slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
  	$slug = strtolower($slug);
  	$slug = preg_replace('~[^-\w]+~', '', $slug);

  	if (!$slug) $slug = "na";
  	$fname = $slug.".jpg";

	$filename = wp_unique_filename( $uploads['path'], $fname, $unique_filename_callback );

	$tmp_file = tempnam("some_tmp_directory", "FOO");
	file_put_contents($tmp_file, $img);

	$wp_filetype = wp_check_filetype_and_ext( $tmp_file, $fname, $mimes );

	extract( $wp_filetype );

	// Check to see if wp_check_filetype_and_ext() determined the filename was incorrect
	if ( $proper_filename )
		$fname = $proper_filename;

	if ( ( !$type || !$ext ) && !current_user_can( 'unfiltered_upload' ) )
		return false;

	if ( !$ext )
		$ext = ltrim(strrchr($fname, '.'), '.');

	if ( !$type )
		$type = "image/jpeg";

	// Copy the temporary file into its destination
	$new_file = $uploads['path'] . "/$filename";
	copy( $tmp_file, $new_file );
	unlink($tmp_file);

	// Set correct file permissions
	$stat = stat( dirname( $new_file ));
	$perms = $stat['mode'] & 0000666;
	@ chmod( $new_file, $perms );

	// Compute the URL
	$url = $uploads['url'] . "/$filename";

	if ( is_multisite() )
		delete_transient( 'dirsize_cache' );

	$file = apply_filters( 'wp_handle_upload', array( 'file' => $new_file, 'url' => $url, 'type' => $type ), 'upload' );

	if (!$file || isset($file['error']))
		return false;
	
	$name_parts = pathinfo($fname);
	$name = trim( substr( $fname, 0, -(1 + strlen($name_parts['extension'])) ) );

	$url = $file['url'];
	$type = $file['type'];
	$file = $file['file'];
	$title = $title;
	$content = '';

	// Construct the attachment array
	$attachment = array_merge( array(
		'post_mime_type' => $type,
		'guid' => $url,
		'post_parent' => $post_id,
		'post_title' => $title,
		'post_excerpt' => $caption,
		'post_content' => $content,
	), array() );

	// This should never be set as it would then overwrite an existing attachment.
	if ( isset( $attachment['ID'] ) )
		unset( $attachment['ID'] );

	// Save the data
	$id = wp_insert_attachment($attachment, $file, $post_id);
	if ( !is_wp_error($id) ) {
		wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $file ) );
	}

	return $id;
}