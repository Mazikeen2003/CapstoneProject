<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Mode | City Transparency Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #f1f5f9;
            --surface: rgba(255, 255, 255, 0.82);
            --ink: #0f172a;
            --muted: #64748b;
            --line: rgba(148, 163, 184, 0.22);
            --accent: #059669;
            --accent-2: #10b981;
            --amber: #d97706;
            --amber-soft: rgba(217, 119, 6, 0.08);
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
            opacity: 0.35;
        }
        .orb-1 {
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(5,150,105,0.18) 0%, transparent 70%);
            top: -100px; left: -80px;
            animation: orbDrift1 20s ease-in-out infinite;
        }
        .orb-2 {
            width: 320px; height: 320px;
            background: radial-gradient(circle, rgba(16,185,129,0.12) 0%, transparent 70%);
            bottom: -60px; right: -60px;
            animation: orbDrift2 18s ease-in-out infinite;
        }
        .orb-3 {
            width: 260px; height: 260px;
            background: radial-gradient(circle, rgba(217,119,6,0.08) 0%, transparent 70%);
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            animation: orbDrift3 24s ease-in-out infinite;
        }

        @keyframes orbDrift1 {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(30px, 40px); }
        }
        @keyframes orbDrift2 {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-25px, -30px); }
        }
        @keyframes orbDrift3 {
            0%, 100% { transform: translate(-50%, -50%) scale(1); }
            50% { transform: translate(-45%, -55%) scale(1.1); }
        }

        .card {
            width: 100%;
            max-width: 460px;
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
            background: linear-gradient(90deg, var(--accent) 0%, var(--accent-2) 50%, var(--amber) 100%);
            opacity: 0.85;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 16px;
            border-radius: 999px;
            background: var(--amber-soft);
            border: 1px solid rgba(217, 119, 6, 0.12);
            color: var(--amber);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-bottom: 32px;
        }

        .dot {
            width: 7px;
            height: 7px;
            background: var(--amber);
            border-radius: 50%;
            position: relative;
        }
        .dot::after {
            content: "";
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            background: var(--amber);
            opacity: 0;
            animation: softPulse 2.5s ease-out infinite;
        }
        @keyframes softPulse {
            0% { transform: scale(0.6); opacity: 0.5; }
            100% { transform: scale(2.4); opacity: 0; }
        }

        .icon-wrap {
            width: 140px;
            height: 140px;
            margin: 0 auto 32px;
            position: relative;
        }
        .icon-wrap svg { width: 100%; height: 100%; display: block; }

        .ring-outer { animation: slowSpin 14s linear infinite; transform-origin: 70px 70px; }
        .ring-mid { animation: slowSpinReverse 10s linear infinite; transform-origin: 70px 70px; }
        .ring-inner { animation: slowSpin 7s linear infinite; transform-origin: 70px 70px; }
        .blip-1 { animation: blipFade 2.5s ease-in-out infinite; }
        .blip-2 { animation: blipFade 2.5s ease-in-out infinite 0.8s; }
        .blip-3 { animation: blipFade 2.5s ease-in-out infinite 1.6s; }
        .wrench-float { animation: wrenchBob 3.5s ease-in-out infinite; }
        .spark-1 { animation: sparkPop 2.2s ease-in-out infinite; }
        .spark-2 { animation: sparkPop 2.2s ease-in-out infinite 0.6s; }
        .spark-3 { animation: sparkPop 2.2s ease-in-out infinite 1.2s; }

        @keyframes slowSpin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        @keyframes slowSpinReverse { from { transform: rotate(360deg); } to { transform: rotate(0deg); } }
        @keyframes blipFade {
            0% { opacity: 0; transform: scale(0.5); }
            30% { opacity: 1; transform: scale(1); }
            70% { opacity: 1; }
            100% { opacity: 0; transform: scale(1.3); }
        }
        @keyframes wrenchBob {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-4px) rotate(1deg); }
        }
        @keyframes sparkPop {
            0%, 100% { opacity: 0; transform: scale(0.5); }
            40% { opacity: 1; transform: scale(1); }
            80% { opacity: 0; transform: scale(1.2); }
        }

        h1 {
            font-family: "Space Grotesk", "Inter", sans-serif;
            font-size: clamp(1.6rem, 1.4rem + 1vw, 2rem);
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--ink);
            margin-bottom: 14px;
            line-height: 1.15;
        }

        p {
            color: var(--muted);
            font-size: 0.95rem;
            line-height: 1.7;
            max-width: 320px;
            margin: 0 auto;
        }

        .divider {
            width: 32px;
            height: 3px;
            border-radius: 3px;
            background: linear-gradient(90deg, var(--accent), var(--accent-2));
            margin: 32px auto;
            opacity: 0.5;
        }

        .footer-note {
            margin-top: 8px;
            font-size: 0.75rem;
            color: var(--muted);
            letter-spacing: 0.02em;
        }

        @media (prefers-reduced-motion: reduce) {
            .ring-outer, .ring-mid, .ring-inner, .blip-1, .blip-2, .blip-3,
            .wrench-float, .spark-1, .spark-2, .spark-3, .dot::after,
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
            Maintenance Mode
        </div>

        <div class="icon-wrap" aria-hidden="true">
            <svg viewBox="0 0 140 140" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Background disc -->
                <circle cx="70" cy="70" r="58" fill="#ffffff" stroke="#e2e8f0" stroke-width="1"/>

                <!-- Data blips -->
                <circle cx="32" cy="44" r="2.5" fill="#059669" opacity="0.3" class="blip-1"/>
                <circle cx="110" cy="36" r="2" fill="#10b981" opacity="0.4" class="blip-2"/>
                <circle cx="106" cy="102" r="2" fill="#059669" opacity="0.25" class="blip-3"/>

                <!-- Outer ring -->
                <g class="ring-outer" opacity="0.15">
                    <circle cx="70" cy="70" r="48" stroke="#059669" stroke-width="1.2" stroke-dasharray="5 6"/>
                </g>

                <!-- Middle ring -->
                <g class="ring-mid" opacity="0.22">
                    <circle cx="70" cy="70" r="38" stroke="#059669" stroke-width="1"/>
                </g>

                <!-- Inner ring -->
                <g class="ring-inner" opacity="0.3">
                    <circle cx="70" cy="70" r="28" stroke="#10b981" stroke-width="0.8" stroke-dasharray="3 4"/>
                </g>

                <!-- Wrench icon -->
                <g class="wrench-float">
                    <!-- Wrench body -->
                    <path d="M55 48c-6 6-6 16 0 22l12 12-3 3 2 2 3-3 12 12c6 6 16 6 22 0l-10-10-4 4-2-2 4-4-10-10-2 2 4 4-10 10c-4 4-10 4-14 0L49 72c-4-4-4-10 0-14l6-10z"
                          fill="#ffffff" stroke="#0f172a" stroke-width="2" stroke-linejoin="round"/>

                    <!-- Wrench head detail -->
                    <path d="M55 48l8 8M87 97l-8-8"
                          stroke="#0f172a" stroke-width="1.5" stroke-linecap="round"/>

                    <!-- Green accent fill on wrench head -->
                    <path d="M58 51c-4 4-4 11 0 15l9 9 3-3-9-9c-2-2-2-6 0-8l-3-4z"
                          fill="#059669" opacity="0.15"/>
                    <path d="M82 91c4-4 4-11 0-15l-9-9-3 3 9 9c2 2 2 6 0 8l3 4z"
                          fill="#059669" opacity="0.15"/>

                    <!-- Handle grip lines -->
                    <line x1="56" y1="76" x2="62" y2="82" stroke="#0f172a" stroke-width="1.2" stroke-linecap="round" opacity="0.4"/>
                    <line x1="60" y1="72" x2="66" y2="78" stroke="#0f172a" stroke-width="1.2" stroke-linecap="round" opacity="0.4"/>
                    <line x1="64" y1="68" x2="70" y2="74" stroke="#0f172a" stroke-width="1.2" stroke-linecap="round" opacity="0.4"/>
                </g>

                <!-- Spark effects around wrench -->
                <g class="spark-1" fill="#10b981">
                    <circle cx="48" cy="44" r="1.5"/>
                </g>
                <g class="spark-2" fill="#34d399">
                    <circle cx="98" cy="52" r="1.2"/>
                </g>
                <g class="spark-3" fill="#059669">
                    <circle cx="92" cy="102" r="1.5"/>
                </g>
            </svg>
        </div>

        <h1>Under Maintenance</h1>
        <p>The City Transparency Portal is temporarily unavailable while we perform scheduled improvements. We will be back shortly.</p>

        <div class="divider" aria-hidden="true"></div>

        <p class="footer-note">Cabuyao City Government · System Tracker · <span id="year"></span></p>
    </div>

    <script>
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>

</body>
</html>