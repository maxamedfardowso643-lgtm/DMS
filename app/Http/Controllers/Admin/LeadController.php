<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppointmentRequest;
use App\Models\ContactMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function index(): View
    {
        $bookingRequests = AppointmentRequest::with('service')->latest()->get();
        $contactMessages = ContactMessage::latest()->get();

        return view('admin.leads.index', compact('bookingRequests', 'contactMessages'));
    }

    public function updateStatus(Request $request, AppointmentRequest $appointmentRequest): JsonResponse
    {
        $request->validate([
            'status' => ['required', Rule::in(['pending', 'contacted', 'converted', 'dismissed'])],
        ]);

        $appointmentRequest->update(['status' => $request->status]);

        return response()->json(['message' => 'Status updated.']);
    }

    public function markMessageRead(ContactMessage $contactMessage): JsonResponse
    {
        $contactMessage->update(['is_read' => true]);

        return response()->json(['message' => 'Marked as read.']);
    }

    public function destroyMessage(ContactMessage $contactMessage): JsonResponse
    {
        $contactMessage->delete();

        return response()->json(['message' => 'Message deleted.']);
    }

    public function destroyRequest(AppointmentRequest $appointmentRequest): JsonResponse
    {
        $appointmentRequest->delete();

        return response()->json(['message' => 'Request deleted.']);
    }
}
