<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Seo\app\Traits\Resources\SeoFieldResource;

class BaseJsonResource extends JsonResource
{
    use SeoFieldResource;

    public function toArray(Request $request): array
    {
        $data = $this->getData($request);

        $resource = $this->resource;
        if (method_exists($resource, 'relationLoaded') && $resource->relationLoaded('seoField')) {
            $data['seo_field'] = $this->seoData($resource->seoField);
        }

        return $data;
    }

    protected function getData(Request $request): array
    {
        return parent::toArray($request);
    }
}
