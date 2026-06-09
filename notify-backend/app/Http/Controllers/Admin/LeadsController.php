<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ContactLead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadsController extends Controller
{
    /** JSON list with optional status filter + search, plus status counts. */
    public function index(Request $request): JsonResponse
    {
        $query = ContactLead::query()->latest();

        if ($status = $request->query('status')) {
            if (in_array($status, ContactLead::STATUSES, true)) {
                $query->where('status', $status);
            }
        }

        if ($search = trim((string) $request->query('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%");
            });
        }

        return response()->json([
            'leads' => $query->limit(500)->get(),
            'counts' => $this->counts(),
            'statuses' => ContactLead::STATUSES,
        ]);
    }

    /** Update a lead's status and/or admin notes. */
    public function update(Request $request, ContactLead $lead): JsonResponse
    {
        $data = $request->validate([
            'status' => ['sometimes', 'in:'.implode(',', ContactLead::STATUSES)],
            'admin_notes' => ['sometimes', 'nullable', 'string', 'max:5000'],
        ]);

        $lead->update($data);
        ActivityLog::record('updated', $lead, 'Lead updated');

        return response()->json(['message' => 'Lead updated.', 'lead' => $lead, 'counts' => $this->counts()]);
    }

    public function destroy(ContactLead $lead): JsonResponse
    {
        $lead->delete();
        ActivityLog::record('deleted', null, "Lead #{$lead->id} deleted");

        return response()->json(['message' => 'Lead deleted.', 'counts' => $this->counts()]);
    }

    /** Stream all (optionally filtered) leads as a CSV download. */
    public function export(Request $request): StreamedResponse
    {
        $query = ContactLead::query()->latest();
        if (($status = $request->query('status')) && in_array($status, ContactLead::STATUSES, true)) {
            $query->where('status', $status);
        }

        $filename = 'leads-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID', 'Name', 'Phone', 'Email', 'Company', 'Message', 'Source', 'Status', 'IP', 'Date']);
            $query->chunk(200, function ($leads) use ($out) {
                foreach ($leads as $l) {
                    fputcsv($out, [
                        $l->id, $l->name, $l->phone, $l->email, $l->company,
                        $l->message, $l->source, $l->status, $l->ip_address,
                        $l->created_at?->format('Y-m-d H:i'),
                    ]);
                }
            });
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    private function counts(): array
    {
        $counts = ['all' => ContactLead::count()];
        foreach (ContactLead::STATUSES as $status) {
            $counts[$status] = ContactLead::where('status', $status)->count();
        }
        return $counts;
    }
}
