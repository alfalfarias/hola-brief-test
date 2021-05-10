<?php

namespace App\Tests\Controller\Api\OrderController;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class IndexTest extends ApiTestCase
{
	public function testIndex(): void
	{
		$response = static::createClient()->request('GET', '/api/order/orders');

		$this->assertResponseIsSuccessful();
	}
}
