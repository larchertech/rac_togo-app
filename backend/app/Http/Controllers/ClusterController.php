<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use Illuminate\Http\JsonResponse;

class ClusterController extends Controller
{
    public function index(): JsonResponse
    {
        $clusters = Cluster::withCount('alumni')->get();

        return response()->json([
            'success' => true,
            'data' => $clusters,
        ]);
    }

    public function cdejs(Cluster $cluster): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $cluster->cdejs,
        ]);
    }
}
