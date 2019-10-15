<?php
ini_set('memory_limit', '-1');

//require_once 'htmlpurifier/library/HTMLPurifier.auto.php';
//
//$config = HTMLPurifier_Config::createDefault();
//$config->set('HTML.Allowed', 'br,code,pre,ul,li,ol,img[src],span[style],p,a,table[tr,td],ol,li,strong');
//$config->set('CSS.AllowedProperties', 'color');
//$config->set('Filter.YouTube', true);
//$purifier = new HTMLPurifier($config);

//$clean_html = $purifier->purify($dirty_html);

//Define functions
function setProductDefaultValue(&$row)
{
    $row['_attribute_set'] = 'Default';
    $row['_type'] = 'simple';
    $row['has_options'] = '0';
    $row['required_options'] = '1';
    $row['status'] = '1';
    $row['visibility'] = '4';
    $row['is_in_stock'] = '1';
    $row['manage_stock'] = '1';
    $row['use_config_manage_stock'] = '1';
    $row['_root_category'] = 'Default Category';
    $row['store'] = "default";
    $row['website'] = "base";
}

function setMedia(&$row, $imgValue)
{
    if ($imgValue !== '') {
        $row['_media_attribute_id'] = '77';
        $row['_media_image'] = $imgValue;
        $row['_media_is_disabled'] = '0';
    }
}

function setCategory(&$row, $category, &$rootRow = null)
{
    if ($category !== '') {
        $row['_category'] = $category;
        $row['_root_category'] = 'Default Category';
        $brands = getBrandByCategory($category);
        if ($brands != "") {
            if ($rootRow !== null) {
                $rootRow['brands'] = $brands;
            } else {
                $row['brands'] = $brands;
            }
        }
    }
}

function getBrandByCategory($category)
{
    if (strpos($category, 'Shop by Main Brands/') !== false) {
        $brands = str_replace('Shop by Main Brands/', '', $category);
        if ($brands != "") {
            return $brands;
        }
    }
    else if(strpos($category, 'OOBI BABY')){
        $brands = 'OOBI BABY';
        return $brands;
    }
    return "";
}

function setCustomOption(&$row, $customOptionRow)
{
    if (count($customOptionRow) > 0) {
        $row['_custom_option_type'] = $customOptionRow['type'];
        $row['_custom_option_title'] = $customOptionRow['title'];
        $row['_custom_option_is_required'] = $customOptionRow['required'];
        $row['_custom_option_row_price'] = $customOptionRow['price'];
        $row['_custom_option_row_title'] = $customOptionRow['row_title'];
    }
}

function setSupperAttribute(&$row, $attribute, &$arrAttributes)
{
    if (count($attribute) > 0) {
        $row['_super_products_sku'] = $attribute['sku'];
        $row['_super_attribute_code'] = $attribute['code'];
        $row['_super_attribute_option'] = $attribute['option'];
        $row['_super_attribute_price_corr'] = $attribute['price'];
        $arrAttributes[] = array(
            'code' => $attribute['code'],
            'value' => $attribute['option']
        );
    }
}

function download_send_headers($filename)
{
    // disable caching
    $now = gmdate("D, d M Y H:i:s");
    header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
    header("Last-Modified: {$now} GMT");

    // force download
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");

    // disposition / encoding on response body
    header("Content-Disposition: attachment;filename={$filename}");
    header("Content-Transfer-Encoding: binary");
}

function exportCSV(array &$array, $fileName = "")
{
    if (count($array) == 0) {
        return null;
    }
//    download_send_headers($fileName);
    $arrChecker = array();
    $arrChecker = array(117, 118, 119, 120, 121, 122, 123, 124, 125, 126, 136, 137, 138, 139, 140, 141, 142, 143, 144, 145, 146, 158, 162, 163, 164, 165, 166, 167, 168, 169, 170, 171, 452, 539, 637, 638, 639, 640, 641, 642, 781, 782, 783, 784, 785, 786, 787, 788, 789, 790, 814, 819, 820, 821, 822, 823, 824, 825, 826, 827, 899, 934, 1030, 1085, 1090, 1114, 1166, 1205, 1480, 1507, 1551, 1552);
    if ($fileName == "") {
        $fileName = "data_export_" . date("Y_m_d_H_i_s") . ".csv";
        if (count($arrChecker) > 0) {
            $fileName = "data_export_bug_" . date("Y_m_d_H_i_s") . ".csv";
        }
    }
    ob_start();
//    $df = fopen("php://output", 'w');
    $df = fopen('_export/' . $fileName, 'w');
    fputcsv($df, array_keys(reset($array)));
    $i = 1;
    foreach ($array as $row) {
            $i++;
            // if (in_array($i - 1, $arrChecker) || count($arrChecker) == 0) {
            fputcsv($df, $row);
        // }
    }
    fclose($df);
    return ob_get_clean();
}

function getSku($productCode, &$skuArr, $i = 0)
{
    $originProductCode = $productCode;
    if ($i > 0) {
        $productCode = $productCode . "_" . $i;
    }
    if (in_array($productCode, $skuArr)) {
        ++$i;
        return getSku($originProductCode, $skuArr, $i);
    } else {
        $skuArr[] = $productCode;
        return $productCode;
    }
}

function generateCustomOptionRows(array $options)
{
    $customOptionTypeMap = array(
        'R' => 'radio',
        'C' => 'checkbox',
        'T' => 'area',
        'I' => 'field'
    );
    $optionRows = array();
    foreach ($options as $option_id => $option) {
        if ($option['type'] != 'S') {
            $optionRow = array(
                'type' => $customOptionTypeMap[$option['type']],
                'title' => $option['name'],
                'required' => '',
                'price' => '',
                'row_title' => ''
            );
            if (isset($option['value'][0])) {
                if (intval($option['value'][0]['price']) > 0) {
                    $optionRow['price'] = $option['value'][0]['price'];
                    if ($option['value'][0]['price_type'] == 'P') {
                        $optionRow['price'] .= "%";
                    }
                }
                $optionRow['required'] = 1;
                $optionRow['row_title'] = $option['value'][0]['name'];
            }
            $optionRows[] = $optionRow;
            if (count($option['value']) > 1) {
                for ($i = 1; $i < count($option['value']); $i++) {
                    if (isset($option['value'][$i])) {
                        $price = "";
                        if (intval($option['value'][$i]['price']) > 0) {
                            $price = $option['value'][$i]['price'];
                            if ($option['value'][$i]['price_type'] == 'P') {
                                $price .= "%";
                            }
                        }
                        $optionChildRow = array(
                            'type' => '',
                            'title' => '',
                            'required' => '',
                            'price' => $price,
                            'row_title' => $option['value'][$i]['name']
                        );
                        $optionChildRow[] = $optionRow;
                    }
                }
            }
        }
    }
    return $optionRows;
}

function replaceString($string)
{
    $string = strtolower($string);
    $string = str_replace(' ', '_', trim($string));
    $string = str_replace('_&_', '_and_', $string);
    $string = str_replace('&', '_and_', $string);
    $string = str_replace('_/_', '_or_', $string);
    $string = str_replace('/', '_or_', $string);
    $string = str_replace('designs', 'design', $string);
    $string = str_replace('colour', 'color', $string);
    $string = str_replace('sizes', 'size', $string);
    $string = str_replace('desigs', 'design', $string);
    $string = str_replace('partyware_you_would_like_to_order', 'partyware_you_order', $string);
    $string = str_replace('colors_(sold_as_set_of_2)', 'colors__sold_as_set_of_2_', $string);
    $string = str_replace('colors_(sold_individually)', 'colors__sold_individually_', $string);
    $string = str_replace('flavor', 'flavour', $string);
    $string = str_replace('flavours', 'flavour', $string);
    $string = str_replace('partyware_you_order_for_your_party', 'partyware_order_for_your_party', $string);
    $string = str_replace('partyware_you_would_like_to_pre-order_for_your_party', 'partyware_pre_order_your_party', $string);
    $string = str_replace('qty', 'quantity', $string);
    $string = str_replace('types_(comes_in_set_of_2)', 'types__comes_in_set_of_2_', $string);
    $string = str_replace('options', 'option', $string);
    $string = str_replace('characters', 'character', $string);

    /* This Block after is the block convert attribute name to the correct one */
//    $string = str_replace('designs', 'Designs', $string);

    return $string;
}

function addNewColumn($key, array &$sampleArray, array &$outputArr)
{
    if (!isset($sampleArray[$key])) {
        $sampleArray[$key] = "";
        foreach ($outputArr as &$arr) {
            $arr[$key] = "";
        }
    }
}

function generateSimpleChildProducts(array &$outputArr, array $options, array $optionInventory, array $parentCategories, array &$parentRow, array &$sampleRow, array &$skuArr)
{
    $parentAttributes = array();
    $selectOptionNumber = 0;
    foreach ($options as $option_id => $option) {
        if ($option['type'] == 'S') {
            $selectOptionNumber++;
        }
    }
    if ($selectOptionNumber > 1) {
        $parentRow['_type'] = 'configurable';
        $parentRow['use_config_manage_stock'] = '0';
        $parentRow['manage_stock'] = '0';
        return $parentAttributes;
    }
    foreach ($options as $option_id => $option) {
        if ($option['type'] == 'S') {
            foreach ($option['value'] as $optionValue) {
                $attributeCode = replaceString(strtolower($option['name']));
                addNewColumn($attributeCode, $sampleRow, $outputArr);

                $childRow = $sampleRow;
                setProductDefaultValue($childRow);
                $childRow['visibility'] = '1';
                $childRow['sku'] = getSku($parentRow['sku'] . '_' . $optionValue['name'], $skuArr);
                $childRow['description'] = strip_tags($parentRow['description'], '<p><a><table><img><td><tr><tbody><ol><li><object><param><strong><embed>');
                $childRow['short_description'] = $parentRow['short_description'];
                $childRow['name'] = $parentRow['name'] . ' ' . $optionValue['name'];
                $childRow['qty'] = 0;
                if (isset($optionInventory[$option_id . '_' . $optionValue['id']])) {
                    $currentInventory = $optionInventory[$option_id . '_' . $optionValue['id']];
                    if ($currentInventory['product_code'] != '') {
                        $childRow['sku'] = getSku($currentInventory['product_code'], $skuArr);
                    }
                    $childRow['qty'] = $currentInventory['amount'];
                }
                $childRow['price'] = $parentRow['price'];
                if ($optionValue['price'] > 0) {
                    if ($optionValue['price_type'] == 'P') {
                        $childRow['price'] = intval($parentRow['price']) * ((intval($optionValue['price']) + 100) / 100);
                    } else {
                        $childRow['price'] = intval($parentRow['price']) + intval($optionValue['price']);
                    }
                }
                $childRow['weight'] = $parentRow['weight'];
                if ($optionValue['weight'] > 0) {
                    if ($optionValue['weight_type'] == 'P') {
                        $childRow['weight'] = intval($parentRow['weight']) * ((intval($optionValue['weight']) + 100) / 100);
                    } else {
                        $childRow['weight'] = intval($parentRow['weight']) + intval($optionValue['weight']);
                    }
                }
                if (isset($parentCategories[0])) {
                    setCategory($childRow, $parentCategories[0]);
                }
                $childRow[$attributeCode] = $optionValue['name'];
                $parentRow['_type'] = 'configurable';
                $parentRow['manage_stock'] = '0';
                $parentAttributes[] = array(
                    'sku' => $childRow['sku'],
                    'code' => $attributeCode,
                    'option' => $optionValue['name'],
                    'price' => $optionValue['price'] . (($optionValue['price_type'] == 'P') ? '%' : '')
                );
                $extraRows = array();
                if (count($parentCategories) > 1) {
                    for ($i = 1; isset($parentCategories[$i]); $i++) {
                        $hasData = 0;
                        $childRowBelow = $sampleRow;
                        if (isset($productCategories[$i])) {
                            setCategory($childRowBelow, $parentCategories[$i], $childRow);
                            $hasData = 1;
                        }
                        if ($hasData) {
                            $extraRows[] = $childRowBelow;
                        }
                    }
                }
                $outputArr[] = $childRow;
                if (count($extraRows) > 0) {
                    foreach ($extraRows as $row) {
                        $outputArr[] = $row;
                    }
                }
            }
        }
    }
    return $parentAttributes;
}

//Finish define functions

$db = mysqli_connect("localhost", "root", "", "pupsik_cscart_products_2306");

//Create categoriy descriptions Map
$sql_category_description = "
SELECT
	category_id,
	category
FROM
	`cscart_category_descriptions`
WHERE
  lang_code = 'EN' AND category!='What\'s New'
";
$query_category_description = $db->query($sql_category_description);
$categoryDescriptionMap = array();
while ($categoryDescription = $query_category_description->fetch_assoc()) {
    $categoryDescriptionMap[$categoryDescription['category_id']] = $categoryDescription['category'];
}
//Finish categoriy descriptions Map

//Create categories Map
$sql_categories = "
SELECT
	cspc.product_id, csc.category_id, csc.parent_id, csc.id_path
FROM
	cscart_products_categories cspc
LEFT JOIN cscart_categories csc ON cspc.category_id = csc.category_id
";
$query_categories = $db->query($sql_categories);
$categoriesMap = array();
while ($category = $query_categories->fetch_assoc()) {
    $categotyString = "";
    if (isset($category['id_path'])) {
        $pathArr = explode('/', $category['id_path']);
        foreach ($pathArr as $categoryId) {
            $categoryName = $categoryDescriptionMap[$categoryId];
            $categotyString .= str_replace('/', ' & ', $categoryName) . "/";
        }
    }
    $categotyString = trim($categotyString, '/');
    if ($categotyString !== "") {
        $categoriesMap[$category['product_id']][] = $categotyString;
    }
}
//Finish Create categories Map

//Create images map
$sql_images = "
SELECT
	csil.object_id AS product_id,
	csil.image_id,
	csil.detailed_id,
	csi.image_path AS small_path,
	csi.alt AS small_name,
	csi_detail.image_path AS large_path,
	csi_detail.alt AS large_name,
	csil.type
FROM
	cscart_images_links csil
LEFT JOIN cscart_images csi ON csil.image_id = csi.image_id
LEFT JOIN cscart_images csi_detail ON csil.detailed_id = csi_detail.image_id
WHERE
	csil.object_type = 'product'
ORDER BY csil.type DESC
";
$query_images = $db->query($sql_images);
$imagesMap = array();
while ($image = $query_images->fetch_assoc()) {
    $img = array();
    $img['id'] = $image['detailed_id'];
    $img['path'] = '/' . $image['large_path'];
    $img['name'] = $image['large_name'];
    if (!isset($img['id']) || $img['id'] == '0') {
        $img['id'] = $image['image_id'];
        $img['path'] = '/' . $image['small_path'];
        $img['name'] = $image['small_name'];
    }
    $imagesMap[$image['product_id']][] = $img;
}
//Finish create images map

//Create options map
$sql_options = "
SELECT
	cspo.option_id,
	cspo.product_id,
	cspo.option_type,
	cspod.option_name,
	cspov.variant_id,
	cspov.position,
	cspovd.variant_name,
	cspov.modifier AS price,
	cspov.modifier_type AS price_type,
	cspov.weight_modifier AS weight,
	cspov.weight_modifier_type AS weight_type,
	cspov.`status`
FROM
	cscart_product_options cspo
INNER JOIN cscart_product_options_descriptions cspod ON cspo.option_id = cspod.option_id
AND cspod.lang_code = 'EN'
LEFT JOIN cscart_product_option_variants cspov ON cspo.option_id = cspov.option_id
LEFT JOIN cscart_product_option_variants_descriptions cspovd ON cspov.variant_id = cspovd.variant_id
AND cspovd.lang_code = 'EN'
WHERE
	(
		LCASE(cspovd.variant_name) NOT IN ('select', '--', 'Click here to select')
		OR cspovd.variant_name IS NULL
	)
AND cspo.`status` = 'A'
AND (
	cspov.`status` = 'A'
	OR cspo.option_type = 'S'
)
ORDER BY cspov.position, cspovd.variant_id
";
$query_options = $db->query($sql_options);
$optionMap = array();
while ($option = $query_options->fetch_assoc()) {
    if (!isset($optionMap[$option['product_id']])) {
        $optionMap[$option['product_id']] = array();
    }
    if (!isset($optionMap[$option['product_id']][$option['option_id']])) {
        $optionMap[$option['product_id']][$option['option_id']] = array();
        $optionMap[$option['product_id']][$option['option_id']]['type'] = $option['option_type'];
        $optionMap[$option['product_id']][$option['option_id']]['name'] = $option['option_name'];
        $optionMap[$option['product_id']][$option['option_id']]['value'] = array();
    }
    $var = array();
    $var['id'] = $option['variant_id'];
    $var['name'] = $option['variant_name'];
    $var['price'] = $option['price'];
    $var['price_type'] = $option['price_type'];
    $var['weight'] = $option['weight'];
    $var['weight_type'] = $option['weight_type'];
    $var['status'] = $option['status'];
    $optionMap[$option['product_id']][$option['option_id']]['value'][] = $var;
}
//Finish options map

//Create option inventory map
$sql_option_inventory = "SELECT product_id, product_code, combination, amount FROM `cscart_product_options_inventory`";
$query_option_inventory = $db->query($sql_option_inventory);
$optionInventoryMap = array();
while ($inventory = $query_option_inventory->fetch_assoc()) {
    if (!isset($optionInventoryMap[$inventory['product_id']])) {
        $optionInventoryMap[$inventory['product_id']] = array();
    }
    $optionInventoryMap[$inventory['product_id']][$inventory['combination']] = $inventory;
}
//Finish option inventory map

//Start fill product data to array

$sampleArr = array(
    'sku' => "",
    'store' => "",
    'website' => "",
    '_attribute_set' => "",
    '_type' => "",
    '_category' => "",
    '_root_category' => "",
    'color' => "",
    'description' => "",
    'has_options' => "",
    'image' => "",
    'manufacturer' => "",
    'media_gallery' => "",
    'meta_description' => "",
    'meta_keyword' => "",
    'meta_title' => "",
    'model' => "",
    'name' => "",
    'price' => "",
    'required_options' => "",
    'short_description' => "",
    'small_image' => "",
    'small_image_label' => "",
    'special_price' => "",
    'status' => "",
    'thumbnail' => "",
    'thumbnail_label' => "",
    'visibility' => "",
    'weight' => "",
    'qty' => "",
    'is_in_stock' => "",
    'manage_stock' => "",
    'use_config_manage_stock' => "",
    '_media_attribute_id' => "",
    '_media_image' => "",
    '_media_is_disabled' => "",
    '_custom_option_type' => "",
    '_custom_option_title' => "",
    '_custom_option_is_required' => "",
    '_custom_option_row_price' => "",
    '_custom_option_row_title' => "",
    '_super_products_sku' => "",
    '_super_attribute_code' => "",
    '_super_attribute_option' => "",
    '_super_attribute_price_corr' => "",
    'brands' => "",
    'news_from_date'=>"",
    'tax_class_id'=>""
);
$outputArr = array();
$outputArrAttribute = array();
$skuArr = array();
$sql_products = "
SELECT DISTINCT
	csp.product_id,
	csp.product_code,
	csp.status,
	csp.timestamp,
	cspd.full_description,
	cspd.meta_description,
	cspd.meta_keywords,
	cspd.page_title,
	cspd.product,
	csp.list_price,
	cspd.short_description,
	csp.weight,
	csp.amount,
	cspp.price AS spectial_price
FROM
	cscart_products csp
INNER JOIN cscart_product_descriptions cspd ON csp.product_id = cspd.product_id
LEFT JOIN cscart_product_prices cspp ON csp.product_id = cspp.product_id
LEFT JOIN cscart_products_categories catpro ON csp.product_id = catpro.product_id
AND cspp.membership_id = 0
AND cspp.lower_limit = 1
AND cspd.lang_code = 'EN'
WHERE csp.status = 'A' AND catpro.category_id!=0;
";
$query_products = $db->query($sql_products);
$count = 0;
while ($row = $query_products->fetch_assoc()) {
    $count++;
    $csvRow = $sampleArr;
    setProductDefaultValue($csvRow);
    $product_id = $row['product_id'];
    $productCategories = (isset($categoriesMap[$product_id])) ? $categoriesMap[$product_id] : array();
    $productImages = (isset($imagesMap[$product_id])) ? $imagesMap[$product_id] : array();
    $productOptions = (isset($optionMap[$product_id])) ? $optionMap[$product_id] : array();
    $productOptionInventory = (isset($optionInventoryMap[$product_id])) ? $optionInventoryMap[$product_id] : array();
    $productCustomOptionRows = generateCustomOptionRows($productOptions);
    $csvRow['sku'] = getSku($row['product_code'], $skuArr);
    $csvRow['description'] = ($row['full_description']);
    $csvRow['news_from_date'] = date('Y-m-d H:i:s',$row['timestamp']);
    $csvRow['tax_class_id'] = '2';

    //$csvRow['description'] = preg_replace('/<\?xml.*?\/>/im', '',$csvRow['description']);
    if (strpos($csvRow['description'], '<!--[if gte mso 9]>') !== false) {
        $csvRow['description'] = strip_tags($csvRow['description'], '<p><a><table><img><td><tr><tbody><ol><li><object><param><strong><embed><strong.style>');
    }
    //$csvRow['description'] = strip_tags($csvRow['description'],'<p><a><table><img><td><tr><tbody><ol><li><object><param><strong><embed><strong.style>');
    if ($csvRow['sku'] == 'JJB_TBE_NEAT') {
        print_r($csvRow['description']);
        print_r($row['full_description']);

    }


    $csvRow['meta_description'] = $row['meta_description'];
    $csvRow['meta_keyword'] = $row['meta_keywords'];
    $csvRow['meta_title'] = $row['page_title'];
    $csvRow['name'] = $row['product'];
    $csvRow['price'] = $row['list_price'];
    $csvRow['short_description'] = $row['short_description'];
    $csvRow['weight'] = $row['weight'];
    $csvRow['qty'] = $row['amount'];
    if (intval($row['spectial_price']) < intval($row['list_price'])) {
        $csvRow['special_price'] = $row['spectial_price'];
    }
    $productSupperAttributes = generateSimpleChildProducts($outputArr, $productOptions, $productOptionInventory, $productCategories, $csvRow, $sampleArr, $skuArr);
    if (isset($productCategories[0])) {
        $firstCategory = $productCategories[0];
        setCategory($csvRow, $firstCategory);
    }
    //var_dump($productImages);die;
    if (isset($productImages[0])) {
        $firstImage = $productImages[0];
        $csvRow['image'] = $firstImage['path'];
        $csvRow['small_image'] = $firstImage['path'];
        $csvRow['small_image_label'] = $firstImage['name'];
        $csvRow['thumbnail'] = $firstImage['path'];
        $csvRow['thumbnail_label'] = $firstImage['name'];
        setMedia($csvRow, $firstImage['path']);
    }
    if (isset($productCustomOptionRows[0])) {
        setCustomOption($csvRow, $productCustomOptionRows[0]);
    }
    if (isset($productSupperAttributes[0])) {
        setSupperAttribute($csvRow, $productSupperAttributes[0], $outputArrAttribute);
    }
    $extraRows = array();
    if (
        count($productCategories) > 1
        || count($productImages) > 1
        || count($productCustomOptionRows) > 1
        || count($productSupperAttributes) > 1
    ) {
        for ($i = 1;
             isset($productCategories[$i])
             || isset($productImages[$i])
             || isset($productCustomOptionRows[$i])
             || isset($productSupperAttributes[$i])
        ; $i++) {
            $hasData = 0;
            $csvRowBelow = $sampleArr;
            if (isset($productCategories[$i])) {
                setCategory($csvRowBelow, $productCategories[$i], $csvRow);
                $hasData = 1;
            }
            if (isset($productImages[$i])) {
                setMedia($csvRowBelow, $productImages[$i]['path']);
                $hasData = 1;
            }
            if (isset($productCustomOptionRows[$i])) {
                setCustomOption($csvRowBelow, $productCustomOptionRows[$i]);
                $hasData = 1;
            }
            if (isset($productSupperAttributes[$i])) {
                setSupperAttribute($csvRowBelow, $productSupperAttributes[$i], $outputArrAttribute);
                $hasData = 1;
            }
            if ($hasData) {
                $extraRows[] = $csvRowBelow;
            }
        }
    }
    $outputArr[] = $csvRow;
    if (count($extraRows) > 0) {
        foreach ($extraRows as $row) {
            $outputArr[] = $row;
        }
    }
}
$query_products->free_result();
exportCSV($outputArr);
//exportCSV($outputArrAttribute, "product_attributes.csv");
die();