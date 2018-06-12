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

if (!$read = XoopsCache::read('sha1matches'))
{
    $pass = true;
} elseif( $read['time']>time() ) {
    $pass = true;
} else {
    $pass = false;
}
if ($pass != false) {
    XoopsCache::write('sha1matches', $read = array('time'=>time() + (60 * 11)));
        
    $GLOBALS['xoopsDB']->queryF("START TRANSACTION");
    // Albums
    $tracks_handler = xoops_getModuleHandler('tracks', basename(dirname(__DIR__)));
    $criteria = new CriteriaCompo(new Criteria('sha1', "", "NOT LIKE"), 'AND');
    $criteria->add(new Criteria('mode', 'online'), "AND");
    $criteria->setSort("RAND()");
    $criteria->setLimit(15);
    if ($tracks_handler->getCount($criteria) > 0)
    {
        
        foreach($tracks_handler->getObjects($criteria, true) as $key => $track)
        {
            $criteriab = new CriteriaCompo(new Criteria('sha1', $track->getVar('sha1'), "LIKE"), 'AND');
            $criteriab->add(new Criteria('id', $track->getVar('id'), '!='), "AND");
            $criteriab->add(new CriteriaCompo(new Criteria('mode', 'online'), "AND"), "AND");
            $criteriab->setSort("RAND()");
            foreach($tracks_handler->getObjects($criteriab, true) as $key => $trackb)
            {
                $trackb->setVar('mode', 'offline');
                $tracks_handler->insert($trackb, true);
            } 
        }
    }
    $GLOBALS['xoopsDB']->queryF("COMMIT");
}
   
?>