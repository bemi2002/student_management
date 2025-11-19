<?php

namespace App\Http\Controllers;

use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CalendarEventController extends Controller
{
    // Display all events - return JSON
    public function index(): JsonResponse
    {
        $events = CalendarEvent::orderBy('start', 'asc')->get();
        return response()->json($events);
    }

    // Store new event - return JSON
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'start' => 'required|date',
            'end' => 'nullable|date|after:start'
        ]);

        $event = CalendarEvent::create($validated);

        return response()->json($event, 201);
    }

    // Update event - return JSON
    public function update(Request $request, CalendarEvent $calendarEvent): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'start' => 'required|date',
            'end' => 'nullable|date|after:start'
        ]);

        $calendarEvent->update($validated);

        return response()->json($calendarEvent);
    }

    // Delete event - return JSON
    public function destroy(CalendarEvent $calendarEvent): JsonResponse
    {
        $calendarEvent->delete();

        return response()->json(['message' => 'Event deleted successfully.']);
    }
}