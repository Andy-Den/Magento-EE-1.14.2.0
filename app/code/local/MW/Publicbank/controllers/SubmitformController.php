<?php
class MW_Publicbank_SubmitformController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
    {
        if(Mage::getStoreConfig('payment/publicbank_standard/is_test')==1)
            $sub_url = Mage::getStoreConfig('payment/publicbank_standard/testing_url');//'https://test.paydollar.com/b2cDemo/eng/dPayment/payComp.jsp';
        else
            $sub_url = Mage::getStoreConfig('payment/publicbank_standard/live_url');//'https://www.paydollar.com/b2c2/eng/directPay/payComp.jsp';
        $arrOrder = Mage::getModel('sales/order')->load(Mage::getSingleton('checkout/session')->getLastOrderId())->toArray();
        $arrCredit = Mage::getSingleton('core/session')->getCredit();
        //$order_id = str_pad($arrOrder['increment_id'], 20, "0", STR_PAD_LEFT);
        //$expired_date = str_pad($arrCredit['cc_exp_month'], 2, "0", STR_PAD_LEFT).substr($arrCredit['cc_exp_year'],2,2);
        //$amount = str_pad($arrOrder['grand_total']*100, 12, "0", STR_PAD_LEFT);
        $merchant_id = (Mage::getStoreConfig('payment/publicbank_standard/merchant_vi_id'));//:(Mage::getStoreConfig('payment/publicbank_standard/merchant_mc_id'));
        //var_dump(Mage::getSingleton('checkout/session')->getLastOrderId(),$order_id,$expired_date);
        switch($arrCredit['cc_type']){
            case 'VI':
                $cctype = 'VISA';
                break;
            case 'MC':
                $cctype = 'Master';
                break;
            case 'AE':
                $cctype = 'AMEX';
                break;
        }
        $submit_form = '<html>
		<head><title>Router to ACS</title></head>
		<body OnLoad="OnLoadEvent();" >
		<p>Please wait...</p>
        <form name="payForm" method="post" action="'.$sub_url.'" style="display:none">
            <input type="text" name="merchantId" value="'.$merchant_id.'">
            <input type="text" name="amount" value="'.$arrOrder['grand_total'].'" >
            <input type="text" name="orderRef" value="'.$arrOrder['increment_id'].'">
            <input type="text" name="currCode" value="702" >
            <input type="text" name="pMethod" value="'.$cctype.'" >
            <input type="text" name="cardNo" value="'.$arrCredit['cc_number'].'" >
            <input type="text" name="securityCode" value="'.$arrCredit['cc_cid'].'" >
            <input type="text" name="cardHolder" value="Testing" >
            <input type="text" name="epMonth" value="'.$arrCredit['cc_exp_month'].'" >
            <input type="text" name="epYear" value="'.$arrCredit['cc_exp_year'].'" >
            <input type="text" name="payType" value="N" >
            <input type="text" name="successUrl" value="'.Mage::getUrl('checkout/onepage/success').'">
            <input type="text" name="failUrl" value="'.Mage::getUrl('checkout/onepage/fail').'">
            <input type="text" name="errorUrl" value="'.Mage::getUrl('checkout/onepage/fail').'">
            <input type="text" name="lang" VALUE="E">
            <input type="submit" value="Pay Now">
            </form>



        <SCRIPT LANGUAGE="Javascript" >
		    function OnLoadEvent(){ document.payForm.submit(); }
		</SCRIPT></body>
		</html>
    ';
		echo $submit_form;
		// Mage::getSingleton('core/session')->unsAcsurl();
		// Mage::getSingleton('core/session')->unsPa();
		// Mage::getSingleton('core/session')->unsTemurl();
		// Mage::getSingleton('core/session')->unsMD();
		// Mage::getSingleton('core/session')->unsStatus();
		//Mage::log('aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaabbbbbbbbbbbbbbbbb');
    }
}