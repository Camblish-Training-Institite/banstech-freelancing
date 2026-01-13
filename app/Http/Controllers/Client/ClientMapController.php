<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use Geocoder\Laravel\Facades\Geocoder;

class ClientMapController extends Controller
{
    public function showMap(Job $job)
    {
        // Load proposals and nested freelancer/profile
        $job->load(['proposals.user.profile']);

        $proposalsWithCoords = $job->proposals->map(function ($proposal) {
            $profile = $proposal->user->profile;
            $lat = null;
            $lng = null;

            // Check if profile exists and has an address
            if ($profile && $profile->address) {
                try {
                    // Use the Geocoder facade to get coordinates from the string address
                    $result = Geocoder::geocode($profile->address)->get()->first();
                    
                    if ($result) {
                        $lat = $result->getCoordinates()->getLatitude();
                        $lng = $result->getCoordinates()->getLongitude();
                    }
                } catch (\Exception $e) {
                    // Handle geocoding failure (e.g., invalid address) silently
                    \Log::error("Geocoding failed for profile ID: " . $profile->id);
                }
            }

            return [
                'id' => $proposal->id,
                'name' => $proposal->user->name,
                'user_id' => $proposal->user->id,
                'bid' => $proposal->bid_amount,
                'lat' => $lat,
                'lng' => $lng,
            ];
        })->filter(fn($p) => $p['lat'] !== null); // Only show freelancers we successfully geocoded

        return view('geo_location.client_map', [
            'job' => $job,
            'proposals' => $proposalsWithCoords
        ]);
    }
}
