<?php
/**
 * Overcoming Magento's Full Page Cache
 * http://www.pixafy.com/blog
 *
 * @category    Pixafy
 * @package     Pixafy_ExampleFPC
 * @copyright   Copyright (c) 2013 Pixafy (http://www.pixafy.com)
 * @author      Thomas Lackemann
 */

class Gento_ExcludeCache_Model_Container_Homecms extends Enterprise_PageCache_Model_Container_Abstract
{
    protected function _getCacheId()
    {
        return 'HOME_CMS' . md5($this->_placeholder->getAttribute('cache_id')).'_'.$this->_getIdentifier();
    }

    protected function _renderBlock()
    {
        $blockClass = $this->_placeholder->getAttribute('block');
        $template = $this->_placeholder->getAttribute('template');
        $block = new $blockClass;
        $block->setTemplate($template);
        $block->setLayout(Mage::app()->getLayout());
        return $block->toHtml();
    }
    public function getCacheKeyInfo() {
        $info = parent::getCacheKeyInfo();
        return $info;
    }
    protected function _getIdentifier()
    {
        return $this->_getCookieValue(Enterprise_PageCache_Model_Cookie::COOKIE_CUSTOMER, '');
    }
    
     
    /**
     * Note:
     * Setting $lifetime = 5 because apparently
     * cache_lifetime in cache.xml is ignored?
     */
    protected function _saveCache($data, $id, $tags = array(), $lifetime = 5)
    {
        parent::_saveCache($data, $id, $tags, $lifetime);
    }
}