
function colorSelected(id, value, front_label) {
	var theswitchis = 'off',
		switchCounter = 0,
		k = 0,
		b = 0,
		l= 0,
		thelements = [],
		children = [],
		newchildren = [],
		thisdiv = '',
		thisopt = '',
		nextAttribute = '',
		nextAttrib = [],
		theoptions = [],
		selectedmoreview = [],
		moreviews = [],
		disableDiv = '',
		i,d,e,h,z,dropdown,textdiv,theattributeid,thedropdown,thetextdiv,thedivid,thediv,dropdownval,base_image;
		
	
    dropdown = document.getElementById(id);
		
	
	//for (i = 0; i < attoptions.length; i += 1)
	//{
	//	theattributeid = attoptions[i];
	//	thedropdown = 'attribute' + theattributeid;
	//	thetextdiv = 'divattribute' + theattributeid;
    //
	//	if (id === thedropdown) {
	//		theswitchis = 'on'; }
	//
	//	if (theswitchis === 'on')
	//	{
	//
	//		if(switchCounter === 1) {
	//			nextAttribute = theattributeid; }
	//
	//		dropdown = document.getElementById(thedropdown);
	//		textdiv =  document.getElementById(thetextdiv);
	//		textdiv.innerHTML = selecttitle;
	//		//dropdown.selectedIndex = 0;
	//
	//		if(switchCounter !== 0) {
	//
	//			thelements = document.getElementById('ul-attribute' + theattributeid).getElementsByTagName('*');
	//
	//			for(h = 0; h < thelements.length; h += 1) {
	//				if (thelements[h].nodeName.toLowerCase() === 'div' || thelements[h].nodeName.toLowerCase() === 'img') {
	//					thedivid = thelements[h].id;
	//					thisdiv = document.getElementById(thedivid);
	//					thisdiv.className = "swatch";
	//				}
	//			}
	//		}
	//
	//		for (z = 1; z < dropdown.options.length; z += 1)
	//		{
	//			dropdownval = dropdown.options[z].value;
	//
	//			moreviews = jQuery(".moreview" + dropdownval);
	//			thediv = document.getElementById(dropdownval);
	//			if (thediv !== null ) {
	//				thediv.className = "swatch";}
	//			if (moreviews.length > 0) {
     //               moreviews.hide();
	//			}
	//		}
	//		switchCounter += 1;
	//	}
	//}
	

	if(nextAttribute === null || nextAttribute === '') {
		nextAttribute = 'none'; }

			
//Show more view for configurable products
    jQuery('#ul-moreviews li').hide();
    jQuery('#ul-moreviews .moreview').show();
    jQuery('#' + value).addClass("swatchSelected");

    var before_option = jQuery('#before_option').val();

    if(value){
        jQuery('#div' + id).text(front_label);
        jQuery('.baseOptionImg').hide();
        jQuery('.base' + value).show();
        var flag = true;
        jQuery('#ul-moreviews li').each(function(){
            if (jQuery(this).css('display') != 'none'){
                flag = false;
            }
        });
        if(flag ==true){
            jQuery(".moreview" + before_option).show();
            jQuery('.baseOptionImg').show();
        }else
            jQuery('#before_option').val(value);

        jQuery(".moreview" + value).show();
    }else{
        jQuery('#div' + id).text("");
        jQuery('.baseOptionImg').show();
    }
    if(jQuery('.base' + value).length > 0){
        jQuery('.base' + value).first().children('a').first().click();
    }else{
        jQuery('.baseImg').first().children('a').first().click();
    }


	if(nextAttribute !== 'none') {
		
		children = document.getElementById('ul-attribute' + nextAttribute).getElementsByTagName('*');
		nextAttrib = document.getElementById('attribute' + nextAttribute);
		
		for(h = 0; h < nextAttrib.options.length; h += 1) {
			if(nextAttrib.options[h].value !== '') {
				theoptions[b] = nextAttrib.options[h].value;
				b += 1;
			}
		}
		for(h = 0; h < children.length; h += 1) {
			if (children[h].nodeName.toLowerCase() === 'div' || children[h].nodeName.toLowerCase() === 'img') {
				newchildren[k] = children[h].id;
				k += 1;
			}
		}
		
		for(h = 0; h < newchildren.length; h += 1) {
			thisdiv = newchildren[h];
			if(theoptions[l]) {
				thisopt = theoptions[l];
				if(thisopt === thisdiv) {
					disableDiv = document.getElementById(thisdiv);
					disableDiv.className = 'swatch';
					l += 1;
				} else {
					disableDiv = document.getElementById(thisdiv);
					disableDiv.className = 'disabledSwatch';
				}
			} else {
				disableDiv = document.getElementById(thisdiv);
				disableDiv.className = 'disabledSwatch';
			}
		}
	}

}