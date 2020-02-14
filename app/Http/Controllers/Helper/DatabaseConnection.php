<?php

namespace App\Http\Controllers\Helper;

use App\Site;
use App\Abre_Tenant_Map;
use App\Parent_Student;
use App\Users_Parent;

use App\Http\Controllers\Helper\DatabaseSettings;

use DB;
use App\Http\Controllers\Config;

use JWTAuth;

class DatabaseConnection
{

    public static $site_domain = "";
    public static $site_id = "";

    /*
        Set database to $site
    */
    public static function setConnection($site)
    {
        // Read domain from abre_tenant_map
        self::$site_id = $site;
        self::$site_domain = self::getConfigSiteGafeDomain();

        /*
        $collection = Site::where('id', $site)->get()->first();

        self::$site_domain = $collection->domain;

        config(['database.connections.mysql' => [
            'driver' => $collection->driver,
            'host' => $collection->host,
            'database' => $collection->database,
            'username' => $collection->username,
            'port' => $collection->port,
            'password' => $collection->password
        ]]);

        DB::purge('mysql');
        DB::reconnect('mysql');
        */
    }

    /*
        Validates authenticated user from token against user table
        Also, returns User object from user table
    */
    public static function validateUser() 
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                $response = [
                    'status' => 'Error',
                    'result' => 'User not found'
                ];
                return response()->json($response, 404);
            }
        } catch (Exception $e) {
            $response = [
                'status' => 'Error',
                'result' => 'Access error'
            ];
            return response()->json($response, 400);
        }

        $response = [
            'status' => 'Success',
            'result' => $user
        ];

        return $response;
    }

    private static function loadConfigData($siteID) {

        $options = Abre_Tenant_Map::where('siteID', $siteID)->get();

        if (count($options) == 0) return null;

        $options = $options[0];
        $options = $options['config_settings'];

		return isset($options) && $options != "" ? json_decode($options) : null;
	}

    private static function getConfigSettings($siteID, $value) {
        $configData = self::loadConfigData($siteID);
		return $configData != null ? $configData->$value : null;
	}

    public static function getConfigSiteGafeDomain() {
		return self::getConfigSettings(self::$site_id, "SITE_GAFE_DOMAIN");
	}

    public static function getUserType($domain, $email) 
    {
        $usertype = "";

        $studentdomain = DatabaseSettings::getSiteStudentDomain();
        $studentdomainrequired = DatabaseSettings::getSiteStudentDomainRequired();

        if ($studentdomain == NULL) { $studentdomain = $domain; }

		$userdomain = substr($email, strpos($email, '@'));
        $username = substr($email, 0, strpos($email, '@'));
              
        if($domain == $studentdomain) {

            //error_log("EQUAL ".$username." ".$studentdomainrequired." ".$domain);
            //Check for required chracters (if any)
            if(strcspn($username, $studentdomainrequired) != strlen($username)) {
                $usertype = "student";
                //error_log("STUDENT");
            } 
            else if (strpos($domain, $userdomain) !== false || strpos($userdomain, $domain) !== false) {
                $usertype = "staff";
                //error_log("STAFF");
            }
        }
        else {
            
            if($studentdomainrequired == "" && (strpos($email, $studentdomain) !== false)){
                $usertype = "student";                
            }
            else {

                if ((strpos($email, $studentdomain) !== false) && 
                    strcspn($username, $studentdomainrequired) != strlen($username)) {
                    $usertype = "student";
                }
                else if (strpos($domain, $userdomain) !== false) {
                    $usertype = "staff";
                }
            }
        }       
        
        //error_log($usertype);

        return $usertype;

        // Get domain from key
        //$domain = JWTAuth::parseToken()->getPayload()->get('domain');
        //$domain = JWTAuth::getPayload()->get('domain');
    }
}


