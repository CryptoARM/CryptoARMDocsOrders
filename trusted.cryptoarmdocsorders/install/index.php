<?php
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Application;
use Bitrix\Main\ModuleManager;
use Trusted\CryptoARM\Docs;
Loc::loadMessages(__FILE__);

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/trusted.cryptoarmdocsorders/include.php';
// require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/trusted.cryptoarmdocsbp/classes/WorkflowDocument.php';


Class trusted_cryptoarmdocsorders extends CModule
{
    // Required by the marketplace standards
    const MODULE_ID = "trusted.cryptoarmdocsorders";
    var $MODULE_ID = "trusted.cryptoarmdocsorders";
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $PARTNER_NAME;
    var $PARTNER_URI;

    function trusted_cryptoarmdocsorders()
    {
        self::__construct();
    }

    function __construct()
    {
        $arModuleVersion = array();
        include __DIR__ . "/version.php";
        $this->MODULE_NAME = Loc::getMessage("TR_CA_DOCS_MODULE_NAME_ORDERS");
        $this->MODULE_DESCRIPTION = Loc::getMessage("TR_CA_DOCS_MODULE_DESCRIPTION_ORDERS");
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        $this->PARTNER_NAME = GetMessage("TR_CA_DOCS_PARTNER_NAME");
        $this->PARTNER_URI = GetMessage("TR_CA_DOCS_PARTNER_URI");
    }

    function DoInstall()
    {
        global $DOCUMENT_ROOT, $APPLICATION;

        include __DIR__ . "/version.php";

        //$context = Application::getInstance()->getContext();
        //$request = $context->getRequest();
        //step = (int)$request["step"];

        if (!self::d7Support() || !self::coreModuleInstalled()
        || !self::ModuleIsRelevant(ModuleManager::getVersion("trusted.cryptoarmdocs"), $arModuleVersion["VERSION"])
        || !self::ModuleIsRelevant($arModuleVersion["VERSION"], ModuleManager::getVersion("trusted.cryptoarmdocs"))) {
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage("MOD_INSTALL_TITLE"),
                 $DOCUMENT_ROOT . "/bitrix/modules/" . self::MODULE_ID . "/install/step_cancel.php"
            );
        }


        // if ($request["choice"] == Loc::getMessage("TR_CA_DOCS_CANCEL_INSTALL")) {
        //     $continue = false;
        // }
        // if ($step < 2 && $continue) {
        //     $APPLICATION->IncludeAdminFile(
        //         Loc::getMessage("MOD_INSTALL_TITLE"),
        //         $DOCUMENT_ROOT . "/bitrix/modules/" . $this->MODULE_ID . "/install/step1.php"

        //     );

        // }
        // if ($step == 2 && $continue) {
        //     $APPLICATION->IncludeAdminFile(
        //         Loc::getMessage("MOD_INSTALL_TITLE"),
        //         $DOCUMENT_ROOT . "/bitrix/modules/" . $this->MODULE_ID . "/install/step2.php"
        //     );
        // }
        // if ($step == 3 && $continue) {
        //     $APPLICATION->IncludeAdminFile(
        //         Loc::getMessage("MOD_INSTALL_TITLE"),
        //         $DOCUMENT_ROOT . "/bitrix/modules/" . $this->MODULE_ID . "/install/step3.php"
        //     );
        // }
        // if ($step == 4 && $continue) {
        //     if ($request["dropDB"] == "Y") {
        //         $this->UnInstallDB();
        //         $this->UnInstallIb();
        //     } elseif ($request["dropLostDocs"]) {
        //         $lostDocs = unserialize($request["dropLostDocs"]);
        //         foreach ($lostDocs as $id) {
        //             $this->dropDocumentChain($id);
        //         }
        //     }

        self::InstallFiles();

        ModuleManager::registerModule(self::MODULE_ID);

    }

    function d7Support()
    {
        return CheckVersion(ModuleManager::getVersion("main"), "14.00.00");
    }

    function coreModuleInstalled()
    {
        return IsModuleInstalled("trusted.cryptoarmdocs");
    }

    function ModuleIsRelevant($module1, $module2)
    {
        $module1 = explode(".", $module1);
        $module2 = explode(".", $module2);
        if (intval($module2[0])>intval($module1[0])) return false;
            elseif (intval($module2[0])<=intval($module1[0])) return true;
    }


    function InstallFiles()
    {
        CopyDirFiles(
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . self::MODULE_ID . "/install/components/",
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/",
            true, true
        );

        CopyDirFiles(
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . self::MODULE_ID . "/install/admin/",
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/",
            true, false
        );
        return true;
    }

    function DoUninstall()
    {
            self::UnInstallFiles();
            ModuleManager::unRegisterModule(self::MODULE_ID);
    }

    function UnInstallFiles()
    {
        DeleteDirFilesEx("/bitrix/components/trusted/cryptoarm_docs_by_order/");
        DeleteDirFiles(
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . self::MODULE_ID . "/install/admin/",
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin"
        );
        return true;
    }
}
