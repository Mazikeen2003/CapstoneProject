@php
    $documentStage = $project->lifecycle_stage ?: \App\Models\Project::syncLifecycleStageFromCurrentStatus($project->current_status);
    $documentTypes = match ($documentStage) {
        3 => ['Bid Submission', 'Technical Proposal', 'Eligibility Requirement'],
        4 => ['Signed Contract', 'Notice of Award', 'Purchase Order'],
        default => [],
    };
    $stageDocuments = $project->stageDocuments ?? collect();
@endphp

<section class="stage-documents-card mt-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
    <style>
        .stage-documents-card { color: #0f172a; }
        html.dark-mode .stage-documents-card,
        .dark .stage-documents-card {
            border-color: #334155;
            background: #141c2b;
            color: #f8fafc;
        }
        html.dark-mode .stage-documents-card h2,
        .dark .stage-documents-card h2,
        html.dark-mode .stage-documents-card li p:first-child,
        .dark .stage-documents-card li p:first-child { color: #f8fafc; }
        html.dark-mode .stage-documents-card p,
        .dark .stage-documents-card p { color: #cbd5e1; }
        html.dark-mode .stage-documents-card form,
        .dark .stage-documents-card form,
        html.dark-mode .stage-documents-card ul,
        .dark .stage-documents-card ul { border-color: #334155; }
        html.dark-mode .stage-documents-card label,
        .dark .stage-documents-card label { color: #e2e8f0; }
        html.dark-mode .stage-documents-card select,
        html.dark-mode .stage-documents-card input[type="file"],
        .dark .stage-documents-card select,
        .dark .stage-documents-card input[type="file"] {
            border-color: #475569;
            background: #0f172a;
            color: #f8fafc;
            color-scheme: dark;
        }
        html.dark-mode .stage-documents-card input[type="file"]::file-selector-button,
        .dark .stage-documents-card input[type="file"]::file-selector-button {
            border: 0;
            background: #334155;
            color: #f8fafc;
        }
        html.dark-mode .stage-documents-card .stage-document-empty,
        .dark .stage-documents-card .stage-document-empty {
            border-color: #475569;
            background: #0f172a;
            color: #cbd5e1;
        }
        html.dark-mode .stage-documents-card .stage-document-view,
        .dark .stage-documents-card .stage-document-view {
            border-color: #475569;
            color: #e2e8f0;
        }
        html.dark-mode .stage-documents-card .stage-document-view:hover,
        .dark .stage-documents-card .stage-document-view:hover { background: #1e293b; }
    </style>
    <div class="mb-5">
        <h2 class="text-lg font-bold text-slate-900">Bidding and Contract Documents</h2>
        <p class="mt-1 text-sm text-slate-600">Upload and securely share supporting documents for bidding and contract award.</p>
    </div>

    @if (session('stage_document_success'))
        <div role="status" class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('stage_document_success') }}</div>
    @endif

    @if (($allowUpload ?? false) && $documentTypes)
        <form method="POST" action="{{ route('department.projects.stage-documents.store', $project->project_id) }}" enctype="multipart/form-data" class="mb-6 grid gap-4 border-b border-slate-200 pb-6 sm:grid-cols-2">
            @csrf
            <div>
                <label for="stage-document-type" class="mb-1 block text-sm font-semibold text-slate-700">Document type</label>
                <select id="stage-document-type" name="document_type" required class="w-full rounded-lg border-slate-300 text-sm focus:border-amber-500 focus:ring-amber-500">
                    <option value="">Choose a document type</option>
                    @foreach ($documentTypes as $documentType)
                        <option value="{{ $documentType }}" @selected(old('document_type') === $documentType)>{{ $documentType }}</option>
                    @endforeach
                </select>
                @error('document_type')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="stage-document-file" class="mb-1 block text-sm font-semibold text-slate-700">File</label>
                <input id="stage-document-file" type="file" name="document" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png" required class="block w-full rounded-lg border border-slate-300 bg-white text-sm text-slate-700 file:mr-4 file:rounded-l-lg file:border-0 file:bg-slate-100 file:px-4 file:py-2.5 file:font-semibold">
                <p class="mt-1 text-xs text-slate-500">PDF, Office documents, or images. Maximum size: 20 MB.</p>
                @error('document')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
            </div>
            <div class="sm:col-span-2">
                <button type="submit" class="inline-flex items-center rounded-lg bg-amber-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">Upload document</button>
            </div>
        </form>
    @endif

    @if ($stageDocuments->isEmpty())
        <p class="stage-document-empty rounded-xl border border-dashed border-slate-300 bg-slate-50 px-4 py-6 text-center text-sm text-slate-500">No bidding or contract documents have been uploaded.</p>
    @else
        <ul class="divide-y divide-slate-200">
            @foreach ($stageDocuments as $document)
                <li class="flex flex-col gap-3 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="min-w-0">
                        <p class="truncate font-semibold text-slate-900">{{ $document->original_filename }}</p>
                        <p class="mt-1 text-xs text-slate-500">{{ $document->document_type }} · Stage {{ $document->stage }} · {{ $document->created_at?->format('M d, Y') }}</p>
                    </div>
                    <div class="flex shrink-0 items-center gap-2">
                        <a href="{{ route('department.projects.stage-documents.view', [$project->project_id, $document->id]) }}" target="_blank" rel="noopener" class="stage-document-view rounded-lg border border-slate-300 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">View</a>
                        <a href="{{ route('department.projects.stage-documents.download', [$project->project_id, $document->id]) }}" class="rounded-lg bg-slate-900 px-3 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">Download</a>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</section>
