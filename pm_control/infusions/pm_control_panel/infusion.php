<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: infusion.php
| Author: karrak
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) {
    die("Access Denied!");
}

$locale = fusion_get_locale("", PMC_LOCALE);

// Infusion general information
$inf_title = $locale['PMC_000'];
$inf_description = $locale['PMC_001'];
$inf_version = "1.0";
$inf_developer = "karrak";
$inf_email = "admin@fusionjatek.hu";
$inf_weburl = "http://fusionjatek.hu";
$inf_folder = "pm_control_panel";
$inf_image = "pm_control.svg";

// Insert Multilanguage
$inf_mlt[] = [
    'title'  => $locale['PMC_003'],
    'rights' => 'PMC',
];

// Insert settings
$settings = [
    'limit'   => 5,
    'days'    => 90,
    'control' => 0,
    'bubble'  => 1
];

foreach ($settings as $name => $value) {
    $inf_insertdbrow[] = DB_SETTINGS_INF." (settings_name, settings_value, settings_inf) VALUES ('".$name."', '".$value."', '".$inf_folder."')";
}

//Infuse insertations
$inf_insertdbrow[] = DB_PANELS." (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status, panel_url_list, panel_restriction, panel_languages)
VALUES('".$locale['PMC_004']."', '".$inf_folder."', '', '2', '1', 'file', '".USER_LEVEL_MEMBER."', '1', '1', '".fusion_get_settings('opening_page')."', '2', '".fusion_get_settings('enabled_languages')."')";

// Multilanguage links
$enabled_languages = makefilelist(LOCALE, ".|..", TRUE, "folders");
if (!empty($enabled_languages)) {
    foreach ($enabled_languages as $language) {
        include INFUSIONS."pm_control_panel/locale/".$language."/control.php";

        $mlt_adminpanel[$language][] = [
            'title'    => $locale['PMC_003'],
            'image'    => $inf_image,
            'panel'    => "admin.php",
            'rights'   => "PMC",
            'page'     => 5,
            'language' => $language
        ];

        // Delete
        $mlt_deldbrow[$language][] = DB_ADMIN." WHERE admin_rights='PMC' AND admin_language='".$language."'";
    }
} else {
    $inf_adminpanel[] = [
        'title'    => $locale['PMC_003'],
        'image'    => $inf_image,
        'panel'    => "admin.php",
        'rights'   => "PMC",
        'page'     => 5,
        'language' => LANGUAGE
    ];
}

$inf_deldbrow[] = DB_PANELS." WHERE panel_filename='".$inf_folder."'";
$inf_deldbrow[] = DB_SETTINGS_INF." WHERE settings_inf='".$inf_folder."'";
$inf_deldbrow[] = DB_ADMIN." WHERE admin_rights='PMC'";
$inf_deldbrow[] = DB_LANGUAGE_TABLES." WHERE mlt_rights='PMC'";
