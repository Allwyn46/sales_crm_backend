<?php

namespace App\Actions\Leads;

use App\Models\Lead;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Arr;

class CreateLead
{
    /**
     * Create a new lead.
     *
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data, User $actor): Lead
    {
        $tagIds = Arr::get($data, 'tags', []);
        $data = Arr::except($data, ['tags']);

        $lead = Lead::create([
            ...$data,
            'created_by_id' => $actor->id,
        ]);

        if (! empty($tagIds)) {
            $lead->tags()->sync(array_filter(array_map(fn ($id) => Tag::where('id', $id)->value('id'), $tagIds)));
        }

        return $lead->load(['assignedTo', 'createdBy', 'tags']);
    }
}
