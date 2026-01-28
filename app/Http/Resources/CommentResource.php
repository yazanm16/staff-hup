<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class CommentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'      => $this->id,
            'body'    => $this->body,
            'user'    => [
                'id'   => $this->user->id,
                'name' => $this->user->name,
            ],
            'image'   => $this->photo
                ? asset('storage/' . $this->photo->path)
                : null,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
