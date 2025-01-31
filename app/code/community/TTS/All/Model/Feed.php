<?php

class TTS_All_Model_Feed extends Mage_AdminNotification_Model_Feed {
    const XML_FREQUENCY_PATH = 'ttsall/feed/check_frequency';
    const XML_LAST_UPDATE_PATH = 'ttsall/feed/last_update';
    const XML_ITERESTS = 'ttsall/feed/interests';

    const URL_EXTENSIONS = 'http://gentotech.com/magento/feed-news.xml';
    const URL_NEWS = 'http://gentotech.com/magento/feed-news.xml';

    public static function check()
    {
        return Mage::getModel('ttsall/feed')->checkUpdate();
    }

    public function checkUpdate()
    {
        if (($this->getFrequency() + $this->getLastUpdate()) > time()) {
            return $this;
        }

        $this->setLastUpdate();

        if (!extension_loaded('curl')) {
            return $this;
        }

        // load all new and relevant updates into inbox
        $feedData = array();
        $feedXml = $this->getFeedData();

        $wasInstalled = gmdate('Y-m-d H:i:s', Mage::getStoreConfig('ttsall/feed/installed'));

        if ($feedXml && $feedXml->channel && $feedXml->channel->item) {
            foreach ($feedXml->channel->item as $item) {
                $date = $this->getDate((string) $item->pubDate);

                // compare strings, but they are well-formmatted 
                if ($date < $wasInstalled) {
                    continue;
                }
                if (!$this->isInteresting($item)) {
                    continue;
                }

                $feedData[] = array(
                        'severity' => 3,
                        'date_added' => $this->getDate($date),
                        'title' => (string) $item->title,
                        'description' => (string) $item->description,
                        'url' => (string) $item->link,
                );
            }           
            if ($feedData) {
                Mage::getModel('adminnotification/inbox')->parse($feedData);
            }
        }

        //load all available extensions in the cache
        $this->_feedUrl = self::URL_EXTENSIONS;
        $feedData = array();
        $feedXml = $this->getFeedData();
        if ($feedXml && $feedXml->channel && $feedXml->channel->item) {
            foreach ($feedXml->channel->item as $item) {
                $feedData[(string) $item->code] = array(
                        'name' => (string) $item->title,
                        'url' => (string) $item->link,
                        'version' => (string) $item->version,
                );
            }
            if ($feedData) {
                Mage::app()->saveCache(serialize($feedData), 'ttsall_extensions');
            }
        }

        return $this;
    }

    public function getFrequency()
    {
        return Mage::getStoreConfig(self::XML_FREQUENCY_PATH);
    }

    public function getLastUpdate()
    {
        return Mage::app()->loadCache('ttsall_notifications_lastcheck');
    }

    public function setLastUpdate()
    {
        Mage::app()->saveCache(time(), 'ttsall_notifications_lastcheck');
        return $this;
    }

    public function getFeedUrl()
    {
        if (is_null($this->_feedUrl)) {
            $this->_feedUrl = self::URL_NEWS;
        }
        $query = '?s=' . urlencode(Mage::getStoreConfig('web/unsecure/base_url'));
        return $this->_feedUrl . $query;
    }

    protected function getInterests()
    {
        return Mage::getStoreConfig(self::XML_ITERESTS);
    }

    protected function isInteresting($item)
    { 
        $interests = @explode(',', $this->getInterests());
        $types = @explode(':', (string) $item->type);

        $extenion = (string) $item->extension;

        $selfUpgrades = array_search(TTS_All_Model_Source_Updates_Type::TYPE_INSTALLED_UPDATE, $types);

        foreach ($types as $type) {
            if (array_search($type, $interests) !== false) {
                return true;
            }

            if ($extenion && ($type == TTS_All_Model_Source_Updates_Type::TYPE_UPDATE_RELEASE) && $selfUpgrades) {
                if ($this->isExtensionInstalled($extenion)) {
                    return true;
                }
            }
        }

        return false;
    }

    protected function isExtensionInstalled($code)
    {
        $modules = array_keys((array) Mage::getConfig()->getNode('modules')->children());
        foreach ($modules as $moduleName) {
            if ($moduleName == $code) {
                return true;
            }
        }

        return false;
    }

}