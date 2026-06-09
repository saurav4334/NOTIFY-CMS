<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactLead;
use App\Models\Setting;
use App\Models\SmsRate;
use App\Services\CmsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /** GET /api/v1/content — the full CMS structure for the marketing frontend. */
    public function content(CmsRepository $cms): JsonResponse
    {
        return response()->json($cms->assemble());
    }

    /** GET /api/sms-rates — all active slab rates grouped by type (feeds pricing page). */
    public function rates(): JsonResponse
    {
        return response()->json([
            'non_masking' => SmsRate::active()->ofType('non_masking')->get(),
            'masking' => SmsRate::active()->ofType('masking')->get(),
            'vat_percent' => (float) Setting::value('pricing', 'vat_percent', 0),
        ]);
    }

    /** POST /api/calculate — compute cost for a quantity + masking type (feeds calculator). */
    public function calculate(Request $request): JsonResponse
    {
        $data = $request->validate([
            'type' => ['required', 'in:masking,non_masking'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $unitPrice = SmsRate::priceFor($data['type'], $data['quantity']);

        if ($unitPrice === null) {
            return response()->json([
                'message' => 'No rate slab matches that quantity.',
            ], 422);
        }

        $vatPercent = (float) Setting::value('pricing', 'vat_percent', 0);
        $subtotal = round($unitPrice * $data['quantity'], 2);
        $vat = round($subtotal * $vatPercent / 100, 2);

        return response()->json([
            'type' => $data['type'],
            'quantity' => $data['quantity'],
            'unit_price' => $unitPrice,
            'subtotal' => $subtotal,
            'vat_percent' => $vatPercent,
            'vat' => $vat,
            'total' => round($subtotal + $vat, 2),
            'currency' => 'BDT',
        ]);
    }

    /** POST /api/contact — store a contact-form lead. */
    public function contact(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:32'],
            'email' => ['nullable', 'email', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:5000'],
        ]);

        $lead = ContactLead::create([
            ...$data,
            'source' => 'contact_form',
            'status' => 'new',
            'ip_address' => $request->ip(),
            'user_agent' => substr((string) $request->userAgent(), 0, 255),
        ]);

        return response()->json([
            'message' => 'Thank you — we will get back to you shortly.',
            'id' => $lead->id,
        ], 201);
    }
}
