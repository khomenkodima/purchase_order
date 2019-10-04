<?php

namespace Treestonemedia\PurchaseOrder\Test\Integration\ApiRequests;

use Magento\TestFramework\TestCase\WebapiAbstract;
use Magento\Framework\Webapi\Rest\Request;
use Magento\TestFramework\Helper\Bootstrap;
use Magento\Customer\Api\Data\CustomerInterfaceFactory;
use Magento\Customer\Api\CustomerRepositoryInterface;

abstract class BaseRequest extends WebapiAbstract
{
    public function getBaseUrl()
    {
        if (defined('TESTS_BASE_URL')) {
            return TESTS_BASE_URL;
        }

        throw new \Exception('The base URL has not been defined');
    }



    public function makeRequest($endPoint, $httpMethod, $postData = [])
    {
        $adminToken   = $this->getAdminToken();

        $serviceInfo = [
            'rest' => [
                'resourcePath' => "/V1/purchaseorder/" . $endPoint,
                'httpMethod'   => $httpMethod,
                'token'        => $adminToken
            ]
        ];

        $result = $this->_webApiCall($serviceInfo, $postData);

        return $result;
    }

    /**
     * Fetch admin token
     *
     * @return String
     */
    public function getAdminToken()
    {
        $serviceInfo = [
            'rest' => [
                'resourcePath' => "/V1/integration/admin/token",
                'httpMethod'   => Request::HTTP_METHOD_POST
            ]
        ];

        $postData = array("username" => TESTS_WEBSERVICE_USER, "password" => TESTS_WEBSERVICE_APIKEY);
        $token = $this->_webApiCall($serviceInfo, $postData);
        return $token;
    }

    public static function customerFixtures()
    {
        $objectManager = Bootstrap::getObjectManager();
        $customer = $objectManager->get(CustomerInterfaceFactory::class)->create();
        $customer->setEmail('test@test.com');
        $customer->setFirstname('test');
        $customer->setLastname('test');
        $customer->setCustomAttribute('allow_purchase_order', 1);
        $objectManager->get(CustomerRepositoryInterface::class)->save($customer);
    }


    public static function customerFixturesRollback()
    {
        $objectManager = Bootstrap::getObjectManager();
        $objectManager->get('Magento\Framework\Registry')->register('isSecureArea', true);

        $repository = $objectManager->get(CustomerRepositoryInterface::class);
        $customer = $repository->get('test@test.com');
        $repository->delete($customer);
    }

    protected function getTestCustomerId() {
        $objectManager = Bootstrap::getObjectManager();
        $repository = $objectManager->get(CustomerRepositoryInterface::class);
        $customer = $repository->get('test@test.com');
        return $customer->getId();
    }
}
