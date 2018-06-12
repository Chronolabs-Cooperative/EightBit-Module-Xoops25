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

$xoopsOption['template_main'] = 'db:eightbit_index.html';
include_once dirname(dirname(__DIR__)) . DS . 'header.php';
$totalseconds = 0;
$alpha = array();
foreach(xoops_getModuleHandler('albums', basename(__DIR__))->selAlpha('') as $chars => $values)
{
    $alpha[$chars]['chars'] = $chars;
    $alpha[$chars]['url'] = XOOPS_URL . '/modules/' . basename(__DIR__) . "/albums.php?albumalpha=" . $chars;
    $alpha[$chars]['tracks'] = eightbit_secondsDiplay($values['totalseconds']);

}
$GLOBALS['xoopsTpl']->assign('albumsalpha', $alpha);


$alpha = array();
foreach(xoops_getModuleHandler('artists', basename(__DIR__))->selAlpha('') as $chars => $values)
{
    $alpha[$chars]['chars'] = $chars;
    $alpha[$chars]['url'] = XOOPS_URL . '/modules/' . basename(__DIR__) . "/artists.php?artistalpha=" . $chars;
    $alpha[$chars]['tracks'] = eightbit_secondsDiplay($values['totalseconds']);
    $totalseconds += $values['totalseconds'];
}
$GLOBALS['xoopsTpl']->assign('artistsalpha', $alpha);
$GLOBALS['xoopsTpl']->assign('totalplaytime', eightbit_secondsDiplay($totalseconds));

$alpha = array();
foreach(xoops_getModuleHandler('tracks', basename(__DIR__))->selAlpha('') as $chars => $values)
{
    $alpha[$chars]['chars'] = $chars;
    $alpha[$chars]['url'] = XOOPS_URL . '/modules/' . basename(__DIR__) . "/tracks.php?trackalpha=" . $chars;
    $alpha[$chars]['tracks'] = eightbit_secondsDiplay($values['totalseconds']);
}
$GLOBALS['xoopsTpl']->assign('tracksalpha', $alpha);


include_once dirname(dirname(__DIR__)) . DS . 'footer.php';
