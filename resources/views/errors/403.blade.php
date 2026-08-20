<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Restricted | Project Tracker System</title>
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
            --rose: #e11d48;
            --rose-2: #fb7185;
            --rose-soft: rgba(225, 29, 72, 0.06);
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
            background: radial-gradient(circle, rgba(225,29,72,0.14) 0%, transparent 70%);
            top: -80px; right: -60px;
            animation: orbDrift1 18s ease-in-out infinite;
        }
        .orb-2 {
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(251,113,133,0.10) 0%, transparent 70%);
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
            background: linear-gradient(90deg, var(--rose) 0%, var(--rose-2) 50%, var(--slate-dark) 100%);
            opacity: 0.8;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 16px;
            border-radius: 999px;
            background: var(--rose-soft);
            border: 1px solid rgba(225, 29, 72, 0.10);
            color: var(--rose);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 32px;
        }

        .dot {
            width: 7px;
            height: 7px;
            background: var(--rose);
            border-radius: 50%;
            position: relative;
        }
        .dot::after {
            content: "";
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            background: var(--rose);
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

        .shield-ring { animation: slowSpin 16s linear infinite; transform-origin: 65px 65px; }
        .shield-pulse { animation: shieldBreathe 3s ease-in-out infinite; }
        .lock-shake { animation: gentleShake 4s ease-in-out infinite; transform-origin: 65px 65px; }
        .strike-line { animation: strikeDraw 2.5s ease-in-out infinite; stroke-dasharray: 40; stroke-dashoffset: 0; }

        @keyframes slowSpin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        @keyframes shieldBreathe {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.03); }
        }
        @keyframes gentleShake {
            0%, 100% { transform: rotate(0deg); }
            78% { transform: rotate(0deg); }
            80% { transform: rotate(-3deg); }
            82% { transform: rotate(3deg); }
            84% { transform: rotate(-2deg); }
            86% { transform: rotate(0deg); }
        }
        @keyframes strikeDraw {
            0% { stroke-dashoffset: 40; opacity: 0; }
            20% { opacity: 1; }
            60% { stroke-dashoffset: 0; opacity: 1; }
            100% { stroke-dashoffset: 0; opacity: 0.6; }
        }

        h1 {
            font-family: "Space Grotesk", "Inter", sans-serif;
            font-size: clamp(1.6rem, 1.4rem + 1vw, 2rem);
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--ink);
            margin-bottom: 12px;
            line-height: 1.15;
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
            color: var(--rose);
            letter-spacing: 0.04em;
        }

        .divider {
            width: 32px;
            height: 3px;
            border-radius: 3px;
            background: linear-gradient(90deg, var(--rose), var(--rose-2));
            margin: 32px auto;
            opacity: 0.4;
        }

        .actions {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 8px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 24px;
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

        .btn-ghost {
            background: transparent;
            color: var(--muted);
            border: 1px solid var(--line);
        }
        .btn-ghost:hover {
            background: rgba(15, 23, 42, 0.03);
            color: var(--ink);
        }

        .footer-note {
            margin-top: 28px;
            font-size: 0.75rem;
            color: var(--muted);
            letter-spacing: 0.02em;
        }

        @media (prefers-reduced-motion: reduce) {
            .shield-ring, .shield-pulse, .lock-shake, .strike-line, .dot::after,
            .orb-1, .orb-2, .orb-3 {
                animation: none;
            }
            .strike-line { opacity: 0.6; }
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
            Access Restricted
        </div>

        <div class="icon-wrap" aria-hidden="true">
            <svg viewBox="0 0 130 130" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Background disc -->
                <circle cx="65" cy="65" r="55" fill="#ffffff" stroke="#e2e8f0" stroke-width="1"/>

                <!-- Orbiting ring -->
                <g class="shield-ring" opacity="0.15">
                    <circle cx="65" cy="65" r="45" stroke="#e11d48" stroke-width="1.2" stroke-dasharray="5 6"/>
                </g>

                <!-- Shield + Lock group -->
                <g class="lock-shake">
                    <!-- Shield body -->
                    <g class="shield-pulse">
                        <path d="M65 28c-12 0-22 3-30 8-.5 16 4 32 30 46 26-14 30.5-30 30-46-8-5-18-8-30-8z"
                              fill="#ffffff" stroke="#0f172a" stroke-width="2" stroke-linejoin="round"/>
                        <!-- Shield inner fill -->
                        <path d="M65 28c-12 0-22 3-30 8-.5 16 4 32 30 46 26-14 30.5-30 30-46-8-5-18-8-30-8z"
                              fill="rgba(225,29,72,0.04)"/>
                    </g>

                    <!-- Lock body -->
                    <rect x="54" y="56" width="22" height="18" rx="4" fill="#0f172a"/>
                    <!-- Lock shackle -->
                    <path d="M58 56v-5a7 7 0 0 1 14 0v5" stroke="#0f172a" stroke-width="2.5" stroke-linecap="round" fill="none"/>
                    <!-- Keyhole -->
                    <circle cx="65" cy="63" r="2" fill="#ffffff"/>
                    <path d="M65 65v4" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round"/>

                    <!-- Strike line (access denied) -->
                    <line x1="52" y1="52" x2="78" y2="78"
                          stroke="#e11d48" stroke-width="2.5" stroke-linecap="round"
                          class="strike-line" opacity="0.9"/>
                </g>
            </svg>
        </div>

        <h1>Access unavailable</h1>
        <p class="message">
            You do not have permission to access this page. If you believe this is an error, please contact your system administrator.
        </p>

        <div class="code">Error 403 · Forbidden</div>

        <div class="divider" aria-hidden="true"></div>

        <div class="actions">
            <a href="{{ route('dashboard') }}" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                Back to Dashboard
            </a>
            <a href="javascript:history.back()" class="btn btn-ghost">
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