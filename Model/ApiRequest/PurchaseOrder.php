<?php

namespace Treestonemedia\PurchaseOrder\Model\ApiRequest;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Customer\Api\CustomerRepositoryInterface;
use \Magento\Framework\Exception\LocalizedException;

class PurchaseOrder implements \Treestonemedia\PurchaseOrder\Api\Requests\PurchaseOrderInterface
{
    /**
     * @var FilterBuilder
     */
    protected $filterBuilder;
    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;
    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;
    /**
     * @var \Treestonemedia\PurchaseOrder\Helper\Data
     */
    protected $helper;



    /**
     * @param FilterBuilder $filterBuilder
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param CustomerRepositoryInterface $customerRepository
     * @param \Treestonemedia\PurchaseOrder\Helper\Data $helper
     */
    public function __construct(
        FilterBuilder $filterBuilder,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CustomerRepositoryInterface $customerRepository,
        \Treestonemedia\PurchaseOrder\Helper\Data $helper
    ) {
        $this->helper = $helper;
        if (!$this->helper->getGeneralConfig('enable')) {
            throw new LocalizedException(__('Module disabled.'));
        }
        $this->filterBuilder = $filterBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->customerRepository = $customerRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getList()
    {
        $searchFields = ['allow_purchase_order'];
        $filters = [];
        foreach ($searchFields as $field) {
            $filters[] = $this->filterBuilder
                ->setField($field)
                ->setConditionType('eq')
                ->setValue(1)
                ->create();
        }
        $this->searchCriteriaBuilder->addFilters($filters);
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $searchResults = $this->customerRepository->getList($searchCriteria);
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function allow($customerId)
    {
        $customer = $this->customerRepository->getById($customerId);
        if(!$customer->getId()) {
            return false;
        }
        $customer->setCustomAttribute('allow_purchase_order', 1);
        $this->customerRepository->save($customer);
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function disallow($customerId)
    {
        $customer = $this->customerRepository->getById($customerId);
        if(!$customer->getId()) {
            return false;
        }
        $customer->setCustomAttribute('allow_purchase_order', 0);
        $this->customerRepository->save($customer);
        return true;
    }
}
