<?php

namespace Treestonemedia\PurchaseOrder\Plugin\Payment;


class Available
{

    protected $customerSession;
    protected $helper;

    /**
     * Construct
     *
     * @param \Magento\Customer\Model\Session $customerSession
     * @param \Treestonemedia\PurchaseOrder\Helper\Data $helper
     */
    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Treestonemedia\PurchaseOrder\Helper\Data $helper
    ) {
        $this->customerSession = $customerSession;
        $this->helper = $helper;
    }

    public function afterGetAvailableMethods($subject, $result)
    {
        if (!$this->helper->getGeneralConfig('enable')) {
            return $result;
        }
        foreach ($result as $key=>$_result) {
            if ($_result->getCode() == "purchaseorder") {
                $isAllowed =  false;
                if ($this->customerSession->isLoggedIn() &&
                    $this->customerSession->getCustomer()->getAllowPurchaseOrder()) {
                    $isAllowed = true;
                }
                if (!$isAllowed) {
                    unset($result[$key]);
                }
            }
        }
        return $result;
    }
}