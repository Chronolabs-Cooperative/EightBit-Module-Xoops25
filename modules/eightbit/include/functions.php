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

/**
 * validateMD5()
 * Validates an MD5 Checksum
 *
 * @param string $email
 * @return boolean
 */
function eightbit_validateSHA1($sha1) {
    if(preg_match("/^[a-f0-9]{40}$/i", $sha1)) {
        return true;
    } else {
        return false;
    }
}

/**
 * validateMD5()
 * Validates an MD5 Checksum
 *
 * @param string $email
 * @return boolean
 */
function eightbit_validateMD5($md5) {
    if(preg_match("/^[a-f0-9]{32}$/i", $md5)) {
        return true;
    } else {
        return false;
    }
}

/**
 * validateEmail()
 * Validates an Email Address
 *
 * @param string $email
 * @return boolean
 */
function eightbit_validateEmail($email) {
    if(preg_match("^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.([0-9]{1,3})|([a-zA-Z]{2,3})|(aero|coop|info|mobi|asia|museum|name))$", $email)) {
        return true;
    } else {
        return false;
    }
}

/**
 * validateDomain()
 * Validates a Domain Name
 *
 * @param string $domain
 * @return boolean
 */
function eightbit_validateDomain($domain) {
    if(!preg_match("/^([-a-z0-9]{2,100})\.([a-z\.]{2,8})$/i", $domain)) {
        return false;
    }
    return $domain;
}

/**
 * validateIPv4()
 * Validates and IPv6 Address
 *
 * @param string $ip
 * @return boolean
 */
function eightbit_validateIPv4($ip) {
    if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE) === FALSE) // returns IP is valid
    {
        return false;
    } else {
        return true;
    }
}

/**
 * validateIPv6()
 * Validates and IPv6 Address
 *
 * @param string $ip
 * @return boolean
 */
function eightbit_validateIPv6($ip) {
    if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === FALSE) // returns IP is valid
    {
        return false;
    } else {
        return true;
    }
}

if (!function_exists("eightbit_getArtistsHTML")) {
    
    /* function eightbit_getURIData()
     *
     * 	Get a supporting domain system for the API
     * @author 		Simon Roberts (Chronolabs) simon@labs.coop
     *
     * @return 		float()
     */
    function eightbit_getArtistsHTML($id = 0, $artist = '')
    {
        if (eightbit_validateSHA1($artist))
        {
            $return = array();
            $criteria = new Criteria('artistid', $id);
            foreach(xoops_getModuleHandler('artists_chaining')->getObjects($criteria, true) as $key => $object)
                $return[] = "<a href='" . XOOPS_URL . "/modules/" . basename(dirname(__DIR__)) . "/artist.php?key=" . md5($object->getVar('childid')) . "'>".xoops_getModuleHandler('artists')->get($object->getVar('childid'))->getVar('artist')."</a>";
            if (count($return) > 4) {
                while(count($return) > 4)
                {
                    sort($return);
                    unset($return[mt_rand(0, count($return) - 1)]);
                }
                $return[] = '...';
            }
            return implode(',&nbsp;', $return);
        } else {
            return "<a href='" . XOOPS_URL . "/modules/" . basename(dirname(__DIR__)) . "/artist.php?key=" . md5($id) . "'>$artist</a>";
        }
    }
}

if (!function_exists("eightbit_PlayerHTML")) {
    
    /* function eightbit_getURIData()
     *
     * 	Get a supporting domain system for the API
     * @author 		Simon Roberts (Chronolabs) simon@labs.coop
     *
     * @return 		float()
     */
    function eightbit_PlayerHTML($player = '', $url = '', $width = 240, $height = 24)
    {
        return "<embed flashvars=\"playerID=1&amp;bg=0xf8f8f8&amp;leftbg=0x3786b3&amp;lefticon=0x78bee3&amp;rightbg=0x3786b3&amp;rightbghover=0x78bee3&amp;righticon=0x78bee3&amp;righticonhover=0x3786b3&amp;text=0x666666&amp;slider=0x3786b3&amp;track=0xcccccc&amp;border=0x666666&amp;loader=0x78bee3&amp;loop=no&amp;soundFile=$url\" quality='high' menu='false' wmode='transparent' pluginspage='http://www.macromedia.com/go/getflashplayer' src='" . XOOPS_URL . "/modules/".basename(dirname(__DIR__))."/assets/swf/$player'  width=$width height=$height type='application/x-shockwave-flash'></embed>";
    }
}

if (!function_exists("eightbit_RemoveFieldKeywords")) {
    
    /* function eightbit_getURIData()
     *
     * 	Get a supporting domain system for the API
     * @author 		Simon Roberts (Chronolabs) simon@labs.coop
     *
     * @return 		float()
     */
    function eightbit_RemoveFieldKeywords($keyword = '', $array = array())
    {
        foreach($array as $key => $values)
            if (strpos(strtolower(' '. $key), strtolower($keyword)))
                unset($array[$key]);
       return $array;
    }
}

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