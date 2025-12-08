<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CalendarEvent;
use App\Models\Enrollmentss;

class CalendarEventController extends Controller
{
    // GET /calendar-events
    public function index()
    {
        return CalendarEvent::with('enrollmentss')->orderBy('start', 'asc')->get();
    }

    // GET /enrollmentss
    public function enrollments()
    {
        return Enrollmentss::orderBy('id', 'desc')->get();
    }

    // POST /calendar-events
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'enrollmentss_id' => 'nullable|integer|exists:enrollmentss,id', // FIXED
        ]);

        $event = CalendarEvent::create($validated);
        $event->load('enrollmentss');

        return response()->json(['event' => $event], 201);
    }

    // PUT /calendar-events/{calendarEvent}
    public function update(Request $request, CalendarEvent $calendarEvent)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'start' => 'required|date',
            'end' => 'nullable|date|after_or_equal:start',
            'enrollmentss_id' => 'nullable|integer|exists:enrollmentss,id', // FIXED
        ]);

        $calendarEvent->update($validated);
        $calendarEvent->load('enrollmentss');

        return response()->json($calendarEvent);
    }

    // DELETE /calendar-events/{calendarEvent}
    public function destroy(CalendarEvent $calendarEvent)
    {
        $calendarEvent->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }
}
