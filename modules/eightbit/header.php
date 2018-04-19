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


require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'mainfile.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'functions.php';

$myts =& MyTextSanitizer::getInstance();

global $eightbitModule, $eightbitConfigsList, $eightbitConfigs, $eightbitConfigsOptions;

if (empty($eightbitModule))
{
    if (is_a($eightbitModule = xoops_getHandler('module')->getByDirname(basename(__DIR__)), "XoopsModule"))
    {
        if (empty($eightbitConfigsList))
        {
            $eightbitConfigsList = eightbit_loadConfig();
        }
        if (empty($eightbitConfigs))
        {
            $eightbitConfigs = xoops_getHandler('config')->getConfigs(new Criteria('conf_modid', $eightbitModule->getVar('mid')));
        }
        if (empty($eightbitConfigsOptions) && !empty($eightbitConfigs))
        {
            foreach($eightbitConfigs as $key => $config)
                $eightbitConfigsOptions[$config->getVar('conf_name')] = $config->getConfOptions();
        }
    }
}

global $artistalpha, $albumalpha, $trackalpha, $artistid, $albumid, $trackid, $start, $sort, $order, $mode, $op;

$op   	         = empty($_GET["op"]) ? 'default' : $_GET["op"];
$artistalpha	 = intval( empty($_GET["artistalpha"]) ? md5(null) : $_GET["artistalpha"] );
$albumalpha  	 = intval( empty($_GET["albumalpha"]) ? md5(null) : $_GET["albumalpha"] );
$trackalpha  	 = intval( empty($_GET["trackalpha"]) ? md5(null) : $_GET["trackalpha"] );
$artistid	 	 = intval( empty($_GET["artistid"]) ? md5(null) : $_GET["artistid"] );
$albumid  	     = intval( empty($_GET["albumid"]) ? md5(null) : $_GET["albumid"] );
$trackid  	     = intval( empty($_GET["trackid"]) ? md5(null) : $_GET["trackid"] );
$start  	     = intval( empty($_GET["start"]) ? 0 : $_GET["start"] );
$limit  	     = intval( empty($_GET["limit"]) ? $eightbitConfigsList['limit'] : $_GET["limit"] );
$sort   	     = empty($_GET["sort"]) ? "hits" : $_GET["sort"] ;
$order  	     = empty($_GET["order"]) ? "DESC" : $_GET["order"] ;
$mode   	     = empty($_GET["mode"]) ? "list" : (in_array($_GET["mode"], array('list','cloud'))? $_GET['mode'] : 'cloud') ;
