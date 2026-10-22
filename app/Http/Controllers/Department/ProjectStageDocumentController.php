<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectStageDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use RuntimeException;
use Symfony\Component\HttpFoundation\HeaderUtils;

class ProjectStageDocumentController extends Controller
{
    public function store(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $stage = $this->stageFor($project);
        abort_unless(isset(ProjectStageDocument::DOCUMENT_TYPES[$stage]), 403, 'Documents can only be uploaded during the Proposed, bidding, or contract award stages.');

        $validated = $request->validate([
            'document_type' => ['required', Rule::in(ProjectStageDocument::DOCUMENT_TYPES[$stage])],
            'document' => ['required', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png', 'max:20480'],
            'is_public' => ['required', 'boolean'],
        ]);

        $file = $validated['document'];
        $path = $file->store('project-stage-documents/' . $project->project_id, 'local');
        if (! is_string($path) || $path === '') {
            throw new RuntimeException('The uploaded project document could not be stored.');
        }
        $originalFilename = preg_replace('/[\r\n\x00-\x1F\x7F]/u', '', $file->getClientOriginalName()) ?: 'project-document';

        $project->stageDocuments()->create([
            'uploaded_by' => Auth::id(),
            'stage' => $stage,
            'document_type' => $validated['document_type'],
            'original_filename' => Str::limit($originalFilename, 240, ''),
            'file_path' => $path,
            'mime_type' => $file->getMimeType() ?: 'application/octet-stream',
            'is_public' => $validated['is_public'],
        ]);

        return redirect()
            ->route('department.projects.show', $project->project_id)
            ->with('stage_document_success', 'Project document uploaded successfully.');
    }

    public function view(Project $project, ProjectStageDocument $document)
    {
        return $this->respondWithDocument($project, $document, false);
    }

    public function download(Project $project, ProjectStageDocument $document)
    {
        return $this->respondWithDocument($project, $document, true);
    }

    public function publicView(int $projectId, int $documentId)
    {
        $project = Project::withoutRoleScope()->findOrFail($projectId);
        $document = $project->stageDocuments()->findOrFail($documentId);

        abort_unless($document->is_public, 404);
        abort_unless(Storage::disk('local')->exists($document->file_path), 404);

        $path = Storage::disk('local')->path($document->file_path);
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_INLINE,
            $document->original_filename,
            'project-document-' . $document->getKey()
        );

        return response()->file($path, [
            'Content-Type' => $document->mime_type,
            'Content-Disposition' => $disposition,
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function respondWithDocument(Project $project, ProjectStageDocument $document, bool $download)
    {
        abort_unless((int) $document->project_id === (int) $project->project_id, 404);
        $this->authorize('view', $project);
        abort_unless(Storage::disk('local')->exists($document->file_path), 404);

        if ($download) {
            return Storage::disk('local')->download($document->file_path, $document->original_filename);
        }

        $path = Storage::disk('local')->path($document->file_path);
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_INLINE,
            $document->original_filename,
            'project-document-' . $document->getKey()
        );

        return response()->file($path, [
            'Content-Type' => $document->mime_type,
            'Content-Disposition' => $disposition,
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function stageFor(Project $project): int
    {
        return $project->current_status === 'On Hold'
            ? ($project->lifecycle_stage ?: Project::syncLifecycleStageFromCurrentStatus($project->statusBeforeOnHold()))
            : Project::syncLifecycleStageFromCurrentStatus($project->current_status);
    }
}
