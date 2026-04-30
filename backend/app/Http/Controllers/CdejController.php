<?php

namespace App\Http\Controllers;

use App\Models\Cdej;
use Illuminate\Http\JsonResponse;

class CdejController extends Controller
{
    public function index(): JsonResponse
    {
        $cdejs = Cdej::with('cluster')->paginate(50);

        return response()->json([
            'success' => true,
            'data' => $cdejs,
            'meta' => ['pagination' => [
                'current_page' => $cdejs->currentPage(),
                'last_page' => $cdejs->lastPage(),
                'per_page' => $cdejs->perPage(),
                'total' => $cdejs->total(),
            ]],
        ]);
    }

    public function alumni(Cdej $cdej): JsonResponse
    {
        $alumni = $cdej->alumni()->with('user')->get();

        return response()->json([
            'success' => true,
            'data' => $alumni,
        ]);
    }
}
