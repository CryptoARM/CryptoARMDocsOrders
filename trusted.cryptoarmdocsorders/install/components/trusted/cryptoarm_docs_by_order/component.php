<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Trusted\CryptoARM\Docs;
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Localization\Loc;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/trusted.cryptoarmdocsorders/install/index.php';
Loader::includeModule('trusted.cryptoarmdocsorders');
Loader::includeModule(TR_CA_DOCS_CORE_MODULE);

if (CModule::IncludeModuleEx(TR_CA_DOCS_CORE_MODULE) == MODULE_DEMO_EXPIRED) {
    echo GetMessage("TR_CA_DOCS_MODULE_DEMO_EXPIRED");
    return false;
}
if (!trusted_cryptoarmdocsorders::coreModuleInstalled()) {
    echo ShowMessage(Loc::getMessage("TR_CA_DOCS_NO_CORE_MODULE"));
    return false;
}
switch (trusted_cryptoarmdocsorders::CoreAndModuleAreCompatible()) {
    case "updateCore":
        echo ShowMessage(Loc::getMessage("TR_CA_DOCS_UPDATE_CORE_MODULE") . intval(ModuleManager::getVersion("trusted.cryptoarmdocsorders")) . Loc::getMessage("TR_CA_DOCS_UPDATE_CORE_MODULE2"));
        return false;
        break;
    case "updateModule":
        echo ShowMessage(Loc::getMessage("TR_CA_DOCS_UPDATE_ORDERS_MODULE"));
        return false;
        break;
    default: break;
}

Loader::includeModule(TR_CA_DOCS_CORE_MODULE);

if ($USER->IsAuthorized()) {
    $docs = Docs\Database::getDocumentsByOrder($arParams["ORDER"]);
} else {
    $docs = new Docs\DocumentCollection();
}

$docList = $docs->getList();

$docsInfo = array();
$allIds = array();

foreach ($docList as $doc) {
    $docsInfo[] = array(
        "ID" => $doc->getId(),
        "NAME" => $doc->getName(),
        "TYPE" => $doc->getType(),
        "TYPE_STRING" => Docs\Utils::getTypeString($doc),
        "STATUS" => $doc->getStatus(),
        "STATUS_STRING" => Docs\Utils::getStatusString($doc),
    );
    $allIds[] = $doc->getId();
}

$arResult = array(
    'DOCS' => $docsInfo,
    'ALL_IDS' => $allIds,
    'ALL_IDS_JS' => json_encode($allIds),
);

$this->IncludeComponentTemplate();

