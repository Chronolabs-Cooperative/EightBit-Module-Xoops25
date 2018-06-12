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
 * EightBit Artists Class
 *
 * @package             eightbit
 *
 * @author              Simon Antony Roberts <wishcraft@users.sourceforge.net>
 */
class EightbitArtists extends XoopsObject
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->initVar('id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('type', XOBJ_DTYPE_ENUM, 'alone', false, false, false, false, false, array('alone', 'chaining'));
        $this->initVar('artist', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('alphaid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('albums', XOBJ_DTYPE_INT, null, false);
        $this->initVar('tracks', XOBJ_DTYPE_INT, null, false);
        $this->initVar('bytes', XOBJ_DTYPE_INT, null, false);
        $this->initVar('hits', XOBJ_DTYPE_INT, null, false);
        $this->initVar('totalseconds', XOBJ_DTYPE_FLOAT, null, false);
    }
}

/**
 * Class 8BitArtistsHandler
 */
class EightbitArtistsHandler extends XoopsPersistableObjectHandler
{
    /**
     * @param null|XoopsDatabase $db
     */
    public function __construct(XoopsDatabase $db)
    {
        parent::__construct($db, '8bit_artists', 'EightbitArtists', 'id', 'artist');
    }
    
    public function selAlpha($alpha = '')
    {
        
        return xoops_getModuleHandler('alpha_artists', 'eightbit')->selAlpha($alpha);
    }
    
    public function getIDsAlpha($alpha = '')
    {
        
        return xoops_getModuleHandler('alpha_artists', 'eightbit')->getIDsAlpha($alpha);
    }
    
    public function getCrumbs($alpha = '')
    {
        
        return xoops_getModuleHandler('alpha_artists', 'eightbit')->getCrumbs($alpha);
    }
    
    public function getByKey($key = '')
    {
        $sql = "SELECT * FROM `" . $GLOBALS['xoopsDB']->prefix('8bit_artists') . "` WHERE md5(`id`) LIKE '$key'";
        if ($myrow = $GLOBALS['xoopsDB']->fetchArray($GLOBALS['xoopsDB']->queryF($sql)))
        {
            $obj = new EightbitArtists();
            $obj->assignVars($myrow);
            return $obj;
        }
        return false;
    }
}
