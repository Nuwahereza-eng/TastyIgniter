<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Igniter\Local\Facades\Location;
use Igniter\Local\Models\Location as LocationModel;

/**
 * Location Schedule Controller
 * 
 * Handles setting the order schedule timeslot for TastyIgniter's cart system
 */
class LocationScheduleController extends Controller
{
    /**
     * Get current schedule settings
     */
    public function current(): JsonResponse
    {
        if (!Location::check()) {
            return response()->json([
                'success' => false,
                'message' => 'No location selected',
                'data' => null,
            ]);
        }

        try {
            return response()->json([
                'success' => true,
                'data' => [
                    'location_id' => Location::getId(),
                    'location_name' => Location::getName(),
                    'order_type' => Location::orderType(),
                    'order_datetime' => Location::orderDateTime()->toIso8601String(),
                    'order_date' => Location::orderDateTime()->format('Y-m-d'),
                    'order_time' => Location::orderDateTime()->format('H:i'),
                    'is_asap' => Location::orderTimeIsAsap(),
                    'is_open' => Location::isOpened(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get schedule settings',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Set the schedule timeslot
     * 
     * This updates TastyIgniter's session so the order uses the specified date/time
     */
    public function setSchedule(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'order_date' => 'required|date_format:Y-m-d|after_or_equal:today',
            'order_time' => 'required|date_format:H:i',
            'is_asap' => 'sometimes|boolean',
            'order_type' => 'sometimes|string|in:delivery,collection',
            'location_id' => 'sometimes|integer',
        ]);

        try {
            // Set location if provided
            if (!empty($validated['location_id'])) {
                $locationModel = LocationModel::find($validated['location_id']);
                if ($locationModel) {
                    Location::setCurrent($locationModel);
                }
            }

            // Ensure we have a location
            if (!Location::check()) {
                // Try to use default location
                $defaultLocation = LocationModel::where('location_status', true)->first();
                if ($defaultLocation) {
                    Location::setCurrent($defaultLocation);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'No location available. Please select a restaurant first.',
                    ], 400);
                }
            }

            // Set order type if provided
            if (!empty($validated['order_type'])) {
                Location::updateOrderType($validated['order_type']);
            }

            // Combine date and time
            $dateTimeString = $validated['order_date'] . ' ' . $validated['order_time'];
            $dateTime = make_carbon($dateTimeString);
            
            // Check if the time slot is valid
            $isAsap = $validated['is_asap'] ?? false;
            
            // Debug logging
            \Log::info('LocationScheduleController setSchedule', [
                'order_date' => $validated['order_date'],
                'order_time' => $validated['order_time'],
                'dateTime' => $dateTime->format('Y-m-d H:i:s'),
                'isAsap' => $isAsap,
                'locationId' => Location::getId(),
                'orderType' => Location::orderType(),
            ]);
            
            // Update the schedule timeslot in TastyIgniter's session
            Location::updateScheduleTimeSlot($dateTime, $isAsap);
            
            // Verify the update
            \Log::info('LocationScheduleController after updateScheduleTimeSlot', [
                'orderDateTime' => Location::orderDateTime()->format('Y-m-d H:i:s'),
                'checkOrderTime' => Location::checkOrderTime() ? 'PASS' : 'FAIL',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Schedule set successfully',
                'data' => [
                    'order_date' => $validated['order_date'],
                    'order_time' => $validated['order_time'],
                    'order_datetime' => $dateTime->toIso8601String(),
                    'is_asap' => $isAsap,
                    'order_type' => Location::orderType(),
                    'location_id' => Location::getId(),
                    'location_name' => Location::getName(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to set schedule',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get available timeslots for a date
     */
    public function getTimeslots(Request $request): JsonResponse
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        
        try {
            if (!Location::check()) {
                // Try to use default location
                $defaultLocation = LocationModel::where('location_status', true)->first();
                if ($defaultLocation) {
                    Location::setCurrent($defaultLocation);
                }
            }

            if (!Location::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No location selected',
                ], 400);
            }

            $timeslots = Location::scheduleTimeslot();
            $formattedSlots = [];

            $timeslots->collapse()->each(function ($slot) use (&$formattedSlots, $date) {
                $slotDate = $slot->format('Y-m-d');
                $slotTime = $slot->format('H:i');
                
                if ($slotDate === $date) {
                    $formattedSlots[] = [
                        'time' => $slotTime,
                        'label' => make_carbon($slot)->isoFormat('h:mm A'),
                    ];
                }
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'date' => $date,
                    'timeslots' => $formattedSlots,
                    'has_asap' => Location::hasAsapSchedule(),
                    'is_open' => Location::isOpened(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get timeslots',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Clear the schedule (set to ASAP)
     */
    public function clearSchedule(): JsonResponse
    {
        try {
            if (Location::check()) {
                Location::updateScheduleTimeSlot(null, true);
            }

            return response()->json([
                'success' => true,
                'message' => 'Schedule cleared - set to ASAP',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear schedule',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
