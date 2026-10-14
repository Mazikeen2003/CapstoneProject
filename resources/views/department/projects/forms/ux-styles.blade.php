<style>
    .department-form-ux {
        max-width: 1120px;
        margin: 0 auto;
        padding: clamp(1rem, 3vw, 2rem);
        color: #0f172a;
    }
    .department-form-ux > div:first-of-type h1 {
        font-size: clamp(1.5rem, 3vw, 2rem);
        line-height: 1.2;
        letter-spacing: -0.025em;
    }
    .department-form-ux form > div.bg-white {
        border-color: #dbe3ee !important;
        border-radius: 1rem;
        box-shadow: 0 8px 24px rgb(15 23 42 / 0.06);
        padding: clamp(1rem, 2.5vw, 1.5rem);
    }
    .department-form-ux label {
        display: block;
        margin-bottom: 0.35rem;
        font-weight: 650;
        line-height: 1.45;
    }
    .department-form-ux input:not([type="checkbox"]):not([type="radio"]),
    .department-form-ux select,
    .department-form-ux textarea {
        width: 100%;
        min-height: 2.75rem;
        padding: 0.65rem 0.8rem;
        border: 1px solid #cbd5e1 !important;
        border-radius: 0.65rem;
        background: #fff;
        color: #0f172a !important;
        font: inherit;
        font-size: 0.925rem;
        transition: border-color 150ms ease, box-shadow 150ms ease;
    }
    .department-form-ux .currency-input-with-prefix {
        padding-left: 2.75rem !important;
    }
    .department-form-ux textarea {
        min-height: 6.5rem;
        resize: vertical;
    }
    .department-form-ux input[readonly] {
        background: #f1f5f9 !important;
        color: #475569 !important;
        cursor: not-allowed;
    }
    .department-form-ux input:focus,
    .department-form-ux select:focus,
    .department-form-ux textarea:focus {
        outline: none;
        border-color: #d97706 !important;
        box-shadow: 0 0 0 3px rgb(217 119 6 / 0.16);
    }
    .department-form-ux .overflow-x-auto {
        border: 1px solid #dbe3ee;
        border-radius: 0.75rem;
    }
    .department-form-ux .text-gray-500,
    .department-form-ux .text-slate-500 { color: #475569 !important; }
    .department-form-ux .text-gray-600,
    .department-form-ux .text-slate-600 { color: #334155 !important; }
    .department-form-ux .text-gray-700,
    .department-form-ux .text-slate-700 { color: #1e293b !important; }
    .department-form-ux table {
        min-width: 44rem;
    }
    .department-form-ux .flex.justify-end.space-x-3 {
        flex-wrap: wrap;
        gap: 0.65rem;
        padding-top: 0.5rem;
    }
    .department-form-ux .flex.justify-end.space-x-3 > * {
        margin-left: 0 !important;
    }
    .department-form-ux .project-form-action {
        min-height: 2.75rem;
        border-radius: 0.65rem;
        font-weight: 700;
    }
    html.dark-mode .department-form-ux,
    .dark .department-form-ux {
        color: #f8fafc;
    }
    html.dark-mode .department-form-ux label,
    html.dark-mode .department-form-ux h1,
    html.dark-mode .department-form-ux h2,
    html.dark-mode .department-form-ux h3,
    .dark .department-form-ux label,
    .dark .department-form-ux h1,
    .dark .department-form-ux h2,
    .dark .department-form-ux h3 {
        color: #f8fafc !important;
    }
    html.dark-mode .department-form-ux form > div.bg-white,
    .dark .department-form-ux form > div.bg-white {
        background: #141c2b !important;
        border-color: #334155 !important;
    }
    html.dark-mode .department-form-ux input:not([type="checkbox"]):not([type="radio"]),
    html.dark-mode .department-form-ux select,
    html.dark-mode .department-form-ux textarea,
    .dark .department-form-ux input:not([type="checkbox"]):not([type="radio"]),
    .dark .department-form-ux select,
    .dark .department-form-ux textarea {
        background: #0f172a;
        border-color: #475569 !important;
        color: #f8fafc !important;
        color-scheme: dark;
    }
    html.dark-mode .department-form-ux input[readonly],
    .dark .department-form-ux input[readonly] {
        background: #1e293b !important;
        color: #cbd5e1 !important;
    }
    html.dark-mode .department-form-ux .overflow-x-auto,
    .dark .department-form-ux .overflow-x-auto {
        border-color: #334155;
    }
    html.dark-mode .department-form-ux .text-gray-400,
    html.dark-mode .department-form-ux .text-gray-500,
    html.dark-mode .department-form-ux .text-gray-600,
    html.dark-mode .department-form-ux .text-gray-700,
    html.dark-mode .department-form-ux .text-slate-400,
    html.dark-mode .department-form-ux .text-slate-500,
    html.dark-mode .department-form-ux .text-slate-600,
    html.dark-mode .department-form-ux .text-slate-700,
    .dark .department-form-ux .text-gray-400,
    .dark .department-form-ux .text-gray-500,
    .dark .department-form-ux .text-gray-600,
    .dark .department-form-ux .text-gray-700,
    .dark .department-form-ux .text-slate-400,
    .dark .department-form-ux .text-slate-500,
    .dark .department-form-ux .text-slate-600,
    .dark .department-form-ux .text-slate-700 { color: #cbd5e1 !important; }
    @media (max-width: 640px) {
        .department-form-ux { padding: 1rem 0.75rem; }
        .department-form-ux .project-form-action { flex: 1 1 auto; justify-content: center; }
    }
</style>
