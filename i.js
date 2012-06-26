
var media_bar = document.getElementById('wp-content-media-buttons');
if (typeof(media_bar) != 'undefined' && media_bar) 
{
	var html = '&nbsp;&nbsp;<a href=\"#\" onclick=\"show_imgcommander(); return false;\">IMG.Commander <img src="'+imgcommander_plugin_url+'logo.png" width="15" height="15"></a>';
	media_bar.innerHTML+=html;
}

function show_imgcommander()
{
	var post_id = try_to_get_post_id();
	var url = imgcommander_plugin_url+"browser.php?post_id="+post_id+"&TB_iframe=true&height=670&width=1030";
	tb_show('IMG.Commander', url, ''); 
	tb_position(); 
	init_commander();
}

function commander_close()
{
	jQuery("#TB_window").remove();
	jQuery("body").append("<div id='TB_window'></div>");
	var post_id = try_to_get_post_id();
	var url = "media-upload.php?type=image&tab=gallery&post_id="+post_id+"&TB_iframe=1&height=500&width=750";
	tb_show("", url, "");
	
}

function commander_exit()
{
	jQuery("#TB_window").remove();
	jQuery("body").append("<div id='TB_window'></div>");
}

function init_commander()
{
	if (jQuery('#TB_iframeContent').length && typeof(jQuery('#TB_iframeContent')[0]) != "undefined" && typeof(jQuery('#TB_iframeContent')[0].contentWindow) != "undefined" && typeof(jQuery('#TB_iframeContent')[0].contentWindow.init) != "undefined")
	{
		var text = "";
		if (typeof(tinyMCE) != "undefined" && typeof(tinyMCE.activeEditor) != "undefined" && tinyMCE.activeEditor)
			text = tinyMCE.activeEditor.getContent();
		else if (jQuery("#content").length)
			text = jQuery("#content").val()
			
		jQuery('#TB_iframeContent')[0].contentWindow.init(text);		
	} else {
		setTimeout(init_commander, 100);		
	}
}

function try_to_get_post_id()
{
	if (jQuery('#content-add_media').length)
	{
		var or_url = jQuery('#content-add_media').attr('href');
		if (!or_url) return false;
		or_url = or_url.split("post_id=")[1];
		if (!or_url) return false;
		return parseInt(or_url);
	} else
	return false;
}

var tb_position;
jQuery(document).ready(function($) {
	tb_position = function() {


	var isIE6 = typeof document.body.style.maxHeight === "undefined";
	jQuery("#TB_window").css({marginLeft: '-' + parseInt((TB_WIDTH / 2),10) + 'px', width: TB_WIDTH + 'px'});
	if ( ! isIE6 ) { // take away IE6
		jQuery("#TB_window").css({marginTop: '-' + parseInt((TB_HEIGHT / 2),10) + 'px'});
	}

	};

	$(window).resize(function(){ tb_position(); });

});
