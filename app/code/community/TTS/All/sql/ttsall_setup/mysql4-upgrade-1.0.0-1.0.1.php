<?php
$this->startSetup();

Mage::getModel('core/config_data')
        ->setScope('default')
        ->setPath('ttsall/feed/installed')
        ->setValue(time())
        ->save(); 

Mage::getModel('core/config_data')
        ->setScope('default')
        ->setPath('ttsall/feed/check_frequency')
        ->setValue(3600*12)
        ->save(); 

Mage::getModel('core/config_data')
        ->setScope('default')
        ->setPath('ttsall/feed/last_update')
        ->setValue(0)
        ->save(); 

Mage::getModel('core/config_data')
        ->setScope('default')
        ->setPath('ttsall/feed/interests')
        ->setValue('INSTALLED_UPDATE,UPDATE_RELEASE,NEW_RELEASE,PROMO,INFO')
        ->save();

$feedData = array();
$feedData[] = array(
    'severity'      => 4,
    'date_added'    => gmdate('Y-m-d H:i:s', time()),
    'title'         => "Gento Tech Extensions.",
    'description'   => 'You can see versions of the installed extensions right in the admin, as well as configure notifications about major updates.',
    'url'           => 'http://www.gentotech.com'
);
Mage::getModel('adminnotification/inbox')->parse($feedData);

$this->endSetup();