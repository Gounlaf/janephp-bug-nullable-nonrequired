<?php

declare(strict_types=1);

namespace Gounlaf\JanephpBug\Tests\Endpoint;

use Gounlaf\JanephpBug\Client;
use Gounlaf\JanephpBug\Model\PatchableEntity;
use PHPUnit\Framework\TestCase;

final class PatchAiringTest extends TestCase
{
    /**
     * @dataProvider providerForTestPatchEntity
     */
    public function testPatchEntity($entity, $expectedJson)
    {
        $httpClient = new \Http\Mock\Client();
        $client = Client::create($httpClient);

        $client->patchEntity(42, $entity);

        $req = $httpClient->getLastRequest();
        $this->assertJsonStringEqualsJsonString(json_encode($expectedJson), (string)$req->getBody());
    }

    public function providerForTestPatchEntity()
    {
        yield 'both property filled' => [
            (new PatchableEntity())
                ->setNullableAndRequiredProperty('test_required_value')
                ->setNullableProperty('test_value'),

            ['nullable_and_required_property' => 'test_required_value', 'nullable_property' => 'test_value',],
        ];

        // this one doesn't work
        yield 'nullable non required not set' => [
            (new PatchableEntity())
                ->setNullableAndRequiredProperty('test_required_value'),

            ['nullable_and_required_property' => 'test_required_value'],
        ];
    }
}
