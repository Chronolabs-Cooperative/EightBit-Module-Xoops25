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
    if (is_a($eightbitModule = xoops_GetHandler('module')->getByDirname(basename(__DIR__)), "XoopsModule"))
    {
        if (empty($eightbitConfigsList))
        {
            $eightbitConfigsList = eightbit_loadConfig();
        }
        if (empty($eightbitConfigs))
        {
            $eightbitConfigs = xoops_GetHandler('config')->getConfigs(new Criteria('conf_modid', $eightbitModule->getVar('mid')));
        }
        if (empty($eightbitConfigsOptions) && !empty($eightbitConfigs))
        {
            foreach($eightbitConfigs as $key => $config)
                $eightbitConfigsOptions[$config->getVar('conf_name')] = $config->getConfOptions();
        }
    }
}

global $artistalpha, $albumalpha, $trackalpha, $artistid, $albumid, $trackid, $start, $limit, $sort, $order, $mode, $op;

$op   	         = empty($_REQUEST["op"]) ? 'default' : $_REQUEST["op"];
$artistalpha	 = intval( empty($_REQUEST["artistalpha"]) ? '' : $_REQUEST["artistalpha"] );
$albumalpha  	 = intval( empty($_REQUEST["albumalpha"]) ? '' : $_REQUEST["albumalpha"] );
$trackalpha  	 = intval( empty($_REQUEST["trackalpha"]) ? '' : $_REQUEST["trackalpha"] );
$artistid	 	 = intval( empty($_REQUEST["artistid"]) ? md5(null) : $_REQUEST["artistid"] );
$albumid  	     = intval( empty($_REQUEST["albumid"]) ? md5(null) : $_REQUEST["albumid"] );
$trackid  	     = intval( empty($_REQUEST["trackid"]) ? md5(null) : $_REQUEST["trackid"] );
$start  	     = intval( empty($_REQUEST["start"]) ? 0 : $_REQUEST["start"] );
$limit  	     = intval( empty($_REQUEST["limit"]) ? 13 : $_REQUEST["limit"] );
$sort   	     = empty($_REQUEST["sort"]) ? "hits" : $_REQUEST["sort"] ;
$order  	     = empty($_REQUEST["order"]) ? "DESC" : $_REQUEST["order"] ;
$mode   	     = empty($_REQUEST["mode"]) ? "list" : (in_array($_REQUEST["mode"], array('list','cloud'))? $_REQUEST['mode'] : 'cloud') ;

error_reporting(E_ALL);
ini_set('display_errors', true);
