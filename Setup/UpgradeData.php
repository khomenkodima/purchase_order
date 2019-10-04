<?php

namespace Treestonemedia\PurchaseOrder\Setup;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\State;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Entity\Attribute\Source\Boolean;

class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
    /**
     * @var State
     */
    private $state;
    /**
     * @var CustomerHelper
     */
    private $customerHelper;

    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        CustomerRepositoryInterface $customerRepository,
        State $state
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->customerRepository = $customerRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->state = $state;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $version = $context->getVersion();

        switch (true) {
            case (version_compare($version, '1.0.0') < 0):
                $this->createAllowPurchaseOrderAttribute();
        }

        $setup->endSetup();
    }

    protected function createAllowPurchaseOrderAttribute()
    {
        $customerSetup = $this->customerSetupFactory->create();
        $code = 'allow_purchase_order';

        $customerSetup->addAttribute(
            Customer::ENTITY,
            $code,
            [
                'label' => 'Allow Purchase Order',
                'type' => 'int',
                'input' => 'boolean',
                'required'=> false,
                'position'=> 80,
                'visible' => true,
                'system'=> false,
                'source' => Boolean::class,
                'is_used_in_grid' => true,
                'is_visible_in_grid'=> true,
                'is_filterable_in_grid' => true,
                'is_searchable_in_grid' => true
            ]
        );

        // add attribute to form
        /** @var  $attribute */
        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, $code);
        $attribute->setData('used_in_forms', ['adminhtml_customer']);


        $attribute->save();
    }
}