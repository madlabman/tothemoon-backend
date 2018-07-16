<?php

namespace App\Http\Controllers\API;

use App\Command;
use App\Http\Controllers\Controller;

class TeamController extends Controller
{
    public function index()
    {
        try {
            $teams = Command::latest()->get();
            return response()->json([
                'status' => 'success',
                'teams'  => $teams->map(function ($team) {
                    return [
                        'id'        => $team->id,
                        'name'      => $team->name,
                        'leader'    => $team->admin->name,
                        'is_leader' => $team->admin->id === auth()->user()->id,
                        'is_member' => $team->id === auth()->user()->team->id,
                    ];
                }),
            ]);
        } catch (\Exception $ex) {
            return response()->json([
                'status' => 'error',
            ], 500);
        }
    }

    public function join($id)
    {
        $team = Command::findOrFail($id);
        $user_team = auth()->user()->team;
        $managed_team = auth()->user()->team;

        if (!empty($user_team) && $team->id === $user_team->id) {
            return response()->json([
                'status' => 'success',
                'msg'    => 'Вы уже состоите в выбранной команде!',
            ]);
        }

        if (!empty($managed_team) && $team->id === $managed_team->id) {
            return response()->json([
                'status' => 'error',
                'msg'    => 'Вы не можете присоединиться к команде, которой управляете!',
            ], 500);
        }

        auth()->user()->team()->associate($team)->save();
        return response()->json([
            'status' => 'success',
            'msg'    => 'Вы успешно присоединились к выбранной команде!',
        ]);
    }
}