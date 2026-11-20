<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IssueCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return
            [
                'id' => $this->id,
                'issueId' => $this->issue_id,
                'body' => $this->body,
                'isInternal' => $this->is_internal,
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at,
                'commenter' => $this->whenLoaded(
                    'user',
                    function () {
                        return [
                            'id' => $this->user->id,
                            'fullName' => $this->user->fullName
                        ];
                    }
                )
            ];
    }
}
