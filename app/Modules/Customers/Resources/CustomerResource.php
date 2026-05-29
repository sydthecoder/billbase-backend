<?php

namespace App\Modules\Customers\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'customer_code'      => $this->customer_code,
            'company_name'       => $this->company_name,
            'company_reg_number' => $this->company_reg_number,
            'vat_number'         => $this->vat_number,
            'first_name'         => $this->first_name,
            'last_name'          => $this->last_name,
            'full_name'          => trim($this->first_name . ' ' . $this->last_name),
            'email'              => $this->email,
            'phone'              => $this->phone,
            'address'            => [
                'street_address' => $this->street_address,
                'suburb'         => $this->suburb,
                'city'           => $this->city,
                'province'       => $this->province,
                'province_label' => $this->province ? config('lookup.provinces')[$this->province] : null,
                'postal_code'    => $this->postal_code,
            ],
            'notes'      => $this->notes,
            'status'     => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}