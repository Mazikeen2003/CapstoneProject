<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Expired | Project Tracker System</title>
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
            --orange: #ea580c;
            --orange-2: #fb923c;
            --orange-soft: rgba(234, 88, 12, 0.06);
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
            background: radial-gradient(circle, rgba(234,88,12,0.14) 0%, transparent 70%);
            top: -80px; right: -60px;
            animation: orbDrift1 18s ease-in-out infinite;
        }
        .orb-2 {
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(251,146,60,0.10) 0%, transparent 70%);
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
            background: linear-gradient(90deg, var(--orange) 0%, var(--orange-2) 50%, var(--slate-dark) 100%);
            opacity: 0.8;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 16px;
            border-radius: 999px;
            background: var(--orange-soft);
            border: 1px solid rgba(234, 88, 12, 0.10);
            color: var(--orange);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 32px;
        }

        .dot {
            width: 7px;
            height: 7px;
            background: var(--orange);
            border-radius: 50%;
            position: relative;
        }
        .dot::after {
            content: "";
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            background: var(--orange);
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

        .clock-ring { animation: slowSpin 16s linear infinite; transform-origin: 65px 65px; }
        .clock-pulse { animation: gentleBreathe 3s ease-in-out infinite; }
        .hand-tick { animation: handTick 2s ease-in-out infinite; transform-origin: 65px 65px; }
        .blip-1 { animation: blipFade 2.5s ease-in-out infinite; }
        .blip-2 { animation: blipFade 2.5s ease-in-out infinite 0.8s; }
        .blip-3 { animation: blipFade 2.5s ease-in-out infinite 1.6s; }

        @keyframes slowSpin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        @keyframes gentleBreathe {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.03); }
        }
        @keyframes handTick {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(15deg); }
            50% { transform: rotate(45deg); }
            75% { transform: rotate(60deg); }
        }
        @keyframes blipFade {
            0% { opacity: 0; transform: scale(0.5); }
            30% { opacity: 1; transform: scale(1); }
            70% { opacity: 1; }
            100% { opacity: 0; transform: scale(1.3); }
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
            color: var(--orange);
            letter-spacing: 0.04em;
        }

        .divider {
            width: 32px;
            height: 3px;
            border-radius: 3px;
            background: linear-gradient(90deg, var(--orange), var(--orange-2));
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
            .clock-ring, .clock-pulse, .hand-tick, .blip-1, .blip-2, .blip-3, .dot::after,
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
            Session Expired
        </div>

        <div class="icon-wrap" aria-hidden="true">
            <svg viewBox="0 0 130 130" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Background disc -->
                <circle cx="65" cy="65" r="55" fill="#ffffff" stroke="#e2e8f0" stroke-width="1"/>

                <!-- Data blips -->
                <circle cx="30" cy="42" r="2.5" fill="#ea580c" opacity="0.3" class="blip-1"/>
                <circle cx="108" cy="34" r="2" fill="#fb923c" opacity="0.35" class="blip-2"/>
                <circle cx="104" cy="104" r="2" fill="#ea580c" opacity="0.25" class="blip-3"/>

                <!-- Outer orbit ring -->
                <g class="clock-ring" opacity="0.12">
                    <circle cx="65" cy="65" r="46" stroke="#ea580c" stroke-width="1.2" stroke-dasharray="5 6"/>
                </g>

                <!-- Clock body -->
                <g class="clock-pulse">
                    <circle cx="65" cy="65" r="26" fill="#ffffff" stroke="#0f172a" stroke-width="2"/>
                    <circle cx="65" cy="65" r="22" fill="none" stroke="#e2e8f0" stroke-width="1"/>

                    <!-- Clock ticks -->
                    <line x1="65" y1="43" x2="65" y2="47" stroke="#0f172a" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="65" y1="83" x2="65" y2="87" stroke="#0f172a" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="43" y1="65" x2="47" y2="65" stroke="#0f172a" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="83" y1="65" x2="87" y2="65" stroke="#0f172a" stroke-width="1.5" stroke-linecap="round"/>

                    <!-- Clock hands -->
                    <g class="hand-tick">
                        <line x1="65" y1="65" x2="65" y2="50" stroke="#ea580c" stroke-width="2.2" stroke-linecap="round"/>
                        <line x1="65" y1="65" x2="75" y2="70" stroke="#0f172a" stroke-width="2" stroke-linecap="round"/>
                    </g>

                    <!-- Center pin -->
                    <circle cx="65" cy="65" r="3" fill="#0f172a"/>
                    <circle cx="65" cy="65" r="1.5" fill="#ffffff"/>
                </g>
            </svg>
        </div>

        <h1>Your Session Expired</h1>
        <p class="message">
            Your session has expired for security reasons. Please refresh the page and try again, or log back in.
        </p>

        <div class="code">Error 419 · Page Expired</div>

        <div class="divider" aria-hidden="true"></div>

        <div class="actions">
            <button onclick="location.reload()" class="btn btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="23 4 23 10 17 10"/>
                    <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
                </svg>
                Refresh Page
            </button>
            <a href="{{ route('login') }}" class="btn btn-ghost">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                    <polyline points="10 17 15 12 10 7"/>
                    <line x1="15" y1="12" x2="3" y2="12"/>
                </svg>
                Back to Login
            </a>
        </div>

        <p class="footer-note">Project Tracker System · Cabuyao City Government · <span id="year"></span></p>
    </div>

    <script>
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>

</body>
</html>