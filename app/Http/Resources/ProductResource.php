<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'identifier' => (int)$this->id,
            'title' => (string)$this->name,
            'details' => (string)$this->description,
            'stock' => (int)$this->quantity,
            'situation' => (string)$this->status,
            'picture' => url("img/{$this->image}"),
            'seller' => (int)$this->seller_id,
            'creationDate' => (string)$this->created_at,
            'lastChange' => (string)$this->updated_at,
            'deletedDate' => isset($this->deleted_at) ? (string) $this->deleted_at : null,
        
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('products.show', $this->id),
                ],
                [
                    'rel' => 'product.buyers',
                    'href' => route('products.buyers.index', $this->id),
                ],
                [
                    'rel' => 'product.categories',
                    'href' => route('products.categories.index', $this->id),
                ],
                [
                    'rel' => 'product.transactions',
                    'href' => route('products.transactions.index', $this->id),
                ],
                [
                    'rel' => 'seller',
                    'href' => route('sellers.show', $this->seller_id),
                ],
            ]
        ];
    }
}
