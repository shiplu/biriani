<?php

/**
 * Autoload function for Biriani
 * @param type $class class name
 * @return string file path containing class
 */
function biriani_autoload($cname) {

    if (class_exists($cname)) {
        return true;
    }

    $class_map = array(
        // Core Biriani classes
        "Biriani" => "Biriani.php",
        "Biriani_Cache" => "Biriani_Cache.php",
        "Biriani_Data" => "Biriani_Data.php",
        "Biriani_Extractable_Abstract" => "Biriani_Extractable_Abstract.php",
        "Biriani_HTTPTransaction" => "Biriani_HTTPTransaction.php",
        "Biriani_Registry" => "Biriani_Registry.php",
        "Biriani_Request" => "Biriani_Request.php",
        "Biriani_Response" => "Biriani_Response.php",
        "BirianiUncompletedRequestObjectException" => "Exceptions.php",
        "BirianiMatchedExtractableNotFoundException" => "Exceptions.php",
        "BirianiRequiredExtensionNotFoundException" => "Exceptions.php",
        "IFillableData" => "IFillableData.php",
        "IExtractable" => "IExtractable.php",
        "IData" => "IData.php",
        "Biriani_Log" => "Biriani_Log.php",
    );

    // Add Recipes
    $recipes = array_map('basename', glob(dirname(__FILE__) . '/recipes/*Biriani.php'));
    foreach ($recipes as $recipe) {
        $class_map[substr($recipe, 0, -4)] = 'recipes/' . $recipe;
    }

    if (isset($class_map[$cname])) {
        $fname = dirname(__FILE__) . DIRECTORY_SEPARATOR . $class_map[$cname];
        if (file_exists($fname)) {
            // including the file
            include $fname;
            return true;
        }
    }
    return false;
}

spl_autoload_register("biriani_autoload");
?>
