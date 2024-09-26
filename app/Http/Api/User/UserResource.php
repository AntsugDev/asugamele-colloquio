<?php

namespace App\Http\Api\User;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

   public function toArray(Request $request)
   {
       return [
           "id" => $this->resource->id,
           "name" => $this->resource->name,
           "email" => $this->resource->email,
           "created_at" => $this->resource->created_at,
           "updated_at" => $this->resource->updated_at,
       ];
   }
}
