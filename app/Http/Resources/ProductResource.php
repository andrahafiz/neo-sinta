<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name_product,
            'slug'        => $this->slug,
            'price'       => $this->price,
            'stock'       => $this->stock,
            'image'       => $this->image !== null ? config('app.url') . '/storage/' . $this->image : 'no-image.jpg',
            'categories'  => new CategoryResource($this->whenLoaded('categories')),
            'description' => $this->description,
            'createdAt'   => $this->created_at?->format('c'),
            'updatedAt'   => $this->updated_at?->format('c'),
        ];
    }
}
