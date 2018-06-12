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
 * EightBit Alpha Class
 *
 * @package             eightbit
 *
 * @author              Simon Antony Roberts <wishcraft@users.sourceforge.net>
 */
class EightbitAlpha_albums extends XoopsObject
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->initVar('id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('alphaid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('albumid', XOBJ_DTYPE_INT, null, false);
    }
}

/**
 * Class 8BitAlphaHandler
 */
class EightbitAlpha_albumsHandler extends XoopsPersistableObjectHandler
{
    /**
     * @param null|XoopsDatabase $db
     */
    public function __construct(XoopsDatabase $db)
    {
        parent::__construct($db, '8bit_alpha_albums', 'EightbitAlpha_albums', 'id', 'albumid');
    }
    
    public function selAlpha($alpha = '')
    {
        return xoops_getModuleHandler('alpha', 'eightbit')->selAlpha($alpha, 'album');   
    }
    
    public function getIDsAlpha($alpha = '')
    {
        return xoops_getModuleHandler('alpha', 'eightbit')->getIDsAlpha($alpha, 'album');
    }
}
