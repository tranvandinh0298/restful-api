<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BuyerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'identifier' => (int) $this->id,
            'name' => (string) $this->name,
            'email' => (string) $this->email,
            'isVerified' => (int) $this->verified,
            'creationDate' => (string) $this->created_at,
            'lastChange' => (string) $this->updated_at,
            'deletedDate' => isset($this->deleted_at) ? (string) $this->deleted_at : null,
            
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('buyers.show', $this->id),
                ],
                [
                    'rel' => 'buyer.categories',
                    'href' => route('buyers.categories.index', $this->id),
                ],
                [
                    'rel' => 'buyer.products',
                    'href' => route('buyers.products.index', $this->id),
                ],
                [
                    'rel' => 'buyer.sellers',
                    'href' => route('buyers.sellers.index', $this->id),
                ],
                [
                    'rel' => 'buyer.transactions',
                    'href' => route('buyers.transactions.index', $this->id),
                ],
                [
                    'rel' => 'user',
                    'href' => route('users.show', $this->id),
                ],
            ],
        ];
    }
}
