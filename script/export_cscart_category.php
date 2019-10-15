<?php
ini_set('memory_limit', '-1');
//Define functions
function download_send_headers($filename) {
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

function exportCSV(array &$array){
    if (count($array) == 0) {
        return null;
    }
    ob_start();
    $df = fopen("php://output", 'w');
    fputcsv($df, array_keys(reset($array)));
    foreach ($array as $row) {
        fputcsv($df, $row);
    }
    fclose($df);
    return ob_get_clean();
}

function replaceString($string){
    $string = trim(preg_replace('/\s+/', ' ', $string));
    return $string;
}

function getCategoryPath($category, $path, $categoryMap){
    $pathArr = explode('/', $path);
    $categotyString = "Default Category";
    foreach($pathArr as $categoryId){
        if($categoryId != $category){
            $categoryName = $categoryMap[$categoryId];
            $categotyString .= "/" . str_replace('/', ' & ', $categoryName);
        }
    }
    return $categotyString;
}
//Finish define functions

$db = mysqli_connect("localhost","root","","pupsik_cscart_products");

//Create categoriy descriptions Map
$sql_category_description = "
SELECT
	category_id,
	category
FROM
	cscart_category_descriptions
WHERE
  lang_code = 'EN'
";
$query_category_description = $db->query($sql_category_description);
$categoryDescriptionMap = array();
while($categoryDescription = $query_category_description->fetch_assoc()){
    $categoryDescriptionMap[$categoryDescription['category_id']] = $categoryDescription['category'];
}
//Finish categoriy descriptions Map

//Start fill category data to array
$sampleArr = array(
    'Name' => "",
    'Path' => "",
    'Position' => "",
    'Is Active' => "",
    'Url Key' => "",
    'Description' => "",
    'Image' => "",
    'Page Title' => "",
    'Meta Keywords' => "",
    'Meta Description' => "",
    'Include In Menu' => "",
    'Display Mode' => "",
    'CMS Block' => "",
    'Is Anchor' => "",
    'Availabe Sort By' => "",
    'Default Sort By' => "",
    'Page Layout' => "",
    'Custom Layout Update' => ""
);
$outputArr = array();
$sql_categories = "
SELECT
	csc.category_id,
	csc.parent_id,
	csc.id_path,
	csc.status,
	csc.position,
	cscd.category AS `name`,
	cscd.description,
	cscd.meta_keywords,
	cscd.meta_description,
	cscd.page_title,
	cssn.`name` as url
FROM
	cscart_categories csc
INNER JOIN cscart_category_descriptions cscd ON csc.category_id = cscd.category_id
LEFT JOIN cscart_seo_names cssn ON csc.category_id = cssn.object_id AND cssn.type = 'c'
AND cscd.lang_code = 'EN'
ORDER BY csc.parent_id
";
$query_categories = $db->query($sql_categories);
while($row = $query_categories->fetch_assoc()){
    $csvRow = $sampleArr;
    $csvRow['Name'] = str_replace('/', ' & ', $row['name']);
    $csvRow['Path'] = getCategoryPath($row['category_id'], $row['id_path'], $categoryDescriptionMap);
    $csvRow['Position'] = $row['position'];
    if($row['status'] == 'A'){
        $csvRow['Is Active'] = 'Yes';
    }else{
        $csvRow['Is Active'] = 'No';
    }
    $csvRow['Url Key'] = $row['url'];
    $csvRow['Description'] = replaceString($row['description']);
    $csvRow['Page Title'] = $row['page_title'];
    $csvRow['Meta Keywords'] = $row['meta_keywords'];
    $csvRow['Meta Description'] = replaceString($row['meta_description']);
    $csvRow['Include In Menu'] = 'Yes';
    $csvRow['Display Mode'] = 'Products only';
    $csvRow['CMS Block'] = '';
    $csvRow['Is Anchor'] = 'Yes';
    $csvRow['Availabe Sort By'] = '';
    $csvRow['Default Sort By'] = '';
    $csvRow['Page Layout'] = 'No layout updates';
    $csvRow['Custom Layout Update'] = '';
    $outputArr[] = $csvRow;
}
$query_categories->free_result();
download_send_headers("categories_" . date("Y-m-d") . ".csv");
echo exportCSV($outputArr);
die();