<?php

use App\Services\ExploreService;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Collection;


test('get service type', function () {
    $mock = Mockery::mock(ExploreService::class);
    $mock->shouldReceive('getServiceType')
            ->once()
            ->andReturn(new Collection([
                (object) [
                    'uuid' => Str::uuid(),
                    'name' => 'A'
                ],
                (object) [
                    'uuid' => Str::uuid(),
                    'name' => 'B'
                ]
            ]));

    $this->app->instance(ExploreService::class, $mock);

    $response = $this->get('/api/v1/public/service-type');
    $response->assertStatus(200);
    expect($response->json('data'))->toHaveCount(2);
});
