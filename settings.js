
  function imgcommander_help(item)
  {
  	document.getElementById('imgcommander_helpdiv_how').style.display = 'none';
  	document.getElementById('imgcommander_helpdiv_apikey').style.display = 'none';
  	document.getElementById('imgcommander_helpdiv_searchby').style.display = 'none';
  	document.getElementById('imgcommander_helpdiv_licenses').style.display = 'none';
  	document.getElementById('imgcommander_helpdiv_caption_template').style.display = 'none';


  	document.getElementById('imgcommander_helpdiv_'+item).style.display = 'block';
  }
              /*
  function checkkey(key)
  {
  	jQuery.post("http://imgcommander.com/call/checkkey", { apikey: key }, keychecked, "json");
  }

  function keychecked(data)
  {
  	if (typeof(data.error) != "undefined")
  	{
  		alert("Invalid API key or different blog domain");
  		jQuery('#key').focus();
  		jQuery('#imgcommander_submit').hide();
  	} else {
  		jQuery('#imgcommander_submit').show();  		
  	}
  }    */