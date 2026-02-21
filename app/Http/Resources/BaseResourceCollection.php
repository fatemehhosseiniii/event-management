<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractPaginator;

class BaseResourceCollection extends ResourceCollection
{
    protected array $itemParameters = [];
    protected string $collectionTitle = 'data';
    
    public function withParameters(array $parameters): static
    {
        $this->itemParameters = array_merge($this->itemParameters, $parameters);
        return $this;
    }

    public function toArray($request): array
    {
        return [
            $this->collectionTitle => $this->collection->map(function ($resource) use ($request) {
                if (!empty($this->itemParameters)) {
                    $resource->additional($this->itemParameters);
                }
                return $resource->resolve($request);
            }),
            $this->mergeWhen($this->isPaginated(), [
                'page_data' => new PageLinkResource($this->resource)
            ]),
        ];
    }

    protected function isPaginated(): bool
    {
        return $this->resource instanceof AbstractPaginator;
    }

}
