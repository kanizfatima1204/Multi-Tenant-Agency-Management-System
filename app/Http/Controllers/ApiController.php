<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    public function token(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['required', 'string', 'max:100'],
        ]);

        $user = \App\Models\User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 422);
        }

        return response()->json([
            'token' => $user->createToken($data['device_name'])->plainTextToken,
            'user' => $user->only(['id', 'name', 'email', 'role', 'tenant_id']),
        ]);
    }

    public function me(Request $request)
    {
        return response()->json($request->user()->load('tenant')->only(['id', 'name', 'email', 'role', 'tenant_id']));
    }

    public function projects()
    {
        return response()->json(
            Project::with('tenant:id,name')->withCount(['tasks', 'updates'])->latest()->paginate(20)
        );
    }

    public function project(Project $project)
    {
        $this->authorize('view', $project);

        return response()->json($project->load(['tasks', 'files', 'updates.user:id,name']));
    }
}
