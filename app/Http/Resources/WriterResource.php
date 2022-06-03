<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class WriterResource extends JsonResource
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public string $collects = User::class;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $isAdmin = $request->user() && $request->user()->is_admin;

        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'biography' => $this->biography,
            'email' => $this->when($isAdmin, $this->email),
            'created_at' => $this->created_at,
            'updated_at' => $this->when($isAdmin, $this->updated_at),
        ];
    }
}
