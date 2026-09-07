<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Portal Customer Pengujian Lingkungan | PT Envirotama Solusindo (LP-1813-IDN)</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=3">
    <link rel="stylesheet" href="{{ asset('vendor/fonts/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('mobile-nav.css') }}">

    <style>
        :root {
            --primary: #059669;
            --primary-hover: #047857;
            --primary-light: #ecfdf5;
            --secondary: #0284c7;
            --slate-900: #0f172a;
            --slate-800: #1e293b;
            --slate-700: #334155;
            --slate-600: #475569;
            --slate-500: #64748b;
            --slate-100: #f1f5f9;
            --slate-50: #f8fafc;
            --success: #10b981;
            --success-light: #d1fae5;
            --warning: #f59e0b;
            --warning-light: #fef3c7;
            --danger: #ef4444;
            --danger-light: #fee2e2;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            background-image: 
                radial-gradient(at 0% 0%, rgba(13, 148, 136, 0.10) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(2, 132, 199, 0.10) 0px, transparent 50%),
                radial-gradient(at 50% 100%, rgba(15, 76, 129, 0.08) 0px, transparent 50%),
                radial-gradient(circle 900px at 100% 60%, rgba(0, 187, 167, 0.06), transparent),
                linear-gradient(rgba(203, 213, 225, 0.4) 1px, transparent 1px),
                linear-gradient(90deg, rgba(203, 213, 225, 0.4) 1px, transparent 1px);
            background-size: 100% 100%, 100% 100%, 100% 100%, 100% 100%, 36px 36px, 36px 36px;
            background-attachment: fixed;
            color: var(--slate-800);
            min-height: 100vh;
        }

        /* --- 2-TIER TOP NAVBAR (BRAND & USER TOP, MENU TABS BOTTOM) --- */
        .portal-navbar {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 15px rgba(15, 23, 42, 0.04);
        }

        .navbar-top-tier {
            max-width: 1440px;
            margin: 0 auto;
            padding: 8px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            border-bottom: 1px solid #f1f5f9;
        }

        .brand-container {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }
        .brand-logo-img {
            height: 42px;
            width: auto;
            max-width: 150px;
            object-fit: contain;
        }
        .brand-info-text {
            display: flex;
            flex-direction: column;
        }
        .brand-title {
            font-size: 0.88rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: 0.3px;
            line-height: 1.2;
        }
        .brand-sub {
            font-size: 0.7rem;
            color: #059669;
            font-weight: 700;
        }

        .navbar-right-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .navbar-bottom-tier {
            max-width: 1440px;
            margin: 0 auto;
            padding: 5px 18px;
            display: flex;
            align-items: center;
            overflow-x: auto;
            scrollbar-width: none;
        }
        .navbar-bottom-tier::-webkit-scrollbar { display: none; }

        .top-nav-menu {
            display: flex;
            align-items: center;
            gap: 6px;
            width: 100%;
            flex-wrap: wrap;
        }

        .nav-tab-btn {
            background: transparent;
            border: 1px solid transparent;
            color: #475569;
            padding: 6px 13px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
            white-space: nowrap;
            text-decoration: none;
            position: relative;
            user-select: none;
        }

        .nav-tab-btn i {
            font-size: 0.9rem;
            transition: transform 0.2s ease;
        }

        .nav-tab-btn:hover {
            background: #f1f5f9;
            color: #059669;
            transform: translateY(-1px);
        }

        .nav-tab-btn:hover i {
            transform: scale(1.15);
        }

        .nav-tab-btn.active {
            background: #ecfdf5;
            color: #059669;
            box-shadow: 0 2px 8px rgba(5, 150, 105, 0.15);
            border: 1px solid #a7f3d0;
            font-weight: 800;
        }

        .nav-badge {
            padding: 2px 7px;
            border-radius: 99px;
            font-size: 0.65rem;
            font-weight: 800;
            color: white;
            line-height: 1;
        }

        .btn-portal-switch {
            background: linear-gradient(135deg, #0284c7, #0369a1);
            color: white;
            padding: 6px 13px;
            border-radius: 8px;
            font-size: 0.76rem;
            font-weight: 800;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 8px rgba(2, 132, 199, 0.25);
            transition: all 0.2s ease;
        }
        .btn-portal-switch:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(2, 132, 199, 0.4);
            color: white;
        }

        .switch-badge {
            background: rgba(255, 255, 255, 0.25);
            padding: 1px 5px;
            border-radius: 5px;
            font-size: 0.65rem;
            font-weight: 800;
        }

        /* --- CENTER PORTAL IDENTITY BADGE IN NAVBAR --- */
        .portal-identity-badge {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 6px 18px;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(5, 150, 105, 0.08), rgba(16, 185, 129, 0.12));
            border: 1.5px solid rgba(5, 150, 105, 0.3);
            box-shadow: 0 2px 10px rgba(5, 150, 105, 0.06);
            transition: all 0.2s ease;
        }

        .portal-identity-badge.identity-kalibrasi {
            background: linear-gradient(135deg, rgba(2, 132, 199, 0.08), rgba(14, 165, 233, 0.12));
            border: 1.5px solid rgba(2, 132, 199, 0.3);
            box-shadow: 0 2px 10px rgba(2, 132, 199, 0.06);
        }

        .portal-identity-badge .identity-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            color: white;
            background: linear-gradient(135deg, #059669, #047857);
            box-shadow: 0 3px 8px rgba(5, 150, 105, 0.35);
            flex-shrink: 0;
        }

        .portal-identity-badge.identity-kalibrasi .identity-icon {
            background: linear-gradient(135deg, #0284c7, #0369a1);
            box-shadow: 0 3px 8px rgba(2, 132, 199, 0.35);
        }

        .portal-identity-badge .identity-text {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        .portal-identity-badge .identity-title {
            font-size: 0.88rem;
            font-weight: 900;
            color: #0f172a;
            letter-spacing: 0.3px;
            line-height: 1.2;
        }

        .portal-identity-badge .identity-sub {
            font-size: 0.68rem;
            font-weight: 700;
            color: #059669;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .portal-identity-badge.identity-kalibrasi .identity-sub {
            color: #0284c7;
        }

        @media (max-width: 960px) {
            .portal-identity-badge {
                display: none;
            }
        }

        .user-pill-dropdown {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            padding: 4px 10px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .user-avatar-small {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #059669;
            color: white;
            font-weight: 800;
            font-size: 0.78rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .user-company-text {
            max-width: 170px;
            line-height: 1.2;
        }
        .user-company-text .company-name {
            font-size: 0.75rem;
            font-weight: 800;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .user-company-text .company-role {
            font-size: 0.62rem;
            font-weight: 600;
            color: #059669;
        }

        .btn-logout-pill {
            background: #fee2e2;
            border: none;
            color: #dc2626;
            width: 26px;
            height: 26px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.76rem;
        }
        .btn-logout-pill:hover {
            background: #ef4444;
            color: white;
            transform: scale(1.08);
        }

        .main-container {
            max-width: 1440px;
            margin: 0 auto;
            padding: 10px 18px;
        }

        @media (max-width: 1024px) {
            .navbar-top-tier { flex-direction: column; align-items: flex-start; gap: 10px; }
            .navbar-right-actions { width: 100%; justify-content: space-between; }
        }

        .hero-quick-boxes {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 10px;
            margin-top: 16px;
        }

        .hero-quick-box {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 2px 6px rgba(0,0,0,0.03);
        }

        .hero-quick-box:hover {
            transform: translateY(-2px) scale(1.02);
            border-color: #0d9488;
            box-shadow: 0 6px 16px rgba(13, 148, 136, 0.12);
        }

        .quick-box-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        .quick-box-title {
            font-size: 0.76rem;
            font-weight: 800;
            color: #0f172a;
            display: block;
            line-height: 1.2;
            white-space: nowrap;
        }

        .quick-box-sub {
            font-size: 0.64rem;
            color: #64748b;
            font-weight: 600;
            display: block;
            margin-top: 1px;
            white-space: nowrap;
        }

        /* --- NEXT-LEVEL ANIMATIONS, 3D & FLOATING PARTICLES --- */
        .hero-banner-container {
            position: relative;
            background: linear-gradient(115deg, #0f4c81 0%, #1e3a8a 45%, #00bba7 100%);
            border-radius: 24px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 16px 40px -10px rgba(15, 23, 42, 0.22), 0 0 25px rgba(0, 187, 167, 0.12);
            border: 1.5px solid rgba(255, 255, 255, 0.25);
            overflow: hidden;
            perspective: 1200px;
        }

        .floating-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(45px);
            pointer-events: none;
            z-index: 1;
            opacity: 0.55;
            transition: transform 0.2s cubic-bezier(0.2, 0.8, 0.2, 1);
        }
        .floating-orb-1 {
            width: 280px;
            height: 280px;
            background: radial-gradient(circle, rgba(0, 187, 167, 0.5) 0%, rgba(15, 76, 129, 0) 70%);
            top: -60px;
            right: 12%;
        }
        .floating-orb-2 {
            width: 240px;
            height: 240px;
            background: radial-gradient(circle, rgba(56, 189, 248, 0.45) 0%, rgba(30, 58, 138, 0) 70%);
            bottom: -50px;
            left: 8%;
        }

        .floating-3d-badge {
            position: absolute;
            z-index: 3;
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(12px);
            border: 1.5px solid rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            padding: 6px 13px;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 0.73rem;
            font-weight: 800;
            color: #0f172a;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15);
            pointer-events: none;
            transition: transform 0.15s ease-out;
        }
        .float-badge-1 {
            top: 18px;
            right: 28px;
            animation: floatAnim 4.5s ease-in-out infinite;
        }
        .float-badge-2 {
            bottom: 22px;
            left: 28px;
            animation: floatAnimReverse 5s ease-in-out infinite;
        }

        @keyframes floatAnim {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-7px) rotate(1.5deg); }
        }
        @keyframes floatAnimReverse {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(7px) rotate(-1.5deg); }
        }

        .tilt-card-3d {
            transition: transform 0.22s cubic-bezier(0.2, 0.8, 0.2, 1), box-shadow 0.22s ease;
            transform-style: preserve-3d;
            position: relative;
        }
        .tilt-card-3d:hover {
            box-shadow: 0 14px 30px rgba(15, 23, 42, 0.1), 0 0 15px rgba(13, 148, 136, 0.1);
        }

        .btn-action-shimmer {
            position: relative;
            overflow: hidden;
        }
        .btn-action-shimmer::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(60deg, transparent 30%, rgba(255, 255, 255, 0.35) 50%, transparent 70%);
            transform: rotate(30deg) translateX(-100%);
            animation: shimmerSweep 4.5s infinite;
        }
        @keyframes shimmerSweep {
            0%, 65% { transform: rotate(30deg) translateX(-100%); }
            100% { transform: rotate(30deg) translateX(100%); }
        }

        .live-pulse-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10b981;
            display: inline-block;
            position: relative;
            flex-shrink: 0;
        }
        .live-pulse-dot::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            background: rgba(16, 185, 129, 0.6);
            animation: pulseRing 1.8s cubic-bezier(0.24, 0, 0.38, 1) infinite;
        }
        @keyframes pulseRing {
            0% { transform: scale(0.5); opacity: 1; }
            100% { transform: scale(2.4); opacity: 0; }
        }

        /* NEXT-LEVEL 4-STEP SERVICE WORKFLOW PIPELINE CARD */
        .service-pipeline-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 18px;
            padding: 16px 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(15, 23, 42, 0.04);
            position: relative;
            overflow: hidden;
        }
        .pipeline-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 14px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f1f5f9;
        }
        .pipeline-steps-flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            position: relative;
        }
        @media (max-width: 992px) {
            .pipeline-steps-flex {
                flex-wrap: wrap;
            }
            .pipeline-arrow {
                display: none;
            }
        }
        .pipeline-step-item {
            flex: 1 1 200px;
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 14px;
            padding: 12px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            position: relative;
            transition: all 0.22s cubic-bezier(0.34, 1.56, 0.64, 1);
            text-decoration: none;
        }
        .pipeline-arrow {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-size: 1.15rem;
            flex-shrink: 0;
            padding: 0 4px;
            animation: arrowPulse 2s ease-in-out infinite;
        }
        @keyframes arrowPulse {
            0%, 100% { transform: translateX(0); opacity: 0.6; color: #94a3b8; }
            50% { transform: translateX(5px); opacity: 1; color: #0d9488; }
        }
        .pipeline-step-item:hover {
            background: #ffffff;
            border-color: #0d9488;
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 20px rgba(13, 148, 136, 0.12);
        }
        .pipeline-step-item.active {
            background: #f0fdfa;
            border-color: #5eead4;
            box-shadow: 0 4px 14px rgba(13, 148, 136, 0.08);
        }
        .step-num-badge {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #0d9488;
            color: white;
            font-size: 0.7rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: -7px;
            left: -7px;
            box-shadow: 0 2px 6px rgba(13, 148, 136, 0.35);
        }
        .step-icon-wrap {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }
        .step-details {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }
        .step-title {
            font-size: 0.78rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 2px;
        }
        /* --- STATS OVERVIEW (INFORMASI AKTUAL) --- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 14px;
            margin-bottom: 22px;
        }

        .stat-card {
            background: #ffffff;
            padding: 16px 18px;
            border-radius: 16px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.05);
            border: 1.5px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 14px;
            transition: all 0.22s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            overflow: hidden;
        }

        .stat-card.card-accent-emerald {
            border-top: 4px solid #0d9488;
        }
        .stat-card.card-accent-green {
            border-top: 4px solid #10b981;
        }
        .stat-card.card-accent-amber {
            border-top: 4px solid #f59e0b;
        }
        .stat-card.card-accent-red {
            border-top: 4px solid #ef4444;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
            border-color: #cbd5e1;
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .stat-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .stat-info h4 {
            font-size: 0.73rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            font-weight: 800;
            margin: 0;
        }

        .stat-info .value {
            font-size: 1.5rem;
            font-weight: 900;
            color: #0f172a;
            line-height: 1.1;
        }

        .stat-tag {
            font-size: 0.64rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin-top: 2px;
        }

        /* --- CARDS & TABLES --- */
        .card {
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 4px -1px rgba(0,0,0,0.02);
            padding: 14px 18px;
            margin-bottom: 14px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding-bottom: 10px;
            border-bottom: 1px solid #f1f5f9;
            flex-wrap: wrap;
            gap: 10px;
        }

        .card-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--slate-900);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-action {
            background: var(--primary);
            color: white;
            padding: 6px 13px;
            border-radius: 8px;
            border: none;
            font-weight: 700;
            font-size: 0.76rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-action:hover {
            background: var(--primary-hover);
        }

        table.custom-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.8rem;
        }

        table.custom-table th {
            background: #f8fafc;
            color: var(--slate-600);
            padding: 9px 12px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.68rem;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e2e8f0;
        }

        table.custom-table td {
            padding: 9px 12px;
            border-bottom: 1px solid #f1f5f9;
            color: var(--slate-700);
            vertical-align: middle;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 0.68rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-warning { background: #fef3c7; color: #b45309; }
        .badge-info { background: #e0f2fe; color: #0369a1; }
        .badge-neutral { background: #f1f5f9; color: #475569; }

        .pipeline-bar {
            display: flex;
            gap: 4px;
            background: #f1f5f9;
            padding: 3px;
            border-radius: 6px;
            margin-top: 4px;
        }

        .pipeline-step {
            flex: 1;
            height: 5px;
            border-radius: 3px;
            background: #cbd5e1;
        }

        .pipeline-step.done { background: #10b981; }
        .pipeline-step.active { background: #059669; }

        .tab-content { display: none; }
        .tab-content.active { display: block; animation: fadeIn 0.25s ease-out; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* --- STATS CARDS --- */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
            gap: 10px;
            margin-bottom: 14px;
        }

        .stat-card {
            background: #ffffff;
            padding: 10px 14px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.05rem;
            flex-shrink: 0;
        }

        .stat-info h4 {
            font-size: 0.72rem;
            color: var(--slate-600);
            font-weight: 700;
            margin-bottom: 1px;
        }

        .stat-info .value {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--slate-900);
            line-height: 1.1;
        }

        /* --- MODAL POPUPS (FIXED OVERLAY) --- */
        .modal-backdrop-custom {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            padding: 20px;
            opacity: 0;
            transition: opacity 0.25s ease;
        }

        .modal-backdrop-custom.active {
            display: flex;
            opacity: 1;
        }

        .modal-box-custom {
            background: #ffffff;
            border-radius: 20px;
            width: 100%;
            max-width: 680px;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.35);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.8);
            transform: scale(0.95);
            transition: transform 0.25s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .modal-backdrop-custom.active .modal-box-custom {
            transform: scale(1);
        }

        .modal-header-custom {
            padding: 16px 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .modal-body-custom {
            padding: 22px;
            max-height: 80vh;
            overflow-y: auto;
        }
    </style>
</head>
<body>

    <!-- 1. TOP CONTACT INFO STRIP -->
    <div style="background: #e6f4f1; border-bottom: 1px solid #cbd5e1; padding: 4px 18px; font-size: 0.74rem; color: #334155; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
        <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 5px; font-weight: 600;">
                <i class="fa-solid fa-envelope" style="color: #0284c7;"></i>
                <span>sales2@eslab.co.id</span>
            </div>
            <div style="display: flex; align-items: center; gap: 5px; font-weight: 600;">
                <i class="fa-solid fa-location-dot" style="color: #0284c7;"></i>
                <span>Jl. Jatiwangi-Cikedokan No. 84, Cikarang Barat, Bekasi, Jawa Barat</span>
            </div>
        </div>
        <a href="https://wa.me/6281380711482" target="_blank" style="background: linear-gradient(135deg, #0d9488, #0f766e); color: white; padding: 3px 12px; border-radius: 99px; text-decoration: none; font-weight: 800; font-size: 0.72rem; display: inline-flex; align-items: center; gap: 4px;">
            <i class="fa-brands fa-whatsapp"></i> Hubungi Kami
        </a>
    </div>

    <!-- 2. STICKY TOP NAVBAR (2-TIER: BRAND & USER TOP, MENU TABS BOTTOM) -->
    <header class="portal-navbar">
        <!-- TIER 1: BRAND LOGO (LEFT), CENTER IDENTITY (MIDDLE), & ACTIONS (RIGHT) -->
        <div class="navbar-top-tier">
            <div class="brand-container">
                <a href="javascript:void(0)" onclick="switchTab('tab-sampel')" style="display: inline-flex; align-items: center; text-decoration: none;">
                    <img src="{{ asset('images/logo_eslab.jpg') }}" alt="Envirotama Logo" class="brand-logo-img" onerror="this.onerror=null; this.src='{{ asset('logo_eslab.jpg') }}';">
                </a>
                <div class="brand-info-text">
                    <span class="brand-title">PT ENVIROTAMA SOLUSINDO</span>
                    <span class="brand-sub"><i class="fa-solid fa-flask-vial"></i> Laboratorium Pengujian Lingkungan</span>
                </div>
            </div>

            <!-- IDENTITAS BESAR PORTAL UTAMA DI HEADER TENGAH -->
            <div class="portal-identity-badge identity-pengujian">
                <div class="identity-icon">
                    <i class="fa-solid fa-flask-vial"></i>
                </div>
                <div class="identity-text">
                    <span class="identity-title">PORTAL UTAMA PENGUJIAN LINGKUNGAN</span>
                    <span class="identity-sub"><i class="fa-solid fa-certificate"></i> Akreditasi KAN LP-1813-IDN & ISO/IEC 17025</span>
                </div>
            </div>

            <div class="navbar-right-actions">
                <a href="{{ route('kalibrasi.klien.portal') }}" class="btn-portal-switch" title="Beralih ke Portal Kalibrasi Alat">
                    <i class="fa-solid fa-scale-balanced"></i>
                    <span>Buka Portal Kalibrasi</span>
                    <span class="switch-badge">LK-361</span>
                </a>

                <div class="user-pill-dropdown">
                    <div class="user-avatar-small">
                        {{ strtoupper(substr(session('client_company', 'C'), 0, 1)) }}
                    </div>
                    <div class="user-company-text" title="{{ session('client_company') }}">
                        <div class="company-name">{{ session('client_company') }}</div>
                        <div class="company-role">Pelanggan Terdaftar</div>
                    </div>
                    <form action="{{ route('klien.logout') }}" method="POST" style="margin:0;">
                        @csrf
                        <button type="submit" class="btn-logout-pill" title="Keluar Portal">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- TIER 2: HORIZONTAL MENU TABS ROW -->
        <div class="navbar-bottom-tier">
            <nav class="top-nav-menu">
                <button type="button" class="nav-tab-btn active" onclick="switchTab('tab-sampel', this)">
                    <i class="fa-solid fa-flask"></i>
                    <span>Status Sampel</span>
                    <span class="nav-badge" style="background: #059669;">{{ count($samples) }}</span>
                </button>

                <button type="button" class="nav-tab-btn" onclick="switchTab('tab-coa', this)">
                    <i class="fa-solid fa-file-contract"></i>
                    <span>Sertifikat CoA</span>
                    <span class="nav-badge" style="background: #059669;">{{ count($coas) }}</span>
                </button>

                <button type="button" class="nav-tab-btn" onclick="switchTab('tab-sampling-req', this)">
                    <i class="fa-solid fa-truck-droplet"></i>
                    <span>Permintaan Sampling</span>
                    <span class="nav-badge" style="background: #d97706;">{{ $stats['pending_sampling'] ?? 0 }}</span>
                </button>

                <button type="button" class="nav-tab-btn" onclick="switchTab('tab-lingkup-kan', this)">
                    <i class="fa-solid fa-layer-group"></i>
                    <span>Ruang Lingkup KAN</span>
                </button>

                <button type="button" class="nav-tab-btn" onclick="openVerifyModal()">
                    <i class="fa-solid fa-qrcode"></i>
                    <span>Cek Barcode</span>
                    <span class="nav-badge" style="background: #0284c7;">Valid</span>
                </button>

                <button type="button" class="nav-tab-btn" onclick="switchTab('tab-invoices', this)">
                    <i class="fa-solid fa-receipt"></i>
                    <span>Invoice</span>
                    <span class="nav-badge" style="background: #64748b;">{{ count($invoices) }}</span>
                </button>

                <button type="button" class="nav-tab-btn" onclick="switchTab('tab-pesan', this)">
                    <i class="fa-solid fa-comments"></i>
                    <span>Helpdesk</span>
                    <span class="nav-badge" style="background: #64748b;">{{ count($messages) }}</span>
                </button>
            </nav>
        </div>
    </header>

    <!-- MAIN CONTENT WRAPPER -->
    <main class="main-container">

            <!-- 3. HERO BANNER PORTAL KLIEN (NEXT-LEVEL 3D & FLOATING PARTICLES) -->
            <div class="hero-banner-container">
                <!-- AMBIENT FLOATING ORBS REACTING TO MOUSE -->
                <div class="floating-orb floating-orb-1"></div>
                <div class="floating-orb floating-orb-2"></div>

                <!-- FLOATING 3D INTERACTIVE BADGES -->
                <div class="floating-3d-badge float-badge-1">
                    <span class="live-pulse-dot"></span>
                    <i class="fa-solid fa-flask-vial" style="color: #0d9488;"></i>
                    <span>KAN LP-1813-IDN</span>
                </div>
                <div class="floating-3d-badge float-badge-2">
                    <i class="fa-solid fa-wind" style="color: #0284c7;"></i>
                    <span>Emisi & Mekanik Cal</span>
                </div>

                <div style="background: rgba(255, 255, 255, 0.98); backdrop-filter: blur(18px); border-radius: 18px; padding: 24px 28px; display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 24px; align-items: center; box-shadow: 0 10px 30px rgba(0,0,0,0.06); position: relative; z-index: 2;">
                    <div>
                        <div style="display: inline-flex; align-items: center; gap: 8px; background: #e6f4f1; color: #0d9488; font-weight: 800; font-size: 0.78rem; padding: 5px 14px; border-radius: 99px; margin-bottom: 10px; border: 1px solid rgba(13,148,136,0.25);">
                            <span class="live-pulse-dot"></span>
                            <i class="fa-solid fa-flask-vial"></i> Terakreditasi KAN LK-361-IDN & LP-1813-IDN
                        </div>
                        <h1 style="font-size: 1.65rem; font-weight: 900; color: #0f172a; line-height: 1.25; margin-bottom: 8px; letter-spacing: -0.02em;">
                            Lab Kalibrasi dan Pengujian Terakreditasi KAN
                        </h1>
                        <p style="color: #475569; font-size: 0.85rem; line-height: 1.5; margin-bottom: 16px; font-weight: 500;">
                            Portal Terpadu Pelanggan PT Envirotama Solusindo untuk Monitoring Pengujian Laboratorium LIMS, Tracking Kalibrasi Alat, Verifikasi Barcode, dan Unduh E-Sertifikat CoA Resmi 24/7.
                        </p>

                        <!-- QUICK ACTION BUTTONS & SMALL SHORTCUT TILES -->
                        <div style="display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                            <button type="button" onclick="switchTab('tab-sampling-req')" class="btn-action btn-action-shimmer" style="background: #0d9488; color: white; padding: 9px 20px; border-radius: 99px; font-weight: 800; font-size: 0.82rem; border: none; cursor: pointer; box-shadow: 0 4px 14px rgba(13,148,136,0.35);">
                                <i class="fa-solid fa-truck-droplet me-1"></i> Request Sampling / Uji
                            </button>
                            <a href="{{ route('kalibrasi.klien.portal') }}" class="btn-action" style="background: linear-gradient(135deg, #0284c7, #0369a1); color: white; padding: 9px 20px; border-radius: 99px; font-weight: 800; font-size: 0.82rem; text-decoration: none; display: inline-flex; align-items: center; box-shadow: 0 4px 12px rgba(2,132,199,0.3);">
                                <i class="fa-solid fa-scale-balanced me-1"></i> Buka Portal Kalibrasi Alat →
                            </a>
                        </div>

                        <!-- MENU KECIL KOTAK-KOTAK SHORTCUT KHUSUS PENGUJIAN -->
                        <div class="hero-quick-boxes">
                            <a href="javascript:void(0)" onclick="switchTab('tab-sampel')" class="hero-quick-box tilt-card-3d" title="Lihat Status Tracking Sampel Laboratorium">
                                <div class="quick-box-icon" style="background: #ecfdf5; color: #059669;">
                                    <i class="fa-solid fa-vials"></i>
                                </div>
                                <div class="quick-box-content">
                                    <span class="quick-box-title">Status Sampel</span>
                                    <span class="quick-box-sub">Monitoring LIMS & CoC</span>
                                </div>
                            </a>

                            <a href="javascript:void(0)" onclick="switchTab('tab-coa')" class="hero-quick-box tilt-card-3d" title="Unduh E-Sertifikat CoA Pengujian">
                                <div class="quick-box-icon" style="background: #d1fae5; color: #10b981;">
                                    <i class="fa-solid fa-file-shield"></i>
                                </div>
                                <div class="quick-box-content">
                                    <span class="quick-box-title">Sertifikat CoA</span>
                                    <span class="quick-box-sub">Unduh E-CoA Resmi</span>
                                </div>
                            </a>

                            <a href="javascript:void(0)" onclick="openVerifyModal()" class="hero-quick-box tilt-card-3d" title="Verifikasi Keaslian CoA Pengujian KAN LP-1813">
                                <div class="quick-box-icon" style="background: #e0f2fe; color: #0284c7;">
                                    <i class="fa-solid fa-shield-halved"></i>
                                </div>
                                <div class="quick-box-content">
                                    <span class="quick-box-title">Cek Keaslian COA</span>
                                    <span class="quick-box-sub">Scan QR Pengujian KAN</span>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- RIGHT VISUAL CARD WITH 3D TILT (PURE CSS/SVG GLASSMORPHISM - ZERO 404) -->
                    <div class="tilt-card-3d" style="position: relative; border-radius: 16px; overflow: hidden; height: 175px; box-shadow: 0 10px 25px rgba(5,150,105,0.25); display: flex; flex-direction: column; justify-content: space-between; background: linear-gradient(135deg, #0f172a 0%, #065f46 100%); padding: 18px; border: 1px solid rgba(52,211,153,0.3);">
                        <!-- AMBIENT GLOW IN CARD -->
                        <div style="position: absolute; top: -20px; right: -20px; width: 120px; height: 120px; background: radial-gradient(circle, rgba(52,211,153,0.4) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>
                        <div style="position: absolute; bottom: -30px; left: -10px; width: 100px; height: 100px; background: radial-gradient(circle, rgba(14,165,233,0.3) 0%, transparent 70%); border-radius: 50%; pointer-events: none;"></div>

                        <div style="position: relative; z-index: 2; display: flex; justify-content: space-between; align-items: flex-start;">
                            <div style="display: inline-flex; align-items: center; gap: 6px; background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); padding: 4px 10px; border-radius: 99px; font-size: 0.72rem; font-weight: 800; color: #6ee7b7; border: 1px solid rgba(52,211,153,0.3);">
                                <i class="fa-solid fa-award"></i> SNI ISO/IEC 17025:2017
                            </div>
                            <div style="width: 32px; height: 32px; background: rgba(5,150,105,0.35); border: 1px solid rgba(52,211,153,0.5); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: #a7f3d0; font-size: 1rem;">
                                <i class="fa-solid fa-flask-vial"></i>
                            </div>
                        </div>

                        <div style="position: relative; z-index: 2; color: white;">
                            <h4 style="font-weight: 900; font-size: 1.02rem; margin: 0 0 4px 0; letter-spacing: -0.01em; color: #ffffff;">
                                Laboratorium Pengujian Lingkungan
                            </h4>
                            <p style="font-size: 0.74rem; color: #a7f3d0; margin: 0; line-height: 1.35; font-weight: 500;">
                                <i class="fa-solid fa-circle-check" style="color: #34d399;"></i> LP-1813-IDN • Pengujian Kinerja Alat, Mekanik & Emisi Terstandar
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- NEXT-LEVEL 4-STEP INTERACTIVE SERVICE WORKFLOW PIPELINE -->
            <!-- 1. ALUR LAYANAN (WORKFLOW PIPELINE 4-STEP) -->
            <div class="service-pipeline-card">
                <div class="pipeline-header">
                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <span style="background: #e6f4f1; color: #0d9488; padding: 4px 10px; border-radius: 8px; font-weight: 800; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.05em; display: inline-flex; align-items: center; gap: 6px;">
                            <i class="fa-solid fa-diagram-project"></i> TAHAPAN PROSES (WORKFLOW)
                        </span>
                        <span style="font-weight: 800; font-size: 0.85rem; color: #0f172a;">
                            Alur Layanan Pengujian Laboratorium (SNI ISO/IEC 17025)
                        </span>
                    </div>
                    <span style="font-size: 0.72rem; color: #0d9488; font-weight: 700; background: #ffffff; padding: 4px 12px; border-radius: 99px; border: 1px solid #99f6e4; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                        <i class="fa-solid fa-arrow-pointer text-primary"></i> Klik langkah untuk langsung membuka menu
                    </span>
                </div>

                <div class="pipeline-steps-flex">
                    <!-- Step 1 -->
                    <div class="pipeline-step-item active" onclick="switchTab('tab-sampling-req')">
                        <div class="step-num-badge">1</div>
                        <div class="step-icon-wrap" style="background: #e6f4f1; color: #0d9488;">
                            <i class="fa-solid fa-truck-droplet"></i>
                        </div>
                        <div class="step-details">
                            <div class="step-title">Permintaan Sampling</div>
                            <div class="step-sub">Pengajuan sampling & uji online</div>
                        </div>
                    </div>

                    <!-- Arrow Connector 1 -->
                    <div class="pipeline-arrow" title="Lanjut ke Penerimaan & CoC">
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>

                    <!-- Step 2 -->
                    <div class="pipeline-step-item" onclick="switchTab('tab-sampel')">
                        <div class="step-num-badge">2</div>
                        <div class="step-icon-wrap" style="background: #e0f2fe; color: #0284c7;">
                            <i class="fa-solid fa-vials"></i>
                        </div>
                        <div class="step-details">
                            <div class="step-title">Penerimaan & CoC LIMS</div>
                            <div class="step-sub">Registrasi sampel & Chain of Custody</div>
                        </div>
                    </div>

                    <!-- Arrow Connector 2 -->
                    <div class="pipeline-arrow" title="Lanjut ke Analisis Lab">
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>

                    <!-- Step 3 -->
                    <div class="pipeline-step-item" onclick="switchTab('tab-lingkup-kan')">
                        <div class="step-num-badge">3</div>
                        <div class="step-icon-wrap" style="background: #fef3c7; color: #d97706;">
                            <i class="fa-solid fa-flask-vial"></i>
                        </div>
                        <div class="step-details">
                            <div class="step-title">Analisis Parameter Lab</div>
                            <div class="step-sub">Pengujian sesuai regulasi baku mutu</div>
                        </div>
                    </div>

                    <!-- Arrow Connector 3 -->
                    <div class="pipeline-arrow" title="Lanjut ke Penerbitan E-Sertifikat CoA">
                        <i class="fa-solid fa-arrow-right"></i>
                    </div>

                    <!-- Step 4 -->
                    <div class="pipeline-step-item" onclick="switchTab('tab-coa')">
                        <div class="step-num-badge">4</div>
                        <div class="step-icon-wrap" style="background: #f0fdf4; color: #16a34a;">
                            <i class="fa-solid fa-file-contract"></i>
                        </div>
                        <div class="step-details">
                            <div class="step-title">E-Sertifikat CoA Resmi</div>
                            <div class="step-sub">Unduh PDF & Validasi QR Barcode</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FLOATING WHATSAPP BUTTON -->
            <a href="https://wa.me/6281380711482" target="_blank" style="position: fixed; bottom: 24px; right: 24px; width: 52px; height: 52px; border-radius: 50%; background: #10b981; color: white; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; box-shadow: 0 6px 20px rgba(16,185,129,0.45); z-index: 9999; text-decoration: none; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'" title="Hubungi Kami via WhatsApp">
                <i class="fa-brands fa-whatsapp"></i>
            </a>

            <!-- 2. SECTION DIVIDER & HEADER: INFORMASI AKTUAL & STATISTIK REAL-TIME -->
            <div style="display: flex; align-items: center; justify-content: space-between; margin: 26px 0 14px 0; padding-bottom: 10px; border-bottom: 2px dashed #cbd5e1; flex-wrap: wrap; gap: 10px;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 34px; height: 34px; border-radius: 10px; background: linear-gradient(135deg, #0f172a, #334155); color: white; display: flex; align-items: center; justify-content: center; font-size: 0.95rem; box-shadow: 0 4px 10px rgba(15, 23, 42, 0.15);">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span style="background: #0f172a; color: #ffffff; padding: 2px 8px; border-radius: 6px; font-weight: 800; font-size: 0.68rem; text-transform: uppercase;">
                                DATA AKTUAL
                            </span>
                            <h3 style="font-size: 0.98rem; font-weight: 900; color: #0f172a; margin: 0; line-height: 1.2;">
                                Ringkasan Informasi Aktual & Status Terkini
                            </h3>
                        </div>
                        <span style="font-size: 0.72rem; color: #64748b; font-weight: 600;">Data real-time sampel lingkungan, sertifikat CoA resmi, dan progres uji laboratorium Anda</span>
                    </div>
                </div>
                <span style="background: #ecfdf5; color: #059669; font-size: 0.7rem; font-weight: 800; padding: 5px 14px; border-radius: 99px; border: 1px solid #a7f3d0; display: inline-flex; align-items: center; gap: 6px;">
                    <span class="live-pulse-dot" style="background: #10b981;"></span> Update Otomatis Real-time
                </span>
            </div>

            <!-- STATS GRID WITH DISTINCT 3D TILT METRIC CARDS -->
            <div class="stats-grid">
                <div class="stat-card card-accent-emerald tilt-card-3d">
                    <div class="stat-icon" style="background: #ecfdf5; color: #059669;">
                        <i class="fa-solid fa-vials"></i>
                    </div>
                    <div class="stat-info">
                        <h4>Total Sampel Lingkungan</h4>
                        <div class="value">{{ count($samples) }}</div>
                        <span class="stat-tag" style="color: #059669;">
                            <i class="fa-solid fa-circle-check"></i> Teregistrasi LIMS
                        </span>
                    </div>
                </div>

                <div class="stat-card card-accent-green tilt-card-3d">
                    <div class="stat-icon" style="background: #d1fae5; color: #10b981;">
                        <i class="fa-solid fa-file-shield"></i>
                    </div>
                    <div class="stat-info">
                        <h4>Sertifikat Terbit (CoA)</h4>
                        <div class="value">{{ count($coas) }}</div>
                        <span class="stat-tag" style="color: #10b981;">
                            <i class="fa-solid fa-shield-halved"></i> Terverifikasi KAN
                        </span>
                    </div>
                </div>

                <div class="stat-card card-accent-amber tilt-card-3d">
                    <div class="stat-icon" style="background: #fef3c7; color: #d97706;">
                        <i class="fa-solid fa-flask-vial"></i>
                    </div>
                    <div class="stat-info">
                        <h4>Sampel Dalam Analisa Lab</h4>
                        <div class="value">{{ $stats['in_analysis'] ?? 0 }}</div>
                        <span class="stat-tag" style="color: #d97706;">
                            <i class="fa-solid fa-clock-rotate-left"></i> Sedang Diuji
                        </span>
                    </div>
                </div>

                <div class="stat-card card-accent-red tilt-card-3d">
                    <div class="stat-icon" style="background: #fee2e2; color: #ef4444;">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <div class="stat-info">
                        <h4>Invoice Belum Lunas</h4>
                        <div class="value">{{ $stats['unpaid_invoices'] ?? 0 }}</div>
                        <span class="stat-tag" style="color: #ef4444;">
                            <i class="fa-solid fa-circle-exclamation"></i> Menunggu Pembayaran
                        </span>
                    </div>
                </div>
            </div>

            <!-- TAB 1: STATUS SAMPEL & COC -->
            <div id="tab-sampel" class="tab-content active card">
                <div class="card-header">
                    <div>
                        <div class="card-title">
                            <i class="fa-solid fa-vial-circle-check" style="color: var(--primary);"></i> Status Tracking Sampel & Chain of Custody (CoC)
                        </div>
                        <p style="font-size: 0.8rem; color: var(--slate-600); margin-top: 4px;">Progres real-time penerimaan, analisa laboratorium, dan penerbitan COA pengujian lingkungan.</p>
                    </div>
                </div>

                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>ID Sampel / Barcode</th>
                                <th>Deskripsi Sampel & Lokasi</th>
                                <th>Tgl Sampling</th>
                                <th>Tgl Terima Lab</th>
                                <th>Status Pipeline Lab</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($samples as $sample)
                            <tr>
                                <td style="font-weight: 700; color: var(--primary);">
                                    <i class="fa-solid fa-barcode me-1"></i> {{ $sample->sample_id ?? ('SMP-'.str_pad($sample->id, 5, '0', STR_PAD_LEFT)) }}
                                </td>
                                <td>
                                    <div style="font-weight: 700;">{{ $sample->description ?? $sample->nama_cerobong ?? 'Sampel Lingkungan' }}</div>
                                    <div style="font-size: 0.75rem; color: var(--slate-500);">Param: {{ is_array($sample->parameters) ? implode(', ', $sample->parameters) : ($sample->parameters ?? 'Air/Udara/Emisi') }}</div>
                                </td>
                                <td>{{ $sample->tgl_sampling ? \Carbon\Carbon::parse($sample->tgl_sampling)->format('d/m/Y') : '-' }}</td>
                                <td>{{ $sample->tgl_terima_lab ? \Carbon\Carbon::parse($sample->tgl_terima_lab)->format('d/m/Y') : '-' }}</td>
                                <td>
                                    @if($sample->is_verified || $sample->verified_at)
                                        <span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> Selesai & Verified</span>
                                    @elseif($sample->status_lab === 'Analisa Selesai')
                                        <span class="badge badge-info"><i class="fa-solid fa-spinner"></i> Verifikasi Manager</span>
                                    @else
                                        <span class="badge badge-warning"><i class="fa-solid fa-flask"></i> Analisa Laboratorium</span>
                                    @endif

                                    <div class="pipeline-bar">
                                        <div class="pipeline-step done" title="Penerimaan"></div>
                                        <div class="pipeline-step {{ $sample->status_lab ? 'done' : 'active' }}" title="Analisa Lab"></div>
                                        <div class="pipeline-step {{ $sample->is_verified ? 'done' : '' }}" title="Verifikasi"></div>
                                        <div class="pipeline-step {{ $sample->is_verified ? 'done' : '' }}" title="COA Terbit"></div>
                                    </div>
                                </td>
                                <td>
                                    <button type="button" class="badge badge-neutral" style="cursor:pointer; border:1px solid #cbd5e1;" onclick="quickVerifyDoc('{{ $sample->sample_id ?? $sample->id }}')">
                                        <i class="fa-solid fa-qrcode"></i> Cek Barcode
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 36px; color: var(--slate-500);">
                                    <i class="fa-solid fa-vials fa-2x" style="margin-bottom: 10px; color: #cbd5e1;"></i>
                                    <p style="font-weight: 700;">Belum ada data sampel pengujian lingkungan terdaftar.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 2: SERTIFIKAT TERBIT (COA) -->
            <div id="tab-coa" class="tab-content card">
                <div class="card-header">
                    <div>
                        <div class="card-title">
                            <i class="fa-solid fa-file-certificate" style="color: var(--primary);"></i> Sertifikat Hasil Uji / Certificate of Analysis (COA)
                        </div>
                        <p style="font-size: 0.8rem; color: var(--slate-600); margin-top: 4px;">Daftar COA resmi ber-barcode KAN yang telah diterbitkan dan ditandatangani secara digital.</p>
                    </div>
                </div>
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>No. COA / Sertifikat</th>
                                <th>Jenis Matriks</th>
                                <th>Tgl Terbit</th>
                                <th>Status Keabsahan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($coas as $coa)
                            <tr>
                                <td style="font-weight: 700; color: var(--primary);">
                                    <i class="fa-solid fa-file-pdf me-1"></i> {{ $coa->no_coa ?? ('COA-ENV-'.$coa->id) }}
                                </td>
                                <td>{{ $coa->matriks ?? 'Air / Udara Lingkungan' }}</td>
                                <td>{{ $coa->created_at ? \Carbon\Carbon::parse($coa->created_at)->format('d/m/Y') : '-' }}</td>
                                <td><span class="badge badge-success"><i class="fa-solid fa-check-double"></i> Terbit KAN LP-1813</span></td>
                                <td>
                                    <button type="button" class="btn-action" style="padding: 6px 12px; font-size: 0.75rem;" onclick="quickVerifyDoc('{{ $coa->no_coa ?? $coa->id }}')">
                                        <i class="fa-solid fa-qrcode"></i> Verifikasi
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 36px; color: var(--slate-500);">
                                    <i class="fa-solid fa-file-shield fa-2x" style="margin-bottom: 10px; color: #cbd5e1;"></i>
                                    <p style="font-weight: 700;">Belum ada sertifikat COA pengujian lingkungan terbit.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 3: PERMINTAAN SAMPLING -->
            <div id="tab-sampling-req" class="tab-content card">
                <div class="card-header">
                    <div>
                        <div class="card-title">
                            <i class="fa-solid fa-truck-droplet" style="color: var(--primary);"></i> Permintaan & Jadwal Sampling Lingkungan
                        </div>
                    </div>
                    <button class="btn-action" onclick="openSamplingModal()">
                        <i class="fa-solid fa-plus"></i> Ajukan Sampling Baru
                    </button>
                </div>
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>ID Permintaan</th>
                                <th>Rencana Tanggal</th>
                                <th>Jumlah Titik</th>
                                <th>Parameter Dipilih</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($samplingRequests as $req)
                            <tr>
                                <td style="font-weight:700;">REQ-SMP-{{ $req->id }}</td>
                                <td>{{ $req->tgl_rencana ?? '-' }}</td>
                                <td>{{ $req->jumlah_cerobong ?? 1 }} Titik</td>
                                <td>{{ $req->parameters ?? 'Air / Udara' }}</td>
                                <td><span class="badge badge-info">{{ $req->status ?? 'Memproses' }}</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 36px; color: var(--slate-500);">
                                    <p style="font-weight: 700;">Belum ada permintaan sampling lingkungan diajukan.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB: RUANG LINGKUP & CMC KAN -->
            <!-- TAB 3: RUANG LINGKUP KAN & CMC (ENVIROTAMA.ID STYLE) -->
            <div id="tab-lingkup-kan" class="tab-content card" style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 24px;">
                <!-- HEADER SECTION -->
                <div style="text-align: center; max-width: 860px; margin: 0 auto 28px auto;">
                    <div style="display: inline-flex; align-items: center; gap: 8px; background: #059669; color: white; border-radius: 999px; padding: 6px 20px; font-weight: 800; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 4px 14px rgba(5,150,105,0.3);">
                        <i class="fa-solid fa-award"></i> SERTIFIKAT AKREDITASI LP-1813-IDN & LK-361-IDN
                    </div>
                    <h2 id="scopeMainHeadingP" style="font-size: 1.7rem; font-weight: 900; color: #0f172a; margin: 14px 0 6px 0; letter-spacing: -0.5px;">
                        RUANG LINGKUP PENGUJIAN KAN
                    </h2>
                    <p style="font-size: 0.88rem; color: #64748b; margin: 0 auto; line-height: 1.5;">
                        Kemampuan Pengujian Laboratorium Lingkungan & Biohazard Validasi Resmi Sesuai SNI ISO/IEC 17025:2017
                    </p>

                    <!-- SCOPE TYPE SWITCHER PILL -->
                    <div style="display: inline-flex; background: #e2e8f0; padding: 4px; border-radius: 999px; margin-top: 18px; gap: 4px;">
                        <button type="button" id="btnScopeSwitchUjiP" onclick="switchScopeModeP('pengujian')" style="padding: 8px 22px; border-radius: 999px; font-weight: 800; font-size: 0.82rem; border: none; cursor: pointer; transition: all 0.25s; background: #059669; color: white; box-shadow: 0 2px 6px rgba(5,150,105,0.3);">
                            <i class="fa-solid fa-flask-vial me-1"></i> Ruang Lingkup Pengujian (LP-1813-IDN) <span class="badge" style="background: #10b981; color: white; font-size: 0.68rem; margin-left: 4px;">BSC/LAF Baru</span>
                        </button>
                        <button type="button" id="btnScopeSwitchKalP" onclick="switchScopeModeP('kalibrasi')" style="padding: 8px 22px; border-radius: 999px; font-weight: 800; font-size: 0.82rem; border: none; cursor: pointer; transition: all 0.25s; background: transparent; color: #475569;">
                            <i class="fa-solid fa-scale-balanced me-1"></i> Ruang Lingkup Kalibrasi & CMC (LK-361-IDN)
                        </button>
                    </div>
                </div>

                <!-- SEARCH PILL BAR -->
                <div style="max-width: 680px; margin: 0 auto 24px auto; position: relative;">
                    <input type="text" id="interactiveScopeSearchP" onkeyup="filterInteractiveScopeP()" placeholder="🔍 Cari parameter atau alat (contoh: BSC, LAF, Fume Hood, Air Limbah, Emisi, pH)..." style="width: 100%; padding: 13px 20px 13px 44px; border-radius: 999px; border: 1.5px solid #cbd5e1; font-size: 0.88rem; font-weight: 600; outline: none; background: white; box-shadow: 0 4px 15px rgba(0,0,0,0.04); transition: all 0.2s;">
                    <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.95rem;"></i>
                </div>

                <!-- VIEW MODE TOGGLE & ACTIONS -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
                    <div style="display: flex; gap: 8px;">
                        <button type="button" id="btnViewCardsP" onclick="setScopeDisplayModeP('cards')" style="padding: 6px 14px; border-radius: 8px; font-size: 0.78rem; font-weight: 800; border: 1px solid #cbd5e1; background: #059669; color: white; cursor: pointer;">
                            <i class="fa-solid fa-grip me-1"></i> Tampilan Kartu Interaktif
                        </button>
                        <button type="button" id="btnViewTableP" onclick="setScopeDisplayModeP('table')" style="padding: 6px 14px; border-radius: 8px; font-size: 0.78rem; font-weight: 800; border: 1px solid #cbd5e1; background: white; color: #475569; cursor: pointer;">
                            <i class="fa-solid fa-table-list me-1"></i> Tampilan Tabel Lengkap
                        </button>
                    </div>
                    <div style="display: flex; gap: 8px;">
                        <a href="https://kan.or.id" target="_blank" class="btn-action btn-secondary" style="padding: 6px 12px; font-size: 0.76rem;">
                            <i class="fa-solid fa-file-pdf" style="color: #dc2626;"></i> SK Akreditasi KAN (PDF)
                        </a>
                    </div>
                </div>

                <!-- ==================== PENGUJIAN SECTION (DEFAULT IN PENGUJIAN PORTAL) ==================== -->
                <div id="sectionScopePengujianP">
                    <!-- CATEGORY CARDS GRID PENGUJIAN (2 CLEAN CATEGORIES) -->
                    <div id="ujiCategoryCardsGridP" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; margin-bottom: 24px; max-width: 720px;">
                        <!-- 1. Pengujian Mekanik -->
                        <div class="scope-cat-card-p active" onclick="selectUjiCategoryP('Pengujian Mekanik', this)" data-category="Pengujian Mekanik" style="background: white; border: 1.5px solid #cbd5e1; border-radius: 14px; padding: 16px 18px; cursor: pointer; transition: all 0.25s; display: flex; align-items: center; gap: 14px;">
                            <div class="cat-icon-box" style="width: 48px; height: 48px; border-radius: 12px; background: #dcfce7; color: #15803d; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                                <i class="fa-solid fa-gears"></i>
                            </div>
                            <div>
                                <div class="cat-title" style="font-weight: 800; font-size: 0.95rem; color: #0f172a; line-height: 1.2;">Pengujian Mekanik</div>
                                <div class="cat-desc" style="font-size: 0.76rem; color: #15803d; font-weight: 700; margin-top: 3px;">BSC, LAF, Fume Hood</div>
                            </div>
                        </div>

                        <!-- 2. Udara Emisi Sumber Tidak Bergerak (Proses Akreditasi) -->
                        <div class="scope-cat-card-p" onclick="selectUjiCategoryP('Udara Emisi Cerobong', this)" data-category="Udara Emisi Cerobong" style="background: white; border: 1.5px solid #cbd5e1; border-radius: 14px; padding: 16px 18px; cursor: pointer; transition: all 0.25s; display: flex; align-items: center; gap: 14px;">
                            <div class="cat-icon-box" style="width: 48px; height: 48px; border-radius: 12px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                                <i class="fa-solid fa-smog"></i>
                            </div>
                            <div>
                                <div style="display: flex; align-items: center; gap: 6px;">
                                    <div class="cat-title" style="font-weight: 800; font-size: 0.95rem; color: #0f172a; line-height: 1.2;">Udara Emisi</div>
                                    <span class="badge" style="background: #fef3c7; color: #b45309; border: 1px solid #fde68a; font-size: 0.65rem; font-weight: 800;">Proses Akreditasi</span>
                                </div>
                                <div class="cat-desc" style="font-size: 0.74rem; color: #64748b; margin-top: 3px;">Cerobong & Isokinetik</div>
                            </div>
                        </div>
                    </div>

                    <!-- ACTIVE UJI BANNER -->
                    <div id="activeUjiBannerP" style="background: white; border-radius: 16px; padding: 18px 22px; border: 1.5px solid #e2e8f0; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
                        <div style="display: flex; align-items: center; gap: 14px;">
                            <div id="activeUjiIconBoxP" style="width: 46px; height: 46px; border-radius: 12px; background: #dcfce7; color: #15803d; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                                <i class="fa-solid fa-gears"></i>
                            </div>
                            <div>
                                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                                    <h3 id="activeUjiTitleTextP" style="font-weight: 900; font-size: 1.15rem; color: #0f172a; margin: 0;">
                                        Pengujian Mekanik: Biosafety Cabinet (BSC), LAF & Fume Hood
                                    </h3>
                                    <span id="activeUjiBadgeP" class="badge badge-success" style="font-size: 0.75rem; font-weight: 800;">
                                        LP-1813 Terakreditasi KAN
                                    </span>
                                </div>
                                <p id="activeUjiSubtitleTextP" style="font-size: 0.78rem; color: #64748b; margin: 2px 0 0 0;">
                                    Standar Internasional: NSF/ANSI 49, EN 12469, ISO 14644 & ASHRAE 110
                                </p>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn-action" style="background: #059669; color: white; padding: 8px 18px; border-radius: 10px; font-weight: 800; font-size: 0.82rem;" onclick="openModal('modal-sampling')">
                                <i class="fa-solid fa-calendar-plus me-1"></i> Request Sampling / Pengujian
                            </button>
                        </div>
                    </div>

                    <!-- PENGUJIAN CARDS GRID -->
                    <div id="ujiCardsContainerP" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)); gap: 16px;">
                        @foreach($lingkupPengujianList as $item)
                        @php
                            $groupCat = 'Pengujian Mekanik';
                            $isAkreditasi = true;
                            if (str_contains($item['kategori'], 'Udara Emisi')) {
                                $groupCat = 'Udara Emisi Cerobong';
                                $isAkreditasi = false;
                            }
                        @endphp
                        <div class="uji-instrument-card-p" data-category="{{ $groupCat }}" data-subcat="{{ $item['kategori'] }}" data-param="{{ $item['parameter'] }}" data-acuan="{{ $item['acuan'] }}" data-metode="{{ $item['metode'] }}" style="background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px; box-shadow: 0 2px 8px rgba(0,0,0,0.03); display: flex; flex-direction: column; justify-content: space-between; transition: all 0.2s;">
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                                    <div style="font-weight: 800; font-size: 0.95rem; color: #0f172a; line-height: 1.3;">
                                        <i class="fa-solid {{ $isAkreditasi ? 'fa-shield-virus' : 'fa-smog' }}" style="color: {{ $isAkreditasi ? '#059669' : '#d97706' }}; margin-right: 6px;"></i> {{ $item['kategori'] }}
                                    </div>
                                    @if($isAkreditasi)
                                        <span class="badge" style="background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; font-size: 0.68rem; font-weight: 800;">LP-1813</span>
                                    @else
                                        <span class="badge" style="background: #fef3c7; color: #b45309; border: 1px solid #fde68a; font-size: 0.68rem; font-weight: 800;">Proses Akreditasi</span>
                                    @endif
                                </div>

                                <div style="font-size: 0.85rem; font-weight: 700; color: #1e293b; margin-bottom: 10px; line-height: 1.4;">
                                    {{ $item['parameter'] }}
                                </div>

                                <div style="background: #f8fafc; border: 1px solid #f1f5f9; border-radius: 10px; padding: 10px 12px; margin-bottom: 10px;">
                                    <div style="font-size: 0.7rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 2px;">Baku Mutu / Standar Acuan:</div>
                                    <div style="font-size: 0.78rem; font-weight: 600; color: #334155;">{{ $item['acuan'] }}</div>
                                </div>
                            </div>

                            <div style="border-top: 1px solid #f1f5f9; padding-top: 10px; display: flex; justify-content: space-between; align-items: center;">
                                <div style="font-size: 0.72rem; color: #64748b; font-weight: 600;">
                                    <i class="fa-solid fa-book me-1" style="color: #94a3b8;"></i> {{ $item['metode'] }}
                                </div>
                                @if($isAkreditasi)
                                    <span class="badge badge-success" style="font-size: 0.68rem;">Terakreditasi</span>
                                @else
                                    <span class="badge badge-warning" style="font-size: 0.68rem;">Proses Akreditasi</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- TABLE PENGUJIAN -->
                    <div id="ujiTableContainerP" class="table-responsive" style="display: none;">
                        <table class="custom-table" id="ujiTableMainP">
                            <thead>
                                <tr>
                                    <th style="width: 40px;">No</th>
                                    <th>Kategori / Instrumen</th>
                                    <th>Parameter / Item Pengujian</th>
                                    <th>Baku Mutu / Standar Acuan</th>
                                    <th>Metode Pengujian</th>
                                    <th>Status Akreditasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lingkupPengujianList as $index => $item)
                                @php
                                    $groupCat = 'Pengujian Mekanik';
                                    $isAkreditasi = true;
                                    if (str_contains($item['kategori'], 'Udara Emisi')) {
                                        $groupCat = 'Udara Emisi Cerobong';
                                        $isAkreditasi = false;
                                    }
                                @endphp
                                <tr data-category="{{ $groupCat }}" data-subcat="{{ $item['kategori'] }}" data-param="{{ $item['parameter'] }}" data-acuan="{{ $item['acuan'] }}" data-metode="{{ $item['metode'] }}">
                                    <td style="font-weight: 700; color: #64748b;">{{ $index + 1 }}</td>
                                    <td style="font-weight: 800; color: #059669;">{{ $item['kategori'] }}</td>
                                    <td style="font-weight: 700; color: #0f172a;">{{ $item['parameter'] }}</td>
                                    <td style="font-size: 0.78rem; color: #475569;">{{ $item['acuan'] }}</td>
                                    <td><span class="badge badge-neutral" style="font-family: monospace; font-size: 0.72rem;">{{ $item['metode'] }}</span></td>
                                    <td>
                                        @if($isAkreditasi)
                                            <span class="badge badge-success" style="font-size: 0.7rem;">LP-1813 KAN</span>
                                        @else
                                            <span class="badge badge-warning" style="font-size: 0.7rem;">Proses Akreditasi</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ==================== KALIBRASI SECTION IN PENGUJIAN VIEW ==================== -->
                <div id="sectionScopeKalibrasiP" style="display: none;">
                    <div class="table-responsive">
                        <table class="custom-table" id="cmcTableMainP">
                            <thead>
                                <tr>
                                    <th style="width: 40px;">No</th>
                                    <th>Kategori KAN</th>
                                    <th>Jenis / Kelompok Alat Ukur</th>
                                    <th>Rentang Ukur Terakreditasi</th>
                                    <th>Kemampuan Ukur (CMC U95)</th>
                                    <th>Metode Standar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lingkupCmcList as $index => $cmc)
                                <tr>
                                    <td style="font-weight: 700; color: #64748b;">{{ $index + 1 }}</td>
                                    <td><span class="badge badge-info" style="font-size: 0.72rem;">{{ $cmc['kategori'] }}</span></td>
                                    <td style="font-weight: 800; color: #0f172a;">{{ $cmc['alat'] }}</td>
                                    <td><span style="font-family: monospace; font-weight: 700; color: #0284c7;">{{ $cmc['rentang'] }}</span></td>
                                    <td><span style="font-family: monospace; font-weight: 800; color: #16a34a; background: #f0fdf4; border: 1px dashed #86efac; padding: 4px 10px; border-radius: 6px; display: inline-block;">± {{ $cmc['cmc'] }}</span></td>
                                    <td style="font-size: 0.76rem; color: #475569; font-weight: 600;">{{ $cmc['metode'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <style>
                .scope-cat-card-p {
                    box-shadow: 0 2px 6px rgba(0,0,0,0.03);
                }
                .scope-cat-card-p:hover {
                    transform: translateY(-3px);
                    box-shadow: 0 8px 18px rgba(0,0,0,0.08);
                    border-color: #059669 !important;
                }
                .scope-cat-card-p.active {
                    background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
                    border-color: #059669 !important;
                    color: white !important;
                    box-shadow: 0 10px 25px -5px rgba(5, 150, 105, 0.45) !important;
                }
                .scope-cat-card-p.active .cat-title {
                    color: white !important;
                }
                .scope-cat-card-p.active .cat-desc {
                    color: #a7f3d0 !important;
                }
                .scope-cat-card-p.active .cat-icon-box {
                    background: rgba(255, 255, 255, 0.2) !important;
                    color: white !important;
                }
                .uji-instrument-card-p:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 8px 20px rgba(0,0,0,0.07);
                    border-color: #34d399 !important;
                }
            </style>

            <script>
                let currentScopeModeP = 'pengujian';
                let currentDisplayModeP = 'cards';
                let activeUjiCatP = 'Pengujian Mekanik';

                function switchScopeModeP(mode) {
                    currentScopeModeP = mode;
                    const btnUji = document.getElementById('btnScopeSwitchUjiP');
                    const btnKal = document.getElementById('btnScopeSwitchKalP');
                    const secUji = document.getElementById('sectionScopePengujianP');
                    const secKal = document.getElementById('sectionScopeKalibrasiP');
                    const mainHeading = document.getElementById('scopeMainHeadingP');

                    if (mode === 'pengujian') {
                        btnUji.style.background = '#059669';
                        btnUji.style.color = 'white';
                        btnUji.style.boxShadow = '0 2px 6px rgba(5,150,105,0.3)';

                        btnKal.style.background = 'transparent';
                        btnKal.style.color = '#475569';
                        btnKal.style.boxShadow = 'none';

                        secUji.style.display = 'block';
                        secKal.style.display = 'none';
                        if (mainHeading) mainHeading.innerText = 'RUANG LINGKUP PENGUJIAN KAN';
                        selectUjiCategoryP(activeUjiCatP);
                    } else {
                        btnKal.style.background = '#0284c7';
                        btnKal.style.color = 'white';
                        btnKal.style.boxShadow = '0 2px 6px rgba(2,132,199,0.3)';

                        btnUji.style.background = 'transparent';
                        btnUji.style.color = '#475569';
                        btnUji.style.boxShadow = 'none';

                        secUji.style.display = 'none';
                        secKal.style.display = 'block';
                        if (mainHeading) mainHeading.innerText = 'RUANG LINGKUP KALIBRASI KAN';
                    }
                    filterInteractiveScopeP();
                }

                function setScopeDisplayModeP(mode) {
                    currentDisplayModeP = mode;
                    const btnCards = document.getElementById('btnViewCardsP');
                    const btnTable = document.getElementById('btnViewTableP');

                    const ujiCards = document.getElementById('ujiCardsContainerP');
                    const ujiTable = document.getElementById('ujiTableContainerP');
                    const ujiBanner = document.getElementById('activeUjiBannerP');
                    const ujiCats = document.getElementById('ujiCategoryCardsGridP');

                    if (mode === 'cards') {
                        btnCards.style.background = '#059669';
                        btnCards.style.color = 'white';
                        btnTable.style.background = 'white';
                        btnTable.style.color = '#475569';

                        if (ujiCards) ujiCards.style.display = 'grid';
                        if (ujiTable) ujiTable.style.display = 'none';
                        if (ujiBanner) ujiBanner.style.display = 'flex';
                        if (ujiCats) ujiCats.style.display = 'grid';
                    } else {
                        btnTable.style.background = '#059669';
                        btnTable.style.color = 'white';
                        btnCards.style.background = 'white';
                        btnCards.style.color = '#475569';

                        if (ujiCards) ujiCards.style.display = 'none';
                        if (ujiTable) ujiTable.style.display = 'block';
                        if (ujiBanner) ujiBanner.style.display = 'none';
                        if (ujiCats) ujiCats.style.display = 'none';
                    }
                    filterInteractiveScopeP();
                }

                function selectUjiCategoryP(category, el = null) {
                    activeUjiCatP = category;
                    document.querySelectorAll('.scope-cat-card-p').forEach(c => c.classList.remove('active'));
                    if (el) {
                        el.classList.add('active');
                    } else {
                        const match = document.querySelector(`.scope-cat-card-p[data-category="${category}"]`);
                        if (match) match.classList.add('active');
                    }

                    const titleEl = document.getElementById('activeUjiTitleTextP');
                    const badgeEl = document.getElementById('activeUjiBadgeP');
                    const subtitleEl = document.getElementById('activeUjiSubtitleTextP');
                    const iconBox = document.getElementById('activeUjiIconBoxP');

                    if (category === 'Pengujian Mekanik') {
                        if (titleEl) titleEl.innerText = 'Pengujian Mekanik: Biosafety Cabinet (BSC), LAF & Fume Hood';
                        if (badgeEl) {
                            badgeEl.className = 'badge badge-success';
                            badgeEl.innerText = 'LP-1813 Terakreditasi KAN';
                        }
                        if (subtitleEl) subtitleEl.innerText = 'Standar Internasional: NSF/ANSI 49, EN 12469, ISO 14644 & ASHRAE 110';
                        if (iconBox) {
                            iconBox.innerHTML = '<i class="fa-solid fa-gears"></i>';
                            iconBox.style.background = '#dcfce7';
                            iconBox.style.color = '#15803d';
                        }
                    } else {
                        if (titleEl) titleEl.innerText = 'Pengujian Udara Emisi Sumber Tidak Bergerak (Cerobong)';
                        if (badgeEl) {
                            badgeEl.className = 'badge';
                            badgeEl.style.background = '#fef3c7';
                            badgeEl.style.color = '#b45309';
                            badgeEl.style.border = '1px solid #fde68a';
                            badgeEl.innerText = 'Dalam Proses Akreditasi KAN';
                        }
                        if (subtitleEl) subtitleEl.innerText = 'Metode Acuan: Permen LHK No. 11/2021, SNI 7117 Series & US EPA Method 5';
                        if (iconBox) {
                            iconBox.innerHTML = '<i class="fa-solid fa-smog"></i>';
                            iconBox.style.background = '#fef3c7';
                            iconBox.style.color = '#d97706';
                        }
                    }

                    filterInteractiveScopeP();
                }

                function filterInteractiveScopeP() {
                    const searchVal = (document.getElementById('interactiveScopeSearchP')?.value || '').toLowerCase().trim();

                    if (currentScopeModeP === 'pengujian') {
                        const ujiCards = document.querySelectorAll('.uji-instrument-card-p');
                        ujiCards.forEach(card => {
                            const cat = card.getAttribute('data-category');
                            const text = card.innerText.toLowerCase();

                            const matchesCategory = searchVal ? true : (cat === activeUjiCatP);
                            const matchesSearch = !searchVal || text.includes(searchVal);

                            if (matchesCategory && matchesSearch) {
                                card.style.display = 'flex';
                            } else {
                                card.style.display = 'none';
                            }
                        });

                        const rowsUji = document.querySelectorAll('#ujiTableMainP tbody tr');
                        rowsUji.forEach(row => {
                            const text = row.innerText.toLowerCase();
                            if (!searchVal || text.includes(searchVal)) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        });
                    }
                }

                document.addEventListener('DOMContentLoaded', function() {
                    selectUjiCategoryP('Pengujian Mekanik');
                });
            </script>

            <!-- TAB 4: INVOICES -->
            <div id="tab-invoices" class="tab-content card">
                <div class="card-header">
                    <div>
                        <div class="card-title">
                            <i class="fa-solid fa-receipt" style="color: var(--primary);"></i> Tagihan & Invoice Digital Pengujian
                        </div>
                    </div>
                </div>
                <div class="table-responsive" style="overflow-x: auto;">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>No. Invoice</th>
                                <th>Tanggal</th>
                                <th>Total Tagihan</th>
                                <th>Status Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $inv)
                            <tr>
                                <td style="font-weight: 700;">{{ $inv->no_invoice ?? ('INV-ENV-'.$inv->id) }}</td>
                                <td>{{ $inv->tgl_invoice ?? '-' }}</td>
                                <td style="font-weight:700; color:var(--primary);">Rp {{ number_format($inv->grand_total ?? 0, 0, ',', '.') }}</td>
                                <td><span class="badge badge-warning">Menunggu Pelunasan</span></td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" style="text-align: center; padding: 36px; color: var(--slate-500);">
                                    <p style="font-weight: 700;">Belum ada tagihan invoice pengujian.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 5: PESAN -->
            <div id="tab-pesan" class="tab-content card">
                <div class="card-header">
                    <div>
                        <div class="card-title">
                            <i class="fa-solid fa-comments" style="color: var(--primary);"></i> Pesan & Helpdesk Pelanggan
                        </div>
                    </div>
                </div>
                <p style="font-size: 0.85rem; color: var(--slate-600);">Layanan komunikasi langsung dengan Admin TS Pengujian Lingkungan PT Envirotama Solusindo.</p>
            </div>
        </main>
    </div>

    <!-- MODAL CEK KEASLIAN BARCODE / SERTIFIKAT COA PENGUJIAN -->
    <div id="modalVerifyDoc" class="modal-backdrop-custom">
        <div class="modal-box-custom">
            <div class="modal-header-custom" style="background: #0f172a; color: white;">
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 38px; height: 38px; background: #059669; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fa-solid fa-qrcode"></i>
                    </div>
                    <div>
                        <h4 style="font-weight: 800; font-size: 1.05rem; margin: 0;">Verifikasi Keaslian CoA Pengujian Laboratorium</h4>
                        <p style="font-size: 0.72rem; color: #a7f3d0; margin: 0;">Sistem Verifikasi Dokumen KAN LP-1813-IDN Terakreditasi PT Envirotama Solusindo</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modalVerifyDoc')" style="background: transparent; border: none; color: #94a3b8; font-size: 1.2rem; cursor: pointer;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="modal-body-custom">
                <div style="margin-bottom: 16px;">
                    <label style="font-size: 0.78rem; font-weight: 800; color: var(--slate-700); text-transform: uppercase;">
                        Masukkan Nomor CoA / Sample ID / No. CoC / Barcode Token:
                    </label>
                    <div style="display: flex; gap: 8px; margin-top: 6px;">
                        <input type="text" id="modalSearchQuery" placeholder="Contoh: SMP-..., COA-..., COC-..., atau token barcode QR..." style="flex: 1; padding: 10px 14px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-weight: 600; font-size: 0.88rem; outline: none;" onkeypress="if(event.key === 'Enter') executeDocVerify('modal')">
                        <button type="button" id="btnModalVerify" onclick="executeDocVerify('modal')" style="background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 10px; font-weight: 800; cursor: pointer;">
                            <i class="fa-solid fa-magnifying-glass me-1"></i> Periksa
                        </button>
                    </div>
                    <p style="font-size: 0.72rem; color: var(--slate-500); margin-top: 6px;">
                        <i class="fa-solid fa-lock me-1"></i> <em>Catatan: Hanya dokumen pengujian yang terdaftar atas nama <strong>{{ session('client_company') }}</strong> yang dapat diverifikasi oleh akun Anda.</em>
                    </p>
                </div>

                <div id="modalVerifyResult" style="display: none;"></div>
            </div>
        </div>
    </div>

    <!-- MODAL RUANG LINGKUP & CMC KAN -->
    <div id="modalScope" class="modal-backdrop-custom">
        <div class="modal-box-custom" style="max-width: 950px;">
            <div class="modal-header-custom" style="background: #0f172a; color: white;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div class="logo-box">
                        <img src="{{ asset('images/logo_eslab.jpg') }}" alt="Logo">
                    </div>
                    <div>
                        <h4 style="font-weight: 800; font-size: 1.1rem; margin: 0;">Ruang Lingkup & CMC Akreditasi KAN</h4>
                        <p style="font-size: 0.72rem; color: #6ee7b7; margin: 0;">Laboratorium Pengujian (LP-1813-IDN) & Laboratorium Kalibrasi (LK-361-IDN)</p>
                    </div>
                </div>
                <button type="button" onclick="closeModal('modalScope')" style="background: transparent; border: none; color: #94a3b8; font-size: 1.2rem; cursor: pointer;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="modal-body-custom">
                <div style="margin-bottom: 16px;">
                    <input type="text" placeholder="Ketik untuk memfilter parameter, nama alat, atau metode..." onkeyup="filterScopeTable('modalScopeTable', this.value)" style="width: 100%; padding: 10px 14px; border-radius: 10px; border: 1.5px solid #cbd5e1; font-weight: 600; font-size: 0.85rem; outline: none;">
                </div>

                <div class="table-responsive" style="max-height: 55vh; overflow-y: auto;">
                    <table class="custom-table" id="modalScopeTable">
                        <thead>
                            <tr>
                                <th>Kategori / Bidang</th>
                                <th>Parameter / Alat Ukur</th>
                                <th>Acuan / Rentang Ukur</th>
                                <th>Metode Uji / Standar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lingkupPengujianList as $item)
                            <tr>
                                <td style="font-weight: 700; color: var(--primary);">🧪 {{ $item['kategori'] }}</td>
                                <td style="font-weight: 600;">{{ $item['parameter'] }}</td>
                                <td style="font-size: 0.8rem;">{{ $item['acuan'] }}</td>
                                <td><span class="badge badge-neutral">{{ $item['metode'] }}</span></td>
                            </tr>
                            @endforeach
                            @foreach($lingkupCmcList as $cmc)
                            <tr>
                                <td style="font-weight: 700; color: #0284c7;">⚖️ {{ $cmc['kategori'] }}</td>
                                <td style="font-weight: 600;">{{ $cmc['alat'] }}</td>
                                <td style="font-size: 0.8rem;">{{ $cmc['rentang'] }} (CMC: {{ $cmc['cmc'] }})</td>
                                <td><span class="badge badge-neutral">{{ $cmc['metode'] }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- NEXT-LEVEL MOUSE-FOLLOWING INTERACTIVE 3D PARALLAX & COUNTERS ---
        document.addEventListener('DOMContentLoaded', () => {
            const heroBanner = document.querySelector('.hero-banner-container');
            const orb1 = document.querySelector('.floating-orb-1');
            const orb2 = document.querySelector('.floating-orb-2');
            const floatBadge1 = document.querySelector('.float-badge-1');
            const floatBadge2 = document.querySelector('.float-badge-2');
            const tiltCards = document.querySelectorAll('.tilt-card-3d');

            if (heroBanner) {
                heroBanner.addEventListener('mousemove', (e) => {
                    const rect = heroBanner.getBoundingClientRect();
                    const x = e.clientX - rect.left - rect.width / 2;
                    const y = e.clientY - rect.top - rect.height / 2;

                    // Move ambient glowing orbs with physics parallax
                    if (orb1) orb1.style.transform = `translate(${x * 0.08}px, ${y * 0.08}px)`;
                    if (orb2) orb2.style.transform = `translate(${x * -0.06}px, ${y * -0.06}px)`;
                    if (floatBadge1) floatBadge1.style.transform = `translate(${x * 0.05}px, ${y * 0.05}px) rotate(${x * 0.012}deg)`;
                    if (floatBadge2) floatBadge2.style.transform = `translate(${x * -0.04}px, ${y * -0.04}px) rotate(${y * -0.012}deg)`;
                });

                heroBanner.addEventListener('mouseleave', () => {
                    if (orb1) orb1.style.transform = 'translate(0px, 0px)';
                    if (orb2) orb2.style.transform = 'translate(0px, 0px)';
                    if (floatBadge1) floatBadge1.style.transform = 'translate(0px, 0px)';
                    if (floatBadge2) floatBadge2.style.transform = 'translate(0px, 0px)';
                });
            }

            // Interactive 3D Tilt for cards on hover
            tiltCards.forEach(card => {
                card.addEventListener('mousemove', (e) => {
                    const rect = card.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;
                    const rotateX = ((y - centerY) / centerY) * -6;
                    const rotateY = ((x - centerX) / centerX) * 6;

                    card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.025, 1.025, 1.025)`;
                });

                card.addEventListener('mouseleave', () => {
                    card.style.transform = 'perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
                });
            });

            // Smooth Animated Number Counter for Stat Values
            const statValues = document.querySelectorAll('.stat-card .value');
            statValues.forEach(el => {
                const finalVal = parseInt(el.innerText.replace(/[^0-9]/g, '')) || 0;
                if (finalVal > 0) {
                    let current = 0;
                    const duration = 1000;
                    const stepTime = 25;
                    const increment = Math.ceil(finalVal / (duration / stepTime));
                    const timer = setInterval(() => {
                        current += increment;
                        if (current >= finalVal) {
                            el.innerText = finalVal;
                            clearInterval(timer);
                        } else {
                            el.innerText = current;
                        }
                    }, stepTime);
                }
            });
        });

        function switchTab(tabId, element = null) {
            document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.nav-tab-btn, .tab-btn, .pipeline-step-item').forEach(el => el.classList.remove('active'));

            const targetTab = document.getElementById(tabId);
            if (targetTab) targetTab.classList.add('active');

            if (element) {
                element.classList.add('active');
            } else {
                const btn = document.querySelector(`.nav-tab-btn[onclick*="${tabId}"], .tab-btn[onclick*="${tabId}"], .pipeline-step-item[onclick*="${tabId}"]`);
                if (btn) btn.classList.add('active');
            }
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function openVerifyModal() {
            document.getElementById('modalVerifyDoc').classList.add('active');
        }

        function openScopeModal() {
            switchTab('tab-lingkup-kan');
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
        }

        function openSamplingModal() {
            alert('Formulir permohonan sampling lingkungan dibuka. Silakan hubungi admin helpdesk jika membutuhkan survei lokasi awal.');
        }

        function toggleScopeView(type) {
            const btnP = document.getElementById('btnScopePengujian');
            const btnK = document.getElementById('btnScopeKalibrasi');
            const viewP = document.getElementById('viewScopePengujian');
            const viewK = document.getElementById('viewScopeKalibrasi');

            if (type === 'pengujian') {
                btnP.style.background = 'var(--primary)';
                btnP.style.color = 'white';
                btnK.style.background = '#f1f5f9';
                btnK.style.color = 'var(--slate-700)';
                viewP.style.display = 'block';
                viewK.style.display = 'none';
            } else {
                btnK.style.background = '#0284c7';
                btnK.style.color = 'white';
                btnP.style.background = '#f1f5f9';
                btnP.style.color = 'var(--slate-700)';
                viewK.style.display = 'block';
                viewP.style.display = 'none';
            }
        }

        function filterScopeTable(tableId, query) {
            const q = query.toLowerCase().trim();
            const table = document.getElementById(tableId);
            if (!table) return;
            const rows = table.getElementsByTagName('tr');

            for (let i = 1; i < rows.length; i++) {
                const text = rows[i].innerText.toLowerCase();
                rows[i].style.display = text.includes(q) ? '' : 'none';
            }
        }

        function quickVerifyDoc(code) {
            openVerifyModal();
            document.getElementById('modalSearchQuery').value = code;
            executeDocVerify('modal');
        }

        function executeDocVerify(source) {
            const queryInput = source === 'hero' ? document.getElementById('heroSearchQuery') : document.getElementById('modalSearchQuery');
            const resultBox = source === 'hero' ? document.getElementById('heroVerifyResult') : document.getElementById('modalVerifyResult');
            const query = queryInput.value.trim();

            if (!query) {
                alert('Silakan masukkan nomor barcode, nomor sertifikat/CoA, atau nomor quotation.');
                return;
            }

            resultBox.style.display = 'block';
            resultBox.innerHTML = `
                <div style="background: rgba(255,255,255,0.1); border-radius: 12px; padding: 20px; text-align: center; color: white;">
                    <i class="fa-solid fa-spinner fa-spin fa-2x" style="color: #34d399;"></i>
                    <p style="margin-top: 10px; font-weight: 700;">Memverifikasi keaslian dokumen pada database resmi KAN...</p>
                </div>
            `;

            fetch('{{ route('klien.verify-doc') }}?query=' + encodeURIComponent(query), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    const d = res.data;
                    resultBox.innerHTML = `
                        <div style="background: #ffffff; color: var(--slate-900); border-radius: 16px; padding: 22px; border: 2px solid #10b981; box-shadow: 0 10px 25px rgba(0,0,0,0.15);">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 14px; flex-wrap: wrap; gap: 10px;">
                                <div>
                                    <span style="background: #dcfce7; color: #15803d; font-size: 0.72rem; font-weight: 800; padding: 4px 10px; border-radius: 6px; display: inline-flex; align-items: center; gap: 6px;">
                                        <i class="fa-solid fa-circle-check"></i> ${res.badge_title || 'DOKUMEN RESMI TERVERIFIKASI KAN'}
                                    </span>
                                    <h3 style="font-size: 1.15rem; font-weight: 800; color: #064e3b; margin-top: 6px;">
                                        No. Dokumen: ${d.no_dokumen}
                                    </h3>
                                </div>
                                <div style="text-align: right;">
                                    <span style="font-size: 0.72rem; color: #64748b;">Perusahaan Pemilik:</span>
                                    <div style="font-weight: 800; font-size: 0.9rem; color: var(--slate-900);">${d.customer}</div>
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; background: #f8fafc; padding: 14px; border-radius: 12px; margin-bottom: 16px; border: 1px solid #e2e8f0;">
                                <div>
                                    <span style="font-size: 0.7rem; color: #64748b; font-weight: 700;">ITEM / DESKRIPSI:</span>
                                    <div style="font-weight: 700; font-size: 0.85rem; color: var(--slate-900);">${d.nama_item}</div>
                                </div>
                                <div>
                                    <span style="font-size: 0.7rem; color: #64748b; font-weight: 700;">DETAIL / MATRIKS:</span>
                                    <div style="font-weight: 700; font-size: 0.85rem; color: var(--slate-900);">${d.merk_tipe} (${d.no_seri})</div>
                                </div>
                                <div>
                                    <span style="font-size: 0.7rem; color: #64748b; font-weight: 700;">TANGGAL PELAKSANAAN:</span>
                                    <div style="font-weight: 700; font-size: 0.85rem; color: var(--slate-900);">${d.tgl_pelaksanaan}</div>
                                </div>
                                <div>
                                    <span style="font-size: 0.7rem; color: #64748b; font-weight: 700;">PENGESAH / PENYELIA:</span>
                                    <div style="font-weight: 700; font-size: 0.85rem; color: var(--slate-900);">${d.verifikator}</div>
                                </div>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                                <div style="display: flex; align-items: center; gap: 8px; font-size: 0.75rem; color: #16a34a; font-weight: 700;">
                                    <i class="fa-solid fa-shield-halved fa-lg"></i> ${d.status_keaslian}
                                </div>
                                ${d.link_view ? `
                                    <a href="${d.link_view}" target="_blank" style="background: var(--primary); color: white; padding: 8px 16px; border-radius: 8px; font-weight: 800; font-size: 0.8rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
                                        <i class="fa-solid fa-file-pdf"></i> Lihat / Cetak Dokumen
                                    </a>
                                ` : ''}
                            </div>
                        </div>
                    `;
                } else if (res.owner_mismatch) {
                    resultBox.innerHTML = `
                        <div style="background: #fff1f2; color: #9f1239; border-radius: 14px; padding: 18px; border: 2px solid #f43f5e;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <i class="fa-solid fa-shield-xmark fa-2x" style="color: #e11d48;"></i>
                                <div>
                                    <h4 style="font-weight: 800; font-size: 0.95rem; margin: 0;">Akses Verifikasi Ditolak (Privasi Pelanggan)</h4>
                                    <p style="font-size: 0.8rem; margin-top: 4px; line-height: 1.4;">${res.message}</p>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    resultBox.innerHTML = `
                        <div style="background: #fffbeb; color: #92400e; border-radius: 14px; padding: 16px; border: 1.5px solid #f59e0b;">
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <i class="fa-solid fa-circle-exclamation fa-lg"></i>
                                <span style="font-size: 0.85rem; font-weight: 700;">${res.message}</span>
                            </div>
                        </div>
                    `;
                }
            })
            .catch(err => {
                resultBox.innerHTML = `
                    <div style="background: #fee2e2; color: #991b1b; border-radius: 12px; padding: 14px;">
                        Gagal melakukan verifikasi dokumen. Silakan periksa koneksi atau coba sesaat lagi.
                    </div>
                `;
            });
        }
    </script>
</body>
</html>
