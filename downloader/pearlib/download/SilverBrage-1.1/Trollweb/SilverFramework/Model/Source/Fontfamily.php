<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Mage
 * @package    Mage_Paygate
 * @copyright  Copyright (c) 2004-2007 Irubin Consulting Inc. DBA Varien (http://www.varien.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Trollweb_SilverFramework_Model_Source_Fontfamily
{
    public function toOptionArray()
    {
        $options = array(
            array('label'=>'Arial/Helvetica', 'value'=>'arial'),
            array('label'=>'Arial Black/Gadget', 'value'=>'arial-black'),
            array('label'=>'Comic Sans MS', 'value'=>'comic-sans-ms'),
            array('label'=>'Courier New', 'value'=>'courier-new'),
            array('label'=>'Georgia', 'value'=>'georgia'),
            array('label'=>'Impact/Charcoal', 'value'=>'impact'),
            array('label'=>'Lucida Console/Monaco', 'value'=>'lucida-console'),
            array('label'=>'Lucida Sans Unicode/Lucida Grande', 'value'=>'lucida-sans-unicode'),
            array('label'=>'Palatino Linotype/Book Antiqua', 'value'=>'palatino-linotype'),
            array('label'=>'Tahoma/Geneva', 'value'=>'tahoma'),
            array('label'=>'Times New Roman', 'value'=>'times-new-roman'),
            array('label'=>'Trebuchet MS/Helvetica', 'value'=>'font-trebuchet-ms'),
            array('label'=>'Verdana', 'value'=>'verdana'),
            array('label'=>'MS Sans Serif/Geneva', 'value'=>'ms-sans-serif'),
            array('label'=>'MS Serif/New York', 'value'=>'font-ms-serif'),
        );

        return $options;
    }
}