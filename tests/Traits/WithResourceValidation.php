<?php

namespace Tests\Traits;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Testing\TestResponse;

trait WithResourceValidation
{
    public function validateResourceResponse(TestResponse $response, JsonResource $resource)
    {
        $response->assertJson($resource->response()->getData(true));

        return $this;
    }
}
