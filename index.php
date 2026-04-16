<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le Gourmet — Caisse Interactive</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Inter:wght@300;400;500;600&family=Courier+Prime:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        /* ========== RESET & BASE ========== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg-main:      #0d0d0d;
            --bg-panel:     #141414;
            --bg-card:      #1c1c1c;
            --bg-card-h:    #242424;
            --gold:         #c9a84c;
            --gold-light:   #e8c96a;
            --gold-dim:     #6b5520;
            --red:          #c0392b;
            --green:        #27ae60;
            --text-main:    #f0ead6;
            --text-muted:   #888;
            --border:       #2a2a2a;
            --plate-zone:   rgba(201,168,76,.12);
            --plate-border: rgba(201,168,76,.5);
            --drop-active:  rgba(201,168,76,.25);
            --radius:       12px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-main);
            color: var(--text-main);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ========== HEADER ========== */
        header {
            background: linear-gradient(90deg, #0d0d0d 0%, #1a1208 50%, #0d0d0d 100%);
            border-bottom: 1px solid var(--gold-dim);
            padding: 10px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 20px rgba(0,0,0,.6);
        }
        .brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: var(--gold);
            letter-spacing: .08em;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .brand i { font-size: 1.2rem; }
        .header-info {
            display: flex;
            gap: 20px;
            font-size: .78rem;
            color: var(--text-muted);
        }
        .header-info span { display: flex; align-items: center; gap: 5px; }
        .header-info i { color: var(--gold); }

        /* ========== LAYOUT ========== */
        .app-layout {
            display: grid;
            grid-template-columns: 260px 1fr 380px;
            gap: 0;
            height: calc(100vh - 57px);
            overflow: hidden;
        }

        /* ========== LEFT PANEL — MENU ========== */
        .panel-menu {
            background: var(--bg-panel);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .panel-title {
            padding: 16px 18px 12px;
            font-family: 'Playfair Display', serif;
            font-size: .95rem;
            color: var(--gold);
            letter-spacing: .06em;
            text-transform: uppercase;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }
        .menu-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 14px 12px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .menu-scroll::-webkit-scrollbar { width: 4px; }
        .menu-scroll::-webkit-scrollbar-track { background: transparent; }
        .menu-scroll::-webkit-scrollbar-thumb { background: var(--gold-dim); border-radius: 4px; }

        /* ========== ARTICLE CARD ========== */
        .article-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 10px;
            cursor: grab;
            user-select: none;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all .2s ease;
            position: relative;
            overflow: hidden;
        }
        .article-card::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 3px;
            background: var(--gold-dim);
            border-radius: 3px 0 0 3px;
            transition: background .2s;
        }
        .article-card:hover {
            background: var(--bg-card-h);
            border-color: var(--gold-dim);
            transform: translateX(4px);
            box-shadow: 0 4px 16px rgba(0,0,0,.4);
        }
        .article-card:hover::before { background: var(--gold); }
        .article-card.dragging {
            opacity: .4;
            cursor: grabbing;
            transform: scale(.97);
        }
        .article-img-wrap {
            width: 52px;
            height: 52px;
            flex-shrink: 0;
            background: #111;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #2a2a2a;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .article-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            pointer-events: none;
        }
        .article-info { flex: 1; min-width: 0; }
        .article-name {
            font-size: .82rem;
            font-weight: 600;
            color: var(--text-main);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .article-sub {
            font-size: .7rem;
            color: var(--text-muted);
            margin-top: 2px;
        }
        .article-price {
            font-size: .9rem;
            font-weight: 700;
            color: var(--gold);
            white-space: nowrap;
        }
        /* Badge quantité sur la carte menu */
        .article-card .qty-badge {
            position: absolute;
            top: 6px;
            right: 6px;
            background: #c0392b;
            color: #fff;
            font-size: .6rem;
            font-weight: 700;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transform: scale(0);
            transition: all .2s cubic-bezier(.175,.885,.32,1.275);
        }
        .article-card .qty-badge.show {
            opacity: 1;
            transform: scale(1);
        }
        .drag-hint {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            font-size: .65rem;
            color: #333;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .article-card:hover .drag-hint { color: var(--gold-dim); }

        /* ========== MIDDLE PANEL — PLATEAU ========== */
        .panel-plate {
            background: radial-gradient(ellipse at center, #1a1a0e 0%, #0d0d0d 70%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }
        .panel-plate::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 20% 80%, rgba(201,168,76,.04) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(201,168,76,.04) 0%, transparent 50%);
            pointer-events: none;
        }
        .plate-label {
            position: absolute;
            top: 16px;
            left: 50%;
            transform: translateX(-50%);
            font-family: 'Playfair Display', serif;
            font-size: .8rem;
            color: var(--text-muted);
            letter-spacing: .1em;
            text-transform: uppercase;
            white-space: nowrap;
        }
        .plate-container {
            position: relative;
            width: 380px;
            height: 380px;
        }

        /* Plate background using image parts */
        .plate-bg {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            overflow: hidden;
        }
        .plate-bg img.plate-full {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 12px 40px rgba(0,0,0,.8));
        }

        /* Drop zone ring */
        .plate-drop-zone {
            position: absolute;
            inset: 30px;
            border-radius: 50%;
            border: 2px dashed transparent;
            transition: all .25s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }
        .plate-drop-zone.drag-over {
            border-color: var(--gold);
            background: var(--drop-active);
            box-shadow: 0 0 30px rgba(201,168,76,.2), inset 0 0 30px rgba(201,168,76,.1);
        }
        .plate-drop-zone.drag-over .drop-hint { opacity: 1; }

        .drop-hint {
            opacity: 0;
            text-align: center;
            pointer-events: none;
            transition: opacity .2s;
        }
        .drop-hint i {
            font-size: 2rem;
            color: var(--gold);
            display: block;
            margin-bottom: 6px;
            animation: bounce 1s infinite;
        }
        .drop-hint span {
            font-size: .75rem;
            color: var(--gold-light);
            letter-spacing: .06em;
        }
        @keyframes bounce {
            0%,100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }

        /* Items ON the plate */
        .plate-items-layer {
            position: absolute;
            inset: 40px;
            border-radius: 50%;
            pointer-events: none;
            z-index: 5;
        }
        .plate-item {
            position: absolute;
            width: 52px;
            height: 52px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid rgba(255,255,255,.2);
            box-shadow: 0 4px 14px rgba(0,0,0,.65);
            /* centrage via transform — pas besoin de dx/dy */
            transform: translate(-50%, -50%) scale(1);
            animation: popIn .35s cubic-bezier(.175,.885,.32,1.275);
        }
        .plate-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        @keyframes popIn {
            0%  { transform: translate(-50%, -50%) scale(0) rotate(-12deg); opacity: 0; }
            100%{ transform: translate(-50%, -50%) scale(1) rotate(0deg);   opacity: 1; }
        }

        /* Plate empty message */
        .plate-empty-msg {
            position: absolute;
            inset: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            pointer-events: none;
            z-index: 3;
            transition: opacity .3s;
        }
        .plate-empty-msg i {
            font-size: 1.8rem;
            color: #333;
            margin-bottom: 8px;
        }
        .plate-empty-msg p {
            font-size: .72rem;
            color: #444;
            text-align: center;
            letter-spacing: .05em;
            line-height: 1.5;
        }
        .plate-empty-msg.hidden { opacity: 0; }

        /* Clear plate button */
        .clear-plate-btn {
            position: absolute;
            bottom: 16px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(192,57,43,.15);
            border: 1px solid rgba(192,57,43,.4);
            color: #e74c3c;
            font-size: .72rem;
            padding: 6px 16px;
            border-radius: 20px;
            cursor: pointer;
            letter-spacing: .05em;
            transition: all .2s;
            display: flex;
            align-items: center;
            gap: 6px;
            opacity: 0;
            pointer-events: none;
        }
        .clear-plate-btn.visible {
            opacity: 1;
            pointer-events: all;
        }
        .clear-plate-btn:hover {
            background: rgba(192,57,43,.3);
            border-color: #e74c3c;
        }

        /* ===== RIGHT PANEL — TICKET ===== */
        .panel-ticket {
            background: #fafaf5;          /* fond blanc = le ticket EST le panel */
            border-left: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Barre titre ultra-compacte */
        .ticket-topbar {
            background: #111;
            border-bottom: 1px solid var(--gold-dim);
            padding: 0 10px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }
        .ticket-topbar-title {
            font-family: 'Playfair Display', serif;
            font-size: .72rem;
            color: var(--gold);
            letter-spacing: .07em;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .ticket-topbar-title i { font-size: .75rem; }
        .ticket-topbar-icons { display: flex; gap: 4px; }
        .topbar-icon-btn {
            width: 26px;
            height: 26px;
            background: rgba(201,168,76,.12);
            border: 1px solid var(--gold-dim);
            color: var(--gold);
            border-radius: 6px;
            cursor: pointer;
            font-size: .7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .18s;
        }
        .topbar-icon-btn:hover {
            background: rgba(201,168,76,.28);
            border-color: var(--gold);
        }

        /* Wrapper sans padding — le papier colle aux bords */
        .ticket-wrap {
            flex: 1;
            min-height: 0;
            display: flex;
            flex-direction: column;
        }

        /* Papier ticket = toute la hauteur, pleine largeur */
        .ticket-paper {
            background: #fafaf5;
            color: #222;
            font-family: 'Courier Prime', monospace;
            font-size: .85rem;
            line-height: 1.55;
            display: flex;
            flex-direction: column;
            flex: 1;
            min-height: 0;
            overflow: hidden;
            border-radius: 0;
        }

        /* EN-TÊTE sombre avec table intégrée */
        .ticket-header {
            background: #1a1a1a;
            color: #f0ead6;
            text-align: center;
            padding: 10px 12px 8px;
            flex-shrink: 0;
        }
        .ticket-resto-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.05rem;
            color: var(--gold);
            letter-spacing: .12em;
        }
        .ticket-sub-info {
            font-size: .6rem;
            color: #888;
            margin-top: 2px;
        }
        /* Table select + N° sur la même ligne dans l'en-tête */
        .ticket-header-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 7px;
            font-size: .72rem;
            color: #aaa;
        }
        .ticket-header-meta i { color: var(--gold); margin-right: 4px; }
        .ticket-header-meta strong { color: #f0ead6; }
        .table-select-inline {
            background: #2a2a2a;
            border: 1px solid var(--gold-dim);
            color: var(--gold);
            font-size: .72rem;
            padding: 2px 6px;
            border-radius: 5px;
            outline: none;
            cursor: pointer;
            margin-left: 4px;
        }
        .table-select-inline option { background: #1a1a1a; }

        /* Barre miniatures visuelles */
        .ticket-summary-bar {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            background: #edeadc;
            border-bottom: 1px dashed #d5d0c0;
            flex-wrap: wrap;
            min-height: 44px;
            flex-shrink: 0;
        }
        .summary-thumb {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid #c9a84c;
            flex-shrink: 0;
            position: relative;
            transition: transform .2s;
        }
        .summary-thumb:hover { transform: scale(1.15); }
        .summary-thumb img { width: 100%; height: 100%; object-fit: cover; }
        .summary-badge {
            position: absolute;
            bottom: -2px; right: -3px;
            background: #c0392b;
            color: #fff;
            font-size: .48rem;
            font-weight: 700;
            width: 14px; height: 14px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #fff;
        }
        .summary-empty { font-size: .7rem; color: #bbb; font-style: italic; }

        /* Titre section commande */
        .ticket-divider {
            text-align: center;
            color: #aaa;
            font-size: .7rem;
            padding: 4px 14px;
            letter-spacing: .07em;
            border-bottom: 1px dashed #ddd;
            flex-shrink: 0;
            background: #fafaf5;
        }

        /* ★ ZONE ARTICLES — scrollable, prend tout l'espace libre ★ */
        .ticket-items {
            flex: 1;
            min-height: 0;
            overflow-y: auto;
            padding: 0 14px 4px;
        }
        .ticket-items::-webkit-scrollbar { width: 6px; }
        .ticket-items::-webkit-scrollbar-track { background: #ede9dc; }
        .ticket-items::-webkit-scrollbar-thumb {
            background: #c9a84c;
            border-radius: 3px;
        }
        .ticket-items::-webkit-scrollbar-thumb:hover { background: #a8872e; }

        /* Message vide — affiché à la place de ticket-items quand commande vide */
        .ticket-empty {
            flex-shrink: 0;
            text-align: center;
            color: #ccc;
            padding: 32px 0 16px;
            font-size: .8rem;
            font-style: italic;
        }
        /* Quand caché, il ne prend aucune place */
        .ticket-empty[style*="display: none"],
        .ticket-empty[style*="display:none"] { padding: 0; }

        @keyframes slideIn {
            from { opacity: 0; transform: translateX(-6px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        /* ★ TOTAUX — toujours visibles, collés en bas du papier ★ */
        .ticket-totals {
            flex-shrink: 0;
            border-top: 2px dashed #c9a84c;
            padding: 8px 14px 6px;
            background: #f5f2e8;
        }
        .ticket-total-row {
            display: flex;
            justify-content: space-between;
            padding: 2px 0;
            font-size: .78rem;
            color: #666;
        }
        .ticket-total-row.grand {
            font-size: 1.05rem;
            font-weight: 700;
            color: #111;
            border-top: 2px solid #222;
            margin-top: 5px;
            padding-top: 5px;
            letter-spacing: .03em;
        }
        .ticket-total-row.grand .ttc-badge {
            background: #c9a84c;
            color: #000;
            font-size: .55rem;
            font-weight: 800;
            padding: 1px 5px;
            border-radius: 4px;
            vertical-align: middle;
            margin-left: 5px;
            letter-spacing: .05em;
        }

        .ticket-footer {
            text-align: center;
            padding: 6px 12px 10px;
            color: #bbb;
            font-size: .62rem;
            border-top: 1px dashed #ddd;
            flex-shrink: 0;
            background: #fafaf5;
        }

        /* ticket-teeth supprimé — ticket pleine hauteur */

        /* Bouton valider — seul élément sous le ticket */
        .ticket-actions {
            padding: 8px 10px;
            background: #111;
            flex-shrink: 0;
            border-top: 1px solid var(--gold-dim);
        }
        .btn-validate {
            background: linear-gradient(135deg, var(--gold-dim), var(--gold));
            color: #000;
            border: none;
            border-radius: 8px;
            padding: 11px;
            font-weight: 700;
            font-size: .85rem;
            letter-spacing: .04em;
            cursor: pointer;
            transition: all .2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-validate:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(201,168,76,.35);
        }
        .btn-validate:disabled {
            background: #2a2a2a;
            color: #555;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        /* btn-clear-ticket déplacé dans topbar-icon-btn */

        /* ========== TOAST ========== */
        .toast-container {
            position: fixed;
            bottom: 24px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            pointer-events: none;
        }
        .toast-msg {
            background: rgba(30,30,20,.95);
            border: 1px solid var(--gold-dim);
            color: var(--gold-light);
            padding: 8px 18px;
            border-radius: 30px;
            font-size: .78rem;
            letter-spacing: .04em;
            animation: toastIn .3s ease, toastOut .3s ease 1.7s forwards;
            display: flex;
            align-items: center;
            gap: 7px;
        }
        @keyframes toastIn  { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes toastOut { from { opacity: 1; } to { opacity: 0; } }

        /* ========== FLYING ITEM ANIMATION ========== */
        .flying-item {
            position: fixed;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid var(--gold);
            pointer-events: none;
            z-index: 9998;
            box-shadow: 0 0 20px rgba(201,168,76,.4);
            transition: none;
        }
        .flying-item img { width: 100%; height: 100%; object-fit: cover; }

        /* table-selector supprimé — intégré dans ticket-header-meta */

        /* ========== ZOOM PLATEAU ========== */
        .zoom-controls {
            position: absolute;
            top: 14px;
            right: 18px;
            display: flex;
            gap: 6px;
            z-index: 20;
        }
        .zoom-btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(201,168,76,.12);
            border: 1px solid var(--gold-dim);
            color: var(--gold);
            font-size: .9rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all .2s;
        }
        .zoom-btn:hover {
            background: rgba(201,168,76,.25);
            border-color: var(--gold);
        }
        /* Sizes */
        .plate-container                 { width: 340px; height: 340px; transition: all .35s cubic-bezier(.4,0,.2,1); }
        .plate-container.size-md         { width: 440px; height: 440px; }
        .plate-container.size-lg         { width: 540px; height: 540px; }

        /* ========== TICKET ITEM ROW — avec +/- ========== */
        .ticket-item-row {
            display: grid;
            grid-template-columns: 1fr auto auto;
            align-items: center;
            gap: 0;
            padding: 7px 0;
            border-bottom: 1px solid #e8e4d8;
            animation: slideIn .22s ease;
        }
        .ticket-item-row:last-child { border-bottom: none; }

        .t-left  { display: flex; flex-direction: column; }
        .t-name  { font-size: .9rem; font-weight: 700; color: #111; line-height: 1.2; }
        .t-unit  { font-size: .68rem; color: #aaa; margin-top: 2px; }

        .t-qty-ctrl {
            display: flex;
            align-items: center;
            gap: 0;
            border: 1.5px solid #c9a84c;
            border-radius: 20px;
            overflow: hidden;
            margin: 0 10px;
        }
        .t-btn {
            width: 26px;
            height: 26px;
            background: none;
            border: none;
            cursor: pointer;
            font-size: .9rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .15s;
            color: #555;
            flex-shrink: 0;
        }
        .t-btn.minus:hover { background: #fde8e8; color: #c0392b; }
        .t-btn.plus:hover  { background: #e8f8ed; color: #27ae60; }
        .t-qty-val {
            min-width: 26px;
            text-align: center;
            font-weight: 800;
            font-size: .88rem;
            color: #c0392b;
            background: #f5f2e8;
            padding: 0 2px;
        }
        .t-price {
            min-width: 70px;
            text-align: right;
            font-weight: 800;
            font-size: .9rem;
            color: #111;
            white-space: nowrap;
        }

        /* ticket-summary-bar + summary-thumb définis dans le bloc ticket ci-dessus */

        /* ========== RESPONSIVE ========== */
        @media (max-width: 1200px) {
            .app-layout { grid-template-columns: 240px 1fr 340px; }
        }
        @media (max-width: 1000px) {
            .app-layout { grid-template-columns: 210px 1fr 300px; }
            .plate-container { width: 300px !important; height: 300px !important; }
        }
        @media (max-width: 820px) {
            .app-layout { grid-template-columns: 180px 1fr 260px; }
            .plate-container { width: 240px !important; height: 240px !important; }
        }
    </style>
</head>
<body>

<!-- HEADER -->
<header>
    <div class="brand">
        <i class="fa-solid fa-utensils"></i>
        Le Gourmet
    </div>
    <div class="header-info">
        <span><i class="fa-regular fa-clock"></i> <span id="hdr-time">--:--</span></span>
        <span><i class="fa-regular fa-calendar"></i> <span id="hdr-date">--</span></span>
        <span><i class="fa-solid fa-circle" style="color:#27ae60;font-size:.55rem"></i> Service actif</span>
    </div>
</header>

<!-- MAIN LAYOUT -->
<div class="app-layout">

    <!-- ======== LEFT : MENU ======== -->
    <aside class="panel-menu">
        <div class="panel-title">
            <i class="fa-solid fa-book-open"></i>
            Menu du jour
        </div>
        <div class="menu-scroll" id="menu-list">
            <!-- generated by JS -->
        </div>
    </aside>

    <!-- ======== MIDDLE : PLATEAU ======== -->
    <main class="panel-plate">
        <div class="plate-label">
            <i class="fa-solid fa-hand-pointer" style="margin-right:6px;color:var(--gold-dim)"></i>
            Glissez les articles sur le plateau
        </div>

        <div class="plate-container">

            <!-- Plate background -->
            <div class="plate-bg">
                <img class="plate-full" src="images/plateau.png" alt="Plateau" draggable="false">
            </div>

            <!-- Items placed on plate -->
            <div class="plate-items-layer" id="plate-items-layer"></div>

            <!-- Empty message -->
            <div class="plate-empty-msg" id="plate-empty-msg">
                <i class="fa-regular fa-circle-dot"></i>
                <p>Le plateau est vide<br><span style="color:#333">Faites glisser un article</span></p>
            </div>

            <!-- Drop zone (over everything) -->
            <div class="plate-drop-zone" id="plate-drop-zone">
                <div class="drop-hint">
                    <i class="fa-solid fa-plus-circle"></i>
                    <span>DÉPOSER ICI</span>
                </div>
            </div>
        </div>

        <!-- Zoom controls -->
        <div class="zoom-controls">
            <button class="zoom-btn" id="zoom-out-btn" title="Réduire" style="display:none">
                <i class="fa-solid fa-magnifying-glass-minus"></i>
            </button>
            <button class="zoom-btn" id="zoom-in-btn" title="Agrandir">
                <i class="fa-solid fa-magnifying-glass-plus"></i>
            </button>
        </div>

        <button class="clear-plate-btn" id="clear-plate-btn">
            <i class="fa-solid fa-rotate-left"></i>
            Vider le plateau
        </button>
    </main>

    <!-- ======== RIGHT : TICKET ======== -->
    <aside class="panel-ticket">

        <!-- Barre titre minimaliste -->
        <div class="ticket-topbar">
            <span class="ticket-topbar-title">
                <i class="fa-solid fa-receipt"></i>
                Ticket de commande
            </span>
            <div class="ticket-topbar-icons">
                <button class="topbar-icon-btn" id="btn-clear-ticket" title="Effacer">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
                <button class="topbar-icon-btn" id="btn-print" title="Imprimer" onclick="window.print()">
                    <i class="fa-solid fa-print"></i>
                </button>
            </div>
        </div>

        <!-- Ticket papier — occupe toute la hauteur restante -->
        <div class="ticket-wrap">
            <div class="ticket-paper" id="ticket-paper">

                <!-- EN-TÊTE : nom resto + table + N° commande -->
                <div class="ticket-header">
                    <div class="ticket-resto-name">✦ LE GOURMET ✦</div>
                    <div class="ticket-sub-info">Tunis — <span id="t-date">--</span></div>
                    <div class="ticket-header-meta">
                        <span>
                            <i class="fa-solid fa-chair"></i>
                            <select id="table-select" class="table-select-inline">
                                <option value="01">Table 01</option>
                                <option value="02">Table 02</option>
                                <option value="03">Table 03</option>
                                <option value="04">Table 04</option>
                                <option value="05">Table 05</option>
                                <option value="06">Table 06</option>
                            </select>
                        </span>
                        <span>N° <strong id="t-num">0001</strong></span>
                    </div>
                </div>

                <!-- MINIATURES VISUELLES -->
                <div class="ticket-summary-bar" id="ticket-summary-bar">
                    <span class="summary-empty" id="summary-empty-label">Glissez un article sur le plateau</span>
                </div>

                <!-- SÉPARATEUR -->
                <div class="ticket-divider">— VOS ARTICLES —</div>

                <!-- Message vide (jamais dans #ticket-items) -->
                <div class="ticket-empty" id="ticket-empty-msg">
                    Glissez un article sur le plateau
                </div>

                <!-- ★ LISTE SCROLLABLE ★ -->
                <div class="ticket-items" id="ticket-items"></div>

                <!-- ★ TOTAUX — toujours visibles en bas ★ -->
                <div class="ticket-totals" id="ticket-totals" style="display:none">
                    <div class="ticket-total-row">
                        <span>Sous-total HT</span>
                        <span id="t-subtotal">0.000 DT</span>
                    </div>
                    <div class="ticket-total-row">
                        <span>TVA (19%)</span>
                        <span id="t-tva">0.000 DT</span>
                    </div>
                    <div class="ticket-total-row grand">
                        <span>À PAYER <span class="ttc-badge">TTC</span></span>
                        <span id="t-total">0.000 DT</span>
                    </div>
                </div>

                <div class="ticket-footer">
                    Merci de votre visite ✦ Bon appétit !
                </div>
            </div>
        </div>

        <!-- Bouton Valider (seul bouton hors ticket) -->
        <div class="ticket-actions">
            <button class="btn-validate" id="btn-validate" disabled>
                <i class="fa-solid fa-check-circle"></i>
                Valider la commande
            </button>
        </div>
    </aside>
</div>

<!-- TOAST CONTAINER -->
<div class="toast-container" id="toast-container"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
'use strict';

/* =========================================================
   DATA — articles (static, deviendra dynamique PHP/MySQL)
   ========================================================= */
const ARTICLES = [
    { id: 1, nom: 'Babybel',          cat: 'Fromages',  prix: 1.500, img: 'images/article1.png' },
    { id: 2, nom: 'Fromage frais',    cat: 'Fromages',  prix: 2.000, img: 'images/article2.png' },
    { id: 3, nom: "S'Môret crémeux", cat: 'Fromages',  prix: 2.500, img: 'images/article3.png' },
    { id: 4, nom: 'Confiture maison', cat: 'Épicerie',  prix: 3.000, img: 'images/article4.png' },
    { id: 5, nom: 'Crêpe dorée',      cat: 'Plats',     prix: 2.500, img: 'images/article5.png' },
    { id: 6, nom: 'Sauce tomate',     cat: 'Sauces',    prix: 1.500, img: 'images/article6.png' },
    { id: 7, nom: 'Charcuterie',      cat: 'Plats',     prix: 4.000, img: 'images/article7.png' },
];

/* =========================================================
   STATE
   ========================================================= */
const state = {
    order:       {},   // { articleId: { article, qty } }
    plateItems:  [],   // array of articleIds on plate (visual)
    orderNum:    1,
};

/* =========================================================
   POSITIONS on plate (concentric circle slots)
   ========================================================= */
/* Grille 3 colonnes × 4 rangées = 12 slots
   Ordre : gauche→centre→droite, rangée 1→4
   Positions = point centre de chaque item (transform:translate(-50%,-50%)) */
const SLOTS = [
    // ── Rangée 1 (haut) ────────────────────────────────
    { top: '14%', left: '22%' },   // [0]  haut-gauche
    { top: '14%', left: '50%' },   // [1]  haut-centre
    { top: '14%', left: '78%' },   // [2]  haut-droite
    // ── Rangée 2 ────────────────────────────────────────
    { top: '38%', left: '22%' },   // [3]  milieu-haut gauche
    { top: '38%', left: '50%' },   // [4]  milieu-haut centre
    { top: '38%', left: '78%' },   // [5]  milieu-haut droite
    // ── Rangée 3 ────────────────────────────────────────
    { top: '62%', left: '22%' },   // [6]  milieu-bas gauche
    { top: '62%', left: '50%' },   // [7]  milieu-bas centre
    { top: '62%', left: '78%' },   // [8]  milieu-bas droite
    // ── Rangée 4 (bas) ──────────────────────────────────
    { top: '86%', left: '22%' },   // [9]  bas-gauche
    { top: '86%', left: '50%' },   // [10] bas-centre
    { top: '86%', left: '78%' },   // [11] bas-droite
];

/* =========================================================
   UTILS
   ========================================================= */
function fmt(n) { return parseFloat(n).toFixed(3) + ' DT'; }

function toast(msg, icon = 'fa-utensils') {
    const c = document.getElementById('toast-container');
    const el = document.createElement('div');
    el.className = 'toast-msg';
    el.innerHTML = `<i class="fa-solid ${icon}"></i> ${msg}`;
    c.appendChild(el);
    setTimeout(() => el.remove(), 2100);
}

function updateClock() {
    const now  = new Date();
    const time = now.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
    const date = now.toLocaleDateString('fr-FR',  { weekday: 'long', day: 'numeric', month: 'long' });
    document.getElementById('hdr-time').textContent = time;
    document.getElementById('hdr-date').textContent = date;
    // t-date dans le ticket (t-time supprimé du HTML — on vérifie avant d'accéder)
    const tDate = document.getElementById('t-date');
    if (tDate) tDate.textContent = now.toLocaleDateString('fr-FR');
}
setInterval(updateClock, 1000);
updateClock();

/* Table select — synchronise le N° de commande affiché */
document.getElementById('table-select').addEventListener('change', function () {
    const tTable = document.getElementById('t-table');
    if (tTable) tTable.textContent = this.value;
});

/* =========================================================
   RENDER MENU (LEFT)
   ========================================================= */
function renderMenu() {
    const list = document.getElementById('menu-list');
    list.innerHTML = '';
    ARTICLES.forEach(a => {
        const card = document.createElement('div');
        card.className = 'article-card';
        card.draggable  = true;
        card.dataset.id = a.id;
        card.innerHTML  = `
            <div class="article-img-wrap">
                <img src="${a.img}" alt="${a.nom}">
            </div>
            <div class="article-info">
                <div class="article-name">${a.nom}</div>
                <div class="article-sub">${a.cat}</div>
            </div>
            <div class="article-price">${fmt(a.prix)}</div>
            <span class="qty-badge" id="badge-${a.id}"></span>
            <div class="drag-hint">
                <i class="fa-solid fa-grip-vertical" style="font-size:.6rem"></i>
            </div>`;
        // Drag events
        card.addEventListener('dragstart', onDragStart);
        card.addEventListener('dragend',   onDragEnd);
        // Touch support
        card.addEventListener('touchstart', onTouchStart, { passive: false });
        list.appendChild(card);
    });
}

/* =========================================================
   DRAG & DROP — Desktop
   ========================================================= */
let draggingId = null;

function onDragStart(e) {
    draggingId = parseInt(e.currentTarget.dataset.id);
    e.currentTarget.classList.add('dragging');
    e.dataTransfer.effectAllowed = 'copy';
    e.dataTransfer.setData('text/plain', draggingId);
    // Custom ghost image
    const ghost = e.currentTarget.querySelector('.article-img-wrap').cloneNode(true);
    ghost.style.cssText = 'width:60px;height:60px;border-radius:50%;position:fixed;top:-100px;left:-100px;border:2px solid #c9a84c';
    document.body.appendChild(ghost);
    e.dataTransfer.setDragImage(ghost, 30, 30);
    setTimeout(() => ghost.remove(), 0);
}

function onDragEnd(e) {
    e.currentTarget.classList.remove('dragging');
    draggingId = null;
}

const dropZone = document.getElementById('plate-drop-zone');

dropZone.addEventListener('dragover', e => {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'copy';
    dropZone.classList.add('drag-over');
});

dropZone.addEventListener('dragleave', e => {
    if (!dropZone.contains(e.relatedTarget)) {
        dropZone.classList.remove('drag-over');
    }
});

dropZone.addEventListener('drop', e => {
    e.preventDefault();
    dropZone.classList.remove('drag-over');
    const id = parseInt(e.dataTransfer.getData('text/plain'));
    if (id) addArticle(id);
});

/* =========================================================
   DRAG & DROP — Touch (mobile)
   ========================================================= */
let touchDragEl   = null;
let touchDragId   = null;
let flyingEl      = null;

function onTouchStart(e) {
    e.preventDefault();
    const card  = e.currentTarget;
    touchDragId = parseInt(card.dataset.id);
    const art   = ARTICLES.find(a => a.id === touchDragId);
    const touch = e.touches[0];

    // Create flying element
    flyingEl = document.createElement('div');
    flyingEl.className = 'flying-item';
    flyingEl.innerHTML = `<img src="${art.img}" alt="">`;
    flyingEl.style.left = (touch.clientX - 28) + 'px';
    flyingEl.style.top  = (touch.clientY - 28) + 'px';
    document.body.appendChild(flyingEl);

    card.classList.add('dragging');
    touchDragEl = card;

    document.addEventListener('touchmove',  onTouchMove,  { passive: false });
    document.addEventListener('touchend',   onTouchEnd,   { passive: false });
}

function onTouchMove(e) {
    e.preventDefault();
    if (!flyingEl) return;
    const touch = e.touches[0];
    flyingEl.style.left = (touch.clientX - 28) + 'px';
    flyingEl.style.top  = (touch.clientY - 28) + 'px';

    // Highlight drop zone if over it
    const zone  = dropZone.getBoundingClientRect();
    const cx    = touch.clientX, cy = touch.clientY;
    const over  = cx >= zone.left && cx <= zone.right && cy >= zone.top && cy <= zone.bottom;
    dropZone.classList.toggle('drag-over', over);
}

function onTouchEnd(e) {
    if (!flyingEl) return;
    const touch = e.changedTouches[0];
    flyingEl.remove();
    flyingEl = null;

    if (touchDragEl) touchDragEl.classList.remove('dragging');
    dropZone.classList.remove('drag-over');

    // Check if dropped on zone
    const zone = dropZone.getBoundingClientRect();
    const cx = touch.clientX, cy = touch.clientY;
    if (cx >= zone.left && cx <= zone.right && cy >= zone.top && cy <= zone.bottom) {
        addArticle(touchDragId);
    }

    document.removeEventListener('touchmove', onTouchMove);
    document.removeEventListener('touchend',  onTouchEnd);
    touchDragId = null;
    touchDragEl = null;
}

/* =========================================================
   ADD ARTICLE — core logic
   ========================================================= */
const MAX_TICKET_LINES = 20;   // nb max d'articles distincts dans la commande

function addArticle(id) {
    const art = ARTICLES.find(a => a.id === id);
    if (!art) return;

    // Vérifier limite 20 articles distincts
    const nbDistincts = Object.keys(state.order).length;
    const isNew = !state.order[id];
    if (isNew && nbDistincts >= MAX_TICKET_LINES) {
        toast(`Maximum ${MAX_TICKET_LINES} articles atteint`, 'fa-ban');
        return;
    }

    // Mettre à jour la commande
    if (state.order[id]) {
        state.order[id].qty++;
    } else {
        state.order[id] = { article: art, qty: 1 };
    }

    // Plateau visuel — max 9 emplacements (grille 3×3)
    if (state.plateItems.length < SLOTS.length) {
        state.plateItems.push(id);
        renderPlateItem(id, state.plateItems.length - 1, art.img);
    }

    // Masquer message vide plateau
    document.getElementById('plate-empty-msg').classList.add('hidden');
    document.getElementById('clear-plate-btn').classList.add('visible');

    // Badge sur la carte menu
    updateMenuBadge(id);

    // Mettre à jour le ticket
    renderTicket();

    toast(`${art.nom} ajouté`, 'fa-plus-circle');
}

/* =========================================================
   RENDER PLATE ITEM
   ========================================================= */
function renderPlateItem(id, slotIdx, img) {
    const layer = document.getElementById('plate-items-layer');
    const slot  = SLOTS[slotIdx % SLOTS.length];   // boucle si > 9 (affichage superposé)
    const el    = document.createElement('div');
    el.className   = 'plate-item';
    el.dataset.slot = slotIdx;
    el.style.top   = slot.top;
    el.style.left  = slot.left;
    el.innerHTML   = `<img src="${img}" alt="">`;
    layer.appendChild(el);
}

/* =========================================================
   RENDER TICKET — version sécurisée
   emptyMsg est HORS du container : jamais détruit par innerHTML = ''
   ========================================================= */
function renderTicket() {
    const container = document.getElementById('ticket-items');   // liste scrollable
    const emptyMsg  = document.getElementById('ticket-empty-msg'); // frère fixe
    const totalsEl  = document.getElementById('ticket-totals');
    const btnVal    = document.getElementById('btn-validate');

    const items = Object.values(state.order);   // tableau [{article, qty}, ...]

    // Miniatures dans la barre visuelle
    renderSummaryBar(items);

    // ── Cas : commande vide ──────────────────────────────
    if (items.length === 0) {
        container.innerHTML        = '';          // vide le container de lignes
        emptyMsg.style.display     = 'block';     // affiche le message
        totalsEl.style.display     = 'none';
        btnVal.disabled            = true;
        document.getElementById('t-subtotal').textContent = fmt(0);
        document.getElementById('t-tva').textContent      = fmt(0);
        document.getElementById('t-total').textContent    = fmt(0);
        updateTicketNum();
        return;
    }

    // ── Cas : au moins 1 article ────────────────────────
    emptyMsg.style.display = 'none';             // cache le message vide
    totalsEl.style.display = 'block';
    btnVal.disabled        = false;

    // Vider et reconstruire toutes les lignes
    container.innerHTML = '';
    let subtotalHT = 0;

    items.forEach(({ article: a, qty }) => {
        const pu       = parseFloat(a.prix);
        const qte      = parseInt(qty);
        const ligneHT  = pu * qte;
        subtotalHT    += ligneHT;
        const ligneTTC = ligneHT * 1.19;

        const row = document.createElement('div');
        row.className = 'ticket-item-row';
        row.innerHTML = `
            <div class="t-left">
                <span class="t-name">${a.nom}</span>
                <span class="t-unit">${fmt(pu * 1.19)} TTC / u.</span>
            </div>
            <div class="t-qty-ctrl">
                <button class="t-btn minus" data-id="${a.id}">−</button>
                <span class="t-qty-val">${qte}</span>
                <button class="t-btn plus"  data-id="${a.id}">+</button>
            </div>
            <span class="t-price">${fmt(ligneTTC)}</span>`;
        container.appendChild(row);
    });

    // Attacher les handlers +/- (après insertion dans le DOM)
    container.querySelectorAll('.t-btn.minus').forEach(btn =>
        btn.addEventListener('click', () => changeQty(parseInt(btn.dataset.id), -1)));
    container.querySelectorAll('.t-btn.plus').forEach(btn =>
        btn.addEventListener('click', () => changeQty(parseInt(btn.dataset.id), +1)));

    // Calculer et afficher les totaux TTC
    const tva      = subtotalHT * 0.19;
    const totalTTC = subtotalHT + tva;
    document.getElementById('t-subtotal').textContent = fmt(subtotalHT);
    document.getElementById('t-tva').textContent      = fmt(tva);
    document.getElementById('t-total').textContent    = fmt(totalTTC);

    updateTicketNum();
}

/* =========================================================
   SUMMARY BAR — miniatures en haut du ticket
   ========================================================= */
function renderSummaryBar(items) {
    const bar = document.getElementById('ticket-summary-bar');
    const lbl = document.getElementById('summary-empty-label');

    // Supprimer seulement les miniatures, pas le label
    bar.querySelectorAll('.summary-thumb').forEach(el => el.remove());

    if (items.length === 0) {
        lbl.style.display = 'inline';
        return;
    }

    lbl.style.display = 'none';
    items.forEach(({ article: a, qty }) => {
        const wrap = document.createElement('div');
        wrap.className = 'summary-thumb';
        wrap.title = `${a.nom} ×${qty}`;
        wrap.innerHTML = `
            <img src="${a.img}" alt="${a.nom}">
            <span class="summary-badge">${qty}</span>`;
        bar.appendChild(wrap);
    });
}

/* =========================================================
   CHANGE QTY (+/-)
   ========================================================= */
function changeQty(id, delta) {
    if (!state.order[id]) return;
    const art = state.order[id].article;
    state.order[id].qty += delta;

    if (state.order[id].qty <= 0) {
        delete state.order[id];
        const idx = state.plateItems.lastIndexOf(id);
        if (idx !== -1) state.plateItems.splice(idx, 1);
        rebuildPlate();
        if (state.plateItems.length === 0) {
            document.getElementById('plate-empty-msg').classList.remove('hidden');
            document.getElementById('clear-plate-btn').classList.remove('visible');
        }
        toast(`${art.nom} retiré`, 'fa-minus-circle');
    } else if (delta > 0) {
        if (state.plateItems.length < SLOTS.length) {
            state.plateItems.push(id);
            renderPlateItem(id, state.plateItems.length - 1, art.img);
        }
        toast(`${art.nom} +1`, 'fa-plus-circle');
    } else {
        const idx = state.plateItems.lastIndexOf(id);
        if (idx !== -1) state.plateItems.splice(idx, 1);
        rebuildPlate();
        toast(`${art.nom} −1`, 'fa-minus-circle');
    }
    updateMenuBadge(id);
    renderTicket();
}

function updateTicketNum() {
    document.getElementById('t-num').textContent =
        String(state.orderNum).padStart(4, '0');
}

/* =========================================================
   BADGE QUANTITÉ SUR CARTE MENU
   ========================================================= */
function updateMenuBadge(id) {
    const badge = document.getElementById(`badge-${id}`);
    if (!badge) return;
    const qty = state.order[id] ? state.order[id].qty : 0;
    if (qty > 0) {
        badge.textContent = qty;
        badge.classList.add('show');
    } else {
        badge.classList.remove('show');
    }
}

function updateAllMenuBadges() {
    ARTICLES.forEach(a => updateMenuBadge(a.id));
}

/* =========================================================
   ZOOM PLATEAU
   ========================================================= */
const ZOOM_SIZES = ['', 'size-md', 'size-lg'];
let zoomLevel = 0;

document.getElementById('zoom-in-btn').addEventListener('click', () => {
    if (zoomLevel >= ZOOM_SIZES.length - 1) return;
    const pc = document.querySelector('.plate-container');
    pc.classList.remove(ZOOM_SIZES[zoomLevel]);
    zoomLevel++;
    pc.classList.add(ZOOM_SIZES[zoomLevel]);
    document.getElementById('zoom-out-btn').style.display = 'flex';
    document.getElementById('zoom-in-btn').style.display  = zoomLevel >= ZOOM_SIZES.length - 1 ? 'none' : 'flex';
});

document.getElementById('zoom-out-btn').addEventListener('click', () => {
    if (zoomLevel <= 0) return;
    const pc = document.querySelector('.plate-container');
    pc.classList.remove(ZOOM_SIZES[zoomLevel]);
    zoomLevel--;
    pc.classList.add(ZOOM_SIZES[zoomLevel]);
    document.getElementById('zoom-in-btn').style.display  = 'flex';
    document.getElementById('zoom-out-btn').style.display = zoomLevel <= 0 ? 'none' : 'flex';
});

/* =========================================================
   REBUILD PLATE VISUALS
   ========================================================= */
function rebuildPlate() {
    const layer = document.getElementById('plate-items-layer');
    layer.innerHTML = '';
    state.plateItems.forEach((id, idx) => {
        const art = ARTICLES.find(a => a.id === id);
        if (art) renderPlateItem(id, idx, art.img);
    });
}

/* =========================================================
   CLEAR PLATE
   ========================================================= */
document.getElementById('clear-plate-btn').addEventListener('click', () => {
    // Retirer visuellement du plateau mais garder le ticket
    state.plateItems = [];
    document.getElementById('plate-items-layer').innerHTML = '';
    document.getElementById('plate-empty-msg').classList.remove('hidden');
    document.getElementById('clear-plate-btn').classList.remove('visible');
    toast('Plateau vidé', 'fa-rotate-left');
});

/* =========================================================
   CLEAR TICKET
   ========================================================= */
document.getElementById('btn-clear-ticket').addEventListener('click', () => {
    state.order      = {};
    state.plateItems = [];
    rebuildPlate();
    document.getElementById('plate-empty-msg').classList.remove('hidden');
    document.getElementById('clear-plate-btn').classList.remove('visible');
    updateAllMenuBadges();
    renderTicket();
    toast('Ticket effacé', 'fa-trash-can');
});

/* =========================================================
   VALIDATE ORDER
   ========================================================= */
document.getElementById('btn-validate').addEventListener('click', () => {
    if (Object.keys(state.order).length === 0) return;
    const total = Object.values(state.order)
        .reduce((s, {article: a, qty}) => s + parseFloat(a.prix) * parseInt(qty) * 1.19, 0);
    toast(`Commande #${String(state.orderNum).padStart(4,'0')} validée — ${fmt(total)}`, 'fa-check-circle');
    state.orderNum++;
    state.order      = {};
    state.plateItems = [];
    rebuildPlate();
    document.getElementById('plate-empty-msg').classList.remove('hidden');
    document.getElementById('clear-plate-btn').classList.remove('visible');
    updateAllMenuBadges();
    renderTicket();
});

/* =========================================================
   INIT
   ========================================================= */
renderMenu();
renderTicket();
</script>
</body>
</html>
