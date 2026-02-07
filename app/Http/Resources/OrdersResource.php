<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrdersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'code'=>$this->order_code,
            'name'=>$this->customer_name,
            'product_name' => $this->product ? $this->product->name : 'N/A',
            'product_price' => $this->product ? $this->product->price : 'N/A',
            'order_date'=>$this->order_date,
            'total_amount'=>$this->total_amount,
            'order_status'=>$this->status
        ];
    }
}
