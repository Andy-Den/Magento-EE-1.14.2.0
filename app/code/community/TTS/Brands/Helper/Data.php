<?php
class TTS_Brands_Helper_Data extends Mage_Core_Helper_Abstract
{
    protected $_brands;
	public function getBrands()
    {
		$brands = Mage::getResourceModel('brands/brands_collection')
                  ->loadData()
				  ->toOptionArray(false, true);
		foreach ($brands as $k => $v) {
			$brands[$k]['image_url'] = $this->getBrandImageUrl($v['image']);
			$brands[$k]['url'] = $this->getBrandUrlById($v['value']);
            $brands[$k]['status'] = $this->getBrandStatusById($v['status']);
            $brands[$k]['filler'] = $this->getBrandFillerById($v['filler']);
		}
        return $brands;
    }

	public function getBrandImageUrl($image)
	{                  
		$path = Mage::getBaseDir('media') . '/' . 'catalog'. '/' .'brands'. '/' .$image;
		if (file_exists($path)) {
			$img_url = Mage::getBaseUrl('media') . 'catalog/brands/'.$image;
		} else {
           # $img_url = Mage::getBaseUrl('skin'). 'adminhtml/default/default/images/spacer.gif';
            $img_url = Mage::getBaseUrl('media') . 'catalog/brands/'.$image;
		}
		return $img_url;
	}
    
   	public function getBrandStatusById($status)
	{
		return $status;
	}
    
    public function getBrandFillerById($filler)
	{
		return $filler;
	}


	public function getBrandUrlById($id)
	{
        if (!isset($this->_brands[$id])) {
            $this->_brands[$id] = Mage::getModel('brands/brands')->load($id);
        }

        if ($url = $this->_brands[$id]->getUrl()) {
            return $url;
        }
	}

	public function getImageUrlById($id)
	{
		$data = Mage::getResourceModel('brands/brands_collection')->getImgById($id)->getData();
    	return $this->getBrandImageUrl($data[0]['image']);
	}
}