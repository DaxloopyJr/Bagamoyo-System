<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function logActivity($description, $subject = null, $event = null, $properties = null)
    {
        $data = [
            'log_name' => 'default',
            'description' => $description,
            'event' => $event,
            'causer_type' => auth()->check() ? get_class(auth()->user()) : null,
            'causer_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];

        if ($subject) {
            $data['subject_type'] = get_class($subject);
            $data['subject_id'] = $subject->id;
        }

        if ($properties) {
            $data['properties'] = is_array($properties) ? $properties : ['message' => $properties];
        }

        \App\Models\ActivityLog::create($data);
    }

    protected function successResponse($message, $redirect = null)
    {
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message]);
        }
        return redirect($redirect ?: url()->previous())->with('success', $message);
    }

    protected function errorResponse($message, $redirect = null)
    {
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => false, 'message' => $message], 422);
        }
        return redirect($redirect ?: url()->previous())->with('error', $message);
    }
}
