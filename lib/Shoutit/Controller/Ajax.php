<?php
/**
 * Shoutit module for Zikula Application Framework
 *
 * @author       Gabriel Freinbichler
 *              refactored for zk 1.3 by Philippe Baudrion - UniGE/FTI
 * @link         http://www.cmods-dev.de
 * @copyright    Copyright (C) by Gabriel Freinbichler
 * @license      http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @version      $Id$
 */
class Shoutit_Controller_Ajax extends Zikula_Controller_AbstractAjax
{
    public function getmessages()
    {
        $html = '';
        $bid  = (int)$this->request->getPost()->get('bid');

        if (empty($bid)) {
            throw new Zikula_Exception_Fatal($this->__('Ajax Error! Missing required parameter.'));
        }

        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Shoutit::', $bid.'::', ACCESS_OVERVIEW), LogUtil::getErrorMsgPermission());

        $messages = ModUtil::apiFunc('Shoutit', 'user', 'getMessages', array('bid' => $bid));

        Zikula_AbstractController::configureView();
        $this->view->setCaching(Zikula_View::CACHE_DISABLED);

        $this->view->assign('messages', $messages);
        
        if ($this->view->template_exists("user/messages_{$bid}.tpl")) {
            $html = $this->view->fetch("user/messages_{$bid}.tpl");
        } else {
            $html = $this->view->fetch('user/messages.tpl');
        }

        echo $html;
        System::shutDown();
    }

    public function savemessages()
    {
        $this->checkAjaxToken();

        $bid        = (int)$this->request->getPost()->get('bid');
        $message    = $this->request->getPost()->get('message');
        $gid        = $this->request->getPost()->get('gid');        // $gid can be empty, do not force type
        
        if (empty($bid) ||
            empty($message)) {
            throw new Zikula_Exception_Fatal($this->__('Ajax Error! Missing required parameter.'));
        }

        $this->throwForbiddenUnless(SecurityUtil::checkPermission('Shoutit::', $bid.'::', ACCESS_COMMENT), LogUtil::getErrorMsgPermission());

        $result = ModUtil::apiFunc('Shoutit', 'user', 'saveMessages', array(
            'message' => $message,
            'gid' => $gid,
            'bid' => $bid
            ));

        return array('result' => $result);
    }
// end of class
}
?>
