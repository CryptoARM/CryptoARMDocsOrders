<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;

if (!check_bitrix_sessid()) {
    return;
}

Loc::loadMessages(__FILE__);

$APPLICATION->SetTitle(Loc::getMessage("TR_CA_DOCS_UNINSTALL_TITLE"));
?>

<form action="<?= $APPLICATION->GetCurPage() ?>">
    <?= bitrix_sessid_post() ?>
    <input type="hidden" name="lang" value="<?= LANGUAGE_ID ?>">
    <input type="hidden" name="id" value="trusted.cryptoarmdocsbp">
    <input type="hidden" name="uninstall" value="Y">
    <input type="hidden" name="step" value="2">
    <? echo CAdminMessage::ShowMessage(Loc::getMessage("MOD_UNINST_WARN")) ?>
    <input type="submit" name="uninst" value="<?= Loc::getMessage("MOD_UNINST_DEL") ?>">
</form>
