<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sweet Austria — Tableau de Bord Exécutif</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.2/dist/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jspdf-autotable@3.8.4/dist/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --sidebar-w: 240px;
            --header-h: 180px;
            --sidebar-bg: #F5F3F0;
            --sidebar-surface: rgba(255, 252, 247, 0.72);
            --sidebar-gold: #C8956C;
            --sidebar-gold-light: #FFD700;
            --main-bg: #F9F8F3;
            --green-dark: #003326;
            --green-card: #004236;
            --blue-card: #2B3E92;
            --orange-card: #E65C19;
            --brown-card: #BF571B;
            --orange-accent: #E65C19;
            --nav-active-bg: #FFF0E8;
            --text-dark: #1C1C1A;
            --text-muted: #6B6B68;
            --text-light: #9A9A97;
            --white: #FFFFFF;
            --border: #E8E6E1;
            --table-header: #F0EEEA;
            --status-dispo: #2D8A4E;
            --status-dispo-bg: #E8F5EC;
            --status-faible: #D97706;
            --status-faible-bg: #FEF3E2;
            --status-rupture: #C44B6E;
            --status-rupture-bg: #FCE8EE;
            --chart-charges: #B8956A;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--main-bg);
            color: var(--text-dark);
            min-height: 100vh;
            zoom: 1.05;
        }

        /* ── Logo header (aligné avec la barre hero) ── */
        .logo-header-box {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-w);
            height: var(--header-h);
            background:
                linear-gradient(160deg,
                    rgba(255, 252, 247, 0.94) 0%,
                    rgba(255, 241, 224, 0.88) 38%,
                    rgba(245, 220, 185, 0.82) 72%,
                    rgba(232, 196, 150, 0.78) 100%),
                url('/images/fruits-pixabay.jpg') center center / cover no-repeat;
            border-right: 1px solid rgba(200, 149, 108, 0.4);
            border-bottom: 1px solid rgba(200, 149, 108, 0.35);
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, 0.7),
                inset 0 -12px 28px rgba(191, 87, 27, 0.1),
                4px 0 18px rgba(0, 51, 38, 0.06);
            z-index: 101;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px 16px;
            overflow: hidden;
        }

        .logo-header-box::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 90% 70% at 20% 15%, rgba(255, 215, 0, 0.18) 0%, transparent 55%),
                radial-gradient(ellipse 80% 60% at 85% 90%, rgba(0, 51, 38, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 50% 50%, rgba(255, 255, 255, 0.35) 0%, transparent 65%);
            pointer-events: none;
            z-index: 0;
        }

        .logo-header-box::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg,
                var(--green-dark) 0%,
                #C8956C 25%,
                #FFD700 50%,
                #C8956C 75%,
                var(--green-dark) 100%);
            opacity: 0.65;
            z-index: 2;
        }

        .logo-header-glow {
            position: absolute;
            width: 130px;
            height: 130px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 215, 0, 0.22) 0%, transparent 70%);
            filter: blur(8px);
            z-index: 0;
            pointer-events: none;
        }

        .logo-circle {
            position: relative;
            z-index: 1;
            width: auto;
            height: 100%;
            max-height: calc(var(--header-h) - 28px);
            aspect-ratio: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #FFFFFF;
            border-radius: 50%;
            overflow: hidden;
            box-shadow:
                0 6px 24px rgba(0, 51, 38, 0.14),
                0 2px 8px rgba(191, 87, 27, 0.12),
                0 0 0 3px rgba(255, 255, 255, 0.95),
                0 0 0 5px rgba(200, 149, 108, 0.5),
                0 0 0 7px rgba(255, 215, 0, 0.25);
        }

        .logo-circle img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            object-position: center;
            display: block;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: var(--sidebar-w);
            background:
                linear-gradient(180deg,
                    rgba(255, 251, 245, 0.97) 0%,
                    rgba(250, 242, 230, 0.95) 35%,
                    rgba(245, 235, 218, 0.93) 70%,
                    rgba(240, 228, 210, 0.95) 100%);
            border-right: 1px solid rgba(200, 149, 108, 0.35);
            box-shadow: 4px 0 24px rgba(0, 51, 38, 0.07);
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            position: fixed;
            top: var(--header-h);
            left: 0;
            bottom: 0;
            z-index: 100;
            overflow: hidden;
        }

        .sidebar::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(ellipse 120% 40% at 50% 0%, rgba(255, 215, 0, 0.1) 0%, transparent 55%),
                radial-gradient(ellipse 80% 50% at 0% 100%, rgba(0, 51, 38, 0.06) 0%, transparent 50%);
            pointer-events: none;
            z-index: 0;
        }

        .sidebar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 16px;
            right: 16px;
            height: 1px;
            background: linear-gradient(90deg,
                transparent 0%,
                rgba(255, 215, 0, 0.45) 30%,
                rgba(200, 149, 108, 0.6) 50%,
                rgba(255, 215, 0, 0.45) 70%,
                transparent 100%);
            z-index: 1;
        }

        .sidebar-nav {
            position: relative;
            z-index: 1;
            flex: 1;
            padding: 20px 12px 16px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(200, 149, 108, 0.45) transparent;
        }

        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(200, 149, 108, 0.45);
            border-radius: 4px;
        }

        .sidebar-nav::before {
            content: 'Navigation';
            display: block;
            padding: 0 12px 14px;
            font-size: 9.5px;
            font-weight: 600;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: rgba(0, 51, 38, 0.42);
        }

        .nav-item {
            display: flex;
            align-items: center;
            width: 100%;
            margin: 0 0 4px;
            padding: 10px 14px;
            font-size: 13px;
            color: #2A2826;
            cursor: pointer;
            transition: all 0.22s ease;
            gap: 11px;
            text-decoration: none;
            border-radius: 10px;
            border: 1px solid rgba(200, 149, 108, 0.18);
            position: relative;
            overflow: hidden;
            background:
                linear-gradient(135deg,
                    rgba(255, 255, 255, 0.55) 0%,
                    rgba(255, 246, 236, 0.32) 55%,
                    rgba(245, 220, 185, 0.28) 100%);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.6);
            font-family: inherit;
            text-align: left;
        }

        .nav-item::after {
            content: '';
            position: absolute;
            top: 0;
            left: -130%;
            width: 75%;
            height: 100%;
            background: linear-gradient(120deg,
                transparent 0%,
                rgba(255, 255, 255, 0.75) 50%,
                transparent 100%);
            transform: skewX(-22deg);
            transition: left 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: none;
            z-index: 1;
        }

        .nav-item:hover::after {
            left: 135%;
        }

        .nav-item > * {
            position: relative;
            z-index: 2;
        }

        .nav-label {
            font-family: 'Playfair Display', serif;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.04em;
            color: var(--green-dark);
            line-height: 1.2;
        }

        .nav-group {
            margin-bottom: 8px;
            padding: 5px;
            background: rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(200, 149, 108, 0.18);
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0, 51, 38, 0.04);
        }

        .nav-group-toggle {
            border: none;
        }

        .nav-group.open > .nav-group-toggle {
            background: rgba(255, 255, 255, 0.55);
            border-color: rgba(200, 149, 108, 0.2);
        }

        .nav-group.open > .nav-group-toggle .nav-chevron {
            transform: rotate(180deg);
            opacity: 0.7;
        }

        .nav-submenu,
        .nav-subsubmenu {
            list-style: none;
            margin: 0;
            padding: 0 0 0 8px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s ease, padding 0.25s ease;
        }

        .nav-subsubmenu .nav-subitem {
            padding-left: 36px;
            font-size: 11.5px;
        }

        .nav-subsubmenu .nav-subitem::before {
            left: 22px;
        }

        .nav-group.open > .nav-submenu {
            max-height: 560px;
            padding: 2px 0 8px 8px;
        }

        .nav-subgroup.open > .nav-subsubmenu {
            max-height: 120px;
            padding: 2px 0 6px 12px;
        }

        .nav-subitem {
            display: block;
            padding: 7px 12px 7px 28px;
            font-size: 12px;
            font-weight: 500;
            color: #4A4845;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 2px;
            letter-spacing: 0.02em;
            transition: background 0.15s, color 0.15s;
            position: relative;
        }

        .nav-subitem::before {
            content: '';
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: rgba(200, 149, 108, 0.55);
        }

        .nav-subitem:hover {
            background: rgba(255, 255, 255, 0.7);
            color: var(--green-dark);
        }

        .nav-subitem.active {
            background: rgba(255, 240, 232, 0.85);
            color: var(--green-dark);
            font-weight: 600;
        }

        .nav-subgroup-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 7px 12px 7px 28px;
            font-size: 11.5px;
            font-weight: 600;
            color: #5C574F;
            background: transparent;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-align: left;
            letter-spacing: 0.02em;
            transition: background 0.15s, color 0.15s;
        }

        .nav-subgroup-toggle:hover {
            background: rgba(255, 255, 255, 0.5);
            color: var(--green-dark);
        }

        .nav-subgroup-toggle .nav-chevron {
            width: 12px;
            height: 12px;
            opacity: 0.45;
            transition: transform 0.25s ease;
            flex-shrink: 0;
        }

        .nav-subgroup.open > .nav-subgroup-toggle .nav-chevron {
            transform: rotate(180deg);
        }

        .nav-item:hover {
            background:
                linear-gradient(135deg,
                    rgba(255, 255, 255, 0.95) 0%,
                    rgba(255, 240, 232, 0.78) 60%,
                    rgba(245, 220, 185, 0.6) 100%);
            border-color: rgba(200, 149, 108, 0.4);
            box-shadow:
                0 6px 18px rgba(0, 51, 38, 0.12),
                inset 0 1px 0 rgba(255, 255, 255, 0.9);
            color: var(--green-dark);
            transform: translateY(-1px);
        }

        .nav-item.active {
            background: linear-gradient(135deg,
                rgba(255, 255, 255, 0.92) 0%,
                rgba(255, 240, 232, 0.88) 100%);
            border-color: rgba(200, 149, 108, 0.35);
            box-shadow:
                0 4px 14px rgba(191, 87, 27, 0.1),
                inset 0 1px 0 rgba(255, 255, 255, 0.9);
            font-weight: 600;
            color: var(--green-dark);
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 3px;
            height: 55%;
            border-radius: 0 3px 3px 0;
            background: linear-gradient(180deg, var(--green-dark) 0%, var(--sidebar-gold) 50%, var(--sidebar-gold-light) 100%);
        }

        .nav-item .nav-icon {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
            opacity: 0.55;
            transition: opacity 0.2s, color 0.2s;
        }

        .nav-item:hover .nav-icon,
        .nav-item.active .nav-icon {
            opacity: 1;
            color: var(--orange-accent);
        }

        .nav-item .nav-chevron {
            margin-left: auto;
            width: 14px;
            height: 14px;
            opacity: 0.35;
            transition: opacity 0.2s, transform 0.25s ease;
            flex-shrink: 0;
        }

        .nav-item:hover .nav-chevron { opacity: 0.6; }

        .sidebar-action {
            position: relative;
            z-index: 1;
            padding: 8px 16px 18px;
        }

        .btn-new-batch {
            width: 100%;
            background: linear-gradient(135deg, var(--green-dark) 0%, #004d3a 55%, #006650 100%);
            color: white;
            border: 1px solid rgba(255, 215, 0, 0.2);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.03em;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.22s ease;
            box-shadow:
                0 4px 16px rgba(0, 51, 38, 0.22),
                inset 0 1px 0 rgba(255, 255, 255, 0.12);
        }

        .btn-new-batch:hover {
            background: linear-gradient(135deg, #004d3a 0%, var(--green-dark) 100%);
            box-shadow:
                0 6px 20px rgba(0, 51, 38, 0.28),
                0 0 0 1px rgba(255, 215, 0, 0.25),
                inset 0 1px 0 rgba(255, 255, 255, 0.15);
            transform: translateY(-1px);
        }

        .sidebar-footer {
            position: relative;
            z-index: 1;
            padding: 14px 12px 18px;
            border-top: 1px solid rgba(200, 149, 108, 0.28);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.25) 0%, rgba(255, 252, 247, 0.5) 100%);
        }

        .sidebar-footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 16px;
            right: 16px;
            height: 1px;
            background: linear-gradient(90deg,
                transparent 0%,
                rgba(255, 255, 255, 0.8) 50%,
                transparent 100%);
        }

        .sidebar-footer .nav-item {
            font-size: 12.5px;
            color: var(--text-muted);
            margin-bottom: 2px;
            background: transparent;
            border-color: transparent;
            box-shadow: none;
        }

        .sidebar-footer .nav-item::after {
            display: none;
        }

        .sidebar-footer .nav-item:hover {
            color: var(--green-dark);
            background: rgba(255, 255, 255, 0.5);
            transform: none;
            box-shadow: none;
        }

        /* ── Sidebar toggle ── */
        .logo-header-box,
        .sidebar {
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body.sidebar-collapsed .logo-header-box,
        body.sidebar-collapsed .sidebar {
            transform: translateX(calc(-1 * var(--sidebar-w)));
        }

        .sidebar-toggle {
            position: fixed;
            top: calc(var(--header-h) + (100vh - var(--header-h)) / 2);
            left: calc(var(--sidebar-w) - 2px);
            transform: translateY(-50%);
            z-index: 102;
            width: 32px;
            height: 56px;
            padding: 0;
            border: none;
            cursor: pointer;
            border-radius: 0 14px 14px 0;
            background: linear-gradient(165deg, #004d3a 0%, var(--green-dark) 45%, #002419 100%);
            border: 1px solid rgba(255, 215, 0, 0.45);
            border-left: none;
            box-shadow:
                0 4px 20px rgba(0, 51, 38, 0.25),
                inset 0 1px 0 rgba(255, 255, 255, 0.12),
                inset 0 -1px 0 rgba(255, 215, 0, 0.2);
            color: var(--sidebar-gold-light);
            display: flex;
            align-items: center;
            justify-content: center;
            transition:
                left 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                border-radius 0.4s ease,
                box-shadow 0.2s ease,
                transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-toggle:hover {
            box-shadow:
                0 6px 24px rgba(0, 51, 38, 0.32),
                0 0 12px rgba(255, 215, 0, 0.25),
                inset 0 1px 0 rgba(255, 255, 255, 0.15);
            color: #FFF8E7;
        }

        .sidebar-toggle-inner {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        }

        .sidebar-toggle-icon {
            width: 18px;
            height: 18px;
            filter: drop-shadow(0 0 4px rgba(255, 215, 0, 0.5));
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body.sidebar-collapsed .sidebar-toggle {
            left: 0;
            border-radius: 14px 0 0 14px;
            border-left: 1px solid rgba(255, 215, 0, 0.45);
            border-right: none;
        }

        body.sidebar-collapsed .sidebar-toggle-icon {
            transform: rotate(180deg);
        }

        /* ── Main ── */
        .main-wrapper {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow: visible;
        }

        body.sidebar-collapsed .main-wrapper {
            margin-left: 0;
        }

        /* ── Hero Header (sticky : navbar + bannière restent visibles au scroll) ── */
        .hero-header {
            position: sticky;
            top: 0;
            z-index: 90;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            height: var(--header-h);
            background: url('/images/fruits-secs.png') center center / cover no-repeat;
        }

        .hero-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(0, 35, 25, 0.28) 0%, rgba(0, 35, 25, 0.42) 100%);
            z-index: 0;
        }

        .hero-banner {
            position: relative;
            z-index: 1;
            height: 100%;
            min-height: var(--header-h);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding-bottom: 18px;
        }

        .hero-content-bottom {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .top-nav {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 32px;
            min-height: 48px;
        }

        .nav-brand {
            position: relative;
            z-index: 2;
            flex-shrink: 0;
            text-decoration: none;
            padding: 7px 18px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(6px);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.12);
            transition: box-shadow 0.2s, transform 0.2s;
        }

        .nav-brand:hover {
            box-shadow: 0 4px 16px rgba(255, 215, 0, 0.25);
            transform: translateY(-1px);
        }

        .nav-brand-text {
            font-family: 'Playfair Display', serif;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: none;
            background: linear-gradient(
                105deg,
                #8B5E3C 0%,
                #C8956C 20%,
                #FFD700 40%,
                #FFF8E7 50%,
                #FFD700 60%,
                #C8956C 80%,
                #003326 100%
            );
            background-size: 250% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: brand-shine 4s ease-in-out infinite;
            filter: drop-shadow(0 1px 2px rgba(255, 215, 0, 0.35));
        }

        @keyframes brand-shine {
            0%, 100% { background-position: 0% center; }
            50% { background-position: 100% center; }
        }

        .top-nav-links {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            gap: 20px;
            list-style: none;
            z-index: 1;
        }

        .social-icons {
            display: flex;
            align-items: center;
            gap: 10px;
            position: relative;
            z-index: 2;
            margin-left: auto;
            flex-shrink: 0;
        }

        .social-btn {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.18);
            transition: transform 0.15s, box-shadow 0.15s;
        }

        .social-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.28);
        }

        .social-btn svg {
            width: 16px;
            height: 16px;
            fill: currentColor;
        }

        .social-facebook { background: #1877F2; }
        .social-instagram { background: linear-gradient(135deg, #F58529 0%, #DD2A7B 50%, #8134AF 100%); }
        .social-tiktok { background: #010101; border: 1px solid rgba(255, 255, 255, 0.15); }
        .social-youtube { background: #FF0000; }

        .top-nav-links a {
            position: relative;
            overflow: hidden;
            color: var(--green-dark);
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 7px 16px;
            white-space: nowrap;
            border-radius: 20px;
            border: 1px solid rgba(200, 149, 108, 0.28);
            background: linear-gradient(135deg,
                rgba(255, 255, 255, 0.94) 0%,
                rgba(255, 246, 236, 0.88) 55%,
                rgba(245, 224, 196, 0.8) 100%);
            backdrop-filter: blur(6px);
            box-shadow:
                0 2px 10px rgba(0, 0, 0, 0.12),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            transition: background 0.25s ease, box-shadow 0.25s ease, transform 0.25s ease, color 0.25s ease, border-color 0.25s ease;
        }

        .top-nav-links a::after {
            content: '';
            position: absolute;
            top: 0;
            left: -130%;
            width: 70%;
            height: 100%;
            background: linear-gradient(120deg,
                transparent 0%,
                rgba(255, 255, 255, 0.85) 50%,
                transparent 100%);
            transform: skewX(-22deg);
            transition: left 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: none;
        }

        .top-nav-links a:hover::after {
            left: 140%;
        }

        .top-nav-links a:hover {
            background: linear-gradient(135deg,
                #ffffff 0%,
                rgba(255, 240, 232, 0.96) 60%,
                rgba(255, 226, 196, 0.92) 100%);
            border-color: rgba(255, 215, 0, 0.55);
            box-shadow:
                0 6px 18px rgba(191, 87, 27, 0.22),
                inset 0 0 0 1px rgba(255, 215, 0, 0.35);
            transform: translateY(-2px);
            color: var(--orange-accent);
        }

        .top-nav-links a:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .nav-dropdown {
            position: relative;
        }

        .nav-dropdown-toggle svg {
            transition: transform 0.2s;
        }

        .nav-dropdown:not(.nav-dropdown-click):hover .nav-dropdown-toggle svg {
            transform: rotate(180deg);
        }

        .nav-dropdown-pin:hover .nav-dropdown-toggle svg,
        .nav-dropdown-pin.is-open .nav-dropdown-toggle svg {
            transform: rotate(180deg);
        }

        .nav-dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            left: 50%;
            transform: translateX(-50%);
            min-width: 340px;
            list-style: none;
            background: linear-gradient(165deg, rgba(255, 255, 255, 0.99) 0%, rgba(252, 250, 246, 0.98) 100%);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-radius: 16px;
            border: 1px solid rgba(233, 197, 119, 0.28);
            box-shadow:
                0 4px 6px rgba(0, 40, 30, 0.04),
                0 18px 40px rgba(0, 40, 30, 0.14),
                inset 0 1px 0 rgba(255, 255, 255, 0.9);
            padding: 10px;
            opacity: 0;
            visibility: hidden;
            transform: translateX(-50%) translateY(-6px);
            transition: opacity 0.25s ease, visibility 0.25s ease, transform 0.25s ease;
            z-index: 50;
        }

        .nav-dropdown-pin .nav-dropdown-menu::after {
            content: '';
            position: absolute;
            top: 0;
            left: 16px;
            right: 16px;
            height: 2px;
            border-radius: 0 0 2px 2px;
            background: linear-gradient(90deg, transparent, #d4a86a 20%, #e9c577 50%, #d4a86a 80%, transparent);
            opacity: 0.85;
        }

        .landing-nav-links .nav-dropdown-menu {
            z-index: 4100;
        }

        .nav-dropdown-pin .nav-dropdown-menu {
            top: 100%;
            margin-top: 0;
            padding-top: 14px;
            min-width: 360px;
        }

        .nav-dropdown-pin .nav-dropdown-menu::before {
            content: '';
            position: absolute;
            top: -8px;
            left: 0;
            width: 100%;
            height: 8px;
        }

        .nav-dropdown-pin.is-open .nav-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
            pointer-events: auto;
        }

        .nav-dropdown-pin.is-open .nav-dropdown-menu .nav-sub-link {
            pointer-events: auto;
        }

        .nav-dropdown-pin.is-open > .nav-dropdown-toggle {
            color: #2a1a05;
            background: linear-gradient(135deg, #e9c577 0%, #d4a86a 100%);
            border-color: #e9c577;
        }

        .nav-dropdown:not(.nav-dropdown-pin):hover .nav-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }

        .nav-dropdown-click.is-open .nav-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }

        .nav-dropdown-menu li a {
            display: block;
            padding: 0;
            border-radius: 0;
            background: transparent;
            box-shadow: none;
            font-size: 13px;
            font-weight: 500;
            white-space: normal;
            line-height: 1.4;
            transform: none;
        }

        .nav-dropdown-menu li a:hover {
            background: transparent;
            box-shadow: none;
            transform: none;
        }

        .nav-dropdown-menu-label {
            padding: 4px 12px 8px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #9a8b72;
            pointer-events: none;
            user-select: none;
        }

        .nav-dropdown-pin .nav-dropdown-menu li + li:not(.nav-dropdown-menu-label) {
            margin-top: 4px;
        }

        .nav-sub-link {
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 12px !important;
            border-radius: 11px !important;
            border: 1px solid rgba(0, 50, 38, 0.06) !important;
            background: rgba(255, 255, 255, 0.72) !important;
            color: #003326 !important;
            box-shadow: 0 1px 2px rgba(0, 40, 30, 0.04) !important;
            transform: none !important;
            transition:
                background 0.22s ease,
                border-color 0.22s ease,
                color 0.22s ease,
                box-shadow 0.22s ease,
                transform 0.22s ease !important;
            position: relative;
            overflow: hidden;
        }

        .nav-sub-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(180deg, #e9c577, #c49a4a);
            opacity: 0;
            transition: opacity 0.22s ease;
            border-radius: 11px 0 0 11px;
        }

        .sub-link-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 18px;
            line-height: 1;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.5);
            transition: transform 0.22s ease, box-shadow 0.22s ease;
        }

        .nav-sub-link:hover .sub-link-icon,
        .nav-sub-link.is-active .sub-link-icon {
            transform: scale(1.05);
            box-shadow: 0 2px 8px rgba(0, 40, 30, 0.1);
        }

        .sub-link-icon.cat-coque    { background: linear-gradient(135deg, #f5ebe0 0%, #e8d5c0 100%); }
        .sub-link-icon.cat-seche    { background: linear-gradient(135deg, #fde8ef 0%, #f5c6d6 100%); }
        .sub-link-icon.cat-cacahuetes { background: linear-gradient(135deg, #fef3e2 0%, #f5d9a8 100%); }
        .sub-link-icon.cat-graines  { background: linear-gradient(135deg, #fff8e1 0%, #ffe082 100%); }
        .sub-link-icon.cat-enrobes  { background: linear-gradient(135deg, #fce4ec 0%, #f8bbd9 100%); }
        .sub-link-icon.cat-ramadan  { background: linear-gradient(135deg, #e8eaf6 0%, #c5cae9 100%); }

        .sub-link-content {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .sub-link-title {
            font-size: 13.5px;
            font-weight: 600;
            color: #003326;
            letter-spacing: -0.01em;
            line-height: 1.3;
        }

        .sub-link-desc {
            font-size: 11px;
            font-weight: 400;
            color: #6b7c76;
            line-height: 1.35;
        }

        .sub-link-arrow {
            flex-shrink: 0;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 50, 38, 0.06);
            color: #004236;
            opacity: 0;
            transform: translateX(-6px);
            transition: opacity 0.22s ease, transform 0.22s ease, background 0.22s ease, color 0.22s ease;
        }

        .sub-link-arrow svg {
            width: 12px;
            height: 12px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2.2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .landing-nav-links .nav-dropdown-menu .nav-sub-link:hover,
        .landing-nav-links .nav-dropdown-menu li:hover > .nav-sub-link,
        .landing-nav-links .nav-dropdown-menu .nav-sub-link.is-active,
        .nav-dropdown-pin .nav-dropdown-menu li a.nav-sub-link:hover,
        .nav-dropdown-pin .nav-dropdown-menu li:hover > a.nav-sub-link,
        .nav-dropdown-pin .nav-dropdown-menu li a.nav-sub-link.is-active {
            background: linear-gradient(135deg, rgba(255, 252, 245, 0.98) 0%, rgba(252, 241, 220, 0.95) 100%) !important;
            border-color: rgba(212, 168, 106, 0.45) !important;
            color: #2a1a05 !important;
            box-shadow:
                0 4px 14px rgba(212, 168, 106, 0.18),
                inset 0 1px 0 rgba(255, 255, 255, 0.8) !important;
            transform: translateX(2px) !important;
        }

        .landing-nav-links .nav-dropdown-menu .nav-sub-link:hover::before,
        .landing-nav-links .nav-dropdown-menu .nav-sub-link.is-active::before,
        .nav-dropdown-pin .nav-dropdown-menu .nav-sub-link:hover::before,
        .nav-dropdown-pin .nav-dropdown-menu .nav-sub-link.is-active::before {
            opacity: 1;
        }

        .landing-nav-links .nav-dropdown-menu .nav-sub-link:hover .sub-link-title,
        .landing-nav-links .nav-dropdown-menu .nav-sub-link.is-active .sub-link-title,
        .nav-dropdown-pin .nav-dropdown-menu .nav-sub-link:hover .sub-link-title,
        .nav-dropdown-pin .nav-dropdown-menu .nav-sub-link.is-active .sub-link-title {
            color: #2a1a05;
            font-weight: 700;
        }

        .landing-nav-links .nav-dropdown-menu .nav-sub-link:hover .sub-link-desc,
        .landing-nav-links .nav-dropdown-menu .nav-sub-link.is-active .sub-link-desc,
        .nav-dropdown-pin .nav-dropdown-menu .nav-sub-link:hover .sub-link-desc,
        .nav-dropdown-pin .nav-dropdown-menu .nav-sub-link.is-active .sub-link-desc {
            color: #7a6348;
        }

        .landing-nav-links .nav-dropdown-menu .nav-sub-link:hover .sub-link-arrow,
        .landing-nav-links .nav-dropdown-menu .nav-sub-link.is-active .sub-link-arrow,
        .nav-dropdown-pin .nav-dropdown-menu .nav-sub-link:hover .sub-link-arrow,
        .nav-dropdown-pin .nav-dropdown-menu .nav-sub-link.is-active .sub-link-arrow {
            opacity: 1;
            transform: translateX(0);
            background: linear-gradient(135deg, #e9c577 0%, #d4a86a 100%);
            color: #2a1a05;
        }

        .nav-dropdown-menu li:first-child a {
            border-radius: 11px;
        }

        .nav-dropdown-menu li:last-child a {
            border-radius: 11px;
        }

        .nav-dropdown-menu li a.zone-link,
        .nav-dropdown-menu li a.partner-link {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .zone-icon,
        .partner-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.45);
            transition: transform 0.22s ease, box-shadow 0.22s ease;
        }

        .nav-sub-link:hover .zone-icon,
        .nav-sub-link:hover .partner-icon,
        .nav-sub-link.is-active .zone-icon,
        .nav-sub-link.is-active .partner-icon {
            transform: scale(1.05);
            box-shadow: 0 2px 8px rgba(0, 40, 30, 0.12);
        }

        .zone-icon svg {
            width: 17px;
            height: 17px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .zone-est    { background: linear-gradient(135deg, #e8f5ec 0%, #c8e6d0 100%); color: #2D8A4E; }
        .zone-ouest  { background: linear-gradient(135deg, #e8eef8 0%, #c5d4f0 100%); color: #2B3E92; }
        .zone-taza   { background: linear-gradient(135deg, #fef3e2 0%, #f5d4a8 100%); color: #C45B1C; }
        .zone-gharb  { background: linear-gradient(135deg, #f3e8ff 0%, #ddd0f5 100%); color: #7C3AED; }
        .zone-casa   { background: linear-gradient(135deg, #e0f2fe 0%, #b3e0f7 100%); color: #0284C7; }

        .partner-icon svg {
            width: 17px;
            height: 17px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .partner-marjane    { background: linear-gradient(135deg, #fde8e8 0%, #f5b8b8 100%); color: #E30613; }
        .partner-decathlon  { background: linear-gradient(135deg, #e0f0fa 0%, #a8d4f0 100%); color: #0082C3; }
        .partner-atacadaw   { background: linear-gradient(135deg, #fff4e0 0%, #f5c98a 100%); color: #E65C19; }

        /* ── Carte de visite Commercial ── */
        .visit-card-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 20, 15, 0.55);
            backdrop-filter: blur(4px);
            z-index: 5000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s, visibility 0.25s;
        }

        .visit-card-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .visit-card {
            background: var(--white);
            border-radius: 16px;
            width: 100%;
            max-width: 520px;
            overflow: hidden;
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.25);
            transform: translateY(20px) scale(0.97);
            transition: transform 0.25s;
        }

        .visit-card-overlay.active .visit-card {
            transform: translateY(0) scale(1);
        }

        .visit-card-header {
            background: linear-gradient(135deg, var(--green-dark) 0%, #004d3a 100%);
            color: white;
            padding: 18px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .visit-card-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            font-weight: 700;
        }

        .visit-card-close {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.15);
            color: white;
            font-size: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .visit-card-close:hover {
            background: rgba(255, 255, 255, 0.25);
        }

        .visit-card-body {
            padding: 24px;
        }

        .visit-card-main {
            display: flex;
            gap: 20px;
            align-items: flex-start;
            margin-bottom: 22px;
        }

        .visit-photo-wrap {
            flex-shrink: 0;
        }

        .visit-photo {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--green-dark);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
        }

        .visit-info {
            flex: 1;
            min-width: 0;
        }

        .visit-field {
            margin-bottom: 10px;
        }

        .visit-field-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 3px;
        }

        .visit-field-value {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-dark);
        }

        .visit-field-value.id {
            font-family: monospace;
            color: var(--green-dark);
            font-size: 14px;
        }

        .visit-field-value.phone {
            color: var(--orange-card);
        }

        .visit-qr-wrap {
            flex-shrink: 0;
            text-align: center;
        }

        .visit-qr {
            width: 100px;
            height: 100px;
            border-radius: 8px;
            border: 1px solid var(--border);
            padding: 4px;
            background: white;
        }

        .visit-qr-label {
            font-size: 9px;
            color: var(--text-muted);
            margin-top: 6px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .visit-manager-section {
            border-top: 1px solid var(--border);
            padding-top: 20px;
        }

        .visit-manager-title {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--green-dark);
            margin-bottom: 14px;
        }

        .visit-manager-card {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px;
            background: var(--sidebar-bg);
            border-radius: 12px;
            border: 1px solid var(--border);
        }

        .visit-manager-photo {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--orange-accent);
        }

        .visit-manager-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .visit-manager-role {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* ── Modal Catégories ── */
        .category-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 20, 15, 0.72);
            backdrop-filter: blur(6px);
            z-index: 5000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s, visibility 0.25s;
        }

        .category-modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .category-modal {
            background: var(--white);
            border-radius: 16px;
            width: 100%;
            max-width: 980px;
            max-height: 90vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 24px 48px rgba(0, 0, 0, 0.35);
            transform: translateY(20px) scale(0.97);
            transition: transform 0.25s;
        }

        .category-modal-overlay.active .category-modal {
            transform: translateY(0) scale(1);
        }

        .category-modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            background: var(--sidebar-bg);
        }

        .category-modal-title {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 700;
            color: var(--green-dark);
        }

        .category-modal-close {
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 50%;
            background: rgba(0, 51, 38, 0.08);
            color: var(--green-dark);
            font-size: 22px;
            line-height: 1;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.15s;
        }

        .category-modal-close:hover {
            background: rgba(0, 51, 38, 0.15);
        }

        .category-modal-body {
            padding: 24px;
            overflow-y: auto;
        }

        .category-gallery-intro {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
            padding: 14px 16px;
            border-radius: 12px;
            background: linear-gradient(135deg, #f8f6f0 0%, #f0ebe0 100%);
            border: 1px solid var(--border);
        }

        .category-gallery-cover {
            width: 88px;
            height: 88px;
            border-radius: 12px;
            object-fit: cover;
            flex-shrink: 0;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        }

        .category-gallery-intro-text h3 {
            margin: 0 0 4px;
            font-size: 15px;
            font-weight: 700;
            color: var(--green-dark);
        }

        .category-gallery-intro-text p {
            margin: 0;
            font-size: 12px;
            color: var(--text-muted);
            line-height: 1.45;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 18px;
        }

        .product-card {
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            background: var(--white);
            display: flex;
            flex-direction: column;
            transition: box-shadow 0.2s, transform 0.2s;
        }

        .product-card:hover {
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .product-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            background: var(--table-header);
            transition: transform 0.25s;
        }

        .product-card:hover .product-image {
            transform: scale(1.03);
        }

        .product-info {
            padding: 16px;
            display: flex;
            flex-direction: column;
            flex: 1;
            gap: 8px;
        }

        .product-name {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1.3;
        }

        .product-desc {
            font-size: 12.5px;
            color: var(--text-muted);
            line-height: 1.5;
            flex: 1;
        }

        .product-price {
            font-size: 18px;
            font-weight: 700;
            color: var(--orange-card);
        }

        .btn-add-cart {
            width: 100%;
            padding: 10px 16px;
            border: none;
            border-radius: 8px;
            background: var(--green-dark);
            color: white;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.15s;
            margin-top: 4px;
        }

        .btn-add-cart:hover {
            background: #004d3a;
        }

        .cart-toast {
            position: fixed;
            bottom: 28px;
            right: 28px;
            background: var(--green-dark);
            color: white;
            padding: 14px 22px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
            z-index: 1100;
            opacity: 0;
            transform: translateY(12px);
            transition: opacity 0.3s, transform 0.3s;
            pointer-events: none;
        }

        .cart-toast.show {
            opacity: 1;
            transform: translateY(0);
        }

        .hero-slogan-wrap {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px 48px;
        }

        .hero-slogan {
            position: relative;
            z-index: 2;
            font-family: 'Playfair Display', serif;
            font-size: clamp(26px, 3.2vw, 38px);
            font-weight: 600;
            font-style: italic;
            color: white;
            text-align: center;
            padding: 0 20px;
            letter-spacing: 0.04em;
            line-height: 1.35;
            text-shadow: 0 2px 12px rgba(0, 0, 0, 0.35), 0 0 30px rgba(255, 255, 255, 0.15);
        }

        .sparkle {
            position: absolute;
            color: #FFD700;
            text-shadow: 0 0 8px rgba(255, 215, 0, 0.9), 0 0 16px rgba(255, 255, 255, 0.6);
            animation: twinkle 2.4s ease-in-out infinite;
            pointer-events: none;
            line-height: 1;
        }

        .sparkle svg {
            display: block;
            filter: drop-shadow(0 0 4px rgba(255, 215, 0, 0.8));
        }

        .sparkle-1 { top: -4px;  left: 12%;  font-size: 18px; animation-delay: 0s; }
        .sparkle-2 { top: 50%;  left: 4%;   font-size: 14px; animation-delay: 0.6s; transform: translateY(-50%); }
        .sparkle-3 { bottom: -2px; left: 18%; font-size: 12px; animation-delay: 1.2s; }
        .sparkle-4 { top: -6px;  right: 12%; font-size: 20px; animation-delay: 0.3s; }
        .sparkle-5 { top: 50%;  right: 4%;  font-size: 15px; animation-delay: 0.9s; transform: translateY(-50%); }
        .sparkle-6 { bottom: 0;  right: 16%; font-size: 13px; animation-delay: 1.5s; }
        .sparkle-7 { top: -10px; left: 50%;  font-size: 16px; animation-delay: 0.45s; transform: translateX(-50%); }
        .sparkle-8 { bottom: -8px; left: 50%; font-size: 14px; animation-delay: 1.1s; transform: translateX(-50%); }

        @keyframes twinkle {
            0%, 100% { opacity: 0.45; transform: scale(0.85); filter: brightness(0.9); }
            50% { opacity: 1; transform: scale(1.15); filter: brightness(1.4); }
        }

        .sparkle-2, .sparkle-5 {
            animation-name: twinkle-side;
        }

        @keyframes twinkle-side {
            0%, 100% { opacity: 0.45; transform: translateY(-50%) scale(0.85); }
            50% { opacity: 1; transform: translateY(-50%) scale(1.2); }
        }

        .sparkle-7 {
            animation-name: twinkle-center-top;
        }

        .sparkle-8 {
            animation-name: twinkle-center-bottom;
        }

        @keyframes twinkle-center-top {
            0%, 100% { opacity: 0.45; transform: translateX(-50%) scale(0.85); }
            50% { opacity: 1; transform: translateX(-50%) scale(1.2); }
        }

        @keyframes twinkle-center-bottom {
            0%, 100% { opacity: 0.45; transform: translateX(-50%) scale(0.85); }
            50% { opacity: 1; transform: translateX(-50%) scale(1.15); }
        }

        /* ── Dashboard Content ── */
        .dashboard-content {
            padding: 28px 32px 0;
            flex: 1;
        }

        .page-title {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 700;
            color: var(--green-dark);
            margin-bottom: 24px;
        }

        .hidden { display: none !important; }

        /* ── Barre de saisie ── */
        .saisie-panel {
            animation: saisieFadeIn 0.3s ease;
        }

        @keyframes saisieFadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .saisie-card {
            background: white;
            border-radius: 14px;
            border: 1px solid var(--border);
            box-shadow: 0 4px 24px rgba(0, 51, 38, 0.06);
            overflow: hidden;
        }

        /* Bon d'achat : en-tête fixe, champs défilants */
        #achatsView .saisie-card {
            display: flex;
            flex-direction: column;
            max-height: calc(100vh - var(--header-h) - 96px);
        }

        #achatsView .saisie-card-header {
            flex-shrink: 0;
            z-index: 2;
        }

        #achatsView .saisie-form {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-height: 0;
            padding: 0;
        }

        #achatsView #achatsPrintArea {
            display: flex;
            flex-direction: column;
            flex: 1;
            min-height: 0;
            overflow: hidden;
        }

        #achatsView .achats-form-scroll {
            flex: 0 1 auto;
            max-height: 46vh;
            min-height: 0;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 16px 18px;
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 51, 38, 0.28) transparent;
        }

        #achatsView .achats-form-scroll::-webkit-scrollbar {
            width: 7px;
        }

        #achatsView .achats-form-scroll::-webkit-scrollbar-thumb {
            background: rgba(0, 51, 38, 0.22);
            border-radius: 4px;
        }

        #achatsView .achats-doc-actions {
            flex-shrink: 0;
            margin-top: 0;
            padding: 12px 18px 16px;
            background: white;
            border-top: 1px solid var(--border);
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: flex-end;
            align-items: center;
        }

        #achatsView .achats-doc-actions .btn-list {
            padding: 9px 16px;
            font-size: 12px;
        }

        /* Bon d'achat : Infos fournisseur sur une seule ligne */
        #achatsView .achats-fr-inline-row {
            display: grid;
            grid-template-columns: 120px 160px 160px 1fr;
            gap: 10px 14px;
            align-items: end;
            padding: 10px 12px;
            border-radius: 12px;
            background: linear-gradient(180deg, rgba(249, 248, 243, 0.95) 0%, rgba(244, 241, 234, 0.9) 100%);
            border: 1px solid rgba(0, 51, 38, 0.08);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.75);
        }

        #achatsView .achats-pay-inline-row {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 8px 10px;
            align-items: end;
        }

        #achatsView .achats-liv-inline-row {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 8px 10px;
            align-items: end;
        }

        #achatsView .achats-pay-liv-card {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0;
            align-items: stretch;
            padding: 12px 14px;
            border-radius: 12px;
            background: linear-gradient(180deg, rgba(249, 248, 243, 0.95) 0%, rgba(244, 241, 234, 0.9) 100%);
            border: 1px solid rgba(0, 51, 38, 0.08);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.75);
        }

        #achatsView .achats-pay-liv-col {
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-height: 100%;
            padding: 0 10px;
        }

        #achatsView .achats-pay-liv-col .achats-pay-inline-row,
        #achatsView .achats-pay-liv-col .achats-liv-inline-row {
            flex: 1;
            align-content: end;
        }

        #achatsView .achats-pay-liv-col:first-child {
            border-right: 1px solid rgba(0, 51, 38, 0.1);
            padding-left: 0;
        }

        #achatsView .achats-pay-liv-col:last-child {
            padding-right: 0;
        }

        #achatsView .achats-subsection-title {
            font-family: 'Playfair Display', serif;
            font-size: 12px;
            font-weight: 700;
            color: var(--green-dark);
            letter-spacing: 0.04em;
            text-transform: uppercase;
            margin: 0;
            padding-bottom: 6px;
            border-bottom: 1px solid rgba(200, 149, 108, 0.35);
        }

        #achatsView .achats-articles-panel {
            flex: 1 1 auto;
            min-height: 210px;
            padding: 12px 18px 10px;
            border-top: 1px solid var(--border);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.98) 0%, rgba(249, 248, 243, 0.95) 100%);
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        #achatsView .achats-articles-panel .achats-articles-title {
            font-family: 'Playfair Display', serif;
            font-size: 13px;
            font-weight: 700;
            color: var(--green-dark);
            letter-spacing: 0.03em;
            margin: 0;
        }

        #achatsView .achats-articles-panel .fournisseur-table-wrap {
            flex: 1;
            min-height: 140px;
            max-height: 280px;
            overflow: auto;
        }

        #achatsView .achats-articles-panel .achats-lines-table thead th {
            position: sticky;
            top: 0;
            z-index: 2;
        }

        #achatsView .achats-fr-inline-row .form-group,
        #achatsView .achats-pay-inline-row .form-group,
        #achatsView .achats-liv-inline-row .form-group {
            min-width: 0;
        }

        #achatsView .achats-fr-inline-row .form-input,
        #achatsView .achats-fr-inline-row .form-select,
        #achatsView .achats-pay-inline-row .form-input,
        #achatsView .achats-pay-inline-row .form-select,
        #achatsView .achats-liv-inline-row .form-input,
        #achatsView .achats-liv-inline-row .form-select {
            min-height: 34px;
        }

        @media (max-width: 1100px) {
            #achatsView .achats-pay-liv-card {
                grid-template-columns: 1fr;
                gap: 12px;
            }

            #achatsView .achats-pay-liv-col:first-child {
                border-right: none;
                border-bottom: 1px solid rgba(0, 51, 38, 0.1);
                padding-bottom: 12px;
            }

            #achatsView .achats-liv-inline-row {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 900px) {
            #achatsView .achats-fr-inline-row,
            #achatsView .achats-pay-inline-row,
            #achatsView .achats-liv-inline-row {
                grid-template-columns: 1fr 1fr;
            }
        }

        .saisie-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            background: linear-gradient(135deg, var(--green-dark) 0%, #004d3a 100%);
            color: white;
        }

        .saisie-card-header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: 0.03em;
        }

        .saisie-card-header span {
            font-size: 10px;
            opacity: 0.75;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .saisie-form {
            padding: 16px 18px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px 14px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .form-group-full {
            grid-column: 1 / -1;
        }

        .form-group label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--green-dark);
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 6px 10px;
            font-size: 12px;
            line-height: 1.35;
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            background: #FDFCFA;
            border: 1px solid var(--border);
            border-radius: 6px;
            transition: border-color 0.15s, box-shadow 0.15s;
            min-height: 32px;
        }

        .form-select {
            padding-right: 28px;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: rgba(200, 149, 108, 0.65);
            box-shadow: 0 0 0 2px rgba(255, 215, 0, 0.12);
        }

        .form-input.readonly {
            background: var(--table-header);
            color: var(--text-muted);
            font-weight: 600;
            letter-spacing: 0.04em;
            font-size: 11px;
        }

        .form-textarea {
            min-height: 52px;
            resize: vertical;
        }

        .fr-inline-row {
            grid-column: 1 / -1;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px 14px;
            align-items: end;
        }

        .fr-inline-row-idnom {
            grid-template-columns: 90px 1.6fr 1fr 1fr;
        }

        .fr-inline-row-adr {
            grid-template-columns: 1.6fr 1fr 1fr 1fr 1.4fr;
        }

        @media (max-width: 900px) {
            .fr-inline-row,
            .fr-inline-row-idnom,
            .fr-inline-row-adr {
                grid-template-columns: 1fr 1fr;
            }
        }

        .rib-label {
            font-size: 10px !important;
            color: var(--text-muted) !important;
        }

        .rib-input {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            letter-spacing: 0.04em;
            text-align: center;
            padding: 8px 8px;
            background: white;
            min-height: 32px;
        }

        .form-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            margin-top: 16px;
            padding-top: 14px;
            border-top: 1px solid var(--border);
        }

        .btn-form {
            padding: 7px 16px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: background 0.15s, transform 0.15s;
        }

        .btn-form-primary {
            background: linear-gradient(135deg, var(--green-dark) 0%, #004d3a 100%);
            color: white;
            box-shadow: 0 4px 14px rgba(0, 51, 38, 0.2);
        }

        .btn-form-primary:hover { background: #004d3a; transform: translateY(-1px); }

        .btn-form-secondary {
            background: var(--table-header);
            color: var(--text-dark);
            border: 1px solid var(--border);
        }

        .btn-form-secondary:hover { background: var(--border); }

        .btn-form-outline {
            background: white;
            color: var(--green-dark);
            border: 1px solid var(--green-dark);
        }

        .btn-form-outline:hover { background: rgba(0, 51, 38, 0.06); }

        .saisie-section {
            margin-bottom: 16px;
            padding-bottom: 4px;
        }

        .saisie-section:last-of-type { margin-bottom: 0; }

        .saisie-section-title {
            font-family: 'Playfair Display', serif;
            font-size: 14px;
            font-weight: 700;
            color: var(--green-dark);
            margin-bottom: 10px;
            padding-bottom: 6px;
            border-bottom: 2px solid rgba(200, 149, 108, 0.35);
            letter-spacing: 0.02em;
        }

        .fournisseur-info-panel {
            grid-column: 1 / -1;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px 14px;
            padding: 10px 12px;
            background: rgba(255, 252, 247, 0.9);
            border: 1px solid var(--border);
            border-radius: 8px;
            margin-top: 2px;
        }

        .fournisseur-info-item {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .fournisseur-info-item span:first-child {
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--text-muted);
        }

        .fournisseur-info-item span:last-child {
            font-size: 11px;
            color: var(--text-dark);
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .fournisseur-info-panel { grid-template-columns: 1fr; }
            .form-grid { grid-template-columns: 1fr; }
            .achats-lignes-grid { grid-template-columns: 1fr; }
        }

        .achats-lignes-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px 12px;
        }

        .achats-lignes-grid .form-group-full {
            grid-column: 1 / -1;
        }

        .achats-lignes-grid .form-group-span2 {
            grid-column: span 2;
        }

        .achats-sous-total-input {
            background: var(--table-header) !important;
            font-weight: 700;
            color: var(--green-dark);
            text-align: right;
        }

        #achatsView .achats-lignes-scroll {
            overflow-x: auto;
            padding-bottom: 2px;
            width: 100%;
        }

        #achatsView .achats-lignes-inline-row {
            display: grid;
            grid-template-columns: 72px 132px minmax(120px, 1fr) 100px 88px 68px 130px 80px 96px;
            gap: 6px 8px;
            align-items: end;
            width: 100%;
            min-width: 900px;
            padding: 10px 12px;
            border-radius: 12px;
            background: linear-gradient(180deg, rgba(249, 248, 243, 0.95) 0%, rgba(244, 241, 234, 0.9) 100%);
            border: 1px solid rgba(0, 51, 38, 0.08);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.75);
        }

        #achatsView .achats-lignes-inline-row .form-group {
            min-width: 0;
        }

        #achatsView .achats-lignes-inline-row label {
            font-size: 9px;
            white-space: nowrap;
        }

        #achatsView .achats-lignes-inline-row .form-input,
        #achatsView .achats-lignes-inline-row .form-select {
            min-height: 32px;
            font-size: 11px;
            padding: 6px 8px;
        }

        #achatsView .achats-saisie-ligne .achats-line-actions {
            margin: 8px 0 4px;
            justify-content: flex-end;
        }

        .achats-line-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 12px 0 10px;
        }

        @media (max-width: 900px) {
            #achatsView .achats-lignes-inline-row {
                min-width: 860px;
            }
        }

        .achats-commandes-section {
            margin-bottom: 14px;
        }

        .achats-commandes-table {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            font-size: 12px;
            min-width: 1100px;
        }

        .achats-commandes-table col.col-cmd-bon { width: 8%; }
        .achats-commandes-table col.col-cmd-date { width: 9%; }
        .achats-commandes-table col.col-cmd-code { width: 7%; }
        .achats-commandes-table col.col-cmd-nom { width: 20%; }
        .achats-commandes-table col.col-cmd-ville { width: 10%; }
        .achats-commandes-table col.col-cmd-qte { width: 6%; }
        .achats-commandes-table col.col-cmd-total { width: 10%; }
        .achats-commandes-table col.col-cmd-reg { width: 8%; }
        .achats-commandes-table col.col-cmd-ech { width: 9%; }
        .achats-commandes-table col.col-cmd-actions { width: 13%; }

        .achats-commandes-table thead th,
        .achats-commandes-table tbody td {
            padding: 12px 10px;
            line-height: 1.4;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .achats-commandes-table thead th {
            background: var(--green-dark);
            color: white;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .achats-commandes-table tbody td {
            border-bottom: 1px solid var(--border);
            color: var(--text-dark);
        }

        .achats-commandes-table .cmd-col-nom {
            white-space: nowrap;
        }

        .achats-commandes-table td.col-actions-cmd {
            white-space: nowrap;
        }

        .col-actions-wrap,
        .cmd-actions-wrap {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            flex-wrap: nowrap;
        }

        .achats-commandes-table tbody tr {
            cursor: pointer;
            transition: background 0.12s;
        }

        .achats-commandes-table tbody tr:hover td {
            background: rgba(255, 252, 247, 0.85);
        }

        .achats-commandes-table tbody tr.selected td {
            background: rgba(255, 240, 232, 0.95);
        }

        .achats-commandes-empty {
            text-align: center;
            padding: 28px 16px;
            color: var(--text-muted);
            font-size: 13px;
        }

        #commandesView .fournisseur-table-wrap {
            overflow-x: auto;
        }

        /* Listes : carte à hauteur fixe, seul le tableau défile à l'intérieur */
        #fournisseurPrintArea .fournisseur-table-wrap,
        #commandesPrintArea .fournisseur-table-wrap,
        #produitPrintArea .fournisseur-table-wrap {
            max-height: calc(100vh - var(--header-h) - 130px);
            overflow: auto;
        }

        #fournisseurPrintArea .fournisseur-table thead th,
        #commandesPrintArea .achats-commandes-table thead th,
        #produitPrintArea .produits-table thead th {
            position: sticky;
            top: 0;
            z-index: 6;
        }

        .achats-lines-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            min-width: 900px;
        }

        .achats-lines-table thead th {
            background: var(--green-dark);
            color: white;
            padding: 10px 12px;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .achats-lines-table tbody td {
            padding: 10px 12px;
            border-bottom: 1px solid var(--border);
        }

        .achats-lines-table tbody tr {
            cursor: pointer;
            transition: background 0.12s;
        }

        .achats-lines-table tbody tr:hover td {
            background: rgba(255, 252, 247, 0.85);
        }

        .achats-lines-table tbody tr.selected td {
            background: rgba(255, 240, 232, 0.95);
        }

        .achats-lines-empty {
            text-align: center;
            padding: 32px 16px;
            color: var(--text-muted);
        }

        .achats-total-bar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 16px;
            padding: 16px 18px;
            margin-top: 12px;
            background: linear-gradient(135deg, rgba(0, 51, 38, 0.06) 0%, rgba(200, 149, 108, 0.08) 100%);
            border-radius: 10px;
            border: 1px solid var(--border);
        }

        .achats-total-bar span:first-child {
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--green-dark);
        }

        .achats-total-bar span:last-child {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 700;
            color: var(--green-dark);
        }

        .achats-doc-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: flex-end;
        }

        @media print {
            #achatsPrintArea,
            #achatsPrintArea * { visibility: visible; }
            #achatsPrintArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 16px;
            }
            .achats-line-actions,
            .achats-doc-actions,
            .achats-saisie-ligne,
            .no-print-achats { display: none !important; }
        }

        .list-toolbar {
            position: sticky;
            top: var(--header-h);
            z-index: 70;
            background: var(--main-bg);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            margin: 0 0 16px;
            padding: 14px 0 14px;
        }

        .list-toolbar-title {
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 700;
            color: var(--green-dark);
        }

        .list-toolbar-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .btn-list {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 18px;
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: background 0.15s, transform 0.15s;
        }

        .btn-list svg {
            width: 16px;
            height: 16px;
            flex-shrink: 0;
        }

        .btn-list-print {
            background: var(--green-dark);
            color: white;
        }

        .btn-list-print:hover { background: #004d3a; transform: translateY(-1px); }

        .btn-list-pdf {
            background: var(--orange-accent);
            color: white;
        }

        .btn-list-pdf:hover { background: #d45215; transform: translateY(-1px); }

        .btn-list-add {
            background: white;
            color: var(--green-dark);
            border: 1px solid var(--border);
        }

        .btn-list-add:hover { background: var(--table-header); }

        .fournisseur-table-wrap {
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: white;
        }

        .fournisseur-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            min-width: 960px;
        }

        .fournisseur-table thead th {
            background: var(--green-dark);
            color: white;
            padding: 12px 14px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .fournisseur-table tbody td {
            padding: 11px 14px;
            border-bottom: 1px solid var(--border);
            color: var(--text-dark);
        }

        .fournisseur-table tbody tr:hover td {
            background: rgba(255, 252, 247, 0.8);
        }

        .fournisseur-table tbody tr:last-child td {
            border-bottom: none;
        }

        .fournisseur-empty {
            text-align: center;
            padding: 48px 24px;
            color: var(--text-muted);
            font-size: 14px;
        }

        .col-actions { white-space: nowrap; }

        /* Alignement centré : en-têtes et données sur la même colonne */
        .fournisseur-table thead th,
        .fournisseur-table tbody td,
        .achats-lines-table thead th,
        .achats-lines-table tbody td,
        .achats-commandes-table thead th,
        .achats-commandes-table tbody td,
        .stock-table thead th,
        .stock-table tbody td,
        .produits-table thead th,
        .produits-table tbody td {
            text-align: center;
            vertical-align: middle;
        }

        .produits-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            min-width: 1100px;
            table-layout: fixed;
        }

        .produits-table thead th {
            background: var(--green-dark);
            color: white;
            padding: 12px 10px;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .produits-table tbody td {
            padding: 10px 10px;
            border-bottom: 1px solid var(--border);
            color: var(--text-dark);
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .produits-table tbody tr {
            cursor: pointer;
            transition: background 0.12s;
        }

        .produits-table tbody tr:hover td {
            background: rgba(255, 252, 247, 0.85);
        }

        .produits-table tbody tr.selected td {
            background: rgba(255, 240, 232, 0.95);
        }

        .produit-qr-cell {
            padding: 6px !important;
        }

        .produit-qr-cell canvas,
        .produit-qr-cell img {
            display: block;
            margin: 0 auto;
            width: 52px;
            height: 52px;
        }

        .produit-photo-cell {
            padding: 6px !important;
        }

        .produit-photo-thumb {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 52px;
            height: 52px;
            margin: 0 auto;
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
            background: #FDFCFA;
        }

        .produit-photo-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .produit-photo-thumb-empty {
            border-style: dashed;
            color: rgba(107, 107, 104, 0.5);
        }

        .produit-photo-thumb-empty svg {
            width: 20px;
            height: 20px;
        }

        .btn-icon-row {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            padding: 0;
            border-radius: 6px;
            border: 1px solid transparent;
            cursor: pointer;
            transition: background 0.15s, transform 0.15s;
        }

        .btn-icon-row svg {
            width: 14px;
            height: 14px;
        }

        .btn-icon-edit {
            background: rgba(0, 51, 38, 0.08);
            color: var(--green-dark);
        }

        .btn-icon-edit:hover {
            background: rgba(0, 51, 38, 0.18);
            transform: translateY(-1px);
        }

        .btn-icon-delete {
            background: rgba(155, 44, 44, 0.08);
            color: #9B2C2C;
        }

        .btn-icon-delete:hover {
            background: rgba(155, 44, 44, 0.18);
            transform: translateY(-1px);
        }

        .produits-empty {
            text-align: center;
            padding: 28px 16px;
            color: var(--text-muted);
            font-size: 13px;
        }

        .produits-table .col-designation {
            white-space: nowrap;
        }

        /* Fiche Produit — barre compacte + photo */
        #ficheProduitView .produit-form-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 168px;
            gap: 14px 16px;
            align-items: start;
        }

        #ficheProduitView .produit-form-fields {
            min-width: 0;
        }

        #ficheProduitView .pr-inline-row {
            display: grid;
            gap: 8px 10px;
            align-items: end;
            margin-bottom: 8px;
        }

        #ficheProduitView .pr-inline-row:last-child {
            margin-bottom: 0;
        }

        #ficheProduitView .pr-inline-row-1 {
            grid-template-columns: 68px minmax(0, 1.5fr) minmax(0, 1fr);
        }

        #ficheProduitView .pr-inline-row-2 {
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) 72px 72px;
        }

        #ficheProduitView .pr-inline-row-3 {
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            max-width: 360px;
        }

        #ficheProduitView .produit-form-fields .form-input,
        #ficheProduitView .produit-form-fields .form-select {
            min-height: 30px;
            padding: 5px 8px;
            font-size: 11px;
        }

        #ficheProduitView .produit-form-fields label {
            font-size: 9px;
        }

        #ficheProduitView .produit-photo-panel {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        #ficheProduitView .produit-photo-panel > label {
            font-size: 9px;
            font-weight: 600;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: var(--green-dark);
        }

        #ficheProduitView .produit-photo-preview {
            width: 100%;
            aspect-ratio: 1;
            border: 2px dashed rgba(0, 51, 38, 0.2);
            border-radius: 8px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(145deg, #FDFCFA 0%, #F3F1EA 100%);
            position: relative;
        }

        #ficheProduitView .produit-photo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        #ficheProduitView .produit-photo-placeholder {
            font-size: 10px;
            color: var(--text-muted);
            text-align: center;
            padding: 8px;
            line-height: 1.35;
        }

        #ficheProduitView .produit-photo-actions {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        #ficheProduitView .btn-photo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            width: 100%;
            padding: 6px 8px;
            font-size: 10px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            border-radius: 6px;
            border: 1px solid var(--border);
            background: white;
            color: var(--green-dark);
            cursor: pointer;
            transition: background 0.15s, border-color 0.15s;
        }

        #ficheProduitView .btn-photo:hover {
            background: var(--table-header);
            border-color: rgba(0, 51, 38, 0.25);
        }

        #ficheProduitView .btn-photo-danger {
            color: #9B2C2C;
            border-color: rgba(155, 44, 44, 0.25);
        }

        #ficheProduitView .btn-photo svg {
            width: 12px;
            height: 12px;
            flex-shrink: 0;
        }

        @media (max-width: 820px) {
            #ficheProduitView .produit-form-layout {
                grid-template-columns: 1fr;
            }

            #ficheProduitView .produit-photo-panel {
                max-width: 200px;
            }

            #ficheProduitView .pr-inline-row-1,
            #ficheProduitView .pr-inline-row-2,
            #ficheProduitView .pr-inline-row-3 {
                grid-template-columns: 1fr 1fr;
            }

            #ficheProduitView .pr-inline-row-1 .form-group:nth-child(2) {
                grid-column: 1 / -1;
            }
        }

        .btn-list-modify:disabled {
            opacity: 0.45;
            cursor: not-allowed;
            transform: none !important;
        }

        .btn-row {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 10px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: background 0.15s, transform 0.15s;
        }

        .btn-row-edit {
            background: rgba(0, 51, 38, 0.1);
            color: var(--green-dark);
        }

        .btn-row-edit:hover { background: rgba(0, 51, 38, 0.18); }

        .btn-row-delete {
            background: rgba(196, 75, 110, 0.12);
            color: var(--status-rupture);
        }

        .btn-row-delete:hover { background: rgba(196, 75, 110, 0.22); }

        .solde-cell {
            font-weight: 600;
            white-space: nowrap;
        }

        .solde-positive { color: var(--status-dispo); }
        .solde-negative { color: var(--status-rupture); }
        .solde-zero { color: var(--text-muted); }

        @media print {
            .col-actions { display: none !important; }
        }

        @media print {
            body * { visibility: hidden; }
            #fournisseurPrintArea,
            #fournisseurPrintArea *,
            #commandesPrintArea,
            #commandesPrintArea *,
            #produitPrintArea,
            #produitPrintArea * { visibility: visible; }
            #fournisseurPrintArea,
            #commandesPrintArea,
            #produitPrintArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 20px;
            }
            .list-toolbar,
            .list-toolbar-actions,
            .no-print-cmd,
            .no-print-produit { display: none !important; }
            #fournisseurPrintArea .fournisseur-table-wrap,
            #commandesPrintArea .fournisseur-table-wrap,
            #produitPrintArea .fournisseur-table-wrap {
                max-height: none !important;
                overflow: visible !important;
            }
        }

        /* Impression dédiée : Bon d'achat (consultation) */
        @media print {
            body.print-achats-consult * { visibility: hidden; }
            body.print-achats-consult #commandesPrintAreaAchats,
            body.print-achats-consult #commandesPrintAreaAchats * { visibility: visible; }
            body.print-achats-consult #commandesPrintAreaAchats {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 20px;
            }
            body.print-achats-consult .list-toolbar,
            body.print-achats-consult .list-toolbar-actions,
            body.print-achats-consult .no-print-cmd { display: none !important; }
            body.print-achats-consult #commandesPrintAreaAchats .fournisseur-table-wrap {
                max-height: none !important;
                overflow: visible !important;
            }
        }

        /* ── KPI Cards ── */
        .kpi-grid {
            position: sticky;
            top: var(--header-h);
            z-index: 60;
            background: var(--main-bg);
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 0;
            padding: 10px 0 16px;
        }

        .kpi-card {
            border-radius: 12px;
            padding: 20px 22px;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .kpi-card.green  { background: var(--green-card); }
        .kpi-card.blue   { background: var(--blue-card); }
        .kpi-card.orange { background: var(--orange-card); }
        .kpi-card.brown  { background: var(--brown-card); }

        .kpi-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .kpi-icon {
            width: 36px;
            height: 36px;
            background: rgba(255,255,255,0.15);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .kpi-badge {
            font-size: 11px;
            font-weight: 600;
            background: rgba(255,255,255,0.2);
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .kpi-badge svg {
            width: 14px;
            height: 14px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2.5;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex-shrink: 0;
        }

        .kpi-badge-up   { background: rgba(255, 255, 255, 0.25); }
        .kpi-badge-down { background: rgba(0, 0, 0, 0.15); }
        .kpi-badge-flat { background: rgba(255, 255, 255, 0.18); }

        .kpi-label {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            opacity: 0.85;
            margin-bottom: 6px;
        }

        .kpi-value {
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -0.02em;
        }

        /* ── Stock Tables ── */
        .tables-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 24px;
        }

        .table-card {
            background: var(--white);
            border-radius: 12px;
            border: 1px solid var(--border);
            overflow: hidden;
        }

        .table-card-title {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--text-dark);
            padding: 16px 20px 12px;
        }

        .stock-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12.5px;
        }

        .stock-table thead th {
            background: var(--table-header);
            padding: 8px 16px;
            font-size: 11px;
            font-weight: 600;
            color: var(--text-muted);
            letter-spacing: 0.04em;
        }

        .stock-table tbody td {
            padding: 10px 16px;
            border-top: 1px solid var(--border);
            color: var(--text-dark);
        }

        .stock-table tbody tr:last-child td { padding-bottom: 14px; }

        .status-pill {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.05em;
        }

        .status-dispo   { background: var(--status-dispo-bg);   color: var(--status-dispo); }
        .status-faible  { background: var(--status-faible-bg);  color: var(--status-faible); }
        .status-rupture { background: var(--status-rupture-bg); color: var(--status-rupture); }

        /* ── Charts Section ── */
        .charts-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 32px;
        }

        .charts-grid .chart-section.span-full {
            grid-column: 1 / -1;
        }

        .chart-section {
            background: var(--white);
            border-radius: 12px;
            border: 1px solid var(--border);
            padding: 20px 24px 16px;
        }

        .chart-section-title {
            font-family: 'Playfair Display', serif;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .chart-subtitle {
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 16px;
        }

        .chart-container {
            position: relative;
            height: 260px;
        }

        .chart-container.tall {
            height: 300px;
        }

        /* ── Footer ── */
        .page-footer {
            border-top: 1px solid var(--border);
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 11.5px;
            color: var(--text-light);
        }

        .footer-links {
            display: flex;
            gap: 20px;
            list-style: none;
        }

        .footer-links a {
            color: var(--text-light);
            text-decoration: none;
        }

        .footer-links a:hover { color: var(--text-muted); }

        /* ===== Interface d'accueil / Connexion ===== */
        .landing-screen {
            position: fixed;
            inset: 0;
            z-index: 4000;
            display: flex;
            flex-direction: column;
            overflow: auto;
            background: #00231a;
        }

        .landing-screen.is-hidden {
            display: none;
        }

        .landing-bg {
            position: absolute;
            inset: 0;
            background: url('{{ asset('images/fruits-secs.jpg') }}') center/cover no-repeat;
            transform: scale(1.05);
            filter: saturate(1.1);
            z-index: 0;
        }

        .landing-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(0, 35, 26, 0.82) 0%, rgba(0, 51, 38, 0.7) 45%, rgba(60, 30, 10, 0.78) 100%);
            z-index: 1;
        }

        .landing-header {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 18px;
            padding: 16px 34px;
            flex-wrap: wrap;
            backdrop-filter: blur(6px);
            background: rgba(0, 25, 18, 0.35);
            border-bottom: 1px solid rgba(212, 175, 110, 0.25);
        }

        .landing-brand {
            display: flex;
            align-items: center;
            gap: 16px;
            text-decoration: none;
            margin-right: auto;
        }

        .landing-brand-logo {
            position: relative;
            width: 54px;
            height: 54px;
            border-radius: 50%;
            overflow: hidden;
            display: grid;
            place-items: center;
            background: radial-gradient(circle at 30% 25%, #ffffff 0%, #fff7e9 60%, #f3e3c4 100%);
            border: 2px solid rgba(233, 197, 119, 0.9);
            box-shadow:
                0 0 0 4px rgba(233, 197, 119, 0.18),
                0 6px 22px rgba(0, 0, 0, 0.4),
                0 0 26px rgba(233, 197, 119, 0.55);
            animation: brandGlow 3.2s ease-in-out infinite;
        }

        @keyframes brandGlow {
            0%, 100% {
                box-shadow:
                    0 0 0 4px rgba(233, 197, 119, 0.18),
                    0 6px 22px rgba(0, 0, 0, 0.4),
                    0 0 22px rgba(233, 197, 119, 0.45);
            }
            50% {
                box-shadow:
                    0 0 0 5px rgba(233, 197, 119, 0.28),
                    0 6px 22px rgba(0, 0, 0, 0.4),
                    0 0 38px rgba(233, 197, 119, 0.85);
            }
        }

        .landing-brand-logo img {
            width: 86%;
            height: 86%;
            object-fit: contain;
        }

        .landing-brand-text {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: 0.12em;
            background: linear-gradient(135deg, #fff7e6 0%, #f3d68f 35%, #e9c577 55%, #fff7e6 100%);
            background-size: 200% auto;
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: #f3d68f;
            text-shadow: 0 2px 18px rgba(233, 197, 119, 0.5);
            animation: brandShine 5s linear infinite;
        }

        @keyframes brandShine {
            0% { background-position: 0% center; }
            100% { background-position: 200% center; }
        }

        .landing-nav-links {
            position: static;
            transform: none;
            left: auto;
            top: auto;
            background: transparent;
            margin: 0 auto;
            gap: 26px;
        }

        .landing-nav-links > li > a {
            color: #f4ecdd;
            padding: 9px 16px;
            border-radius: 999px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.14) 0%, rgba(255, 255, 255, 0.06) 100%);
            border: 1px solid rgba(233, 197, 119, 0.35);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.2);
            transition: background 0.2s, border-color 0.2s, color 0.2s, transform 0.15s, box-shadow 0.2s;
        }

        .landing-nav-links > li > a:hover,
        .landing-nav-links > li.nav-dropdown-pin.is-open > .nav-dropdown-toggle {
            color: #2a1a05;
            background: linear-gradient(135deg, #e9c577 0%, #d4a86a 100%);
            border-color: #e9c577;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(212, 168, 106, 0.45);
        }

        .landing-nav-links > li > a::after {
            display: none;
        }

        .landing-social {
            margin-left: 6px;
        }

        .landing-body {
            position: relative;
            z-index: 2;
            flex: 1;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
            padding: 40px 8vw;
        }

        .landing-hero-text {
            max-width: 540px;
            color: #fff;
        }

        .landing-hero-text h1 {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: clamp(36px, 5vw, 62px);
            line-height: 1.05;
            font-weight: 800;
            margin: 0 0 18px;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.55);
        }

        .landing-hero-text h1 span {
            display: block;
            color: #e9c577;
        }

        .landing-hero-text p {
            font-size: clamp(16px, 2vw, 22px);
            color: #f0e6d4;
            line-height: 1.5;
            margin: 0;
            text-shadow: 0 2px 12px rgba(0, 0, 0, 0.5);
        }

        .landing-connect-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 9px 18px;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.03em;
            color: #2a1a05;
            background: linear-gradient(135deg, #e9c577 0%, #d4a86a 100%);
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 999px;
            cursor: pointer;
            box-shadow: 0 6px 18px rgba(212, 168, 106, 0.4);
            transition: transform 0.15s, box-shadow 0.2s, background 0.2s;
            white-space: nowrap;
        }

        .landing-nav-links .landing-connect-btn {
            margin: 0;
        }

        .landing-connect-btn svg {
            width: 15px;
            height: 15px;
        }

        .landing-connect-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 34px rgba(212, 168, 106, 0.55);
            background: linear-gradient(135deg, #f0d08a 0%, #e0b878 100%);
        }

        .landing-connect-btn:active {
            transform: translateY(0);
        }

        .login-panel {
            width: 380px;
            max-width: 100%;
            opacity: 0;
            visibility: hidden;
            transform: translateY(16px) scale(0.98);
            transition: opacity 0.25s ease, transform 0.25s ease, visibility 0.25s;
            pointer-events: none;
        }

        .login-panel.is-open {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
            pointer-events: auto;
        }

        .login-panel-close {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
            cursor: pointer;
            transition: background 0.15s;
        }

        .login-panel-close:hover {
            background: rgba(255, 255, 255, 0.22);
        }

        .login-panel-close svg {
            width: 16px;
            height: 16px;
        }

        .login-card {
            position: relative;
            width: 100%;
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 20px;
            padding: 34px 30px;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.45);
        }

        .login-title {
            margin: 0;
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 28px;
            font-weight: 800;
            color: #fff;
            text-align: center;
        }

        .login-subtitle {
            margin: 6px 0 26px;
            text-align: center;
            font-size: 13px;
            color: rgba(255, 255, 255, 0.8);
        }

        .login-error {
            margin: 0 0 16px;
            padding: 10px 12px;
            border-radius: 10px;
            background: rgba(214, 64, 41, 0.22);
            border: 1px solid rgba(255, 120, 100, 0.55);
            color: #ffd9d2;
            font-size: 12.5px;
            text-align: center;
        }

        .login-field {
            position: relative;
            display: flex;
            align-items: center;
            margin-bottom: 16px;
            background: rgba(255, 255, 255, 0.16);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .login-field:focus-within {
            border-color: #e9c577;
            box-shadow: 0 0 0 3px rgba(233, 197, 119, 0.25);
        }

        .login-icon {
            display: grid;
            place-items: center;
            width: 44px;
            height: 46px;
            color: #e9c577;
            flex-shrink: 0;
        }

        .login-icon svg {
            width: 20px;
            height: 20px;
        }

        .login-field input {
            flex: 1;
            border: none;
            background: transparent;
            outline: none;
            padding: 12px 8px 12px 0;
            font-size: 14px;
            color: #fff;
        }

        .login-field input::placeholder {
            color: rgba(255, 255, 255, 0.65);
        }

        /* Corrige l'autofill : garde un fond translucide et un texte lisible */
        .login-field input:-webkit-autofill,
        .login-field input:-webkit-autofill:hover,
        .login-field input:-webkit-autofill:focus,
        .login-field input:-webkit-autofill:active {
            -webkit-text-fill-color: #2a1a05 !important;
            caret-color: #2a1a05;
            -webkit-box-shadow: 0 0 0 1000px #f6ecd8 inset !important;
            box-shadow: 0 0 0 1000px #f6ecd8 inset !important;
            border-radius: 12px;
            transition: background-color 9999s ease-in-out 0s;
        }

        .login-eye {
            background: none;
            border: none;
            cursor: pointer;
            color: rgba(255, 255, 255, 0.75);
            display: grid;
            place-items: center;
            width: 42px;
            height: 46px;
        }

        .login-eye svg {
            width: 18px;
            height: 18px;
        }

        .login-eye:hover {
            color: #e9c577;
        }

        .login-btn {
            width: 100%;
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 13px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.03em;
            color: #2a1a05;
            background: linear-gradient(135deg, #e9c577 0%, #d4a86a 100%);
            box-shadow: 0 10px 26px rgba(212, 168, 106, 0.45);
            transition: transform 0.15s, box-shadow 0.2s, filter 0.2s;
        }

        .login-btn svg {
            width: 18px;
            height: 18px;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            filter: brightness(1.05);
            box-shadow: 0 14px 32px rgba(212, 168, 106, 0.6);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .landing-footer {
            position: relative;
            z-index: 2;
            text-align: center;
            padding: 14px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
            background: rgba(0, 25, 18, 0.35);
            border-top: 1px solid rgba(212, 175, 110, 0.2);
        }

        .top-nav-logout {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.25);
            color: #fff;
            padding: 7px 14px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, transform 0.15s;
        }

        .top-nav-logout svg {
            width: 16px;
            height: 16px;
        }

        .top-nav-logout:hover {
            background: rgba(255, 255, 255, 0.22);
            transform: translateY(-1px);
        }

        @media (max-width: 760px) {
            .landing-nav-links { order: 3; width: 100%; justify-content: center; }
            .landing-body { justify-content: center; text-align: center; }
            .landing-hero-text { text-align: center; }
        }
    </style>
</head>
<body>

    {{-- ===== Interface d'accueil / Connexion ===== --}}
    <div id="landingScreen" class="landing-screen">
        <div class="landing-bg" aria-hidden="true"></div>
        <div class="landing-overlay" aria-hidden="true"></div>

        <header class="landing-header">
            <a href="#" class="landing-brand" translate="no">
                <span class="landing-brand-logo">
                    <img src="{{ asset('images/sweet-austria-logo.png') }}" alt="Sweet Austria">
                </span>
                <span class="landing-brand-text">SWEET AUSTRIA</span>
            </a>

            <ul class="top-nav-links landing-nav-links">
                <li><a href="#" translate="no">Home</a></li>
                <li class="nav-dropdown nav-dropdown-pin" id="categoriesDropdown">
                    <a href="#" class="nav-dropdown-toggle" aria-expanded="false" aria-haspopup="true">Catégories <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg></a>
                    <ul class="nav-dropdown-menu">
                        <li class="nav-dropdown-menu-label">Nos gammes produits</li>
                        <li>
                            <a href="#" class="category-link nav-sub-link" data-category="coque" onclick="return window.openCategoryGallery(event, 'coque')">
                                <span class="sub-link-icon cat-coque">🌰</span>
                                <span class="sub-link-content">
                                    <span class="sub-link-title">Fruits à coque</span>
                                    <span class="sub-link-desc">Noix et graines nobles</span>
                                </span>
                                <span class="sub-link-arrow" aria-hidden="true"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="category-link nav-sub-link" data-category="seche" onclick="return window.openCategoryGallery(event, 'seche')">
                                <span class="sub-link-icon cat-seche">🍇</span>
                                <span class="sub-link-content">
                                    <span class="sub-link-title">Fruits séchés</span>
                                    <span class="sub-link-desc">Dattes, figues &amp; abricots</span>
                                </span>
                                <span class="sub-link-arrow" aria-hidden="true"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="category-link nav-sub-link" data-category="cacahuetes" onclick="return window.openCategoryGallery(event, 'cacahuetes')">
                                <span class="sub-link-icon cat-cacahuetes">🥜</span>
                                <span class="sub-link-content">
                                    <span class="sub-link-title">Cacahuètes et dérivés</span>
                                    <span class="sub-link-desc">Grillées, salées &amp; enrobées</span>
                                </span>
                                <span class="sub-link-arrow" aria-hidden="true"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="category-link nav-sub-link" data-category="graines" onclick="return window.openCategoryGallery(event, 'graines')">
                                <span class="sub-link-icon cat-graines">🌻</span>
                                <span class="sub-link-content">
                                    <span class="sub-link-title">Graines alimentaires</span>
                                    <span class="sub-link-desc">Chia, lin, tournesol &amp; plus</span>
                                </span>
                                <span class="sub-link-arrow" aria-hidden="true"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="category-link nav-sub-link" data-category="enrobes" onclick="return window.openCategoryGallery(event, 'enrobes')">
                                <span class="sub-link-icon cat-enrobes">🍬</span>
                                <span class="sub-link-content">
                                    <span class="sub-link-title">Fruits secs enrobés</span>
                                    <span class="sub-link-desc">Chocolat &amp; confiseries</span>
                                </span>
                                <span class="sub-link-arrow" aria-hidden="true"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="category-link nav-sub-link" data-category="ramadan" onclick="return window.openCategoryGallery(event, 'ramadan')">
                                <span class="sub-link-icon cat-ramadan">🕌</span>
                                <span class="sub-link-content">
                                    <span class="sub-link-title">Produits Ramadan &amp; Fêtes</span>
                                    <span class="sub-link-desc">Coffrets &amp; assortiments</span>
                                </span>
                                <span class="sub-link-arrow" aria-hidden="true"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-dropdown nav-dropdown-pin" id="commercialDropdown">
                    <a href="#" class="nav-dropdown-toggle" aria-expanded="false" aria-haspopup="true">Commercial <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg></a>
                    <ul class="nav-dropdown-menu">
                        <li class="nav-dropdown-menu-label">Zones commerciales</li>
                        <li>
                            <a href="#" class="zone-link nav-sub-link" data-zone="est" onclick="return window.openZoneVisitCard(event, 'est')">
                                <span class="zone-icon zone-est">
                                    <svg viewBox="0 0 24 24"><circle cx="12" cy="10" r="3"/><path d="M12 21s7-4.5 7-11a7 7 0 1 0-14 0c0 6.5 7 11 7 11z"/><path d="M16 7l2-2"/><path d="M18 7h-2"/></svg>
                                </span>
                                <span class="sub-link-content">
                                    <span class="sub-link-title">Zone Est</span>
                                    <span class="sub-link-desc">Carte de visite commercial</span>
                                </span>
                                <span class="sub-link-arrow" aria-hidden="true"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="zone-link nav-sub-link" data-zone="ouest" onclick="return window.openZoneVisitCard(event, 'ouest')">
                                <span class="zone-icon zone-ouest">
                                    <svg viewBox="0 0 24 24"><circle cx="12" cy="10" r="3"/><path d="M12 21s7-4.5 7-11a7 7 0 1 0-14 0c0 6.5 7 11 7 11z"/><path d="M8 7L6 5"/><path d="M6 7h2"/></svg>
                                </span>
                                <span class="sub-link-content">
                                    <span class="sub-link-title">Zone Ouest</span>
                                    <span class="sub-link-desc">Carte de visite commercial</span>
                                </span>
                                <span class="sub-link-arrow" aria-hidden="true"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="zone-link nav-sub-link" data-zone="taza" onclick="return window.openZoneVisitCard(event, 'taza')">
                                <span class="zone-icon zone-taza">
                                    <svg viewBox="0 0 24 24"><path d="M4 20 L8 8 L12 14 L16 6 L20 20 Z"/><path d="M4 20h16"/></svg>
                                </span>
                                <span class="sub-link-content">
                                    <span class="sub-link-title">Zone Taza, Fès</span>
                                    <span class="sub-link-desc">Carte de visite commercial</span>
                                </span>
                                <span class="sub-link-arrow" aria-hidden="true"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="zone-link nav-sub-link" data-zone="gharb" onclick="return window.openZoneVisitCard(event, 'gharb')">
                                <span class="zone-icon zone-gharb">
                                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76" fill="currentColor" stroke="none"/></svg>
                                </span>
                                <span class="sub-link-content">
                                    <span class="sub-link-title">Zone Elgharb</span>
                                    <span class="sub-link-desc">Carte de visite commercial</span>
                                </span>
                                <span class="sub-link-arrow" aria-hidden="true"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="zone-link nav-sub-link" data-zone="casa" onclick="return window.openZoneVisitCard(event, 'casa')">
                                <span class="zone-icon zone-casa">
                                    <svg viewBox="0 0 24 24"><path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 21v-6h6v6"/><path d="M9 9h1"/><path d="M14 9h1"/></svg>
                                </span>
                                <span class="sub-link-content">
                                    <span class="sub-link-title">Zone Casablanca</span>
                                    <span class="sub-link-desc">Carte de visite commercial</span>
                                </span>
                                <span class="sub-link-arrow" aria-hidden="true"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-dropdown nav-dropdown-pin" id="partnersDropdown">
                    <a href="#" class="nav-dropdown-toggle" aria-expanded="false" aria-haspopup="true">Nos Partenaires <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg></a>
                    <ul class="nav-dropdown-menu">
                        <li class="nav-dropdown-menu-label">Réseau de distribution</li>
                        <li>
                            <a href="#" class="partner-link nav-sub-link" data-partner="marjane" onclick="return window.openPartnerProfile(event, 'marjane')">
                                <span class="partner-icon partner-marjane">
                                    <svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                </span>
                                <span class="sub-link-content">
                                    <span class="sub-link-title">Marjane</span>
                                    <span class="sub-link-desc">Grande distribution nationale</span>
                                </span>
                                <span class="sub-link-arrow" aria-hidden="true"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="partner-link nav-sub-link" data-partner="decathlon" onclick="return window.openPartnerProfile(event, 'decathlon')">
                                <span class="partner-icon partner-decathlon">
                                    <svg viewBox="0 0 24 24"><circle cx="12" cy="5" r="2"/><path d="M10 22V12l-2 4"/><path d="M14 22V12l2 4"/><path d="M8 12h8"/><path d="M12 7v5"/></svg>
                                </span>
                                <span class="sub-link-content">
                                    <span class="sub-link-title">Décathlon</span>
                                    <span class="sub-link-desc">Nutrition sportive &amp; bien-être</span>
                                </span>
                                <span class="sub-link-arrow" aria-hidden="true"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="partner-link nav-sub-link" data-partner="atacadaw" onclick="return window.openPartnerProfile(event, 'atacadaw')">
                                <span class="partner-icon partner-atacadaw">
                                    <svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                                </span>
                                <span class="sub-link-content">
                                    <span class="sub-link-title">Atacadaw</span>
                                    <span class="sub-link-desc">Cash &amp; carry professionnel</span>
                                </span>
                                <span class="sub-link-arrow" aria-hidden="true"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li><a href="#">Contacts</a></li>
                <li>
                    <button type="button" class="landing-connect-btn" id="openLoginBtn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                        Se Connecter
                    </button>
                </li>
            </ul>

            <div class="social-icons landing-social">
                <a href="#" class="social-btn social-facebook" title="Facebook" aria-label="Facebook">
                    <svg viewBox="0 0 24 24"><path d="M24 12.073C24 5.405 18.627 0 12 0S0 5.405 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/></svg>
                </a>
                <a href="#" class="social-btn social-instagram" title="Instagram" aria-label="Instagram">
                    <svg viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/></svg>
                </a>
                <a href="#" class="social-btn social-tiktok" title="TikTok" aria-label="TikTok">
                    <svg viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 0 1-2.88 2.5 2.89 2.89 0 0 1-2.89-2.89 2.89 2.89 0 0 1 2.89-2.89c.28 0 .54.04.79.1V9.01a6.27 6.27 0 0 0-.79-.05 6.34 6.34 0 0 0-6.34 6.34 6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.33-6.34V8.69a8.18 8.18 0 0 0 4.78 1.52V6.76a4.85 4.85 0 0 1-1.01-.07z"/></svg>
                </a>
                <a href="#" class="social-btn social-youtube" title="YouTube" aria-label="YouTube">
                    <svg viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                </a>
            </div>
        </header>

        <div class="landing-body">
            <div class="landing-hero-text">
                <h1>Sweet Austria <span>Enterprise</span></h1>
                <p>La plateforme la plus proche des goûts de luxe</p>
            </div>

            <div class="login-panel" id="loginPanel">
            <form class="login-card" id="loginForm" autocomplete="off">
                <button type="button" class="login-panel-close" id="closeLoginBtn" aria-label="Fermer">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
                <h2 class="login-title">Connexion</h2>
                <p class="login-subtitle">Accédez à votre espace de gestion</p>

                <div class="login-error" id="loginError" hidden>Identifiant ou mot de passe incorrect.</div>

                <div class="login-field">
                    <span class="login-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </span>
                    <input type="text" id="loginUser" name="login_user" placeholder="Identifiant" autocomplete="username" value="superadmin@sweetaustria.com">
                </div>

                <div class="login-field">
                    <span class="login-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </span>
                    <input type="password" id="loginPass" name="login_pass" placeholder="Mot de passe" autocomplete="current-password" value="mot de passe">
                    <button type="button" class="login-eye" id="loginEyeBtn" aria-label="Afficher le mot de passe">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>

                <button type="submit" class="login-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                    Se connecter
                </button>
            </form>
            </div>
        </div>

        <div class="landing-footer">© {{ date('Y') }} Sweet Austria Enterprise — Tous droits réservés</div>
    </div>

    {{-- Logo header (aligné avec hero) --}}
    <div class="logo-header-box">
        <span class="logo-header-glow" aria-hidden="true"></span>
        <div class="logo-circle">
            <img src="{{ asset('images/sweet-austria-logo.png') }}" alt="Sweet Austria">
        </div>
    </div>

    {{-- Sidebar --}}
    <aside class="sidebar">
        <nav class="sidebar-nav">
            <a class="nav-item active" href="#" data-view="dashboard">
                <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                <span class="nav-label">Dashboard</span>
            </a>

            <div class="nav-group">
                <button type="button" class="nav-item nav-group-toggle" aria-expanded="false">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    <span class="nav-label">Fournisseur</span>
                    <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <ul class="nav-submenu">
                    <li><a href="#" class="nav-subitem" data-view="fiche-fournisseur">Fiche fournisseur</a></li>
                    <li><a href="#" class="nav-subitem" data-view="commandes">Bon De Commande</a></li>
                    <li><a href="#" class="nav-subitem" data-view="achats">Achats</a></li>
                    <li><a href="#" class="nav-subitem">Règlement Achats</a></li>
                    <li><a href="#" class="nav-subitem">Balance Achats</a></li>
                    <li><a href="#" class="nav-subitem">Relevé Compte Frns</a></li>
                </ul>
            </div>

            <div class="nav-group">
                <button type="button" class="nav-item nav-group-toggle" aria-expanded="false">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
                    <span class="nav-label">Stock</span>
                    <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <ul class="nav-submenu">
                    <li><a href="#" class="nav-subitem" data-view="fiche-produit">Fiche Produit</a></li>
                    <li><a href="#" class="nav-subitem">Stock Produits</a></li>
                    <li><a href="#" class="nav-subitem">Stock Produit Fini</a></li>
                    <li><a href="#" class="nav-subitem">Stock Produit Divers</a></li>
                </ul>
            </div>

            <div class="nav-group">
                <button type="button" class="nav-item nav-group-toggle" aria-expanded="false">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4"/><path d="M12 18v4"/><path d="M4.93 4.93l2.83 2.83"/><path d="M16.24 16.24l2.83 2.83"/><path d="M2 12h4"/><path d="M18 12h4"/><path d="M4.93 19.07l2.83-2.83"/><path d="M16.24 7.76l2.83-2.83"/></svg>
                    <span class="nav-label">Production</span>
                    <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <ul class="nav-submenu">
                    <li><a href="#" class="nav-subitem">État Journalier Quantité</a></li>
                    <li><a href="#" class="nav-subitem">État Journalier Production</a></li>
                    <li><a href="#" class="nav-subitem">État Journalier Sortie</a></li>
                    <li><a href="#" class="nav-subitem">État Journalier Dépense</a></li>
                </ul>
            </div>

            <div class="nav-group">
                <button type="button" class="nav-item nav-group-toggle" aria-expanded="false">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span class="nav-label">Client</span>
                    <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <ul class="nav-submenu">
                    <li><a href="#" class="nav-subitem">Fiche Client</a></li>
                    <li><a href="#" class="nav-subitem">Vente</a></li>
                    <li><a href="#" class="nav-subitem">Règlement</a></li>
                    <li><a href="#" class="nav-subitem">Balance</a></li>
                    <li><a href="#" class="nav-subitem">Relevé Compte Clt</a></li>
                </ul>
            </div>

            <div class="nav-group">
                <button type="button" class="nav-item nav-group-toggle" aria-expanded="false">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                    <span class="nav-label">Banque</span>
                    <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <ul class="nav-submenu">
                    <li><a href="#" class="nav-subitem">Débit</a></li>
                    <li><a href="#" class="nav-subitem">Crédit</a></li>
                    <li><a href="#" class="nav-subitem">Caisse</a></li>
                </ul>
            </div>

            <div class="nav-group">
                <button type="button" class="nav-item nav-group-toggle" aria-expanded="false">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    <span class="nav-label">Rapport</span>
                    <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <ul class="nav-submenu">
                    <li><a href="#" class="nav-subitem">État Achats</a></li>
                    <li><a href="#" class="nav-subitem">État Vente</a></li>
                    <li><a href="#" class="nav-subitem">État Stock</a></li>
                    <li><a href="#" class="nav-subitem">État Paiement</a></li>
                </ul>
            </div>

            <div class="nav-group">
                <button type="button" class="nav-item nav-group-toggle" aria-expanded="false">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                    <span class="nav-label">Configuration</span>
                    <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <ul class="nav-submenu">
                    <li><a href="#" class="nav-subitem">Fiche Société</a></li>
                    <li><a href="#" class="nav-subitem">Utilisateur</a></li>
                    <li><a href="#" class="nav-subitem">Trésorerie</a></li>
                    <li><a href="#" class="nav-subitem">Banque</a></li>
                    <li><a href="#" class="nav-subitem">Caisse</a></li>
                    <li><a href="#" class="nav-subitem">Unité de mesure</a></li>
                    <li><a href="#" class="nav-subitem">Ville</a></li>
                    <li><a href="#" class="nav-subitem">Commerciaux</a></li>
                    <li><a href="#" class="nav-subitem">Transport</a></li>
                    <li><a href="#" class="nav-subitem">Chauffeur</a></li>
                </ul>
            </div>
        </nav>

    </aside>

    <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Ouvrir ou fermer la barre latérale" aria-expanded="true">
        <span class="sidebar-toggle-inner">
            <svg class="sidebar-toggle-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="15 18 9 12 15 6"/>
            </svg>
        </span>
    </button>

    {{-- Main --}}
    <div class="main-wrapper">
        <header class="hero-header">
            <div class="hero-banner">
                <div class="hero-content-bottom">
                    <div class="hero-slogan-wrap">
                        <span class="sparkle sparkle-1"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 17l-6.2 4.3 2.4-7.4L2 9.4h7.6z"/></svg></span>
                        <span class="sparkle sparkle-2"><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l1.8 5.5H20l-4.6 3.4 1.8 5.5L12 14.5 7 16.4l1.8-5.5L4.2 7.5h6.2z"/></svg></span>
                        <span class="sparkle sparkle-3"><svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l1.5 4.6H18l-3.9 2.8 1.5 4.6L12 12.8 8.4 14l1.5-4.6L6 6.6h4.5z"/></svg></span>
                        <span class="sparkle sparkle-4"><svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.4 7.4H22l-6.2 4.5 2.4 7.4L12 17l-6.2 4.3 2.4-7.4L2 9.4h7.6z"/></svg></span>
                        <span class="sparkle sparkle-5"><svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l1.8 5.5H20l-4.6 3.4 1.8 5.5L12 14.5 7 16.4l1.8-5.5L4.2 7.5h6.2z"/></svg></span>
                        <span class="sparkle sparkle-6"><svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l1.5 4.6H18l-3.9 2.8 1.5 4.6L12 12.8 8.4 14l1.5-4.6L6 6.6h4.5z"/></svg></span>
                        <span class="sparkle sparkle-7"><svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2 6.2H20l-5.2 3.8 2 6.2L12 15.2 7.2 18.2l2-6.2L4 8.2h6z"/></svg></span>
                        <span class="sparkle sparkle-8"><svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l1.8 5.5H20l-4.6 3.4 1.8 5.5L12 14.5 7 16.4l1.8-5.5L4.2 7.5h6.2z"/></svg></span>
                        <p class="hero-slogan">La plateforme la plus proche des goûts de luxe</p>
                    </div>
                    <nav class="top-nav">
                        <a href="#" class="nav-brand" translate="no">
                            <span class="nav-brand-text">SWEET AUSTRIA</span>
                        </a>
                        <button type="button" class="top-nav-logout" id="goToLandingBtn" title="Page d'accueil / Déconnexion">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            <span>Accueil</span>
                        </button>
                    </nav>
                </div>
            </div>
        </header>

        <main class="dashboard-content">
            <div id="dashboardView">

            {{-- KPI Cards --}}
            <div class="kpi-grid">
                <div class="kpi-card green">
                    <div class="kpi-top">
                        <div class="kpi-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                        </div>
                        <span class="kpi-badge kpi-badge-up">
                            <svg viewBox="0 0 24 24"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
                            +12%
                        </span>
                    </div>
                    <div class="kpi-label">Chiffre d'Affaires</div>
                    <div class="kpi-value">452,000 MAD</div>
                </div>
                <div class="kpi-card blue">
                    <div class="kpi-top">
                        <div class="kpi-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                        </div>
                        <span class="kpi-badge kpi-badge-up">
                            <svg viewBox="0 0 24 24"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
                            +5%
                        </span>
                    </div>
                    <div class="kpi-label">Chiffre des Achats</div>
                    <div class="kpi-value">218,000 MAD</div>
                </div>
                <div class="kpi-card orange">
                    <div class="kpi-top">
                        <div class="kpi-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/></svg>
                        </div>
                        <span class="kpi-badge kpi-badge-down">
                            <svg viewBox="0 0 24 24"><line x1="7" y1="7" x2="17" y2="17"/><polyline points="17 7 17 17 7 17"/></svg>
                            -2%
                        </span>
                    </div>
                    <div class="kpi-label">Chiffre des Charges</div>
                    <div class="kpi-value">128,300 MAD</div>
                </div>
                <div class="kpi-card brown">
                    <div class="kpi-top">
                        <div class="kpi-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                        </div>
                        <span class="kpi-badge kpi-badge-up">
                            <svg viewBox="0 0 24 24"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
                            +8%
                        </span>
                    </div>
                    <div class="kpi-label">Bénéfice</div>
                    <div class="kpi-value">105,700 MAD</div>
                </div>
            </div>

            {{-- Stock Tables --}}
            <div class="tables-grid">
                <div class="table-card">
                    <div class="table-card-title">État Stock Produit</div>
                    <table class="stock-table">
                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Désignation</th>
                                <th>Quantité</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>MP-001</td>
                                <td>Amandes Premium</td>
                                <td>450 kg</td>
                                <td><span class="status-pill status-dispo">DISPO</span></td>
                            </tr>
                            <tr>
                                <td>MP-002</td>
                                <td>Noix de Cajou Bio</td>
                                <td>120 kg</td>
                                <td><span class="status-pill status-faible">FAIBLE</span></td>
                            </tr>
                            <tr>
                                <td>MP-003</td>
                                <td>Dattes Medjool</td>
                                <td>0 kg</td>
                                <td><span class="status-pill status-rupture">RUPTURE</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table-card">
                    <div class="table-card-title">État Stock Produit Fini</div>
                    <table class="stock-table">
                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Désignation</th>
                                <th>Quantité</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>PF-101</td>
                                <td>Mix Luxe 500g</td>
                                <td>1,200 u</td>
                                <td><span class="status-pill status-dispo">DISPO</span></td>
                            </tr>
                            <tr>
                                <td>PF-102</td>
                                <td>Coffret Prestige</td>
                                <td>85 u</td>
                                <td><span class="status-pill status-faible">FAIBLE</span></td>
                            </tr>
                            <tr>
                                <td>PF-103</td>
                                <td>Amandes Chocolat</td>
                                <td>0 u</td>
                                <td><span class="status-pill status-rupture">RUPTURE</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Charts --}}
            <div class="charts-grid">
                <div class="chart-section span-full">
                    <div class="chart-section-title">Ventes par mois</div>
                    <div class="chart-subtitle">Ventes et charges mensuelles — année en cours (MAD)</div>
                    <div class="chart-container tall">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
                <div class="chart-section">
                    <div class="chart-section-title">Villes les plus actives</div>
                    <div class="chart-subtitle">Volume de commandes par ville</div>
                    <div class="chart-container">
                        <canvas id="citiesChart"></canvas>
                    </div>
                </div>
                <div class="chart-section">
                    <div class="chart-section-title">Articles — ventes & demande</div>
                    <div class="chart-subtitle">Plus vendus / demandés vs moins vendus</div>
                    <div class="chart-container">
                        <canvas id="productsChart"></canvas>
                    </div>
                </div>
            </div>
            </div>

            {{-- Fiche Fournisseur --}}
            <div id="ficheFournisseurView" class="saisie-panel hidden">
                {{-- Formulaire --}}
                <div id="fournisseurFormPanel">
                <div class="saisie-card">
                    <div class="saisie-card-header">
                        <div>
                            <h2 id="fournisseurFormTitle">Fiche Fournisseur</h2>
                            <span id="fournisseurFormSubtitle">Barre de saisie</span>
                        </div>
                    </div>
                    <form class="saisie-form" id="ficheFournisseurForm" novalidate>
                        <div class="form-grid">
                            <div class="fr-inline-row fr-inline-row-idnom">
                                <div class="form-group">
                                    <label for="fr_id">ID</label>
                                    <input type="text" id="fr_id" name="id" class="form-input readonly" value="FR0001" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="fr_nom">Nom Fournisseur</label>
                                    <input type="text" id="fr_nom" name="nom" class="form-input" placeholder="Raison sociale ou nom">
                                </div>
                                <div class="form-group">
                                    <label for="fr_type">Type</label>
                                    <select id="fr_type" name="type" class="form-select">
                                        <option value="">— Sélectionner —</option>
                                        <option value="Rev">Rev — Revendeur</option>
                                        <option value="Ste">Ste — Société</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="fr_statut">Statut</label>
                                    <select id="fr_statut" name="statut" class="form-select">
                                        <option value="">— Sélectionner —</option>
                                        <option value="G/c">G/c — Grand compte</option>
                                        <option value="Mc">Mc — Moyen compte</option>
                                        <option value="Pc">Pc — Petit compte</option>
                                    </select>
                                </div>
                            </div>
                            <div class="fr-inline-row fr-inline-row-adr">
                                <div class="form-group">
                                    <label for="fr_adresse">Adresse</label>
                                    <input type="text" id="fr_adresse" name="adresse" class="form-input" placeholder="Adresse complète">
                                </div>
                                <div class="form-group">
                                    <label for="fr_telephone">Téléphone</label>
                                    <input type="tel" id="fr_telephone" name="telephone" class="form-input" placeholder="06 XX XX XX XX">
                                </div>
                                <div class="form-group">
                                    <label for="fr_fixe">Fixe</label>
                                    <input type="tel" id="fr_fixe" name="fixe" class="form-input" placeholder="05 XX XX XX XX">
                                </div>
                                <div class="form-group">
                                    <label for="fr_ville">Ville</label>
                                    <select id="fr_ville" name="ville" class="form-select">
                                        <option value="">— Sélectionner —</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="fr_email">E-mail</label>
                                    <input type="email" id="fr_email" name="email" class="form-input" placeholder="contact@fournisseur.ma">
                                </div>
                            </div>
                            <div class="fr-inline-row">
                                <div class="form-group">
                                    <label for="fr_type_paiement">Type Règlement</label>
                                    <select id="fr_type_paiement" name="type_paiement" class="form-select">
                                        <option value="">— Sélectionner —</option>
                                        <option value="Esp">Esp — Espèces</option>
                                        <option value="Chq">Chq — Chèque</option>
                                        <option value="Eff">Eff — Effet</option>
                                        <option value="Vir">Vir — Virement</option>
                                        <option value="Vers">Vers — Versement</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="fr_solde">Solde initial (MAD)</label>
                                    <input type="number" id="fr_solde" name="solde" class="form-input money-input" step="0.01" value="0.00" placeholder="0.00">
                                </div>
                                <div class="form-group">
                                    <label for="fr_banque">Banque</label>
                                    <input type="text" id="fr_banque" name="banque" class="form-input" placeholder="Nom de la banque">
                                </div>
                                <div class="form-group">
                                    <label for="fr_rib">RIB</label>
                                    <input type="text" id="fr_rib" name="rib" class="form-input rib-input" placeholder="000 000 0000000000000000 00" maxlength="27">
                                </div>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn-form btn-form-secondary" id="cancelFournisseurForm">Annuler</button>
                            <button type="button" class="btn-form btn-form-primary" id="validerFournisseurBtn">Valider</button>
                        </div>
                    </form>
                </div>
                </div>

                {{-- Liste fournisseurs --}}
                <div id="fournisseurListPanel" class="hidden">
                    <div class="list-toolbar">
                        <h2 class="list-toolbar-title">Liste des Fournisseurs</h2>
                        <div class="list-toolbar-actions">
                            <button type="button" class="btn-list btn-list-add" id="addFournisseurBtn">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                Nouveau fournisseur
                            </button>
                            <button type="button" class="btn-list btn-list-print" id="printFournisseursBtn">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                Imprimer
                            </button>
                            <button type="button" class="btn-list btn-list-pdf" id="exportFournisseursPdfBtn">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                Exporter PDF
                            </button>
                        </div>
                    </div>
                    <div id="fournisseurPrintArea">
                        <div class="fournisseur-table-wrap">
                            <table class="fournisseur-table" id="fournisseursTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Type</th>
                                        <th>Ville</th>
                                        <th>Téléphone</th>
                                        <th>Fixe</th>
                                        <th>E-mail</th>
                                        <th>Statut</th>
                                        <th>Paiement</th>
                                        <th>Banque</th>
                                        <th>RIB</th>
                                        <th>Solde</th>
                                        <th class="col-actions">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="fournisseursTableBody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Achats --}}
            <div id="achatsView" class="saisie-panel hidden">
                <div id="achatsConsultMode">
                    <div class="list-toolbar">
                        <h2 class="list-toolbar-title">Bons d'achat — Consultation</h2>
                        <div class="list-toolbar-actions">
                            <button type="button" class="btn-list btn-list-add" id="nouveauBonAchatsBtn">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                Nouveau Bon
                            </button>
                            <button type="button" class="btn-list btn-list-print" id="printCommandesAchatsBtn">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                Imprimer
                            </button>
                            <button type="button" class="btn-list btn-list-pdf" id="exportCommandesAchatsPdfBtn">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                Exporter PDF
                            </button>
                        </div>
                    </div>
                    <div class="saisie-card" id="commandesPrintAreaAchats">
                        <p style="font-family:'Playfair Display',serif;font-size:18px;font-weight:700;color:#003326;margin-bottom:4px;padding:16px 16px 0;">SWEET AUSTRIA — Bons d'achat</p>
                        <p style="font-size:11px;color:#6B6B68;margin-bottom:12px;padding:0 16px;" id="commandesPrintDateAchats"></p>
                        <div class="fournisseur-table-wrap">
                            <table class="achats-commandes-table" id="commandesListTableAchats">
                                <colgroup>
                                    <col class="col-cmd-bon">
                                    <col class="col-cmd-date">
                                    <col class="col-cmd-code">
                                    <col class="col-cmd-nom">
                                    <col class="col-cmd-ville">
                                    <col class="col-cmd-qte">
                                    <col class="col-cmd-total">
                                    <col class="col-cmd-reg">
                                    <col class="col-cmd-ech">
                                    <col class="col-cmd-actions">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th>Bon N°</th>
                                        <th>Date Cmd</th>
                                        <th>Code</th>
                                        <th>Nom Fournisseur</th>
                                        <th>Ville</th>
                                        <th>Qté</th>
                                        <th>Total</th>
                                        <th>Règlement</th>
                                        <th>Échéance</th>
                                        <th class="col-actions col-actions-cmd no-print-cmd">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="commandesListTableBodyAchats">
                                    <tr><td colspan="10" class="achats-commandes-empty">Aucune commande saisie</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="saisie-card hidden" id="achatsSaisieMode">
                    <div class="saisie-card-header">
                        <div>
                            <h2>Bon d'Achat</h2>
                        </div>
                    </div>
                    <form class="saisie-form" id="achatsForm" novalidate>
                        <div id="achatsPrintArea">
                        <div class="achats-form-scroll">
                        <div id="achatsSaisiePanel">
                        <div class="saisie-section">
                            <h3 class="saisie-section-title">Infos Fournisseur</h3>
                            <div class="achats-fr-inline-row">
                                <div class="form-group">
                                    <label for="ach_bon">Bon N°</label>
                                    <input type="text" id="ach_bon" name="bon" class="form-input readonly" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="ach_date_cmd">Date Cmd</label>
                                    <input type="date" id="ach_date_cmd" name="date_cmd" class="form-input">
                                </div>
                                <div class="form-group">
                                    <label for="ach_code_fournisseur">Code Fournisseur</label>
                                    <input type="text" id="ach_code_fournisseur" name="code_fournisseur" class="form-input" placeholder="Ex. FR0001" list="achFournisseurCodesList" autocomplete="off">
                                    <datalist id="achFournisseurCodesList"></datalist>
                                </div>
                                <div class="form-group">
                                    <label for="ach_nom_fournisseur">Nom Fournisseur</label>
                                    <input type="text" id="ach_nom_fournisseur" name="nom_fournisseur" class="form-input" placeholder="Raison sociale" list="achFournisseurNomsList" autocomplete="off">
                                    <datalist id="achFournisseurNomsList"></datalist>
                                </div>
                            </div>
                        </div>

                        <div class="saisie-section">
                            <div class="achats-pay-liv-card">
                                <div class="achats-pay-liv-col">
                                    <h4 class="achats-subsection-title">Infos Paiement</h4>
                                    <div class="achats-pay-inline-row">
                                        <div class="form-group">
                                            <label for="ach_type_reglement">Type Règlement</label>
                                            <select id="ach_type_reglement" name="type_reglement" class="form-select">
                                                <option value="">— Sélectionner —</option>
                                                <option value="Esp">Esp — Espèces</option>
                                                <option value="Chq">Chq — Chèque</option>
                                                <option value="Eff">Eff — Effet</option>
                                                <option value="Vir">Vir — Virement</option>
                                                <option value="Vers">Vers — Versement</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="ach_echeance">Échéance</label>
                                            <input type="date" id="ach_echeance" name="echeance" class="form-input">
                                        </div>
                                        <div class="form-group">
                                            <label for="ach_recuperation">Récupération Règlement</label>
                                            <select id="ach_recuperation" name="recuperation" class="form-select">
                                                <option value="">— Sélectionner —</option>
                                                <option value="Immediat">Immédiat</option>
                                                <option value="Semaine">Semaine</option>
                                                <option value="Mois">Mois</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="achats-pay-liv-col">
                                    <h4 class="achats-subsection-title">Livraison</h4>
                                    <div class="achats-liv-inline-row">
                                        <div class="form-group">
                                            <label for="ach_ville_livraison">Ville Livraison</label>
                                            <select id="ach_ville_livraison" name="ville_livraison" class="form-select">
                                                <option value="">— Sélectionner —</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="ach_transport">Transport</label>
                                            <select id="ach_transport" name="transport" class="form-select">
                                                <option value="">— Sélectionner —</option>
                                                <option value="Interne">Transport interne</option>
                                                <option value="Externe">Transport externe</option>
                                                <option value="Frns">Transport fournisseur</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="ach_matricule">Matricule</label>
                                            <input type="text" id="ach_matricule" name="matricule" class="form-input" placeholder="Matricule véhicule">
                                        </div>
                                        <div class="form-group">
                                            <label for="ach_chauffeur">Chauffeur</label>
                                            <input type="text" id="ach_chauffeur" name="chauffeur" class="form-input" placeholder="Nom du chauffeur">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="saisie-section achats-saisie-ligne">
                            <h3 class="saisie-section-title">Lignes articles</h3>
                            <div class="achats-lignes-scroll">
                            <div class="achats-lignes-inline-row">
                                <div class="form-group">
                                    <label for="ach_ligne_ref">Réf</label>
                                    <input type="text" id="ach_ligne_ref" class="form-input" placeholder="Réf">
                                </div>
                                <div class="form-group">
                                    <label for="ach_ligne_code_barre">Code barre</label>
                                    <input type="text" id="ach_ligne_code_barre" class="form-input" placeholder="Code" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label for="ach_ligne_designation">Désignation</label>
                                    <select id="ach_ligne_designation" class="form-select">
                                        <option value="">— Sélectionner —</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="ach_ligne_categorie">Catégorie</label>
                                    <select id="ach_ligne_categorie" class="form-select">
                                        <option value="">— Sélectionner —</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="ach_ligne_famille">Famille</label>
                                    <select id="ach_ligne_famille" class="form-select">
                                        <option value="">— Sélectionner —</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="ach_ligne_quantite">Qté</label>
                                    <input type="number" id="ach_ligne_quantite" class="form-input" step="0.001" min="0" value="1" placeholder="0">
                                </div>
                                <div class="form-group">
                                    <label for="ach_ligne_mesure">Unité de mesure</label>
                                    <select id="ach_ligne_mesure" class="form-select">
                                        <option value="">— Chargement… —</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="ach_ligne_prix">Prix U</label>
                                    <input type="number" id="ach_ligne_prix" class="form-input money-input" step="0.01" min="0" placeholder="0.00">
                                </div>
                                <div class="form-group">
                                    <label for="ach_ligne_sous_total">Sous-T.</label>
                                    <input type="text" id="ach_ligne_sous_total" class="form-input achats-sous-total-input readonly" readonly value="0,00 MAD">
                                </div>
                            </div>
                            </div>
                            <div class="achats-line-actions no-print-achats">
                                <button type="button" class="btn-form btn-form-primary" id="validerLigneAchatsBtn">Valider</button>
                                <button type="button" class="btn-form btn-form-secondary" id="modifierLigneAchatsBtn">Modifier</button>
                                <button type="button" class="btn-form btn-form-secondary" id="annulerLigneAchatsBtn">Annuler</button>
                            </div>
                        </div>
                        </div>
                        </div>

                        <div class="achats-articles-panel">
                            <h3 class="achats-articles-title">Articles saisis</h3>
                            <div class="fournisseur-table-wrap">
                                <table class="achats-lines-table" id="achatsLignesTable">
                                    <thead>
                                        <tr>
                                            <th>Réf</th>
                                            <th>Code barre</th>
                                            <th>Désignation</th>
                                            <th>Catégorie</th>
                                            <th>Famille</th>
                                            <th>Qté</th>
                                            <th>Mesure</th>
                                            <th>Prix U</th>
                                            <th>Sous-Total</th>
                                        </tr>
                                    </thead>
                                    <tbody id="achatsLignesTableBody">
                                        <tr><td colspan="9" class="achats-lines-empty">Aucune ligne saisie</td></tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="achats-total-bar">
                                <span>Total général</span>
                                <span id="achatsTotalGeneral">0,00 MAD</span>
                            </div>
                        </div>
                        </div>

                        <div class="form-actions achats-doc-actions no-print-achats">
                            <button type="button" class="btn-form btn-form-outline" id="fermerAchatsBtn">Fermer</button>
                            <button type="button" class="btn-form btn-form-primary" id="enregistrerCommandeAchatsBtn">Valider</button>
                            <button type="button" class="btn-list btn-list-print" id="printAchatsBtn">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                Imprimer
                            </button>
                            <button type="button" class="btn-list btn-list-pdf" id="exportAchatsPdfBtn">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                Exporter PDF
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Commandes --}}
            <div id="commandesView" class="saisie-panel hidden">
                <div class="list-toolbar">
                    <h2 class="list-toolbar-title">Commandes saisies</h2>
                    <div class="list-toolbar-actions">
                        <button type="button" class="btn-list btn-list-add" id="nouvelleCommandeBtn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Nouvelle commande
                        </button>
                        <button type="button" class="btn-list btn-list-print" id="printCommandesBtn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                            Imprimer
                        </button>
                        <button type="button" class="btn-list btn-list-pdf" id="exportCommandesPdfBtn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                            Exporter PDF
                        </button>
                    </div>
                </div>
                <div class="saisie-card" id="commandesPrintArea">
                    <p style="font-family:'Playfair Display',serif;font-size:18px;font-weight:700;color:#003326;margin-bottom:4px;padding:16px 16px 0;">SWEET AUSTRIA — Commandes saisies</p>
                    <p style="font-size:11px;color:#6B6B68;margin-bottom:12px;padding:0 16px;" id="commandesPrintDate"></p>
                    <div class="fournisseur-table-wrap">
                        <table class="achats-commandes-table" id="commandesListTable">
                            <colgroup>
                                <col class="col-cmd-bon">
                                <col class="col-cmd-date">
                                <col class="col-cmd-code">
                                <col class="col-cmd-nom">
                                <col class="col-cmd-ville">
                                <col class="col-cmd-qte">
                                <col class="col-cmd-total">
                                <col class="col-cmd-reg">
                                <col class="col-cmd-ech">
                                <col class="col-cmd-actions">
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>Bon N°</th>
                                    <th>Date Cmd</th>
                                    <th>Code</th>
                                    <th>Nom Fournisseur</th>
                                    <th>Ville</th>
                                    <th>Qté</th>
                                    <th>Total</th>
                                    <th>Règlement</th>
                                    <th>Échéance</th>
                                    <th class="col-actions col-actions-cmd no-print-cmd">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="commandesListTableBody">
                                <tr><td colspan="10" class="achats-commandes-empty">Aucune commande saisie</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Fiche Produit --}}
            <div id="ficheProduitView" class="saisie-panel hidden">
                <div id="produitFormPanel" class="hidden">
                    <div class="saisie-card">
                        <div class="saisie-card-header">
                            <div>
                                <h2 id="produitFormTitle">Fiche Produit</h2>
                                <span id="produitFormSubtitle">Barre de saisie</span>
                            </div>
                        </div>
                        <form class="saisie-form" id="ficheProduitForm" novalidate>
                            <div class="produit-form-layout">
                                <div class="produit-form-fields">
                                    <div class="pr-inline-row pr-inline-row-1">
                                        <div class="form-group">
                                            <label for="pr_ref">Réf</label>
                                            <input type="text" id="pr_ref" name="ref" class="form-input readonly" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="pr_designation">Désignation</label>
                                            <select id="pr_designation" name="designation" class="form-select" required>
                                                <option value="">— Sélectionner —</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="pr_type">Type</label>
                                            <select id="pr_type" name="type" class="form-select">
                                                <option value="">— Sélectionner —</option>
                                                <option value="Pro Cru">Pro Cru</option>
                                                <option value="Pro Fini">Pro Fini</option>
                                                <option value="Pro Div">Pro Div</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="pr-inline-row pr-inline-row-2">
                                        <div class="form-group">
                                            <label for="pr_categorie">Catégorie</label>
                                            <select id="pr_categorie" name="categorie" class="form-select">
                                                <option value="">— Sélectionner —</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="pr_famille">Famille</label>
                                            <select id="pr_famille" name="famille" class="form-select">
                                                <option value="">— Sélectionner —</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="pr_quantite">Qté</label>
                                            <input type="number" id="pr_quantite" name="quantite" class="form-input" step="0.001" min="0" value="0">
                                        </div>
                                        <div class="form-group">
                                            <label for="pr_unite">U</label>
                                            <select id="pr_unite" name="unite" class="form-select">
                                                <option value="">— Sélectionner —</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="pr-inline-row pr-inline-row-3">
                                        <div class="form-group">
                                            <label for="pr_prix_achat">P. Achat</label>
                                            <input type="number" id="pr_prix_achat" name="prix_achat" class="form-input money-input" step="0.01" min="0" placeholder="0.00">
                                        </div>
                                        <div class="form-group">
                                            <label for="pr_prix_vente">P. Vente</label>
                                            <input type="number" id="pr_prix_vente" name="prix_vente" class="form-input money-input" step="0.01" min="0" placeholder="0.00">
                                        </div>
                                    </div>
                                </div>
                                <div class="produit-photo-panel">
                                    <label for="pr_photo_file">Photo produit</label>
                                    <div class="produit-photo-preview" id="pr_photo_preview">
                                        <span class="produit-photo-placeholder" id="pr_photo_placeholder">Aucune photo</span>
                                        <img id="pr_photo_img" alt="Photo produit" class="hidden">
                                    </div>
                                    <div class="produit-photo-actions">
                                        <input type="file" id="pr_photo_file" accept="image/*" class="hidden">
                                        <input type="file" id="pr_photo_camera" accept="image/*" capture="environment" class="hidden">
                                        <button type="button" class="btn-photo" id="prPhotoPickBtn">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                                            Ajouter photo
                                        </button>
                                        <button type="button" class="btn-photo" id="prPhotoCaptureBtn">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                                            Prendre photo
                                        </button>
                                        <button type="button" class="btn-photo btn-photo-danger hidden" id="prPhotoRemoveBtn">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                            Supprimer
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="button" class="btn-form btn-form-secondary" id="cancelProduitForm">Annuler</button>
                                <button type="submit" class="btn-form btn-form-primary" id="saveProduitBtn">Enregistrer</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="produitListPanel">
                    <div class="list-toolbar no-print-produit">
                        <h2 class="list-toolbar-title">Fiche Produit</h2>
                        <div class="list-toolbar-actions">
                            <button type="button" class="btn-list btn-list-print" id="printProduitsBtn">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
                                Imprimer
                            </button>
                            <button type="button" class="btn-list btn-list-add btn-list-modify" id="modifierProduitToolbarBtn" disabled>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Modifier
                            </button>
                            <button type="button" class="btn-list btn-list-pdf" id="exportProduitsPdfBtn">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                Exporter PDF
                            </button>
                            <button type="button" class="btn-list btn-list-add" id="addProduitBtn">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                Ajouter Produit
                            </button>
                        </div>
                    </div>
                    <div id="produitPrintArea">
                        <div class="fournisseur-table-wrap">
                            <table class="produits-table" id="produitsTable">
                                <thead>
                                    <tr>
                                        <th>Réf</th>
                                        <th>Photo</th>
                                        <th>QR code</th>
                                        <th>Désignation</th>
                                        <th>Type</th>
                                        <th>Catégorie</th>
                                        <th>Famille</th>
                                        <th>Quantité</th>
                                        <th>U</th>
                                        <th>Prix Achat</th>
                                        <th>Prix Vente</th>
                                        <th class="col-actions no-print-produit">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="produitsTableBody">
                                    <tr><td colspan="12" class="produits-empty">Aucun produit enregistré</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="page-footer">
            <span>© 2023 SWEET AUSTRIA Enterprise. Tous droits réservés.</span>
            <ul class="footer-links">
                <li><a href="#">Journal de sécurité</a></li>
                <li><a href="#">Statut des API</a></li>
                <li><a href="#">Mentions légales</a></li>
            </ul>
        </footer>
    </div>

    {{-- Modal Catégories --}}
    <div class="category-modal-overlay" id="categoryModal">
        <div class="category-modal" role="dialog" aria-modal="true" aria-labelledby="categoryModalTitle">
            <div class="category-modal-header">
                <h2 class="category-modal-title" id="categoryModalTitle"></h2>
                <button class="category-modal-close" id="closeCategoryModal" aria-label="Fermer">&times;</button>
            </div>
            <div class="category-modal-body">
                <div class="category-gallery-intro" id="categoryGalleryIntro"></div>
                <div class="products-grid" id="productsGrid"></div>
            </div>
        </div>
    </div>

    {{-- Détail commande (Voir) --}}
    <div class="visit-card-overlay" id="commandeDetailModal">
        <div class="visit-card" role="dialog" aria-modal="true" aria-labelledby="commandeDetailTitle" style="max-width:720px;">
            <div class="visit-card-header">
                <h2 id="commandeDetailTitle">Détail commande</h2>
                <button class="visit-card-close" id="closeCommandeDetail" aria-label="Fermer">&times;</button>
            </div>
            <div class="visit-card-body" id="commandeDetailBody"></div>
        </div>
    </div>

    <div class="cart-toast" id="cartToast"></div>

    {{-- Carte de visite Commercial --}}
    <div class="visit-card-overlay" id="visitCardModal">
        <div class="visit-card" role="dialog" aria-modal="true" aria-labelledby="visitCardTitle">
            <div class="visit-card-header">
                <h2 id="visitCardTitle">Carte de visite</h2>
                <button class="visit-card-close" id="closeVisitCard" aria-label="Fermer">&times;</button>
            </div>
            <div class="visit-card-body" id="visitCardBody"></div>
        </div>
    </div>

    <script>
        const sidebarToggle = document.getElementById('sidebarToggle');
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', () => {
                const collapsed = document.body.classList.toggle('sidebar-collapsed');
                sidebarToggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
            });
        }

        document.querySelectorAll('.nav-group-toggle').forEach(toggle => {
            toggle.addEventListener('click', () => {
                const group = toggle.closest('.nav-group');
                const isOpen = group.classList.toggle('open');
                toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });
        });

        document.querySelectorAll('.nav-subgroup-toggle').forEach(toggle => {
            toggle.addEventListener('click', e => {
                e.stopPropagation();
                const subgroup = toggle.closest('.nav-subgroup');
                const isOpen = subgroup.classList.toggle('open');
                toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });
        });

        document.querySelectorAll('.nav-subitem').forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                document.querySelectorAll('.nav-subitem.active').forEach(el => el.classList.remove('active'));
                link.classList.add('active');
                if (link.dataset.view) showAppView(link.dataset.view);
            });
        });

        document.querySelectorAll('.nav-item[data-view]').forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                document.querySelectorAll('.nav-item.active').forEach(el => el.classList.remove('active'));
                link.classList.add('active');
                document.querySelectorAll('.nav-subitem.active').forEach(el => el.classList.remove('active'));
                showAppView(link.dataset.view);
            });
        });

        const dashboardView = document.getElementById('dashboardView');
        const ficheFournisseurView = document.getElementById('ficheFournisseurView');
        const achatsView = document.getElementById('achatsView');
        const commandesView = document.getElementById('commandesView');
        const ficheProduitView = document.getElementById('ficheProduitView');
        const produitFormPanel = document.getElementById('produitFormPanel');
        const produitListPanel = document.getElementById('produitListPanel');
        const ficheProduitForm = document.getElementById('ficheProduitForm');
        const produitsTableBody = document.getElementById('produitsTableBody');
        const prRefInput = document.getElementById('pr_ref');
        const achatsForm = document.getElementById('achatsForm');
        const fournisseurFormPanel = document.getElementById('fournisseurFormPanel');
        const fournisseurListPanel = document.getElementById('fournisseurListPanel');
        const ficheFournisseurForm = document.getElementById('ficheFournisseurForm');
        const fournisseursTableBody = document.getElementById('fournisseursTableBody');
        const frIdInput = document.getElementById('fr_id');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        let fournisseurs = [];
        let nextFournisseurId = 'FR0001';
        let editingFournisseurId = null;

        function normalizeFournisseurCode(code) {
            return (code || '').trim().toUpperCase();
        }

        function normalizeFournisseurNom(nom) {
            return (nom || '').trim().toLowerCase();
        }

        function commandeBelongsToFournisseur(c, f) {
            const cCode = normalizeFournisseurCode(c.code_fournisseur);
            const fCode = normalizeFournisseurCode(f.id);
            if (cCode && fCode && cCode === fCode) return true;
            const cNom = normalizeFournisseurNom(c.nom_fournisseur);
            const fNom = normalizeFournisseurNom(f.nom);
            return !!(cNom && fNom && cNom === fNom);
        }

        function isCommandePayee(c) {
            if (c.paye === true) return true;
            if (c.paye === false) return false;
            return (c.type_reglement || '') === 'Esp';
        }

        function unpaidTotal(f) {
            return commandesAchats
                .filter(c => commandeBelongsToFournisseur(c, f) && !isCommandePayee(c))
                .reduce((sum, c) => sum + (parseFloat(c.total) || 0), 0);
        }

        function fournisseurInitialSolde(f) {
            return parseFloat(f && f.solde != null ? f.solde : 0) || 0;
        }

        // Solde affiché = solde initial saisi + total des achats non payés
        function computeFournisseurSolde(f) {
            return fournisseurInitialSolde(f) + unpaidTotal(f);
        }

        function applyFournisseurSoldesFromCommandes() {
            loadCommandesAchats();
        }

        function updateFournisseurSoldeField(f) {
            const soldeInput = document.getElementById('fr_solde');
            if (!soldeInput) return;
            soldeInput.value = (f ? fournisseurInitialSolde(f) : 0).toFixed(2);
        }

        function refreshFournisseurSoldesAfterCommandeChange() {
            loadCommandesAchats();
            if (fournisseurListPanel && !fournisseurListPanel.classList.contains('hidden')) {
                renderFournisseursTable();
            }
        }

        function formatSolde(val) {
            const n = parseFloat(val) || 0;
            return n.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' MAD';
        }

        function soldeClass(val) {
            const n = parseFloat(val) || 0;
            if (n > 0) return 'solde-positive';
            if (n < 0) return 'solde-negative';
            return 'solde-zero';
        }

        function resetFournisseurFormMode() {
            editingFournisseurId = null;
            const title = document.getElementById('fournisseurFormTitle');
            const subtitle = document.getElementById('fournisseurFormSubtitle');
            const saveBtn = document.getElementById('saveFournisseurBtn');
            if (title) title.textContent = 'Fiche Fournisseur';
            if (subtitle) subtitle.textContent = 'Barre de saisie';
            if (saveBtn) saveBtn.textContent = 'Enregistrer';
        }

        function showFournisseurForm(reset = false) {
            if (fournisseurFormPanel) fournisseurFormPanel.classList.remove('hidden');
            if (fournisseurListPanel) fournisseurListPanel.classList.add('hidden');
            if (reset) {
                resetFournisseurFormMode();
                if (ficheFournisseurForm) ficheFournisseurForm.reset();
                if (frIdInput) frIdInput.value = editingFournisseurId || nextFournisseurId;
                updateFournisseurSoldeField({ id: '', nom: '' });
                refreshLookupSelects();
            }
        }

        function editFournisseur(code) {
            const f = fournisseurs.find(x => x.id === code);
            if (!f) return;
            editingFournisseurId = code;
            showFournisseurForm(false);
            refreshLookupSelects({ fr_ville: f.ville || '' });
            if (frIdInput) frIdInput.value = f.id;
            document.getElementById('fr_nom').value = f.nom || '';
            document.getElementById('fr_type').value = f.type || '';
            document.getElementById('fr_ville').value = f.ville || '';
            document.getElementById('fr_adresse').value = f.adresse || '';
            document.getElementById('fr_telephone').value = f.telephone || '';
            document.getElementById('fr_fixe').value = f.fixe || '';
            document.getElementById('fr_email').value = f.email || '';
            document.getElementById('fr_statut').value = f.statut || '';
            document.getElementById('fr_type_paiement').value = f.type_paiement || '';
            document.getElementById('fr_banque').value = f.banque || '';
            document.getElementById('fr_rib').value = f.rib || '';
            applyFournisseurSoldesFromCommandes();
            updateFournisseurSoldeField(f);
            const title = document.getElementById('fournisseurFormTitle');
            const subtitle = document.getElementById('fournisseurFormSubtitle');
            const saveBtn = document.getElementById('saveFournisseurBtn');
            if (title) title.textContent = 'Modifier Fournisseur';
            if (subtitle) subtitle.textContent = f.id;
            if (saveBtn) saveBtn.textContent = 'Mettre à jour';
        }

        async function deleteFournisseur(code) {
            const f = fournisseurs.find(x => x.id === code);
            if (!f) return;
            if (!confirm('Supprimer le fournisseur « ' + f.nom + ' » (' + code + ') ?')) return;
            try {
                const res = await fetch('/api/fournisseurs/' + encodeURIComponent(code), {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                });
                if (!res.ok) {
                    const data = await res.json();
                    alert(data.message || 'Erreur lors de la suppression');
                    return;
                }
                await loadFournisseurs();
                renderFournisseursTable();
                const toast = document.getElementById('cartToast');
                if (toast) {
                    toast.textContent = 'Fournisseur ' + code + ' supprimé';
                    toast.classList.add('show');
                    setTimeout(() => toast.classList.remove('show'), 2800);
                }
            } catch (err) {
                console.error(err);
                alert('Impossible de supprimer le fournisseur.');
            }
        }

        function showFournisseurList() {
            if (fournisseurFormPanel) fournisseurFormPanel.classList.add('hidden');
            if (fournisseurListPanel) fournisseurListPanel.classList.remove('hidden');
            renderFournisseursTable();
        }

        function renderFournisseursTable() {
            if (!fournisseursTableBody) return;
            applyFournisseurSoldesFromCommandes();
            if (fournisseurs.length === 0) {
                fournisseursTableBody.innerHTML = '<tr><td colspan="13" class="fournisseur-empty">Aucun fournisseur enregistré</td></tr>';
                return;
            }
            fournisseursTableBody.innerHTML = fournisseurs.map(f => `
                <tr data-id="${escHtml(f.id)}">
                    <td><strong>${escHtml(f.id)}</strong></td>
                    <td>${escHtml(f.nom)}</td>
                    <td>${escHtml(f.type)}</td>
                    <td>${escHtml(f.ville)}</td>
                    <td>${escHtml(f.telephone)}</td>
                    <td>${escHtml(f.fixe)}</td>
                    <td>${escHtml(f.email)}</td>
                    <td>${escHtml(f.statut)}</td>
                    <td>${escHtml(f.type_paiement)}</td>
                    <td>${escHtml(f.banque)}</td>
                    <td style="font-family:monospace;font-size:11px;">${escHtml(f.rib)}</td>
                    <td class="solde-cell ${soldeClass(computeFournisseurSolde(f))}">${formatSolde(computeFournisseurSolde(f))}</td>
                    <td class="col-actions">
                        <span class="col-actions-wrap">
                            <button type="button" class="btn-row btn-row-edit" data-edit="${f.id}">Modifier</button>
                            <button type="button" class="btn-row btn-row-delete" data-delete="${f.id}">Supprimer</button>
                        </span>
                    </td>
                </tr>
            `).join('');

            fournisseursTableBody.querySelectorAll('[data-edit]').forEach(btn => {
                btn.addEventListener('click', () => editFournisseur(btn.dataset.edit));
            });
            fournisseursTableBody.querySelectorAll('[data-delete]').forEach(btn => {
                btn.addEventListener('click', () => deleteFournisseur(btn.dataset.delete));
            });
        }

        function escHtml(str) {
            if (!str) return '—';
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        let achatsReturnView = 'dashboard';

        function syncNavActive(viewId) {
            if (viewId === 'dashboard') {
                document.querySelectorAll('.nav-subitem.active').forEach(el => el.classList.remove('active'));
                document.querySelectorAll('.nav-item.active').forEach(el => el.classList.remove('active'));
                document.querySelector('.nav-item[data-view="dashboard"]')?.classList.add('active');
                return;
            }
            if (['achats', 'commandes', 'fiche-fournisseur', 'fiche-produit'].includes(viewId)) {
                document.querySelectorAll('.nav-subitem.active').forEach(el => el.classList.remove('active'));
                document.querySelector(`.nav-subitem[data-view="${viewId}"]`)?.classList.add('active');
            }
        }

        function showAppView(viewId, options = {}) {
            syncNavActive(viewId);
            if (dashboardView) dashboardView.classList.toggle('hidden', viewId !== 'dashboard');
            if (ficheFournisseurView) ficheFournisseurView.classList.toggle('hidden', viewId !== 'fiche-fournisseur');
            if (achatsView) achatsView.classList.toggle('hidden', viewId !== 'achats');
            if (commandesView) commandesView.classList.toggle('hidden', viewId !== 'commandes');
            if (ficheProduitView) ficheProduitView.classList.toggle('hidden', viewId !== 'fiche-produit');
            if (viewId === 'fiche-fournisseur') {
                loadFournisseurs().then(() => {
                    refreshLookupSelects();
                    if (fournisseurs.length > 0) showFournisseurList();
                    else showFournisseurForm(true);
                });
            }
            if (viewId === 'achats') {
                if (options.returnView !== undefined) {
                    achatsReturnView = options.returnView;
                } else {
                    achatsReturnView = 'dashboard';
                }
                const consult = document.getElementById('achatsConsultMode');
                const saisie = document.getElementById('achatsSaisieMode');
                const mode = options.mode || 'consult';
                if (consult && saisie) {
                    consult.classList.toggle('hidden', mode !== 'consult');
                    saisie.classList.toggle('hidden', mode !== 'saisie');
                }
                Promise.all([loadFournisseurs(), loadProduits()]).then(() => {
                    updateAchatsFournisseurDatalists();
                    loadCommandesAchats();
                    return loadUnitesMesure();
                }).then(() => {
                    refreshLookupSelects();
                    if (!options.keepForm) resetAchatsForm();
                });
            }
            if (viewId === 'commandes') {
                loadCommandesAchats();
                renderCommandesAchatsTable();
            }
            if (viewId === 'fiche-produit') {
                Promise.all([loadProduits(), loadUnitesMesure()]).then(() => {
                    refreshLookupSelects();
                    showProduitList();
                });
            }
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        let produits = [];
        let nextProduitRef = 'PR0001';
        let editingProduitRef = null;
        let selectedProduitRef = null;
        let produitPhotoFile = null;
        let produitPhotoRemove = false;
        let produitPhotoObjectUrl = null;

        const LOOKUP_LISTS = {
            villes: ['Casablanca', 'Fès', 'Rabat', 'Nador', 'Tanger', 'Marrakech', 'Taza', 'Oujda', 'Agadir', 'Meknès', 'Kenitra', 'El Jadida'],
            categories: [
                'Fruits à coque', 'Fruits séchés', 'Cacahuètes et dérivés', 'Graines alimentaires',
                'Fruits secs enrobés et confiseries', 'Produits Ramadan et Fêtes', 'Épices', 'Confiserie', 'Divers'
            ],
            familles: ['Noix', 'Amandes', 'Dattes', 'Figues', 'Abricots', 'Raisins', 'Graines', 'Mélanges', 'Confiserie', 'Divers'],
            designations: [
                'Amandes décortiquées', 'Noix de cajou', 'Dattes Medjool', 'Figues séchées', 'Abricots secs',
                'Raisins secs', 'Pistaches', 'Cacahuètes grillées', 'Mélange fruits secs', 'Noix de coco râpée'
            ],
        };

        function escapeOptionAttr(val) {
            return String(val || '').replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;');
        }

        function escapeOptionText(val) {
            return String(val || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        function uniqueSortedList(items) {
            return [...new Set((items || []).filter(Boolean))].sort((a, b) => a.localeCompare(b, 'fr'));
        }

        function getDesignationOptions() {
            const fromProduits = (produits || []).map(p => p.designation).filter(Boolean);
            return uniqueSortedList([...LOOKUP_LISTS.designations, ...fromProduits]);
        }

        function populateLookupSelect(selectId, items, selectedValue = '') {
            const el = document.getElementById(selectId);
            if (!el) return;
            const merged = uniqueSortedList([...(items || []), selectedValue]);
            el.innerHTML = '<option value="">— Sélectionner —</option>' +
                merged.map(v => `<option value="${escapeOptionAttr(v)}"${v === selectedValue ? ' selected' : ''}>${escapeOptionText(v)}</option>`).join('');
        }

        function refreshLookupSelects(selected = {}) {
            populateLookupSelect('fr_ville', LOOKUP_LISTS.villes, selected.fr_ville || '');
            populateLookupSelect('ach_ville_livraison', LOOKUP_LISTS.villes, selected.ach_ville_livraison || '');
            populateLookupSelect('ach_ligne_categorie', LOOKUP_LISTS.categories, selected.ach_ligne_categorie || '');
            populateLookupSelect('ach_ligne_famille', LOOKUP_LISTS.familles, selected.ach_ligne_famille || '');
            populateLookupSelect('ach_ligne_designation', getDesignationOptions(), selected.ach_ligne_designation || '');
            populateLookupSelect('pr_categorie', LOOKUP_LISTS.categories, selected.pr_categorie || '');
            populateLookupSelect('pr_famille', LOOKUP_LISTS.familles, selected.pr_famille || '');
            populateLookupSelect('pr_designation', getDesignationOptions(), selected.pr_designation || '');
        }

        function findProduitByDesignation(designation) {
            const d = (designation || '').trim();
            if (!d) return null;
            return produits.find(p => (p.designation || '').trim() === d) || null;
        }

        function applyAchatsLigneFromProduit(designation) {
            const p = findProduitByDesignation(designation);
            if (!p) return;
            refreshLookupSelects({
                ach_ligne_designation: p.designation || designation,
                ach_ligne_categorie: p.categorie || '',
                ach_ligne_famille: p.famille || '',
            });
            if (p.ref) document.getElementById('ach_ligne_ref').value = p.ref;
            if (p.categorie) document.getElementById('ach_ligne_categorie').value = p.categorie;
            if (p.famille) document.getElementById('ach_ligne_famille').value = p.famille;
            if (p.unite) document.getElementById('ach_ligne_mesure').value = p.unite;
            if (p.prix_achat != null && p.prix_achat !== '') {
                document.getElementById('ach_ligne_prix').value = parseFloat(p.prix_achat).toFixed(2);
            }
            calcAchatsLigneSousTotal();
        }

        function applyProduitFormFromDesignation(designation) {
            const p = findProduitByDesignation(designation);
            if (!p) return;
            refreshLookupSelects({
                pr_designation: p.designation || designation,
                pr_categorie: p.categorie || '',
                pr_famille: p.famille || '',
            });
            if (p.categorie) document.getElementById('pr_categorie').value = p.categorie;
            if (p.famille) document.getElementById('pr_famille').value = p.famille;
            if (p.type) document.getElementById('pr_type').value = p.type;
            if (p.unite) document.getElementById('pr_unite').value = p.unite;
            if (p.quantite != null) document.getElementById('pr_quantite').value = p.quantite;
            if (p.prix_achat != null && p.prix_achat !== '') {
                document.getElementById('pr_prix_achat').value = parseFloat(p.prix_achat).toFixed(2);
            }
            if (p.prix_vente != null && p.prix_vente !== '') {
                document.getElementById('pr_prix_vente').value = parseFloat(p.prix_vente).toFixed(2);
            }
        }

        function formatPrixOptionnel(val) {
            if (val === null || val === undefined || val === '') return '—';
            return formatMoney(val);
        }

        function formatQuantiteProduit(val) {
            const n = parseFloat(val) || 0;
            return n.toLocaleString('fr-FR', { minimumFractionDigits: 0, maximumFractionDigits: 3 });
        }

        function getProduitPhotoUrl(produit) {
            if (!produit) return null;
            if (produit.photo_url) {
                return produit.photo_url.startsWith('http')
                    ? produit.photo_url.replace(/^https?:\/\/[^/]+/, '')
                    : produit.photo_url;
            }
            if (produit.photo) {
                return '/storage/' + String(produit.photo).replace(/\\/g, '/').replace(/^\/+/, '');
            }
            return null;
        }

        function uniteLibelle(code) {
            if (!code) return '—';
            const u = unitesMesure.find(x => x.code === code);
            return u ? u.code : code;
        }

        function resetProduitPhoto(clearRemoveFlag = true) {
            produitPhotoFile = null;
            if (clearRemoveFlag) produitPhotoRemove = false;
            if (produitPhotoObjectUrl) {
                URL.revokeObjectURL(produitPhotoObjectUrl);
                produitPhotoObjectUrl = null;
            }
            const img = document.getElementById('pr_photo_img');
            const placeholder = document.getElementById('pr_photo_placeholder');
            const removeBtn = document.getElementById('prPhotoRemoveBtn');
            const fileInput = document.getElementById('pr_photo_file');
            const cameraInput = document.getElementById('pr_photo_camera');
            if (img) {
                img.src = '';
                img.classList.add('hidden');
            }
            if (placeholder) placeholder.classList.remove('hidden');
            if (removeBtn) removeBtn.classList.add('hidden');
            if (fileInput) fileInput.value = '';
            if (cameraInput) cameraInput.value = '';
        }

        function setProduitPhotoPreview(src) {
            const img = document.getElementById('pr_photo_img');
            const placeholder = document.getElementById('pr_photo_placeholder');
            const removeBtn = document.getElementById('prPhotoRemoveBtn');
            if (!img || !placeholder) return;
            if (src) {
                img.src = src;
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
                if (removeBtn) removeBtn.classList.remove('hidden');
            } else {
                img.src = '';
                img.classList.add('hidden');
                placeholder.classList.remove('hidden');
                if (removeBtn) removeBtn.classList.add('hidden');
            }
        }

        function handleProduitPhotoFile(file) {
            if (!file || !file.type.startsWith('image/')) {
                alert('Veuillez sélectionner une image valide.');
                return;
            }
            if (file.size > 5 * 1024 * 1024) {
                alert('La photo ne doit pas dépasser 5 Mo.');
                return;
            }
            produitPhotoFile = file;
            produitPhotoRemove = false;
            if (produitPhotoObjectUrl) URL.revokeObjectURL(produitPhotoObjectUrl);
            produitPhotoObjectUrl = URL.createObjectURL(file);
            setProduitPhotoPreview(produitPhotoObjectUrl);
        }

        function resetProduitFormMode() {
            editingProduitRef = null;
            const title = document.getElementById('produitFormTitle');
            const subtitle = document.getElementById('produitFormSubtitle');
            const saveBtn = document.getElementById('saveProduitBtn');
            if (title) title.textContent = 'Fiche Produit';
            if (subtitle) subtitle.textContent = 'Barre de saisie';
            if (saveBtn) saveBtn.textContent = 'Enregistrer';
        }

        function populateProduitUniteSelect() {
            const select = document.getElementById('pr_unite');
            if (!select) return;
            select.innerHTML = '<option value="">— Sélectionner —</option>' +
                unitesMesure.map(u => `<option value="${u.code}">${u.libelle} (${u.code})</option>`).join('');
        }

        function showProduitForm(reset = false) {
            if (produitFormPanel) produitFormPanel.classList.remove('hidden');
            if (produitListPanel) produitListPanel.classList.add('hidden');
            if (reset) {
                resetProduitFormMode();
                if (ficheProduitForm) ficheProduitForm.reset();
                if (prRefInput) prRefInput.value = editingProduitRef || nextProduitRef;
                populateProduitUniteSelect();
                refreshLookupSelects();
                resetProduitPhoto();
            }
        }

        function showProduitList() {
            if (produitFormPanel) produitFormPanel.classList.add('hidden');
            if (produitListPanel) produitListPanel.classList.remove('hidden');
            renderProduitsTable();
        }

        function updateProduitToolbarState() {
            const btn = document.getElementById('modifierProduitToolbarBtn');
            if (btn) btn.disabled = !selectedProduitRef;
        }

        function renderProduitQrCodes() {
            if (!window.QRCode) return;
            document.querySelectorAll('[data-qr-ref]').forEach(cell => {
                const ref = cell.dataset.qrRef;
                if (!ref) return;
                QRCode.toCanvas(ref, { width: 52, margin: 1, color: { dark: '#003326' } }, (err, canvas) => {
                    if (err) return;
                    cell.innerHTML = '';
                    cell.appendChild(canvas);
                });
            });
        }

        function renderProduitsTable() {
            if (!produitsTableBody) return;
            updateProduitToolbarState();
            if (produits.length === 0) {
                produitsTableBody.innerHTML = '<tr><td colspan="12" class="produits-empty">Aucun produit enregistré</td></tr>';
                return;
            }
            produitsTableBody.innerHTML = produits.map(p => {
                const selected = selectedProduitRef === p.ref ? ' selected' : '';
                const photoUrl = getProduitPhotoUrl(p);
                const photoCell = photoUrl
                    ? `<span class="produit-photo-thumb"><img src="${escHtml(photoUrl)}" alt="${escHtml(p.designation)}" loading="lazy"></span>`
                    : `<span class="produit-photo-thumb produit-photo-thumb-empty"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg></span>`;
                return `<tr class="${selected}" data-produit-ref="${escHtml(p.ref)}">
                    <td><strong>${escHtml(p.ref)}</strong></td>
                    <td class="produit-photo-cell">${photoCell}</td>
                    <td class="produit-qr-cell" data-qr-ref="${escHtml(p.ref)}"></td>
                    <td class="col-designation" title="${escHtml(p.designation)}">${escHtml(p.designation)}</td>
                    <td>${escHtml(p.type) || '—'}</td>
                    <td>${escHtml(p.categorie) || '—'}</td>
                    <td>${escHtml(p.famille) || '—'}</td>
                    <td>${formatQuantiteProduit(p.quantite)}</td>
                    <td>${escHtml(uniteLibelle(p.unite))}</td>
                    <td>${formatPrixOptionnel(p.prix_achat)}</td>
                    <td>${formatPrixOptionnel(p.prix_vente)}</td>
                    <td class="col-actions no-print-produit" onclick="event.stopPropagation()">
                        <span class="col-actions-wrap">
                            <button type="button" class="btn-icon-row btn-icon-edit" data-edit-produit="${escHtml(p.ref)}" title="Modifier" aria-label="Modifier">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </button>
                            <button type="button" class="btn-icon-row btn-icon-delete" data-delete-produit="${escHtml(p.ref)}" title="Supprimer" aria-label="Supprimer">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                            </button>
                        </span>
                    </td>
                </tr>`;
            }).join('');

            produitsTableBody.querySelectorAll('tr[data-produit-ref]').forEach(row => {
                row.addEventListener('click', () => {
                    selectedProduitRef = row.dataset.produitRef;
                    renderProduitsTable();
                });
            });
            produitsTableBody.querySelectorAll('[data-edit-produit]').forEach(btn => {
                btn.addEventListener('click', () => editProduit(btn.dataset.editProduit));
            });
            produitsTableBody.querySelectorAll('[data-delete-produit]').forEach(btn => {
                btn.addEventListener('click', () => deleteProduit(btn.dataset.deleteProduit));
            });
            renderProduitQrCodes();
        }

        function editProduit(ref) {
            const p = produits.find(x => x.ref === ref);
            if (!p) return;
            editingProduitRef = ref;
            selectedProduitRef = ref;
            showProduitForm(false);
            populateProduitUniteSelect();
            refreshLookupSelects({
                pr_designation: p.designation || '',
                pr_categorie: p.categorie || '',
                pr_famille: p.famille || '',
            });
            if (prRefInput) prRefInput.value = p.ref;
            document.getElementById('pr_designation').value = p.designation || '';
            document.getElementById('pr_type').value = p.type || '';
            document.getElementById('pr_categorie').value = p.categorie || '';
            document.getElementById('pr_famille').value = p.famille || '';
            document.getElementById('pr_quantite').value = p.quantite ?? 0;
            document.getElementById('pr_unite').value = p.unite || '';
            document.getElementById('pr_prix_achat').value = p.prix_achat != null && p.prix_achat !== '' ? parseFloat(p.prix_achat).toFixed(2) : '';
            document.getElementById('pr_prix_vente').value = p.prix_vente != null && p.prix_vente !== '' ? parseFloat(p.prix_vente).toFixed(2) : '';
            resetProduitPhoto();
            const photoUrl = getProduitPhotoUrl(p);
            if (photoUrl) setProduitPhotoPreview(photoUrl);
            const title = document.getElementById('produitFormTitle');
            const subtitle = document.getElementById('produitFormSubtitle');
            const saveBtn = document.getElementById('saveProduitBtn');
            if (title) title.textContent = 'Modifier Produit';
            if (subtitle) subtitle.textContent = p.ref;
            if (saveBtn) saveBtn.textContent = 'Mettre à jour';
        }

        async function deleteProduit(ref) {
            const p = produits.find(x => x.ref === ref);
            if (!p) return;
            if (!confirm('Supprimer le produit « ' + p.designation + ' » (' + ref + ') ?')) return;
            try {
                const res = await fetch('/api/produits/' + encodeURIComponent(ref), {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                });
                if (!res.ok) {
                    const data = await res.json();
                    alert(data.message || 'Erreur lors de la suppression');
                    return;
                }
                if (selectedProduitRef === ref) selectedProduitRef = null;
                if (editingProduitRef === ref) editingProduitRef = null;
                await loadProduits();
                showProduitList();
                const toast = document.getElementById('cartToast');
                if (toast) {
                    toast.textContent = 'Produit ' + ref + ' supprimé';
                    toast.classList.add('show');
                    setTimeout(() => toast.classList.remove('show'), 2800);
                }
            } catch (err) {
                console.error(err);
                alert('Impossible de supprimer le produit.');
            }
        }

        async function loadProduits() {
            try {
                const res = await fetch('/api/produits');
                if (!res.ok) throw new Error('Erreur chargement');
                const data = await res.json();
                produits = data.produits || [];
                nextProduitRef = data.next_ref || 'PR0001';
                if (prRefInput && !editingProduitRef) prRefInput.value = nextProduitRef;
                refreshLookupSelects({
                    pr_designation: document.getElementById('pr_designation')?.value || '',
                    pr_categorie: document.getElementById('pr_categorie')?.value || '',
                    pr_famille: document.getElementById('pr_famille')?.value || '',
                    ach_ligne_designation: document.getElementById('ach_ligne_designation')?.value || '',
                });
            } catch (err) {
                console.error(err);
                produits = [];
            }
        }

        async function saveProduit() {
            const designation = document.getElementById('pr_designation')?.value?.trim() || '';
            if (!designation) {
                alert('Veuillez sélectionner la désignation.');
                document.getElementById('pr_designation')?.focus();
                return;
            }

            const prixAchatRaw = document.getElementById('pr_prix_achat')?.value;
            const prixVenteRaw = document.getElementById('pr_prix_vente')?.value;
            const formData = new FormData();
            formData.append('designation', designation);
            formData.append('type', document.getElementById('pr_type')?.value?.trim() || '');
            formData.append('categorie', document.getElementById('pr_categorie')?.value?.trim() || '');
            formData.append('famille', document.getElementById('pr_famille')?.value?.trim() || '');
            formData.append('quantite', String(parseFloat(document.getElementById('pr_quantite')?.value) || 0));
            formData.append('unite', document.getElementById('pr_unite')?.value || '');
            if (prixAchatRaw !== '' && prixAchatRaw != null) formData.append('prix_achat', String(parseFloat(prixAchatRaw)));
            if (prixVenteRaw !== '' && prixVenteRaw != null) formData.append('prix_vente', String(parseFloat(prixVenteRaw)));
            if (produitPhotoFile) formData.append('photo', produitPhotoFile);
            if (produitPhotoRemove) formData.append('remove_photo', '1');

            const isEdit = !!editingProduitRef;
            const saveBtn = document.getElementById('saveProduitBtn');
            if (saveBtn) {
                saveBtn.disabled = true;
                saveBtn.textContent = isEdit ? 'Mise à jour…' : 'Enregistrement…';
            }

            try {
                const url = isEdit
                    ? '/api/produits/' + encodeURIComponent(editingProduitRef)
                    : '/api/produits';
                if (isEdit) formData.append('_method', 'PUT');
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: formData,
                });
                const data = await res.json();
                if (!res.ok) {
                    const msg = data.message || (data.errors ? Object.values(data.errors).flat().join('\n') : 'Erreur');
                    alert(msg);
                    return;
                }
                resetProduitFormMode();
                selectedProduitRef = data.produit?.ref || null;
                await loadProduits();
                showProduitList();
                const toast = document.getElementById('cartToast');
                if (toast) {
                    toast.textContent = isEdit
                        ? 'Produit ' + data.produit.ref + ' modifié'
                        : 'Produit ' + data.produit.ref + ' enregistré';
                    toast.classList.add('show');
                    setTimeout(() => toast.classList.remove('show'), 2800);
                }
            } catch (err) {
                console.error(err);
                alert('Impossible d\'enregistrer le produit.');
            } finally {
                if (saveBtn) {
                    saveBtn.disabled = false;
                    saveBtn.textContent = isEdit ? 'Mettre à jour' : 'Enregistrer';
                }
            }
        }

        const SAISIES_RESET_VERSION = '2026-07-08-v1';

        function resetLocalSaisiesIfNeeded() {
            if (localStorage.getItem('saisiesResetVersion') === SAISIES_RESET_VERSION) return;
            localStorage.removeItem('commandesAchats');
            localStorage.removeItem('achatsBonCounter');
            localStorage.setItem('saisiesResetVersion', SAISIES_RESET_VERSION);
        }

        resetLocalSaisiesIfNeeded();

        let achatsBonCounter = parseInt(localStorage.getItem('achatsBonCounter') || '0', 10);
        let fillingAchatsFournisseur = false;
        let unitesMesure = [];
        let achatsLignes = [];
        let editingAchatsLineIndex = null;
        let selectedAchatsLineIndex = null;
        let commandesAchats = [];
        let editingCommandeIndex = null;
        let selectedCommandeIndex = null;

        function loadCommandesAchats() {
            try {
                commandesAchats = JSON.parse(localStorage.getItem('commandesAchats') || '[]');
            } catch (err) {
                commandesAchats = [];
            }
        }

        function persistCommandesAchats() {
            localStorage.setItem('commandesAchats', JSON.stringify(commandesAchats));
        }

        function formatDateFr(iso) {
            if (!iso) return '—';
            const d = new Date(iso + (iso.length === 10 ? 'T12:00:00' : ''));
            if (isNaN(d.getTime())) return iso;
            return d.toLocaleDateString('fr-FR');
        }

        function commandeTotalQte(c) {
            return (c.lignes || []).reduce((s, l) => s + (parseFloat(l.quantite) || 0), 0);
        }

        function commandeVille(c) {
            return c.ville_livraison || c.ville || '';
        }

        function renderCommandesAchatsTableInto(tbodyId, printDateId) {
            const tbody = document.getElementById(tbodyId);
            const printDate = document.getElementById(printDateId);
            if (printDate) {
                printDate.textContent = 'Édité le ' + new Date().toLocaleDateString('fr-FR', {
                    day: '2-digit', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'
                });
            }
            if (!tbody) return;
            if (commandesAchats.length === 0) {
                tbody.innerHTML = '<tr><td colspan="10" class="achats-commandes-empty">Aucune commande saisie</td></tr>';
                return;
            }
            tbody.innerHTML = commandesAchats.map((c, i) => {
                const selected = selectedCommandeIndex === i ? ' selected' : '';
                const qte = commandeTotalQte(c);
                return `<tr class="${selected}" data-commande-index="${i}">
                    <td><strong>${escHtml(c.bon)}</strong></td>
                    <td>${formatDateFr(c.date_cmd)}</td>
                    <td>${escHtml(c.code_fournisseur) || '—'}</td>
                    <td class="cmd-col-nom" title="${escHtml(c.nom_fournisseur) || ''}">${escHtml(c.nom_fournisseur) || '—'}</td>
                    <td>${escHtml(commandeVille(c)) || '—'}</td>
                    <td>${qte.toLocaleString('fr-FR')}</td>
                    <td><strong>${formatMoney(c.total || 0)}</strong></td>
                    <td>${escHtml(c.type_reglement) || '—'}</td>
                    <td>${formatDateFr(c.echeance)}</td>
                    <td class="col-actions col-actions-cmd no-print-cmd" onclick="event.stopPropagation()">
                        <span class="cmd-actions-wrap">
                            <button type="button" class="btn-row btn-row-edit" data-regler-commande="${i}" ${isCommandePayee(c) ? 'style="display:none"' : ''}>Régler</button>
                            <button type="button" class="btn-row btn-row-edit" data-voir-commande="${i}">Voir</button>
                            <button type="button" class="btn-row btn-row-edit" data-modifier-commande="${i}">Modifier</button>
                            <button type="button" class="btn-row btn-row-delete" data-suppr-commande="${i}">Supprimer</button>
                        </span>
                    </td>
                </tr>`;
            }).join('');

            tbody.querySelectorAll('tr[data-commande-index]').forEach(row => {
                row.addEventListener('click', () => {
                    selectedCommandeIndex = parseInt(row.dataset.commandeIndex, 10);
                    renderCommandesAchatsTable();
                });
            });
            tbody.querySelectorAll('[data-regler-commande]').forEach(btn => {
                btn.addEventListener('click', () => reglerCommandeAchats(parseInt(btn.dataset.reglerCommande, 10)));
            });
            tbody.querySelectorAll('[data-voir-commande]').forEach(btn => {
                btn.addEventListener('click', () => voirCommandeDetail(parseInt(btn.dataset.voirCommande, 10)));
            });
            tbody.querySelectorAll('[data-modifier-commande]').forEach(btn => {
                btn.addEventListener('click', () => loadCommandeIntoForm(parseInt(btn.dataset.modifierCommande, 10)));
            });
            tbody.querySelectorAll('[data-suppr-commande]').forEach(btn => {
                btn.addEventListener('click', () => deleteCommandeAchats(parseInt(btn.dataset.supprCommande, 10)));
            });
        }

        function renderCommandesAchatsTable() {
            renderCommandesAchatsTableInto('commandesListTableBody', 'commandesPrintDate');
            renderCommandesAchatsTableInto('commandesListTableBodyAchats', 'commandesPrintDateAchats');
        }

        function voirCommandeDetail(index) {
            const c = commandesAchats[index];
            if (!c) return;
            const modal = document.getElementById('commandeDetailModal');
            const body = document.getElementById('commandeDetailBody');
            const title = document.getElementById('commandeDetailTitle');
            if (!modal || !body) return;
            if (title) title.textContent = 'Commande ' + (c.bon || '');
            const lignesHtml = (c.lignes || []).length === 0
                ? '<p style="color:#6B6B68;font-size:13px;">Aucune ligne article.</p>'
                : `<table class="achats-lines-table" style="min-width:100%;margin-top:12px;">
                    <thead><tr><th>Réf</th><th>Code barre</th><th>Désignation</th><th>Qté</th><th>Mesure</th><th>Prix U</th><th>Sous-Total</th></tr></thead>
                    <tbody>${(c.lignes || []).map(l => `<tr>
                        <td>${escHtml(l.ref) || '—'}</td>
                        <td>${escHtml(l.code_barre) || '—'}</td>
                        <td>${escHtml(l.designation)}</td>
                        <td>${(parseFloat(l.quantite) || 0).toLocaleString('fr-FR')}</td>
                        <td>${escHtml(l.mesure_libelle || l.mesure) || '—'}</td>
                        <td>${formatMoney(l.prix_u)}</td>
                        <td><strong>${formatMoney(l.sous_total)}</strong></td>
                    </tr>`).join('')}</tbody>
                </table>`;
            body.innerHTML = `
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px 28px;font-size:13px;margin-bottom:12px;">
                    <div><span style="color:#6B6B68;font-size:11px;text-transform:uppercase;">Date Cmd</span><br><strong>${formatDateFr(c.date_cmd)}</strong></div>
                    <div><span style="color:#6B6B68;font-size:11px;text-transform:uppercase;">Code</span><br><strong>${escHtml(c.code_fournisseur) || '—'}</strong></div>
                    <div><span style="color:#6B6B68;font-size:11px;text-transform:uppercase;">Fournisseur</span><br><strong>${escHtml(c.nom_fournisseur) || '—'}</strong></div>
                    <div><span style="color:#6B6B68;font-size:11px;text-transform:uppercase;">Ville</span><br><strong>${escHtml(commandeVille(c)) || '—'}</strong></div>
                    <div><span style="color:#6B6B68;font-size:11px;text-transform:uppercase;">Règlement</span><br><strong>${escHtml(c.type_reglement) || '—'}</strong></div>
                    <div><span style="color:#6B6B68;font-size:11px;text-transform:uppercase;">Échéance</span><br><strong>${formatDateFr(c.echeance)}</strong></div>
                    <div><span style="color:#6B6B68;font-size:11px;text-transform:uppercase;">Qté totale</span><br><strong>${commandeTotalQte(c).toLocaleString('fr-FR')}</strong></div>
                    <div><span style="color:#6B6B68;font-size:11px;text-transform:uppercase;">Total</span><br><strong style="color:#003326;font-size:16px;">${formatMoney(c.total || 0)}</strong></div>
                    <div><span style="color:#6B6B68;font-size:11px;text-transform:uppercase;">Statut paiement</span><br><strong>${isCommandePayee(c) ? 'Payé' : 'Non payé'}</strong></div>
                </div>
                ${lignesHtml}`;
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeCommandeDetailModal() {
            const modal = document.getElementById('commandeDetailModal');
            if (modal) modal.classList.remove('active');
            document.body.style.overflow = '';
        }

        function loadCommandeIntoForm(index) {
            const c = commandesAchats[index];
            if (!c) return;
            editingCommandeIndex = index;
            selectedCommandeIndex = index;
            loadFournisseurs().then(() => {
                updateAchatsFournisseurDatalists();
                return Promise.all([loadProduits(), loadUnitesMesure()]);
            }).then(() => {
                refreshLookupSelects({ ach_ville_livraison: c.ville_livraison || '' });
                    document.getElementById('ach_bon').value = c.bon || '';
                    document.getElementById('ach_date_cmd').value = c.date_cmd || '';
                    document.getElementById('ach_code_fournisseur').value = c.code_fournisseur || '';
                    document.getElementById('ach_nom_fournisseur').value = c.nom_fournisseur || '';
                    lookupAchatsFournisseurByCode();
                    document.getElementById('ach_type_reglement').value = c.type_reglement || '';
                    document.getElementById('ach_echeance').value = c.echeance || '';
                    document.getElementById('ach_recuperation').value = c.recuperation || '';
                    document.getElementById('ach_ville_livraison').value = c.ville_livraison || '';
                    document.getElementById('ach_transport').value = c.transport || '';
                    document.getElementById('ach_matricule').value = c.matricule || '';
                    document.getElementById('ach_chauffeur').value = c.chauffeur || '';
                    achatsLignes = (c.lignes || []).map(l => ({ ...l }));
                    clearAchatsLigneForm();
                    renderAchatsLignesTable();
                    showAppView('achats', { keepForm: true, returnView: 'commandes' });
                    document.getElementById('achatsSaisiePanel')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        }

        function reglerCommandeAchats(index) {
            const c = commandesAchats[index];
            if (!c || isCommandePayee(c)) return;
            if (!confirm('Marquer la commande « ' + c.bon + ' » comme payée ?')) return;
            c.paye = true;
            persistCommandesAchats();
            renderCommandesAchatsTable();
            refreshFournisseurSoldesAfterCommandeChange(c);
        }

        function deleteCommandeAchats(index) {
            const c = commandesAchats[index];
            if (!c) return;
            if (!confirm('Supprimer la commande « ' + c.bon + ' » ?')) return;
            commandesAchats.splice(index, 1);
            persistCommandesAchats();
            if (editingCommandeIndex === index) {
                editingCommandeIndex = null;
                resetAchatsForm();
            } else if (editingCommandeIndex !== null && editingCommandeIndex > index) {
                editingCommandeIndex -= 1;
            }
            if (selectedCommandeIndex === index) selectedCommandeIndex = null;
            else if (selectedCommandeIndex !== null && selectedCommandeIndex > index) selectedCommandeIndex -= 1;
            renderCommandesAchatsTable();
            refreshFournisseurSoldesAfterCommandeChange(c);
        }

        function saveCommandeAchats() {
            const header = getAchatsHeaderInfo();
            if (!header.code_fournisseur && !header.nom_fournisseur) {
                alert('Veuillez saisir le fournisseur.');
                document.getElementById('ach_code_fournisseur')?.focus();
                return;
            }
            if (achatsLignes.length === 0) {
                alert('Ajoutez au moins une ligne article.');
                document.getElementById('ach_ligne_designation')?.focus();
                return;
            }
            const total = achatsLignes.reduce((s, l) => s + l.sous_total, 0);
            const previousCommande = editingCommandeIndex !== null ? { ...commandesAchats[editingCommandeIndex] } : null;
            const commande = {
                ...header,
                lignes: achatsLignes.map(l => ({ ...l })),
                total,
                paye: editingCommandeIndex !== null
                    ? previousCommande?.paye === true
                    : header.type_reglement === 'Esp',
                saved_at: new Date().toISOString(),
            };
            if (editingCommandeIndex !== null) {
                commandesAchats[editingCommandeIndex] = commande;
            } else {
                commandesAchats.unshift(commande);
            }
            persistCommandesAchats();
            renderCommandesAchatsTable();
            editingCommandeIndex = null;
            selectedCommandeIndex = null;
            resetAchatsForm();
            refreshFournisseurSoldesAfterCommandeChange(previousCommande, commande);
            const toast = document.getElementById('cartToast');
            if (toast) {
                toast.textContent = 'Commande ' + commande.bon + ' enregistrée';
                toast.classList.add('show');
                setTimeout(() => toast.classList.remove('show'), 2800);
            }
        }

        async function loadUnitesMesure() {
            try {
                const res = await fetch('/api/unites-mesure');
                if (!res.ok) throw new Error('Erreur chargement mesures');
                const data = await res.json();
                unitesMesure = data.unites || [];
                const select = document.getElementById('ach_ligne_mesure');
                if (select) {
                    select.innerHTML = '<option value="">— Sélectionner —</option>' +
                        unitesMesure.map(u => `<option value="${u.code}">${u.libelle} (${u.code})</option>`).join('');
                }
            } catch (err) {
                console.error(err);
                const select = document.getElementById('ach_ligne_mesure');
                if (select) {
                    select.innerHTML = '<option value="">— Unité —</option><option value="KG">Kilogramme (KG)</option><option value="UN">Unité (UN)</option>';
                }
            }
        }

        function formatMoney(val) {
            const n = parseFloat(val) || 0;
            return n.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' MAD';
        }

        function parseMoneyInput(val) {
            return parseFloat(String(val || '').replace(',', '.')) || 0;
        }

        // Formate tout champ de montant pour qu'il se termine par .00 (2 décimales)
        function formatMoneyInputField(el) {
            if (!el) return;
            const raw = String(el.value || '').trim().replace(',', '.');
            if (raw === '') return;
            const n = parseFloat(raw);
            if (isNaN(n)) return;
            el.value = n.toFixed(2);
        }

        document.addEventListener('focusout', function (e) {
            const t = e.target;
            if (t && t.classList && t.classList.contains('money-input')) {
                formatMoneyInputField(t);
            }
        });

        function calcAchatsLigneSousTotal() {
            const qte = parseFloat(document.getElementById('ach_ligne_quantite')?.value) || 0;
            const prix = parseFloat(document.getElementById('ach_ligne_prix')?.value) || 0;
            const total = qte * prix;
            const out = document.getElementById('ach_ligne_sous_total');
            if (out) out.value = formatMoney(total);
            return total;
        }

        function getAchatsLigneFormData() {
            const mesureCode = document.getElementById('ach_ligne_mesure')?.value || '';
            const mesureObj = unitesMesure.find(u => u.code === mesureCode);
            const qte = parseFloat(document.getElementById('ach_ligne_quantite')?.value) || 0;
            const prix = parseFloat(document.getElementById('ach_ligne_prix')?.value) || 0;
            return {
                ref: document.getElementById('ach_ligne_ref')?.value?.trim() || '',
                code_barre: document.getElementById('ach_ligne_code_barre')?.value?.trim() || '',
                designation: document.getElementById('ach_ligne_designation')?.value?.trim() || '',
                categorie: document.getElementById('ach_ligne_categorie')?.value || '',
                famille: document.getElementById('ach_ligne_famille')?.value?.trim() || '',
                quantite: qte,
                mesure: mesureCode,
                mesure_libelle: mesureObj ? mesureObj.libelle : mesureCode,
                prix_u: prix,
                sous_total: qte * prix,
            };
        }

        function fillAchatsLigneForm(line) {
            refreshLookupSelects({
                ach_ligne_designation: line.designation || '',
                ach_ligne_categorie: line.categorie || '',
                ach_ligne_famille: line.famille || '',
            });
            document.getElementById('ach_ligne_ref').value = line.ref || '';
            document.getElementById('ach_ligne_code_barre').value = line.code_barre || '';
            document.getElementById('ach_ligne_designation').value = line.designation || '';
            document.getElementById('ach_ligne_categorie').value = line.categorie || '';
            document.getElementById('ach_ligne_famille').value = line.famille || '';
            document.getElementById('ach_ligne_quantite').value = line.quantite ?? 1;
            document.getElementById('ach_ligne_mesure').value = line.mesure || '';
            document.getElementById('ach_ligne_prix').value = line.prix_u != null && line.prix_u !== '' ? parseFloat(line.prix_u).toFixed(2) : '';
            calcAchatsLigneSousTotal();
        }

        function clearAchatsLigneForm() {
            editingAchatsLineIndex = null;
            selectedAchatsLineIndex = null;
            document.getElementById('ach_ligne_ref').value = '';
            document.getElementById('ach_ligne_code_barre').value = '';
            document.getElementById('ach_ligne_designation').value = '';
            document.getElementById('ach_ligne_categorie').value = '';
            document.getElementById('ach_ligne_famille').value = '';
            document.getElementById('ach_ligne_quantite').value = '1';
            document.getElementById('ach_ligne_mesure').value = '';
            document.getElementById('ach_ligne_prix').value = '';
            document.getElementById('ach_ligne_sous_total').value = formatMoney(0);
            const modBtn = document.getElementById('modifierLigneAchatsBtn');
            if (modBtn) modBtn.textContent = 'Modifier';
            renderAchatsLignesTable();
        }

        function validateAchatsLigneData(data) {
            if (!data.designation) {
                alert('Veuillez sélectionner la désignation.');
                document.getElementById('ach_ligne_designation')?.focus();
                return false;
            }
            if (data.quantite <= 0) {
                alert('La quantité doit être supérieure à 0.');
                document.getElementById('ach_ligne_quantite')?.focus();
                return false;
            }
            if (!data.mesure) {
                alert('Veuillez sélectionner une mesure.');
                document.getElementById('ach_ligne_mesure')?.focus();
                return false;
            }
            return true;
        }

        function renderAchatsLignesTable() {
            const tbody = document.getElementById('achatsLignesTableBody');
            const totalEl = document.getElementById('achatsTotalGeneral');
            if (!tbody) return;
            if (achatsLignes.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" class="achats-lines-empty">Aucune ligne saisie</td></tr>';
                if (totalEl) totalEl.textContent = formatMoney(0);
                return;
            }
            let total = 0;
            tbody.innerHTML = achatsLignes.map((l, i) => {
                total += l.sous_total;
                const selected = selectedAchatsLineIndex === i ? ' selected' : '';
                return `<tr class="${selected}" data-line-index="${i}">
                    <td>${escHtml(l.ref) || '—'}</td>
                    <td>${escHtml(l.code_barre) || '—'}</td>
                    <td>${escHtml(l.designation)}</td>
                    <td>${escHtml(l.categorie) || '—'}</td>
                    <td>${escHtml(l.famille) || '—'}</td>
                    <td>${l.quantite.toLocaleString('fr-FR')}</td>
                    <td>${escHtml(l.mesure_libelle || l.mesure)}</td>
                    <td>${formatMoney(l.prix_u)}</td>
                    <td><strong>${formatMoney(l.sous_total)}</strong></td>
                </tr>`;
            }).join('');
            if (totalEl) totalEl.textContent = formatMoney(total);
            tbody.querySelectorAll('tr[data-line-index]').forEach(row => {
                row.addEventListener('click', () => {
                    selectedAchatsLineIndex = parseInt(row.dataset.lineIndex, 10);
                    renderAchatsLignesTable();
                });
                row.addEventListener('dblclick', () => {
                    const idx = parseInt(row.dataset.lineIndex, 10);
                    editingAchatsLineIndex = idx;
                    selectedAchatsLineIndex = idx;
                    fillAchatsLigneForm(achatsLignes[idx]);
                    const modBtn = document.getElementById('modifierLigneAchatsBtn');
                    if (modBtn) modBtn.textContent = 'Mettre à jour';
                });
            });
        }

        function getAchatsHeaderInfo() {
            return {
                bon: document.getElementById('ach_bon')?.value || '',
                date_cmd: document.getElementById('ach_date_cmd')?.value || '',
                code_fournisseur: document.getElementById('ach_code_fournisseur')?.value || '',
                nom_fournisseur: document.getElementById('ach_nom_fournisseur')?.value || '',
                type_reglement: document.getElementById('ach_type_reglement')?.value || '',
                echeance: document.getElementById('ach_echeance')?.value || '',
                recuperation: document.getElementById('ach_recuperation')?.value || '',
                ville_livraison: document.getElementById('ach_ville_livraison')?.value || '',
                transport: document.getElementById('ach_transport')?.value || '',
                matricule: document.getElementById('ach_matricule')?.value || '',
                chauffeur: document.getElementById('ach_chauffeur')?.value || '',
            };
        }

        function nextAchatsBonNumber() {
            achatsBonCounter += 1;
            localStorage.setItem('achatsBonCounter', String(achatsBonCounter));
            return 'ACH' + String(achatsBonCounter).padStart(4, '0');
        }

        function todayIsoDate() {
            return new Date().toISOString().slice(0, 10);
        }

        function updateAchatsFournisseurDatalists() {
            const codesList = document.getElementById('achFournisseurCodesList');
            const nomsList = document.getElementById('achFournisseurNomsList');
            if (codesList) {
                codesList.innerHTML = fournisseurs.map(f => `<option value="${f.id}">${f.nom || ''}</option>`).join('');
            }
            if (nomsList) {
                nomsList.innerHTML = fournisseurs.map(f => `<option value="${f.nom || ''}">${f.id}</option>`).join('');
            }
        }

        function displayVal(val) {
            return val && String(val).trim() ? val : '—';
        }

        function formatSoldeDisplay(val) {
            const n = parseFloat(val) || 0;
            return n.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' MAD';
        }

        function fillAchatsFournisseurFields(f) {
            if (!f) return;
            fillingAchatsFournisseur = true;
            const codeInput = document.getElementById('ach_code_fournisseur');
            const nomInput = document.getElementById('ach_nom_fournisseur');
            const villeLivraison = document.getElementById('ach_ville_livraison');
            const typeReglement = document.getElementById('ach_type_reglement');
            if (codeInput) codeInput.value = f.id || '';
            if (nomInput) nomInput.value = f.nom || '';
            if (villeLivraison && !villeLivraison.value.trim() && f.ville) {
                populateLookupSelect('ach_ville_livraison', LOOKUP_LISTS.villes, f.ville);
                villeLivraison.value = f.ville;
            }
            if (typeReglement && f.type_paiement) {
                typeReglement.value = f.type_paiement;
            }
            fillingAchatsFournisseur = false;
        }

        function findFournisseurByCode(code) {
            const q = (code || '').trim().toUpperCase();
            if (!q) return null;
            return fournisseurs.find(f => (f.id || '').toUpperCase() === q) || null;
        }

        function findFournisseurByNom(nom) {
            const q = (nom || '').trim().toLowerCase();
            if (!q) return null;
            return fournisseurs.find(f => (f.nom || '').trim().toLowerCase() === q)
                || fournisseurs.find(f => (f.nom || '').trim().toLowerCase().includes(q))
                || null;
        }

        function resetAchatsForm(keepBon = false) {
            if (!achatsForm) return;
            const bonInput = document.getElementById('ach_bon');
            const currentBon = bonInput?.value;
            achatsForm.reset();
            if (bonInput) bonInput.value = keepBon && currentBon ? currentBon : nextAchatsBonNumber();
            const dateCmd = document.getElementById('ach_date_cmd');
            if (dateCmd) dateCmd.value = todayIsoDate();
            achatsLignes = [];
            editingCommandeIndex = null;
            clearAchatsLigneForm();
        }

        function lookupAchatsFournisseurByCode() {
            if (fillingAchatsFournisseur) return;
            const code = document.getElementById('ach_code_fournisseur')?.value || '';
            const f = findFournisseurByCode(code);
            if (f) fillAchatsFournisseurFields(f);
        }

        function lookupAchatsFournisseurByNom() {
            if (fillingAchatsFournisseur) return;
            const nom = document.getElementById('ach_nom_fournisseur')?.value || '';
            const f = findFournisseurByNom(nom);
            if (f) fillAchatsFournisseurFields(f);
        }

        document.getElementById('ach_code_fournisseur')?.addEventListener('input', lookupAchatsFournisseurByCode);
        document.getElementById('ach_code_fournisseur')?.addEventListener('change', lookupAchatsFournisseurByCode);
        document.getElementById('ach_nom_fournisseur')?.addEventListener('input', lookupAchatsFournisseurByNom);
        document.getElementById('ach_nom_fournisseur')?.addEventListener('change', lookupAchatsFournisseurByNom);

        document.getElementById('ach_ligne_designation')?.addEventListener('change', (e) => {
            applyAchatsLigneFromProduit(e.target.value);
        });
        document.getElementById('pr_designation')?.addEventListener('change', (e) => {
            applyProduitFormFromDesignation(e.target.value);
        });

        document.getElementById('prPhotoPickBtn')?.addEventListener('click', () => {
            document.getElementById('pr_photo_file')?.click();
        });
        document.getElementById('prPhotoCaptureBtn')?.addEventListener('click', () => {
            document.getElementById('pr_photo_camera')?.click();
        });
        document.getElementById('pr_photo_file')?.addEventListener('change', (e) => {
            const file = e.target.files?.[0];
            if (file) handleProduitPhotoFile(file);
        });
        document.getElementById('pr_photo_camera')?.addEventListener('change', (e) => {
            const file = e.target.files?.[0];
            if (file) handleProduitPhotoFile(file);
        });
        document.getElementById('prPhotoRemoveBtn')?.addEventListener('click', () => {
            produitPhotoRemove = true;
            resetProduitPhoto(false);
        });

        document.getElementById('ach_ligne_quantite')?.addEventListener('input', calcAchatsLigneSousTotal);
        document.getElementById('ach_ligne_prix')?.addEventListener('input', calcAchatsLigneSousTotal);

        document.getElementById('validerLigneAchatsBtn')?.addEventListener('click', () => {
            const data = getAchatsLigneFormData();
            if (!validateAchatsLigneData(data)) return;
            if (editingAchatsLineIndex !== null) {
                achatsLignes[editingAchatsLineIndex] = data;
            } else {
                achatsLignes.push(data);
            }
            clearAchatsLigneForm();
            renderAchatsLignesTable();
        });

        document.getElementById('modifierLigneAchatsBtn')?.addEventListener('click', () => {
            if (editingAchatsLineIndex !== null) {
                const data = getAchatsLigneFormData();
                if (!validateAchatsLigneData(data)) return;
                achatsLignes[editingAchatsLineIndex] = data;
                clearAchatsLigneForm();
                renderAchatsLignesTable();
                return;
            }
            if (selectedAchatsLineIndex === null) {
                alert('Sélectionnez une ligne dans le tableau (clic ou double-clic).');
                return;
            }
            editingAchatsLineIndex = selectedAchatsLineIndex;
            fillAchatsLigneForm(achatsLignes[selectedAchatsLineIndex]);
            document.getElementById('modifierLigneAchatsBtn').textContent = 'Mettre à jour';
        });

        document.getElementById('annulerLigneAchatsBtn')?.addEventListener('click', () => clearAchatsLigneForm());

        document.getElementById('enregistrerCommandeAchatsBtn')?.addEventListener('click', saveCommandeAchats);

        document.getElementById('fermerAchatsBtn')?.addEventListener('click', () => {
            showAppView(achatsReturnView);
        });

        document.getElementById('nouvelleCommandeBtn')?.addEventListener('click', () => {
            editingCommandeIndex = null;
            selectedCommandeIndex = null;
            showAppView('achats', { returnView: 'commandes' });
        });

        document.getElementById('nouveauBonAchatsBtn')?.addEventListener('click', () => {
            editingCommandeIndex = null;
            selectedCommandeIndex = null;
            showAppView('achats', { returnView: 'achats', mode: 'saisie' });
        });

        document.getElementById('printCommandesAchatsBtn')?.addEventListener('click', () => {
            if (commandesAchats.length === 0) {
                alert('Aucun bon à imprimer.');
                return;
            }
            document.body.classList.add('print-achats-consult');
            const cleanup = () => document.body.classList.remove('print-achats-consult');
            window.addEventListener('afterprint', cleanup, { once: true });
            window.print();
            setTimeout(cleanup, 1000);
        });

        document.getElementById('exportCommandesAchatsPdfBtn')?.addEventListener('click', () => {
            if (commandesAchats.length === 0) {
                alert('Aucun bon à exporter.');
                return;
            }
            if (!window.jspdf) {
                alert('Bibliothèque PDF non chargée.');
                return;
            }
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
            doc.setFontSize(16);
            doc.setTextColor(0, 51, 38);
            doc.text('SWEET AUSTRIA — Bons d\'achat', 14, 16);
            doc.setFontSize(9);
            doc.setTextColor(107, 107, 104);
            doc.text('Édité le ' + new Date().toLocaleDateString('fr-FR'), 14, 22);
            doc.autoTable({
                startY: 28,
                head: [['Bon N°', 'Date Cmd', 'Code', 'Nom Fournisseur', 'Ville', 'Qté', 'Total', 'Règlement', 'Échéance']],
                body: commandesAchats.map(c => [
                    c.bon,
                    formatDateFr(c.date_cmd),
                    c.code_fournisseur || '—',
                    c.nom_fournisseur || '—',
                    commandeVille(c) || '—',
                    commandeTotalQte(c).toLocaleString('fr-FR'),
                    formatMoney(c.total || 0),
                    c.type_reglement || '—',
                    formatDateFr(c.echeance),
                ]),
                styles: { fontSize: 7, cellPadding: 2 },
                headStyles: { fillColor: [0, 51, 38], textColor: 255, fontStyle: 'bold' },
                alternateRowStyles: { fillColor: [249, 248, 243] },
                margin: { left: 14, right: 14 },
            });
            doc.save('bons-achat-sweet-austria.pdf');
        });

        document.getElementById('printCommandesBtn')?.addEventListener('click', () => {
            if (commandesAchats.length === 0) {
                alert('Aucune commande à imprimer.');
                return;
            }
            window.print();
        });

        document.getElementById('exportCommandesPdfBtn')?.addEventListener('click', () => {
            if (commandesAchats.length === 0) {
                alert('Aucune commande à exporter.');
                return;
            }
            if (!window.jspdf) {
                alert('Bibliothèque PDF non chargée.');
                return;
            }
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
            doc.setFontSize(16);
            doc.setTextColor(0, 51, 38);
            doc.text('SWEET AUSTRIA — Commandes saisies', 14, 16);
            doc.setFontSize(9);
            doc.setTextColor(107, 107, 104);
            doc.text('Édité le ' + new Date().toLocaleDateString('fr-FR'), 14, 22);
            doc.autoTable({
                startY: 28,
                head: [['Bon N°', 'Date Cmd', 'Code', 'Nom Fournisseur', 'Ville', 'Qté', 'Total', 'Règlement', 'Échéance']],
                body: commandesAchats.map(c => [
                    c.bon,
                    formatDateFr(c.date_cmd),
                    c.code_fournisseur || '—',
                    c.nom_fournisseur || '—',
                    commandeVille(c) || '—',
                    commandeTotalQte(c).toLocaleString('fr-FR'),
                    formatMoney(c.total || 0),
                    c.type_reglement || '—',
                    formatDateFr(c.echeance),
                ]),
                styles: { fontSize: 7, cellPadding: 2 },
                headStyles: { fillColor: [0, 51, 38], textColor: 255, fontStyle: 'bold' },
                alternateRowStyles: { fillColor: [249, 248, 243] },
                margin: { left: 14, right: 14 },
            });
            doc.save('commandes-sweet-austria.pdf');
        });

        document.getElementById('closeCommandeDetail')?.addEventListener('click', closeCommandeDetailModal);
        document.getElementById('commandeDetailModal')?.addEventListener('click', e => {
            if (e.target.id === 'commandeDetailModal') closeCommandeDetailModal();
        });

        document.getElementById('printAchatsBtn')?.addEventListener('click', () => {
            if (achatsLignes.length === 0) {
                alert('Ajoutez au moins une ligne avant d\'imprimer.');
                return;
            }
            window.print();
        });

        document.getElementById('exportAchatsPdfBtn')?.addEventListener('click', () => {
            if (achatsLignes.length === 0) {
                alert('Ajoutez au moins une ligne avant d\'exporter.');
                return;
            }
            if (!window.jspdf) {
                alert('Bibliothèque PDF non chargée.');
                return;
            }
            const info = getAchatsHeaderInfo();
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
            doc.setFontSize(16);
            doc.setTextColor(0, 51, 38);
            doc.text('SWEET AUSTRIA — Bon d\'Achat ' + info.bon, 14, 16);
            doc.setFontSize(9);
            doc.setTextColor(107, 107, 104);
            doc.text('Date : ' + (info.date_cmd || '—') + '  |  Fournisseur : ' + (info.nom_fournisseur || info.code_fournisseur || '—'), 14, 22);
            doc.text('Livraison : ' + (info.ville_livraison || '—') + '  |  Transport : ' + (info.transport || '—'), 14, 27);
            const total = achatsLignes.reduce((s, l) => s + l.sous_total, 0);
            doc.autoTable({
                startY: 32,
                head: [['Réf', 'Code barre', 'Désignation', 'Catégorie', 'Famille', 'Qté', 'Mesure', 'Prix U', 'Sous-Total']],
                body: achatsLignes.map(l => [
                    l.ref || '—', l.code_barre || '—', l.designation, l.categorie || '—', l.famille || '—',
                    l.quantite.toLocaleString('fr-FR'), l.mesure_libelle || l.mesure,
                    formatMoney(l.prix_u), formatMoney(l.sous_total)
                ]),
                foot: [['', '', '', '', '', '', '', 'Total', formatMoney(total)]],
                styles: { fontSize: 7, cellPadding: 2 },
                headStyles: { fillColor: [0, 51, 38], textColor: 255, fontStyle: 'bold' },
                footStyles: { fillColor: [240, 238, 234], textColor: [0, 51, 38], fontStyle: 'bold' },
                alternateRowStyles: { fillColor: [249, 248, 243] },
                margin: { left: 14, right: 14 },
            });
            doc.save('bon-achat-' + (info.bon || 'export') + '.pdf');
        });

        achatsForm?.addEventListener('submit', e => e.preventDefault());

        async function loadFournisseurs() {
            const data = await loadFournisseursReturn();
            if (data) {
                fournisseurs = data.fournisseurs || [];
                nextFournisseurId = data.next_id || 'FR0001';
                if (frIdInput) frIdInput.value = nextFournisseurId;
                applyFournisseurSoldesFromCommandes();
            }
        }

        async function loadFournisseursReturn() {
            try {
                const res = await fetch('/api/fournisseurs');
                if (!res.ok) throw new Error('Erreur chargement');
                return await res.json();
            } catch (err) {
                console.error(err);
                return null;
            }
        }

        async function saveFournisseur() {
            const nom = document.getElementById('fr_nom')?.value?.trim() || '';
            if (!nom) {
                alert('Veuillez saisir le nom du fournisseur.');
                document.getElementById('fr_nom')?.focus();
                return;
            }

            const isEdit = !!editingFournisseurId;
            const saveBtnLabel = isEdit ? 'Mettre à jour' : 'Enregistrer';

            const payload = {
                nom,
                type: document.getElementById('fr_type')?.value || '',
                ville: document.getElementById('fr_ville')?.value?.trim() || '',
                adresse: document.getElementById('fr_adresse')?.value?.trim() || '',
                telephone: document.getElementById('fr_telephone')?.value?.trim() || '',
                fixe: document.getElementById('fr_fixe')?.value?.trim() || '',
                email: document.getElementById('fr_email')?.value?.trim() || '',
                statut: document.getElementById('fr_statut')?.value || '',
                type_paiement: document.getElementById('fr_type_paiement')?.value || '',
                banque: document.getElementById('fr_banque')?.value?.trim() || '',
                rib: document.getElementById('fr_rib')?.value?.trim() || '',
                solde: parseFloat(document.getElementById('fr_solde')?.value) || 0,
            };

            const saveBtn = document.getElementById('saveFournisseurBtn');
            const validerBtn = document.getElementById('validerFournisseurBtn');
            if (validerBtn) validerBtn.disabled = true;
            if (saveBtn) {
                saveBtn.disabled = true;
                saveBtn.textContent = isEdit ? 'Mise à jour…' : 'Enregistrement…';
            }

            try {
                const url = isEdit
                    ? '/api/fournisseurs/' + encodeURIComponent(editingFournisseurId)
                    : '/api/fournisseurs';
                const res = await fetch(url, {
                    method: isEdit ? 'PUT' : 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify(payload),
                });

                const data = await res.json();

                if (!res.ok) {
                    const msg = data.message || (data.errors ? Object.values(data.errors).flat().join('\n') : 'Erreur lors de l\'enregistrement');
                    alert(msg);
                    return;
                }

                resetFournisseurFormMode();
                await loadFournisseurs();
                showFournisseurList();

                const toast = document.getElementById('cartToast');
                if (toast) {
                    toast.textContent = isEdit
                        ? 'Fournisseur ' + data.fournisseur.id + ' modifié'
                        : 'Fournisseur ' + data.fournisseur.id + ' enregistré';
                    toast.classList.add('show');
                    setTimeout(() => toast.classList.remove('show'), 2800);
                }
            } catch (err) {
                console.error(err);
                alert('Impossible d\'enregistrer le fournisseur. Vérifiez votre connexion.');
            } finally {
                if (saveBtn) {
                    saveBtn.disabled = false;
                    saveBtn.textContent = saveBtnLabel;
                }
                const validerBtn = document.getElementById('validerFournisseurBtn');
                if (validerBtn) validerBtn.disabled = false;
            }
        }

        document.getElementById('validerFournisseurBtn')?.addEventListener('click', () => saveFournisseur());

        document.getElementById('cancelFournisseurForm')?.addEventListener('click', () => {
            resetFournisseurFormMode();
            if (fournisseurs.length > 0) showFournisseurList();
            else document.querySelector('.nav-item[data-view="dashboard"]')?.click();
        });

        document.getElementById('addFournisseurBtn')?.addEventListener('click', () => showFournisseurForm(true));

        ficheFournisseurForm?.addEventListener('submit', e => {
            e.preventDefault();
            saveFournisseur();
        });

        document.getElementById('printFournisseursBtn')?.addEventListener('click', () => {
            if (fournisseurs.length === 0) {
                alert('Aucun fournisseur à imprimer.');
                return;
            }
            window.print();
        });

        document.getElementById('exportFournisseursPdfBtn')?.addEventListener('click', () => {
            if (fournisseurs.length === 0) {
                alert('Aucun fournisseur à exporter.');
                return;
            }
            if (!window.jspdf) {
                alert('Bibliothèque PDF non chargée.');
                return;
            }
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
            doc.setFontSize(16);
            doc.setTextColor(0, 51, 38);
            doc.text('SWEET AUSTRIA — Liste des Fournisseurs', 14, 16);
            doc.setFontSize(9);
            doc.setTextColor(107, 107, 104);
            doc.text('Édité le ' + new Date().toLocaleDateString('fr-FR'), 14, 22);
            applyFournisseurSoldesFromCommandes();
            doc.autoTable({
                startY: 28,
                head: [['ID', 'Nom', 'Type', 'Ville', 'Tél.', 'Fixe', 'E-mail', 'Statut', 'Paiement', 'Banque', 'RIB', 'Solde']],
                body: fournisseurs.map(f => [
                    f.id, f.nom, f.type, f.ville, f.telephone, f.fixe,
                    f.email, f.statut, f.type_paiement, f.banque, f.rib,
                    formatSolde(computeFournisseurSolde(f))
                ]),
                styles: { fontSize: 7, cellPadding: 2 },
                headStyles: { fillColor: [0, 51, 38], textColor: 255, fontStyle: 'bold' },
                alternateRowStyles: { fillColor: [249, 248, 243] },
                margin: { left: 14, right: 14 },
            });
            doc.save('fournisseurs-sweet-austria.pdf');
        });

        document.getElementById('addProduitBtn')?.addEventListener('click', () => {
            selectedProduitRef = null;
            loadUnitesMesure().then(() => showProduitForm(true));
        });

        document.getElementById('modifierProduitToolbarBtn')?.addEventListener('click', () => {
            if (selectedProduitRef) editProduit(selectedProduitRef);
        });

        document.getElementById('cancelProduitForm')?.addEventListener('click', () => {
            resetProduitFormMode();
            showProduitList();
        });

        ficheProduitForm?.addEventListener('submit', e => {
            e.preventDefault();
            saveProduit();
        });

        document.getElementById('printProduitsBtn')?.addEventListener('click', () => {
            if (produits.length === 0) {
                alert('Aucun produit à imprimer.');
                return;
            }
            window.print();
        });

        document.getElementById('exportProduitsPdfBtn')?.addEventListener('click', () => {
            if (produits.length === 0) {
                alert('Aucun produit à exporter.');
                return;
            }
            if (!window.jspdf) {
                alert('Bibliothèque PDF non chargée.');
                return;
            }
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });
            doc.setFontSize(16);
            doc.setTextColor(0, 51, 38);
            doc.text('SWEET AUSTRIA — Fiche Produit', 14, 16);
            doc.setFontSize(9);
            doc.setTextColor(107, 107, 104);
            doc.text('Édité le ' + new Date().toLocaleDateString('fr-FR'), 14, 22);
            doc.autoTable({
                startY: 28,
                head: [['Réf', 'Désignation', 'Type', 'Catégorie', 'Famille', 'Qté', 'U', 'Prix Achat', 'Prix Vente']],
                body: produits.map(p => [
                    p.ref,
                    p.designation,
                    p.type || '—',
                    p.categorie || '—',
                    p.famille || '—',
                    formatQuantiteProduit(p.quantite),
                    uniteLibelle(p.unite),
                    p.prix_achat != null ? formatMoney(p.prix_achat) : '—',
                    p.prix_vente != null ? formatMoney(p.prix_vente) : '—',
                ]),
                styles: { fontSize: 7, cellPadding: 2 },
                headStyles: { fillColor: [0, 51, 38], textColor: 255, fontStyle: 'bold' },
                alternateRowStyles: { fillColor: [249, 248, 243] },
                margin: { left: 14, right: 14 },
            });
            doc.save('fiche-produit-sweet-austria.pdf');
        });

        Promise.all([loadFournisseurs(), loadProduits(), loadUnitesMesure()]).then(() => refreshLookupSelects());

        const categoryCatalog = {
            coque: {
                title: '🌰 Fruits à coque (Noix et graines nobles)',
                products: [
                    { name: 'Amandes Premium Californie', image: 'https://images.unsplash.com/photo-1508747703725-719777637510?w=500&q=80', price: '185 MAD/kg', desc: 'Amandes entières sélectionnées, croquantes et riches en nutriments. Idéales en snack ou pâtisserie fine.' },
                    { name: 'Noix de Cajou W320', image: 'https://images.unsplash.com/photo-1599599810769-bda6a6a30469?w=500&q=80', price: '220 MAD/kg', desc: 'Noix de cajou entières de grade supérieur, saveur douce et texture onctueuse.' },
                    { name: 'Noix de Grenoble AOP', image: 'https://images.unsplash.com/photo-1559181567-c3190ca9959b?w=500&q=80', price: '195 MAD/kg', desc: 'Noix décortiquées, chair ferme et goût authentique des terroirs montagnards.' },
                    { name: 'Pistaches de Sicile', image: 'https://images.unsplash.com/photo-1576045057995-568f588f82fb?w=500&q=80', price: '280 MAD/kg', desc: 'Pistaches naturellement ouvertes, arôme intense et qualité gastronomique.' },
                ],
            },
            seche: {
                title: '🍇 Fruits séchés',
                products: [
                    { name: 'Abricots secs de Turquie', image: 'https://images.unsplash.com/photo-1587049352846-83a3988c6791?w=500&q=80', price: '95 MAD/kg', desc: 'Abricots moelleux, légèrement acidulés, séchés au soleil sans conservateurs.' },
                    { name: 'Dattes Medjool Premium', image: 'https://images.unsplash.com/photo-1585335208606-c7c710a45d9d?w=500&q=80', price: '145 MAD/kg', desc: 'Dattes extra moelleuses, caramel naturel, parfaites pour le Ramadan et le petit-déjeuner.' },
                    { name: 'Figues séchées Izmir', image: 'https://images.unsplash.com/photo-1606312619070-d48b4c652765?w=500&q=80', price: '110 MAD/kg', desc: 'Figues blanches tendres, saveur miellée, source naturelle de fibres.' },
                    { name: 'Raisins secs Golden', image: 'https://images.unsplash.com/photo-1571068316344-75bc76f77890?w=500&q=80', price: '75 MAD/kg', desc: 'Raisins dorés juteux, douceur naturelle pour muesli, salades et desserts.' },
                ],
            },
            cacahuetes: {
                title: '🥜 Cacahuètes et dérivés',
                products: [
                    { name: 'Cacahuètes grillées salées', image: 'https://images.unsplash.com/photo-1553627862-fbb7dd4c7102?w=500&q=80', price: '55 MAD/kg', desc: 'Cacahuètes croustillantes, légèrement salées, parfaites pour l\'apéritif.' },
                    { name: 'Cacahuètes nature bio', image: 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=500&q=80', price: '65 MAD/kg', desc: 'Cacahuètes décortiquées, sans sel, certifiées agriculture biologique.' },
                    { name: 'Beurre de cacahuète crémeux', image: 'https://images.unsplash.com/photo-1599599810769-bda6a6a30469?w=500&q=80', price: '89 MAD/pot', desc: 'Purée 100% cacahuètes, texture onctueuse, sans huile de palme ajoutée.' },
                ],
            },
            graines: {
                title: '🌻 Graines alimentaires',
                products: [
                    { name: 'Graines de tournesol décortiquées', image: 'https://images.unsplash.com/photo-1518843875459-f738682238c6?w=500&q=80', price: '45 MAD/kg', desc: 'Graines fraîches, riches en vitamine E, idéales en salade ou snack.' },
                    { name: 'Graines de courge', image: 'https://images.unsplash.com/photo-1608797178972-15b33a581138?w=500&q=80', price: '120 MAD/kg', desc: 'Graines de courge premium, goût de noisette, source de zinc et magnésium.' },
                    { name: 'Graines de chia bio', image: 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?w=500&q=80', price: '95 MAD/kg', desc: 'Graines noires de chia, super-aliment pour smoothies et bowls healthy.' },
                    { name: 'Mélange de graines gourmet', image: 'https://images.unsplash.com/photo-1608797178972-15b33a581138?w=500&q=80', price: '85 MAD/kg', desc: 'Assortiment tournesol, lin et sésame pour une nutrition complète.' },
                ],
            },
            enrobes: {
                title: '🍬 Fruits secs enrobés et confiseries',
                products: [
                    { name: 'Amandes enrobées chocolat noir', image: 'https://images.unsplash.com/photo-1548365328-0f4e0977132a?w=500&q=80', price: '165 MAD/kg', desc: 'Amandes entières enrobées de chocolat noir 70%, alliance croquante et intense.' },
                    { name: 'Raisins secs enrobés chocolat au lait', image: 'https://images.unsplash.com/photo-1481391319762-47dff72954d9?w=500&q=80', price: '135 MAD/kg', desc: 'Raisins moelleux nappés de chocolat au lait belge, douceur gourmande.' },
                    { name: 'Dattes fourrées amandes', image: 'https://images.unsplash.com/photo-1585335208606-c7c710a45d9d?w=500&q=80', price: '175 MAD/kg', desc: 'Dattes Medjool farcies d\'amandes entières, création artisanale de luxe.' },
                    { name: 'Orangettes au chocolat', image: 'https://images.unsplash.com/photo-1607922267115-ed5d32ecbc2c?w=500&q=80', price: '155 MAD/kg', desc: 'Écorces d\'orange confites enrobées de chocolat noir, saveur raffinée.' },
                ],
            },
            ramadan: {
                title: '🕌 Produits Ramadan et Fêtes',
                products: [
                    { name: 'Coffret Ramadan Prestige', image: 'https://images.unsplash.com/photo-1608797178972-15b33a581138?w=500&q=80', price: '450 MAD', desc: 'Coffret assortiment dattes, noix et fruits secs, présentation élégante pour l\'Iftar.' },
                    { name: 'Chebakia artisanale (500g)', image: 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?w=500&q=80', price: '85 MAD', desc: 'Chebakia traditionnelle au miel et sésame, préparée selon recette marocaine.' },
                    { name: 'Sellou aux amandes', image: 'https://images.unsplash.com/photo-1559181567-c3190ca9959b?w=500&q=80', price: '120 MAD/kg', desc: 'Sellou maison grillé, mélange énergétique de farine, amandes et miel.' },
                    { name: 'Panier Fêtes Sweet Austria', image: 'https://images.unsplash.com/photo-1599599810769-bda6a6a30469?w=500&q=80', price: '680 MAD', desc: 'Panier cadeau premium : fruits secs, confiseries et spécialités de fête.' },
                ],
            },
        };

        const categoryModal = document.getElementById('categoryModal');
        const categoryModalTitle = document.getElementById('categoryModalTitle');
        const categoryGalleryIntro = document.getElementById('categoryGalleryIntro');
        const productsGrid = document.getElementById('productsGrid');
        const closeCategoryModal = document.getElementById('closeCategoryModal');
        const cartToast = document.getElementById('cartToast');
        let toastTimeout;

        function restoreBodyScrollState() {
            const landing = document.getElementById('landingScreen');
            if (landing && !landing.classList.contains('is-hidden')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }

        function closeNavDropdowns() {
            document.querySelectorAll('.landing-nav-links .nav-dropdown-pin.is-open').forEach(dropdown => {
                dropdown.classList.remove('is-open');
                const toggle = dropdown.querySelector('.nav-dropdown-toggle');
                if (toggle) toggle.setAttribute('aria-expanded', 'false');
            });
        }

        function openNavDropdown(dropdown) {
            if (!dropdown) return;
            document.querySelectorAll('.landing-nav-links .nav-dropdown-pin.is-open').forEach(openDropdown => {
                if (openDropdown !== dropdown) {
                    openDropdown.classList.remove('is-open');
                    const toggle = openDropdown.querySelector('.nav-dropdown-toggle');
                    if (toggle) toggle.setAttribute('aria-expanded', 'false');
                }
            });
            dropdown.classList.add('is-open');
            const toggle = dropdown.querySelector('.nav-dropdown-toggle');
            if (toggle) toggle.setAttribute('aria-expanded', 'true');
        }

        function bindNavSubLinks() {
            document.querySelectorAll('.nav-sub-link').forEach(link => {
                link.addEventListener('mouseenter', () => {
                    const menu = link.closest('.nav-dropdown-menu');
                    if (menu) {
                        menu.querySelectorAll('.nav-sub-link.is-active').forEach(el => el.classList.remove('is-active'));
                    }
                    link.classList.add('is-active');
                });

                link.addEventListener('mouseleave', () => {
                    link.classList.remove('is-active');
                });
            });
        }

        document.querySelectorAll('.landing-nav-links .nav-dropdown-pin').forEach(dropdown => {
            const toggle = dropdown.querySelector('.nav-dropdown-toggle');
            if (!toggle) return;

            toggle.addEventListener('click', e => {
                e.preventDefault();
                e.stopPropagation();
                if (dropdown.classList.contains('is-open')) {
                    closeNavDropdowns();
                } else {
                    openNavDropdown(dropdown);
                }
            });
        });

        document.addEventListener('click', e => {
            const openDropdown = document.querySelector('.landing-nav-links .nav-dropdown-pin.is-open');
            if (!openDropdown) return;
            if (!openDropdown.contains(e.target)) {
                closeNavDropdowns();
            }
        });

        function openCategoryModal(categoryKey) {
            const category = categoryCatalog[categoryKey];
            if (!category || !categoryModal || !productsGrid) return;

            closeNavDropdowns();
            categoryModalTitle.textContent = category.title;

            const coverImage = category.cover || category.products[0]?.image || '';
            if (categoryGalleryIntro) {
                categoryGalleryIntro.innerHTML = `
                    <img class="category-gallery-cover" src="${escHtml(coverImage)}" alt="${escHtml(category.title)}">
                    <div class="category-gallery-intro-text">
                        <h3>${escHtml(category.title)}</h3>
                        <p>${category.products.length} produit${category.products.length > 1 ? 's' : ''} — découvrez notre sélection avec photos</p>
                    </div>
                `;
            }

            productsGrid.innerHTML = category.products.map(p => `
                <article class="product-card">
                    <img class="product-image" src="${escHtml(p.image)}" alt="${escHtml(p.name)}" loading="lazy">
                    <div class="product-info">
                        <h3 class="product-name">${escHtml(p.name)}</h3>
                        <p class="product-desc">${escHtml(p.desc)}</p>
                        <div class="product-price">${escHtml(p.price)}</div>
                        <button class="btn-add-cart" data-product="${escHtml(p.name)}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                            Ajouter au panier
                        </button>
                    </div>
                </article>
            `).join('');

            categoryModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        window.openCategoryGallery = function (event, categoryKey) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            openCategoryModal(categoryKey);
            return false;
        };

        function closeCategoryModalFn() {
            if (!categoryModal) return;
            categoryModal.classList.remove('active');
            restoreBodyScrollState();
        }

        function showCartToast(productName) {
            cartToast.textContent = `✓ « ${productName} » ajouté au panier`;
            cartToast.classList.add('show');
            clearTimeout(toastTimeout);
            toastTimeout = setTimeout(() => cartToast.classList.remove('show'), 2800);
        }

        bindNavSubLinks();

        if (closeCategoryModal) {
            closeCategoryModal.addEventListener('click', closeCategoryModalFn);
        }

        if (categoryModal) {
            categoryModal.addEventListener('click', e => {
                if (e.target === categoryModal) closeCategoryModalFn();
            });
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && categoryModal?.classList.contains('active')) {
                closeCategoryModalFn();
            }
        });

        const zoneCatalog = {
            est: {
                zone: 'Zone Est',
                id: 'COM-EST-001',
                name: 'Karim Benali',
                phone: '+212 6 12 34 56 78',
                photo: 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=200&q=80',
                manager: { name: 'Youssef El Amrani', role: 'Responsable Commercial Régional', photo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=200&q=80' },
            },
            ouest: {
                zone: 'Zone Ouest',
                id: 'COM-OUE-002',
                name: 'Sara Idrissi',
                phone: '+212 6 23 45 67 89',
                photo: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=200&q=80',
                manager: { name: 'Mohamed Tazi', role: 'Responsable Commercial Régional', photo: 'https://images.unsplash.com/photo-1519081900723-00d085d022a8?w=200&q=80' },
            },
            taza: {
                zone: 'Zone Taza, Fes',
                id: 'COM-TAZ-003',
                name: 'Hassan Alami',
                phone: '+212 6 34 56 78 90',
                photo: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&q=80',
                manager: { name: 'Fatima Bennani', role: 'Responsable Commercial Régional', photo: 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=200&q=80' },
            },
            gharb: {
                zone: 'Zone Elgharb',
                id: 'COM-GHB-004',
                name: 'Nadia Chraibi',
                phone: '+212 6 45 67 89 01',
                photo: 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=200&q=80',
                manager: { name: 'Rachid Mansouri', role: 'Responsable Commercial Régional', photo: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=200&q=80' },
            },
            casa: {
                zone: 'Zone Casablanca',
                id: 'COM-CAS-005',
                name: 'Omar Fassi',
                phone: '+212 6 56 78 90 12',
                photo: 'https://images.unsplash.com/photo-1506794778202-cad84cf45f2d?w=200&q=80',
                manager: { name: 'Jean-Luc Moreau', role: 'Directeur Commercial National', photo: 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=200&q=80' },
            },
        };

        const visitCardModal = document.getElementById('visitCardModal');
        const visitCardTitle = document.getElementById('visitCardTitle');
        const visitCardBody = document.getElementById('visitCardBody');
        const closeVisitCard = document.getElementById('closeVisitCard');

        function buildQrUrl(data) {
            return `https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=${encodeURIComponent(data)}`;
        }

        function buildVCard(commercial) {
            return `BEGIN:VCARD\nVERSION:3.0\nFN:${commercial.name}\nORG:Sweet Austria\nTITLE:Commercial\nTEL:${commercial.phone.replace(/\s/g, '')}\nNOTE:${commercial.id}\nEND:VCARD`;
        }

        function openVisitCard(zoneKey) {
            const c = zoneCatalog[zoneKey];
            if (!c) return;

            closeNavDropdowns();
            visitCardTitle.textContent = `Carte de visite — ${c.zone}`;
            const qrData = buildVCard(c);
            visitCardBody.innerHTML = `
                <div class="visit-card-main">
                    <div class="visit-photo-wrap">
                        <img class="visit-photo" src="${c.photo}" alt="${c.name}">
                    </div>
                    <div class="visit-info">
                        <div class="visit-field">
                            <div class="visit-field-label">ID Commercial</div>
                            <div class="visit-field-value id">${c.id}</div>
                        </div>
                        <div class="visit-field">
                            <div class="visit-field-label">Nom Commercial</div>
                            <div class="visit-field-value">${c.name}</div>
                        </div>
                        <div class="visit-field">
                            <div class="visit-field-label">Numéro téléphone</div>
                            <div class="visit-field-value phone">${c.phone}</div>
                        </div>
                    </div>
                    <div class="visit-qr-wrap">
                        <img class="visit-qr" src="${buildQrUrl(qrData)}" alt="QR Code ${c.name}">
                        <div class="visit-qr-label">QR Code</div>
                    </div>
                </div>
                <div class="visit-manager-section">
                    <div class="visit-manager-title">Profil responsable commercial</div>
                    <div class="visit-manager-card">
                        <img class="visit-manager-photo" src="${c.manager.photo}" alt="${c.manager.name}">
                        <div>
                            <div class="visit-manager-name">${c.manager.name}</div>
                            <div class="visit-manager-role">${c.manager.role}</div>
                        </div>
                    </div>
                </div>
            `;

            visitCardModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        window.openZoneVisitCard = function (event, zoneKey) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            openVisitCard(zoneKey);
            return false;
        };

        const partnerCatalog = {
            marjane: {
                name: 'Marjane',
                type: 'Grande distribution',
                since: '2018',
                regions: 'Casablanca, Rabat, Fès, Tanger',
                contact: 'partenariat@marjane.ma',
                image: 'https://images.unsplash.com/photo-1604719312566-8912e9227c6a?w=800&q=80',
                desc: 'Distribution nationale de nos gammes fruits secs premium dans les hypermarchés Marjane.',
            },
            decathlon: {
                name: 'Décathlon',
                type: 'Retail sport & nutrition',
                since: '2020',
                regions: 'Maroc (12 magasins)',
                contact: 'nutrition@decathlon.ma',
                image: 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=800&q=80',
                desc: 'Partenaire nutrition sportive : mixes énergétiques et graines pour les rayons bien-être.',
            },
            atacadaw: {
                name: 'Atacadaw',
                type: 'Cash & carry',
                since: '2019',
                regions: 'Nador, Oujda, Tétouan',
                contact: 'commercial@atacadaw.ma',
                image: 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=800&q=80',
                desc: 'Approvisionnement en vrac pour professionnels : hôtellerie, restauration et revendeurs.',
            },
        };

        function showPartnerProfile(partnerKey) {
            const p = partnerCatalog[partnerKey];
            if (!p || !visitCardModal) return;

            closeNavDropdowns();
            visitCardTitle.textContent = `Partenaire — ${p.name}`;
            visitCardBody.innerHTML = `
                <div class="visit-card-main">
                    <div class="visit-photo-wrap" style="width:100%;max-width:320px;">
                        <img class="visit-photo" src="${p.image}" alt="${p.name}" style="width:100%;height:180px;object-fit:cover;border-radius:12px;">
                    </div>
                    <div class="visit-info" style="flex:1;">
                        <div class="visit-field">
                            <div class="visit-field-label">Type de partenariat</div>
                            <div class="visit-field-value">${p.type}</div>
                        </div>
                        <div class="visit-field">
                            <div class="visit-field-label">Depuis</div>
                            <div class="visit-field-value">${p.since}</div>
                        </div>
                        <div class="visit-field">
                            <div class="visit-field-label">Zones couvertes</div>
                            <div class="visit-field-value">${p.regions}</div>
                        </div>
                        <div class="visit-field">
                            <div class="visit-field-label">Contact</div>
                            <div class="visit-field-value phone">${p.contact}</div>
                        </div>
                    </div>
                </div>
                <div class="visit-manager-section">
                    <div class="visit-manager-title">À propos du partenariat</div>
                    <p style="margin:0;color:#4a4a48;font-size:14px;line-height:1.6;">${p.desc}</p>
                </div>
            `;

            visitCardModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        window.openPartnerProfile = function (event, partnerKey) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            showPartnerProfile(partnerKey);
            return false;
        };

        function closeVisitCardFn() {
            visitCardModal.classList.remove('active');
            restoreBodyScrollState();
        }

        closeVisitCard.addEventListener('click', closeVisitCardFn);

        visitCardModal.addEventListener('click', e => {
            if (e.target === visitCardModal) closeVisitCardFn();
        });

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeCategoryModalFn();
                closeVisitCardFn();
            }
        });

        productsGrid.addEventListener('click', e => {
            const btn = e.target.closest('.btn-add-cart');
            if (btn) showCartToast(btn.dataset.product);
        });

        const chartDefaults = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        boxWidth: 12,
                        boxHeight: 12,
                        padding: 16,
                        font: { family: 'Inter', size: 11 },
                        color: '#6B6B68',
                    },
                },
            },
        };

        const axisStyle = {
            grid: { color: '#F0EEEA' },
            border: { display: false },
            ticks: { font: { family: 'Inter', size: 10 }, color: '#9A9A97' },
        };

        const months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];

        const barValueLabels = {
            id: 'barValueLabels',
            afterDatasetsDraw(chart) {
                if (chart.canvas.id !== 'salesChart') return;
                const { ctx, chartArea } = chart;
                if (!chartArea) return;

                chart.data.datasets.forEach((dataset, datasetIndex) => {
                    const meta = chart.getDatasetMeta(datasetIndex);
                    meta.data.forEach((bar, index) => {
                        const value = dataset.data[index];
                        if (value == null) return;

                        const label = new Intl.NumberFormat('fr-FR').format(value);
                        const { x, y, base } = bar.getProps(['x', 'y', 'base'], true);
                        const barHeight = Math.abs(base - y);
                        const midY = y + (base - y) / 2;

                        ctx.save();
                        ctx.font = '600 9px Inter, sans-serif';
                        ctx.fillStyle = '#ffffff';
                        ctx.textAlign = 'center';
                        ctx.textBaseline = 'middle';

                        if (barHeight >= 22) {
                            ctx.fillText(label, x, midY);
                        } else {
                            ctx.fillStyle = '#1C1C1A';
                            ctx.textBaseline = 'bottom';
                            ctx.fillText(label, x, y - 4);
                        }
                        ctx.restore();
                    });
                });
            },
        };

        Chart.register(barValueLabels);

        new Chart(document.getElementById('salesChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Ventes (MAD)',
                        data: [280000, 310000, 350000, 320000, 380000, 420000, 390000, 410000, 370000, 400000, 430000, 450000],
                        backgroundColor: 'rgba(0, 66, 54, 0.85)',
                        borderColor: '#004236',
                        borderWidth: 1,
                        borderRadius: 6,
                    },
                    {
                        label: 'Charges (MAD)',
                        data: [128000, 130000, 129000, 131000, 128000, 130000, 129000, 131000, 128000, 130000, 129000, 128000],
                        backgroundColor: 'rgba(184, 149, 106, 0.85)',
                        borderColor: '#B8956A',
                        borderWidth: 1,
                        borderRadius: 6,
                    },
                ],
            },
            options: {
                ...chartDefaults,
                plugins: {
                    ...chartDefaults.plugins,
                    legend: {
                        ...chartDefaults.plugins.legend,
                        display: true,
                        position: 'bottom',
                        align: 'end',
                    },
                },
                scales: {
                    x: { ...axisStyle, grid: { display: false } },
                    y: {
                        ...axisStyle,
                        ticks: {
                            ...axisStyle.ticks,
                            callback: v => (v / 1000) + 'k',
                        },
                    },
                },
            },
        });

        new Chart(document.getElementById('citiesChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Casablanca', 'Fès', 'Rabat', 'Nador', 'Tanger', 'Marrakech'],
                datasets: [{
                    label: 'Commandes',
                    data: [842, 615, 534, 478, 392, 356],
                    backgroundColor: [
                        '#004236', '#2B3E92', '#E65C19', '#BF571B', '#2D8A4E', '#7C3AED',
                    ],
                    borderRadius: 6,
                }],
            },
            options: {
                indexAxis: 'y',
                ...chartDefaults,
                plugins: { ...chartDefaults.plugins, legend: { display: false } },
                scales: {
                    x: { ...axisStyle },
                    y: { ...axisStyle, grid: { display: false } },
                },
            },
        });

        new Chart(document.getElementById('productsChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: [
                    'Mix Luxe 500g', 'Dattes Medjool', 'Amandes Premium', 'Coffret Prestige',
                    'Orangettes Choco', 'Graines Chia', 'Figues Izmir', 'Noix Pécan',
                ],
                datasets: [
                    {
                        label: 'Plus vendus / demandés',
                        data: [1240, 980, 870, 720, null, null, null, null],
                        backgroundColor: '#004236',
                        borderRadius: 4,
                    },
                    {
                        label: 'Moins vendus',
                        data: [null, null, null, null, 58, 45, 62, 38],
                        backgroundColor: '#E65C19',
                        borderRadius: 4,
                    },
                ],
            },
            options: {
                indexAxis: 'y',
                ...chartDefaults,
                scales: {
                    x: { ...axisStyle },
                    y: { ...axisStyle, grid: { display: false } },
                },
            },
        });

        /* ===== Interface d'accueil / Connexion ===== */
        (function initLanding() {
            const landing = document.getElementById('landingScreen');
            const loginPanel = document.getElementById('loginPanel');
            const openLoginBtn = document.getElementById('openLoginBtn');
            const closeLoginBtn = document.getElementById('closeLoginBtn');
            const loginForm = document.getElementById('loginForm');
            const eyeBtn = document.getElementById('loginEyeBtn');
            const userInput = document.getElementById('loginUser');
            const passInput = document.getElementById('loginPass');
            const loginError = document.getElementById('loginError');
            const goToLandingBtn = document.getElementById('goToLandingBtn');

            // Identifiants Super Admin
            const SUPER_ADMIN_1 = { user: 'superadmin@sweetaustria.com', pass: 'mot de passe' };
            const SUPER_ADMIN_OLD = { user: 'superadmin', pass: 'SweetAustria@2026' };

            function openLoginPanel() {
                if (!loginPanel) return;
                loginPanel.classList.add('is-open');
                if (userInput) {
                    setTimeout(() => userInput.focus(), 200);
                }
            }

            function closeLoginPanel() {
                if (!loginPanel) return;
                loginPanel.classList.remove('is-open');
                if (loginError) loginError.hidden = true;
            }

            function showLanding() {
                if (!landing) return;
                landing.classList.remove('is-hidden');
                closeLoginPanel();
                document.body.style.overflow = 'hidden';
            }

            function hideLanding() {
                if (!landing) return;
                landing.classList.add('is-hidden');
                closeLoginPanel();
                document.body.style.overflow = '';
            }

            if (openLoginBtn) {
                openLoginBtn.addEventListener('click', openLoginPanel);
            }

            if (closeLoginBtn) {
                closeLoginBtn.addEventListener('click', closeLoginPanel);
            }

            if (eyeBtn && passInput) {
                eyeBtn.addEventListener('click', function () {
                    const isPwd = passInput.type === 'password';
                    passInput.type = isPwd ? 'text' : 'password';
                    eyeBtn.setAttribute('aria-label', isPwd ? 'Masquer le mot de passe' : 'Afficher le mot de passe');
                });
            }

            if (loginForm) {
                loginForm.addEventListener('submit', function (e) {
                    e.preventDefault();
                    const u = (userInput?.value || '').trim();
                    const p = (passInput?.value || '').trim();
                    const okNew = (u === SUPER_ADMIN_1.user && p === SUPER_ADMIN_1.pass);
                    const okOld = (u === SUPER_ADMIN_OLD.user && p === SUPER_ADMIN_OLD.pass);
                    if (okNew || okOld) {
                        if (loginError) loginError.hidden = true;
                        hideLanding();
                    } else {
                        if (loginError) loginError.hidden = false;
                        if (passInput) {
                            passInput.value = '';
                            passInput.focus();
                        }
                    }
                });

                [userInput, passInput].forEach(function (el) {
                    el && el.addEventListener('input', function () {
                        if (loginError) loginError.hidden = true;
                    });
                });
            }

            if (goToLandingBtn) {
                goToLandingBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    showLanding();
                });
            }

            // Bloque le scroll de l'app tant que l'accueil est affiché
            if (landing && !landing.classList.contains('is-hidden')) {
                document.body.style.overflow = 'hidden';
            }
        })();
    </script>
</body>
</html>
