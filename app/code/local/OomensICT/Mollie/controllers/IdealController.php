<?php
/**
 * @category   OomensICT
 * @package    OomensICT_Mollie
 * @copyright  Copyright (c) 2009 Oomens ICT
 */

/**
 * Mollie iDEAL Advanced Checkout Controller
 *
 * @category    OomensICT
 * @package     OomensICT_Mollie
 * @author      Victor Oomens
 */
 
class OomensICT_Mollie_IdealController extends Mage_Core_Controller_Front_Action
{
    public function getCheckout()
    {
        return Mage::getSingleton('checkout/session');
    }

    public function getIdeal()
    {
        return Mage::getSingleton('mollie/ideal');
    }

    /**
     * When a customer chooses Mollie iDEAL on Checkout/Payment page
     *
     */
    public function redirectAction()
    {
        $session = Mage::getSingleton('checkout/session');
        $order = Mage::getModel('sales/order');
        $order->load($this->getCheckout()->getLastOrderId());
        if($order->getId()){
            $payment = $order->getPayment()->getMethodInstance();
            $quote = Mage::getSingleton('checkout/session')->getQuote();
            //$bankId = $quote->getPayment()->getMollieBankId();
            $bankId = $payment->getInfoInstance()->getAdditionalInformation('mollie_bank_id');
            
            $response = $payment->createPayment($order, $bankId);

            if ($response) {
				        $session->setMollieIdealQuoteId($session->getQuoteId());
                $order->setMollieTransactionId($response->getTransactionId());
                $order->setMolliePaidStatus(0);
                
                $bankList = $this->getIdeal()->getBanksList();
                $bank = $bankList[$response->getBankId()];
                $order->getPayment()->setMollieBankTitle($bank);
                $order->getPayment()->setMollieBankId($bankId);
                
                /*
                $order->addStatusToHistory(
                    $order->getStatus(),
                    $this->__('Customer was redirected to Mollie iDEAL')
                );
                */
                $order->save();

                $this->getResponse()->setBody(
                    $this->getLayout()->createBlock('mollie/ideal_redirect')
                        ->setMessage($this->__('You will be redirected to your bank in a few seconds.'))
                        ->setRedirectUrl($response->getBankURL())
                        ->toHtml()
                );

				        $session->unsQuoteId();
                return;
            }
        }

        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('mollie/ideal_redirect')
                ->setMessage($this->__('Error occured. You will be redirected back to the store.'))
                ->setRedirectUrl(Mage::getUrl('checkout/cart'))
                ->toHtml()
        );
    }

    /**
     * When customer return from iDEAL
     */
    public function returnAction()
    {
    		// Check order status!!!
    		$transactionId = $this->getRequest()->get('transaction_id');
    		
        $order = Mage::getModel('sales/order');
        $order->loadByAttribute('mollie_transaction_id', $transactionId);
        if ($order->getMolliePaidStatus() == true) {
            // SUCCESS
            
		        $session = Mage::getSingleton('checkout/session');
		        $session->setQuoteId($session->getMollieIdealQuoteId(true));
		        /**
		         * set the quote as inactive after back from Mollie
		         */
		        Mage::getSingleton('checkout/session')->getQuote()->setIsActive(false)->save();
		
		        //Mage::getSingleton('checkout/session')->unsQuoteId();
		
		        $this->_redirect('checkout/onepage/success', array('_secure'=>true));
        } else {
            // NO Payment
		        $session = $this->getCheckout();
		        $session->setQuoteId($session->getMollieIdealQuoteId(true));
		
            $order->cancel()->save();
		
		        $this->_redirect('checkout/cart');
	      }
    }
    
    /**
     * Report from Mollie
     * cannot have any output here
     * validate report data
     * if data is valid need to update the database that the user has
     */
    public function reportAction()
    {
        $this->getIdeal()->checkPayment($this->getRequest()->get('transaction_id'));
    }
    
}
