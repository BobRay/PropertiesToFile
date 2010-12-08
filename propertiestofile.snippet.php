
<?php
/**
 * PropertiesToFile
 *
 * Copyright 2010 by Bob Ray <http://bobsguides.com>
 *
 * PropertiesToFile is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * PropertiesToFile is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * QuickEmail; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
 * Suite 330, Boston, MA 02111-1307 USA
 *
 * @package propertiespofile
 */
/**
 * PropertiesToFile - A snippet to write element properties to a file for use in a build script (MODx Revolution)
 *
 * @package propertiestofile
 */



$sp =& $scriptProperties;

$header = $modx->getOption('headerTpl',$sp);
if (empty($header)) {
    $header = "<?php\n
 /**
 * Properties for [[+elementName]]
 *
 * @package [[+package]]
 * @subpackage [[+subPackageName]]
 */\n\n";

}

$package = $modx->getOption('packageName',$sp);
$elementName = $modx->getOption('elementName',$sp);
if (empty($elementName)) {
   $element = $packageName;
}
$elementId = $modx->getOption('elementId',$sp);
$subPackageName = $modx->getOption('subPackageName',$sp);
$header = str_replace('[[+subPackageName]]',$subPackageName,$header);
$header = str_replace('[[+package]]',$package,$header);
$header = str_replace('[[+elementName]]',$elementName,$header);

$output = $header;
if (! is_numeric($elementId)) {
   return 'elementId must be an ID number';
}
$elementObj = $modx->getObject($type,$elementId);
if (! $elementObj) {
   return 'Failed to get element: ' .$elementId;
}
$props = $elementObj->get('properties');

$output .= '$properties = array(' ."\n";
foreach($props as $prop) {
    $output .= "    array(\n";
    foreach($prop as $k=>$v) {
        if ($k == 'desc_trans') {
           continue;
        }
        if ($k == 'options') {
            if (empty($v)) {
                $output .= "        '{$k}' => '',\n";
            } else {
               $output .= "        '{$k}' => array(\n";
               foreach ($v as $l=>$r) {
                   $output .= "            array(\n";
                   foreach ($r as $a=>$b) {

                       $output .= "                '{$a}' => '{$b}',\n";
                   }
                   $output .= "            ),\n";
               }
               $output .= "        ),\n";
            }
            continue;
        }
        $output .= "        '{$k}' => '{$v}',\n";
    }
    $output .= "    ),\n";
}
$output .= ");\n";

$output .= "\n" . 'return $properties;';

$fileName = $modx->getOption('fileName',$sp);
$path = $modx->getOption('path', $sp);
if (empty($path) ) {
  $path = MODX_ASSETS_PATH . 'components/' . $package . '/_build/data/';
}
$fullPath = $path . $fileName;

if ($fp = fopen ($fullPath, "w")) {
    fwrite($fp, $output);
    fclose($fp);
    return 'File Written';
} else {

    return 'Failed to open file: ' . $fullPath;

}