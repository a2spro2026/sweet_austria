<!DOCTYPE html>



<html lang="fr">



<head>



    <meta charset="utf-8">



    <meta name="viewport" content="width=device-width, initial-scale=1">



    <meta name="csrf-token" content="{{ csrf_token() }}">



    <title>Sweet Austria ? Tableau de bord ex?cutif</title>



    <link rel="preconnect" href="https://fonts.googleapis.com">



    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>



    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">



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



            --teal-card: #0D5C63;



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



        /* ?? Logo header (align? avec la barre hero) ?? */



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



        /* ?? Sidebar ?? */



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



            padding-left: 18px;



            font-size: 11.5px;



        }



        .nav-group.open > .nav-submenu {



            max-height: 980px;



            padding: 2px 0 8px 8px;



        }



        .nav-subgroup.open > .nav-subsubmenu {



            max-height: 420px;



            padding: 2px 0 6px 12px;



        }



        .nav-subitem {



            display: flex;



            align-items: center;



            gap: 10px;



            padding: 8px 12px 8px 10px;



            font-size: 12px;



            font-weight: 500;



            color: #4A4845;



            text-decoration: none;



            border-radius: 8px;



            margin-bottom: 2px;



            letter-spacing: 0.02em;



            transition: background 0.15s, color 0.15s, transform 0.15s, box-shadow 0.15s;



            position: relative;



            border: 1px solid transparent;



        }



        .nav-subitem .nav-subicon {



            width: 28px;



            height: 28px;



            flex-shrink: 0;



            display: inline-flex;



            align-items: center;



            justify-content: center;



            border-radius: 8px;



            background: linear-gradient(145deg, rgba(0, 51, 38, 0.08) 0%, rgba(200, 149, 108, 0.14) 100%);



            color: var(--green-dark);



            border: 1px solid rgba(200, 149, 108, 0.22);



            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.65);



            transition: background 0.15s, color 0.15s, border-color 0.15s, transform 0.15s;



        }



        .nav-subitem .nav-subicon svg {



            width: 14px;



            height: 14px;



            stroke: currentColor;



            fill: none;



            stroke-width: 2;



            stroke-linecap: round;



            stroke-linejoin: round;



        }



        .nav-subitem:hover {



            background: rgba(255, 255, 255, 0.78);



            color: var(--green-dark);



            border-color: rgba(200, 149, 108, 0.2);



            transform: translateX(2px);



        }



        .nav-subitem:hover .nav-subicon {



            background: linear-gradient(145deg, rgba(0, 51, 38, 0.14) 0%, rgba(230, 92, 25, 0.16) 100%);



            color: var(--orange-accent);



            border-color: rgba(230, 92, 25, 0.28);



            transform: scale(1.05);



        }



        .nav-subitem.active {



            background: rgba(255, 240, 232, 0.9);



            color: var(--green-dark);



            font-weight: 600;



            border-color: rgba(230, 92, 25, 0.25);



        }



        .nav-subitem.active .nav-subicon {



            background: linear-gradient(145deg, var(--green-dark) 0%, #004d3a 100%);



            color: #fff;



            border-color: rgba(0, 51, 38, 0.35);



            box-shadow: 0 2px 8px rgba(0, 51, 38, 0.2);



        }



        .nav-subgroup-toggle {



            display: flex;



            align-items: center;



            justify-content: space-between;



            gap: 10px;



            width: 100%;



            padding: 8px 12px 8px 10px;



            font-size: 12px;



            font-weight: 600;



            color: #4A4845;



            background: transparent;



            border: none;



            border-radius: 8px;



            cursor: pointer;



            text-align: left;



            letter-spacing: 0.02em;



            transition: background 0.15s, color 0.15s;



        }



        .nav-subgroup-toggle-main {



            display: inline-flex;



            align-items: center;



            gap: 10px;



            min-width: 0;



        }



        .nav-subgroup-toggle .nav-subicon {



            width: 28px;



            height: 28px;



            flex-shrink: 0;



            display: inline-flex;



            align-items: center;



            justify-content: center;



            border-radius: 8px;



            background: rgba(0, 51, 38, 0.06);



            color: var(--green-dark);



        }



        .nav-subgroup-toggle .nav-subicon svg {



            width: 14px;



            height: 14px;



            stroke: currentColor;



            fill: none;



            stroke-width: 2;



            stroke-linecap: round;



            stroke-linejoin: round;



        }



        .nav-subgroup-toggle:hover {



            background: rgba(255, 255, 255, 0.5);



            color: var(--green-dark);



        }



        .nav-subgroup-toggle:hover .nav-subicon {



            background: rgba(0, 51, 38, 0.12);



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



        /* ?? Sidebar toggle ?? */



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



        /* ?? Main ?? */



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



        /* ?? Hero Header (sticky : navbar + banni?re restent visibles au scroll) ?? */



        .hero-header {



            position: sticky;



            top: 0;



            z-index: 90;



            flex-shrink: 0;



            display: flex;



            flex-direction: column;



            height: var(--header-h);



            background: url('/images/a2s-fruits-background.png') center center / cover no-repeat;



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



        /* ?? Carte de visite Commercial ?? */



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



        /* ?? Modal Catégories ?? */



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



        /* ?? Dashboard Content ?? */



        .dashboard-content {



            padding: 0 32px 0;



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



        /* ?? Barre de saisie ?? */



        .saisie-panel {



            animation: saisieFadeIn 0.3s ease;



            padding-top: 16px;



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



        /* Bon d'achat : en-t?te fixe, champs d?filants */



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



            grid-template-columns: 72px 132px minmax(120px, 1fr) 100px 88px 68px 130px 80px 96px 44px;



            gap: 6px 8px;



            align-items: end;



            width: 100%;



            min-width: 960px;



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



        #achatsView .achats-add-article-wrap {



            display: flex;



            align-items: flex-end;



            justify-content: center;



            padding-bottom: 1px;



        }



        #achatsView .btn-add-article {



            display: inline-flex;



            align-items: center;



            justify-content: center;



            width: 36px;



            height: 36px;



            border: none;



            border-radius: 10px;



            background: var(--green-dark);



            color: #fff;



            cursor: pointer;



            box-shadow: 0 2px 8px rgba(0, 51, 38, 0.22);



            transition: background 0.15s, transform 0.15s, box-shadow 0.15s;



        }



        #achatsView .btn-add-article:hover {



            background: #004d3a;



            transform: translateY(-1px);



            box-shadow: 0 4px 12px rgba(0, 51, 38, 0.28);



        }



        #achatsView .btn-add-article svg {



            width: 18px;



            height: 18px;



            stroke: currentColor;



            fill: none;



            pointer-events: none;



        }



        #achatsView .achats-articles-summary {



            display: flex;



            align-items: center;



            justify-content: space-between;



            gap: 16px;



            margin-top: 10px;



            padding: 12px 16px;



            border-radius: 10px;



            border: 1px solid var(--border);



            background: linear-gradient(135deg, rgba(0, 51, 38, 0.06) 0%, rgba(200, 149, 108, 0.08) 100%);



        }



        #achatsView .achats-articles-count {



            font-size: 12px;



            font-weight: 600;



            color: var(--text-dark);



        }



        #achatsView .achats-articles-count strong {



            color: var(--green-dark);



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



            background: linear-gradient(135deg, var(--green-dark) 0%, #004d3a 100%);



            color: white;



            font-size: 10px;



            font-weight: 700;



            letter-spacing: 0.06em;



            text-transform: uppercase;



            white-space: nowrap;



            border-bottom: 2px solid rgba(200, 149, 108, 0.55);



            text-shadow: 0 1px 0 rgba(0, 0, 0, 0.15);



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



            overflow-x: hidden;



        }



        #fournisseurPrintArea .fournisseur-table thead th,



        #commandesPrintArea .achats-commandes-table thead th,



        #produitPrintArea .produits-table thead th {



            position: sticky;



            top: var(--header-h);



            z-index: 6;



        }



        .achats-lines-table {



            width: 100%;



            border-collapse: collapse;



            font-size: 12px;



            min-width: 900px;



        }



        .achats-lines-table thead th {



            background: linear-gradient(135deg, var(--green-dark) 0%, #004d3a 100%);



            color: white;



            padding: 10px 12px;



            font-size: 10px;



            font-weight: 700;



            letter-spacing: 0.06em;



            text-transform: uppercase;



            white-space: nowrap;



            border-bottom: 2px solid rgba(200, 149, 108, 0.55);



            text-shadow: 0 1px 0 rgba(0, 0, 0, 0.15);



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



            background: linear-gradient(135deg, var(--green-dark) 0%, #004d3a 100%);



            color: white;



            padding: 12px 14px;



            font-size: 11px;



            font-weight: 700;



            letter-spacing: 0.06em;



            text-transform: uppercase;



            white-space: nowrap;



            border-bottom: 2px solid rgba(200, 149, 108, 0.55);



            text-shadow: 0 1px 0 rgba(0, 0, 0, 0.15);



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



        /* Alignement centr? : en-t?tes et donn?es sur la m?me colonne */



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



            background: linear-gradient(135deg, var(--green-dark) 0%, #004d3a 100%);



            color: white;



            padding: 12px 10px;



            font-size: 10px;



            font-weight: 700;



            letter-spacing: 0.06em;



            text-transform: uppercase;



            white-space: nowrap;



            border-bottom: 2px solid rgba(200, 149, 108, 0.55);



            text-shadow: 0 1px 0 rgba(0, 0, 0, 0.15);



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



            width: 32px;



            height: 32px;



            padding: 0;



            border-radius: 8px;



            border: 1px solid transparent;



            cursor: pointer;



            transition: background 0.15s, transform 0.15s, box-shadow 0.15s;



            flex-shrink: 0;



        }



        .btn-icon-row svg {



            width: 16px;



            height: 16px;



            stroke: currentColor;



            fill: none;



            pointer-events: none;



        }



        .btn-icon-edit {



            background: #004236;



            color: #fff;



            border-color: #004236;



        }



        .btn-icon-edit:hover {



            background: #003326;



            transform: translateY(-1px);



            box-shadow: 0 2px 8px rgba(0, 51, 38, 0.25);



        }



        .btn-icon-view {



            background: #2B3E92;



            color: #fff;



            border-color: #2B3E92;



        }



        .btn-icon-view:hover {



            background: #1f2e6e;



            transform: translateY(-1px);



            box-shadow: 0 2px 8px rgba(43, 62, 146, 0.3);



        }



        .btn-icon-pdf {



            background: #E65C19;



            color: #fff;



            border-color: #E65C19;



        }



        .btn-icon-pdf:hover {



            background: #c44d14;



            transform: translateY(-1px);



            box-shadow: 0 2px 8px rgba(230, 92, 25, 0.3);



        }



        .btn-icon-delete {



            background: #9B2C2C;



            color: #fff;



            border-color: #9B2C2C;



        }



        .btn-icon-delete:hover {



            background: #7f2424;



            transform: translateY(-1px);



            box-shadow: 0 2px 8px rgba(155, 44, 44, 0.3);



        }



        #fournisseursTable .col-actions {



            position: sticky;



            right: 0;



            background: #fff;



            z-index: 2;



            min-width: 160px;



            box-shadow: -6px 0 10px -8px rgba(0, 0, 0, 0.18);



        }



        #fournisseursTable thead th.col-actions {



            background: linear-gradient(135deg, var(--green-dark) 0%, #004d3a 100%);



            z-index: 7;



        }



        /* Tous les tableaux restent fixes dans leur conteneur, sans d?filement interne */



        .fournisseur-table-wrap,



        #fournisseurPrintArea .fournisseur-table-wrap,



        #commandesPrintAreaAchats .fournisseur-table-wrap,



        #produitPrintArea .fournisseur-table-wrap {



            width: 100%;



            max-height: none;



            overflow: visible;



        }



        .fournisseur-table,



        .achats-commandes-table,



        .produits-table,



        .stock-table,



        .achats-lines-table {



            width: 100%;



            min-width: 0;



            table-layout: fixed;



        }



        .fournisseur-table th,



        .fournisseur-table td,



        .achats-commandes-table th,



        .achats-commandes-table td,



        .produits-table th,



        .produits-table td,



        .stock-table th,



        .stock-table td,



        .achats-lines-table th,



        .achats-lines-table td {



            white-space: normal;



            overflow-wrap: anywhere;



            word-break: normal;



        }



        #fournisseurPrintArea .fournisseur-table thead th,



        #commandesPrintAreaAchats .achats-commandes-table thead th,



        #produitPrintArea .produits-table thead th {



            position: static;



        }



        #fournisseursTable .col-actions {



            position: static;



            width: 150px;



            min-width: 0;



            box-shadow: none;



        }



        @media (max-width: 1200px) {



            .fournisseur-table,



            .achats-commandes-table,



            .produits-table {



                font-size: 10px;



            }



            .fournisseur-table thead th,



            .fournisseur-table tbody td,



            .achats-commandes-table thead th,



            .achats-commandes-table tbody td,



            .produits-table thead th,



            .produits-table tbody td {



                padding-left: 5px;



                padding-right: 5px;



            }



            #fournisseursTable .col-actions {



                width: 124px;



            }



            #fournisseursTable .btn-icon-row {



                width: 26px;



                height: 26px;



            }



        }



        /* Liste fournisseurs : positions du tableau et des boutons verrouill?es */



        #fournisseurListPanel .list-toolbar {



            flex-wrap: nowrap;



            min-height: 66px;



            margin-bottom: 16px;



            box-shadow: 0 8px 14px -14px rgba(0, 51, 38, 0.4);



        }



        #fournisseurListPanel .list-toolbar-title {



            flex: 1 1 auto;



            min-width: 0;



        }



        #fournisseurListPanel .list-toolbar-actions {



            display: grid;



            grid-template-columns: repeat(3, max-content);



            flex: 0 0 auto;



            flex-wrap: nowrap;



            align-items: center;



        }



        #fournisseurListPanel .btn-list,



        #fournisseurListPanel .btn-list:hover {



            transform: none;



            white-space: nowrap;



        }



        #fournisseurPrintArea {



            width: 100%;



            position: relative;



            contain: layout;



        }



        #fournisseurPrintArea .fournisseur-table-wrap {



            width: 100%;



            overflow: visible;



        }



        #fournisseursTable {



            width: 100%;



            min-width: 0;



            table-layout: fixed;



        }



        #fournisseursTable th,



        #fournisseursTable td {



            box-sizing: border-box;



            overflow: hidden;



            text-overflow: ellipsis;



        }



        #fournisseurListPanel #fournisseursTable .col-actions {



            position: static;



            width: auto;



            min-width: 0;



            padding-left: 4px;



            padding-right: 4px;



        }



        #fournisseursTable .col-actions-wrap {



            display: grid;



            grid-template-columns: repeat(4, 28px);



            justify-content: center;



            align-items: center;



            gap: 4px;



            width: 124px;



            min-width: 124px;



            margin: 0 auto;



        }



        #fournisseursTable .btn-icon-row,



        #fournisseursTable .btn-icon-row:hover {



            width: 28px;



            height: 28px;



            flex: 0 0 28px;



            transform: none;



        }



        @media (max-width: 900px) {



            #fournisseurListPanel .list-toolbar {



                align-items: flex-start;



            }



            #fournisseurListPanel .list-toolbar-actions {



                grid-template-columns: repeat(3, 36px);



            }



            #fournisseurListPanel .btn-list {



                width: 36px;



                height: 36px;



                padding: 0;



                justify-content: center;



                font-size: 0;



            }



            #fournisseurListPanel .btn-list svg {



                width: 17px;



                height: 17px;



            }



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



        /* Fiche Produit ? barre compacte + photo */



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



            grid-template-columns:



                minmax(120px, 1fr)



                minmax(110px, 0.9fr)



                minmax(110px, 0.7fr)



                minmax(110px, 0.7fr);



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



        #ficheProduitView .btn-photo,



        #ficheSocieteView .btn-photo,



        #tresorerieMaterielsView .btn-photo {



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



        #ficheProduitView .btn-photo:hover,



        #ficheSocieteView .btn-photo:hover,



        #tresorerieMaterielsView .btn-photo:hover {



            background: var(--table-header);



            border-color: rgba(0, 51, 38, 0.25);



        }



        #ficheProduitView .btn-photo-danger,



        #ficheSocieteView .btn-photo-danger,



        #tresorerieMaterielsView .btn-photo-danger {



            color: #9B2C2C;



            border-color: rgba(155, 44, 44, 0.25);



        }



        #ficheProduitView .btn-photo svg,



        #ficheSocieteView .btn-photo svg,



        #tresorerieMaterielsView .btn-photo svg {



            width: 12px;



            height: 12px;



            flex-shrink: 0;



        }



        #ficheSocieteView .produit-photo-actions,



        #tresorerieMaterielsView .produit-photo-actions {



            display: flex;



            flex-direction: column;



            gap: 6px;



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



        /* ?? Fiche Société ?? */



        #ficheSocieteView .societe-form-layout {



            display: grid;



            grid-template-columns: minmax(0, 1fr) 170px;



            gap: 14px 18px;



            align-items: start;



        }



        #ficheSocieteView .so-inline-row {



            display: grid;



            gap: 8px 10px;



            align-items: end;



            margin-bottom: 8px;



        }



        #ficheSocieteView .so-inline-row-1 {



            grid-template-columns: minmax(0, 1.2fr) minmax(0, 1.2fr) minmax(0, 0.9fr);



        }



        #ficheSocieteView .so-inline-row-2 {



            grid-template-columns: minmax(0, 0.8fr) minmax(0, 1.6fr) minmax(0, 0.8fr) minmax(0, 1fr);



        }



        #ficheSocieteView .so-inline-row-3 {



            grid-template-columns: repeat(4, minmax(0, 1fr));



        }



        #ficheSocieteView .so-inline-row-4 {



            grid-template-columns: minmax(0, 0.8fr) minmax(0, 1.4fr);



        }



        #ficheSocieteView .societe-photo-panel {



            display: flex;



            flex-direction: column;



            gap: 8px;



        }



        #ficheSocieteView .societe-photo-panel > label {



            font-size: 9px;



            font-weight: 600;



            letter-spacing: 0.04em;



            text-transform: uppercase;



            color: var(--green-dark);



        }



        #ficheSocieteView .societe-photo-preview {



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



        #ficheSocieteView .societe-photo-preview img {



            width: 100%;



            height: 100%;



            object-fit: cover;



            display: block;



        }



        
        #ficheSocieteView .so-habillage-block {
            margin-top: 14px;
            grid-column: 1 / -1;
        }
        #ficheSocieteView .saisie-form > .so-habillage-block {
            margin: 12px 0 4px;
            width: 100%;
        }
        #ficheSocieteView .so-habillage-toggle {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        #ficheSocieteView .so-habillage-panel {
            margin-top: 10px;
            padding: 12px;
            border: 1px solid rgba(0, 77, 58, 0.18);
            border-radius: 8px;
            background: rgba(0, 77, 58, 0.04);
            display: grid;
            gap: 10px;
        }
        #ficheSocieteView .so-habillage-panel.hidden {
            display: none;
        }
        #ficheSocieteView .so-habillage-row {
            display: grid;
            gap: 6px;
        }
        #ficheSocieteView .so-habillage-row label {
            font-size: 12px;
            font-weight: 600;
            color: #004d3a;
        }
        #ficheSocieteView .so-habillage-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }
        #ficheSocieteView .so-habillage-status {
            font-size: 12px;
            color: #5a6b63;
        }
        #ficheSocieteView .so-habillage-status.is-set {
            color: #004d3a;
            font-weight: 600;
        }
        #landingAdIframe.hidden,
        #landingAdVideo.hidden {
            display: none !important;
        }

#ficheSocieteView .societe-photo-placeholder {



            font-size: 10px;



            color: var(--text-muted);



            text-align: center;



            padding: 8px;



        }



        #ficheSocieteView .societe-consult-card {



            background: white;



            border: 1px solid var(--border);



            border-radius: 12px;



            padding: 20px 22px;



            overflow: auto;



            max-height: 100%;



        }



        #ficheSocieteView .societe-consult-header {



            display: flex;



            align-items: center;



            gap: 18px;



            margin-bottom: 18px;



            padding-bottom: 16px;



            border-bottom: 1px solid var(--border);



        }



        #ficheSocieteView .societe-consult-logo {



            width: 88px;



            height: 88px;



            border-radius: 10px;



            border: 1px solid var(--border);



            object-fit: cover;



            background: #F7F5F0;



            flex-shrink: 0;



        }



        #ficheSocieteView .societe-consult-logo.placeholder {



            display: flex;



            align-items: center;



            justify-content: center;



            color: var(--text-muted);



            font-size: 11px;



            text-align: center;



            padding: 8px;



        }



        #ficheSocieteView .societe-consult-title {



            font-family: 'Playfair Display', serif;



            font-size: 22px;



            font-weight: 700;



            color: var(--green-dark);



            margin: 0 0 4px;



        }



        #ficheSocieteView .societe-consult-sub {



            font-size: 13px;



            color: var(--text-muted);



        }



        #ficheSocieteView .societe-consult-grid {



            display: grid;



            grid-template-columns: repeat(3, minmax(0, 1fr));



            gap: 12px 18px;



        }



        #ficheSocieteView .societe-consult-item {



            min-width: 0;



        }



        #ficheSocieteView .societe-consult-item.full {



            grid-column: 1 / -1;



        }



        #ficheSocieteView .societe-consult-label {



            display: block;



            font-size: 10px;



            font-weight: 700;



            letter-spacing: 0.06em;



            text-transform: uppercase;



            color: var(--text-muted);



            margin-bottom: 4px;



        }



        #ficheSocieteView .societe-consult-value {



            font-size: 13px;



            color: var(--text-dark);



            word-break: break-word;



        }



        @media (max-width: 900px) {



            #ficheSocieteView .societe-form-layout {



                grid-template-columns: 1fr;



            }



            #ficheSocieteView .so-inline-row-1,



            #ficheSocieteView .so-inline-row-2,



            #ficheSocieteView .so-inline-row-3,



            #ficheSocieteView .so-inline-row-4,



            #ficheSocieteView .societe-consult-grid {



                grid-template-columns: 1fr 1fr;



            }



        }



        /* ?? Utilisateur ?? */



        #utilisateurView .user-form-grid {



            display: grid;



            grid-template-columns: 110px 100px minmax(0, 1.4fr) minmax(0, 1fr);



            gap: 8px 10px;



            align-items: end;



            margin-bottom: 10px;



        }



        #utilisateurView .user-form-grid-2 {



            display: grid;



            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);



            gap: 8px 10px;



            align-items: end;



            margin-bottom: 12px;



        }



        #utilisateurView .user-auth-box {



            border: 1px solid var(--border);



            border-radius: 10px;



            padding: 12px;



            background: #fff;



            max-height: 38vh;



            overflow: auto;



        }



        #utilisateurView .user-auth-title {



            display: flex;



            align-items: center;



            justify-content: space-between;



            gap: 10px;



            margin-bottom: 10px;



        }



        #utilisateurView .user-auth-title h3 {



            margin: 0;



            font-size: 13px;



            color: var(--green-dark);



        }



        #utilisateurView .user-auth-section {



            border: 1px solid var(--border);



            border-radius: 8px;



            padding: 8px 10px;



            margin-bottom: 8px;



            background: #FBFBF8;



        }



        #utilisateurView .user-auth-section:last-child {



            margin-bottom: 0;



        }



        #utilisateurView .user-auth-section-head {



            display: flex;



            align-items: center;



            gap: 8px;



            margin-bottom: 6px;



            font-size: 12px;



            font-weight: 700;



            color: var(--green-dark);



        }



        #utilisateurView .user-auth-items {



            display: grid;



            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));



            gap: 4px 10px;



            padding-left: 22px;



        }



        #utilisateurView .user-auth-item {



            display: flex;



            align-items: center;



            gap: 6px;



            font-size: 11px;



            color: var(--text-dark);



        }



        #utilisateurView .user-auth-item input,



        #utilisateurView .user-auth-section-head input {



            width: 14px;



            height: 14px;



            accent-color: var(--green-dark);



        }



        #utilisateurView .user-auth-chips {



            display: flex;



            flex-wrap: wrap;



            gap: 4px;



            justify-content: center;



        }



        #utilisateurView .user-auth-chip {



            display: inline-block;



            padding: 2px 7px;



            border-radius: 999px;



            background: rgba(0, 51, 38, 0.08);



            color: var(--green-dark);



            font-size: 10px;



            white-space: nowrap;



        }



        #utilisateurView .pwd-mask {



            letter-spacing: 0.12em;



            font-family: monospace;



        }



        @media (max-width: 900px) {



            #utilisateurView .user-form-grid,



            #utilisateurView .user-form-grid-2 {



                grid-template-columns: 1fr 1fr;



            }



        }



        /* ?? Trésorerie Matériels ?? */



        #tresorerieMaterielsView .tm-form-layout {



            display: grid;



            grid-template-columns: minmax(0, 1fr) 160px;



            gap: 14px 16px;



            align-items: start;



        }



        #tresorerieMaterielsView .tm-inline-row {



            display: grid;



            gap: 8px 10px;



            align-items: end;



            margin-bottom: 8px;



        }



        #tresorerieMaterielsView .tm-inline-row-1 {



            grid-template-columns: 120px 110px minmax(0, 1.4fr);



        }



        #tresorerieMaterielsView .tm-inline-row-2 {



            grid-template-columns: minmax(0, 1.2fr) minmax(0, 0.9fr) minmax(0, 0.9fr) minmax(0, 1fr);



        }



        #tresorerieMaterielsView .tm-photo-panel {



            display: flex;



            flex-direction: column;



            gap: 8px;



        }



        #tresorerieMaterielsView .tm-photo-panel > label {



            font-size: 9px;



            font-weight: 600;



            letter-spacing: 0.04em;



            text-transform: uppercase;



            color: var(--green-dark);



        }



        #tresorerieMaterielsView .tm-photo-preview {



            width: 100%;



            aspect-ratio: 1;



            border: 2px dashed rgba(0, 51, 38, 0.2);



            border-radius: 8px;



            overflow: hidden;



            display: flex;



            align-items: center;



            justify-content: center;



            background: linear-gradient(145deg, #FDFCFA 0%, #F3F1EA 100%);



        }



        #tresorerieMaterielsView .tm-photo-preview img {



            width: 100%;



            height: 100%;



            object-fit: cover;



            display: block;



        }



        #tresorerieMaterielsView .tm-photo-placeholder {



            font-size: 10px;



            color: var(--text-muted);



            text-align: center;



            padding: 8px;



        }



        #tresorerieMaterielsView .materiels-table img.tm-thumb {



            width: 42px;



            height: 42px;



            object-fit: cover;



            border-radius: 6px;



            border: 1px solid var(--border);



            display: block;



            margin: 0 auto;



        }



        #tresorerieMaterielsView .materiels-table .tm-thumb-empty {



            width: 42px;



            height: 42px;



            border-radius: 6px;



            border: 1px dashed var(--border);



            display: flex;



            align-items: center;



            justify-content: center;



            margin: 0 auto;



            color: var(--text-muted);



            font-size: 9px;



        }



        @media (max-width: 900px) {



            #tresorerieMaterielsView .tm-form-layout {



                grid-template-columns: 1fr;



            }



            #tresorerieMaterielsView .tm-inline-row-1,



            #tresorerieMaterielsView .tm-inline-row-2 {



                grid-template-columns: 1fr 1fr;



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



        .solde-negative {



            color: #DC2626 !important;



            font-weight: 700;



        }



        .solde-zero { color: var(--text-muted); }



        /* Soldes ? 5 derniers bons de ventes */



        .table-card .stock-table td.solde-negative {



            color: #DC2626 !important;



            font-weight: 700;



        }



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



            #produitPrintArea *,



            #societePrintArea,



            #societePrintArea *,



            #materielsPrintArea,



            #materielsPrintArea * { visibility: visible; }



            #fournisseurPrintArea,



            #commandesPrintArea,



            #produitPrintArea,



            #societePrintArea,



            #materielsPrintArea {



                position: absolute;



                left: 0;



                top: 0;



                width: 100%;



                padding: 20px;



            }



            .list-toolbar,



            .list-toolbar-actions,



            .no-print-cmd,



            .no-print-produit,



            .no-print-societe,



            .no-print-materiels { display: none !important; }



            #fournisseurPrintArea .fournisseur-table-wrap,



            #commandesPrintArea .fournisseur-table-wrap,



            #produitPrintArea .fournisseur-table-wrap,



            #materielsPrintArea .fournisseur-table-wrap {



                max-height: none !important;



                overflow: visible !important;



            }



        }



        /* Impression d?di?e : Bon d'achat (consultation) */



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



        /* ?? KPI Cards (coll?es sous le hero, bloqu?es au scroll) ?? */



        .kpi-grid {



            position: sticky;



            top: var(--header-h);



            z-index: 80;



            background: var(--main-bg);



            display: grid;



            grid-template-columns: repeat(5, 1fr);



            gap: 12px;



            margin: 0 -32px 16px;



            padding: 10px 32px 12px;



            box-shadow: 0 10px 20px -12px rgba(0, 51, 38, 0.18);



        }



        .kpi-card {



            border-radius: 12px;



            padding: 18px 16px;



            color: white;



            position: relative;



            overflow: hidden;



        }



        .kpi-card.green  { background: var(--green-card); }



        .kpi-card.blue   { background: var(--blue-card); }



        .kpi-card.orange { background: var(--orange-card); }



        .kpi-card.brown  { background: var(--brown-card); }



        .kpi-card.teal   { background: var(--teal-card); }



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



            font-size: 22px;



            font-weight: 700;



            letter-spacing: -0.02em;



        }



        /* ?? Stock Tables ?? */



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



            background: linear-gradient(135deg, var(--green-dark) 0%, #004d3a 100%);



            color: white;



            padding: 11px 16px;



            font-size: 11px;



            font-weight: 700;



            letter-spacing: 0.06em;



            text-transform: uppercase;



            white-space: nowrap;



            border-bottom: 2px solid rgba(200, 149, 108, 0.55);



            text-shadow: 0 1px 0 rgba(0, 0, 0, 0.15);



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



        /* ?? Footer ?? */



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



            background: url('{{ asset('images/a2s-fruits-background.png') }}') center/cover no-repeat;



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
            padding: 14px 28px;
            flex-wrap: wrap;
            backdrop-filter: blur(14px) saturate(1.15);
            -webkit-backdrop-filter: blur(14px) saturate(1.15);
            background:
                linear-gradient(180deg, rgba(0, 32, 24, 0.72) 0%, rgba(0, 22, 16, 0.55) 100%);
            border-bottom: 1px solid rgba(233, 197, 119, 0.32);
            box-shadow:
                0 10px 28px rgba(0, 0, 0, 0.28),
                inset 0 1px 0 rgba(255, 248, 230, 0.08);
        }

        .landing-header::after {
            content: '';
            position: absolute;
            left: 8%;
            right: 8%;
            bottom: -1px;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(233, 197, 119, 0.75), transparent);
            pointer-events: none;
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
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            margin: 0 auto;
            gap: 8px;
            padding: 6px;
            border-radius: 16px;
            background:
                linear-gradient(145deg, rgba(255, 248, 230, 0.08) 0%, rgba(0, 40, 30, 0.35) 100%);
            border: 1px solid rgba(233, 197, 119, 0.28);
            box-shadow:
                inset 0 1px 0 rgba(255, 255, 255, 0.08),
                0 8px 24px rgba(0, 0, 0, 0.22);
        }

        .landing-nav-links > li {
            list-style: none;
            position: relative;
        }

        .landing-nav-links > li > a {
            position: relative;
            overflow: hidden;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: #f7efdf;
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.04em;
            padding: 10px 16px;
            border-radius: 12px;
            background: linear-gradient(160deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.03) 100%);
            border: 1px solid rgba(233, 197, 119, 0.28);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.18);
            text-decoration: none;
            transition: background 0.25s, border-color 0.25s, color 0.25s, transform 0.2s, box-shadow 0.25s;
        }

        .landing-nav-links > li > a svg {
            width: 12px;
            height: 12px;
            opacity: 0.85;
            transition: transform 0.25s ease, opacity 0.25s;
        }

        .landing-nav-links > li > a::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(110deg, transparent 30%, rgba(255, 255, 255, 0.28) 48%, transparent 66%);
            transform: translateX(-120%);
            transition: transform 0.55s ease;
            pointer-events: none;
        }

        .landing-nav-links > li > a:hover::before,
        .landing-nav-links > li.nav-dropdown-pin.is-open > .nav-dropdown-toggle::before {
            transform: translateX(120%);
        }

        .landing-nav-links > li > a:hover,
        .landing-nav-links > li.nav-dropdown-pin.is-open > .nav-dropdown-toggle {
            color: #2a1a05;
            background: linear-gradient(135deg, #f3dfaa 0%, #e9c577 45%, #d4a86a 100%);
            border-color: rgba(255, 236, 190, 0.9);
            transform: translateY(-2px);
            box-shadow:
                0 10px 22px rgba(212, 168, 106, 0.42),
                0 0 18px rgba(233, 197, 119, 0.35);
        }

        .landing-nav-links > li.nav-dropdown-pin.is-open > .nav-dropdown-toggle svg {
            transform: rotate(180deg);
            opacity: 1;
        }

        .landing-nav-links > li > a::after {
            display: none;
        }

        .landing-nav-links .nav-dropdown-menu {
            margin-top: 10px;
            border-radius: 14px;
            border: 1px solid rgba(233, 197, 119, 0.4);
            background:
                linear-gradient(165deg, rgba(8, 36, 28, 0.97) 0%, rgba(3, 22, 17, 0.98) 100%);
            box-shadow:
                0 18px 40px rgba(0, 0, 0, 0.45),
                0 0 0 1px rgba(255, 248, 230, 0.06),
                0 0 28px rgba(233, 197, 119, 0.12);
            backdrop-filter: blur(12px);
            overflow: hidden;
        }

        .landing-nav-links .nav-dropdown-menu-label {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 12px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #e9c577;
            padding: 12px 16px 8px;
            border-bottom: 1px solid rgba(233, 197, 119, 0.2);
            background: linear-gradient(90deg, rgba(233, 197, 119, 0.12), transparent);
        }

        .landing-nav-links .nav-sub-link {
            border-radius: 10px;
            margin: 4px 6px;
            transition: background 0.2s, transform 0.2s, border-color 0.2s;
            border: 1px solid transparent;
        }

        .landing-nav-links .nav-sub-link:hover,
        .landing-nav-links .nav-sub-link.is-active {
            background: linear-gradient(135deg, rgba(233, 197, 119, 0.18), rgba(233, 197, 119, 0.06));
            border-color: rgba(233, 197, 119, 0.35);
            transform: translateX(3px);
        }

        .landing-nav-links .sub-link-title {
            color: #fff8ea;
            font-weight: 600;
        }

        .landing-nav-links .sub-link-desc {
            color: rgba(240, 230, 212, 0.7);
        }

        .landing-nav-links .landing-connect-btn {
            font-family: 'Playfair Display', Georgia, serif;
            letter-spacing: 0.05em;
            border-radius: 12px !important;
            box-shadow:
                0 6px 16px rgba(212, 168, 106, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.35);
        }

        .landing-nav-links .landing-connect-btn:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow:
                0 10px 24px rgba(212, 168, 106, 0.5),
                0 0 16px rgba(233, 197, 119, 0.35);
        }

        .landing-social {
            margin-left: 6px;
            display: inline-flex;
            gap: 8px;
            align-items: center;
            padding: 4px;
            border-radius: 12px;
            border: 1px solid rgba(233, 197, 119, 0.22);
            background: rgba(255, 255, 255, 0.04);
        }

        .landing-social a {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            color: #f4ecdd;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(233, 197, 119, 0.2);
            transition: transform 0.2s, background 0.2s, color 0.2s, box-shadow 0.2s;
        }

        .landing-social a:hover {
            color: #2a1a05;
            background: linear-gradient(135deg, #e9c577, #d4a86a);
            border-color: #e9c577;
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(212, 168, 106, 0.4);
        }

        @media (max-width: 900px) {
            .landing-nav-links {
                width: 100%;
                justify-content: center;
            }
        }



        
        .landing-body {
            position: relative;
            z-index: 2;
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: flex-start;
            gap: 28px;
            padding: 28px 4vw 36px 16px;
        }

        .landing-hero-text {
            width: 100%;
            max-width: 920px;
            color: #fff;
            text-align: left;
            animation: landingHeroIn 0.85s ease both;
        }

        .landing-hero-text h1,
        .landing-brand-title {
            position: relative;
            display: inline-block;
            font-family: 'Playfair Display', Georgia, serif;
            font-size: clamp(42px, 6.5vw, 74px);
            line-height: 1.05;
            font-weight: 800;
            margin: 0 0 14px;
            letter-spacing: 0.03em;
            color: transparent;
            isolation: isolate;
        }

        .landing-brand-title-main {
            position: relative;
            z-index: 2;
            display: inline-block;
            background: linear-gradient(
                115deg,
                #fff8e8 0%,
                #ffe7a8 18%,
                #ffffff 32%,
                #e9c577 48%,
                #fff4d2 62%,
                #f0d48a 78%,
                #ffffff 100%
            );
            background-size: 220% 100%;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            -webkit-text-fill-color: transparent;
            filter:
                drop-shadow(0 0 10px rgba(255, 236, 180, 0.55))
                drop-shadow(0 0 28px rgba(233, 197, 119, 0.45))
                drop-shadow(0 6px 18px rgba(0, 0, 0, 0.35));
            animation: brandLightSweep 4.5s ease-in-out infinite;
        }

        .landing-brand-sweet {
            font-style: italic;
            font-weight: 700;
            margin-right: 0.28em;
            color: inherit;
            -webkit-text-fill-color: transparent;
        }

        .landing-brand-austria {
            font-weight: 800;
            letter-spacing: 0.045em;
            color: inherit;
            -webkit-text-fill-color: transparent;
        }

        .landing-brand-title-aura {
            position: absolute;
            z-index: 0;
            left: 50%;
            top: 55%;
            width: 120%;
            height: 70%;
            transform: translate(-50%, -50%);
            background:
                radial-gradient(ellipse at center, rgba(255, 244, 210, 0.55) 0%, rgba(233, 197, 119, 0.28) 35%, transparent 72%);
            filter: blur(18px);
            pointer-events: none;
            animation: brandAuraPulse 3.2s ease-in-out infinite;
        }

        .landing-brand-title-shine {
            position: absolute;
            z-index: 3;
            inset: -10% -8%;
            pointer-events: none;
            background: linear-gradient(
                105deg,
                transparent 35%,
                rgba(255, 255, 255, 0.05) 42%,
                rgba(255, 255, 255, 0.55) 50%,
                rgba(255, 248, 220, 0.2) 58%,
                transparent 65%
            );
            background-size: 220% 100%;
            mix-blend-mode: soft-light;
            animation: brandShinePass 3.8s ease-in-out infinite;
            border-radius: 8px;
        }

        .landing-brand-title-shine::after {
            content: '';
            position: absolute;
            top: 18%;
            left: 12%;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #fff;
            box-shadow:
                0 0 10px 3px rgba(255, 250, 230, 0.95),
                42px 18px 0 -1px rgba(255, 240, 200, 0.85),
                42px 18px 12px rgba(233, 197, 119, 0.55),
                110px 8px 0 -2px rgba(255, 255, 255, 0.7),
                110px 8px 10px rgba(255, 230, 160, 0.45);
            animation: brandSparkle 2.6s ease-in-out infinite;
        }

        @keyframes brandLightSweep {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }

        @keyframes brandAuraPulse {
            0%, 100% { opacity: 0.55; transform: translate(-50%, -50%) scale(0.92); }
            50% { opacity: 1; transform: translate(-50%, -50%) scale(1.08); }
        }

        @keyframes brandShinePass {
            0%, 100% { background-position: 130% 0; opacity: 0.35; }
            45%, 55% { opacity: 0.85; }
            50% { background-position: -30% 0; }
        }

        @keyframes brandSparkle {
            0%, 100% { opacity: 0.35; transform: scale(0.8); }
            40% { opacity: 1; transform: scale(1.15); }
            70% { opacity: 0.6; transform: scale(1); }
        }

        @media (prefers-reduced-motion: reduce) {
            .landing-brand-title-main,
            .landing-brand-title-aura,
            .landing-brand-title-shine,
            .landing-brand-title-shine::after {
                animation: none;
            }
        }

        .landing-hero-text p {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: clamp(18px, 2.2vw, 26px);
            font-style: italic;
            color: #f0e6d4;
            line-height: 1.45;
            margin: 0;
            max-width: 640px;
            text-shadow: 0 2px 12px rgba(0, 0, 0, 0.5);
            animation: landingSloganIn 1s ease 0.15s both;
        }

        .landing-ad-frame {
            width: min(960px, 100%);
            align-self: flex-start;
            margin-left: 0;
            margin-right: auto;
            animation: landingAdIn 0.9s ease 0.25s both;
        }

        .landing-ad-inner {
            position: relative;
            width: 100%;
            aspect-ratio: 16 / 9;
            border-radius: 10px;
            overflow: hidden;
            background: rgba(0, 18, 12, 0.75);
            border: 2px solid rgba(233, 197, 119, 0.75);
            box-shadow:
                0 0 0 6px rgba(233, 197, 119, 0.12),
                0 18px 48px rgba(0, 0, 0, 0.45);
        }

        .landing-ad-inner::before {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 2;
            border-radius: inherit;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.12);
        }

        .landing-ad-inner video,
        .landing-ad-inner iframe {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            border: 0;
            object-fit: cover;
            background: #00140e;
            z-index: 1;
        }

        .landing-ad-placeholder {
            position: absolute;
            inset: 0;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 24px;
            text-align: center;
            color: rgba(240, 230, 212, 0.9);
            background:
                radial-gradient(ellipse at center, rgba(233, 197, 119, 0.12) 0%, transparent 55%),
                linear-gradient(160deg, rgba(0, 40, 30, 0.95), rgba(0, 20, 14, 0.98));
        }

        .landing-ad-placeholder.is-hidden {
            display: none;
        }

        .landing-ad-placeholder strong {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: clamp(18px, 2.4vw, 26px);
            color: #e9c577;
            font-weight: 700;
        }

        .landing-ad-placeholder span {
            font-size: 14px;
            max-width: 420px;
            line-height: 1.5;
            color: rgba(240, 230, 212, 0.75);
        }

        @keyframes landingHeroIn {
            from { opacity: 0; transform: translateY(-18px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes landingSloganIn {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes landingAdIn {
            from { opacity: 0; transform: translateY(22px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
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
            position: fixed;
            top: 50%;
            left: 50%;
            width: min(380px, calc(100vw - 32px));
            max-width: 100%;
            z-index: 30;
            opacity: 0;
            visibility: hidden;
            transform: translate(-50%, calc(-50% + 16px)) scale(0.98);
            transition: opacity 0.25s ease, transform 0.25s ease, visibility 0.25s;
            pointer-events: none;
        }

        .login-panel.is-open {
            opacity: 1;
            visibility: visible;
            transform: translate(-50%, -50%) scale(1);
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



            .landing-body { justify-content: flex-start; align-items: flex-start; text-align: left; }



            .landing-hero-text { text-align: left; max-width: 100%; }



        }



        /* ===== Verrouillage global des tableaux : aucun d?filement horizontal ===== */



        .fournisseur-table-wrap,



        .stock-table-wrap,



        #commandesView .fournisseur-table-wrap,



        #commandesPrintArea .fournisseur-table-wrap,



        #commandesPrintAreaAchats .fournisseur-table-wrap,



        #produitPrintArea .fournisseur-table-wrap {



            max-width: 100%;



            overflow-x: hidden;



        }



        .fournisseur-table,



        .produits-table,



        .achats-commandes-table,



        .achats-lines-table,



        .stock-table {



            width: 100%;



            max-width: 100%;



            min-width: 0;



            table-layout: fixed;



        }



        .fournisseur-table thead th,



        .fournisseur-table tbody td,



        .produits-table thead th,



        .produits-table tbody td,



        .achats-commandes-table thead th,



        .achats-commandes-table tbody td,



        .achats-lines-table thead th,



        .achats-lines-table tbody td,



        .stock-table thead th,



        .stock-table tbody td {



            box-sizing: border-box;



            overflow: hidden;



            text-overflow: ellipsis;



            white-space: normal;



            overflow-wrap: anywhere;



        }



        .fournisseur-table thead th,



        .produits-table thead th,



        .achats-commandes-table thead th {



            padding-left: 6px;



            padding-right: 6px;



            font-size: 9.5px;



            line-height: 1.2;



            letter-spacing: 0.02em;



        }



        .fournisseur-table tbody td,



        .produits-table tbody td,



        .achats-commandes-table tbody td {



            padding-left: 6px;



            padding-right: 6px;



            font-size: 11px;



        }



        /* ===== Pages fixes : seules les zones de donn?es des tableaux d?filent ===== */



        html,



        body {



            width: 100%;



            height: 100%;



            max-width: 100%;



            overflow: hidden !important;



        }



        .main-wrapper {



            height: 100vh;



            max-height: 100vh;



            min-height: 0;



            overflow: hidden !important;



        }



        .dashboard-content {



            display: flex;



            flex: 1;



            flex-direction: column;



            min-height: 0;



            overflow: hidden !important;



        }



        .page-footer,



        .hero-header,



        .list-toolbar {



            flex: 0 0 auto;



        }



        #dashboardView,



        #ficheFournisseurView,



        #achatsView,



        #ficheProduitView,



        #ficheSocieteView,



        #tresorerieMaterielsView,



        #utilisateurView {



            flex: 1;



            min-height: 0;



            max-height: 100%;



            overflow: hidden !important;



        }



        #ficheFournisseurView:not(.hidden),



        #achatsView:not(.hidden),



        #ficheProduitView:not(.hidden),



        #ficheSocieteView:not(.hidden),



        #tresorerieMaterielsView:not(.hidden),



        #utilisateurView:not(.hidden),



        #fournisseurListPanel:not(.hidden),



        #produitListPanel:not(.hidden),



        #achatsConsultMode:not(.hidden),



        #societeConsultPanel:not(.hidden),



        #societeFormPanel:not(.hidden),



        #materielsConsultPanel:not(.hidden),



        #materielsFormPanel:not(.hidden),



        #utilisateurConsultPanel:not(.hidden),



        #utilisateurFormPanel:not(.hidden) {



            display: flex;



            flex-direction: column;



        }



        #fournisseurListPanel,



        #produitListPanel,



        #achatsConsultMode,



        #societeConsultPanel,



        #materielsConsultPanel,



        #utilisateurConsultPanel,



        #fournisseurPrintArea,



        #produitPrintArea,



        #societePrintArea,



        #materielsPrintArea,



        #utilisateurPrintArea,



        #commandesPrintAreaAchats {



            flex: 1;



            min-height: 0;



            overflow: hidden !important;



        }



        #fournisseurPrintArea,



        #produitPrintArea,



        #societePrintArea,



        #materielsPrintArea,



        #utilisateurPrintArea,



        #commandesPrintAreaAchats {



            display: flex;



            flex-direction: column;



        }



        .fournisseur-table-wrap,



        .stock-table-wrap,



        #fournisseurPrintArea .fournisseur-table-wrap,



        #commandesPrintArea .fournisseur-table-wrap,



        #commandesPrintAreaAchats .fournisseur-table-wrap,



        #produitPrintArea .fournisseur-table-wrap,



        #achatsView .achats-articles-panel .fournisseur-table-wrap {



            flex: 1;



            width: 100%;



            height: auto !important;



            min-height: 0;



            max-height: 100% !important;



            max-width: 100% !important;



            overflow-x: hidden !important;



            overflow-y: auto !important;



            overscroll-behavior: contain;



            scrollbar-gutter: stable;



        }



        .fournisseur-table,



        .produits-table,



        .achats-commandes-table,



        .achats-lines-table,



        .stock-table {



            width: 100% !important;



            max-width: 100% !important;



            min-width: 0 !important;



            table-layout: fixed !important;



        }



        .fournisseur-table thead th,



        .produits-table thead th,



        .achats-commandes-table thead th,



        .achats-lines-table thead th,



        .stock-table thead th {



            position: sticky;



            top: 0;



            z-index: 8;



        }



        #fournisseursTable .col-actions,



        #fournisseursTable thead th.col-actions {



            position: static !important;



            right: auto !important;



            min-width: 0 !important;



            box-shadow: none !important;



        }



        .fournisseur-table tbody tr,



        .fournisseur-table tbody tr:hover,



        .fournisseur-table tbody td,



        .fournisseur-table tbody tr:hover td,



        .produits-table tbody tr:hover td,



        .achats-commandes-table tbody tr:hover td,



        .stock-table tbody tr:hover td {



            transform: none !important;



        }



        /* Titres et boutons fix?s directement au-dessus de tous les tableaux */



        #fournisseurListPanel .list-toolbar,



        #produitListPanel .list-toolbar,



        #achatsConsultMode .list-toolbar,



        #societeConsultPanel .list-toolbar,



        #materielsConsultPanel .list-toolbar,



        #utilisateurConsultPanel .list-toolbar,

        #reglementConsultPanel .list-toolbar {



            position: relative;



            top: auto;



            z-index: 10;



            width: 100%;



            min-height: 58px;



            margin: 0;



            padding: 9px 12px;



            border: 1px solid var(--border);



            border-bottom: 0;



            border-radius: 12px 12px 0 0;



            background: #fff;



            box-shadow: none;



            flex-wrap: nowrap;



        }



        #fournisseurListPanel .list-toolbar-title,



        #produitListPanel .list-toolbar-title,



        #achatsConsultMode .list-toolbar-title,



        #societeConsultPanel .list-toolbar-title,



        #materielsConsultPanel .list-toolbar-title,



        #utilisateurConsultPanel .list-toolbar-title,

        #reglementConsultPanel .list-toolbar-title {



            margin: 0;



            line-height: 40px;



            flex: 1 1 auto;



            min-width: 0;



        }



        #fournisseurListPanel .list-toolbar-actions,



        #produitListPanel .list-toolbar-actions,



        #achatsConsultMode .list-toolbar-actions,



        #societeConsultPanel .list-toolbar-actions,



        #materielsConsultPanel .list-toolbar-actions,



        #utilisateurConsultPanel .list-toolbar-actions,

        #reglementConsultPanel .list-toolbar-actions {



            align-self: center;



            flex: 0 0 auto;



            flex-wrap: nowrap;



        }



        #fournisseurPrintArea .fournisseur-table-wrap,



        #produitPrintArea .fournisseur-table-wrap,



        #materielsPrintArea .fournisseur-table-wrap,



        #utilisateurPrintArea .fournisseur-table-wrap,

        #reglementPrintArea .fournisseur-table-wrap {



            border-radius: 0 0 12px 12px;



        }



        #societePrintArea .societe-consult-card {



            border-radius: 0 0 12px 12px;



            border-top: 0;



        }



        #commandesPrintAreaAchats {



            border-radius: 0 0 12px 12px;



            border-top: 0;



        }



        /* Interface principale : cartes et tableaux align?s en haut */



        #dashboardView {



            display: flex;



            flex-direction: column;



            justify-content: flex-start;



            align-content: flex-start;



            height: 100%;



            padding-top: 0;



        }



        #dashboardView .kpi-grid {



            position: relative;



            top: 0;



            flex: 0 0 auto;



            margin-top: 0;



            margin-bottom: 8px;



            padding-top: 4px;



            padding-bottom: 8px;



        }



        #dashboardView .tables-grid {



            flex: 0 0 auto;



            align-items: start;



            margin-top: 0;



            margin-bottom: 8px;



        }



        #dashboardView .table-card {



            align-self: start;



            width: 100%;



        }



    

        /* ── Réglement Achat ── */

        #reglementAchatsView .rg-form-layout {

            display: grid;

            grid-template-columns: minmax(0, 1fr) 150px;

            gap: 14px 16px;

            align-items: start;

        }

        #reglementAchatsView .rg-inline-row {

            display: grid;

            gap: 8px 10px;

            align-items: end;

            margin-bottom: 8px;

        }

        #reglementAchatsView .rg-inline-row-1 {

            grid-template-columns: 130px 110px minmax(0, 1.6fr);

        }

        #reglementAchatsView .rg-inline-row-2 {

            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr) minmax(0, 1fr) minmax(0, 1fr);

        }

        #reglementAchatsView .rg-inline-row-3 {

            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);

        }

        #reglementAchatsView .rg-photo-panel {

            display: flex;

            flex-direction: column;

            gap: 8px;

        }

        #reglementAchatsView .rg-photo-panel > label {

            font-size: 9px;

            font-weight: 600;

            letter-spacing: 0.04em;

            text-transform: uppercase;

            color: var(--green-dark);

        }

        #reglementAchatsView .rg-photo-preview {

            width: 100%;

            aspect-ratio: 1;

            border: 2px dashed rgba(0, 51, 38, 0.2);

            border-radius: 8px;

            overflow: hidden;

            display: flex;

            align-items: center;

            justify-content: center;

            background: linear-gradient(145deg, #FDFCFA 0%, #F3F1EA 100%);

        }

        #reglementAchatsView .rg-photo-preview img {

            width: 100%;

            height: 100%;

            object-fit: cover;

            display: block;

        }

        #reglementAchatsView .rg-photo-placeholder {

            font-size: 10px;

            color: var(--text-muted);

            text-align: center;

            padding: 8px;

        }

        #reglementAchatsView .rg-photo-actions {

            display: flex;

            gap: 6px;

        }

        #reglementAchatsView .rg-photo-actions .btn-photo {

            flex: 1;

        }

        #reglementAchatsView .btn-form-plus {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            width: 36px;

            min-width: 36px;

            padding: 0;

            background: linear-gradient(135deg, var(--green-dark) 0%, #004d3a 100%);

            color: #fff;

        }

        #reglementAchatsView .btn-form-plus:hover {

            filter: brightness(1.08);

        }

        #reglementAchatsView .btn-form-plus svg {

            width: 16px;

            height: 16px;

        }

        #reglementAchatsView .btn-form-plus[disabled] {

            opacity: 0.45;

            cursor: not-allowed;

            filter: none;

        }

        @media (max-width: 900px) {

            #reglementAchatsView .rg-form-layout { grid-template-columns: 1fr; }

            #reglementAchatsView .rg-inline-row-1,

            #reglementAchatsView .rg-inline-row-2,

            #reglementAchatsView .rg-inline-row-3 { grid-template-columns: 1fr 1fr; }

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



                <li><a href="#">Accueil</a></li>



                <li class="nav-dropdown nav-dropdown-pin" id="categoriesDropdown">



                    <a href="#" class="nav-dropdown-toggle" aria-expanded="false" aria-haspopup="true">Catégories <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg></a>                    <ul class="nav-dropdown-menu">



                        <li class="nav-dropdown-menu-label">Nos gammes produits</li>



                        <li>



                            <a href="#" class="category-link nav-sub-link" data-category="coque" onclick="return window.openCategoryGallery(event, 'coque')">



                                <span class="sub-link-icon cat-coque">??</span>



                                <span class="sub-link-content">



                                    <span class="sub-link-title">Fruits ? coque</span>



                                    <span class="sub-link-desc">Noix et graines nobles</span>



                                </span>



                                <span class="sub-link-arrow" aria-hidden="true"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></span>



                            </a>



                        </li>



                        <li>



                            <a href="#" class="category-link nav-sub-link" data-category="seche" onclick="return window.openCategoryGallery(event, 'seche')">



                                <span class="sub-link-icon cat-seche">??</span>



                                <span class="sub-link-content">



                                    <span class="sub-link-title">Fruits s?ch?s</span>



                                    <span class="sub-link-desc">Dattes, figues &amp; abricots</span>



                                </span>



                                <span class="sub-link-arrow" aria-hidden="true"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></span>



                            </a>



                        </li>



                        <li>



                            <a href="#" class="category-link nav-sub-link" data-category="cacahuetes" onclick="return window.openCategoryGallery(event, 'cacahuetes')">



                                <span class="sub-link-icon cat-cacahuetes">??</span>



                                <span class="sub-link-content">



                                    <span class="sub-link-title">Cacahu?tes et d?riv?s</span>



                                    <span class="sub-link-desc">Grill?es, sal?es &amp; enrob?es</span>



                                </span>



                                <span class="sub-link-arrow" aria-hidden="true"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></span>



                            </a>



                        </li>



                        <li>



                            <a href="#" class="category-link nav-sub-link" data-category="graines" onclick="return window.openCategoryGallery(event, 'graines')">



                                <span class="sub-link-icon cat-graines">??</span>



                                <span class="sub-link-content">



                                    <span class="sub-link-title">Graines alimentaires</span>



                                    <span class="sub-link-desc">Chia, lin, tournesol &amp; plus</span>



                                </span>



                                <span class="sub-link-arrow" aria-hidden="true"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></span>



                            </a>



                        </li>



                        <li>



                            <a href="#" class="category-link nav-sub-link" data-category="enrobes" onclick="return window.openCategoryGallery(event, 'enrobes')">



                                <span class="sub-link-icon cat-enrobes">??</span>



                                <span class="sub-link-content">



                                    <span class="sub-link-title">Fruits secs enrob?s</span>



                                    <span class="sub-link-desc">Chocolat &amp; confiseries</span>



                                </span>



                                <span class="sub-link-arrow" aria-hidden="true"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></span>



                            </a>



                        </li>



                        <li>



                            <a href="#" class="category-link nav-sub-link" data-category="ramadan" onclick="return window.openCategoryGallery(event, 'ramadan')">



                                <span class="sub-link-icon cat-ramadan">??</span>



                                <span class="sub-link-content">



                                    <span class="sub-link-title">Produits Ramadan &amp; F?tes</span>



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



                                    <span class="sub-link-title">Zone Taza, F?s</span>



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



                                    <span class="sub-link-title">Zone El Gharb</span>



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



                        <li class="nav-dropdown-menu-label">R?seau de distribution</li>



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



                                    <span class="sub-link-title">D?cathlon</span>



                                    <span class="sub-link-desc">Nutrition sportive &amp; bien-?tre</span>



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



                                    <span class="sub-link-title">Atacadao</span>



                                    <span class="sub-link-desc">Cash &amp; carry professionnel</span>



                                </span>



                                <span class="sub-link-arrow" aria-hidden="true"><svg viewBox="0 0 24 24"><polyline points="9 18 15 12 9 6"/></svg></span>



                            </a>



                        </li>



                    </ul>



                </li>



                <li><a href="#">Contact</a></li>



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
                <h1 class="landing-brand-title" translate="no">
                    <span class="landing-brand-title-aura" aria-hidden="true"></span>
                    <span class="landing-brand-title-main">
                        <span class="landing-brand-sweet">Sweet</span>
                        <span class="landing-brand-austria">Austria</span>
                    </span>
                    <span class="landing-brand-title-shine" aria-hidden="true"></span>
                </h1>
                <p>La plateforme la plus proche des goûts de luxe</p>
            </div>

            <div class="landing-ad-frame" aria-label="Espace publicitaire vidéo">
                <div class="landing-ad-inner">
                    <video id="landingAdVideo" controls playsinline preload="metadata" poster="{{ asset('images/a2s-fruits-background.png') }}" data-default-src="{{ asset('videos/publicite.mp4') }}">
                        <source src="{{ asset('videos/publicite.mp4') }}" type="video/mp4">
                    </video>
                    <iframe id="landingAdIframe" class="hidden" title="Publicité vidéo" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen referrerpolicy="strict-origin-when-cross-origin"></iframe>
                    <div class="landing-ad-placeholder" id="landingAdPlaceholder">
                        <strong>Espace publicitaire</strong>
                        <span>Configurez l’habillage dans <em>Configuration → Fiche Société → Habillage</em> (vidéo ou URL).</span>
                    </div>
                </div>
            </div>

            <div class="login-panel" id="loginPanel">



            <form class="login-card" id="loginForm" autocomplete="off">



                <button type="button" class="login-panel-close" id="closeLoginBtn" aria-label="Fermer">



                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>



                </button>



                <h2 class="login-title">Connexion</h2>



                <p class="login-subtitle">Acc?dez ? votre espace de gestion</p>



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



        <div class="landing-footer">2026-A2s---Tous Droits Réservés</div>



    </div>



    {{-- Logo header (align? avec hero) --}}



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



                <span class="nav-label">Tableau de bord</span>



            </a>



            <div class="nav-group">



                <button type="button" class="nav-item nav-group-toggle" aria-expanded="false">



                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>



                    <span class="nav-label">Fournisseur</span>



                    <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>



                </button>



                <ul class="nav-submenu">



                    <li><a href="#" class="nav-subitem" data-view="fiche-fournisseur"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><path d="M12 18v-6"/><path d="M9 15h6"/></svg></span>Fiche Fournisseur</a></li>



                    <li><a href="#" class="nav-subitem" data-view="achats"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg></span>Bon d'Achat</a></li>



                    <li><a href="#" class="nav-subitem" data-view="reglement-achats"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg></span>Réglement Achat</a></li>



                    <li><a href="#" class="nav-subitem"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></span>Balance Achats</a></li>



                    <li><a href="#" class="nav-subitem"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></span>Relevé Compte Fournisseur</a></li>



                </ul>



            </div>



            <div class="nav-group">



                <button type="button" class="nav-item nav-group-toggle" aria-expanded="false">



                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>



                    <span class="nav-label">Stock</span>



                    <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>



                </button>



                <ul class="nav-submenu">



                    <li><a href="#" class="nav-subitem" data-view="fiche-produit"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><circle cx="12" cy="14" r="2"/><path d="M12 12v-1"/></svg></span>Fiche Produit</a></li>



                    <li><a href="#" class="nav-subitem"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></span>Dépôt Produits Crus</a></li>



                    <li><a href="#" class="nav-subitem"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg></span>Dépôt Produits Finis</a></li>



                    <li><a href="#" class="nav-subitem"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/></svg></span>Dépôt produits Divers</a></li>



                </ul>



            </div>



            <div class="nav-group">



                <button type="button" class="nav-item nav-group-toggle" aria-expanded="false">



                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4"/><path d="M12 18v4"/><path d="M4.93 4.93l2.83 2.83"/><path d="M16.24 16.24l2.83 2.83"/><path d="M2 12h4"/><path d="M18 12h4"/><path d="M4.93 19.07l2.83-2.83"/><path d="M16.24 7.76l2.83-2.83"/></svg>



                    <span class="nav-label">Production</span>



                    <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>



                </button>



                <ul class="nav-submenu">



                    <li><a href="#" class="nav-subitem"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 20V10"/><path d="M18 20V4"/><path d="M6 20v-4"/></svg></span>Etat Journalier Quantité</a></li>



                    <li><a href="#" class="nav-subitem"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg></span>Etat Journalier Production</a></li>



                    <li><a href="#" class="nav-subitem"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M5 12h14"/><path d="M12 5l7 7-7 7"/></svg></span>Etat Journalier Sortie</a></li>



                    <li><a href="#" class="nav-subitem"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M12 1v22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></span>Etat Journalier Dépense</a></li>



                </ul>



            </div>



            <div class="nav-group">



                <button type="button" class="nav-item nav-group-toggle" aria-expanded="false">



                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>



                    <span class="nav-label">Client</span>



                    <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>



                </button>



                <ul class="nav-submenu">



                    <li><a href="#" class="nav-subitem"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></span>Fiche Client</a></li>



                    <li><a href="#" class="nav-subitem"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg></span>Vente</a></li>



                    <li><a href="#" class="nav-subitem"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg></span>R?glement</a></li>



                    <li><a href="#" class="nav-subitem"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></span>Balance</a></li>



                    <li><a href="#" class="nav-subitem"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg></span>Relev? Compte Clt</a></li>



                </ul>



            </div>



            <div class="nav-group">



                <button type="button" class="nav-item nav-group-toggle" aria-expanded="false">



                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>



                    <span class="nav-label">Banque</span>



                    <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>



                </button>



                <ul class="nav-submenu">



                    <li><a href="#" class="nav-subitem"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><polyline points="19 12 12 19 5 12"/></svg></span>D?bit</a></li>



                    <li><a href="#" class="nav-subitem"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg></span>Cr?dit</a></li>



                    <li><a href="#" class="nav-subitem"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg></span>Caisse</a></li>



                </ul>



            </div>



            <div class="nav-group">



                <button type="button" class="nav-item nav-group-toggle" aria-expanded="false">



                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>



                    <span class="nav-label">Rapport</span>



                    <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>



                </button>



                <ul class="nav-submenu">



                    <li><a href="#" class="nav-subitem"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg></span>Etat Achats</a></li>



                    <li><a href="#" class="nav-subitem"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg></span>Etat Ventes</a></li>



                    <li><a href="#" class="nav-subitem"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg></span>Etat Stock</a></li>



                    <li><a href="#" class="nav-subitem"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg></span>Etat Paiement</a></li>



                </ul>



            </div>



            <div class="nav-group">



                <button type="button" class="nav-item nav-group-toggle" aria-expanded="false">



                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>



                    <span class="nav-label">Configuration</span>



                    <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>



                </button>



                                <ul class="nav-submenu">



                    <li><a href="#" class="nav-subitem" data-view="fiche-societe"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M3 21h18"/><path d="M5 21V7l7-4 7 4v14"/><path d="M9 21v-6h6v6"/><path d="M9 9h1"/><path d="M14 9h1"/></svg></span>Fiche Société</a></li>



                    <li><a href="#" class="nav-subitem" data-view="tresorerie-materiels"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><path d="M12 12v4"/><path d="M8 12h8"/></svg></span>Trésorerie Matériels</a></li>



                    <li><a href="#" class="nav-subitem" data-view="utilisateur"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>Utilisateur</a></li>



                    <li class="nav-subgroup">



                        <button type="button" class="nav-subgroup-toggle" aria-expanded="false">

                            <span class="nav-subgroup-toggle-main">

                                <span class="nav-subicon" aria-hidden="true">

                                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>

                                </span>

                                <span>Paramètres</span>

                            </span>

                            <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>

                        </button>



                        <ul class="nav-subsubmenu">



                            <li><a href="#" class="nav-subitem"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg></span>Trésorerie</a></li>



                            <li><a href="#" class="nav-subitem"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg></span>Banque</a></li>



                            <li><a href="#" class="nav-subitem"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><circle cx="12" cy="12" r="2"/></svg></span>Caisse</a></li>



                            <li><a href="#" class="nav-subitem"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M4 19h16"/><path d="M8 19V9"/><path d="M12 19V5"/><path d="M16 19v-7"/></svg></span>Unité de Mesure</a></li>



                            <li><a href="#" class="nav-subitem"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span>Ville</a></li>



                            <li><a href="#" class="nav-subitem"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>Commerciaux</a></li>



                            <li><a href="#" class="nav-subitem"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg></span>Transport</a></li>



                            <li><a href="#" class="nav-subitem"><span class="nav-subicon" aria-hidden="true"><svg viewBox="0 0 24 24"><circle cx="12" cy="7" r="4"/><path d="M5.5 21a6.5 6.5 0 0 1 13 0"/><path d="M12 11v4"/><path d="M9 14h6"/></svg></span>Chauffeurs</a></li>



                        </ul>



                    </li>



                </ul>



            </div>



        </nav>



    </aside>



    <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Ouvrir ou fermer la barre lat?rale" aria-expanded="true">



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



                        <p class="hero-slogan">La plateforme la plus proche des go?ts de luxe</p>



                    </div>



                    <nav class="top-nav">



                        <a href="#" class="nav-brand" translate="no">



                            <span class="nav-brand-text">SWEET AUSTRIA</span>



                        </a>



                        <button type="button" class="top-nav-logout" id="goToLandingBtn" title="Page d'accueil / D?connexion">



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



                    <div class="kpi-label">Total Achats</div>



                    <div class="kpi-value">218,000 MAD</div>



                </div>



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



                    <div class="kpi-label">Total Ventes</div>



                    <div class="kpi-value">452,000 MAD</div>



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



                    <div class="kpi-label">Total Charges</div>



                    <div class="kpi-value">128,300 MAD</div>



                </div>



                <div class="kpi-card brown">



                    <div class="kpi-top">



                        <div class="kpi-icon">



                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>



                        </div>



                        <span class="kpi-badge kpi-badge-up">



                            <svg viewBox="0 0 24 24"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>



                            +3%



                        </span>



                    </div>



                    <div class="kpi-label">Caisse</div>



                    <div class="kpi-value">84,500 MAD</div>



                </div>



                <div class="kpi-card teal">



                    <div class="kpi-top">



                        <div class="kpi-icon">



                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>



                        </div>



                        <span class="kpi-badge kpi-badge-up">



                            <svg viewBox="0 0 24 24"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>



                            +8%



                        </span>



                    </div>



                    <div class="kpi-label">Trésorerie</div>



                    <div class="kpi-value">105,700 MAD</div>



                </div>



            </div>



            {{-- Stock Tables --}}



            <div class="tables-grid">



                <div class="table-card">



                    <div class="table-card-title">?tat Stock Produits</div>



                    <table class="stock-table">



                        <thead>



                            <tr>



                                <th>R?f?rence</th>



                                <th>D?signation</th>



                                <th>Quantit?</th>



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



                    <div class="table-card-title">?tat Stock Produits Finis</div>



                    <table class="stock-table">



                        <thead>



                            <tr>



                                <th>R?f?rence</th>



                                <th>D?signation</th>



                                <th>Quantit?</th>



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



            {{-- Derniers bons --}}



            <div class="tables-grid">



                <div class="table-card">



                    <div class="table-card-title">5 Derniers Bons d'Achat</div>



                    <table class="stock-table">



                        <thead>



                            <tr>



                                <th>Date</th>



                                <th>Fournisseur</th>



                                <th>Bon N?</th>



                                <th>Montant</th>



                            </tr>



                        </thead>



                        <tbody>



                            <tr>



                                <td>27/07/2026</td>



                                <td>Agro Fruits SARL</td>



                                <td>BA-2026-0148</td>



                                <td>42,800.00</td>



                            </tr>



                            <tr>



                                <td>25/07/2026</td>



                                <td>Nador Nuts Trading</td>



                                <td>BA-2026-0147</td>



                                <td>18,350.00</td>



                            </tr>



                            <tr>



                                <td>22/07/2026</td>



                                <td>Atlas Dattes &amp; Co</td>



                                <td>BA-2026-0146</td>



                                <td>31,200.00</td>



                            </tr>



                            <tr>



                                <td>20/07/2026</td>



                                <td>Med Import Fruits</td>



                                <td>BA-2026-0145</td>



                                <td>9,780.00</td>



                            </tr>



                            <tr>



                                <td>18/07/2026</td>



                                <td>Oriental Dry Food</td>



                                <td>BA-2026-0144</td>



                                <td>27,450.00</td>



                            </tr>



                        </tbody>



                    </table>



                </div>



                <div class="table-card">



                    <div class="table-card-title">5 Derniers Bons de Vente</div>



                    <table class="stock-table">



                        <thead>



                            <tr>



                                <th>Date</th>



                                <th>Client</th>



                                <th>Ville</th>



                                <th>Bon N?</th>



                                <th>Montant</th>



                                <th>Solde</th>



                            </tr>



                        </thead>



                        <tbody>



                            <tr>



                                <td>27/07/2026</td>



                                <td>?picerie Al Amal</td>



                                <td>Nador</td>



                                <td>BV-2026-0321</td>



                                <td>8,640.00</td>



                                <td class="solde-negative">0.00</td>



                            </tr>



                            <tr>



                                <td>26/07/2026</td>



                                <td>Palais Gourmet</td>



                                <td>Casablanca</td>



                                <td>BV-2026-0320</td>



                                <td>22,150.00</td>



                                <td class="solde-negative">4,200.00</td>



                            </tr>



                            <tr>



                                <td>24/07/2026</td>



                                <td>Souk Prestige</td>



                                <td>F?s</td>



                                <td>BV-2026-0319</td>



                                <td>15,980.00</td>



                                <td class="solde-negative">0.00</td>



                            </tr>



                            <tr>



                                <td>23/07/2026</td>



                                <td>Casa D?lices</td>



                                <td>Rabat</td>



                                <td>BV-2026-0318</td>



                                <td>11,300.00</td>



                                <td class="solde-negative">1,850.00</td>



                            </tr>



                            <tr>



                                <td>21/07/2026</td>



                                <td>Boutique Luxe Sec</td>



                                <td>Tanger</td>



                                <td>BV-2026-0317</td>



                                <td>19,750.00</td>



                                <td class="solde-negative">0.00</td>



                            </tr>



                        </tbody>



                    </table>



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



                                        <option value="">? S?lectionner ?</option>



                                        <option value="Rev">Rev ? Revendeur</option>



                                        <option value="Ste">St? ? Soci?t?</option>



                                    </select>



                                </div>



                                <div class="form-group">



                                    <label for="fr_statut">Statut</label>



                                    <select id="fr_statut" name="statut" class="form-select">



                                        <option value="">? S?lectionner ?</option>



                                        <option value="G/c">G/c ? Grand compte</option>



                                        <option value="Mc">Mc ? Moyen compte</option>



                                        <option value="Pc">Pc ? Petit compte</option>



                                    </select>



                                </div>



                            </div>



                            <div class="fr-inline-row fr-inline-row-adr">



                                <div class="form-group">



                                    <label for="fr_adresse">Adresse</label>



                                    <input type="text" id="fr_adresse" name="adresse" class="form-input" placeholder="Adresse compl?te">



                                </div>



                                <div class="form-group">



                                    <label for="fr_telephone">T?l?phone</label>



                                    <input type="tel" id="fr_telephone" name="telephone" class="form-input" placeholder="06 XX XX XX XX">



                                </div>



                                <div class="form-group">



                                    <label for="fr_fixe">Fixe</label>



                                    <input type="tel" id="fr_fixe" name="fixe" class="form-input" placeholder="05 XX XX XX XX">



                                </div>



                                <div class="form-group">



                                    <label for="fr_ville">Ville</label>



                                    <select id="fr_ville" name="ville" class="form-select">



                                        <option value="">? S?lectionner ?</option>



                                    </select>



                                </div>



                                <div class="form-group">



                                    <label for="fr_email">E-mail</label>



                                    <input type="email" id="fr_email" name="email" class="form-input" placeholder="contact@fournisseur.ma">



                                </div>



                            </div>



                            <div class="fr-inline-row">



                                <div class="form-group">



                                    <label for="fr_type_paiement">Type R?glement</label>



                                    <select id="fr_type_paiement" name="type_paiement" class="form-select">



                                        <option value="">? S?lectionner ?</option>



                                        <option value="Esp">Esp ? Esp?ces</option>



                                        <option value="Chq">Chq ? Ch?que</option>



                                        <option value="Eff">Eff ? Effet</option>



                                        <option value="Vir">Vir ? Virement</option>



                                        <option value="Vers">Vers ? Versement</option>



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



                                <colgroup>



                                    <col style="width:4%">



                                    <col style="width:9%">



                                    <col style="width:4%">



                                    <col style="width:6%">



                                    <col style="width:7%">



                                    <col style="width:6%">



                                    <col style="width:10%">



                                    <col style="width:5%">



                                    <col style="width:7%">



                                    <col style="width:7%">



                                    <col style="width:10%">



                                    <col style="width:9%">



                                    <col style="width:16%">



                                </colgroup>



                                <thead>



                                    <tr>



                                        <th>ID</th>



                                        <th>Nom</th>



                                        <th>Type</th>



                                        <th>Ville</th>



                                        <th>T?l?phone</th>



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



                        <h2 class="list-toolbar-title">Bons d'Achat ? Consultation</h2>



                        <div class="list-toolbar-actions">



                            <button type="button" class="btn-list btn-list-add" id="nouveauBonAchatsBtn">



                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>



                                Nouveau Bon d'Achat



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



                                        <th>Bon N?</th>



                                        <th>Date Cmd</th>



                                        <th>Code</th>



                                        <th>Nom Fournisseur</th>



                                        <th>Ville</th>



                                        <th>Qt?</th>



                                        <th>Total</th>



                                        <th>R?glement</th>



                                        <th>?ch?ance</th>



                                        <th class="col-actions col-actions-cmd no-print-cmd">Actions</th>



                                    </tr>



                                </thead>



                                <tbody id="commandesListTableBodyAchats">



                                    <tr><td colspan="10" class="achats-commandes-empty">Aucun bon d'achat saisi</td></tr>



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



                                    <label for="ach_bon">Bon N?</label>



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



                                            <label for="ach_type_reglement">Type R?glement</label>



                                            <select id="ach_type_reglement" name="type_reglement" class="form-select">



                                                <option value="">? S?lectionner ?</option>



                                                <option value="Esp">Esp ? Esp?ces</option>



                                                <option value="Chq">Chq ? Ch?que</option>



                                                <option value="Eff">Eff ? Effet</option>



                                                <option value="Vir">Vir ? Virement</option>



                                                <option value="Vers">Vers ? Versement</option>



                                            </select>



                                        </div>



                                        <div class="form-group">



                                            <label for="ach_echeance">?ch?ance</label>



                                            <input type="date" id="ach_echeance" name="echeance" class="form-input">



                                        </div>



                                        <div class="form-group">



                                            <label for="ach_recuperation">R?cup?ration R?glement</label>



                                            <select id="ach_recuperation" name="recuperation" class="form-select">



                                                <option value="">? S?lectionner ?</option>



                                                <option value="Immediat">Imm?diat</option>



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



                                                <option value="">? S?lectionner ?</option>



                                            </select>



                                        </div>



                                        <div class="form-group">



                                            <label for="ach_transport">Transport</label>



                                            <select id="ach_transport" name="transport" class="form-select">



                                                <option value="">? S?lectionner ?</option>



                                                <option value="Interne">Transport interne</option>



                                                <option value="Externe">Transport externe</option>



                                                <option value="Frns">Transport fournisseur</option>



                                            </select>



                                        </div>



                                        <div class="form-group">



                                            <label for="ach_matricule">Matricule</label>



                                            <input type="text" id="ach_matricule" name="matricule" class="form-input" placeholder="Matricule v?hicule">



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



                            <h3 class="saisie-section-title">Articles</h3>



                            <div class="achats-lignes-scroll">



                            <div class="achats-lignes-inline-row">



                                <div class="form-group">



                                    <label for="ach_ligne_ref">R?f</label>



                                    <input type="text" id="ach_ligne_ref" class="form-input" placeholder="R?f">



                                </div>



                                <div class="form-group">



                                    <label for="ach_ligne_code_barre">Code-barres</label>



                                    <input type="text" id="ach_ligne_code_barre" class="form-input" placeholder="Code" autocomplete="off">



                                </div>



                                <div class="form-group">



                                    <label for="ach_ligne_designation">D?signation</label>



                                    <select id="ach_ligne_designation" class="form-select">



                                        <option value="">? S?lectionner ?</option>



                                    </select>



                                </div>



                                <div class="form-group">



                                    <label for="ach_ligne_categorie">Cat?gorie</label>



                                    <select id="ach_ligne_categorie" class="form-select">



                                        <option value="">? S?lectionner ?</option>



                                    </select>



                                </div>



                                <div class="form-group">



                                    <label for="ach_ligne_famille">Famille</label>



                                    <select id="ach_ligne_famille" class="form-select">



                                        <option value="">? S?lectionner ?</option>



                                    </select>



                                </div>



                                <div class="form-group">



                                    <label for="ach_ligne_quantite">Qt?</label>



                                    <input type="number" id="ach_ligne_quantite" class="form-input" step="0.001" min="0" value="1" placeholder="0">



                                </div>



                                <div class="form-group">



                                    <label for="ach_ligne_mesure">Unité de Mesure</label>



                                    <select id="ach_ligne_mesure" class="form-select">



                                        <option value="">? Chargement? ?</option>



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



                                <div class="achats-add-article-wrap no-print-achats">



                                    <button type="button" class="btn-add-article" id="ajouterLigneAchatsBtn" title="Ajouter l'article" aria-label="Ajouter l'article">



                                        <svg viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>



                                    </button>



                                </div>



                            </div>



                            </div>



                            <div class="achats-articles-summary no-print-achats">



                                <span class="achats-articles-count" id="achatsArticlesCount">0 article ajout?</span>



                                <div class="achats-total-bar" style="margin:0;padding:0;border:none;background:transparent;">



                                    <span>Total g?n?ral</span>



                                    <span id="achatsTotalGeneral">0,00 MAD</span>



                                </div>



                            </div>



                        </div>



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



                                            <label for="pr_ref">R?f</label>



                                            <input type="text" id="pr_ref" name="ref" class="form-input readonly" readonly>



                                        </div>



                                        <div class="form-group">



                                            <label for="pr_designation">D?signation</label>



                                            <select id="pr_designation" name="designation" class="form-select" required>



                                                <option value="">? S?lectionner ?</option>



                                            </select>



                                        </div>



                                        <div class="form-group">



                                            <label for="pr_type">Type</label>



                                            <select id="pr_type" name="type" class="form-select">



                                                <option value="">? S?lectionner ?</option>



                                                <option value="Pro Cru">Pro Cru</option>



                                                <option value="Pro Fini">Pro Fini</option>



                                                <option value="Pro Div">Pro Div</option>



                                            </select>



                                        </div>



                                    </div>



                                    <div class="pr-inline-row pr-inline-row-2">



                                        <div class="form-group">



                                            <label for="pr_categorie">Cat?gorie</label>



                                            <select id="pr_categorie" name="categorie" class="form-select">



                                                <option value="">? S?lectionner ?</option>



                                            </select>



                                        </div>



                                        <div class="form-group">



                                            <label for="pr_famille">Famille</label>



                                            <select id="pr_famille" name="famille" class="form-select">



                                                <option value="">? S?lectionner ?</option>



                                            </select>



                                        </div>



                                        <div class="form-group">



                                            <label for="pr_quantite">Qt?</label>



                                            <input type="number" id="pr_quantite" name="quantite" class="form-input" step="0.001" min="0" value="0">



                                        </div>



                                        <div class="form-group">



                                            <label for="pr_unite">U</label>



                                            <select id="pr_unite" name="unite" class="form-select">



                                                <option value="">? S?lectionner ?</option>



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



                                        <th>R?f</th>



                                        <th>Photo</th>



                                        <th>QR code</th>



                                        <th>D?signation</th>



                                        <th>Type</th>



                                        <th>Cat?gorie</th>



                                        <th>Famille</th>



                                        <th>Quantit?</th>



                                        <th>U</th>



                                        <th>Prix Achat</th>



                                        <th>Prix Vente</th>



                                        <th class="col-actions no-print-produit">Actions</th>



                                    </tr>



                                </thead>



                                <tbody id="produitsTableBody">



                                    <tr><td colspan="12" class="produits-empty">Aucun produit enregistr?</td></tr>



                                </tbody>



                            </table>



                        </div>



                    </div>



                </div>



            </div>



            {{-- Fiche Société --}}



            <div id="ficheSocieteView" class="saisie-panel hidden">



                <div id="societeFormPanel" class="hidden">



                    <div class="saisie-card">



                        <div class="saisie-card-header">



                            <div>



                                <h2>Fiche Société</h2>



                                <span>Barre de saisie</span>



                            </div>



                        </div>



                        <form class="saisie-form" id="ficheSocieteForm" novalidate>



                            <div class="societe-form-layout">



                                <div class="societe-form-fields">



                                    <div class="so-inline-row so-inline-row-1">



                                        <div class="form-group">



                                            <label for="so_nom">Nom Soci?t?</label>



                                            <input type="text" id="so_nom" name="nom" class="form-input" placeholder="Raison sociale" required>



                                        </div>



                                        <div class="form-group">



                                            <label for="so_gerant">Nom Complet G?rant</label>



                                            <input type="text" id="so_gerant" name="gerant" class="form-input" placeholder="Nom et pr?nom du g?rant">



                                        </div>



                                        <div class="form-group">



                                            <label for="so_contact">Contact</label>



                                            <input type="tel" id="so_contact" name="contact" class="form-input" placeholder="06 XX XX XX XX">



                                        </div>



                                    </div>



                                    <div class="so-inline-row so-inline-row-2">



                                        <div class="form-group">



                                            <label for="so_ville">Ville</label>



                                            <select id="so_ville" name="ville" class="form-select">



                                                <option value="">? S?lectionner ?</option>



                                            </select>



                                        </div>



                                        <div class="form-group">



                                            <label for="so_adresse">Adresse</label>



                                            <input type="text" id="so_adresse" name="adresse" class="form-input" placeholder="Adresse compl?te">



                                        </div>



                                        <div class="form-group">



                                            <label for="so_fixe">Fixe</label>



                                            <input type="tel" id="so_fixe" name="fixe" class="form-input" placeholder="05 XX XX XX XX">



                                        </div>



                                        <div class="form-group">



                                            <label for="so_email">E-mail</label>



                                            <input type="email" id="so_email" name="email" class="form-input" placeholder="contact@societe.ma">



                                        </div>



                                    </div>



                                    <div class="so-inline-row so-inline-row-3">



                                        <div class="form-group">



                                            <label for="so_rc">RC</label>



                                            <input type="text" id="so_rc" name="rc" class="form-input" placeholder="Registre de commerce">



                                        </div>



                                        <div class="form-group">



                                            <label for="so_ice">ICE</label>



                                            <input type="text" id="so_ice" name="ice" class="form-input" placeholder="ICE">



                                        </div>



                                        <div class="form-group">



                                            <label for="so_if">IF</label>



                                            <input type="text" id="so_if" name="identifiant_fiscal" class="form-input" placeholder="Identifiant fiscal">



                                        </div>



                                        <div class="form-group">



                                            <label for="so_cnss">CNSS</label>



                                            <input type="text" id="so_cnss" name="cnss" class="form-input" placeholder="N? CNSS">



                                        </div>



                                    </div>



                                    <div class="so-inline-row so-inline-row-4">



                                        <div class="form-group">



                                            <label for="so_patente">Patente</label>



                                            <input type="text" id="so_patente" name="patente" class="form-input" placeholder="N? patente">



                                        </div>



                                        <div class="form-group">



                                            <label for="so_rib">RIB Bancaire</label>



                                            <input type="text" id="so_rib" name="rib" class="form-input rib-input" placeholder="000 000 0000000000000000 00" maxlength="27">



                                        </div>



                                    </div>



                                </div>



                                <div class="societe-photo-panel">



                                    <label for="so_photo_file">Importer Photo</label>



                                    <div class="societe-photo-preview" id="so_photo_preview">



                                        <span class="societe-photo-placeholder" id="so_photo_placeholder">Aucune photo</span>



                                        <img id="so_photo_img" alt="Logo soci?t?" class="hidden">



                                    </div>



                                    <div class="produit-photo-actions">



                                        <input type="file" id="so_photo_file" accept="image/*" class="hidden">



                                        <button type="button" class="btn-photo" id="soPhotoPickBtn">



                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>



                                            Importer Photo



                                        </button>



                                        <button type="button" class="btn-photo btn-photo-danger hidden" id="soPhotoRemoveBtn">



                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>



                                            Supprimer



                                        </button>



                                    </div>



                                </div>



                            </div>
                                <div class="so-habillage-block">
                                    <button type="button" class="btn-photo so-habillage-toggle" id="soHabillageBtn">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"/><polygon points="10 9 16 12 10 15 10 9"/></svg>
                                        Habillage
                                    </button>
                                    <div class="so-habillage-panel hidden" id="soHabillagePanel">
                                        <div class="so-habillage-row">
                                            <label for="so_habillage_file">Importer une vidéo</label>
                                            <input type="file" id="so_habillage_file" class="form-input" accept="video/*">
                                        </div>
                                        <div class="so-habillage-row">
                                            <label for="so_habillage_url">Ou lien URL (YouTube, Vimeo, MP4…)</label>
                                            <input type="url" id="so_habillage_url" class="form-input" placeholder="https://…">
                                        </div>
                                        <div class="so-habillage-actions">
                                            <button type="button" class="btn-photo" id="soHabillageApplyUrlBtn">Utiliser l’URL</button>
                                            <button type="button" class="btn-photo btn-photo-danger" id="soHabillageClearBtn">Effacer</button>
                                            <span class="so-habillage-status" id="soHabillageStatus">Aucun habillage</span>
                                        </div>
                                    </div>
                                </div>





                            <div class="form-actions">



                                <button type="button" class="btn-form btn-form-secondary" id="fermerSocieteForm">Fermer</button>



                                <button type="submit" class="btn-form btn-form-primary" id="validerSocieteBtn">Valider</button>



                            </div>



                        </form>



                    </div>



                </div>



                <div id="societeConsultPanel" class="hidden">



                    <div class="list-toolbar no-print-societe">



                        <h2 class="list-toolbar-title">Fiche Société</h2>



                        <div class="list-toolbar-actions">



                            <button type="button" class="btn-list btn-list-print" id="printSocieteBtn">



                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>



                                Imprimer



                            </button>



                            <button type="button" class="btn-list btn-list-add btn-list-modify" id="modifierSocieteBtn">



                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>



                                Modifier



                            </button>



                        </div>



                    </div>



                    <div id="societePrintArea">



                        <div class="societe-consult-card" id="societeConsultCard">



                            <div class="societe-consult-header">



                                <div class="societe-consult-logo placeholder" id="soConsultLogoEmpty">Logo</div>



                                <img class="societe-consult-logo hidden" id="soConsultLogoImg" alt="Logo soci?t?">



                                <div>



                                    <h3 class="societe-consult-title" id="soConsultNom">?</h3>



                                    <div class="societe-consult-sub" id="soConsultGerant">?</div>



                                </div>



                            </div>



                            <div class="societe-consult-grid">



                                <div class="societe-consult-item"><span class="societe-consult-label">Contact</span><div class="societe-consult-value" id="soConsultContact">?</div></div>



                                <div class="societe-consult-item"><span class="societe-consult-label">Ville</span><div class="societe-consult-value" id="soConsultVille">?</div></div>



                                <div class="societe-consult-item"><span class="societe-consult-label">Fixe</span><div class="societe-consult-value" id="soConsultFixe">?</div></div>



                                <div class="societe-consult-item full"><span class="societe-consult-label">Adresse</span><div class="societe-consult-value" id="soConsultAdresse">?</div></div>



                                <div class="societe-consult-item"><span class="societe-consult-label">E-mail</span><div class="societe-consult-value" id="soConsultEmail">?</div></div>



                                <div class="societe-consult-item"><span class="societe-consult-label">RC</span><div class="societe-consult-value" id="soConsultRc">?</div></div>



                                <div class="societe-consult-item"><span class="societe-consult-label">ICE</span><div class="societe-consult-value" id="soConsultIce">?</div></div>



                                <div class="societe-consult-item"><span class="societe-consult-label">IF</span><div class="societe-consult-value" id="soConsultIf">?</div></div>



                                <div class="societe-consult-item"><span class="societe-consult-label">CNSS</span><div class="societe-consult-value" id="soConsultCnss">?</div></div>



                                <div class="societe-consult-item"><span class="societe-consult-label">Patente</span><div class="societe-consult-value" id="soConsultPatente">?</div></div>



                                <div class="societe-consult-item full"><span class="societe-consult-label">RIB Bancaire</span><div class="societe-consult-value" id="soConsultRib">?</div></div>
                                <div class="societe-consult-item full"><span class="societe-consult-label">Habillage</span><div class="societe-consult-value" id="soConsultHabillage">—</div></div>




                            </div>



                        </div>



                    </div>



                </div>



            </div>



            {{-- Trésorerie Matériels --}}



            <div id="tresorerieMaterielsView" class="saisie-panel hidden">



                <div id="materielsFormPanel" class="hidden">



                    <div class="saisie-card">



                        <div class="saisie-card-header">



                            <div>



                                <h2>Trésorerie Matériels</h2>



                                <span>Barre de saisie</span>



                            </div>



                        </div>



                        <form class="saisie-form" id="ficheMaterielForm" novalidate>



                            <div class="tm-form-layout">



                                <div class="tm-form-fields">



                                    <div class="tm-inline-row tm-inline-row-1">



                                        <div class="form-group">



                                            <label for="tm_date">Date</label>



                                            <input type="date" id="tm_date" name="date" class="form-input" required>



                                        </div>



                                        <div class="form-group">



                                            <label for="tm_ref">Réf</label>



                                            <input type="text" id="tm_ref" name="ref" class="form-input" placeholder="Référence" required>



                                        </div>



                                        <div class="form-group">



                                            <label for="tm_designation">Désignation</label>



                                            <input type="text" id="tm_designation" name="designation" class="form-input" placeholder="Désignation du matériel" required>



                                        </div>



                                    </div>



                                    <div class="tm-inline-row tm-inline-row-2">



                                        <div class="form-group">



                                            <label for="tm_fournisseur">Fournisseur</label>



                                            <input type="text" id="tm_fournisseur" name="fournisseur" class="form-input" list="tm_fournisseur_list" placeholder="Nom fournisseur">



                                            <datalist id="tm_fournisseur_list"></datalist>



                                        </div>



                                        <div class="form-group">



                                            <label for="tm_prix_achat">Prix d'Achat</label>



                                            <input type="number" id="tm_prix_achat" name="prix_achat" class="form-input money-input" step="0.01" min="0" placeholder="0.00">



                                        </div>



                                        <div class="form-group">



                                            <label for="tm_douane">Douane</label>



                                            <input type="number" id="tm_douane" name="douane" class="form-input money-input" step="0.01" min="0" placeholder="0.00">



                                        </div>



                                        <div class="form-group">



                                            <label for="tm_date_travail">Date mise en Travail</label>



                                            <input type="date" id="tm_date_travail" name="date_travail" class="form-input">



                                        </div>



                                    </div>



                                </div>



                                <div class="tm-photo-panel">



                                    <label for="tm_photo_file">Importer Photo</label>



                                    <div class="tm-photo-preview" id="tm_photo_preview">



                                        <span class="tm-photo-placeholder" id="tm_photo_placeholder">Aucune photo</span>



                                        <img id="tm_photo_img" alt="Photo mat?riel" class="hidden">



                                    </div>



                                    <div class="produit-photo-actions">



                                        <input type="file" id="tm_photo_file" accept="image/*" class="hidden">



                                        <button type="button" class="btn-photo" id="tmPhotoPickBtn">



                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>



                                            Importer Photo



                                        </button>



                                        <button type="button" class="btn-photo btn-photo-danger hidden" id="tmPhotoRemoveBtn">



                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>



                                            Supprimer



                                        </button>



                                    </div>



                                </div>



                            </div>



                            <div class="form-actions">



                                <button type="button" class="btn-form btn-form-secondary" id="fermerMaterielForm">Fermer</button>



                                <button type="button" class="btn-form btn-form-secondary" id="ajouterMaterielBtn">Ajouter</button>



                                <button type="submit" class="btn-form btn-form-primary" id="validerMaterielBtn">Valider</button>



                            </div>



                        </form>



                    </div>



                </div>



                <div id="materielsConsultPanel">



                    <div class="list-toolbar no-print-materiels">



                        <h2 class="list-toolbar-title">Trésorerie Matériels</h2>



                        <div class="list-toolbar-actions">



                            <button type="button" class="btn-list btn-list-print" id="printMaterielsBtn">



                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>



                                Imprimer



                            </button>



                            <button type="button" class="btn-list btn-list-add" id="enregistrerMaterielBtn">



                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>



                                Enregistrer



                            </button>



                        </div>



                    </div>



                    <div id="materielsPrintArea">



                        <div class="fournisseur-table-wrap">



                            <table class="fournisseur-table materiels-table" id="materielsTable">



                                <thead>



                                    <tr>



                                        <th>Date</th>



                                        <th>Réf</th>



                                        <th>Photo</th>



                                        <th>Désignation</th>



                                        <th>Fournisseur</th>



                                        <th>Prix d'Achat</th>



                                        <th>Douane</th>



                                        <th>Date mise en Travail</th>



                                        <th class="col-actions no-print-materiels">Actions</th>



                                    </tr>



                                </thead>



                                <tbody id="materielsTableBody">



                                    <tr><td colspan="9" class="fournisseur-empty">Aucun matériel enregistré</td></tr>



                                </tbody>



                            </table>



                        </div>



                    </div>



                </div>



            </div>



            {{-- Utilisateur --}}





            {{-- Réglement Achat --}}

            <div id="reglementAchatsView" class="saisie-panel hidden">

                <div id="reglementFormPanel" class="hidden">

                    <div class="saisie-card">

                        <div class="saisie-card-header">

                            <div>

                                <h2 id="reglementFormTitle">Réglement Achat</h2>

                                <span id="reglementFormSubtitle">Barre de saisie</span>

                            </div>

                        </div>

                        <form class="saisie-form" id="ficheReglementForm" novalidate>

                            <div class="rg-form-layout">

                                <div>

                                    <div class="rg-inline-row rg-inline-row-1">

                                        <div class="form-group">

                                            <label for="rg_date">Date</label>

                                            <input type="date" id="rg_date" class="form-input" required>

                                        </div>

                                        <div class="form-group">

                                            <label for="rg_ref">Réf°</label>

                                            <input type="text" id="rg_ref" class="form-input readonly" readonly>

                                        </div>

                                        <div class="form-group">

                                            <label for="rg_bon">Fournisseur</label>

                                            <select id="rg_bon" class="form-select" required>

                                                <option value="">— Bons non soldés —</option>

                                            </select>

                                        </div>

                                    </div>

                                    <div class="rg-inline-row rg-inline-row-2">

                                        <div class="form-group">

                                            <label for="rg_type">Type Rég</label>

                                            <select id="rg_type" class="form-select" required>

                                                <option value="">— Sélectionner —</option>

                                                <option value="Esp">Esp — Espèces</option>

                                                <option value="Chq">Chq — Chèque</option>

                                                <option value="Eff">Eff — Effet</option>

                                                <option value="Vir">Vir — Virement</option>

                                                <option value="Vers">Vers — Versement</option>

                                            </select>

                                        </div>

                                        <div class="form-group">

                                            <label for="rg_num">N° Rég</label>

                                            <input type="text" id="rg_num" class="form-input" placeholder="N° règlement">

                                        </div>

                                        <div class="form-group">

                                            <label for="rg_banque">Banque</label>

                                            <select id="rg_banque" class="form-select">

                                                <option value="">— Sélectionner —</option>

                                                <option value="Attijariwafa Bank">Attijariwafa Bank</option>

                                                <option value="Banque Populaire">Banque Populaire</option>

                                                <option value="BMCE Bank Of Africa">BMCE Bank Of Africa</option>

                                                <option value="CIH Bank">CIH Bank</option>

                                                <option value="Crédit Agricole">Crédit Agricole</option>

                                                <option value="Société Générale">Société Générale</option>

                                                <option value="CFG Bank">CFG Bank</option>

                                                <option value="Al Barid Bank">Al Barid Bank</option>

                                            </select>

                                        </div>

                                        <div class="form-group">

                                            <label for="rg_tire">Tiré</label>

                                            <input type="text" id="rg_tire" class="form-input" placeholder="Nom du tiré">

                                        </div>

                                    </div>

                                    <div class="rg-inline-row rg-inline-row-3">

                                        <div class="form-group">

                                            <label for="rg_montant_reg">Montant Rég</label>

                                            <input type="number" id="rg_montant_reg" class="form-input money-input" step="0.01" min="0" placeholder="0.00">

                                        </div>

                                        <div class="form-group">

                                            <label for="rg_date_decaiss">Date Décaiss</label>

                                            <input type="date" id="rg_date_decaiss" class="form-input">

                                        </div>

                                    </div>

                                    <input type="hidden" id="rg_bon_num" value="">

                                    <input type="hidden" id="rg_fournisseur" value="">

                                    <input type="hidden" id="rg_montant_bon" value="">

                                </div>

                                <div class="rg-photo-panel">

                                    <label for="rg_photo_file">Importer Photo</label>

                                    <div class="rg-photo-preview" id="rg_photo_preview">

                                        <span class="rg-photo-placeholder" id="rg_photo_placeholder">Aucune photo</span>

                                        <img id="rg_photo_img" alt="Photo règlement" class="hidden">

                                    </div>

                                    <div class="rg-photo-actions">

                                        <input type="file" id="rg_photo_file" accept="image/*" class="hidden">

                                        <button type="button" class="btn-photo" id="rgPhotoPickBtn">

                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>

                                            Importer Photo

                                        </button>

                                    </div>

                                </div>

                            </div>

                            <div class="form-actions">

                                <button type="button" class="btn-form btn-form-secondary" id="fermerReglementForm">Fermer</button>

                                <button type="button" class="btn-form btn-form-plus" id="ajouterAutreReglementBtn" title="Ajouter un réglement" aria-label="Ajouter un réglement">

                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>

                                </button>

                                <button type="submit" class="btn-form btn-form-primary" id="validerReglementBtn">Valider</button>

                            </div>

                        </form>

                    </div>

                </div>



                <div id="reglementConsultPanel">

                    <div class="list-toolbar no-print-reglement">

                        <h2 class="list-toolbar-title">Réglement Achat</h2>

                        <div class="list-toolbar-actions">

                            <button type="button" class="btn-list btn-list-add" id="ajouterReglementBtn">

                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>

                                Ajouter

                            </button>

                            <button type="button" class="btn-list btn-list-print" id="fermerReglementConsultBtn">

                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>

                                Fermer

                            </button>

                        </div>

                    </div>

                    <div id="reglementPrintArea">

                        <div class="fournisseur-table-wrap">

                            <table class="fournisseur-table" id="reglementsTable">

                                <thead>

                                    <tr>

                                        <th>Date</th>

                                        <th>Réf°</th>

                                        <th>Fournisseur</th>

                                        <th>Bn°</th>

                                        <th>Montant</th>

                                        <th>Type Rég</th>

                                        <th>N° Rég</th>

                                        <th>Banque</th>

                                        <th>Tiré</th>

                                        <th>Montant Rég</th>

                                        <th>Date Décaiss</th>

                                        <th class="col-actions no-print-reglement">Actions</th>

                                    </tr>

                                </thead>

                                <tbody id="reglementsTableBody">

                                    <tr><td colspan="12" class="fournisseur-empty">Aucun réglement enregistré</td></tr>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>



            <div id="utilisateurView" class="saisie-panel hidden">



                <div id="utilisateurFormPanel" class="hidden">



                    <div class="saisie-card">



                        <div class="saisie-card-header">



                            <div>



                                <h2 id="utilisateurFormTitle">Utilisateur</h2>



                                <span id="utilisateurFormSubtitle">Barre de saisie</span>



                            </div>



                        </div>



                        <form class="saisie-form" id="ficheUtilisateurForm" novalidate>



                            <div class="user-form-grid">



                                <div class="form-group">



                                    <label for="us_date">Date</label>



                                    <input type="date" id="us_date" name="date" class="form-input readonly" readonly>



                                </div>



                                <div class="form-group">



                                    <label for="us_id">ID</label>



                                    <input type="text" id="us_id" name="id" class="form-input readonly" readonly>



                                </div>



                                <div class="form-group">



                                    <label for="us_nom">Nom Complet</label>



                                    <input type="text" id="us_nom" name="nom" class="form-input" placeholder="Nom et prénom" required>



                                </div>



                                <div class="form-group">



                                    <label for="us_statut">Statut</label>



                                    <select id="us_statut" name="statut" class="form-select" required>



                                        <option value="">? Sélectionner ?</option>



                                        <option value="Admin">Admin</option>



                                        <option value="Commercial">Commercial</option>



                                        <option value="Facturation">Facturation</option>



                                        <option value="Magasinier">Magasinier</option>



                                    </select>



                                </div>



                            </div>



                            <div class="user-form-grid-2">



                                <div class="form-group">



                                    <label for="us_login">Login</label>



                                    <input type="text" id="us_login" name="login" class="form-input" placeholder="Identifiant" required>



                                </div>



                                <div class="form-group">



                                    <label for="us_password">Mot de Passe</label>



                                    <input type="text" id="us_password" name="password" class="form-input" placeholder="Mot de passe" required>



                                </div>



                            </div>



                            <div class="user-auth-box">



                                <div class="user-auth-title">



                                    <h3>Autorisations</h3>



                                    <label class="user-auth-item"><input type="checkbox" id="us_auth_all"> Tout sélectionner</label>



                                </div>



                                <div id="utilisateurAuthTree"></div>



                            </div>



                            <div class="form-actions">



                                <button type="button" class="btn-form btn-form-secondary" id="fermerUtilisateurForm">Fermer</button>



                                <button type="submit" class="btn-form btn-form-primary" id="validerUtilisateurBtn">Valider</button>



                            </div>



                        </form>



                    </div>



                </div>



                <div id="utilisateurConsultPanel">



                    <div class="list-toolbar no-print-utilisateur">



                        <h2 class="list-toolbar-title">Utilisateurs</h2>



                        <div class="list-toolbar-actions">



                            <button type="button" class="btn-list btn-list-add" id="ajouterUtilisateurBtn">



                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>



                                Ajouter



                            </button>



                            <button type="button" class="btn-list btn-list-print" id="fermerUtilisateurConsultBtn">



                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>



                                Fermer



                            </button>



                        </div>



                    </div>



                    <div id="utilisateurPrintArea">



                        <div class="fournisseur-table-wrap">



                            <table class="fournisseur-table" id="utilisateursTable">



                                <thead>



                                    <tr>



                                        <th>Date</th>



                                        <th>ID</th>



                                        <th>Statut</th>



                                        <th>Login</th>



                                        <th>Mot de passe</th>



                                        <th>Autorisations</th>



                                        <th class="col-actions no-print-utilisateur">Actions</th>



                                    </tr>



                                </thead>



                                <tbody id="utilisateursTableBody">



                                    <tr><td colspan="7" class="fournisseur-empty">Aucun utilisateur enregistré</td></tr>



                                </tbody>



                            </table>



                        </div>



                    </div>



                </div>



            </div>



        </main>



        <footer class="page-footer">



            <span>2026-A2s---Tous Droits Réservés</span>



            <ul class="footer-links">



                <li><a href="#">Journal de s?curit?</a></li>



                <li><a href="#">Statut des API</a></li>



                <li><a href="#">Mentions l?gales</a></li>



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



    {{-- D?tail commande (Voir) --}}



    <div class="visit-card-overlay" id="commandeDetailModal">



        <div class="visit-card" role="dialog" aria-modal="true" aria-labelledby="commandeDetailTitle" style="max-width:720px;">



            <div class="visit-card-header">



                <h2 id="commandeDetailTitle">D?tail bon d'achat</h2>



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



        const ficheProduitView = document.getElementById('ficheProduitView');



        const ficheSocieteView = document.getElementById('ficheSocieteView');



        const tresorerieMaterielsView = document.getElementById('tresorerieMaterielsView');



        const reglementAchatsView = document.getElementById('reglementAchatsView');

        const reglementFormPanel = document.getElementById('reglementFormPanel');

        const reglementConsultPanel = document.getElementById('reglementConsultPanel');

        const ficheReglementForm = document.getElementById('ficheReglementForm');

        const reglementsTableBody = document.getElementById('reglementsTableBody');

        const utilisateurView = document.getElementById('utilisateurView');



        const utilisateurFormPanel = document.getElementById('utilisateurFormPanel');



        const utilisateurConsultPanel = document.getElementById('utilisateurConsultPanel');



        const ficheUtilisateurForm = document.getElementById('ficheUtilisateurForm');



        const utilisateursTableBody = document.getElementById('utilisateursTableBody');



        const materielsFormPanel = document.getElementById('materielsFormPanel');



        const materielsConsultPanel = document.getElementById('materielsConsultPanel');



        const ficheMaterielForm = document.getElementById('ficheMaterielForm');



        const materielsTableBody = document.getElementById('materielsTableBody');



        const societeFormPanel = document.getElementById('societeFormPanel');



        const societeConsultPanel = document.getElementById('societeConsultPanel');



        const ficheSocieteForm = document.getElementById('ficheSocieteForm');



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



        let viewingFournisseur = false;



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



        // Solde affich? = solde initial saisi + total des achats non pay?s



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



        function setFournisseurFormReadonly(readonly) {



            viewingFournisseur = !!readonly;



            if (!ficheFournisseurForm) return;



            ficheFournisseurForm.querySelectorAll('input, select, textarea').forEach(el => {



                if (el.id === 'fr_id') {



                    el.readOnly = true;



                    return;



                }



                if (el.tagName === 'SELECT') el.disabled = readonly;



                else el.readOnly = readonly;



            });



            const validerBtn = document.getElementById('validerFournisseurBtn');



            if (validerBtn) validerBtn.classList.toggle('hidden', readonly);



            const cancelBtn = document.getElementById('cancelFournisseurForm');



            if (cancelBtn) cancelBtn.textContent = readonly ? 'Retour' : 'Annuler';



        }



        function resetFournisseurFormMode() {



            editingFournisseurId = null;



            setFournisseurFormReadonly(false);



            const title = document.getElementById('fournisseurFormTitle');



            const subtitle = document.getElementById('fournisseurFormSubtitle');



            const saveBtn = document.getElementById('saveFournisseurBtn');



            if (title) title.textContent = 'Fiche Fournisseur';



            if (subtitle) subtitle.textContent = 'Barre de saisie';



            if (saveBtn) saveBtn.textContent = 'Enregistrer';



        }



        function populateFournisseurForm(f) {



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



        }



        function showFournisseurForm(reset = false) {



            document.body.classList.remove('table-list-active');



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



        function voirFournisseur(code) {



            const f = fournisseurs.find(x => x.id === code);



            if (!f) return;



            editingFournisseurId = null;



            showFournisseurForm(false);



            populateFournisseurForm(f);



            setFournisseurFormReadonly(true);



            const title = document.getElementById('fournisseurFormTitle');



            const subtitle = document.getElementById('fournisseurFormSubtitle');



            if (title) title.textContent = 'Consulter Fournisseur';



            if (subtitle) subtitle.textContent = f.id;



        }



        function editFournisseur(code) {



            const f = fournisseurs.find(x => x.id === code);



            if (!f) return;



            editingFournisseurId = code;



            showFournisseurForm(false);



            populateFournisseurForm(f);



            setFournisseurFormReadonly(false);



            const title = document.getElementById('fournisseurFormTitle');



            const subtitle = document.getElementById('fournisseurFormSubtitle');



            const saveBtn = document.getElementById('saveFournisseurBtn');



            if (title) title.textContent = 'Modifier Fournisseur';



            if (subtitle) subtitle.textContent = f.id;



            if (saveBtn) saveBtn.textContent = 'Mettre ? jour';



        }



        function exportFournisseurPdf(code) {



            const f = fournisseurs.find(x => x.id === code);



            if (!f) return;



            if (!window.jspdf) {



                alert('Biblioth?que PDF non charg?e.');



                return;



            }



            applyFournisseurSoldesFromCommandes();



            const { jsPDF } = window.jspdf;



            const doc = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });



            doc.setFontSize(16);



            doc.setTextColor(0, 51, 38);



            doc.text('SWEET AUSTRIA ? Fiche Fournisseur', 14, 18);



            doc.setFontSize(9);



            doc.setTextColor(107, 107, 104);



            doc.text('?dit? le ' + new Date().toLocaleDateString('fr-FR'), 14, 24);



            doc.autoTable({



                startY: 30,



                head: [['Champ', 'Valeur']],



                body: [



                    ['ID', f.id || ''],



                    ['Nom', f.nom || ''],



                    ['Type', f.type || ''],



                    ['Ville', f.ville || ''],



                    ['Adresse', f.adresse || ''],



                    ['T?l?phone', f.telephone || ''],



                    ['Fixe', f.fixe || ''],



                    ['E-mail', f.email || ''],



                    ['Statut', f.statut || ''],



                    ['Type r?glement', f.type_paiement || ''],



                    ['Banque', f.banque || ''],



                    ['RIB', f.rib || ''],



                    ['Solde', formatSolde(computeFournisseurSolde(f))],



                ],



                styles: { fontSize: 10, cellPadding: 3 },



                headStyles: { fillColor: [0, 51, 38], textColor: 255, fontStyle: 'bold' },



                columnStyles: { 0: { fontStyle: 'bold', cellWidth: 45 } },



                margin: { left: 14, right: 14 },



            });



            doc.save('fournisseur-' + (f.id || 'fiche') + '-sweet-austria.pdf');



        }



        async function deleteFournisseur(code) {



            const f = fournisseurs.find(x => x.id === code);



            if (!f) return;



            if (!confirm('Supprimer le fournisseur ? ' + f.nom + ' ? (' + code + ') ?')) return;



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



                    toast.textContent = 'Fournisseur ' + code + ' supprim?';



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



            document.body.classList.add('table-list-active');



            renderFournisseursTable();



        }



        function renderFournisseursTable() {



            if (!fournisseursTableBody) return;



            applyFournisseurSoldesFromCommandes();



            if (fournisseurs.length === 0) {



                fournisseursTableBody.innerHTML = '<tr><td colspan="13" class="fournisseur-empty">Aucun fournisseur enregistr?</td></tr>';



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



                    <td class="col-actions" onclick="event.stopPropagation()">



                        <span class="col-actions-wrap">



                            <button type="button" class="btn-icon-row btn-icon-view" data-view="${f.id}" title="Voir" aria-label="Voir">



                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><circle cx="12" cy="12" r="3" stroke-width="2"/></svg>



                            </button>



                            <button type="button" class="btn-icon-row btn-icon-edit" data-edit="${f.id}" title="Modifier" aria-label="Modifier">



                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>



                            </button>



                            <button type="button" class="btn-icon-row btn-icon-delete" data-delete="${f.id}" title="Supprimer" aria-label="Supprimer">



                                <svg viewBox="0 0 24 24" aria-hidden="true"><polyline points="3 6 5 6 21 6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>



                            </button>



                            <button type="button" class="btn-icon-row btn-icon-pdf" data-pdf="${f.id}" title="PDF" aria-label="PDF">



                                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><polyline points="14 2 14 8 20 8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M9 13h6M9 17h6M9 9h1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>



                            </button>



                        </span>



                    </td>



                </tr>



            `).join('');



            fournisseursTableBody.querySelectorAll('[data-view]').forEach(btn => {



                btn.addEventListener('click', () => voirFournisseur(btn.dataset.view));



            });



            fournisseursTableBody.querySelectorAll('[data-edit]').forEach(btn => {



                btn.addEventListener('click', () => editFournisseur(btn.dataset.edit));



            });



            fournisseursTableBody.querySelectorAll('[data-delete]').forEach(btn => {



                btn.addEventListener('click', () => deleteFournisseur(btn.dataset.delete));



            });



            fournisseursTableBody.querySelectorAll('[data-pdf]').forEach(btn => {



                btn.addEventListener('click', () => exportFournisseurPdf(btn.dataset.pdf));



            });



        }



        function escHtml(str) {



            if (!str) return '?';



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



            if (['achats', 'fiche-fournisseur', 'fiche-produit', 'fiche-societe', 'tresorerie-materiels', 'utilisateur', 'reglement-achats'].includes(viewId)) {



                document.querySelectorAll('.nav-subitem.active').forEach(el => el.classList.remove('active'));



                document.querySelector(`.nav-subitem[data-view="${viewId}"]`)?.classList.add('active');



            }



        }



        function showAppView(viewId, options = {}) {



            if (viewId !== 'fiche-fournisseur') {



                document.body.classList.remove('table-list-active');



            }



            syncNavActive(viewId);



            if (dashboardView) dashboardView.classList.toggle('hidden', viewId !== 'dashboard');



            if (ficheFournisseurView) ficheFournisseurView.classList.toggle('hidden', viewId !== 'fiche-fournisseur');



            if (achatsView) achatsView.classList.toggle('hidden', viewId !== 'achats');



            if (ficheProduitView) ficheProduitView.classList.toggle('hidden', viewId !== 'fiche-produit');



            if (ficheSocieteView) ficheSocieteView.classList.toggle('hidden', viewId !== 'fiche-societe');



            if (tresorerieMaterielsView) tresorerieMaterielsView.classList.toggle('hidden', viewId !== 'tresorerie-materiels');



            if (utilisateurView) utilisateurView.classList.toggle('hidden', viewId !== 'utilisateur');

            if (reglementAchatsView) reglementAchatsView.classList.toggle('hidden', viewId !== 'reglement-achats');



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



            if (viewId === 'fiche-produit') {



                Promise.all([loadProduits(), loadUnitesMesure()]).then(() => {



                    refreshLookupSelects();



                    showProduitList();



                });



            }



            if (viewId === 'fiche-societe') {



                openFicheSociete();



            }



            if (viewId === 'tresorerie-materiels') {



                openTresorerieMateriels();



            }



            if (viewId === 'utilisateur') {



                openUtilisateur();



            }



            if (viewId === 'reglement-achats') {



                openReglementAchats();



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



            villes: ['Casablanca', 'F?s', 'Rabat', 'Nador', 'Tanger', 'Marrakech', 'Taza', 'Oujda', 'Agadir', 'Mekn?s', 'K?nitra', 'El Jadida'],



            categories: [



                'Fruits ? coque', 'Fruits s?ch?s', 'Cacahu?tes et d?riv?s', 'Graines alimentaires',



                'Fruits secs enrob?s et confiseries', 'Produits Ramadan et F?tes', '?pices', 'Confiserie', 'Divers'



            ],



            familles: ['Noix', 'Amandes', 'Dattes', 'Figues', 'Abricots', 'Raisins', 'Graines', 'M?langes', 'Confiserie', 'Divers'],



            designations: [



                'Amandes d?cortiqu?es', 'Noix de cajou', 'Dattes Medjool', 'Figues s?ch?es', 'Abricots secs',



                'Raisins secs', 'Pistaches', 'Cacahu?tes grill?es', 'M?lange fruits secs', 'Noix de coco r?p?e'



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



            el.innerHTML = '<option value="">? S?lectionner ?</option>' +



                merged.map(v => `<option value="${escapeOptionAttr(v)}"${v === selectedValue ? ' selected' : ''}>${escapeOptionText(v)}</option>`).join('');



        }



        function refreshLookupSelects(selected = {}) {



            populateLookupSelect('fr_ville', LOOKUP_LISTS.villes, selected.fr_ville || '');



            populateLookupSelect('so_ville', LOOKUP_LISTS.villes, selected.so_ville || '');



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



            if (val === null || val === undefined || val === '') return '?';



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



            if (!code) return '?';



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



                alert('Veuillez s?lectionner une image valide.');



                return;



            }



            if (file.size > 5 * 1024 * 1024) {



                alert('La photo ne doit pas d?passer 5 Mo.');



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



            select.innerHTML = '<option value="">? S?lectionner ?</option>' +



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



                produitsTableBody.innerHTML = '<tr><td colspan="12" class="produits-empty">Aucun produit enregistr?</td></tr>';



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



                    <td>${escHtml(p.type) || '?'}</td>



                    <td>${escHtml(p.categorie) || '?'}</td>



                    <td>${escHtml(p.famille) || '?'}</td>



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



            if (saveBtn) saveBtn.textContent = 'Mettre ? jour';



        }



        async function deleteProduit(ref) {



            const p = produits.find(x => x.ref === ref);



            if (!p) return;



            if (!confirm('Supprimer le produit ? ' + p.designation + ' ? (' + ref + ') ?')) return;



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



                    toast.textContent = 'Produit ' + ref + ' supprim?';



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



                alert('Veuillez s?lectionner la d?signation.');



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



                saveBtn.textContent = isEdit ? 'Mise ? jour?' : 'Enregistrement?';



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



                        ? 'Produit ' + data.produit.ref + ' modifi?'



                        : 'Produit ' + data.produit.ref + ' enregistr?';



                    toast.classList.add('show');



                    setTimeout(() => toast.classList.remove('show'), 2800);



                }



            } catch (err) {



                console.error(err);



                alert('Impossible d\'enregistrer le produit.');



            } finally {



                if (saveBtn) {



                    saveBtn.disabled = false;



                    saveBtn.textContent = isEdit ? 'Mettre ? jour' : 'Enregistrer';



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



            if (!iso) return '?';



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



                printDate.textContent = '?dit? le ' + new Date().toLocaleDateString('fr-FR', {



                    day: '2-digit', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'



                });



            }



            if (!tbody) return;



            if (commandesAchats.length === 0) {



                tbody.innerHTML = '<tr><td colspan="10" class="achats-commandes-empty">Aucun bon d\'achat saisi</td></tr>';



                return;



            }



            tbody.innerHTML = commandesAchats.map((c, i) => {



                const selected = selectedCommandeIndex === i ? ' selected' : '';



                const qte = commandeTotalQte(c);



                return `<tr class="${selected}" data-commande-index="${i}">



                    <td><strong>${escHtml(c.bon)}</strong></td>



                    <td>${formatDateFr(c.date_cmd)}</td>



                    <td>${escHtml(c.code_fournisseur) || '?'}</td>



                    <td class="cmd-col-nom" title="${escHtml(c.nom_fournisseur) || ''}">${escHtml(c.nom_fournisseur) || '?'}</td>



                    <td>${escHtml(commandeVille(c)) || '?'}</td>



                    <td>${qte.toLocaleString('fr-FR')}</td>



                    <td><strong>${formatMoney(c.total || 0)}</strong></td>



                    <td>${escHtml(c.type_reglement) || '?'}</td>



                    <td>${formatDateFr(c.echeance)}</td>



                    <td class="col-actions col-actions-cmd no-print-cmd" onclick="event.stopPropagation()">



                        <span class="cmd-actions-wrap">



                            <button type="button" class="btn-row btn-row-edit" data-regler-commande="${i}" ${isCommandePayee(c) ? 'style="display:none"' : ''}>R?gler</button>



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



            renderCommandesAchatsTableInto('commandesListTableBodyAchats', 'commandesPrintDateAchats');



        }



        function voirCommandeDetail(index) {



            const c = commandesAchats[index];



            if (!c) return;



            const modal = document.getElementById('commandeDetailModal');



            const body = document.getElementById('commandeDetailBody');



            const title = document.getElementById('commandeDetailTitle');



            if (!modal || !body) return;



            if (title) title.textContent = 'Bon d\'Achat ' + (c.bon || '');



            const lignesHtml = (c.lignes || []).length === 0



                ? '<p style="color:#6B6B68;font-size:13px;">Aucune ligne article.</p>'



                : `<table class="achats-lines-table" style="min-width:100%;margin-top:12px;">



                    <thead><tr><th>R?f</th><th>Code-barres</th><th>D?signation</th><th>Qt?</th><th>Mesure</th><th>Prix U</th><th>Sous-Total</th></tr></thead>



                    <tbody>${(c.lignes || []).map(l => `<tr>



                        <td>${escHtml(l.ref) || '?'}</td>



                        <td>${escHtml(l.code_barre) || '?'}</td>



                        <td>${escHtml(l.designation)}</td>



                        <td>${(parseFloat(l.quantite) || 0).toLocaleString('fr-FR')}</td>



                        <td>${escHtml(l.mesure_libelle || l.mesure) || '?'}</td>



                        <td>${formatMoney(l.prix_u)}</td>



                        <td><strong>${formatMoney(l.sous_total)}</strong></td>



                    </tr>`).join('')}</tbody>



                </table>`;



            body.innerHTML = `



                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px 28px;font-size:13px;margin-bottom:12px;">



                    <div><span style="color:#6B6B68;font-size:11px;text-transform:uppercase;">Date Cmd</span><br><strong>${formatDateFr(c.date_cmd)}</strong></div>



                    <div><span style="color:#6B6B68;font-size:11px;text-transform:uppercase;">Code</span><br><strong>${escHtml(c.code_fournisseur) || '?'}</strong></div>



                    <div><span style="color:#6B6B68;font-size:11px;text-transform:uppercase;">Fournisseur</span><br><strong>${escHtml(c.nom_fournisseur) || '?'}</strong></div>



                    <div><span style="color:#6B6B68;font-size:11px;text-transform:uppercase;">Ville</span><br><strong>${escHtml(commandeVille(c)) || '?'}</strong></div>



                    <div><span style="color:#6B6B68;font-size:11px;text-transform:uppercase;">R?glement</span><br><strong>${escHtml(c.type_reglement) || '?'}</strong></div>



                    <div><span style="color:#6B6B68;font-size:11px;text-transform:uppercase;">?ch?ance</span><br><strong>${formatDateFr(c.echeance)}</strong></div>



                    <div><span style="color:#6B6B68;font-size:11px;text-transform:uppercase;">Qt? totale</span><br><strong>${commandeTotalQte(c).toLocaleString('fr-FR')}</strong></div>



                    <div><span style="color:#6B6B68;font-size:11px;text-transform:uppercase;">Total</span><br><strong style="color:#003326;font-size:16px;">${formatMoney(c.total || 0)}</strong></div>



                    <div><span style="color:#6B6B68;font-size:11px;text-transform:uppercase;">Statut paiement</span><br><strong>${isCommandePayee(c) ? 'Pay?' : 'Non pay?'}</strong></div>



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



                    showAppView('achats', { keepForm: true, returnView: 'achats', mode: 'saisie' });



                    document.getElementById('achatsSaisiePanel')?.scrollIntoView({ behavior: 'smooth', block: 'start' });



            });



        }



        function reglerCommandeAchats(index) {



            const c = commandesAchats[index];



            if (!c || isCommandePayee(c)) return;



            if (!confirm('Marquer le bon d\'achat ? ' + c.bon + ' ? comme pay? ?')) return;



            c.paye = true;



            persistCommandesAchats();



            renderCommandesAchatsTable();



            refreshFournisseurSoldesAfterCommandeChange(c);



        }



        function deleteCommandeAchats(index) {



            const c = commandesAchats[index];



            if (!c) return;



            if (!confirm('Supprimer le bon d\'achat ? ' + c.bon + ' ? ?')) return;



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



                toast.textContent = 'Bon d\'achat ' + commande.bon + ' enregistr?';



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



                    select.innerHTML = '<option value="">? S?lectionner ?</option>' +



                        unitesMesure.map(u => `<option value="${u.code}">${u.libelle} (${u.code})</option>`).join('');



                }



            } catch (err) {



                console.error(err);



                const select = document.getElementById('ach_ligne_mesure');



                if (select) {



                    select.innerHTML = '<option value="">? Unit? ?</option><option value="KG">Kilogramme (KG)</option><option value="UN">Unit? (UN)</option>';



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



        // Formate tout champ de montant pour qu'il se termine par .00 (2 d?cimales)



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



            updateAchatsArticlesSummary();



        }



        function validateAchatsLigneData(data) {



            if (!data.designation) {



                alert('Veuillez s?lectionner la d?signation.');



                document.getElementById('ach_ligne_designation')?.focus();



                return false;



            }



            if (data.quantite <= 0) {



                alert('La quantit? doit ?tre sup?rieure ? 0.');



                document.getElementById('ach_ligne_quantite')?.focus();



                return false;



            }



            if (!data.mesure) {



                alert('Veuillez s?lectionner une mesure.');



                document.getElementById('ach_ligne_mesure')?.focus();



                return false;



            }



            return true;



        }



        function updateAchatsArticlesSummary() {



            const totalEl = document.getElementById('achatsTotalGeneral');



            const countEl = document.getElementById('achatsArticlesCount');



            const total = achatsLignes.reduce((s, l) => s + (parseFloat(l.sous_total) || 0), 0);



            if (totalEl) totalEl.textContent = formatMoney(total);



            if (countEl) {



                const n = achatsLignes.length;



                countEl.innerHTML = n === 0



                    ? '0 article ajout?'



                    : '<strong>' + n + '</strong> article' + (n > 1 ? 's' : '') + ' ajout?' + (n > 1 ? 's' : '');



            }



        }



        function renderAchatsLignesTable() {



            updateAchatsArticlesSummary();



        }



        function ajouterArticleAchats() {



            const data = getAchatsLigneFormData();



            if (!validateAchatsLigneData(data)) return;



            if (editingAchatsLineIndex !== null) {



                achatsLignes[editingAchatsLineIndex] = data;



            } else {



                achatsLignes.push(data);



            }



            clearAchatsLigneForm();



            const toast = document.getElementById('cartToast');



            if (toast) {



                toast.textContent = 'Article ajout? (' + achatsLignes.length + ')';



                toast.classList.add('show');



                setTimeout(() => toast.classList.remove('show'), 1800);



            }



            document.getElementById('ach_ligne_designation')?.focus();



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



            return val && String(val).trim() ? val : '?';



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



        document.getElementById('ajouterLigneAchatsBtn')?.addEventListener('click', ajouterArticleAchats);



        document.getElementById('enregistrerCommandeAchatsBtn')?.addEventListener('click', saveCommandeAchats);



        document.getElementById('fermerAchatsBtn')?.addEventListener('click', () => {



            showAppView(achatsReturnView === 'commandes' ? 'achats' : achatsReturnView, achatsReturnView === 'commandes' || achatsReturnView === 'achats' ? { mode: 'consult' } : {});



        });



        document.getElementById('nouveauBonAchatsBtn')?.addEventListener('click', () => {



            editingCommandeIndex = null;



            selectedCommandeIndex = null;



            showAppView('achats', { returnView: 'achats', mode: 'saisie' });



        });



        document.getElementById('printCommandesAchatsBtn')?.addEventListener('click', () => {



            if (commandesAchats.length === 0) {



                alert('Aucun bon ? imprimer.');



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



                alert('Aucun bon ? exporter.');



                return;



            }



            if (!window.jspdf) {



                alert('Biblioth?que PDF non charg?e.');



                return;



            }



            const { jsPDF } = window.jspdf;



            const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });



            doc.setFontSize(16);



            doc.setTextColor(0, 51, 38);



            doc.text('SWEET AUSTRIA ? Bons d\'Achat', 14, 16);



            doc.setFontSize(9);



            doc.setTextColor(107, 107, 104);



            doc.text('?dit? le ' + new Date().toLocaleDateString('fr-FR'), 14, 22);



            doc.autoTable({



                startY: 28,



                head: [['Bon N?', 'Date Cmd', 'Code', 'Nom Fournisseur', 'Ville', 'Qt?', 'Total', 'R?glement', '?ch?ance']],



                body: commandesAchats.map(c => [



                    c.bon,



                    formatDateFr(c.date_cmd),



                    c.code_fournisseur || '?',



                    c.nom_fournisseur || '?',



                    commandeVille(c) || '?',



                    commandeTotalQte(c).toLocaleString('fr-FR'),



                    formatMoney(c.total || 0),



                    c.type_reglement || '?',



                    formatDateFr(c.echeance),



                ]),



                styles: { fontSize: 7, cellPadding: 2 },



                headStyles: { fillColor: [0, 51, 38], textColor: 255, fontStyle: 'bold' },



                alternateRowStyles: { fillColor: [249, 248, 243] },



                margin: { left: 14, right: 14 },



            });



            doc.save('bons-achat-sweet-austria.pdf');



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



                alert('Biblioth?que PDF non charg?e.');



                return;



            }



            const info = getAchatsHeaderInfo();



            const { jsPDF } = window.jspdf;



            const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });



            doc.setFontSize(16);



            doc.setTextColor(0, 51, 38);



            doc.text('SWEET AUSTRIA ? Bon d\'Achat ' + info.bon, 14, 16);



            doc.setFontSize(9);



            doc.setTextColor(107, 107, 104);



            doc.text('Date : ' + (info.date_cmd || '?') + '  |  Fournisseur : ' + (info.nom_fournisseur || info.code_fournisseur || '?'), 14, 22);



            doc.text('Livraison : ' + (info.ville_livraison || '?') + '  |  Transport : ' + (info.transport || '?'), 14, 27);



            const total = achatsLignes.reduce((s, l) => s + l.sous_total, 0);



            doc.autoTable({



                startY: 32,



                head: [['R?f', 'Code-barres', 'D?signation', 'Cat?gorie', 'Famille', 'Qt?', 'Mesure', 'Prix U', 'Sous-Total']],



                body: achatsLignes.map(l => [



                    l.ref || '?', l.code_barre || '?', l.designation, l.categorie || '?', l.famille || '?',



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



            if (viewingFournisseur) return;



            const nom = document.getElementById('fr_nom')?.value?.trim() || '';



            if (!nom) {



                alert('Veuillez saisir le nom du fournisseur.');



                document.getElementById('fr_nom')?.focus();



                return;



            }



            const isEdit = !!editingFournisseurId;



            const saveBtnLabel = isEdit ? 'Mettre ? jour' : 'Enregistrer';



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



                saveBtn.textContent = isEdit ? 'Mise ? jour?' : 'Enregistrement?';



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



                        ? 'Fournisseur ' + data.fournisseur.id + ' modifi?'



                        : 'Fournisseur ' + data.fournisseur.id + ' enregistr?';



                    toast.classList.add('show');



                    setTimeout(() => toast.classList.remove('show'), 2800);



                }



            } catch (err) {



                console.error(err);



                alert('Impossible d\'enregistrer le fournisseur. V?rifiez votre connexion.');



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



                alert('Aucun fournisseur ? imprimer.');



                return;



            }



            window.print();



        });



        document.getElementById('exportFournisseursPdfBtn')?.addEventListener('click', () => {



            if (fournisseurs.length === 0) {



                alert('Aucun fournisseur ? exporter.');



                return;



            }



            if (!window.jspdf) {



                alert('Biblioth?que PDF non charg?e.');



                return;



            }



            const { jsPDF } = window.jspdf;



            const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });



            doc.setFontSize(16);



            doc.setTextColor(0, 51, 38);



            doc.text('SWEET AUSTRIA ? Liste des Fournisseurs', 14, 16);



            doc.setFontSize(9);



            doc.setTextColor(107, 107, 104);



            doc.text('?dit? le ' + new Date().toLocaleDateString('fr-FR'), 14, 22);



            applyFournisseurSoldesFromCommandes();



            doc.autoTable({



                startY: 28,



                head: [['ID', 'Nom', 'Type', 'Ville', 'T?l.', 'Fixe', 'E-mail', 'Statut', 'Paiement', 'Banque', 'RIB', 'Solde']],



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



                alert('Aucun produit ? imprimer.');



                return;



            }



            window.print();



        });



        document.getElementById('exportProduitsPdfBtn')?.addEventListener('click', () => {



            if (produits.length === 0) {



                alert('Aucun produit ? exporter.');



                return;



            }



            if (!window.jspdf) {



                alert('Biblioth?que PDF non charg?e.');



                return;



            }



            const { jsPDF } = window.jspdf;



            const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' });



            doc.setFontSize(16);



            doc.setTextColor(0, 51, 38);



            doc.text('SWEET AUSTRIA ? Fiche Produit', 14, 16);



            doc.setFontSize(9);



            doc.setTextColor(107, 107, 104);



            doc.text('?dit? le ' + new Date().toLocaleDateString('fr-FR'), 14, 22);



            doc.autoTable({



                startY: 28,



                head: [['R?f', 'D?signation', 'Type', 'Cat?gorie', 'Famille', 'Qt?', 'U', 'Prix Achat', 'Prix Vente']],



                body: produits.map(p => [



                    p.ref,



                    p.designation,



                    p.type || '?',



                    p.categorie || '?',



                    p.famille || '?',



                    formatQuantiteProduit(p.quantite),



                    uniteLibelle(p.unite),



                    p.prix_achat != null ? formatMoney(p.prix_achat) : '?',



                    p.prix_vente != null ? formatMoney(p.prix_vente) : '?',



                ]),



                styles: { fontSize: 7, cellPadding: 2 },



                headStyles: { fillColor: [0, 51, 38], textColor: 255, fontStyle: 'bold' },



                alternateRowStyles: { fillColor: [249, 248, 243] },



                margin: { left: 14, right: 14 },



            });



            doc.save('fiche-produit-sweet-austria.pdf');



        });



        Promise.all([loadFournisseurs(), loadProduits(), loadUnitesMesure()]).then(() => refreshLookupSelects());



        /* ?? Fiche Société ?? */



        const SOCIETE_STORAGE_KEY = 'ficheSociete';



        let societeData = null;



        let societePhotoDataUrl = '';

        let societeHabillageFileDataUrl = '';

        let societeHabillageUrl = '';

        let societeHabillageType = '';

        let societeHabillageName = '';



        function emptySociete() {



            return {



                nom: '',



                gerant: '',



                contact: '',



                ville: '',



                adresse: '',



                fixe: '',



                email: '',



                rc: '',



                ice: '',



                identifiant_fiscal: '',



                cnss: '',



                patente: '',



                rib: '',



                photo: '',
                habillage_type: '',
                habillage_url: '',
                habillage_file: '',
                habillage_name: '',
            };



        }



        function loadSociete() {



            try {



                societeData = JSON.parse(localStorage.getItem(SOCIETE_STORAGE_KEY) || 'null');



            } catch (e) {



                societeData = null;



            }



            return societeData;



        }



        function saveSocieteToStorage(data) {



            societeData = data;



            localStorage.setItem(SOCIETE_STORAGE_KEY, JSON.stringify(data));



        }



        function setSocietePhotoPreview(dataUrl) {



            const img = document.getElementById('so_photo_img');



            const placeholder = document.getElementById('so_photo_placeholder');



            const removeBtn = document.getElementById('soPhotoRemoveBtn');



            societePhotoDataUrl = dataUrl || '';



            if (img && placeholder) {



                if (dataUrl) {



                    img.src = dataUrl;



                    img.classList.remove('hidden');



                    placeholder.classList.add('hidden');



                    removeBtn?.classList.remove('hidden');



                } else {



                    img.removeAttribute('src');



                    img.classList.add('hidden');



                    placeholder.classList.remove('hidden');



                    removeBtn?.classList.add('hidden');



                }



            }



        }



        function populateSocieteForm(data) {



            const d = data || emptySociete();



            refreshLookupSelects({ so_ville: d.ville || '' });



            document.getElementById('so_nom').value = d.nom || '';



            document.getElementById('so_gerant').value = d.gerant || '';



            document.getElementById('so_contact').value = d.contact || '';



            document.getElementById('so_ville').value = d.ville || '';



            document.getElementById('so_adresse').value = d.adresse || '';



            document.getElementById('so_fixe').value = d.fixe || '';



            document.getElementById('so_email').value = d.email || '';



            document.getElementById('so_rc').value = d.rc || '';



            document.getElementById('so_ice').value = d.ice || '';



            document.getElementById('so_if').value = d.identifiant_fiscal || '';



            document.getElementById('so_cnss').value = d.cnss || '';



            document.getElementById('so_patente').value = d.patente || '';



            document.getElementById('so_rib').value = d.rib || '';



            setSocietePhotoPreview(d.photo || '');
            societeHabillageType = d.habillage_type || '';
            societeHabillageUrl = d.habillage_url || '';
            societeHabillageFileDataUrl = d.habillage_file || '';
            societeHabillageName = d.habillage_name || '';
            const urlInput = document.getElementById('so_habillage_url');
            if (urlInput) urlInput.value = societeHabillageUrl || '';
            const fileInput = document.getElementById('so_habillage_file');
            if (fileInput) fileInput.value = '';
            updateSocieteHabillageStatus();




        }



        function collectSocieteForm() {



            return {



                nom: (document.getElementById('so_nom')?.value || '').trim(),



                gerant: (document.getElementById('so_gerant')?.value || '').trim(),



                contact: (document.getElementById('so_contact')?.value || '').trim(),



                ville: document.getElementById('so_ville')?.value || '',



                adresse: (document.getElementById('so_adresse')?.value || '').trim(),



                fixe: (document.getElementById('so_fixe')?.value || '').trim(),



                email: (document.getElementById('so_email')?.value || '').trim(),



                rc: (document.getElementById('so_rc')?.value || '').trim(),



                ice: (document.getElementById('so_ice')?.value || '').trim(),



                identifiant_fiscal: (document.getElementById('so_if')?.value || '').trim(),



                cnss: (document.getElementById('so_cnss')?.value || '').trim(),



                patente: (document.getElementById('so_patente')?.value || '').trim(),



                rib: (document.getElementById('so_rib')?.value || '').trim(),



                photo: societePhotoDataUrl || '',
                habillage_url: (document.getElementById('so_habillage_url')?.value || societeHabillageUrl || '').trim(),
                habillage_file: societeHabillageFileDataUrl || '',
                habillage_name: societeHabillageName || '',
                habillage_type: (function(){
                    if (societeHabillageFileDataUrl) return 'file';
                    const u = (document.getElementById('so_habillage_url')?.value || societeHabillageUrl || '').trim();
                    if (u) return 'url';
                    return societeHabillageType || '';
                })(),



            };



        }



        function displayOrDash(val) {



            return (val && String(val).trim()) ? String(val).trim() : '?';



        }



        function renderSocieteConsult(data) {



            const d = data || emptySociete();



            document.getElementById('soConsultNom').textContent = displayOrDash(d.nom);



            document.getElementById('soConsultGerant').textContent = d.gerant ? ('G?rant : ' + d.gerant) : '?';



            document.getElementById('soConsultContact').textContent = displayOrDash(d.contact);



            document.getElementById('soConsultVille').textContent = displayOrDash(d.ville);



            document.getElementById('soConsultFixe').textContent = displayOrDash(d.fixe);



            document.getElementById('soConsultAdresse').textContent = displayOrDash(d.adresse);



            document.getElementById('soConsultEmail').textContent = displayOrDash(d.email);



            document.getElementById('soConsultRc').textContent = displayOrDash(d.rc);



            document.getElementById('soConsultIce').textContent = displayOrDash(d.ice);



            document.getElementById('soConsultIf').textContent = displayOrDash(d.identifiant_fiscal);



            document.getElementById('soConsultCnss').textContent = displayOrDash(d.cnss);



            document.getElementById('soConsultPatente').textContent = displayOrDash(d.patente);



            document.getElementById('soConsultRib').textContent = displayOrDash(d.rib);
            const habEl = document.getElementById('soConsultHabillage');
            if (habEl) {
                if (d.habillage_type === 'file' && (d.habillage_file || d.habillage_name)) {
                    habEl.textContent = 'Vidéo importée' + (d.habillage_name ? (' — ' + d.habillage_name) : '');
                } else if (d.habillage_url) {
                    habEl.textContent = d.habillage_url;
                } else {
                    habEl.textContent = '—';
                }
            }




            const logoEmpty = document.getElementById('soConsultLogoEmpty');



            const logoImg = document.getElementById('soConsultLogoImg');



            if (d.photo) {



                logoImg.src = d.photo;



                logoImg.classList.remove('hidden');



                logoEmpty.classList.add('hidden');



            } else {



                logoImg.classList.add('hidden');



                logoImg.removeAttribute('src');



                logoEmpty.classList.remove('hidden');



            }



        }



        function showSocieteForm(data) {



            societeFormPanel?.classList.remove('hidden');



            societeConsultPanel?.classList.add('hidden');



            populateSocieteForm(data || emptySociete());



        }



        function showSocieteConsult(data) {



            societeFormPanel?.classList.add('hidden');



            societeConsultPanel?.classList.remove('hidden');



            renderSocieteConsult(data);



        }



        function openFicheSociete() {



            const data = loadSociete();



            refreshLookupSelects({ so_ville: data?.ville || '' });



            if (data && data.nom) showSocieteConsult(data);



            else showSocieteForm(data || emptySociete());



        }



        function validerSociete(e) {



            e?.preventDefault();



            const data = collectSocieteForm();



            if (!data.nom) {



                alert('Le nom de la soci?t? est obligatoire.');



                document.getElementById('so_nom')?.focus();



                return;



            }



            saveSocieteToStorage(data);

            applyLandingHabillage(data);

            showSocieteConsult(data);



            const toast = document.getElementById('cartToast');



            if (toast) {



                toast.textContent = 'Fiche soci?t? enregistr?e';



                toast.classList.add('show');



                setTimeout(() => toast.classList.remove('show'), 2800);



            }



        }



        
        function updateSocieteHabillageStatus() {
            const el = document.getElementById('soHabillageStatus');
            if (!el) return;
            if (societeHabillageType === 'file' && (societeHabillageFileDataUrl || societeHabillageName)) {
                el.textContent = 'Vidéo importée' + (societeHabillageName ? (' — ' + societeHabillageName) : '');
                el.classList.add('is-set');
            } else if ((societeHabillageUrl || '').trim()) {
                el.textContent = 'URL : ' + societeHabillageUrl.trim();
                el.classList.add('is-set');
            } else {
                el.textContent = 'Aucun habillage';
                el.classList.remove('is-set');
            }
        }

        function toVideoEmbedUrl(raw) {
            const url = String(raw || '').trim();
            if (!url) return '';
            try {
                const u = new URL(url);
                const host = u.hostname.replace(/^www\./, '');
                if (host === 'youtu.be') {
                    const id = u.pathname.replace(/^\//, '').split('/')[0];
                    return id ? ('https://www.youtube.com/embed/' + id) : '';
                }
                if (host === 'youtube.com' || host === 'm.youtube.com' || host === 'youtube-nocookie.com') {
                    let id = u.searchParams.get('v');
                    if (!id && u.pathname.startsWith('/embed/')) id = u.pathname.split('/')[2];
                    if (!id && u.pathname.startsWith('/shorts/')) id = u.pathname.split('/')[2];
                    return id ? ('https://www.youtube.com/embed/' + id) : '';
                }
                if (host === 'vimeo.com') {
                    const id = u.pathname.split('/').filter(Boolean)[0];
                    return id ? ('https://player.vimeo.com/video/' + id) : '';
                }
            } catch (e) {}
            return '';
        }

        function isDirectVideoUrl(url) {
            return /\.(mp4|webm|ogg)(\?|#|$)/i.test(String(url || ''));
        }

        function applyLandingHabillage(data) {
            const video = document.getElementById('landingAdVideo');
            const iframe = document.getElementById('landingAdIframe');
            const placeholder = document.getElementById('landingAdPlaceholder');
            if (!video || !placeholder) return;
            const d = data || loadSociete() || {};
            const type = d.habillage_type || '';
            const file = d.habillage_file || '';
            const url = (d.habillage_url || '').trim();

            const showPlaceholder = (on) => {
                placeholder.classList.toggle('is-hidden', !on);
            };
            const hideMedia = () => {
                video.classList.add('hidden');
                video.removeAttribute('src');
                const source = video.querySelector('source');
                if (source) source.removeAttribute('src');
                video.load?.();
                if (iframe) {
                    iframe.classList.add('hidden');
                    iframe.removeAttribute('src');
                }
            };

            if (type === 'file' && file) {
                hideMedia();
                if (iframe) iframe.classList.add('hidden');
                video.classList.remove('hidden');
                const source = video.querySelector('source');
                if (source) {
                    source.setAttribute('src', file);
                    source.setAttribute('type', 'video/mp4');
                }
                video.src = file;
                video.load?.();
                showPlaceholder(false);
                return;
            }

            if (url) {
                const embed = toVideoEmbedUrl(url);
                if (embed && iframe) {
                    hideMedia();
                    video.classList.add('hidden');
                    iframe.classList.remove('hidden');
                    iframe.src = embed;
                    showPlaceholder(false);
                    return;
                }
                if (isDirectVideoUrl(url) || !embed) {
                    hideMedia();
                    if (iframe) iframe.classList.add('hidden');
                    video.classList.remove('hidden');
                    const source = video.querySelector('source');
                    if (source) {
                        source.setAttribute('src', url);
                        source.setAttribute('type', 'video/mp4');
                    }
                    video.src = url;
                    video.load?.();
                    showPlaceholder(false);
                    return;
                }
            }

            // fallback default file
            hideMedia();
            if (iframe) iframe.classList.add('hidden');
            video.classList.remove('hidden');
            const source = video.querySelector('source');
            const def = source?.getAttribute('data-default-src') || (source ? source.getAttribute('src') : '') || '';
            // restore default publicite path from blade asset if present in markup initially
            const defaultSrc = video.getAttribute('data-default-src') || "{{ asset('videos/publicite.mp4') }}";
            if (source) {
                source.setAttribute('src', defaultSrc);
                source.setAttribute('type', 'video/mp4');
            }
            video.src = defaultSrc;
            video.load?.();
            // keep placeholder until loaded or error
            showPlaceholder(true);
            video.onloadeddata = () => {
                if (video.videoWidth > 0) showPlaceholder(false);
            };
            video.onerror = () => showPlaceholder(true);
        }

document.getElementById('soPhotoPickBtn')?.addEventListener('click', () => {



            document.getElementById('so_photo_file')?.click();



        });



        document.getElementById('so_photo_file')?.addEventListener('change', (e) => {



            const file = e.target.files?.[0];



            if (!file) return;



            const reader = new FileReader();



            reader.onload = () => setSocietePhotoPreview(String(reader.result || ''));



            reader.readAsDataURL(file);



        });



        document.getElementById('soPhotoRemoveBtn')?.addEventListener('click', () => {



            const input = document.getElementById('so_photo_file');



            if (input) input.value = '';



            setSocietePhotoPreview('');



        });



        
        document.getElementById('soHabillageBtn')?.addEventListener('click', () => {
            document.getElementById('soHabillagePanel')?.classList.toggle('hidden');
        });
        document.getElementById('soHabillageApplyUrlBtn')?.addEventListener('click', () => {
            const url = (document.getElementById('so_habillage_url')?.value || '').trim();
            if (!url) {
                alert('Saisissez un lien URL vidéo.');
                return;
            }
            societeHabillageUrl = url;
            societeHabillageType = 'url';
            societeHabillageFileDataUrl = '';
            societeHabillageName = '';
            const fileInput = document.getElementById('so_habillage_file');
            if (fileInput) fileInput.value = '';
            updateSocieteHabillageStatus();
        });
        document.getElementById('soHabillageClearBtn')?.addEventListener('click', () => {
            societeHabillageUrl = '';
            societeHabillageType = '';
            societeHabillageFileDataUrl = '';
            societeHabillageName = '';
            const urlInput = document.getElementById('so_habillage_url');
            if (urlInput) urlInput.value = '';
            const fileInput = document.getElementById('so_habillage_file');
            if (fileInput) fileInput.value = '';
            updateSocieteHabillageStatus();
        });
        document.getElementById('so_habillage_file')?.addEventListener('change', (e) => {
            const file = e.target.files?.[0];
            if (!file) return;
            if (file.size > 4.5 * 1024 * 1024) {
                alert('Vidéo trop volumineuse pour le stockage local (max ~4,5 Mo). Utilisez un lien URL.');
                e.target.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = () => {
                societeHabillageFileDataUrl = String(reader.result || '');
                societeHabillageType = 'file';
                societeHabillageName = file.name || 'video';
                societeHabillageUrl = '';
                const urlInput = document.getElementById('so_habillage_url');
                if (urlInput) urlInput.value = '';
                updateSocieteHabillageStatus();
            };
            reader.readAsDataURL(file);
        });

ficheSocieteForm?.addEventListener('submit', validerSociete);



        document.getElementById('validerSocieteBtn')?.addEventListener('click', validerSociete);



        document.getElementById('fermerSocieteForm')?.addEventListener('click', () => {



            const data = loadSociete();



            if (data && data.nom) showSocieteConsult(data);



            else document.querySelector('.nav-item[data-view="dashboard"]')?.click();



        });



        document.getElementById('modifierSocieteBtn')?.addEventListener('click', () => {



            showSocieteForm(loadSociete() || emptySociete());



        });



        document.getElementById('printSocieteBtn')?.addEventListener('click', () => {



            if (!loadSociete()?.nom) {



                alert('Aucune fiche soci?t? ? imprimer.');



                return;



            }



            window.print();



        });



        /* ?? Trésorerie Matériels ?? */



        const MATERIELS_STORAGE_KEY = 'tresorerieMateriels';



        let materiels = [];



        let editingMaterielId = null;



        let materielPhotoDataUrl = '';



        function todayInputValue() {



            const d = new Date();



            const m = String(d.getMonth() + 1).padStart(2, '0');



            const day = String(d.getDate()).padStart(2, '0');



            return `${d.getFullYear()}-${m}-${day}`;



        }



        function loadMateriels() {



            try {



                materiels = JSON.parse(localStorage.getItem(MATERIELS_STORAGE_KEY) || '[]');



                if (!Array.isArray(materiels)) materiels = [];



            } catch (e) {



                materiels = [];



            }



            return materiels;



        }



        function saveMaterielsToStorage() {



            localStorage.setItem(MATERIELS_STORAGE_KEY, JSON.stringify(materiels));



        }



        function nextMaterielRef() {



            const nums = materiels



                .map(m => parseInt(String(m.ref || '').replace(/\D/g, ''), 10))



                .filter(n => !Number.isNaN(n));



            const next = (nums.length ? Math.max(...nums) : 0) + 1;



            return 'TM' + String(next).padStart(4, '0');



        }



        function formatMoneyFr(val) {



            const n = parseFloat(val);



            if (Number.isNaN(n)) return '?';



            return n.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });



        }



        function formatDateFr(iso) {



            if (!iso) return '?';



            const parts = String(iso).split('-');



            if (parts.length !== 3) return iso;



            return `${parts[2]}/${parts[1]}/${parts[0]}`;



        }



        function updateMaterielsFournisseurList() {



            const list = document.getElementById('tm_fournisseur_list');



            if (!list) return;



            const names = [...new Set((fournisseurs || []).map(f => f.nom).filter(Boolean))]



                .sort((a, b) => a.localeCompare(b, 'fr'));



            list.innerHTML = names.map(n => `<option value="${escapeOptionAttr(n)}"></option>`).join('');



        }



        function setMaterielPhotoPreview(dataUrl) {



            const img = document.getElementById('tm_photo_img');



            const placeholder = document.getElementById('tm_photo_placeholder');



            const removeBtn = document.getElementById('tmPhotoRemoveBtn');



            materielPhotoDataUrl = dataUrl || '';



            if (!img || !placeholder) return;



            if (dataUrl) {



                img.src = dataUrl;



                img.classList.remove('hidden');



                placeholder.classList.add('hidden');



                removeBtn?.classList.remove('hidden');



            } else {



                img.removeAttribute('src');



                img.classList.add('hidden');



                placeholder.classList.remove('hidden');



                removeBtn?.classList.add('hidden');



            }



        }



        function resetMaterielForm(keepOpen = true) {



            editingMaterielId = null;



            if (ficheMaterielForm) ficheMaterielForm.reset();



            document.getElementById('tm_date').value = todayInputValue();



            document.getElementById('tm_ref').value = nextMaterielRef();



            setMaterielPhotoPreview('');



            const file = document.getElementById('tm_photo_file');



            if (file) file.value = '';



            if (keepOpen) {



                materielsFormPanel?.classList.remove('hidden');



                materielsConsultPanel?.classList.add('hidden');



            }



        }



        function collectMaterielForm() {



            return {



                id: editingMaterielId || ('m_' + Date.now()),



                date: document.getElementById('tm_date')?.value || '',



                ref: (document.getElementById('tm_ref')?.value || '').trim(),



                designation: (document.getElementById('tm_designation')?.value || '').trim(),



                fournisseur: (document.getElementById('tm_fournisseur')?.value || '').trim(),



                prix_achat: document.getElementById('tm_prix_achat')?.value || '',



                douane: document.getElementById('tm_douane')?.value || '',



                date_travail: document.getElementById('tm_date_travail')?.value || '',



                photo: materielPhotoDataUrl || '',



            };



        }



        function validateMaterielForm(data) {



            if (!data.date) {



                alert('La date est obligatoire.');



                document.getElementById('tm_date')?.focus();



                return false;



            }



            if (!data.ref) {



                alert('La référence est obligatoire.');



                document.getElementById('tm_ref')?.focus();



                return false;



            }



            if (!data.designation) {



                alert('La désignation est obligatoire.');



                document.getElementById('tm_designation')?.focus();



                return false;



            }



            const dup = materiels.find(m => m.ref === data.ref && m.id !== data.id);



            if (dup) {



                alert('Cette référence existe déjà.');



                document.getElementById('tm_ref')?.focus();



                return false;



            }



            return true;



        }



        function upsertMateriel(data) {



            const idx = materiels.findIndex(m => m.id === data.id);



            if (idx >= 0) materiels[idx] = data;



            else materiels.unshift(data);



            saveMaterielsToStorage();



        }



        function renderMaterielsTable() {



            if (!materielsTableBody) return;



            if (!materiels.length) {



                materielsTableBody.innerHTML = '<tr><td colspan="9" class="fournisseur-empty">Aucun matériel enregistré</td></tr>';



                return;



            }



            materielsTableBody.innerHTML = materiels.map(m => {



                const photo = m.photo



                    ? `<img class="tm-thumb" src="${m.photo}" alt="">`



                    : `<span class="tm-thumb-empty">?</span>`;



                return `<tr>



                    <td>${escHtml(formatDateFr(m.date))}</td>



                    <td>${escHtml(m.ref)}</td>



                    <td>${photo}</td>



                    <td>${escHtml(m.designation)}</td>



                    <td>${escHtml(m.fournisseur || '?')}</td>



                    <td>${escHtml(formatMoneyFr(m.prix_achat))}</td>



                    <td>${escHtml(formatMoneyFr(m.douane))}</td>



                    <td>${escHtml(formatDateFr(m.date_travail))}</td>



                    <td class="col-actions no-print-materiels">



                        <span class="col-actions-wrap">



                            <button type="button" class="btn-icon-row btn-icon-edit" data-edit-materiel="${escHtml(m.id)}" title="Modifier" aria-label="Modifier">



                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>



                            </button>



                            <button type="button" class="btn-icon-row btn-icon-delete" data-delete-materiel="${escHtml(m.id)}" title="Supprimer" aria-label="Supprimer">



                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>



                            </button>



                        </span>



                    </td>



                </tr>`;



            }).join('');



            materielsTableBody.querySelectorAll('[data-edit-materiel]').forEach(btn => {



                btn.addEventListener('click', () => editMateriel(btn.dataset.editMateriel));



            });



            materielsTableBody.querySelectorAll('[data-delete-materiel]').forEach(btn => {



                btn.addEventListener('click', () => deleteMateriel(btn.dataset.deleteMateriel));



            });



        }



        function showMaterielsConsult() {



            materielsFormPanel?.classList.add('hidden');



            materielsConsultPanel?.classList.remove('hidden');



            renderMaterielsTable();



        }



        function showMaterielForm(item = null) {



            updateMaterielsFournisseurList();



            materielsFormPanel?.classList.remove('hidden');



            materielsConsultPanel?.classList.add('hidden');



            if (item) {



                editingMaterielId = item.id;



                document.getElementById('tm_date').value = item.date || todayInputValue();



                document.getElementById('tm_ref').value = item.ref || '';



                document.getElementById('tm_designation').value = item.designation || '';



                document.getElementById('tm_fournisseur').value = item.fournisseur || '';



                document.getElementById('tm_prix_achat').value = item.prix_achat || '';



                document.getElementById('tm_douane').value = item.douane || '';



                document.getElementById('tm_date_travail').value = item.date_travail || '';



                setMaterielPhotoPreview(item.photo || '');



            } else {



                resetMaterielForm(true);



            }



        }



        function editMateriel(id) {



            const item = materiels.find(m => m.id === id);



            if (!item) return;



            showMaterielForm(item);



        }



        function deleteMateriel(id) {



            if (!confirm('Supprimer ce mat?riel ?')) return;



            materiels = materiels.filter(m => m.id !== id);



            saveMaterielsToStorage();



            renderMaterielsTable();



        }



        function openTresorerieMateriels() {



            loadMateriels();



            loadFournisseurs().then(() => {



                updateMaterielsFournisseurList();



                showMaterielsConsult();



            }).catch(() => showMaterielsConsult());



        }



        function saveMaterielAndContinue(closeAfter) {



            const data = collectMaterielForm();



            if (!validateMaterielForm(data)) return false;



            upsertMateriel(data);



            const toast = document.getElementById('cartToast');



            if (toast) {



                toast.textContent = 'Mat?riel ' + data.ref + ' enregistr?';



                toast.classList.add('show');



                setTimeout(() => toast.classList.remove('show'), 2800);



            }



            if (closeAfter) showMaterielsConsult();



            else resetMaterielForm(true);



            return true;



        }



        document.getElementById('enregistrerMaterielBtn')?.addEventListener('click', () => showMaterielForm(null));



        document.getElementById('fermerMaterielForm')?.addEventListener('click', () => showMaterielsConsult());



        document.getElementById('ajouterMaterielBtn')?.addEventListener('click', () => saveMaterielAndContinue(false));



        ficheMaterielForm?.addEventListener('submit', (e) => {



            e.preventDefault();



            saveMaterielAndContinue(true);



        });



        document.getElementById('validerMaterielBtn')?.addEventListener('click', (e) => {



            e.preventDefault();



            saveMaterielAndContinue(true);



        });



        document.getElementById('tmPhotoPickBtn')?.addEventListener('click', () => {



            document.getElementById('tm_photo_file')?.click();



        });



        document.getElementById('tm_photo_file')?.addEventListener('change', (e) => {



            const file = e.target.files?.[0];



            if (!file) return;



            const reader = new FileReader();



            reader.onload = () => setMaterielPhotoPreview(String(reader.result || ''));



            reader.readAsDataURL(file);



        });



        document.getElementById('tmPhotoRemoveBtn')?.addEventListener('click', () => {



            const input = document.getElementById('tm_photo_file');



            if (input) input.value = '';



            setMaterielPhotoPreview('');



        });



        document.getElementById('printMaterielsBtn')?.addEventListener('click', () => {



            if (!materiels.length) {



                alert('Aucun mat?riel ? imprimer.');



                return;



            }



            window.print();



        });



        /* ?? Utilisateur ?? */



        const USERS_STORAGE_KEY = 'utilisateursApp';



        let utilisateurs = [];



        let editingUserId = null;



        let utilisateurReadonly = false;



        const AUTH_MENU_TREE = [



            {



                id: 'dashboard',



                label: 'Tableau de bord',



                items: [{ id: 'dashboard.main', label: 'Tableau de bord' }]



            },



            {



                id: 'fournisseur',



                label: 'Fournisseur',



                items: [



                    { id: 'fournisseur.fiche', label: 'Fiche Fournisseur' },



                    { id: 'fournisseur.achats', label: "Bon d'Achat" },



                    { id: 'fournisseur.reglement', label: 'Réglement Achat' },



                    { id: 'fournisseur.balance', label: 'Balance Achats' },



                    { id: 'fournisseur.releve', label: 'Relevé Compte Fournisseur' }



                ]



            },



            {



                id: 'stock',



                label: 'Stock',



                items: [



                    { id: 'stock.fiche', label: 'Fiche Produit' },



                    { id: 'stock.crus', label: 'Dépôt Produits Crus' },



                    { id: 'stock.finis', label: 'Dépôt Produits Finis' },



                    { id: 'stock.divers', label: 'Dépôt produits Divers' }



                ]



            },



            {



                id: 'production',



                label: 'Production',



                items: [



                    { id: 'production.qte', label: 'Etat Journalier Quantité' },



                    { id: 'production.prod', label: 'Etat Journalier Production' },



                    { id: 'production.sortie', label: 'Etat Journalier Sortie' },



                    { id: 'production.depense', label: 'Etat Journalier Dépense' }



                ]



            },



            {



                id: 'client',



                label: 'Client',



                items: [



                    { id: 'client.fiche', label: 'Fiche Client' },



                    { id: 'client.vente', label: 'Vente' },



                    { id: 'client.reglement', label: 'Règlement' },



                    { id: 'client.balance', label: 'Balance' },



                    { id: 'client.releve', label: 'Relevé Compte Clt' }



                ]



            },



            {



                id: 'banque',



                label: 'Banque',



                items: [



                    { id: 'banque.debit', label: 'Débit' },



                    { id: 'banque.credit', label: 'Crédit' },



                    { id: 'banque.caisse', label: 'Caisse' }



                ]



            },



            {



                id: 'rapport',



                label: 'Rapport',



                items: [



                    { id: 'rapport.achats', label: 'Etat Achats' },



                    { id: 'rapport.ventes', label: 'Etat Ventes' },



                    { id: 'rapport.stock', label: 'Etat Stock' },



                    { id: 'rapport.paiement', label: 'Etat Paiement' }



                ]



            },



            {



                id: 'configuration',



                label: 'Configuration',



                items: [



                    { id: 'configuration.societe', label: 'Fiche Société' },



                    { id: 'configuration.materiels', label: 'Trésorerie Matériels' },



                    { id: 'configuration.utilisateur', label: 'Utilisateur' },



                    { id: 'configuration.parametres', label: 'Paramètres' },



                    { id: 'configuration.tresorerie', label: 'Trésorerie' },



                    { id: 'configuration.banque', label: 'Banque' },



                    { id: 'configuration.caisse', label: 'Caisse' },



                    { id: 'configuration.unite', label: 'Unité de Mesure' },



                    { id: 'configuration.ville', label: 'Ville' },



                    { id: 'configuration.commerciaux', label: 'Commerciaux' },



                    { id: 'configuration.transport', label: 'Transport' },



                    { id: 'configuration.chauffeurs', label: 'Chauffeurs' }



                ]



            }



        ];



        function allAuthIds() {



            const ids = [];



            AUTH_MENU_TREE.forEach(sec => {



                ids.push('sec:' + sec.id);



                sec.items.forEach(it => ids.push(it.id));



            });



            return ids;



        }



        function authLabelMap() {



            const map = {};



            AUTH_MENU_TREE.forEach(sec => {



                map['sec:' + sec.id] = sec.label;



                sec.items.forEach(it => { map[it.id] = it.label; });



            });



            return map;



        }



        function loadUtilisateurs() {



            try {



                utilisateurs = JSON.parse(localStorage.getItem(USERS_STORAGE_KEY) || '[]');



                if (!Array.isArray(utilisateurs)) utilisateurs = [];



            } catch (e) {



                utilisateurs = [];



            }



            return utilisateurs;



        }



        function saveUtilisateurs() {



            localStorage.setItem(USERS_STORAGE_KEY, JSON.stringify(utilisateurs));



        }



        function nextUserId() {



            const nums = utilisateurs



                .map(u => parseInt(String(u.id || '').replace(/\D/g, ''), 10))



                .filter(n => !Number.isNaN(n));



            const next = (nums.length ? Math.max(...nums) : 0) + 1;



            return 'US' + String(next).padStart(4, '0');



        }



        function renderAuthTree(selected = [], readonly = false) {



            const root = document.getElementById('utilisateurAuthTree');



            if (!root) return;



            const selectedSet = new Set(selected || []);



            root.innerHTML = AUTH_MENU_TREE.map(sec => {



                const secId = 'sec:' + sec.id;



                const items = sec.items.map(it => `



                    <label class="user-auth-item">



                        <input type="checkbox" class="us-auth-item" data-auth="${it.id}" data-section="${sec.id}" ${selectedSet.has(it.id) ? 'checked' : ''} ${readonly ? 'disabled' : ''}>



                        <span>${escHtml(it.label)}</span>



                    </label>



                `).join('');



                return `



                    <div class="user-auth-section" data-section-box="${sec.id}">



                        <label class="user-auth-section-head">



                            <input type="checkbox" class="us-auth-section" data-section="${sec.id}" data-auth="${secId}" ${selectedSet.has(secId) ? 'checked' : ''} ${readonly ? 'disabled' : ''}>



                            <span>${escHtml(sec.label)}</span>



                        </label>



                        <div class="user-auth-items">${items}</div>



                    </div>



                `;



            }).join('');



            if (!readonly) {



                root.querySelectorAll('.us-auth-section').forEach(cb => {



                    cb.addEventListener('change', () => {



                        const sec = cb.dataset.section;



                        root.querySelectorAll(`.us-auth-item[data-section="${sec}"]`).forEach(item => {



                            item.checked = cb.checked;



                        });



                        syncAuthAllCheckbox();



                    });



                });



                root.querySelectorAll('.us-auth-item').forEach(cb => {



                    cb.addEventListener('change', () => {



                        const sec = cb.dataset.section;



                        const items = [...root.querySelectorAll(`.us-auth-item[data-section="${sec}"]`)];



                        const secCb = root.querySelector(`.us-auth-section[data-section="${sec}"]`);



                        if (secCb) secCb.checked = items.every(i => i.checked);



                        syncAuthAllCheckbox();



                    });



                });



            }



            syncAuthAllCheckbox();



        }



        function syncAuthAllCheckbox() {



            const all = document.getElementById('us_auth_all');



            if (!all) return;



            const boxes = [...document.querySelectorAll('#utilisateurAuthTree input[type="checkbox"]')];



            all.checked = boxes.length > 0 && boxes.every(b => b.checked);



        }



        function collectAuthIds() {



            return [...document.querySelectorAll('#utilisateurAuthTree input[type="checkbox"]:checked')]



                .map(el => el.dataset.auth)



                .filter(Boolean);



        }



        function setUtilisateurFormReadonly(readonly) {



            utilisateurReadonly = !!readonly;



            ['us_nom', 'us_statut', 'us_login', 'us_password'].forEach(id => {



                const el = document.getElementById(id);



                if (!el) return;



                el.disabled = !!readonly;



            });



            const all = document.getElementById('us_auth_all');



            if (all) all.disabled = !!readonly;



            const saveBtn = document.getElementById('validerUtilisateurBtn');



            if (saveBtn) saveBtn.classList.toggle('hidden', !!readonly);



            const title = document.getElementById('utilisateurFormTitle');



            const subtitle = document.getElementById('utilisateurFormSubtitle');



            if (readonly) {



                if (title) title.textContent = 'Consulter Utilisateur';



                if (subtitle) subtitle.textContent = 'Lecture seule';



            } else if (editingUserId) {



                if (title) title.textContent = 'Modifier Utilisateur';



                if (subtitle) subtitle.textContent = editingUserId;



            } else {



                if (title) title.textContent = 'Utilisateur';



                if (subtitle) subtitle.textContent = 'Barre de saisie';



            }



        }



        function showUtilisateurConsult() {



            utilisateurFormPanel?.classList.add('hidden');



            utilisateurConsultPanel?.classList.remove('hidden');



            renderUtilisateursTable();



        }



        function showUtilisateurForm(user = null, readonly = false) {



            utilisateurFormPanel?.classList.remove('hidden');



            utilisateurConsultPanel?.classList.add('hidden');



            editingUserId = user?.id || null;



            document.getElementById('us_date').value = user?.date || todayInputValue();



            document.getElementById('us_id').value = user?.id || nextUserId();



            document.getElementById('us_nom').value = user?.nom || '';



            document.getElementById('us_statut').value = user?.statut || '';



            document.getElementById('us_login').value = user?.login || '';



            document.getElementById('us_password').value = user?.password || '';



            renderAuthTree(user?.autorisations || [], readonly);



            setUtilisateurFormReadonly(readonly);



        }



        function authChipsHtml(ids) {



            const map = authLabelMap();



            const itemIds = (ids || []).filter(id => !String(id).startsWith('sec:'));



            if (!itemIds.length) return '?';



            const shown = itemIds.slice(0, 4).map(id => `<span class="user-auth-chip">${escHtml(map[id] || id)}</span>`).join('');



            const more = itemIds.length > 4 ? `<span class="user-auth-chip">+${itemIds.length - 4}</span>` : '';



            return `<div class="user-auth-chips">${shown}${more}</div>`;



        }



        function maskPassword(pwd) {



            if (!pwd) return '?';



            return '?'.repeat(Math.min(String(pwd).length, 10));



        }



        function renderUtilisateursTable() {



            if (!utilisateursTableBody) return;



            if (!utilisateurs.length) {



                utilisateursTableBody.innerHTML = '<tr><td colspan="7" class="fournisseur-empty">Aucun utilisateur enregistré</td></tr>';



                return;



            }



            utilisateursTableBody.innerHTML = utilisateurs.map(u => `



                <tr>



                    <td>${escHtml(formatDateFr(u.date))}</td>



                    <td>${escHtml(u.id)}</td>



                    <td>${escHtml(u.statut || '?')}</td>



                    <td>${escHtml(u.login || '?')}</td>



                    <td class="pwd-mask">${escHtml(maskPassword(u.password))}</td>



                    <td>${authChipsHtml(u.autorisations)}</td>



                    <td class="col-actions no-print-utilisateur">



                        <span class="col-actions-wrap">



                            <button type="button" class="btn-icon-row btn-icon-view" data-view-user="${escHtml(u.id)}" title="Voir" aria-label="Voir">



                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>



                            </button>



                            <button type="button" class="btn-icon-row btn-icon-edit" data-edit-user="${escHtml(u.id)}" title="Modifier" aria-label="Modifier">



                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>



                            </button>



                            <button type="button" class="btn-icon-row btn-icon-delete" data-delete-user="${escHtml(u.id)}" title="Supprimer" aria-label="Supprimer">



                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>



                            </button>



                        </span>



                    </td>



                </tr>



            `).join('');



            utilisateursTableBody.querySelectorAll('[data-view-user]').forEach(btn => {



                btn.addEventListener('click', () => {



                    const u = utilisateurs.find(x => x.id === btn.dataset.viewUser);



                    if (u) showUtilisateurForm(u, true);



                });



            });



            utilisateursTableBody.querySelectorAll('[data-edit-user]').forEach(btn => {



                btn.addEventListener('click', () => {



                    const u = utilisateurs.find(x => x.id === btn.dataset.editUser);



                    if (u) showUtilisateurForm(u, false);



                });



            });



            utilisateursTableBody.querySelectorAll('[data-delete-user]').forEach(btn => {



                btn.addEventListener('click', () => deleteUtilisateur(btn.dataset.deleteUser));



            });



        }



        function deleteUtilisateur(id) {



            if (!confirm('Supprimer cet utilisateur ?')) return;



            utilisateurs = utilisateurs.filter(u => u.id !== id);



            saveUtilisateurs();



            renderUtilisateursTable();



        }



        function openUtilisateur() {



            loadUtilisateurs();



            showUtilisateurConsult();



        }



        function collectUtilisateurForm() {



            return {



                id: document.getElementById('us_id')?.value || nextUserId(),



                date: document.getElementById('us_date')?.value || todayInputValue(),



                nom: (document.getElementById('us_nom')?.value || '').trim(),



                statut: document.getElementById('us_statut')?.value || '',



                login: (document.getElementById('us_login')?.value || '').trim(),



                password: (document.getElementById('us_password')?.value || '').trim(),



                autorisations: collectAuthIds(),



            };



        }



        function validateUtilisateur(data) {



            if (!data.nom) { alert('Le nom complet est obligatoire.'); document.getElementById('us_nom')?.focus(); return false; }



            if (!data.statut) { alert('Le statut est obligatoire.'); document.getElementById('us_statut')?.focus(); return false; }



            if (!data.login) { alert('Le login est obligatoire.'); document.getElementById('us_login')?.focus(); return false; }



            if (!data.password) { alert('Le mot de passe est obligatoire.'); document.getElementById('us_password')?.focus(); return false; }



            const dupLogin = utilisateurs.find(u => u.login.toLowerCase() === data.login.toLowerCase() && u.id !== data.id);



            if (dupLogin) { alert('Ce login existe déjà.'); document.getElementById('us_login')?.focus(); return false; }



            return true;



        }



        function validerUtilisateur(e) {



            e?.preventDefault();



            if (utilisateurReadonly) return;



            const data = collectUtilisateurForm();



            if (!validateUtilisateur(data)) return;



            const idx = utilisateurs.findIndex(u => u.id === data.id);



            if (idx >= 0) utilisateurs[idx] = data;



            else utilisateurs.unshift(data);



            saveUtilisateurs();



            showUtilisateurConsult();



            const toast = document.getElementById('cartToast');



            if (toast) {



                toast.textContent = 'Utilisateur ' + data.id + ' enregistré';



                toast.classList.add('show');



                setTimeout(() => toast.classList.remove('show'), 2800);



            }



        }



        document.getElementById('ajouterUtilisateurBtn')?.addEventListener('click', () => showUtilisateurForm(null, false));



        document.getElementById('fermerUtilisateurForm')?.addEventListener('click', () => showUtilisateurConsult());



        document.getElementById('fermerUtilisateurConsultBtn')?.addEventListener('click', () => {



            document.querySelector('.nav-item[data-view="dashboard"]')?.click();



        });



        ficheUtilisateurForm?.addEventListener('submit', validerUtilisateur);



        document.getElementById('validerUtilisateurBtn')?.addEventListener('click', validerUtilisateur);



        document.getElementById('us_auth_all')?.addEventListener('change', (e) => {



            const checked = !!e.target.checked;



            document.querySelectorAll('#utilisateurAuthTree input[type="checkbox"]').forEach(cb => {



                if (!cb.disabled) cb.checked = checked;



            });



        });





        /* ── Réglement Achat ── */

        const REGLEMENTS_STORAGE_KEY = 'reglementsAchats';

        let reglementsAchats = [];

        let editingReglementId = null;

        let reglementReadonly = false;

        let reglementPhotoDataUrl = '';



        function loadReglementsAchats() {

            try {

                reglementsAchats = JSON.parse(localStorage.getItem(REGLEMENTS_STORAGE_KEY) || '[]');

                if (!Array.isArray(reglementsAchats)) reglementsAchats = [];

            } catch (e) {

                reglementsAchats = [];

            }

            return reglementsAchats;

        }



        function saveReglementsAchats() {

            localStorage.setItem(REGLEMENTS_STORAGE_KEY, JSON.stringify(reglementsAchats));

        }



        function nextReglementRef() {

            const nums = reglementsAchats

                .map(r => parseInt(String(r.ref || '').replace(/\D/g, ''), 10))

                .filter(n => !Number.isNaN(n));

            const next = (nums.length ? Math.max(...nums) : 0) + 1;

            return 'RG' + String(next).padStart(4, '0');

        }



        function nextReglementNum() {

            const nums = reglementsAchats

                .map(r => parseInt(String(r.num_reg || '').replace(/\D/g, ''), 10))

                .filter(n => !Number.isNaN(n));

            const next = (nums.length ? Math.max(...nums) : 0) + 1;

            return 'NR' + String(next).padStart(4, '0');

        }



        function getBonsNonSoldes() {

            loadCommandesAchats();

            return (commandesAchats || []).filter(c => !isCommandePayee(c));

        }



        function fillReglementBonsSelect(selectedBon = '') {

            const sel = document.getElementById('rg_bon');

            if (!sel) return;

            const bons = getBonsNonSoldes();

            const opts = ['<option value="">— Bons non soldés —</option>'];

            bons.forEach((c, i) => {

                const idx = commandesAchats.indexOf(c);

                const key = String(idx);

                const label = `${c.bon || '—'} — ${c.nom_fournisseur || c.code_fournisseur || 'Fournisseur'} — ${formatMoneyFr(c.total || 0)}`;

                opts.push(`<option value="${escapeOptionAttr(key)}" ${selectedBon === key ? 'selected' : ''}>${escapeOptionText(label)}</option>`);

            });

            // keep currently selected bon even if now paid (edit mode)

            if (selectedBon && !bons.some((_, i) => String(commandesAchats.indexOf(bons[i])) === selectedBon)) {

                // try restore from editing record via option value matching index

            }

            sel.innerHTML = opts.join('');

            if (selectedBon) sel.value = selectedBon;

        }



        function applySelectedBonToReglementForm() {

            const sel = document.getElementById('rg_bon');

            const idx = parseInt(sel?.value || '', 10);

            if (Number.isNaN(idx) || !commandesAchats[idx]) {

                document.getElementById('rg_bon_num').value = '';

                document.getElementById('rg_fournisseur').value = '';

                document.getElementById('rg_montant_bon').value = '';

                return;

            }

            const c = commandesAchats[idx];

            document.getElementById('rg_bon_num').value = c.bon || '';

            document.getElementById('rg_fournisseur').value = c.nom_fournisseur || c.code_fournisseur || '';

            document.getElementById('rg_montant_bon').value = c.total || 0;

            const montantReg = document.getElementById('rg_montant_reg');

            if (montantReg && (!montantReg.value || montantReg.value === '0' || montantReg.value === '0.00')) {

                montantReg.value = parseFloat(c.total || 0).toFixed(2);

            }

        }



        function setReglementPhotoPreview(dataUrl) {

            const img = document.getElementById('rg_photo_img');

            const placeholder = document.getElementById('rg_photo_placeholder');

            reglementPhotoDataUrl = dataUrl || '';

            if (!img || !placeholder) return;

            if (dataUrl) {

                img.src = dataUrl;

                img.classList.remove('hidden');

                placeholder.classList.add('hidden');

            } else {

                img.removeAttribute('src');

                img.classList.add('hidden');

                placeholder.classList.remove('hidden');

            }

        }



        function setReglementFormReadonly(readonly) {

            reglementReadonly = !!readonly;

            ['rg_date', 'rg_bon', 'rg_type', 'rg_num', 'rg_banque', 'rg_tire', 'rg_montant_reg', 'rg_date_decaiss'].forEach(id => {

                const el = document.getElementById(id);

                if (el) el.disabled = !!readonly;

            });

            const saveBtn = document.getElementById('validerReglementBtn');

            if (saveBtn) saveBtn.classList.toggle('hidden', !!readonly);

            const plusBtn = document.getElementById('ajouterAutreReglementBtn');

            if (plusBtn) {

                plusBtn.classList.toggle('hidden', !!readonly);

                plusBtn.toggleAttribute('disabled', !!readonly);

            }

            document.getElementById('rgPhotoPickBtn')?.toggleAttribute('disabled', !!readonly);

            const title = document.getElementById('reglementFormTitle');

            const subtitle = document.getElementById('reglementFormSubtitle');

            if (readonly) {

                if (title) title.textContent = 'Consulter Réglement';

                if (subtitle) subtitle.textContent = 'Lecture seule';

            } else if (editingReglementId) {

                if (title) title.textContent = 'Modifier Réglement';

                if (subtitle) subtitle.textContent = editingReglementId;

            } else {

                if (title) title.textContent = 'Réglement Achat';

                if (subtitle) subtitle.textContent = 'Barre de saisie';

            }

        }



        function showReglementConsult() {

            reglementFormPanel?.classList.add('hidden');

            reglementConsultPanel?.classList.remove('hidden');

            renderReglementsTable();

        }



        function showReglementForm(item = null, readonly = false) {

            loadCommandesAchats();

            reglementFormPanel?.classList.remove('hidden');

            reglementConsultPanel?.classList.add('hidden');

            editingReglementId = item?.id || null;



            let selectedBon = '';

            if (item?.bon) {

                const idx = commandesAchats.findIndex(c => c.bon === item.bon);

                if (idx >= 0) selectedBon = String(idx);

            }

            fillReglementBonsSelect(selectedBon);



            document.getElementById('rg_date').value = item?.date || todayInputValue();

            document.getElementById('rg_ref').value = item?.ref || nextReglementRef();

            document.getElementById('rg_type').value = item?.type_reg || '';

            document.getElementById('rg_num').value = item?.num_reg || nextReglementNum();

            document.getElementById('rg_banque').value = item?.banque || '';

            document.getElementById('rg_tire').value = item?.tire || '';

            document.getElementById('rg_montant_reg').value = item?.montant_reg != null && item?.montant_reg !== ''

                ? parseFloat(item.montant_reg).toFixed(2) : '';

            document.getElementById('rg_date_decaiss').value = item?.date_decaiss || '';

            document.getElementById('rg_bon_num').value = item?.bon || '';

            document.getElementById('rg_fournisseur').value = item?.fournisseur || '';

            document.getElementById('rg_montant_bon').value = item?.montant != null ? item.montant : '';

            setReglementPhotoPreview(item?.photo || '');

            if (selectedBon) applySelectedBonToReglementForm();

            if (item?.montant_reg != null && item?.montant_reg !== '') {

                document.getElementById('rg_montant_reg').value = parseFloat(item.montant_reg).toFixed(2);

            }

            setReglementFormReadonly(readonly);

        }



        function collectReglementForm() {

            return {

                id: editingReglementId || ('rg_' + Date.now()),

                date: document.getElementById('rg_date')?.value || '',

                ref: document.getElementById('rg_ref')?.value || nextReglementRef(),

                fournisseur: document.getElementById('rg_fournisseur')?.value || '',

                bon: document.getElementById('rg_bon_num')?.value || '',

                montant: document.getElementById('rg_montant_bon')?.value || '',

                type_reg: document.getElementById('rg_type')?.value || '',

                num_reg: (document.getElementById('rg_num')?.value || '').trim(),

                banque: document.getElementById('rg_banque')?.value || '',

                tire: (document.getElementById('rg_tire')?.value || '').trim(),

                montant_reg: document.getElementById('rg_montant_reg')?.value || '',

                date_decaiss: document.getElementById('rg_date_decaiss')?.value || '',

                photo: reglementPhotoDataUrl || '',

            };

        }



        function validateReglement(data) {

            if (!data.date) { alert('La date est obligatoire.'); document.getElementById('rg_date')?.focus(); return false; }

            if (!data.bon) { alert('Sélectionnez un bon non soldé.'); document.getElementById('rg_bon')?.focus(); return false; }

            if (!data.type_reg) { alert('Le type de réglement est obligatoire.'); document.getElementById('rg_type')?.focus(); return false; }

            if (!data.montant_reg || parseFloat(data.montant_reg) <= 0) {

                alert('Le montant du réglement est obligatoire.');

                document.getElementById('rg_montant_reg')?.focus();

                return false;

            }

            return true;

        }



        function markBonPayeIfFullySettled(bon) {

            if (!bon) return;

            const idx = commandesAchats.findIndex(c => c.bon === bon);

            if (idx < 0) return;

            const totalBon = parseFloat(commandesAchats[idx].total || 0) || 0;

            const totalReg = reglementsAchats

                .filter(r => r.bon === bon)

                .reduce((s, r) => s + (parseFloat(r.montant_reg) || 0), 0);

            if (totalReg + 0.001 >= totalBon) {

                commandesAchats[idx].paye = true;

                persistCommandesAchats();

            }

        }



        function renderReglementsTable() {

            if (!reglementsTableBody) return;

            if (!reglementsAchats.length) {

                reglementsTableBody.innerHTML = '<tr><td colspan="12" class="fournisseur-empty">Aucun réglement enregistré</td></tr>';

                return;

            }

            reglementsTableBody.innerHTML = reglementsAchats.map(r => `

                <tr>

                    <td>${escHtml(formatDateFr(r.date))}</td>

                    <td>${escHtml(r.ref || '—')}</td>

                    <td>${escHtml(r.fournisseur || '—')}</td>

                    <td>${escHtml(r.bon || '—')}</td>

                    <td>${escHtml(formatMoneyFr(r.montant))}</td>

                    <td>${escHtml(r.type_reg || '—')}</td>

                    <td>${escHtml(r.num_reg || '—')}</td>

                    <td>${escHtml(r.banque || '—')}</td>

                    <td>${escHtml(r.tire || '—')}</td>

                    <td>${escHtml(formatMoneyFr(r.montant_reg))}</td>

                    <td>${escHtml(formatDateFr(r.date_decaiss))}</td>

                    <td class="col-actions no-print-reglement">

                        <span class="col-actions-wrap">

                            <button type="button" class="btn-icon-row btn-icon-view" data-view-reg="${escHtml(r.id)}" title="Voir" aria-label="Voir">

                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"/><circle cx="12" cy="12" r="3"/></svg>

                            </button>

                            <button type="button" class="btn-icon-row btn-icon-edit" data-edit-reg="${escHtml(r.id)}" title="Modifier" aria-label="Modifier">

                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>

                            </button>

                            <button type="button" class="btn-icon-row btn-icon-delete" data-delete-reg="${escHtml(r.id)}" title="Supprimer" aria-label="Supprimer">

                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>

                            </button>

                            <button type="button" class="btn-icon-row btn-icon-pdf" data-pdf-reg="${escHtml(r.id)}" title="PDF" aria-label="PDF">

                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>

                            </button>

                        </span>

                    </td>

                </tr>

            `).join('');



            reglementsTableBody.querySelectorAll('[data-view-reg]').forEach(btn => {

                btn.addEventListener('click', () => {

                    const item = reglementsAchats.find(x => x.id === btn.dataset.viewReg);

                    if (item) showReglementForm(item, true);

                });

            });

            reglementsTableBody.querySelectorAll('[data-edit-reg]').forEach(btn => {

                btn.addEventListener('click', () => {

                    const item = reglementsAchats.find(x => x.id === btn.dataset.editReg);

                    if (item) showReglementForm(item, false);

                });

            });

            reglementsTableBody.querySelectorAll('[data-delete-reg]').forEach(btn => {

                btn.addEventListener('click', () => deleteReglement(btn.dataset.deleteReg));

            });

            reglementsTableBody.querySelectorAll('[data-pdf-reg]').forEach(btn => {

                btn.addEventListener('click', () => exportReglementPdf(btn.dataset.pdfReg));

            });

        }



        function deleteReglement(id) {

            if (!confirm('Supprimer ce réglement ?')) return;

            reglementsAchats = reglementsAchats.filter(r => r.id !== id);

            saveReglementsAchats();

            renderReglementsTable();

        }



        function exportReglementPdf(id) {

            const r = reglementsAchats.find(x => x.id === id);

            if (!r) return;

            if (typeof window.jspdf === 'undefined' && typeof window.jsPDF === 'undefined') {

                // fallback print single row context

                alert('Export PDF: ' + (r.ref || id));

                return;

            }

            try {

                const JsPDF = window.jspdf?.jsPDF || window.jsPDF;

                const doc = new JsPDF({ orientation: 'landscape', unit: 'pt', format: 'a4' });

                doc.setFontSize(14);

                doc.text('Réglement Achat — ' + (r.ref || ''), 40, 40);

                doc.setFontSize(10);

                const lines = [

                    `Date: ${formatDateFr(r.date)}`,

                    `Fournisseur: ${r.fournisseur || '—'}`,

                    `Bon N°: ${r.bon || '—'}`,

                    `Montant bon: ${formatMoneyFr(r.montant)}`,

                    `Type Rég: ${r.type_reg || '—'}`,

                    `N° Rég: ${r.num_reg || '—'}`,

                    `Banque: ${r.banque || '—'}`,

                    `Tiré: ${r.tire || '—'}`,

                    `Montant Rég: ${formatMoneyFr(r.montant_reg)}`,

                    `Date Décaiss: ${formatDateFr(r.date_decaiss)}`,

                ];

                lines.forEach((line, i) => doc.text(line, 40, 70 + i * 18));

                doc.save(`reglement-${r.ref || id}.pdf`);

            } catch (err) {

                console.error(err);

                alert('Impossible de générer le PDF.');

            }

        }



        function openReglementAchats() {

            loadReglementsAchats();

            loadCommandesAchats();

            showReglementConsult();

        }



        function saveReglementFromForm(keepFormOpen = false) {

            if (reglementReadonly) return false;

            applySelectedBonToReglementForm();

            const data = collectReglementForm();

            if (!validateReglement(data)) return false;

            const idx = reglementsAchats.findIndex(r => r.id === data.id);

            if (idx >= 0) reglementsAchats[idx] = data;

            else reglementsAchats.unshift(data);

            saveReglementsAchats();

            markBonPayeIfFullySettled(data.bon);

            const toast = document.getElementById('cartToast');

            if (toast) {

                toast.textContent = 'Réglement ' + data.ref + ' enregistré';

                toast.classList.add('show');

                setTimeout(() => toast.classList.remove('show'), 2800);

            }

            if (keepFormOpen) showReglementForm(null, false);

            else showReglementConsult();

            return true;

        }



        function validerReglement(e) {

            e?.preventDefault();

            saveReglementFromForm(false);

        }



        function ajouterAutreReglement(e) {

            e?.preventDefault();

            saveReglementFromForm(true);

        }



        document.getElementById('ajouterReglementBtn')?.addEventListener('click', () => showReglementForm(null, false));

        document.getElementById('fermerReglementForm')?.addEventListener('click', () => showReglementConsult());

        document.getElementById('fermerReglementConsultBtn')?.addEventListener('click', () => {

            document.querySelector('.nav-item[data-view="dashboard"]')?.click();

        });

        ficheReglementForm?.addEventListener('submit', validerReglement);

        document.getElementById('validerReglementBtn')?.addEventListener('click', validerReglement);

        document.getElementById('ajouterAutreReglementBtn')?.addEventListener('click', ajouterAutreReglement);

        document.getElementById('rg_bon')?.addEventListener('change', applySelectedBonToReglementForm);

        document.getElementById('rgPhotoPickBtn')?.addEventListener('click', () => document.getElementById('rg_photo_file')?.click());

        document.getElementById('rg_photo_file')?.addEventListener('change', (e) => {

            const file = e.target.files?.[0];

            if (!file) return;

            const reader = new FileReader();

            reader.onload = () => setReglementPhotoPreview(String(reader.result || ''));

            reader.readAsDataURL(file);

        });





        const categoryCatalog = {



            coque: {



                title: '?? Fruits ? coque (Noix et graines nobles)',



                products: [



                    { name: 'Amandes Premium Californie', image: 'https://images.unsplash.com/photo-1508747703725-719777637510?w=500&q=80', price: '185 MAD/kg', desc: 'Amandes enti?res s?lectionn?es, croquantes et riches en nutriments. Id?ales en snack ou p?tisserie fine.' },



                    { name: 'Noix de Cajou W320', image: 'https://images.unsplash.com/photo-1599599810769-bda6a6a30469?w=500&q=80', price: '220 MAD/kg', desc: 'Noix de cajou enti?res de grade sup?rieur, saveur douce et texture onctueuse.' },



                    { name: 'Noix de Grenoble AOP', image: 'https://images.unsplash.com/photo-1559181567-c3190ca9959b?w=500&q=80', price: '195 MAD/kg', desc: 'Noix d?cortiqu?es, chair ferme et go?t authentique des terroirs montagnards.' },



                    { name: 'Pistaches de Sicile', image: 'https://images.unsplash.com/photo-1576045057995-568f588f82fb?w=500&q=80', price: '280 MAD/kg', desc: 'Pistaches naturellement ouvertes, ar?me intense et qualit? gastronomique.' },



                ],



            },



            seche: {



                title: '?? Fruits s?ch?s',



                products: [



                    { name: 'Abricots secs de Turquie', image: 'https://images.unsplash.com/photo-1587049352846-83a3988c6791?w=500&q=80', price: '95 MAD/kg', desc: 'Abricots moelleux, l?g?rement acidul?s, s?ch?s au soleil sans conservateurs.' },



                    { name: 'Dattes Medjool Premium', image: 'https://images.unsplash.com/photo-1585335208606-c7c710a45d9d?w=500&q=80', price: '145 MAD/kg', desc: 'Dattes extra moelleuses, caramel naturel, parfaites pour le Ramadan et le petit-d?jeuner.' },



                    { name: 'Figues s?ch?es Izmir', image: 'https://images.unsplash.com/photo-1606312619070-d48b4c652765?w=500&q=80', price: '110 MAD/kg', desc: 'Figues blanches tendres, saveur miell?e, source naturelle de fibres.' },



                    { name: 'Raisins secs Golden', image: 'https://images.unsplash.com/photo-1571068316344-75bc76f77890?w=500&q=80', price: '75 MAD/kg', desc: 'Raisins dor?s juteux, douceur naturelle pour muesli, salades et desserts.' },



                ],



            },



            cacahuetes: {



                title: '?? Cacahu?tes et d?riv?s',



                products: [



                    { name: 'Cacahu?tes grill?es sal?es', image: 'https://images.unsplash.com/photo-1553627862-fbb7dd4c7102?w=500&q=80', price: '55 MAD/kg', desc: 'Cacahu?tes croustillantes, l?g?rement sal?es, parfaites pour l\'ap?ritif.' },



                    { name: 'Cacahu?tes nature bio', image: 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=500&q=80', price: '65 MAD/kg', desc: 'Cacahu?tes d?cortiqu?es, sans sel, certifi?es agriculture biologique.' },



                    { name: 'Beurre de cacahu?te cr?meux', image: 'https://images.unsplash.com/photo-1599599810769-bda6a6a30469?w=500&q=80', price: '89 MAD/pot', desc: 'Pur?e 100% cacahu?tes, texture onctueuse, sans huile de palme ajout?e.' },



                ],



            },



            graines: {



                title: '?? Graines alimentaires',



                products: [



                    { name: 'Graines de tournesol d?cortiqu?es', image: 'https://images.unsplash.com/photo-1518843875459-f738682238c6?w=500&q=80', price: '45 MAD/kg', desc: 'Graines fra?ches, riches en vitamine E, id?ales en salade ou snack.' },



                    { name: 'Graines de courge', image: 'https://images.unsplash.com/photo-1608797178972-15b33a581138?w=500&q=80', price: '120 MAD/kg', desc: 'Graines de courge premium, go?t de noisette, source de zinc et magn?sium.' },



                    { name: 'Graines de chia bio', image: 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?w=500&q=80', price: '95 MAD/kg', desc: 'Graines noires de chia, super-aliment pour smoothies et bowls healthy.' },



                    { name: 'M?lange de graines gourmet', image: 'https://images.unsplash.com/photo-1608797178972-15b33a581138?w=500&q=80', price: '85 MAD/kg', desc: 'Assortiment tournesol, lin et s?same pour une nutrition compl?te.' },



                ],



            },



            enrobes: {



                title: '?? Fruits secs enrob?s et confiseries',



                products: [



                    { name: 'Amandes enrob?es chocolat noir', image: 'https://images.unsplash.com/photo-1548365328-0f4e0977132a?w=500&q=80', price: '165 MAD/kg', desc: 'Amandes enti?res enrob?es de chocolat noir 70%, alliance croquante et intense.' },



                    { name: 'Raisins secs enrob?s chocolat au lait', image: 'https://images.unsplash.com/photo-1481391319762-47dff72954d9?w=500&q=80', price: '135 MAD/kg', desc: 'Raisins moelleux napp?s de chocolat au lait belge, douceur gourmande.' },



                    { name: 'Dattes fourr?es amandes', image: 'https://images.unsplash.com/photo-1585335208606-c7c710a45d9d?w=500&q=80', price: '175 MAD/kg', desc: 'Dattes Medjool farcies d\'amandes enti?res, cr?ation artisanale de luxe.' },



                    { name: 'Orangettes au chocolat', image: 'https://images.unsplash.com/photo-1607922267115-ed5d32ecbc2c?w=500&q=80', price: '155 MAD/kg', desc: '?corces d\'orange confites enrob?es de chocolat noir, saveur raffin?e.' },



                ],



            },



            ramadan: {



                title: '?? Produits Ramadan et F?tes',



                products: [



                    { name: 'Coffret Ramadan Prestige', image: 'https://images.unsplash.com/photo-1608797178972-15b33a581138?w=500&q=80', price: '450 MAD', desc: 'Coffret assortiment dattes, noix et fruits secs, pr?sentation ?l?gante pour l\'Iftar.' },



                    { name: 'Chebakia artisanale (500g)', image: 'https://images.unsplash.com/photo-1596040033229-a9821ebd058d?w=500&q=80', price: '85 MAD', desc: 'Chebakia traditionnelle au miel et s?same, pr?par?e selon recette marocaine.' },



                    { name: 'Sellou aux amandes', image: 'https://images.unsplash.com/photo-1559181567-c3190ca9959b?w=500&q=80', price: '120 MAD/kg', desc: 'Sellou maison grill?, m?lange ?nerg?tique de farine, amandes et miel.' },



                    { name: 'Panier F?tes Sweet Austria', image: 'https://images.unsplash.com/photo-1599599810769-bda6a6a30469?w=500&q=80', price: '680 MAD', desc: 'Panier cadeau premium : fruits secs, confiseries et sp?cialit?s de f?te.' },



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



                        <p>${category.products.length} produit${category.products.length > 1 ? 's' : ''} ? d?couvrez notre s?lection avec photos</p>



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



            cartToast.textContent = `? ? ${productName} ? ajout? au panier`;



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



                manager: { name: 'Youssef El Amrani', role: 'Responsable Commercial R?gional', photo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=200&q=80' },



            },



            ouest: {



                zone: 'Zone Ouest',



                id: 'COM-OUE-002',



                name: 'Sara Idrissi',



                phone: '+212 6 23 45 67 89',



                photo: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=200&q=80',



                manager: { name: 'Mohamed Tazi', role: 'Responsable Commercial R?gional', photo: 'https://images.unsplash.com/photo-1519081900723-00d085d022a8?w=200&q=80' },



            },



            taza: {



                zone: 'Zone Taza, F?s',



                id: 'COM-TAZ-003',



                name: 'Hassan Alami',



                phone: '+212 6 34 56 78 90',



                photo: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&q=80',



                manager: { name: 'Fatima Bennani', role: 'Responsable Commercial R?gional', photo: 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=200&q=80' },



            },



            gharb: {



                zone: 'Zone El Gharb',



                id: 'COM-GHB-004',



                name: 'Nadia Chraibi',



                phone: '+212 6 45 67 89 01',



                photo: 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=200&q=80',



                manager: { name: 'Rachid Mansouri', role: 'Responsable Commercial R?gional', photo: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=200&q=80' },



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



            visitCardTitle.textContent = `Carte de visite ? ${c.zone}`;



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



                            <div class="visit-field-label">Num?ro de t?l?phone</div>



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



                regions: 'Casablanca, Rabat, F?s, Tanger',



                contact: 'partenariat@marjane.ma',



                image: 'https://images.unsplash.com/photo-1604719312566-8912e9227c6a?w=800&q=80',



                desc: 'Distribution nationale de nos gammes fruits secs premium dans les hypermarch?s Marjane.',



            },



            decathlon: {



                name: 'D?cathlon',



                type: 'Retail sport & nutrition',



                since: '2020',



                regions: 'Maroc (12 magasins)',



                contact: 'nutrition@decathlon.ma',



                image: 'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=800&q=80',



                desc: 'Partenaire nutrition sportive : mixes ?nerg?tiques et graines pour les rayons bien-?tre.',



            },



            atacadaw: {



                name: 'Atacadao',



                type: 'Cash & carry',



                since: '2019',



                regions: 'Nador, Oujda, T?touan',



                contact: 'commercial@atacadao.ma',



                image: 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=800&q=80',



                desc: 'Approvisionnement en vrac pour professionnels : h?tellerie, restauration et revendeurs.',



            },



        };



        function showPartnerProfile(partnerKey) {



            const p = partnerCatalog[partnerKey];



            if (!p || !visitCardModal) return;



            closeNavDropdowns();



            visitCardTitle.textContent = `Partenaire ? ${p.name}`;



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



                    <div class="visit-manager-title">? propos du partenariat</div>



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



        /* ===== Interface d'accueil / Connexion ===== */



        (function initLanding() {



            const landing = document.getElementById('landingScreen');



            const loginPanel = document.getElementById('loginPanel');



            
            applyLandingHabillage(loadSociete());

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



            // Bloque le scroll de l'app tant que l'accueil est affich?



            if (landing && !landing.classList.contains('is-hidden')) {



                document.body.style.overflow = 'hidden';



            }



        })();



    </script>



</body>



</html>



