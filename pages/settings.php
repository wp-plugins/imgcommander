<?php

	$licenses_array = array(
		array('id'=>0, 'name'=> __('All Rights Reserved'))
		,array('id'=>4, 'name'=> __('Attribution License'))
		,array('id'=>6, 'name'=> __('Attribution-NoDerivs License'))
		,array('id'=>3, 'name'=> __('Attribution-NonCommercial-NoDerivs License'))
		,array('id'=>2, 'name'=> __('Attribution-NonCommercial License'))
		,array('id'=>1, 'name'=> __('Attribution-NonCommercial-ShareAlike License'))
		,array('id'=>5, 'name'=> __('Attribution-ShareAlike License'))
		,array('id'=>7, 'name'=> __('No known copyright restrictions'))
		);
	
	$selected_licenses = get_option('imgcommander_licenses_to_search');
	$selected_licenses = explode(",", $selected_licenses);

	for ($i = 0; $i<count($licenses_array); $i++)
	{
		if (in_array($licenses_array[$i]['id'], $selected_licenses)) 
			$licenses_array[$i]['selected'] = true;
		else
			$licenses_array[$i]['selected'] = false;
	}

?>

<div class="wrap">


<h2><?php _e('IMG.Commander Configuration'); ?></h2>


<div style="float: left; width: 350px; overflow: hidden;">

<form method="post">

<div onmouseover="imgcommander_help('apikey');">
<h3><label for="key"><?php _e('IMG.Commander API Key'); ?></label></h3>
<p><input id="key" name="key" type="text" value="<?php echo get_option('imgcommander_api_key'); ?>" style="padding: 5px; width: 200px;"/></p>
</div>


<div onmouseover="imgcommander_help('searchby');">
<h3><label for="key"><?php _e('Seach settings'); ?></label></h3>
<label><input name="search_by_keywords" value="1" type="checkbox" 
			<?php if (get_option('imgcommander_search_by_keywords')) { ?> checked="checked" <?php } ?>/> <?php _e('Search by keywords, not by text'); ?></label><br>
</div>


<div onmouseover="imgcommander_help('licenses');">
<h3><label for="key"><?php _e('Find content with licenses'); ?></label></h3>
<p>
<?php foreach ($licenses_array as $l) { ?>
<label><input name="licenses[<?php echo $l['id']; ?>]" value="<?php echo $l['id']; ?>" type="checkbox" 
			<?php if ($l['selected']) { ?> checked="checked" <?php } ?>/> <?php echo $l['name']; ?></label><br>
<?php } ?>
</p>
</div>



<div onmouseover="imgcommander_help('caption_template');">
<h3><label for="key"><?php _e('Image caption template'); ?></label></h3>
<p><input id="caption_template" name="caption_template" type="text" value="<?php echo htmlentities(get_option('imgcommander_caption_template')); ?>" style="padding: 5px; width: 300px;" /></p>
</div>


<p id="imgcommander_submit" class="submit"><input type="submit" name="submit" value="<?php _e('Update options &raquo;'); ?>" /></p>

</form>

</div>
<div style="margin-left: 350px; padding: 10px; border: 1px solid #555; background: #eee; height: 400px;">

	<div style="float: right">
	  <a href="http://imgcommander.com" target="_blank"><img src="<?php echo plugins_url(); ?>/imgcommander/logotype.png"></a>
	</div>

   <div id="imgcommander_helpdiv_how">
      <h3><?php _e('How it works?'); ?></h3>
      <img src="<?php echo plugins_url(); ?>/imgcommander/how.jpg">
   </div>

   <div id="imgcommander_helpdiv_apikey" style="display: none;">
   	<h3><?php _e('API key'); ?></h3>

   	<p>If you don't have an API key yet, you can manage your API keys at <a href="http://imgcommander.com" target="_blank">imgcommander.com</a>.</p>
   </div>
   <div id="imgcommander_helpdiv_searchby" style="display: none;">
   	<h3><?php _e('Search by keywords'); ?></h3>

   	<p>Switch this checkbox on to disable img.commander premium feature, searching images by article content.</p>
   </div>
   <div id="imgcommander_helpdiv_licenses" style="display: none;">
   	<h3><?php _e('Licenses'); ?></h3>

   	<p>Only search within selected licensed images.</p>
   </div>
   <div id="imgcommander_helpdiv_caption_template" style="display: none;">
   	<h3><?php _e('Image caption template'); ?></h3>

   	<p>If you want to provide credit for an image by putting a link inside the image caption, or just add image title to your post.</p>
   	<p>Possible tags: </p>
   	<table>
   	<tr>
   	<td>%author_name%</td><td> &ndash; Author name</td>
   	</tr>
   	<tr>
   	<td>%author_url%</td><td> &ndash; Author page URL</td>
   	</tr>
   	<tr>
   	<td>%title%</td><td> &ndash; Image title</td>
   	</tr>
   	<tr>
   	<td>%provider%</td><td> &ndash; Provider name ( e.g. Flickr )</td>
   	</tr>
   	</table>
   </div>


</div>


<div style="clear: both"></div>

</div>


<script src="<?php echo plugins_url(); ?>/imgcommander/settings.js"></script>