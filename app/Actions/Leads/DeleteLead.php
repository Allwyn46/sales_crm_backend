<?php

namespace App\Actions\Leads;

use App\Models\Lead;

class DeleteLead
{
    public function execute(Lead $lead): bool
    {
        return $lead->delete();
    }
}
