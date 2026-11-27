<?php

namespace App\Actions\Leads;

use App\Models\Lead;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Arr;

class UpdateLead
{
    /**
     * Update an existing lead.
     *
     * @param  array<string, mixed>  $data
     */
    public function execute(Lead $lead, array $data, User $actor): Lead
    {
        $tagIds = Arr::get($data, 'tags', []);
        $data = Arr::except($data, ['tags']);

        $lead->update($data);

        if (array_key_exists('tags', $data) === false && ! empty($tagIds)) {
            $lead->tags()->sync(array_filter(array_map(fn ($id) => Tag::where('id', $id)->value('id'), $tagIds)));
        } elseif (! empty($tagIds)) {
            $lead->tags()->sync(array_filter(array_map(fn ($id) => Tag::where('id', $id)->value('id'), $tagIds)));
        }

        return $lead->load(['assignedTo', 'createdBy', 'tags']);
    }
}
