<?php
/**
 * 8Bit Collective Display and Email Packing
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       (c) 2000-2019 Chronolabs Cooperative (8Bit.snails.email)
 * @license             GNU GPL 2 (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package             eightbit
 * @since               1.1.11
 * @author              Simon Antony Roberts <wishcraft@users.sourceforge.net>
 */

if (!function_exists("eightbit_getURIData")) {
    
    /* function eightbit_getURIData()
     *
     * 	Get a supporting domain system for the API
     * @author 		Simon Roberts (Chronolabs) simon@labs.coop
     *
     * @return 		float()
     */
    function eightbit_getURIData($uri = '', $timeout = 25, $connectout = 25, $post = array(), $headers = array())
    {
        if (!function_exists("curl_init"))
        {
            die("Install PHP Curl Extension ie: $ sudo apt-get install php-curl -y");
        }
        $GLOBALS['php-curl'][md5($uri)] = array();
        if (!$btt = curl_init($uri)) {
            return false;
        }
        if (count($post)==0 || empty($post))
            curl_setopt($btt, CURLOPT_POST, false);
        else {
            $uploadfile = false;
            foreach($post as $field => $value)
                if (substr($value , 0, 1) == '@' && file_exists(substr($value , 1)))
                    $uploadfile = true;
            curl_setopt($btt, CURLOPT_POST, true);
            curl_setopt($btt, CURLOPT_POSTFIELDS, http_build_query($post));
            
            if (!empty($headers))
                foreach($headers as $key => $value)
                    if ($uploadfile==true && substr($value, 0, strlen('Content-Type:')) == 'Content-Type:')
                        unset($headers[$key]);
            if ($uploadfile==true)
                $headers[]  = 'Content-Type: multipart/form-data';
        }
        if (count($headers)==0 || empty($headers))
            curl_setopt($btt, CURLOPT_HEADER, false);
        else {
            curl_setopt($btt, CURLOPT_HEADER, $headers);
        }
        curl_setopt($btt, CURLOPT_CONNECTTIMEOUT, $connectout);
        curl_setopt($btt, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($btt, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($btt, CURLOPT_VERBOSE, false);
        curl_setopt($btt, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($btt, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($btt);
        $GLOBALS['php-curl'][md5($uri)]['http']['posts'] = $post;
        $GLOBALS['php-curl'][md5($uri)]['http']['headers'] = $headers;
        $GLOBALS['php-curl'][md5($uri)]['http']['code'] = curl_getinfo($btt, CURLINFO_HTTP_CODE);
        $GLOBALS['php-curl'][md5($uri)]['header']['size'] = curl_getinfo($btt, CURLINFO_HEADER_SIZE);
        $GLOBALS['php-curl'][md5($uri)]['header']['value'] = curl_getinfo($btt, CURLINFO_HEADER_OUT);
        $GLOBALS['php-curl'][md5($uri)]['size']['download'] = curl_getinfo($btt, CURLINFO_SIZE_DOWNLOAD);
        $GLOBALS['php-curl'][md5($uri)]['size']['upload'] = curl_getinfo($btt, CURLINFO_SIZE_UPLOAD);
        $GLOBALS['php-curl'][md5($uri)]['content']['length']['download'] = curl_getinfo($btt, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
        $GLOBALS['php-curl'][md5($uri)]['content']['length']['upload'] = curl_getinfo($btt, CURLINFO_CONTENT_LENGTH_UPLOAD);
        $GLOBALS['php-curl'][md5($uri)]['content']['type'] = curl_getinfo($btt, CURLINFO_CONTENT_TYPE);
        curl_close($btt);
        return $data;
    }
}


if (!function_exists("eightbit_splitArtists")) {
    
    /* function eightbit_getURIData()
     *
     * 	Get a supporting domain system for the API
     * @author 		Simon Roberts (Chronolabs) simon@labs.coop
     *
     * @return 		float()
     */
    function eightbit_splitArtists($artists = '')
    {
        $artists = str_replace('&', ',', $artists);
        $artists = str_replace('+', ',', $artists);
        $artists = str_replace('\\', ',', $artists);
        $artists = str_replace('/', ',', $artists);
        $artists = explode(',', $artists);
        sort($artists);
        foreach($artists as $key => $artist)
        {
            $artist = trim($artist);
            if (strlen($artist)==0)
                unset($artists[$key]);
            else
                $artists[$key] = ucwords(strtolower($artist));
        }
        $artists = array_unique($artists);
        sort($artists);
        return $artists;
    }
}


if (!function_exists("eightbit_loadConfig")) {
    
    /* function eightbit_loadConfig()
     *
     * Loads XOOPS Module Configurations
     * 
     * @author 		Simon Roberts (Chronolabs) simon@labs.coop
     *
     * @return 		array()
     */
    function eightbit_loadConfig()
    {
        global $xoopsModuleConfig;
        static $moduleConfig;
        
        if (isset($moduleConfig)) {
            return $moduleConfig;
        }
        
        if (isset($GLOBALS["xoopsModule"]) && is_object($GLOBALS["xoopsModule"]) && $GLOBALS["xoopsModule"]->getVar("dirname", "n") == "eightbit") {
            if (!empty($GLOBALS["xoopsModuleConfig"])) {
                $moduleConfig = $GLOBALS["xoopsModuleConfig"];
            } else {
                return null;
            }
        } else {
            $module_handler =& xoops_gethandler('module');
            $module = $module_handler->getByDirname("eightbit");
            
            $config_handler =& xoops_gethandler('config');
            $criteria = new CriteriaCompo(new Criteria('conf_modid', $module->getVar('mid')));
            $configs = $config_handler->getConfigs($criteria);
            foreach (array_keys($configs) as $i) {
                $moduleConfig[$configs[$i]->getVar('conf_name')] = $configs[$i]->getConfValueForOutput();
            }
            unset($configs);
        }
        if ($customConfig = @include XOOPS_ROOT_PATH . "/modules/eightbit/include/plugin.php") {
            $moduleConfig = array_merge($moduleConfig, $customConfig);
        }
        
        return $moduleConfig;
    }
}


if (!function_exists("eightbit_secondsDiplay")) {
    
    /* function eightbit_loadConfig()
     *
     * Converts Seconds to Staggered United Display
     *
     * @author 		Simon Roberts (Chronolabs) simon@labs.coop
     *
     * @return 		array()
     */
    function eightbit_secondsDiplay($playseconds = 0)
    {
        $result = array();
        $months = 3600 * 24 * 7 * 4;
        $weeks = 3600 * 24 * 7;
        $days = 3600 * 24;
        $hours = 3600;
        $minutes = 3600 / 60;
        $seconds = 60;
        if (floor($playseconds / $months) != 0) {
            $result[] = floor($playseconds / $months) . 'mth';
            $playseconds = $playseconds - (floor($playseconds / $months) * $months);
        }
        if (floor($playseconds / $weeks) != 0) {
            $result[] = floor($playseconds / $weeks) . 'wk';
            $playseconds = $playseconds - (floor($playseconds / $weeks) * $weeks);
        }
        if (floor($playseconds / $days) != 0) {
            $result[] = floor($playseconds / $days) . 'd';
            $playseconds = $playseconds - (floor($playseconds / $days) * $days);
        }
        if (floor($playseconds / $hours) != 0) {
            $result[] = floor($playseconds / $hours) . 'h';
            $playseconds = $playseconds - (floor($playseconds / $hours) * $hours);
        }
        if (floor($playseconds / $minutes) != 0) {
            $result[] = floor($playseconds / $minutes) . 'm';
            $playseconds = $playseconds - (floor($playseconds / $minutes) * $minutes);
        }
        if (floor($playseconds) != 0) {
            $result[] = floor($playseconds) . 's';
            $playseconds = $playseconds - (floor($playseconds));
        }
        return implode(" ", $result);
    }
}