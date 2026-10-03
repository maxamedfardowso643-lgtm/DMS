<?php

namespace App\Http\Controllers;

use App\Models\AppointmentRequest;
use App\Models\ContactMessage;
use App\Models\Dentist;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function home(): View
    {
        $services = Service::where('is_active', true)->orderBy('id')->take(6)->get();
        $dentists = Dentist::with('user')->where('is_active', true)->take(4)->get();
        $stats = [
            'patients' => \App\Models\Patient::count(),
            'dentists' => Dentist::where('is_active', true)->count(),
            'services' => Service::where('is_active', true)->count(),
            'years' => 8,
        ];
        $testimonials = Testimonial::where('is_approved', true)->latest()->take(6)->get();

        return view('public.home', compact('services', 'dentists', 'stats', 'testimonials'));
    }

    public function about(): View
    {
        $dentists = Dentist::with('user')->where('is_active', true)->get();

        return view('public.about', compact('dentists'));
    }

    public function services(): View
    {
        $services = Service::where('is_active', true)->orderBy('category')->orderBy('name')->get()->groupBy('category');

        return view('public.services', compact('services'));
    }

    public function team(): View
    {
        $dentists = Dentist::with('user')->where('is_active', true)->get();

        return view('public.team', compact('dentists'));
    }

    public function gallery(): View
    {
        return view('public.gallery');
    }

    public function contact(): View
    {
        $services = Service::where('is_active', true)->orderBy('name')->get();

        return view('public.contact', compact('services'));
    }

    public function submitContact(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'subject' => ['nullable', 'string', 'max:150'],
            'message' => ['required', 'string', 'max:2000'],
        ]);

        ContactMessage::create($data);

        return back()->with('success', 'Thank you! Your message has been sent — we will get back to you shortly.');
    }

    public function submitBookingRequest(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'service_id' => ['nullable', 'exists:services,id'],
            'preferred_date' => ['nullable', 'date', 'after_or_equal:today'],
            'preferred_time' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        AppointmentRequest::create($data + ['status' => 'pending']);

        return response()->json(['message' => 'Request received! Our team will contact you shortly to confirm your appointment.']);
    }

    public function submitTestimonial(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'message' => ['required', 'string', 'max:1000'],
        ]);

        Testimonial::create($data + ['rating' => $data['rating'] ?? 5]);

        return back()->with('success', 'Thank you for sharing your experience! It will appear on our site once reviewed.');
    }
}
