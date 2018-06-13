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

require_once dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'mainfile.php';
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'include'. DIRECTORY_SEPARATOR . 'functions.php';
require_once dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . 'class'. DIRECTORY_SEPARATOR . 'cache'. DIRECTORY_SEPARATOR . 'xoopscache.php';

$criteria = new Criteria('1', '1', "=");
$repositories_handler = xoops_getModuleHandler('repositories', basename(dirname(__DIR__)));
$repositories = $repositories_handler->getObjects($criteria);
foreach($repositories as $key => $repository)
{
    if (!$read = XoopsCache::read('sha1bytes_'.md5($repository->getVar('id'))))
    {
        $pass = true;
    } elseif( $read['time']>time() ) {
        $pass = true;
    } else {
        $pass = false;
    }
    if ($pass != false) {
        XoopsCache::write('sha1bytes_'.md5($repository->getVar('id')), $read = array('time'=>time() + (60 * 17)));
            
        $GLOBALS['xoopsDB']->queryF("START TRANSACTION");
        // Albums
        $tracks_handler = xoops_getModuleHandler('tracks', basename(dirname(__DIR__)));
        $criteria = new CriteriaCompo(new Criteria('sha1', "", "LIKE"), 'OR');
        $criteria->add(new Criteria('bytes', '0'), "OR");
        $criteria->add(new CriteriaCompo(new Criteria('repoid', $repository->getVar('id')), "AND"), "AND");
        $criteria->setSort("RAND()");
        $criteria->setLimit(15);
    
        foreach($tracks_handler->getObjects($criteria, true) as $key => $track)
        {
            $data = eightbit_getURIData(sprintf($repository->getVar('raw'), substr($track->getVar('path'), 1) . "/" . urlencode($track->getVar('file'))), 360, 360);
            if (strpos(strtolower($data), '404') > 0 || strpos(strtolower($data), 'whoops') > 0 || strpos(strtolower($data), 'not found') > 0)
            {
                $track->setVar('mode', 'offline');
                $tracks_handler->insert($track, true);
            } else {
                $track->setVar('sha1', sha1($data));
                $track->setVar('bytes', strlen($data));
                $track->setVar('mode', 'online');
                $tracks_handler->insert($track, true);
            }
        }
        $GLOBALS['xoopsDB']->queryF("COMMIT");
    }
}   
?>