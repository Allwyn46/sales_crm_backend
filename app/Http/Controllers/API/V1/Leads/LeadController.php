<?php

namespace App\Http\Controllers\API\V1\Leads;

use App\Actions\Leads\CreateLead;
use App\Actions\Leads\DeleteLead;
use App\Actions\Leads\UpdateLead;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Leads\StoreLeadRequest;
use App\Http\Requests\API\V1\Leads\UpdateLeadRequest;
use App\Http\Resources\API\V1\Leads\LeadCollection;
use App\Http\Resources\API\V1\Leads\LeadResource;
use App\Models\Lead;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class LeadController extends Controller
{
    /**
     * Display a listing of leads with filtering, sorting, and pagination.
     */
    public function index(Request $request)
    {
        $this->authorize('view leads');
        $leads = QueryBuilder::for(Lead::class)
            ->allowedFilters(
                AllowedFilter::exact('status'),
                AllowedFilter::exact('source'),
                AllowedFilter::exact('assigned_to_id'),
                AllowedFilter::scope('search'),
                AllowedFilter::scope('tag')
            )
            ->allowedSorts('name', 'created_at', 'status', 'follow_up_at', 'phone')
            ->defaultSort('-created_at')
            ->with(['assignedTo', 'tags'])
            ->paginate()
            ->appends($request->query());

        return LeadResource::collection($leads);
    }

    /**
     * Store a newly created lead.
     */
    public function store(StoreLeadRequest $request, CreateLead $createLead)
    {
        $lead = $createLead->execute($request->validated(), $request->user());

        return new LeadResource($lead);
    }

    /**
     * Display the specified lead.
     */
    public function show(Request $request, Lead $lead)
    {
        $this->authorize('view', $lead);

        return new LeadResource($lead->load(['assignedTo', 'createdBy', 'tags']));
    }

    /**
     * Update the specified lead.
     */
    public function update(UpdateLeadRequest $request, Lead $lead, UpdateLead $updateLead)
    {
        $lead = $updateLead->execute($lead, $request->validated(), $request->user());

        return new LeadResource($lead);
    }

    /**
     * Remove the specified lead (soft delete).
     */
    public function destroy(Lead $lead, DeleteLead $deleteLead)
    {
        $this->authorize('delete', $lead);

        $deleteLead->execute($lead);

        return response()->json(['message' => 'Lead deleted']);
    }

    /**
     * Scope for full-text search across name, phone, email, company.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('company', 'like', "%{$search}%");
        });
    }

    /**
     * Scope to filter leads by tag.
     */
    public function scopeTag($query, $tagId)
    {
        return $query->whereHas('tags', fn ($q) => $q->where('tags.id', $tagId));
    }
}
