<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectFileController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $request->validate([
            'file' => ['required', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,webp,doc,docx,xls,xlsx,txt,csv'],
        ]);

        $file = $request->file('file');
        $path = $file->store("tenants/{$project->tenant_id}/projects/{$project->id}");

        ProjectFile::create([
            'tenant_id' => $project->tenant_id,
            'project_id' => $project->id,
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
        ]);

        return back()->with('success', 'File uploaded.');
    }

    public function download(ProjectFile $projectFile)
    {
        $this->authorize('view', $projectFile->project);

        abort_unless(Storage::exists($projectFile->path), 404);

        return Storage::download($projectFile->path, $projectFile->name);
    }

    public function destroy(ProjectFile $projectFile)
    {
        $this->authorize('update', $projectFile->project);
        Storage::delete($projectFile->path);
        $projectFile->delete();

        return back()->with('success', 'File deleted.');
    }
}
