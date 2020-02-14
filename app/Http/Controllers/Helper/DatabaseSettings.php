<?php

namespace App\Http\Controllers\Helper;

use App\Setting;

use App\Http\Controllers\Helper\DatabaseConnection;

use App\Http\Controllers\Config;

class DatabaseSettings
{

    public static function getSiteStudentDomain(){
		return self::getSettingsDbValue('studentdomain');
    }

    public static function getSiteStudentDomainRequired(){
		return self::getSettingsDbValue('studentdomainrequired');
	}

    
	private static function getSettingsDbValue($value) {
		$settingsData = self::loadSettingsDbValue();
		if(!$settingsData) return null;
		return $settingsData->$value;
	}

	private static function loadSettingsDbValue() {

        $options = Setting::where('siteID', DatabaseConnection::$site_id)->get();

        if (count($options) == 0) return null;

        $options = $options[0];
        $options = $options['options'];

        return isset($options) && $options != "" ? json_decode($options) : null;

	}
}