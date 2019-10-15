<?php
class Mage_Checkout_Block_Cart_Items extends Mage_Checkout_Block_Cart_Abstract
{
    /**
     * Prepare Quote Item Product URLs
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Return customer quote items
     *
     * @return array
     */
    public function getItems()
    {
        if ($this->getCustomItems()) {
            return $this->getCustomItems();
        }

        return parent::getItems();
    }

}