<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class IssueAttachmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'original_name' => $this->original_name,
            'mime_type' => $this->mime_type,
            'file_size' => $this->file_size,

            'file_url' => Storage::disk('supabase')
                ->url($this->file_path),

            'created_at' => $this->created_at,

            'uploader' => $this->whenLoaded('uploader', function () {
                return [
                    'fullName' => $this->uploader->full_name,
                ];
            }),
        ];
    }
}