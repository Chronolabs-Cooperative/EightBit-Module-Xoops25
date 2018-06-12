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


require_once __DIR__ . DIRECTORY_SEPARATOR . 'header.php';

$xoopsOption['template_main'] = 'db:eightbit_track.html';
include_once dirname(dirname(__DIR__)) . DS . 'header.php';
include_once dirname(dirname(__DIR__)) . DS . 'class' . DS . 'pagenav.php';

if (!$track = xoops_getModuleHandler('tracks')->getByKey($_REQUEST['key']))
{
    redirect_header(XOOPS_URL . '/modules/' . basename(__DIR__) . '/index.php', 7, 'No track Found for Key!');
    exit(0);
}

$track->setVar('hits', $track->getVar('hits')+1);
xoops_getModuleHandler('tracks')->insert($track);

$breadcrumb = array();
$breadcrumb['home']['url'] = XOOPS_URL . '/modules/' . basename(__DIR__) . "/index.php";
$breadcrumb['home']['chars'] = 'home';
foreach(xoops_getModuleHandler('tracks', basename(__DIR__))->getCrumbs(xoops_getModuleHandler('alpha')->get($track->getVar('alphaid'))->getVar('charley')) as $chars => $values)
{
    $breadcrumb[$chars]['chars'] = $chars;
    $breadcrumb[$chars]['url'] = XOOPS_URL . '/modules/' . basename(__DIR__) . "/tracks.php?trackalpha=" . $chars;
}
$breadcrumb[$_REQUEST['key']]['url'] = XOOPS_URL . '/modules/' . basename(__DIR__) . "/track.php?key=".$_REQUEST['key'];
$breadcrumb[$_REQUEST['key']]['chars'] = $track->getVar('title');
$xoopsOption['xoops_pagetitle'] = "Track: " .$track->getVar('title');

$crumbkeys = array_keys($breadcrumb);
$GLOBALS['xoopsTpl']->assign('breadcrumb', $breadcrumb);
$GLOBALS['xoopsTpl']->assign('lastcrumb', $crumbkeys[count($crumbkeys) - 1]);
$GLOBALS['xoopsTpl']->assign('track', array(   'title'         =>      $track->getVar('title'),
    'album'         =>      xoops_getModuleHandler('albums', basename(__DIR__))->get($track->getVar('albumid'))->getVar('album'),
    'artist'        =>      eightbit_getArtistsHTML($track->getVar('artistid'), xoops_getModuleHandler('artists', basename(__DIR__))->get($track->getVar('artistid'))->getVar('artist')),
    'album_url'     =>      XOOPS_URL . '/modules/' . basename(__DIR__) . '/album.php?key='.md5($track->getVar('albumid')),
    'track_url'     =>      XOOPS_URL . '/modules/' . basename(__DIR__) . '/track.php?key='.md5($track->getVar('trackid')),
    'year'          =>      $track->getVar('year'),
    'bitrate'       =>      number_format($track->getVar('bitrate') / 1024, 0) . 'Kbs',
    'bytes'         =>      number_format($track->getVar('bytes'), 0),
    'hits'          =>      $track->getVar('hits'),
    'player'        =>      eightbit_PlayerHTML('player.swf', sprintf(xoops_getModuleHandler('repositories', basename(__DIR__))->get($track->getVar('repoid'))->getVar('raw'), substr($track->getVar('path'), 1) . "/" . urlencode($track->getVar('file')))),
    'playtime'      =>      eightbit_secondsDiplay($track->getVar('playseconds'))));

include_once dirname(dirname(__DIR__)) . DS . 'footer.php';