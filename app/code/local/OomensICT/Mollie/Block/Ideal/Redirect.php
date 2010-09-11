<?php
/**
 * @category   OomensICT
 * @package    OomensICT_Mollie
 * @copyright  Copyright (c) 2009 Oomens ICT
 */

/**
 * Mollie iDEAL Redirect Block for redirection to select bank page
 *
 * @category    OomensICT
 * @package     OomensICT_Mollie
 * @author      Victor Oomens
 */

class OomensICT_Mollie_Block_Ideal_Redirect extends Mage_Core_Block_Abstract
{
    protected function _toHtml()
    {
        $html = '<html><body>';
        $html.= $this->getMessage();
        $html.= '<script type="text/javascript">location.href = "' . $this->getRedirectUrl() . '";</script>';
        $html.= '</body></html>';
        return $html;
    }
}