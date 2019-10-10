<?php
//checks the name of currently installed core from highest possible version to lowest
$coreIds = array(
    'trusted.cryptoarmdocscrp',
    'trusted.cryptoarmdocsbusiness',
    'trusted.cryptoarmdocsstart',
);
$module_id = 'not found';
foreach ($coreIds as $coreId) {
    $corePathDir = $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/" . $coreId . "/";
    if(file_exists($corePathDir)) {
        $module_id = $coreId;
        break;
    }
}

define("TR_CA_DOCS_CORE_MODULE", $module_id);

define("TR_CA_DOCS_ORDERS_MODULE_ID", "trusted.cryptoarmdocsorders");

// Forms Module directories
define("TR_CA_DOCS_ORDERS_MODULE_DIR", $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . TR_CA_DOCS_ORDERS_MODULE_ID . "/");
