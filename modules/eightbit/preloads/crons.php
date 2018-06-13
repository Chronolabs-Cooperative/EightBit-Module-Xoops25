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

class EightbitCronsPreload extends XoopsPreloadItem
{
    
    /**
     * @param $args
     */
    public static function eventCoreFooterEnd($args)
    {
        include_once dirname(__DIR__) . DS . 'crons' . DS . 'crawl.repositories.php';
        include_once dirname(__DIR__) . DS . 'crons' . DS . 'crawl.sha1bytes.php';
        include_once dirname(__DIR__) . DS . 'crons' . DS . 'crawl.sha1matches.php';
    }
    
}
