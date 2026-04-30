<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $notifications = Notification::where('destinataire_id', $request->user()->id)
            ->latest()
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $notifications,
        ]);
    }

    public function marquerLue(Notification $notification): JsonResponse
    {
        if ($notification->destinataire_id !== auth()->id()) {
            abort(403);
        }

        $notification->update(['statut' => 'envoye']);

        return response()->json([
            'success' => true,
            'message' => 'Notification marquée comme lue.',
        ]);
    }

    public function nonLues(Request $request): JsonResponse
    {
        $count = Notification::where('destinataire_id', $request->user()->id)
            ->where('statut', 'en_attente')
            ->count();

        return response()->json([
            'success' => true,
            'data' => ['count' => $count],
        ]);
    }
}
