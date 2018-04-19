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
 * EightBit Hashings Class
 *
 * @package             eightbit
 *
 * @author              Simon Antony Roberts <wishcraft@users.sourceforge.net>
 */
class EightbitHashing extends XoopsObject
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->initVar('id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('type', XOBJ_DTYPE_ENUM, '8bit', false, false, false, false, array('track', 'artist', 'album', 'repository', '8bit'));
        $this->initVar('repoid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('itemid', XOBJ_DTYPE_INT, null, false);
        $this->initVar('hash', XOBJ_DTYPE_TXTBOX, null, false, 255);
    }
}

/**
 * Class 8BitHashingsHandler
 */
class EightbitHashingHandler extends XoopsPersistableObjectHandler
{
    /**
     * @param null|XoopsDatabase $db
     */
    public function __construct(XoopsDatabase $db)
    {
        parent::__construct($db, '8bit_hashing', 'EightbitHashing', 'id', 'hash');
    }
}
