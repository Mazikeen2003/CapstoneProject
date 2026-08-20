<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Not Found | Project Tracker System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #f8fafc;
            --surface: rgba(255, 255, 255, 0.88);
            --ink: #0f172a;
            --muted: #64748b;
            --line: rgba(148, 163, 184, 0.22);
            --indigo: #4f46e5;
            --indigo-2: #818cf8;
            --indigo-soft: rgba(79, 70, 229, 0.06);
            --slate-dark: #162347;
            --shadow-lg: 0 1px 2px rgba(0,0,0,0.04),
                          0 12px 36px -8px rgba(0,0,0,0.10),
                          0 32px 80px -20px rgba(0,0,0,0.14);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: "Inter", system-ui, -apple-system, sans-serif;
            background: var(--bg);
            color: var(--ink);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
            -webkit-font-smoothing: antialiased;
            position: relative;
            overflow-x: hidden;
        }

        .ambient {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.3;
        }
        .orb-1 {
            width: 380px; height: 380px;
            background: radial-gradient(circle, rgba(79,70,229,0.14) 0%, transparent 70%);
            top: -80px; right: -60px;
            animation: orbDrift1 18s ease-in-out infinite;
        }
        .orb-2 {
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(129,140,248,0.10) 0%, transparent 70%);
            bottom: -40px; left: -40px;
            animation: orbDrift2 16s ease-in-out infinite;
        }
        .orb-3 {
            width: 240px; height: 240px;
            background: radial-gradient(circle, rgba(22,35,71,0.06) 0%, transparent 70%);
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            animation: orbDrift3 22s ease-in-out infinite;
        }

        @keyframes orbDrift1 {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-25px, 30px); }
        }
        @keyframes orbDrift2 {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(20px, -25px); }
        }
        @keyframes orbDrift3 {
            0%, 100% { transform: translate(-50%, -50%) scale(1); }
            50% { transform: translate(-48%, -52%) scale(1.08); }
        }

        .card {
            width: 100%;
            max-width: 480px;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 28px;
            padding: 52px 44px 40px;
            box-shadow: var(--shadow-lg);
            text-align: center;
            position: relative;
            z-index: 1;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        .card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 28px;
            right: 28px;
            height: 3px;
            border-radius: 0 0 4px 4px;
            background: linear-gradient(90deg, var(--indigo) 0%, var(--indigo-2) 50%, var(--slate-dark) 100%);
            opacity: 0.8;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 16px;
            border-radius: 999px;
            background: var(--indigo-soft);
            border: 1px solid rgba(79, 70, 229, 0.10);
            color: var(--indigo);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 32px;
        }

        .dot {
            width: 7px;
            height: 7px;
            background: var(--indigo);
            border-radius: 50%;
            position: relative;
        }
        .dot::after {
            content: "";
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            background: var(--indigo);
            opacity: 0;
            animation: softPulse 2.5s ease-out infinite;
        }
        @keyframes softPulse {
            0% { transform: scale(0.6); opacity: 0.5; }
            100% { transform: scale(2.4); opacity: 0; }
        }

        .icon-wrap {
            width: 130px;
            height: 130px;
            margin: 0 auto 32px;
            position: relative;
        }
        .icon-wrap svg { width: 100%; height: 100%; display: block; }

        .compass-ring { animation: slowSpin 20s linear infinite; transform-origin: 65px 65px; }
        .compass-float { animation: gentleFloat 4s ease-in-out infinite; }
        .needle-sway { animation: needleWobble 3s ease-in-out infinite; transform-origin: 65px 65px; }
        .blip-1 { animation: blipFade 2.5s ease-in-out infinite; }
        .blip-2 { animation: blipFade 2.5s ease-in-out infinite 0.8s; }
        .blip-3 { animation: blipFade 2.5s ease-in-out infinite 1.6s; }

        @keyframes slowSpin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        @keyframes gentleFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-4px); }
        }
        @keyframes needleWobble {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-8deg); }
            75% { transform: rotate(6deg); }
        }
        @keyframes blipFade {
            0% { opacity: 0; transform: scale(0.5); }
            30% { opacity: 1; transform: scale(1); }
            70% { opacity: 1; }
            100% { opacity: 0; transform: scale(1.3); }
        }

        h1 {
            font-family: "Space Grotesk", "Inter", sans-serif;
            font-size: clamp(2.4rem, 2rem + 2vw, 3.2rem);
            font-weight: 700;
            letter-spacing: -0.03em;
            color: var(--ink);
            margin-bottom: 10px;
            line-height: 1.1;
        }

        .message {
            color: var(--muted);
            font-size: 0.95rem;
            line-height: 1.7;
            max-width: 340px;
            margin: 0 auto;
        }

        .code {
            display: inline-block;
            margin-top: 20px;
            padding: 6px 16px;
            border-radius: 8px;
            background: rgba(15, 23, 42, 0.04);
            border: 1px solid var(--line);
            font-family: "SF Mono", Monaco, "Cascadia Code", monospace;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--indigo);
            letter-spacing: 0.04em;
        }

        .divider {
            width: 32px;
            height: 3px;
            border-radius: 3px;
            background: linear-gradient(90deg, var(--indigo), var(--indigo-2));
            margin: 32px auto;
            opacity: 0.4;
        }

        .action-area {
            margin-top: 8px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 28px;
            border-radius: 999px;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
            font-family: inherit;
        }

        .btn-primary {
            background: var(--slate-dark);
            color: #fff;
        }
        .btn-primary:hover {
            opacity: 0.92;
            transform: translateY(-1px);
        }

        .footer-note {
            margin-top: 28px;
            font-size: 0.75rem;
            color: var(--muted);
            letter-spacing: 0.02em;
        }

        @media (prefers-reduced-motion: reduce) {
            .compass-ring, .compass-float, .needle-sway, .blip-1, .blip-2, .blip-3, .dot::after,
            .orb-1, .orb-2, .orb-3 {
                animation: none;
            }
        }

        @media (max-width: 480px) {
            .card { padding: 44px 28px 32px; border-radius: 24px; }
        }
    </style>
</head>
<body>

    <div class="ambient" aria-hidden="true">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>
    </div>

    <div class="card">
        <div class="badge">
            <span class="dot"></span>
            Page Not Found
        </div>

        <div class="icon-wrap" aria-hidden="true">
            <svg viewBox="0 0 130 130" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="65" cy="65" r="55" fill="#ffffff" stroke="#e2e8f0" stroke-width="1"/>
                <circle cx="30" cy="42" r="2.5" fill="#4f46e5" opacity="0.3" class="blip-1"/>
                <circle cx="108" cy="34" r="2" fill="#818cf8" opacity="0.35" class="blip-2"/>
                <circle cx="104" cy="104" r="2" fill="#4f46e5" opacity="0.25" class="blip-3"/>
                <g class="compass-ring" opacity="0.12">
                    <circle cx="65" cy="65" r="46" stroke="#4f46e5" stroke-width="1.2" stroke-dasharray="5 6"/>
                </g>
                <g class="compass-float">
                    <circle cx="65" cy="65" r="28" fill="#ffffff" stroke="#0f172a" stroke-width="2"/>
                    <circle cx="65" cy="65" r="24" fill="none" stroke="#e2e8f0" stroke-width="1"/>
                    <g class="needle-sway">
                        <path d="M65 43l6 22H59l6-22z" fill="#4f46e5"/>
                        <path d="M65 87l-6-22h12l-6 22z" fill="#64748b"/>
                        <circle cx="65" cy="65" r="3" fill="#0f172a"/>
                        <circle cx="65" cy="65" r="1.5" fill="#ffffff"/>
                    </g>
                    <line x1="65" y1="37" x2="65" y2="41" stroke="#0f172a" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="65" y1="89" x2="65" y2="93" stroke="#0f172a" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="37" y1="65" x2="41" y2="65" stroke="#0f172a" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="89" y1="65" x2="93" y2="65" stroke="#0f172a" stroke-width="1.5" stroke-linecap="round"/>
                    <text x="65" y="34" text-anchor="middle" fill="#4f46e5" font-family="Inter, sans-serif" font-size="6" font-weight="700">N</text>
                </g>
            </svg>
        </div>

        <h1>404</h1>
        <p class="message">
            The page you're looking for doesn't exist or may have been moved. Please check the URL and try again.
        </p>

        <div class="code">Error 404 · Not Found</div>

        <div class="divider" aria-hidden="true"></div>

        <div class="action-area">
            <a href="javascript:history.back()" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Go Back
            </a>
        </div>

        <p class="footer-note">Project Tracker System · Cabuyao City Government · <span id="year"></span></p>
    </div>

    <script>
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>

</body>
</html>