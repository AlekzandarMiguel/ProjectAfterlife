<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStatus;
use App\Models\Project;
use Illuminate\View\View;

class ResurrectedController extends Controller
{
    public function index(): View
    {
        $resurrectedProjects = Project::with([
            'category',
            'technologies',
            'owner.profile',
            'originalOwner.profile',
            'versions.uploader',
            'ownershipTransfers.previousOwner',
            'ownershipTransfers.newOwner',
            'latestFinalReview',
        ])
        ->where('status', ProjectStatus::RESURRECTED)
        ->latest('resurrected_at')
        ->paginate(6);

        return view('public.resurrected', compact('resurrectedProjects'));
    }
}
