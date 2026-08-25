<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OwnershipTransfer;
use Illuminate\View\View;

class OwnershipTransferController extends Controller
{
    public function index(): View
    {
        $transfers = OwnershipTransfer::with(['project', 'previousOwner', 'newOwner', 'adminApprover', 'adoptionRequest'])
            ->latest('transferred_at')
            ->paginate(15);

        return view('admin.transfers.index', compact('transfers'));
    }
}
