<?php

namespace Treestonemedia\PurchaseOrder\Test\Integration\ApiRequests;

use Magento\Framework\Webapi\Rest\Request;

class PurchaseOrderTest extends BaseRequest
{
    /**
     * @magentoAppArea adminhtml
     * @magentoApiDataFixture customerFixtures
     */
    public function testSearch()
    {
        $result = $this->makeRequest('search', Request::HTTP_METHOD_GET);
        $matchCount = 0;
        foreach ($result['items'] as $item) {
            if ($item['email'] == 'test@test.com') {
                $matchCount ++;
            }
        }
        $this->assertEquals($matchCount, 1);
    }

    /**
     * @magentoAppArea adminhtml
     * @magentoApiDataFixture customerFixtures
     */
    public function testAllowDisallow()
    {
        $id =  $this->getTestCustomerId();
        $postData = ['id' => $id];

        $result = $this->makeRequest('disallow', Request::HTTP_METHOD_PUT, $postData);
        $this->assertEquals($result, true);

        $result = $this->makeRequest('search', Request::HTTP_METHOD_GET);
        $matchCount = 0;
        foreach ($result['items'] as $item) {
            if ($item['email'] == 'test@test.com') {
                $matchCount ++;
            }
        }
        $this->assertEquals($matchCount, 0);

        $result = $this->makeRequest('allow', Request::HTTP_METHOD_PUT, $postData);
        $this->assertEquals($result, true);

        $result = $this->makeRequest('search', Request::HTTP_METHOD_GET);
        $matchCount = 0;
        foreach ($result['items'] as $item) {
            if ($item['email'] == 'test@test.com') {
                $matchCount ++;
            }
        }
        $this->assertEquals($matchCount, 1);
    }

}
