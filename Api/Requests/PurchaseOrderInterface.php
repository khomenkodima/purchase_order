<?php

namespace Treestonemedia\PurchaseOrder\Api\Requests;

interface PurchaseOrderInterface
{
    /**
     * @param string $id
     * @return bool true on success
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function allow($id);

    /**
     * @param string $id
     * @return bool true on success
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function disallow($id);

    /**
     * @return \Magento\Customer\Api\Data\CustomerSearchResultsInterface
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList();

}
