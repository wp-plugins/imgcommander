<?php

 require_once('../../../wp-admin/admin.php');
 $security_hash = imgcommander_security_hash();

 function show_die()
 {
		echo "<div style='margin: 200px auto; padding: 20px; width: 200px; border: 1px solid #500; background: #fee; color: $500'>
			".__("Something is wrong and IMG.Commander can't download image to your blog. This may be temporary server issue. Please check to make sure that allow_url_fopen is enabled on your server.")."
		</div>";
		die(); 	
 }

 $post_id = 0; 
 if (isset($_GET['post_id'])) $post_id = (int)$_GET['post_id']; 
 if (isset($_POST['post_id'])) $post_id = (int)$_POST['post_id'];

 if (isset($_POST['url']))
 {		
 	if (!isset($_POST['security_hash']) || $_POST['security_hash'] != $security_hash)
 	 die("Invalid security hash");
 	 
	$url = ""; if (isset($_POST['url'])) $url = $_POST['url'];
	$title = ""; if (isset($_POST['title'])) $title = $_POST['title'];
	$caption = ""; 
	if (isset($_POST['author_name']) && isset($_POST['author_url']) && isset($_POST['provider']))
	{
		$author_name = $_POST['author_name'];
		$author_url = $_POST['author_url'];
		$provider = $_POST['provider'];
 		$caption = "<a href=\"".$author_url."\">".$author_name."</a> at ".$provider;	
	}
	if (!$url) show_die();

	$uploaded = imgcommander_upload($url, $title, $post_id, $caption);

	if ($uploaded) 
	{
		echo '<script>
		
		if (typeof(window.parent) != "undefined" && typeof(window.parent[\'commander_close\']) == "function")
    	{
    		window.parent[\'commander_close\']();
    	}

		</script>';
	} else {
		show_die();
	}

 } else {

?><html>
<head>
 <script src="http://imgcommander.com/scripts/search_library.js?v=0.2"></script>
 <script> </script>
 <script>

function init(text)
{
	var licenses = false; <?php if (get_option('imgcommander_licenses_to_search')) { echo "licenses = '".get_option('imgcommander_licenses_to_search')."';"; } ?> 
	var apikey = ''; <?php if (get_option('imgcommander_api_key')) { echo "apikey = '".get_option('imgcommander_api_key')."';"; } ?> 
	var search_by_text = true;  <?php if (get_option('imgcommander_search_by_keywords')) { echo "search_by_text = false;"; } ?> 
	var options = new Object();

	options.buttons = [{caption: 'Open', type: 'open_in_new_tab'}, {caption: 'Use', type: 'js', func: use_photo}];
	options.container = '#search_container';
	options.apikey = apikey;
	if (search_by_text)	options.text = text;
	if (licenses) options.licenses = licenses;
	imgcommander_init(options); 
}

function use_photo(photo)
{
	if (typeof(photo.max_image) != "undefined" && typeof(photo.max_image.url) != "undefined")
	{
		var url = photo.max_image.url;
		document.getElementById('form_image_url').value = url;
		document.getElementById('form_image_post_id').value = '<?php echo $post_id; ?>';
		if (typeof(photo.title) != "undefined")	document.getElementById('form_image_title').value = photo.title;
		if (typeof(photo.author_name) != "undefined")	document.getElementById('form_image_author_name').value = photo.author_name;
		if (typeof(photo.author_link) != "undefined")	document.getElementById('form_image_author_url').value = photo.author_link;
		if (typeof(photo.provider) != "undefined")	document.getElementById('form_image_provider').value = photo.provider;
		document.getElementById('form_image').submit();

		document.getElementById('search_container').style.display = 'none';
		document.getElementById('loading').style.display = 'block';
	}
}

 </script>
</head>
<body>

 <form method="post" action="?" id="form_image">
 	<input type="hidden" id="form_image_url" name="url">
 	<input type="hidden" id="form_image_post_id" name="post_id">
 	<input type="hidden" id="form_image_title" name="title">
 	<input type="hidden" id="form_image_author_name" name="author_name">
 	<input type="hidden" id="form_image_author_url" name="author_url">
 	<input type="hidden" id="form_image_provider" name="provider">
 	<input type="hidden" name="security_hash" value="<?php echo $security_hash; ?>">
 </form>
 
 <div id="loading" style="width: 128px; margin: 300px auto; display: none;"><img src="loading.gif"></div>
 <div id="search_container" style="border: 1px solid #eee; margin: 10px auto; width: 990px; padding: 10px;"></div>

</body>
</html>
<?php 

}

?>