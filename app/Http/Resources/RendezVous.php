<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RendezVous extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'rendez_vous' => $this->rendez_vous,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'objectif' => $this->objectif,
            'sales_man_id' => $this->sales_man_id,
            'user_id' => $this->user_id,
            'sub_id'=>$this->sub_id
        ];
    }
}
