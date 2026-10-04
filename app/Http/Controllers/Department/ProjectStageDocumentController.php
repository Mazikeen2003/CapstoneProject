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
    private const DOCUMENT_TYPES = [
        3 => ['Bid Submission', 'Technical Proposal', 'Eligibility Requirement'],
        4 => ['Signed Contract', 'Notice of Award', 'Purchase Order'],
    ];

    public function store(Request $request, Project $project)
    {
        $this->authorize('update', $project);

        $stage = $this->stageFor($project);
        abort_unless(isset(self::DOCUMENT_TYPES[$stage]), 403, 'Documents can only be uploaded during bidding or contract award.');

        $validated = $request->validate([
            'document_type' => ['required', Rule::in(self::DOCUMENT_TYPES[$stage])],
            'document' => ['required', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png', 'max:20480'],
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
        ]);

        return back()->with('stage_document_success', 'Project document uploaded successfully.');
    }

    public function view(Project $project, ProjectStageDocument $document)
    {
        return $this->respondWithDocument($project, $document, false);
    }

    public function download(Project $project, ProjectStageDocument $document)
    {
        return $this->respondWithDocument($project, $document, true);
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
        return $project->lifecycle_stage ?: Project::syncLifecycleStageFromCurrentStatus($project->current_status);
    }
}
