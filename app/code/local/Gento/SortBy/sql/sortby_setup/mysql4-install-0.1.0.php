<?php

$installer = $this;

$installer->startSetup();

$productEntityTypeId = $installer->getEntityTypeId('catalog_product');
$installer->updateAttribute($productEntityTypeId, 'created_at', 'frontend_label', 'Newest');
$installer->updateAttribute($productEntityTypeId, 'created_at', 'used_for_sort_by', 1);

/*$installer->updateAttribute($productEntityTypeId, 'popularity', 'frontend_label', 'Most Reviewed');
$installer->updateAttribute($productEntityTypeId, 'popularity', 'used_for_sort_by', 1);*/

$installer->endSetup();