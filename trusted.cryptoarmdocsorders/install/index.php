<?php
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Application;
use Bitrix\Main\ModuleManager;
use Trusted\CryptoARM\Docs;

Loc::loadMessages(__FILE__);

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/trusted.cryptoarmdocsorders/include.php';

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

        if (!$this->d7Support() || !$this->coreModuleInstalled() || $this->CoreAndModuleAreCompatible() !== "ok") {
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage("MOD_INSTALL_TITLE"),
                 $DOCUMENT_ROOT . "/bitrix/modules/" . self::MODULE_ID . "/install/step_cancel.php"
            );
        }

        $this->InstallFiles();
        ModuleManager::registerModule(self::MODULE_ID);
    }

    function d7Support()
    {
        return CheckVersion(ModuleManager::getVersion("main"), "14.00.00");
    }

    function coreModuleInstalled()
    {
        return IsModuleInstalled(TR_CA_DOCS_CORE_MODULE);
    }

    function CoreAndModuleAreCompatible()
    {
        include __DIR__ . "/version.php";
        $coreVersion = explode(".", ModuleManager::getVersion(TR_CA_DOCS_CORE_MODULE));
        $moduleVersion = explode(".", $arModuleVersion["VERSION"]);
        if (intval($moduleVersion[0])>intval($coreVersion[0])) {
            $res = "updateCore";
        } elseif (intval($moduleVersion[0])<intval($coreVersion[0])) {
            $res = "updateModule";
        } else $res = "ok";

        return $res;
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
        global $DOCUMENT_ROOT, $APPLICATION;

        $context = Application::getInstance()->getContext();
        $request = $context->getRequest();
        $step = (int)$request["step"];

        if ($step < 2) {
            $APPLICATION->IncludeAdminFile(
                Loc::getMessage("MOD_UNINSTALL_TITLE"),
                $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . self::MODULE_ID . "/install/unstep1.php"
            );
        }
            $this->UnInstallFiles();
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
