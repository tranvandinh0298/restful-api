<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'identifier' => (int) $this->id,
            'quantity' => (int) $this->quantity,
            'buyer' => (int) $this->buyer_id,
            'product' => (int) $this->product_id,
            'creationDate' => (string) $this->created_at,
            'lastChange' => (string) $this->updated_at,
            'deletedDate' => isset($this->deleted_at) ? (string) $this->deleted_at : null,
        
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('transactions.show', $this->id),
                ],
                [
                    'rel' => 'transaction.categories',
                    'href' => route('transactions.categories.index', $this->id),
                ],
                [
                    'rel' => 'transaction.seller',
                    'href' => route('transactions.sellers.index', $this->id),
                ],
                [
                    'rel' => 'buyer',
                    'href' => route('buyers.show', $this->buyer_id),
                ],
                [
                    'rel' => 'product',
                    'href' => route('products.show', $this->product_id),
                ],
            ]
        ];
    }
}
