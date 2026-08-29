<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\View\View;

class CertificateController extends Controller
{
    public function show(Project $project): View
    {
        $user = auth()->user();
        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        $isOriginalOwner = (int) $user->id === (int) $project->original_owner_id;
        $isCurrentOwnerOrAdopter = (int) $user->id === (int) $project->owner_id
            || $project->adoptionRequests()->where('user_id', $user->id)->exists()
            || $project->ownershipTransfers()->where('new_owner_id', $user->id)->exists();
        $isAdmin = $user->isAdmin();

        if (!$isOriginalOwner && !$isCurrentOwnerOrAdopter && !$isAdmin) {
            abort(403, 'Access Restricted. Software provenance certificates are confidential and only accessible to the original project owner, adopter, and platform administrators.');
        }

        $project->load([
            'owner.profile',
            'originalOwner.profile',
            'ownershipTransfers.adminApprover',
            'ownershipTransfers.previousOwner',
            'ownershipTransfers.newOwner',
            'files',
            'category',
            'technologies',
            'latestVersion',
        ]);

        $latestTransfer = $project->ownershipTransfers->first();
        $sourceZip = $project->files->firstWhere('file_type', \App\Enums\FileType::SOURCE_CODE_ZIP) ?? $project->files->first();

        return view('public.certificate', compact('project', 'latestTransfer', 'sourceZip'));
    }
}
