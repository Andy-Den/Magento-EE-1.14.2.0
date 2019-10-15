<?php

class CJM_AutoSwatchesPlus_Helper_Data extends Mage_Core_Helper_Abstract
{	
	public function getUsesSwatchAttribs($_product)
	{
		$swatch_attributes = Mage::helper('autoswatchesplus')->getSwatchAttributes();
		$confAttributes = $_product->getTypeInstance(true)->getConfigurableAttributesAsArray($_product);
        foreach($confAttributes as $confAttribute):				
			$thecode = $confAttribute["attribute_code"];
			if(in_array($thecode, $swatch_attributes)) {
				return 'yes';} 
		endforeach;
		
		return 'no';	
	}
	
	public function getSortedByPosition($array)
	{
        foreach ($array as $k=>$na)
            $new[$k] = serialize($na);
        $uniq = array_unique($new);
        foreach($uniq as $k=>$ser)
            $new1[$k] = unserialize($ser);
        return ($new1);
    }
	
	public function getAssociatedOptions($product, $att)
	{
		$swatch_attributes = Mage::helper('autoswatchesplus')->getSwatchAttributes();
		$confAttributes = $product->getTypeInstance(true)->getConfigurableAttributesAsArray($product);
        $availattribs = array();
		$thecode = '';
		$html = '';  
       	
		foreach ($confAttributes as $confAttribute) {
			$thecode = $confAttribute["attribute_code"];
			if(in_array($thecode, $swatch_attributes))
			{
				$attributeCode = $confAttribute['attribute_code'];
				$attributeName = $confAttribute['label'];
				$options = $confAttribute["values"];
				
				foreach($options as $option) {
					$string = $option["label"];
					$label = trim(substr($string, 0, strpos("$string#", "#")));
					$optvalue = $option["value_index"]; 
					$availattribs['value'][] = $optvalue;
					$availattribs['label'][] = $label;;							
               	}
			}
		}
		
		if($availattribs) {
			$count = count($availattribs['value']);
		} else {
			$count = 0; }
		
		if($count < 1) {
			$html .= '<select class="'.$att.'" id="'.$att.'__value_id__" name="'.$att.'[__value_id__]" disabled="disabled" style="width:100px;">'; }
		else {
			$html .= '<select class="'.$att.'" id="'.$att.'__value_id__" name="'.$att.'[__value_id__]" style="width:100px;">';
			$html .= '<option value="">&nbsp;</option>';
			
			for($s = 0; $s < $count; $s++) {
    			$html .= '<option value="'.$availattribs['value'][$s].'">'.$availattribs['label'][$s].'</option>'; }
			$html .= '</select><br />'; 
		}

		return $html;	
	}
	
	public function getMoreViews($_product)
	{

		$html = '';
        $lightbox = '';
		$product_base = $this->decodeImages($_product);
        $counter = 0;
		if($product_base) {
			$hider = Mage::getStoreConfig('auto_swatches_plus/autoswatchesplusgeneral/hidebase',Mage::app()->getStore());								
//			$_gallery = Mage::getModel('catalog/product')->load($_product->getId())->getMediaGalleryImages();
			$swatch_attributes = Mage::helper('autoswatchesplus')->getSwatchAttributes();
			$confAttributes = $_product->getTypeInstance(true)->getConfigurableAttributesAsArray($_product);
			$count = count($product_base['image']);
            $html .= '<ul id="ul-moreviews" class="zoom-desc">';

			for($s = 0; $s < $count; $s++) {
				if($product_base['morev'][$s] == '') {
					$product_image = $product_base['image'][$s];
					$product_thumb = $product_base['thumb'][$s];
                    $product_small = $product_base['smallimage'][$s];
                    $selected = "";
                    $baseClass = "";
                    if($product_base['color'][$s] != ''){
                        $baseClass = " baseOptionImg base".$product_base['color'][$s];
                    }
                    $baseImgClass = "";
                    if($s == $product_base['baseIndex']){
                        $baseImgClass = " baseImg";
                        $selected = "selected";
                    }
                    if($baseClass != "" || $baseImgClass != "") {
                        $html .= '<li name="moreview1" class="real-lb-order-'.$counter.' moreview' . $baseClass . $baseImgClass . '">';
                        $html .= '<a class="cloud-zoom-gallery t1 ' . $selected . '"   rel="useZoom: \'zoom1\', smallImage: \'' . $product_small . '\' " href="' . $product_small . '" title="" data-order="' . $counter . '" data-value="0"';
//                        $html .= " onclick='$('image').src = this.href; return false;'";
                        $html .= '><img src="' . $product_thumb . '" width="77" height="77" alt="" />';
                        $html .= '</a></li>';
                        $lightbox .= '<a data-lightbox = "lb-0" class="lb-order-' . $counter . '" href="' . $product_image . '"></a>';
                        $counter++;
                    }
				}
			}

            foreach($confAttributes as $confAttribute){
                $thecode = $confAttribute["attribute_code"];
                if(in_array($thecode, $swatch_attributes)){
                    $options = $confAttribute["values"];
                    foreach($options as $option){
                        $value = $option['value_index'];
                        for($t = 0; $t < $count; $t++) {
                            if($product_base['morev'][$t] == $value) {
                                $product_image = $product_base['image'][$t];
                                $product_thumb = $product_base['thumb'][$t];
                                $product_small = $product_base['smallimage'][$t];
                                $moreViewClass = ' moreview'.$value;
                                $hideClass = "";
                                if($product_base['color'][$t] != '' && $hider == 1){
                                    $hideClass = " no-display";
                                }
                                $baseClass = "";
                                $displayStyle = " display:none;";
                                if($product_base['color'][$t] != ''){
                                    $baseClass = " baseOptionImg base".$product_base['color'][$t];
                                    $displayStyle = "";
                                }
                                $baseImgClass = "";
                                if($s == $product_base['baseIndex']){
                                    $baseImgClass = " baseImg";
                                }
                                $html .= '<li name="moreview'.$value.'" class="real-lb-order-'.$counter.' ' . $moreViewClass . $hideClass . $baseClass . $baseImgClass . '" style="z-index:-1;' . $displayStyle . '">';
                                $html .= '<a class="cloud-zoom-gallery t'.$value.'"   rel="useZoom: \'zoom1\', smallImage: \''.$product_small.'\' " href="'.$product_small.'" title="" data-order="'.$counter.'" data-value="0"';
//                                $html .= " onclick='$('image').src = this.href; return false;'";
                                $html .= '><img src="'.$product_thumb.'" width="77" height="77" alt="" />';
                                $html .= '</a></li>';
                                $lightbox .= '<a data-lightbox = "lb-0" class="lb-order-'.$counter.'" href="'.$product_image.'"></a>';
                                $counter++;
                            }
                        }
                    }
                }
            }

            for($s = 0; $s < $count; $s++) {
                if($product_base['morev'][$s] == '') {
                    $product_image = $product_base['image'][$s];
                    $product_thumb = $product_base['thumb'][$s];
                    $product_small = $product_base['smallimage'][$s];
                    $baseClass = "";
                    if($product_base['color'][$s] != ''){
                        $baseClass = " baseOptionImg base".$product_base['color'][$s];
                    }
                    $baseImgClass = "";
                    if($s == $product_base['baseIndex']){
                        $baseImgClass = " baseImg";
                    }
                    if($baseClass == "" && $baseImgClass == "") {
                        $html .= '<li name="moreview1" class="real-lb-order-'.$counter.' moreview' . $baseClass . $baseImgClass . '">';
                        $html .= '<a class="cloud-zoom-gallery t1"   rel="useZoom: \'zoom1\', smallImage: \'' . $product_small . '\' " href="' . $product_small . '" title="" data-order="' . $counter . '" data-value="0"';
//                        $html .= " onclick='$('image').src = this.href; return false;'";
                        $html .= '><img src="' . $product_thumb . '" width="77" height="77" alt="" />';
                        $html .= '</a></li>';
                        $lightbox .= '<a data-lightbox = "lb-0" class="lb-order-' . $counter . '" href="' . $product_image . '"></a>';
                        $counter++;
                    }
                }
            }
			$html .= '</ul>';

            $html .= '<div style="display: none" id="lightbox-hidden">'.$lightbox.'</div>';
		}
		return $html;
	}
	
	public function getSwatchList()
	{
		$swatch_attributes = Mage::helper('autoswatchesplus')->getSwatchAttributes();
		$html = '';
		
		$count = count($swatch_attributes);
		
		for($i = 0; $i < $count; $i++) {
			
			if($i == $count-1) {
				$html .= $swatch_attributes[$i];
			} else {
				$html .= $swatch_attributes[$i].'&nbsp;&#8226;&nbsp;';
			}
		}
		return $html;
	}
	
	public function getSwatchAttributes()
	{
		$swatch_attributes = array();
		$swatchattributes = Mage::getStoreConfig('auto_swatches_plus/autoswatchesplusgeneral/colorattributes');
		$swatch_attributes = explode(",", $swatchattributes);
		
		 foreach($swatch_attributes as &$attribute) {
         	$attribute = Mage::getModel('eav/entity_attribute')->load($attribute)->getAttributeCode();
		 }
		 unset($attribute);
	
		return $swatch_attributes;
	}
	
	public function getSwatchSize($_product)
	{
		if($_product != 'null') {
			if($_product->getCjm_swatchsize()) {
				return $_product->getCjm_swatchsize(); }
			else {
				$swatchsize = Mage::getStoreConfig('auto_swatches_plus/autoswatchesplusgeneral/size',Mage::app()->getStore());
				if ($swatchsize == ""){
					$swatchsize = 25;}
				return $swatchsize;
			}
		} else{
			$swatchsize = Mage::getStoreConfig('auto_swatches_plus/autoswatchesplusgeneral/size',Mage::app()->getStore());
			if ($swatchsize == ""){
				$swatchsize = 25;}
			return $swatchsize;
		}
	}
	
	public function findColorImage($value, $arr, $key)
	{
		$found = '';
		if(isset($arr[$key])) {
 			$total = count($arr[$key]);
			if($total>0)
 			{
				for($i=0; $i<$total;$i++)
 				{
					if($value == ucwords($arr[$key][$i]))
 					{
 						$found = $arr['image'][$i];
					}
 				}
 			}
		}
 		return $found;
	}
	public function findColorImagesmall($value, $arr, $key)
	{
		$found = '';
		if(isset($arr[$key])) {
 			$total = count($arr[$key]);
			if($total>0)
 			{
				for($i=0; $i<$total;$i++)
 				{
					if($value == ucwords($arr[$key][$i]))//if it matches the color listed in the attribute
 					{
 						$found = $arr['smallimage'][$i];//return the image src
					}
 				}
 			}
		}
 		return $found;
	}
	public function decodeImages($_product)
	{
		$helper       = Mage::helper('ecommerceteam_cloudzoom');
		$bigImageWidth  = intval($helper->getConfigData('big_image_width'));
		$bigImageHeight = intval($helper->getConfigData('big_image_height'));
		$_gallery = Mage::getModel('catalog/product')->load($_product->getId())->getMediaGalleryImages();
		$imgcount = $_gallery->count();
		$product_base = array();
        $product_base['baseIndex'] = 0;

		if($imgcount >=1)
		{
			$imgIdsBase = array();
			$imgIdsMoreViews = array();
			
			$cjm_colorselector_base = unserialize($_product->getData('cjm_imageswitcher'));

			if($cjm_colorselector_base) {
				foreach($cjm_colorselector_base as $cjm_colorselectorItem => $cjm_colorselectorVal) {
					$imgIdsBase[$cjm_colorselectorItem]['colorval'] = $cjm_colorselectorVal; }
			}
			
			$cjm_colorselector_more = unserialize($_product->getData('cjm_moreviews'));

			if($cjm_colorselector_more) {
				foreach($cjm_colorselector_more as $cjm_colorselectorItem => $cjm_colorselectorVal) {
					$imgIdsMoreViews[$cjm_colorselectorItem]['moreviews'] = $cjm_colorselectorVal; }
			}
 						
 			if($cjm_colorselector_more && $cjm_colorselector_base) {
                $i = 0;
				foreach ($_gallery as $_image )
 				{
 					$product_base['color'][] = isset($_image['value_id']) ? strval($imgIdsBase[$_image['value_id']]['colorval']) : null;
					$product_base['image'][] = strval(Mage::helper('catalog/image')->init($_product, 'base', $_image->getFile()));
					$product_base['smallimage'][] = strval(Mage::helper('catalog/image')->init($_product, 'base', $_image->getFile())->resize($bigImageWidth, $bigImageHeight));
					$product_base['thumb'][] = strval(Mage::helper('catalog/image')->init($_product, 'thumbnail', $_image->getFile())->resize(77,77));
					$product_base['morev'][] = isset($_image['value_id']) ? strval($imgIdsMoreViews[$_image['value_id']]['moreviews']) : null;
                    if($_image->getFile() == $_product->getImage()){
                        $product_base['baseIndex'] = $i;
                    }
                    $i++;
				}
			}
		}
		return $product_base;	
	}
	
	public function getSwatchUrl($optionId)
    {
        $uploadDir = Mage::getBaseDir('media') . DIRECTORY_SEPARATOR . 
                                                    'autoswatchesplus' . DIRECTORY_SEPARATOR . 'swatches' . DIRECTORY_SEPARATOR;
        if (file_exists($uploadDir . $optionId . '.jpg'))
        {
            return Mage::getBaseUrl('media') . '/' . 'autoswatchesplus' . '/' . 'swatches' . '/' . $optionId . '.jpg';
        }
		else
		{
			return Mage::getBaseUrl('media') . '/' . 'autoswatchesplus' . '/' . 'swatches' . '/' . "noneimage" . '.jpg';
		}
        return '';
    }
	
	public function getSwatchHtml($_productAttribute, $_attribute, $swatchsize, $_product)
	{
		$thisattrib = $_productAttribute->getAttributeCode();
		$attribute = $_product->getResource()->getAttribute($thisattrib);
 		$frontend = $attribute->getFrontend();
 		$selectOptions = $frontend->getSelectOptions();
		$frontendlabel = 'null';
		$html = '';
		$atid = $_attribute->getAttributeId();
		$cnt = 1;
		$hide = Mage::getStoreConfig('auto_swatches_plus/autoswatchesplusgeneral/hidedropdown',Mage::app()->getStore());
		
		if($hide == 0) 
		{
			$html = $html.'<div id="color-swatches" class="swatchesContainerPadded"><ul id="ul-attribute'.$atid.'">';
		} else {
			$html = $html.'<div id="color-swatches" class="swatchesContainer"><ul id="ul-attribute'.$atid.'">';
		}		
                				
		$_option_vals = array();				
		$_colors = array();
        $_collection = Mage::getResourceModel('eav/entity_attribute_option_collection')->setPositionOrder('asc')->setAttributeFilter($_productAttribute->getId())->setStoreFilter(0)->load();
		
		foreach( $_collection->toOptionArray() as $_cur_option )
		{
			$_option_vals[$_cur_option['value']] = array('id' => $_cur_option['value'], 'internal_label' => $_cur_option['label'], 'order' => $cnt);
			$cnt++;
        }

		$temp =  new Mage_Catalog_Block_Product_View_Type_Configurable();
       	$_assProducts = Mage::helper('core')->decorateArray($temp->getAllowProducts());      
           	
		foreach($_assProducts as $_assProduct) 
		{
             $_tempProduct = Mage::getModel('catalog/product');
             $_tempProduct->load($_assProduct->getId());
             $_option_id = $_tempProduct->getData($_productAttribute->getAttributeCode());
			 $order = $_option_vals[$_option_id]['order'];
                
			if ( isset($_option_vals[$_option_id]['internal_label']) ) 
			{
                $_option_vals[$_option_id]['label'] = $_tempProduct->getAttributeText($_productAttribute->getAttributeCode()); 				
				array_push($_colors, array('id' => $_option_id, 'order' => $order));
            }   
       	}
				
		$_color_swatch = Mage::helper('autoswatchesplus')->getSortedByPosition($_colors);
		$_color_swatch = array_values($_color_swatch);
		
		foreach ($_color_swatch as $key => $val) { 
   			 $sortSingle[$key] = $_color_swatch[$key]['order']; } 

		asort ($sortSingle); 
		reset ($sortSingle); 
		
		while (list ($singleKey, $singleVal) = each ($sortSingle)) { 
    		$newArr[] = $_color_swatch[$singleKey]; } 

		$_color_swatch = $newArr;  
		
		foreach($_color_swatch as $_inner_option_id)
 		{
			$img="img";
			$theId =$_inner_option_id['id'];
			$attributeoptionlabel = $_option_vals[$theId]['internal_label'];
			$frontendlabel = $_option_vals[$theId]['label'];
			
			preg_match_all('/((#?[A-Za-z0-9]+))/', $attributeoptionlabel, $matches);
				
			if ( count($matches[0]) > 0 )
			{
				$color_value = $matches[1][count($matches[0])-1];
				$findme = '#';
				$pos = strpos($color_value, $findme);
				
				$product_base = Mage::helper('autoswatchesplus')->decodeImages($_product);			
				$product_image = Mage::helper('autoswatchesplus')->findColorImage(ucwords(strval($theId)),$product_base,'color');//returns url for base image
				$product_imagesmall = Mage::helper('autoswatchesplus')->findColorImagesmall(ucwords(strval($theId)),$product_base,'color');										
              	if (Mage::helper('autoswatchesplus')->getSwatchUrl($theId))
				{ 
					$swatchimage = Mage::helper('autoswatchesplus')->getSwatchUrl($theId);
					$html = $html.'<li class="swatchContainer cloud-zoom-gallery"   rel="useZoom: \'zoom1\', smallImage: \''.$product_imagesmall.'\' " href="'.$product_image.'">';
					$html = $html.'<img src="'.$swatchimage.'" id="'.$theId.'" class="swatch" alt="'.$frontendlabel.'" height="'.$swatchsize.'px" title="'.$frontendlabel.'" ';
					$html = $html.'onclick="colorSelected';
					$html = $html."('attribute".$atid."','".$theId."','".$frontendlabel."')";
					$html = $html.'" />';
					$html = $html.'</li>';
				
				} else {
					
					if($pos !== false)
					{
              			$html = $html.'<li class="swatchContainer cloud-zoom-gallery"   rel="useZoom: \'zoom1\', smallImage: \''.$product_imagesmall.'\' " href="'.$product_image.'">';
						$html = $html.'<div id="'.$theId.'" class="swatch" style="background-color:'.$color_value.'; height:'.$swatchsize.'px;" ';
						$html = $html.' onclick="colorSelected';
						$html = $html."('attribute".$atid."','".$theId."','".$frontendlabel."')";
						$html = $html.'">';
						$html = $html.'</div></li>';
					}
				} 
			}
 		}
		$html = $html.'</ul></div><div class="clear"></div>';
		return $html;	
	}

}