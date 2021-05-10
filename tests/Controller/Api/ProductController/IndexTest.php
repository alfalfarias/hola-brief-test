<?php

namespace App\Tests\Controller\Api\ProductController;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class IndexTest extends ApiTestCase
{
	public function testIndex(): void
	{
		$client = static::createClient([]);
		$request = $client->request('GET', '/api/product/products');

		$this->assertResponseIsSuccessful();
	}
}
