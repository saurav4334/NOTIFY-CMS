<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Services\CmsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CmsController extends Controller
{
    public function __construct(private readonly CmsRepository $cms)
    {
    }

    /** Full CMS structure as JSON (used to refresh the panel if needed). */
    public function show(): JsonResponse
    {
        return response()->json($this->cms->assemble());
    }

    /** Persist the whole CMS structure sent by the admin panel. */
    public function update(Request $request): JsonResponse
    {
        $data = $request->all();

        if (empty($data) || ! is_array($data)) {
            return response()->json(['message' => 'No data received.'], 422);
        }

        $this->cms->persist($data);
        ActivityLog::record('updated', null, 'CMS content updated');

        return response()->json(['message' => 'Saved successfully.']);
    }

    /** Change the signed-in admin's password (Security section). */
    public function changePassword(Request $request): JsonResponse
    {
        $data = $request->validate([
            'current' => ['required'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $user = Auth::user();
        if (! Hash::check($data['current'], $user->password)) {
            return response()->json(['message' => 'Current password is incorrect.'], 422);
        }

        $user->forceFill(['password' => Hash::make($data['password'])])->save();
        ActivityLog::record('updated', $user, 'Password changed');

        return response()->json(['message' => 'Password changed successfully.']);
    }
}
