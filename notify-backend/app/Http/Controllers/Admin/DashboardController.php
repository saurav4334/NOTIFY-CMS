<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactLead;
use App\Services\CmsRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly CmsRepository $cms)
    {
    }

    public function index(): View
    {
        $user = Auth::user();
        $can = [
            'cms' => $user->hasPermission('cms'),
            'media' => $user->hasPermission('media'),
            'leads' => $user->hasPermission('leads'),
        ];

        return view('admin.dashboard', [
            'cms' => $can['cms'] ? $this->cms->assemble() : null,
            'can' => $can,
            'user' => $user,
            'stats' => [
                'leads_total' => $can['leads'] ? ContactLead::count() : 0,
                'leads_new' => $can['leads'] ? ContactLead::where('status', 'new')->count() : 0,
            ],
        ]);
    }
}
