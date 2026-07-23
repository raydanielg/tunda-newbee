<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Interest;
use Illuminate\Http\Request;

class InterestController extends Controller
{
    public function index()
    {
        $interests = Interest::orderBy('name')->get();

        $data = $interests->map(function ($i) {
            return [
                'id' => (string) $i->id,
                'name' => $i->name,
                'icon' => $i->icon,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function userInterests(Request $request)
    {
        $interests = $request->user()->interests;

        $data = $interests->map(function ($i) {
            return [
                'id' => (string) $i->id,
                'name' => $i->name,
                'icon' => $i->icon,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'interests' => 'required|array',
            'interests.*' => 'string',
        ]);

        $interestIds = [];
        foreach ($request->interests as $name) {
            $interest = Interest::firstOrCreate(['name' => $name]);
            $interestIds[] = $interest->id;
        }

        $request->user()->interests()->sync($interestIds);

        return response()->json([
            'success' => true,
            'message' => 'Interests updated',
        ]);
    }
}
