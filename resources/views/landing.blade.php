<!DOCTYPE html>
<html lang="en" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>City Transparency Portal | Cabuyao</title>
    @include('layouts.favicon')
    @include('components.theme-init')

    {{-- Fonts --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600&family=Public+Sans:wght@400;600;700&display=swap">
    {{-- Icons --}}
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .glass-nav {
            backdrop-filter: blur(16px);
            background-color: rgba(253, 252, 247, 0.82);
        }
        html.dark-mode .glass-nav {
            background-color: rgba(15, 23, 42, 0.48) !important;
            border-color: rgba(148, 163, 184, 0.18) !important;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .hero-gradient {
            background:
                linear-gradient(90deg, rgba(9, 20, 43, 0.9) 0%, rgba(9, 20, 43, 0.72) 42%, rgba(9, 20, 43, 0.24) 100%),
                linear-gradient(0deg, rgba(8, 13, 23, 0.56) 0%, transparent 58%);
        }
        .landing-hero-title {
            text-shadow: 0 3px 24px rgba(0, 0, 0, 0.32);
        }
        .landing-hero-title span {
            color: #cbd5e1;
        }
        .landing-hero-description {
            text-shadow: 0 2px 14px rgba(0, 0, 0, 0.42);
        }
        .landing-stats {
            isolation: isolate;
        }
        .landing-stats::before {
            position: absolute;
            z-index: -1;
            inset: 40px 0 0;
            background-color: #fdfcf7;
            content: "";
        }
        html.dark-mode body.landing-page {
            background-color: #111111 !important;
        }
        html.dark-mode body.landing-page .glass-nav {
            background-color: rgba(17, 17, 17, 0.82) !important;
            border-color: rgba(255, 255, 255, 0.08) !important;
        }
        html.dark-mode body.landing-page .landing-stats::before {
            background-color: #1a1a1a;
        }
        .map-guide-type-shape {
            display: inline-flex;
            width: 30px;
            height: 30px;
            flex: 0 0 30px;
            align-items: center;
            justify-content: center;
            background: #111827;
        }
        .map-guide-type-shape svg { width: 19px; height: 19px; }
        .map-guide-type-shape.bridges { clip-path: polygon(5% 20%,95% 20%,95% 38%,82% 38%,82% 57%,72% 57%,72% 45%,60% 45%,50% 55%,40% 45%,28% 45%,28% 57%,18% 57%,18% 38%,5% 38%); }
        .map-guide-type-shape.buildings { border-radius: 5px; }
        .map-guide-type-shape.flood-control { clip-path: polygon(50% 0,100% 50%,50% 100%,0 50%); }
        .map-guide-type-shape.roads { width: 38px; height: 24px; flex-basis: 38px; border-radius: 8px; }
        .map-guide-type-shape.septage { clip-path: polygon(25% 4%,75% 4%,100% 50%,75% 96%,25% 96%,0 50%); }
        .map-guide-type-shape.water { clip-path: polygon(50% 0,95% 55%,88% 78%,72% 95%,50% 100%,28% 95%,12% 78%,5% 55%); }
        .map-guide-type-shape.others { clip-path: polygon(50% 0,62% 35%,100% 38%,72% 61%,82% 100%,50% 77%,18% 100%,28% 61%,0 38%,38% 35%); }
        #features.landing-map-guide { background-color: #fdfcf7 !important; }
        .landing-map-guide .map-guide-panel {
            border-color: #e7e5dc;
            background-color: rgba(255, 255, 255, 0.92);
            box-shadow: 0 16px 40px -30px rgba(15, 23, 42, 0.4);
        }
        .map-guide-header {
            padding-bottom: 34px;
            border-bottom: 1px solid #e7e5dc;
        }
        .map-guide-eyebrow {
            color: #047857;
            font-size: 0.625rem;
            font-weight: 800;
            letter-spacing: 0.24em;
            text-transform: uppercase;
        }
        .map-guide-title {
            max-width: 38rem;
            margin-top: 10px;
            color: #111827;
            font-family: "Manrope", sans-serif;
            font-size: clamp(2.125rem, 4vw, 3rem);
            font-weight: 800;
            letter-spacing: -0.06em;
            line-height: 1.1;
        }
        .map-guide-intro {
            max-width: 39rem;
            margin-top: 16px;
            color: #475569;
            font-family: "Inter", sans-serif;
            font-size: 0.875rem;
            line-height: 1.85;
        }
        .landing-mission-item:nth-child(2) { --mission-accent: #17a9c6; }
        .landing-mission-item:nth-child(3) { --mission-accent: #db2777; }
        .landing-mission-item:nth-child(4) { --mission-accent: #0f766e; }
        .map-guide-panel {
            overflow: hidden;
            border-radius: 15px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .map-guide-panel:hover {
            border-color: #cbd5e1;
            box-shadow: 0 18px 36px -28px rgba(15, 23, 42, 0.42);
        }
        .map-guide-panel-heading {
            display: flex;
            min-height: 78px;
            align-items: center;
            gap: 14px;
            padding: 17px 20px 14px;
            border-bottom: 1px solid #e7e5dc;
            background: #fff;
            color: #111827;
        }
        .map-guide-panel-heading > span:first-child {
            display: flex;
            width: 38px;
            height: 38px;
            flex: 0 0 38px;
            align-items: center;
            justify-content: center;
            border-radius: 11px;
            background: var(--guide-icon-bg, #e6f4ed);
            color: var(--guide-icon-color, #047857);
        }
        .map-guide-panel-heading h3 {
            margin: 0;
            color: inherit;
            font-family: "Manrope", sans-serif;
            font-size: 1rem;
            font-weight: 800;
            letter-spacing: -0.025em;
        }
        .map-guide-panel-heading p {
            margin: 3px 0 0;
            color: #64748b;
            font-family: "Inter", sans-serif;
            font-size: 0.6875rem;
        }
        .map-guide-panel-count {
            margin-left: auto;
            border-radius: 999px;
            background: #f1f5f9;
            color: #475569;
            padding: 5px 10px;
            font-size: 0.625rem;
            font-weight: 800;
            white-space: nowrap;
        }
        .map-guide-status-panel { --guide-icon-bg: #e6f4ed; --guide-icon-color: #047857; }
        .map-guide-type-panel { --guide-icon-bg: #eff6ff; --guide-icon-color: #2563eb; }
        .map-guide-howto-panel { --guide-icon-bg: #fff7ed; --guide-icon-color: #c2410c; }
        .map-guide-status-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
            padding: 18px 20px 20px;
        }
        .map-guide-status-entry {
            display: flex;
            min-width: 0;
            align-items: center;
            gap: 11px;
            border: 1px solid #e7e5dc;
            border-radius: 9px;
            background: #fff;
            padding: 10px 12px;
            color: #334155;
            font-family: "Inter", sans-serif;
            font-size: 0.6875rem;
            font-weight: 700;
            line-height: 1.35;
            transition: border-color 0.16s ease, background 0.16s ease;
        }
        .map-guide-status-entry:hover,
        .map-guide-type-list > div:hover,
        .map-guide-howto-step:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
        }
        .map-guide-status-entry > span:first-child {
            width: 11px;
            height: 11px;
            flex: 0 0 11px;
            border-radius: 50%;
            box-shadow: 0 0 0 3px rgba(15, 23, 42, 0.08);
        }
        .map-guide-type-list {
            gap: 8px;
            padding: 18px 20px 20px;
        }
        .map-guide-type-list > div {
            display: flex;
            min-width: 0;
            align-items: center;
            gap: 10px;
            min-height: 48px;
            border: 1px solid #e7e5dc;
            border-radius: 9px;
            background: #fff;
            padding: 8px 11px;
            color: #334155;
            font-family: "Inter", sans-serif;
            font-size: 0.6875rem;
            font-weight: 700;
            line-height: 1.35;
            transition: border-color 0.16s ease, background 0.16s ease;
        }
        .map-guide-type-list .map-guide-type-shape {
            --project-marker-color: #111827;
            width: 30px;
            height: 30px;
            flex-basis: 30px;
            flex-shrink: 0;
        }
        .map-guide-type-list .map-guide-type-shape.roads {
            width: 38px;
            flex-basis: 38px;
            height: 24px;
            border-radius: 8px;
        }
        .map-guide-howto-list {
            display: grid;
            gap: 10px;
            padding: 18px 20px 20px;
        }
        .map-guide-howto-step {
            display: flex;
            align-items: flex-start;
            gap: 13px;
            border: 1px solid #e7e5dc;
            border-radius: 11px;
            background: #fff;
            padding: 14px 15px;
            color: #475569;
            font-family: "Inter", sans-serif;
            font-size: 0.75rem;
            line-height: 1.6;
            transition: border-color 0.16s ease, background 0.16s ease;
        }
        .map-guide-howto-step > span:first-child {
            display: flex;
            width: 34px;
            height: 34px;
            flex: 0 0 34px;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #fef3c7;
            color: #92400e;
            font-family: "Manrope", sans-serif;
            font-size: 0.875rem;
            font-weight: 800;
        }
        .map-guide-howto-step strong {
            display: block;
            margin: 1px 0 3px;
            color: #0f172a;
            font-family: "Manrope", sans-serif;
            font-size: 0.8125rem;
            font-weight: 800;
        }
        html.dark-mode body.landing-page main {
            background-color: #1a1a1a !important;
        }
        html.dark-mode body.landing-page #features.landing-map-guide {
            background-color: #1a1a1a !important;
        }
        html.dark-mode body.landing-page .map-guide-header {
            border-color: #303030;
        }
        html.dark-mode body.landing-page .map-guide-eyebrow {
            color: #6ee7b7;
        }
        html.dark-mode body.landing-page .map-guide-title {
            color: #f8fafc;
        }
        html.dark-mode body.landing-page .map-guide-intro {
            color: #cbd5e1;
        }
        html.dark-mode body.landing-page .map-guide-status-entry,
        html.dark-mode body.landing-page .map-guide-type-list > div,
        html.dark-mode body.landing-page .map-guide-howto-step {
            border-color: #303030;
            background: #1a1a1a;
            color: #cbd5e1;
        }
        html.dark-mode body.landing-page .map-guide-status-entry:hover,
        html.dark-mode body.landing-page .map-guide-type-list > div:hover,
        html.dark-mode body.landing-page .map-guide-howto-step:hover {
            border-color: #475569;
            background: #222;
        }
        html.dark-mode body.landing-page .map-guide-howto-step > span:first-child {
            background: rgba(245, 158, 11, 0.18);
            color: #fcd34d;
        }
        html.dark-mode body.landing-page .map-guide-howto-step strong {
            color: #f8fafc;
        }
        html.dark-mode body.landing-page .map-guide-panel {
            border-color: #303a35;
            background-color: #1c2420;
        }
        html.dark-mode body.landing-page .map-guide-panel:hover {
            border-color: #47574e;
        }
        html.dark-mode body.landing-page .map-guide-panel-heading {
            border-color: #303a35;
            background-color: #1c2420;
            color: #f8fafc;
        }
        html.dark-mode body.landing-page .map-guide-panel-heading p {
            color: #94a3b8 !important;
        }
        html.dark-mode body.landing-page .map-guide-panel-count {
            background: #27352e;
            color: #cbd5e1;
        }
        html.dark-mode body.landing-page .map-guide-status-panel {
            --guide-icon-bg: rgba(52, 211, 153, 0.14);
            --guide-icon-color: #6ee7b7;
        }
        html.dark-mode body.landing-page .map-guide-type-panel {
            --guide-icon-bg: rgba(96, 165, 250, 0.14);
            --guide-icon-color: #93c5fd;
        }
        html.dark-mode body.landing-page .map-guide-howto-panel {
            --guide-icon-bg: rgba(251, 146, 60, 0.14);
            --guide-icon-color: #fdba74;
        }
        .map-guide-open-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border-radius: 999px;
            background: #111827;
            padding: 12px 19px;
            color: #fff;
            font-family: "Manrope", sans-serif;
            font-size: 0.75rem;
            font-weight: 800;
            text-decoration: none;
            transition: transform 0.18s ease, background 0.18s ease;
        }
        .map-guide-open-button:hover {
            transform: translateY(-1px);
            background: #047857;
        }
        .map-guide-open-button:focus-visible {
            outline: 3px solid rgba(16, 185, 129, 0.45);
            outline-offset: 3px;
        }
        html.dark-mode body.landing-page .map-guide-open-button {
            background: #f8fafc;
            color: #111827;
        }
        html.dark-mode body.landing-page .map-guide-open-button:hover {
            background: #a7f3d0;
        }
        body.landing-page > footer.landing-footer {
            background-color: #111111 !important;
            border-color: rgba(255, 255, 255, 0.12) !important;
            color: #e2e8f0;
        }
        body.landing-page > footer.landing-footer .landing-footer-muted {
            color: #94a3b8;
        }
        body.landing-page > footer.landing-footer a {
            color: #cbd5e1;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        body.landing-page > footer.landing-footer a:hover {
            color: #6ee7b7;
        }
        body.landing-page > footer.landing-footer .landing-footer-bottom {
            border-color: rgba(255, 255, 255, 0.12);
        }
        .landing-vision-section {
            background-color: #f5f4ee;
            color: #111827;
            font-family: "Inter", sans-serif;
        }
        .landing-direction-card {
            border: 1px solid #e7e5dc;
            background: rgba(255, 255, 255, 0.9);
        }
        .landing-direction-icon {
            background: #e6f4ed;
            color: #047857;
        }
        .landing-direction-eyebrow {
            color: #64748b;
            font-size: 0.625rem;
            font-weight: 700;
            letter-spacing: 0.22em;
            text-transform: uppercase;
        }
        .landing-direction-title {
            max-width: 34rem;
            margin-top: 16px;
            color: #111827;
            font-family: "Manrope", sans-serif;
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            letter-spacing: -0.055em;
            line-height: 1.13;
        }
        .landing-vision-callout {
            margin-top: 28px;
            border-left: 4px solid #f59e0b;
            border-radius: 0 14px 14px 0;
            background: #fffbeb;
            padding: 20px 22px;
            color: #92400e;
        }
        .landing-vision-callout-label {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            color: #92400e;
            font-size: 0.875rem;
            font-weight: 800;
        }
        .landing-vision-callout-label .material-symbols-outlined {
            color: #d97706;
            font-size: 20px;
        }
        .landing-vision-callout p {
            margin: 0;
            font-size: 0.9375rem;
            line-height: 1.8;
        }
        .landing-mission-heading {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 20px;
        }
        .landing-mission-heading h3 {
            margin: 0;
            color: #111827;
            font-family: "Manrope", sans-serif;
            font-size: 1.375rem;
            font-weight: 800;
            letter-spacing: -0.03em;
        }
        .landing-mission-heading p {
            margin: 4px 0 0;
            color: #64748b;
            font-size: 0.75rem;
        }
        .landing-mission-list {
            position: relative;
            display: grid;
            gap: 6px;
            margin: 0;
            padding: 0;
            list-style: none;
        }
        .landing-mission-item {
            --mission-accent: #e9233d;
            position: relative;
            display: grid;
            grid-template-columns: 42px minmax(0, 1fr);
            align-items: start;
            gap: 12px;
            border: 1px solid #e7e5dc;
            border-radius: 13px;
            background: rgba(255, 255, 255, 0.88);
            padding: 15px 17px;
            color: #334155;
            transition: border-color 0.18s ease, background 0.18s ease, transform 0.18s ease, box-shadow 0.18s ease;
        }
        .landing-mission-item:hover {
            transform: translateY(-1px);
            border-color: #cbd5e1;
            background: #fff;
            box-shadow: 0 8px 20px -16px rgba(15, 23, 42, 0.4);
        }
        .landing-mission-node {
            z-index: 1;
            display: flex;
            width: 38px;
            height: 26px;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 999px;
            background: var(--mission-accent);
            color: #fff;
            font-family: "Manrope", sans-serif;
            font-size: 0.625rem;
            font-weight: 800;
        }
        .landing-mission-item p {
            margin: 2px 0 0;
            color: #475569;
            font-family: "Inter", sans-serif;
            font-size: 0.8125rem;
            line-height: 1.75;
        }
        html.dark-mode body.landing-page .landing-vision-section {
            background-color: #111111;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }
        html.dark-mode body.landing-page .landing-vision-section .landing-direction-card,
        html.dark-mode body.landing-page .landing-vision-section .landing-mission-item {
            border-color: #303a35 !important;
            background-color: #1c2420 !important;
            color: #e2e8f0;
        }
        html.dark-mode body.landing-page .landing-vision-section .landing-mission-item:hover {
            border-color: rgba(52, 211, 153, 0.42) !important;
            background-color: #202d27 !important;
        }
        html.dark-mode body.landing-page .landing-vision-section .landing-direction-icon {
            background-color: rgba(52, 211, 153, 0.14) !important;
            color: #6ee7b7 !important;
        }
        html.dark-mode body.landing-page .landing-vision-section .landing-mission-node {
            background-color: var(--mission-accent);
            color: #fff;
        }
        html.dark-mode body.landing-page .landing-vision-section .text-emerald-700 {
            color: #6ee7b7 !important;
        }
        html.dark-mode body.landing-page .landing-vision-section .landing-vision-divider {
            border-color: #303a35 !important;
        }
        html.dark-mode body.landing-page .landing-direction-title,
        html.dark-mode body.landing-page .landing-mission-heading h3 {
            color: #f8fafc;
        }
        html.dark-mode body.landing-page .landing-direction-eyebrow,
        html.dark-mode body.landing-page .landing-mission-heading p {
            color: #94a3b8;
        }
        html.dark-mode body.landing-page .landing-vision-callout {
            border-color: #f59e0b;
            background: rgba(245, 158, 11, 0.1);
            color: #fde68a;
        }
        html.dark-mode body.landing-page .landing-vision-callout-label {
            color: #fcd34d;
        }
        html.dark-mode body.landing-page .landing-mission-item { color: #cbd5e1; }
        html.dark-mode body.landing-page .landing-mission-item:hover {
            border-color: rgba(245, 158, 11, 0.35);
            background: rgba(245, 158, 11, 0.08);
        }
        html.dark-mode body.landing-page .landing-mission-node {
            border-color: #475569;
            background: #1e293b;
            color: #e2e8f0;
        }
        html.dark-mode body.landing-page .landing-vision-section .text-slate-950,
        html.dark-mode body.landing-page .landing-vision-section .text-slate-900 {
            color: #f8fafc !important;
        }
        html.dark-mode body.landing-page .landing-vision-section .text-slate-700,
        html.dark-mode body.landing-page .landing-vision-section .text-slate-600 {
            color: #cbd5e1 !important;
        }
        html.dark-mode body.landing-page .landing-vision-section .text-slate-500 {
            color: #94a3b8 !important;
        }
        html.dark-mode body.landing-page .landing-mission-item p {
            color: #cbd5e1;
        }
        html.dark-mode body.landing-page .landing-vision-section .border-slate-300,
        html.dark-mode body.landing-page .landing-vision-section .border-slate-200 {
            border-color: #37443d !important;
        }
        html.dark-mode body.landing-page .landing-vision-section .bg-emerald-50 {
            background-color: rgba(52, 211, 153, 0.14) !important;
            color: #6ee7b7 !important;
        }
        html.dark-mode body.landing-page .landing-stats .bg-white {
            background-color: #1a1a1a !important;
            border-color: #303030 !important;
            box-shadow: 0 20px 25px -8px rgba(0, 0, 0, 0.55) !important;
        }
        html.dark-mode body.landing-page .landing-primary-cta {
            background-color: #fff !important;
            color: #0f172a !important;
        }
        html.dark-mode body.landing-page .landing-primary-cta:hover {
            background-color: #f1f5f9 !important;
        }
        html.dark-mode body.landing-page .landing-login-button {
            border: 1px solid #065f46 !important;
            background-color: #222222 !important;
            color: #e5e7eb !important;
        }
        html.dark-mode body.landing-page .landing-login-button:hover {
            background-color: #1a1a1a !important;
            border-color: #047857 !important;
        }
        html.dark-mode body.landing-page #features section[aria-labelledby] {
            background-color: #222222 !important;
            border-color: #303030 !important;
        }
        @media (max-width: 640px) {
            .map-guide-type-list { grid-template-columns: 1fr !important; }
        }
        @media (min-width: 640px) {
            .landing-stats::before { top: 56px; }
        }
        @media (min-width: 1024px) {
            .landing-stats::before { top: 80px; }
        }
    </style>
</head>
<body class="public-layout landing-page bg-white font-sans text-slate-900 antialiased">

    {{-- ============ TOP NAV ============ --}}
    <header class="sticky top-0 z-50 glass-nav w-full border-b border-slate-200/50">
        <nav class="relative flex items-center justify-between w-full mx-auto px-4 py-3 sm:px-6 sm:py-4 lg:px-12">
            <div class="flex items-center gap-2 sm:gap-4 min-w-0">
                <img src="{{ asset('images/CPDC LOGO.png') }}" alt="Project Tracker System Logo" class="h-9 w-9 sm:h-10 sm:w-10 shrink-0 rounded-lg object-contain" width="40" height="40" decoding="async" />
                <div class="flex flex-col min-w-0">
                    <span class="text-base sm:text-lg md:text-xl font-bold tracking-tighter text-slate-900 leading-tight" style="font-family:'Manrope',sans-serif;">City Transparency Portal</span>
                    <span class="text-[9px] sm:text-[10px] uppercase tracking-widest text-slate-500 opacity-70 truncate" style="font-family:'Public Sans',sans-serif;">Cabuyao Municipal Office</span>
                </div>
            </div>

            <div class="hidden md:flex absolute left-1/2 -translate-x-1/2 items-center text-xs uppercase tracking-widest gap-6" style="font-family:'Public Sans',sans-serif;">
                <a href="{{ url('/') }}" class="text-emerald-700 font-bold border-b-2 border-emerald-600 py-2 transition-all">Home</a>
                <a href="{{ route('public.map') }}" class="text-slate-500 hover:text-emerald-700 transition-colors py-2 font-semibold">Public Map</a>
                <a href="{{ route('public.analytics') }}" class="text-slate-500 hover:text-emerald-700 transition-colors py-2 font-semibold">Analytics</a>
            </div>

            <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                @include('components.public-theme-toggle')
                <a href="{{ route('login') }}" class="public-login-button landing-login-button bg-slate-900 text-white px-3.5 py-2 sm:px-5 sm:py-2.5 rounded-md font-semibold text-xs sm:text-sm hover:opacity-90 transition-all duration-200 shrink-0">Login</a>
            </div>
        </nav>
    </header>

    <main>
        {{-- ============ HERO ============ --}}
        <section id="home" class="relative flex min-h-[560px] items-center overflow-hidden sm:min-h-[620px] lg:min-h-[680px]">
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 hero-gradient z-10"></div>
                <img src="{{ asset('images/hero-cabuyao.jpg') }}"
                     alt=""
                     aria-hidden="true"
                     class="absolute inset-0 h-full w-full object-cover"
                     style="object-position: center 30%;">
            </div>

            <div class="relative z-20 mx-auto w-full max-w-7xl px-6 py-14 text-white sm:py-20 lg:py-24">
                <div class="mx-auto flex w-full max-w-4xl flex-col items-center space-y-6 text-center sm:space-y-7 lg:space-y-8">
                    <div class="mb-1 flex items-center gap-3 sm:gap-4">
                        <div class="flex h-16 w-16 items-center justify-center sm:h-[4.5rem] sm:w-[4.5rem]">
                            <img src="{{ asset('images/cabuyao-logo.png') }}" alt="Cabuyao City logo" class="h-full w-full rounded-full object-contain" width="64" height="64" decoding="async">
                        </div>
                        <div class="flex h-24 w-24 items-center justify-center sm:h-28 sm:w-28">
                            <img src="{{ asset('images/CPDC LOGO.png') }}" alt="Project Tracker System Logo" class="h-20 w-20 rounded-xl object-contain sm:h-24 sm:w-24" width="96" height="96" decoding="async" />
                        </div>
                        <div class="flex h-16 w-16 items-center justify-center sm:h-[4.5rem] sm:w-[4.5rem]">
                            <img src="{{ asset('images/pnc-logo.png') }}" alt="Pamantasan ng Cabuyao logo" class="h-full w-full rounded-full object-contain" width="64" height="64" decoding="async">
                        </div>
                    </div>

                    <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-emerald-200 sm:text-xs" style="font-family:'Public Sans',sans-serif;">Projects · Budgets · Community</p>

                    <h1 class="landing-hero-title max-w-4xl text-4xl font-extrabold leading-[1.05] tracking-tight sm:text-5xl lg:text-7xl" style="font-family:'Manrope',sans-serif;">
                        Cabuyao City <br>
                        <span>Project Tracker System</span>
                    </h1>

                    <p class="landing-hero-description max-w-xl text-base leading-relaxed text-slate-200 sm:text-lg">
                        Empowering citizens with real-time access to official city reports, public budgets, and community project tracking.
                    </p>

                    <div class="flex w-full flex-col gap-3 pt-2 sm:w-auto sm:flex-row sm:gap-4 lg:pt-3">
                        <a href="{{ route('public.map') }}"
                            class="landing-primary-cta px-8 py-4 bg-white text-slate-900 font-bold rounded-md hover:bg-slate-100 transition-all flex items-center justify-center gap-2 shadow-xl">
                            <span class="material-symbols-outlined">map</span>
                            View Public Map
                        </a>
                        <a href="{{ route('public.analytics') }}"
                            class="px-8 py-4 bg-transparent border-2 border-white/40 text-white font-bold rounded-md hover:bg-white/10 transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined">analytics</span>
                            View Analytics
                        </a>
                    </div>
                </div>
            </div>

        </section>

        {{-- ============ LIVE STATS (overlapping hero) ============ --}}
        <section class="landing-stats relative z-20 -mt-10 flex justify-center bg-transparent px-4 py-5 sm:-mt-14 sm:px-6 sm:py-7 lg:-mt-20 lg:px-8 lg:py-8">
            <div class="mx-auto w-full max-w-7xl">
                <div class="grid grid-cols-2 gap-3 sm:gap-4 md:grid-cols-4 lg:gap-6">
                    <div class="flex flex-col items-center justify-center rounded-xl border border-slate-200/60 bg-white p-4 text-center shadow-[0_20px_25px_-8px_rgba(0,0,0,0.15)] sm:p-6 md:p-8">
                        <span class="mb-2 text-2xl font-extrabold text-emerald-700 sm:text-3xl md:text-4xl" id="totalProjects" style="font-family:'Manrope',sans-serif;">0</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500 sm:text-xs" style="font-family:'Public Sans',sans-serif;">Total Projects</span>
                    </div>
                    <div class="flex flex-col items-center justify-center rounded-xl border border-slate-200/60 bg-white p-4 text-center shadow-[0_20px_25px_-8px_rgba(0,0,0,0.15)] sm:p-6 md:p-8">
                        <span class="mb-2 text-2xl font-extrabold text-emerald-700 sm:text-3xl md:text-4xl" id="completedProjects" style="font-family:'Manrope',sans-serif;">0</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500 sm:text-xs" style="font-family:'Public Sans',sans-serif;">Completed</span>
                    </div>
                    <div class="flex flex-col items-center justify-center rounded-xl border border-slate-200/60 bg-white p-4 text-center shadow-[0_20px_25px_-8px_rgba(0,0,0,0.15)] sm:p-6 md:p-8">
                        <span class="mb-2 text-2xl font-extrabold text-emerald-700 sm:text-3xl md:text-4xl" id="ongoingProjects" style="font-family:'Manrope',sans-serif;">0</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500 sm:text-xs" style="font-family:'Public Sans',sans-serif;">Ongoing</span>
                    </div>
                    <div class="flex flex-col items-center justify-center rounded-xl border border-slate-200/60 bg-white p-4 text-center shadow-[0_20px_25px_-8px_rgba(0,0,0,0.15)] sm:p-6 md:p-8">
                        <span class="mb-2 text-2xl font-extrabold text-emerald-700 sm:text-3xl md:text-4xl" id="totalBudget" style="font-family:'Manrope',sans-serif;">₱0</span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500 sm:text-xs" style="font-family:'Public Sans',sans-serif;">Budget Allocated</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- ============ PUBLIC MAP GUIDE ============ --}}
        <section id="features" class="landing-map-guide bg-white px-4 py-16 sm:px-6 sm:py-20 lg:px-8">
            <div class="mx-auto max-w-6xl">
                <div class="map-guide-header mb-9 flex flex-col gap-5 sm:mb-11 sm:flex-row sm:items-end sm:justify-between">
                    <div class="max-w-2xl">
                        <span class="map-guide-eyebrow mb-3 block" style="font-family:'Public Sans',sans-serif;">Public Map Guide</span>
                        <h2 class="map-guide-title">Read the map at a glance</h2>
                        <p class="map-guide-intro">Pin color shows project status. Pin shape shows project type. Use the steps below to explore projects around Cabuyao.</p>
                    </div>
                    <a href="{{ route('public.map') }}" class="map-guide-open-button shrink-0 self-start sm:self-auto">
                        Open the Public Map <span class="material-symbols-outlined text-lg" aria-hidden="true">arrow_forward</span>
                    </a>
                </div>

                <div class="grid items-stretch gap-5 lg:grid-cols-2">
                    <section class="map-guide-panel map-guide-status-panel rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="map-status-legend-title">
                        <div class="map-guide-panel-heading">
                            <span>
                                <span class="material-symbols-outlined">palette</span>
                            </span>
                            <div>
                                <h3 id="map-status-legend-title" class="font-bold text-slate-900">Project status</h3>
                                <p class="text-xs text-slate-500">Read the pin color</p>
                            </div>
                            <span class="map-guide-panel-count">8 statuses</span>
                        </div>
                        <div class="map-guide-status-grid">
                            <div class="map-guide-status-entry"><span class="bg-[#2563eb]"></span>Proposed</div>
                            <div class="map-guide-status-entry"><span class="bg-[#f59e0b]"></span>For bidding</div>
                            <div class="map-guide-status-entry"><span class="bg-[#06b6d4]"></span>Bidding ongoing</div>
                            <div class="map-guide-status-entry"><span class="bg-[#8b5cf6]"></span>Award of contract</div>
                            <div class="map-guide-status-entry"><span class="bg-[#0f766e]"></span>Implementation</div>
                            <div class="map-guide-status-entry"><span class="bg-[#16a34a]"></span>Completed</div>
                            <div class="map-guide-status-entry"><span class="bg-[#dc2626]"></span>On Hold</div>
                            <div class="map-guide-status-entry"><span class="bg-[#64748b]"></span>Cancelled</div>
                        </div>
                    </section>

                    <section class="map-guide-panel map-guide-type-panel rounded-2xl border border-slate-200 bg-white shadow-sm" aria-labelledby="map-type-legend-title">
                        <div class="map-guide-panel-heading">
                            <span>
                                <span class="material-symbols-outlined">category</span>
                            </span>
                            <div>
                                <h3 id="map-type-legend-title" class="font-bold text-slate-900">Project type</h3>
                                <p class="text-xs text-slate-500">Read the pin shape</p>
                            </div>
                            <span class="map-guide-panel-count">7 types</span>
                        </div>
                        <div class="map-guide-type-list grid grid-cols-2">
                            <div class="flex items-center gap-2"><span class="map-guide-type-shape bridges" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 18h18M5 18V9m14 9V9M4 9h16M7 9c0 4 2.2 6 5 6s5-2 5-6M8 5v2m8-2v2"/></svg></span>Bridges</div>
                            <div class="flex items-center gap-2"><span class="map-guide-type-shape buildings" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M5 20V5h14v15M3 20h18M9 9h2m2 0h2M9 13h2m2 0h2M10 20v-3h4v3"/></svg></span>Buildings and Facilities</div>
                            <div class="flex items-center gap-2"><span class="map-guide-type-shape flood-control" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7c2 0 2-2 4-2s2 2 4 2 2-2 4-2 2 2 4 2 2-2 3-2M3 12c2 0 2-2 4-2s2 2 4 2 2-2 4-2 2 2 4 2 2-2 3-2M3 17c2 0 2-2 4-2s2 2 4 2 2-2 4-2 2 2 4 2 2-2 3-2"/></svg></span>Flood Control and Drainage</div>
                            <div class="flex items-center gap-2"><span class="map-guide-type-shape roads" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 3 6 21m9-18 3 18M12 5v3m0 4v3m0 4v2"/></svg></span>Roads</div>
                            <div class="flex items-center gap-2"><span class="map-guide-type-shape septage" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M6 7h12v13H6zM9 7V4h6v3M9 11h6m-6 4h6m-3 5v-3m0-13v3"/></svg></span>Septage and Sewerage</div>
                            <div class="flex items-center gap-2"><span class="map-guide-type-shape water" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3s6 6.5 6 11a6 6 0 1 1-12 0c0-4.5 6-11 6-11Z"/><path d="M9 15a3 3 0 0 0 3 3"/></svg></span>Water Provision and Storage</div>
                            <div class="flex items-center gap-2"><span class="map-guide-type-shape others" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3 2.7 5.5 6.1.9-4.4 4.3 1 6.1-5.4-2.9-5.4 2.9 1-6.1-4.4-4.3 6.1-.9L12 3Z"/></svg></span>Others</div>
                        </div>
                    </section>

                    <section class="map-guide-panel map-guide-howto-panel rounded-2xl border border-slate-200 bg-white shadow-sm lg:col-span-2" aria-labelledby="map-howto-title">
                        <div class="map-guide-panel-heading">
                            <span>
                                <span class="material-symbols-outlined">touch_app</span>
                            </span>
                            <div>
                                <h3 id="map-howto-title" class="font-bold text-slate-900">How to explore</h3>
                                <p class="text-xs text-slate-500">A few simple steps</p>
                            </div>
                            <span class="map-guide-panel-count">3 steps</span>
                        </div>
                        <ol class="map-guide-howto-list md:grid-cols-3">
                            <li class="map-guide-howto-step"><span>01</span><span><strong>Choose a barangay</strong>Its numbered badge shows how many public projects it has.</span></li>
                            <li class="map-guide-howto-step"><span>02</span><span><strong>Open a project</strong>Select a map pin or a project in the list to see its details.</span></li>
                            <li class="map-guide-howto-step"><span>03</span><span><strong>Read the map</strong>Use the legend and Light/Dark controls to understand markers and adjust the view.</span></li>
                        </ol>
                    </section>
                </div>
            </div>
        </section>

        {{-- ============ MISSION / VISION CTA ============ --}}
        <section class="landing-vision-section relative overflow-hidden px-4 py-16 sm:px-6 sm:py-20 lg:px-8">
            <div class="relative mx-auto max-w-6xl">
                <div class="landing-vision-divider mb-10 flex flex-col justify-between gap-6 border-b border-slate-300/70 pb-8 lg:mb-12 lg:flex-row lg:items-end">
                    <div class="max-w-2xl">
                        <span class="landing-direction-eyebrow flex items-center gap-2"><span class="material-symbols-outlined text-sm" aria-hidden="true">groups</span>City Planning &amp; Development</span>
                        <h2 class="landing-direction-title">A clear vision for Cabuyao — and a mission to deliver it.</h2>
                        <p class="landing-vision-description mt-4 max-w-xl text-sm leading-7 text-slate-600">The principles that guide sustainable development and responsive planning in Cabuyao City.</p>
                    </div>
                    <div class="flex items-center gap-3 lg:border-l lg:border-slate-300 lg:pl-5">
                        <span class="landing-direction-icon flex h-10 w-10 items-center justify-center rounded-full">
                            <span class="material-symbols-outlined">explore</span>
                        </span>
                        <span>
                            <span class="block text-[10px] font-bold uppercase tracking-[0.2em] text-emerald-700">Planning framework</span>
                            <span class="mt-1 block text-xs uppercase tracking-widest text-slate-500">Cabuyao City</span>
                        </span>
                    </div>
                </div>

                <div class="grid items-start gap-8 lg:grid-cols-[0.85fr_1.15fr] lg:gap-12">
                    <article class="landing-direction-card landing-vision-card rounded-2xl p-6 shadow-sm sm:p-8">
                        <div class="mb-6 flex items-center gap-3">
                            <span class="landing-direction-icon landing-vision-icon flex h-10 w-10 items-center justify-center rounded-full">
                                <span class="material-symbols-outlined text-2xl">visibility</span>
                            </span>
                            <span>
                                <span class="landing-direction-eyebrow block">Our vision</span>
                                <span class="mt-1 block text-xs text-slate-500">The future we are working toward</span>
                            </span>
                        </div>
                        <p class="text-lg font-semibold leading-8 text-slate-900 sm:text-xl sm:leading-9" style="font-family:'Manrope',sans-serif;">
                            The implementation of sustainable development planning towards an entrepreneurial, progressive and environment-friendly City of Cabuyao.
                        </p>
                        <div class="mt-7 flex items-center gap-3 border-t border-slate-200 pt-5 text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-500">
                            <span class="h-px w-8 bg-emerald-600"></span>
                            <span>Planning framework · Cabuyao City</span>
                        </div>
                    </article>

                    <div>
                        <div class="mb-5 flex items-end justify-between gap-4">
                            <div>
                                <span class="landing-direction-eyebrow">Planning in action</span>
                                <h3 class="mt-1 text-xl font-bold text-slate-950" style="font-family:'Manrope',sans-serif;">Our mission</h3>
                                <p class="mt-1 text-xs text-slate-500">Four commitments guiding the city’s development</p>
                            </div>
                            <span class="shrink-0 text-[10px] font-bold tracking-[0.16em] text-emerald-700">01 — 04</span>
                        </div>
                        <ol class="landing-mission-list grid gap-3">
                            <li class="landing-mission-item">
                                <span class="landing-mission-node">01</span>
                                <p>To formulate sound plans and programs participated by all sectors;</p>
                            </li>
                            <li class="landing-mission-item">
                                <span class="landing-mission-node">02</span>
                                <p>To implement Comprehensive Land Use Plan (CLUP) taking into consideration the protection of environment and efficient services to humanity with the continued support of hardworking staff;</p>
                            </li>
                            <li class="landing-mission-item">
                                <span class="landing-mission-node">03</span>
                                <p>To ensure sustainability in development planning and implementation;</p>
                            </li>
                            <li class="landing-mission-item">
                                <span class="landing-mission-node">04</span>
                                <p>To provide the public with data and information, best planning regulatory and monitoring services for the development of the City.</p>
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- ============ FOOTER ============ --}}
    <footer class="landing-footer border-t">
        <div class="mx-auto grid w-full max-w-6xl gap-10 px-6 py-12 sm:px-8 md:grid-cols-[1.4fr_0.8fr_0.8fr_1fr] md:gap-12 md:py-16">
            <div>
                <a href="{{ url('/') }}" class="mb-5 inline-flex items-center gap-3">
                    <img src="{{ asset('images/cabuyao-logo.png') }}" alt="" class="h-9 w-9 rounded-full bg-white object-contain p-0.5" width="36" height="36" decoding="async">
                    <span class="font-bold text-white" style="font-family:'Manrope',sans-serif;">City Transparency Portal</span>
                </a>
                <p class="landing-footer-muted max-w-sm text-sm leading-6">A civic technology capstone project led by students of Pamantasan ng Cabuyao, making public projects and expenditures easier for the community to follow.</p>
                <p class="landing-footer-muted mt-4 max-w-sm text-sm leading-6">Built in partnership with the City Planning Department of Cabuyao.</p>
            </div>

            <nav aria-label="Public resources">
                <h2 class="mb-4 text-[10px] font-bold uppercase tracking-[0.22em] text-emerald-300">Public resources</h2>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('public.map') }}">Public Map</a></li>
                    <li><a href="{{ route('public.expenditures') }}">Project Expenditures</a></li>
                    <li><a href="{{ route('public.analytics') }}">Analytics</a></li>
                </ul>
            </nav>

            <nav aria-label="Account">
                <h2 class="mb-4 text-[10px] font-bold uppercase tracking-[0.22em] text-emerald-300">Account</h2>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('login') }}">Login</a></li>
                </ul>
            </nav>

            <address class="not-italic">
                <h2 class="mb-4 text-[10px] font-bold uppercase tracking-[0.22em] text-emerald-300">Contacts</h2>
                <ul class="space-y-3 text-sm leading-6">
                    <li><a href="https://bettercabuyao.org/" target="_blank" rel="noopener noreferrer">bettercabuyao.org</a></li>
                    <li class="landing-footer-muted">F.B. Bailon St., Rosario Village, Brgy. Sala, Cabuyao City, 4025 Laguna</li>
                </ul>
            </address>
        </div>
        <div class="landing-footer-bottom border-t">
            <div class="landing-footer-muted mx-auto flex w-full max-w-6xl flex-col gap-2 px-6 py-5 text-xs sm:px-8 md:flex-row md:items-center md:justify-between">
                <span>© {{ date('Y') }} Cabuyao City Government. All rights reserved.</span>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetch('{{ route("api.projects.geojson") }}')
                .then(r => r.json())
                .then(data => {
                    if (data.features && data.features.length > 0) {
                        const completed = data.features.filter(f => f.properties.status === 'Completed').length;
                        const ongoingStatuses = new Set([
                            'Proposed',
                            'For bidding',
                            'Bidding ongoing',
                            'Award of contract',
                            'Implementation'
                        ]);
                        const ongoing = data.features.filter(f => ongoingStatuses.has(f.properties.status)).length;
                        const totalBudget = data.features.reduce((sum, f) => {
                            const budget = Number(f.properties.budget);
                            return sum + (isNaN(budget) ? 0 : budget);
                        }, 0);

                        document.getElementById('totalProjects').textContent = data.features.length;
                        document.getElementById('completedProjects').textContent = completed;
                        document.getElementById('ongoingProjects').textContent = ongoing;
                        const budgetDisplay = totalBudget >= 1000000000
                            ? '₱' + (totalBudget / 1000000000).toLocaleString(undefined, { minimumFractionDigits: 1, maximumFractionDigits: 1 }) + 'B'
                            : (totalBudget >= 1000000
                                ? '₱' + (totalBudget / 1000000).toLocaleString(undefined, { minimumFractionDigits: 1, maximumFractionDigits: 1 }) + 'M'
                                : '₱' + totalBudget.toLocaleString(undefined, { maximumFractionDigits: 0 }));
                        document.getElementById('totalBudget').textContent = budgetDisplay;
                    }
                })
                .catch(err => console.error('Error:', err));
        });

        // Sticky nav shadow on scroll
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 20) {
                header.classList.add('shadow-md');
            } else {
                header.classList.remove('shadow-md');
            }
        });
    </script>
</body>
</html>
