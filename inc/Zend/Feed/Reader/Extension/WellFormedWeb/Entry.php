<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Feed_Reader
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Entry.php,v 1.1 2014/03/04 10:40:58 cvs Exp $
 */

/**
 * @see Zend_Feed_Reader
 */
require_once AUTO_LAST_YOUTUBE_VIDEO_PATH.'inc///Zend/Feed/Reader.php';

/**
 * @see Zend_Feed_Reader_Extension_EntryAbstract
 */
require_once AUTO_LAST_YOUTUBE_VIDEO_PATH.'inc///Zend/Feed/Reader/Extension/EntryAbstract.php';

/**
 * @category   Zend
 * @package    Zend_Feed_Reader
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Feed_Reader_Extension_WellFormedWeb_Entry
    extends Zend_Feed_Reader_Extension_EntryAbstract
{
    /**
     * Get the entry comment Uri
     *
     * @return string|null
     */
    public function getCommentFeedLink()
    {
        $name = 'commentRss';
        if (array_key_exists($name, $this->_data)) {
            return $this->_data[$name];
        }

        $data = $this->_xpath->evaluate('string(' . $this->getXpathPrefix() . '/wfw:' . $name . ')');

        if (!$data) {
            $data = null;
        }

        $this->_data[$name] = $data;

        return $data;
    }

    /**
     * Register Slash namespaces
     *
     * @return void
     */
    protected function _registerNamespaces()
    {
        $this->_xpath->registerNamespace('wfw', 'http://wellformedweb.org/CommentAPI/');
    }
}
