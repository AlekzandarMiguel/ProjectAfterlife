<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\View\View;

class CertificateController extends Controller
{
    public function show(Project $project): View
    {
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
