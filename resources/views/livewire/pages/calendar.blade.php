@push('styles')
    <style>
        main.page-calendar { padding: 16px 16px 110px; }
        body.modal-open { overflow: hidden; }
        .calendar-shell {
            max-width: 780px;
            margin: 0 auto;
            display: grid;
            gap: 18px;
            position: relative;
        }
        .filters-panel {
            background: linear-gradient(135deg, #0f0f12 0%, #0b0b0e 100%);
            border: 1px solid rgba(126, 252, 91, 0.15);
            border-radius: 16px;
            padding: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: stretch;
        }
        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            color: var(--muted);
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            flex: 1;
            min-width: 140px;
        }
        .filter-select {
            background: #1a1a1e;
            border: 2px solid #2a2a2e;
            border-radius: 12px;
            padding: 12px 14px;
            color: var(--text);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%237efc5b' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
        }
        .filter-select:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(126, 252, 91, 0.1);
        }
        .filter-select:active {
            transform: scale(0.98);
        }
        .title-block {
            text-align: center;
            margin: 14px 0 10px;
            font-weight: 700;
            letter-spacing: 0.08em;
            font-size: 18px;
        }
        .title-block span { color: var(--accent); }
        .calendar-card {
            background: linear-gradient(180deg, #f4f3f7 0%, #ededf2 100%);
            border: 1px solid rgba(255,255,255,0.6);
            border-radius: 20px;
            padding: 16px 16px 18px;
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.35);
            color: #17171a;
        }
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }
        .calendar-header .label {
            font-size: 12px;
            color: #6b6b74;
        }
        .calendar-header .date-title {
            font-size: 20px;
            font-weight: 700;
            color: #141416;
        }
        .calendar-header button {
            width: 34px;
            height: 34px;
            border-radius: 12px;
            background: #e7e6ee;
            border: none;
            color: #4f4f57;
            font-size: 16px;
        }
        .month-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #5a5a62;
            margin: 10px 0 8px;
            font-size: 13px;
        }
        .month-row button {
            background: #ececf1;
            border: none;
            color: #5a5a62;
            font-size: 18px;
            width: 32px;
            height: 28px;
            border-radius: 10px;
        }
        .weekday-row, .week-row {
            display: grid;
            grid-template-columns: repeat(7, minmax(0, 1fr));
            gap: 4px;
            text-align: center;
            color: #8a8a92;
            font-size: 11px;
            margin-bottom: 4px;
        }
        .weekday-row {
            text-transform: uppercase;
            letter-spacing: 0.12em;
        }
        .day {
            height: 42px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2px;
            border-radius: 10px;
            border: 1px solid #e2e2e6;
            color: #1b1b1e;
            background: #ffffff;
            cursor: pointer;
            transition: transform 0.2s ease, border-color 0.2s ease, background 0.2s ease;
            font-weight: 600;
            width: min(42px, 100%);
            aspect-ratio: 1 / 1;
            justify-self: center;
            position: relative;
            padding: 4px 2px;
        }
        .day.selected {
            background: #7efc5b;
            border-color: #7efc5b;
            color: #0a0a0a;
            font-weight: 700;
            box-shadow: 0 6px 14px rgba(126,252,91,0.45);
        }
        .day.busy {
            background: #f7f7fb;
            border-color: rgba(126,252,91,0.9);
            color: #1b1b1e;
        }
        .day-number {
            font-size: 13px;
            line-height: 1;
        }
        .trainer-dots {
            display: flex;
            gap: 2px;
            flex-wrap: wrap;
            justify-content: center;
            max-width: 100%;
        }
        .trainer-dot {
            width: 4px;
            height: 4px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .day:hover { transform: translateY(-1px); }
        .trainer-section {
            margin-bottom: 20px;
        }
        .trainer-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 14px;
            background: var(--panel-2);
            border: 1px solid var(--line);
            border-radius: 14px 14px 0 0;
            margin-bottom: 0;
        }
        .trainer-badge {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: grid;
            place-items: center;
            font-size: 11px;
            font-weight: 700;
            color: #0a0a0a;
            flex-shrink: 0;
        }
        .trainer-name {
            font-size: 16px;
            font-weight: 700;
            color: var(--text);
        }
        .trainer-section .list-card {
            border-radius: 0;
            margin-bottom: 0;
            border-top: none;
        }
        .trainer-section .list-card:last-child {
            border-radius: 0 0 14px 14px;
        }
        .time-slot-selector {
            margin: 10px 0;
        }
        .time-label {
            display: block;
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }
        .time-select {
            width: 100%;
            background: #0f0f12;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 10px 12px;
            color: var(--text);
            font-size: 13px;
            cursor: pointer;
        }
        .time-select:focus {
            outline: none;
            border-color: var(--accent);
        }
        .single-time {
            display: flex;
            align-items: center;
            gap: 6px;
            color: var(--muted);
            font-size: 13px;
            margin: 8px 0;
        }
        .single-time i {
            color: var(--accent);
        }
        .list-card {
            display: grid;
            grid-template-columns: 94px 1fr;
            gap: 14px;
            padding: 12px;
            border-radius: 14px;
            background: var(--panel-2);
            border: 1px solid var(--line);
            margin-bottom: 12px;
            box-shadow: var(--shadow);
        }
        .thumb {
            width: 100%;
            aspect-ratio: 1/1;
            border-radius: 12px;
            background: linear-gradient(135deg, #1c1c21, #0f0f12);
            position: relative;
            overflow: hidden;
        }
        .thumb::after {
            content: "";
            position: absolute;
            inset: 0;
            background: repeating-linear-gradient(
                135deg,
                rgba(255,255,255,0.04),
                rgba(255,255,255,0.04) 10px,
                transparent 10px,
                transparent 20px
            );
        }
        .list-body .category {
            color: var(--muted);
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.06em;
            margin-bottom: 4px;
        }
        .list-body .title {
            font-weight: 700;
            font-size: 16px;
            margin-bottom: 8px;
        }
        .list-body .trainer {
            font-size: 13px;
            color: var(--text);
            margin-bottom: 6px;
        }
        .list-tags {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }
        .list-tags span {
            padding: 6px 10px;
            background: #0f0f12;
            border-radius: 10px;
            color: var(--muted);
            font-size: 11px;
            border: 1px solid var(--line);
        }
        .token-pill {
            align-self: flex-start;
            padding: 6px 12px;
            border-radius: 999px;
            border: 1px solid #2a2a2f;
            color: var(--muted);
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .token-pill strong {
            color: var(--accent);
        }
        .list-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 4px;
            flex-wrap: wrap;
        }
        .btn-cta {
            background: var(--accent);
            color: #0a0a0a;
            border: none;
            border-radius: 999px;
            padding: 10px 14px;
            font-weight: 700;
            font-size: 13px;
            align-self: flex-start;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .btn-cta:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .btn-cta.is-secondary {
            background: #131316;
            color: var(--text);
            border: 1px solid var(--line);
        }
        .btn-cta.is-waitlist {
            background: rgba(243,90,167,0.18);
            color: var(--accent-2);
            border: 1px solid rgba(243,90,167,0.5);
        }
        .btn-cta.is-disabled {
            opacity: 0.7;
            cursor: not-allowed;
            box-shadow: none;
        }
        .rv-modal {
            position: fixed;
            inset: 0;
            display: grid;
            place-items: center;
            padding: 20px;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease;
            z-index: 40;
        }
        .rv-modal.is-open {
            opacity: 1;
            pointer-events: auto;
        }
        .rv-modal-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(4, 4, 6, 0.72);
            backdrop-filter: blur(10px);
        }
        .rv-modal-panel {
            position: relative;
            width: min(520px, calc(100% - 32px));
            margin: 0 auto;
        }
        .rv-modal-close {
            position: absolute;
            top: 14px;
            right: 14px;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 1px solid #2a2a2f;
            background: #111114;
            color: var(--text);
            display: grid;
            place-items: center;
            z-index: 2;
        }
        .modal-card {
            background: #0f0f12;
            border-radius: 16px;
            padding: 18px;
            display: grid;
            gap: 12px;
        }
        .modal-title {
            font-size: 18px;
            font-weight: 700;
        }
        .modal-meta {
            color: var(--muted);
            font-size: 13px;
        }
        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 6px;
        }
        .btn-secondary {
            background: transparent;
            color: var(--muted);
            border: 1px solid var(--line);
            border-radius: 999px;
            padding: 9px 14px;
            font-size: 13px;
        }
        .btn-primary {
            background: var(--accent);
            color: #0a0a0a;
            border: none;
            border-radius: 999px;
            padding: 9px 14px;
            font-size: 13px;
            font-weight: 700;
        }
        .btn-primary:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        .modal-note {
            background: rgba(126,252,91,0.12);
            border: 1px solid rgba(126,252,91,0.3);
            color: var(--accent);
            padding: 8px 10px;
            border-radius: 10px;
            font-size: 12px;
        }
        .modal-error {
            background: rgba(243,90,167,0.12);
            border: 1px solid rgba(243,90,167,0.4);
            color: var(--accent-2);
            padding: 8px 10px;
            border-radius: 10px;
            font-size: 12px;
        }
        .duetto-toggle {
            display: flex;
            gap: 10px;
            align-items: center;
            font-size: 13px;
            color: var(--muted);
        }
        .duetto-toggle input {
            width: 18px;
            height: 18px;
            accent-color: var(--accent);
        }
        @media (max-width: 640px) {
            .filters-panel {
                flex-direction: column;
                gap: 12px;
                padding: 14px;
            }
            .filter-group {
                width: 100%;
                min-width: 100%;
            }
            .filter-select {
                width: 100%;
                padding: 14px 16px;
                font-size: 15px;
                border-radius: 14px;
                background-position: right 14px center;
            }
        }
        /* Premium Modal Styles */
        .rv-modal .premium-modal {
            background: linear-gradient(135deg, #16161a 0%, #0d0d10 100%);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            padding: 0;
            overflow: hidden;
            border-radius: 24px;
            box-shadow: 0 24px 48px rgba(0,0,0,0.5);
        }
        .premium-modal .modal-header {
            background: rgba(126, 252, 91, 0.03);
            padding: 32px 24px 24px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            text-align: center;
        }
        .premium-modal .category-badge {
            display: inline-block;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: var(--accent);
            border: 1px solid rgba(126, 252, 91, 0.3);
            padding: 6px 14px;
            border-radius: 100px;
            margin-bottom: 16px;
            background: rgba(126, 252, 91, 0.05);
            font-weight: 700;
        }
        .premium-modal .course-title {
            font-size: 28px;
            font-weight: 800;
            color: #fff;
            margin: 0 0 12px;
            line-height: 1.1;
            letter-spacing: -0.02em;
        }
        .premium-modal .course-trainer {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            color: #a0a0aa;
            font-size: 14px;
            background: rgba(255,255,255,0.05);
            padding: 6px 16px;
            border-radius: 100px;
        }
        .premium-modal .trainer-avatar {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: var(--accent);
            color: #000;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
        }
        .premium-modal .modal-body {
            padding: 32px 24px;
            max-height: 70vh;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
        }
        .premium-modal .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 28px;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 20px;
            padding: 20px 16px;
            border: 1px solid rgba(255,255,255,0.05);
        }
        .premium-modal .info-item {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
        }
        .premium-modal .info-item i {
            font-size: 24px;
            color: var(--accent);
            display: block;
            opacity: 0.9;
            background: rgba(126, 252, 91, 0.1);
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
        }
        .premium-modal .info-item .text-group {
            display: flex;
            flex-direction: column;
        }
        .premium-modal .info-item .label {
            font-size: 10px;
            text-transform: uppercase;
            color: #666;
            letter-spacing: 0.05em;
            margin-bottom: 2px;
        }
        .premium-modal .info-item .value {
            font-size: 15px;
            font-weight: 700;
            color: #fff;
        }
        .premium-modal .token-summary {
            background: linear-gradient(90deg, rgba(126, 252, 91, 0.05) 0%, rgba(126, 252, 91, 0.02) 100%);
            border-left: 3px solid var(--accent);
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .premium-modal .token-text {
            font-size: 14px;
            color: #ccc;
        }
        .premium-modal .token-cost {
            font-size: 16px;
            font-weight: 700;
            color: var(--accent);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .premium-modal .context-info {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }
        .premium-modal .info-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 100px;
            font-size: 13px;
            font-weight: 600;
        }
        .premium-modal .status-pill {
            background: rgba(126, 252, 91, 0.08);
            color: var(--accent);
            border: 1px solid rgba(126, 252, 91, 0.2);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 11px;
            font-weight: 700;
        }
        .premium-modal .trainer-pill {
            background: rgba(255, 255, 255, 0.05);
            color: #d0d0d5;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .premium-modal .trainer-avatar-small {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--accent);
            color: #000;
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 9px;
        }

        .premium-modal .duetto-section {
            background: #1a1a1e;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 24px;
            border: 1px solid rgba(255,255,255,0.1);
        }
        .premium-modal .duetto-toggle {
            display: flex;
            align-items: center;
            gap: 16px;
            cursor: pointer;
            margin: 0;
            color: #fff;
            font-weight: 600;
            font-size: 15px;
        }
        .premium-modal .duetto-toggle input {
            width: 22px;
            height: 22px;
            accent-color: var(--accent);
        }
        .premium-modal .simple-item {
            text-align: center;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            padding: 10px 4px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 4px;
        }
        .premium-modal .simple-item .label {
            font-size: 9px;
            color: #666;
            letter-spacing: 0.05em;
            margin: 0;
        }
        .premium-modal .simple-item .value {
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            line-height: 1.2;
        }

        .premium-modal .status-message {
            padding: 14px;
            border-radius: 12px;
            font-size: 14px;
            text-align: center;
            margin-top: 20px;
            font-weight: 600;
        }
        .premium-modal .status-error {
            background: rgba(255, 87, 87, 0.1);
            color: #ff6b6b;
            border: 1px solid rgba(255, 87, 87, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .premium-modal .modal-footer {
            display: grid;
            grid-template-columns: 1fr 1.5fr;
            gap: 16px;
            padding-top: 0;
        }
        .premium-modal .btn-cancel {
            background: rgba(255, 255, 255, 0.05);
            border: none;
            color: #999;
            border-radius: 14px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            padding: 18px;
            transition: all 0.2s;
        }
        .premium-modal .btn-cancel:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }
        .premium-modal .btn-confirm {
            background: linear-gradient(135deg, var(--accent) 0%, #8fff6b 100%);
            border: none;
            color: #000;
            border-radius: 14px;
            font-weight: 800;
            font-size: 16px;
            cursor: pointer;
            padding: 18px;
            transition: all 0.2s;
            box-shadow: 0 4px 15px rgba(126, 252, 91, 0.2);
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }
        .premium-modal .btn-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(126, 252, 91, 0.3);
        }
        .premium-modal .btn-confirm:disabled {
            filter: grayscale(1);
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Mobile Optimization */
        @media (max-width: 480px) {
            .premium-modal .modal-header {
                padding: 20px 16px 12px;
            }
            .premium-modal .course-title {
                font-size: 22px;
                margin-bottom: 8px;
            }
            .premium-modal .modal-body {
                padding: 16px;
            }
            .premium-modal .context-info {
                margin-bottom: 16px;
                gap: 8px;
            }
            .premium-modal .info-pill {
                padding: 4px 10px;
                font-size: 11px;
            }
            .premium-modal .info-grid {
                grid-template-columns: repeat(3, 1fr); /* Force 3 columns */
                gap: 8px;
                padding: 0;
                background: transparent;
                border: none;
                margin-bottom: 20px;
            }
            .premium-modal .simple-item {
                background: rgba(255,255,255,0.03);
                border: 1px solid rgba(255,255,255,0.05);
                border-radius: 10px;
                padding: 8px 4px;
            }
            .premium-modal .token-summary {
                padding: 12px;
                display: flex;
                flex-direction: row; /* Keep horizontal on mobile if possible */
                justify-content: space-between;
                margin-bottom: 16px;
            }
            .premium-modal .duetto-section {
                padding: 12px;
                margin-bottom: 16px;
            }
            .premium-modal .duetto-toggle {
                font-size: 13px;
            }
            .premium-modal .btn-cancel, .premium-modal .btn-confirm {
                padding: 16px;
                font-size: 14px;
            }
        }
    </style>
@endpush

<div id="calendar-root">
    <main class="page-calendar">
        <div class="calendar-shell">
            <div class="filters-panel">
                <label class="filter-group">
                    Sede
                    <select class="filter-select" wire:model="selectedBranch">
                        <option value="">Qualsiasi</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch['id'] }}">{{ $branch['name'] }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="filter-group">
                    Corso
                    <select class="filter-select" wire:model="selectedCourse">
                        <option value="">Qualsiasi</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course['id'] }}">{{ $course['title'] }}</option>
                        @endforeach
                    </select>
                </label>
            </div>

            <div class="title-block">
                CALENDARIO <span>LEZIONI</span>
            </div>

            <div class="token-pill">
                Token disponibili <strong>{{ $availableTokens }}</strong>
            </div>

            <section class="calendar-card">
                <div class="calendar-header">
                    <div>
                        <div class="label">Seleziona il giorno desiderato</div>
                        <div class="date-title">{{ $calendar['selectedLabel'] }}</div>
                    </div>
                    <button type="button" aria-label="Modifica selezione">
                        <i class="bi bi-pencil"></i>
                    </button>
                </div>
                <div class="month-row">
                    <button type="button" aria-label="Mese precedente" wire:click="previousMonth">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <div>{{ $calendar['monthLabel'] }}</div>
                    <button type="button" aria-label="Mese successivo" wire:click="nextMonth">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
                <div class="weekday-row">
                    @foreach ($calendar['weekdays'] as $day)
                        <div>{{ $day }}</div>
                    @endforeach
                </div>
                @foreach ($calendar['weeks'] as $week)
                    <div class="week-row">
                        @foreach ($week as $day)
                            @php
                                $classes = ['day'];
                                if ($day === $calendar['selectedDay']) {
                                    $classes[] = 'selected';
                                } elseif ($day !== null && isset($calendar['specialDays'][$day])) {
                                    $classes[] = 'busy';
                                }
                                $dayTrainers = $day !== null && isset($calendar['trainersByDay'][$day]) 
                                    ? $calendar['trainersByDay'][$day] 
                                    : [];
                            @endphp
                            @if ($day === null)
                                <div></div>
                            @else
                                <div class="{{ implode(' ', $classes) }}" wire:click="selectDay({{ $day }})">
                                    <span class="day-number">{{ $day }}</span>
                                    @if (!empty($dayTrainers))
                                        <div class="trainer-dots">
                                            @foreach (array_slice($dayTrainers, 0, 3) as $trainer)
                                                <div class="trainer-dot" style="background: {{ $trainer['color'] }};" title="{{ $trainer['name'] }}"></div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </section>


            @if (!empty($lessonCardsByTrainer))
                @foreach ($lessonCardsByTrainer as $trainerGroup)
                    <div class="trainer-section">
                        <div class="trainer-header">
                            <div class="trainer-badge" style="background: {{ $trainerGroup['trainer_color'] }};">
                                {{ $this->getTrainerInitials($trainerGroup['trainer_name']) }}
                            </div>
                            <div class="trainer-name">{{ $trainerGroup['trainer_name'] }}</div>
                            <div style="margin-left: auto; color: var(--muted); font-size: 13px;">
                                {{ count($trainerGroup['courses']) }} {{ count($trainerGroup['courses']) === 1 ? 'corso' : 'corsi' }}
                            </div>
                        </div>
                        @foreach ($trainerGroup['courses'] as $course)
                            <article class="list-card course-card">
                                <div class="thumb" aria-hidden="true"></div>
                                <div class="list-body d-flex flex-column">
                                    <div class="category">{{ $course['category'] }}</div>
                                    <div class="title">{{ $course['title'] }}</div>
                                    
                                    @if (count($course['time_slots']) > 1)
                                        <div class="time-slot-selector">
                                            <label for="time-{{ $course['title'] }}" class="time-label">Seleziona orario:</label>
                                            <select class="time-select" id="time-{{ $course['title'] }}" data-course-title="{{ $course['title'] }}">
                                                @foreach ($course['time_slots'] as $index => $slot)
                                                    <option value="{{ $index }}" 
                                                            data-occurrence-id="{{ $slot['occurrence_id'] }}"
                                                            data-action="{{ $slot['action'] ?? '' }}"
                                                            data-cta="{{ $slot['cta'] }}"
                                                            data-cta-variant="{{ $slot['cta_variant'] }}"
                                                            data-cta-disabled="{{ $slot['cta_disabled'] ? '1' : '0' }}">
                                                        {{ $slot['time'] }} 
                                                        @if (!empty($slot['tags']))
                                                            - {{ implode(', ', array_slice($slot['tags'], 0, 2)) }}
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @else
                                        @php
                                            $slot = $course['time_slots'][0] ?? null;
                                        @endphp
                                        @if ($slot)
                                            <div class="single-time">
                                                <i class="bi bi-clock"></i> {{ $slot['time'] }}
                                            </div>
                                        @endif
                                    @endif

                                    @php
                                        $firstSlot = $course['time_slots'][0] ?? null;
                                        $insufficientTokens = $firstSlot && $firstSlot['action'] === 'book' && $availableTokens < 1;
                                        $ctaDisabled = $firstSlot ? ($firstSlot['cta_disabled'] || $insufficientTokens) : true;
                                        $ctaLabel = $insufficientTokens ? 'Token insufficienti' : ($firstSlot['cta'] ?? 'Non disponibile');
                                        $ctaVariant = $firstSlot['cta_variant'] ?? '';
                                        $action = $firstSlot['action'] ?? '';
                                        $occurrenceId = $firstSlot['occurrence_id'] ?? 0;
                                    @endphp

                                    <div class="list-actions">
                                        <button class="btn-cta {{ $ctaVariant }} {{ $ctaDisabled ? 'is-disabled' : '' }} js-book-course"
                                                type="button"
                                                data-course-title="{{ $course['title'] }}"
                                                data-occurrence-id="{{ $occurrenceId }}"
                                                data-action="{{ $action }}"
                                                @if ($ctaDisabled) disabled @endif>
                                            {{ $ctaLabel }}
                                        </button>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endforeach
            @else
                <article class="list-card">
                    <div class="thumb" aria-hidden="true"></div>
                    <div class="list-body d-flex flex-column">
                        <div class="category">Nessuna lezione</div>
                        <div class="title">Non ci sono lezioni disponibili</div>
                        <div class="trainer">Prova a modificare i filtri o il giorno.</div>
                    </div>
                </article>
            @endif
        </div>
    </main>


    <div class="rv-modal" data-modal="booking-confirm" aria-hidden="true">
        <div class="rv-modal-backdrop" data-modal-close></div>
        <div class="rv-modal-panel">
            <button class="rv-modal-close" type="button" data-modal-close aria-label="Chiudi modal">
                <i class="bi bi-x-lg"></i>
            </button>
            <div class="premium-modal">
                {{-- Header --}}
                <div class="modal-header">
                    <h2 class="course-title" style="font-size: {{ strlen($confirmingDetails['title'] ?? '') > 25 ? '22px' : '28px' }}">
                        {{ $confirmingDetails['title'] ?? 'Lezione' }}
                    </h2>
                </div>

                {{-- Body --}}
                <div class="modal-body">
                    {{-- Context Info (Branch & Trainer) --}}
                    <div class="context-info">
                        <div class="info-pill status-pill">
                            <i class="bi bi-geo-alt-fill"></i>
                            {{ $confirmingDetails['branch'] ?? 'SEDE' }}
                        </div>
                        <div class="info-pill trainer-pill">
                            <div class="trainer-avatar-small" style="background-color: {{ $confirmingDetails['trainer_color'] ?? '#7efc5b' }}">
                                {{ $confirmingDetails['trainer_initials'] ?? 'T' }}
                            </div>
                            {{ $confirmingDetails['trainer'] ?? 'Trainer' }}
                        </div>
                    </div>

                    <div class="info-grid context-info-grid">
                        <div class="info-item simple-item">
                            <div class="label">DATA</div>
                            <div class="value">{{ $confirmingDetails['date'] ?? '--' }}</div>
                        </div>
                        <div class="info-item simple-item">
                            <div class="label">ORA</div>
                            <div class="value">{{ $confirmingDetails['time'] ?? '--:--' }}</div>
                        </div>
                        <div class="info-item simple-item">
                            <div class="label">DURATA</div>
                            <div class="value">{{ $confirmingDetails['duration'] ?? '-- min' }}</div>
                        </div>
                    </div>

                    @php
                        $hasAction = in_array($confirmingAction, ['book', 'waitlist'], true);
                        $requiredTokens = $confirmingAction === 'book' ? 1 : 0;
                        $duettoInsufficient = $confirmDuetto && $duettoTokens !== null && $duettoTokens < 1;
                        $insufficientTokens = $confirmingAction === 'book' && ($availableTokens < 1 || $duettoInsufficient);
                        $requiredLabel = $confirmDuetto ? '2 TOKEN (1 + 1)' : '1 TOKEN';
                        $hasBlockingError = $insufficientTokens || $bookingError || !$hasAction;
                    @endphp

                    @if ($hasAction)
                        <div class="token-summary">
                            <div class="token-text">
                                Disponibili: <strong>{{ $availableTokens }}</strong>
                                @if ($confirmDuetto && $duettoTokens !== null)
                                    (+ {{ $duettoTokens }} partner)
                                @endif
                            </div>
                            <div class="token-cost">
                                -{{ $requiredLabel }}
                            </div>
                        </div>
                    @endif

                    @if ($confirmingAction === 'book' && $hasDuetto)
                        <div class="duetto-section">
                            <label class="duetto-toggle">
                                <input type="checkbox" wire:model.live="confirmDuetto">
                                <span>Prenota in duetto {{ $duettoName ? 'con ' . $duettoName : '' }}</span>
                            </label>
                        </div>
                    @endif

                    @if ($bookingError)
                        <div class="status-message status-error">
                            <i class="bi bi-exclamation-circle-fill"></i>
                            {{ $bookingError }}
                        </div>
                    @endif
                    
                    @if ($insufficientTokens && !$bookingError)
                        <div class="status-message status-error">
                            <i class="bi bi-coin"></i>
                            Token insufficienti per questa prenotazione.
                        </div>
                    @endif

                    {{-- Footer --}}
                    <div class="modal-footer">
                        <button class="btn-cancel" type="button" data-modal-close>Annulla</button>
                        <button class="btn-confirm" 
                                type="button" 
                                wire:click="confirmBooking" 
                                wire:loading.attr="disabled"
                                @if ($hasBlockingError) disabled @endif>
                            {{ $confirmingAction === 'waitlist' ? 'Entra in lista' : 'Conferma' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        (function () {
            const openModal = (name) => {
                if (!name) return;
                const modal = document.querySelector(`[data-modal="${name}"]`);
                if (!modal) return;
                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('modal-open');
            };

            const closeModal = (name, modalEl = null) => {
                const modal = modalEl || document.querySelector(`[data-modal="${name}"]`);
                if (!modal) return;
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('modal-open');
            };

            const setupGlobalModalHandlers = () => {
                if (window.__reverbiaModalHandlers) return;
                window.__reverbiaModalHandlers = true;

                // Listen to Livewire events
                document.addEventListener('livewire:init', () => {
                    Livewire.on('open-modal', (modalName) => {
                        openModal(modalName);
                    });

                    Livewire.on('close-modal', (modalName) => {
                        closeModal(modalName);
                    });
                });

                // Fallback for window events
                window.addEventListener('open-modal', (event) => {
                    const name = typeof event.detail === 'string' ? event.detail : event.detail?.name;
                    openModal(name);
                });

                window.addEventListener('close-modal', (event) => {
                    const name = typeof event.detail === 'string' ? event.detail : event.detail?.name;
                    closeModal(name);
                });

                document.addEventListener('click', (event) => {
                    const closeTrigger = event.target.closest('[data-modal-close]');
                    if (!closeTrigger) return;
                    const modal = closeTrigger.closest('[data-modal]');
                    if (modal) {
                        closeModal(null, modal);
                    }
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key !== 'Escape') return;
                    const modal = document.querySelector('[data-modal].is-open');
                    if (modal) {
                        closeModal(null, modal);
                    }
                });
            };

            const setupCourseOverlay = () => {
                const root = document.getElementById('calendar-root');
                if (!root || root.__courseOverlayInit) return;
                root.__courseOverlayInit = true;

                const overlay = root.querySelector('[data-course-overlay]');
                if (!overlay) return;

                const closeOverlay = () => {
                    overlay.classList.remove('is-open');
                    overlay.setAttribute('aria-hidden', 'true');
                    document.body.classList.remove('modal-open');
                };

                const openOverlay = (data) => {
                    overlay.classList.add('is-open');
                    overlay.setAttribute('aria-hidden', 'false');
                    document.body.classList.add('modal-open');

                    const eyebrow = overlay.querySelector('[data-course-eyebrow]');
                    const title = overlay.querySelector('[data-course-title]');
                    const trainer = overlay.querySelector('[data-course-trainer]');
                    const tags = overlay.querySelector('[data-course-tags]');
                    const cta = overlay.querySelector('[data-course-cta]');

                    if (eyebrow) eyebrow.textContent = data.category || 'Corso';
                    if (title) title.textContent = data.title || 'Reverbia';
                    if (trainer) trainer.textContent = data.trainer || 'Trainer';

                    if (tags) {
                        tags.innerHTML = '';
                        (data.tags || []).forEach((tag) => {
                            const span = document.createElement('span');
                            span.textContent = tag;
                            tags.appendChild(span);
                        });
                    }

                    if (cta) {
                        cta.textContent = data.cta || 'Prenota ora';
                        cta.className = `btn-cta ${data.ctaVariant || ''} ${data.ctaDisabled ? 'is-disabled' : ''}`.trim();
                        cta.disabled = !data.action || data.ctaDisabled;
                        cta.dataset.ctaTarget = data.ctaTarget || '';
                        cta.dataset.occurrenceId = data.occurrenceId || '';
                        cta.dataset.action = data.action || '';
                    }
                };

                root.addEventListener('click', (event) => {
                    const detailButton = event.target.closest('.js-course-detail');
                    if (!detailButton) return;

                    let tags = [];
                    if (detailButton.dataset.courseTags) {
                        try {
                            tags = JSON.parse(detailButton.dataset.courseTags);
                        } catch (e) {
                            tags = [];
                        }
                    }

                    openOverlay({
                        title: detailButton.dataset.courseTitle,
                        trainer: detailButton.dataset.courseTrainer,
                        category: detailButton.dataset.courseCategory,
                        tags,
                        cta: detailButton.dataset.courseCta,
                        ctaVariant: detailButton.dataset.courseCtaVariant,
                        ctaDisabled: detailButton.dataset.courseCtaDisabled === '1',
                        action: detailButton.dataset.courseAction,
                        ctaTarget: detailButton.dataset.courseTarget,
                        occurrenceId: detailButton.dataset.courseOccurrence,
                    });
                });

                overlay.addEventListener('click', (event) => {
                    if (event.target.closest('[data-course-close]')) {
                        closeOverlay();
                    }
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape' && overlay.classList.contains('is-open')) {
                        closeOverlay();
                    }
                });

                const ctaButton = overlay.querySelector('[data-course-cta]');
                if (ctaButton) {
                    ctaButton.addEventListener('click', () => {
                        if (ctaButton.disabled) return;
                        const occurrenceId = ctaButton.dataset.occurrenceId;
                        const action = ctaButton.dataset.action;
                        if (!occurrenceId || !action) return;
                        
                        closeOverlay();
                        
                        // Dispatch Livewire event
                        const component = window.Livewire.find(
                            document.querySelector('[wire\\\\:id]').getAttribute('wire:id')
                        );
                        if (component) {
                            component.call('openBookingModal', parseInt(occurrenceId), action);
                        }
                    });
                }
            };

            const setupTimeSlotHandlers = () => {
                const root = document.getElementById('calendar-root');
                if (!root) return;

                // Handle time slot selection changes
                root.addEventListener('change', (event) => {
                    if (!event.target.classList.contains('time-select')) return;
                    
                    const select = event.target;
                    const selectedOption = select.options[select.selectedIndex];
                    const courseTitle = select.dataset.courseTitle;
                    
                    // Find the booking button for this course
                    const card = select.closest('.course-card');
                    if (!card) return;
                    
                    const bookButton = card.querySelector('.js-book-course');
                    if (!bookButton) return;
                    
                    // Update button with selected slot data
                    const occurrenceId = selectedOption.dataset.occurrenceId;
                    const action = selectedOption.dataset.action;
                    const cta = selectedOption.dataset.cta;
                    const ctaVariant = selectedOption.dataset.ctaVariant;
                    const ctaDisabled = selectedOption.dataset.ctaDisabled === '1';
                    
                    bookButton.dataset.occurrenceId = occurrenceId;
                    bookButton.dataset.action = action;
                    bookButton.textContent = cta;
                    bookButton.className = `btn-cta ${ctaVariant} ${ctaDisabled ? 'is-disabled' : ''} js-book-course`;
                    bookButton.disabled = ctaDisabled;
                });

                // Handle booking button clicks
                root.addEventListener('click', (event) => {
                    if (!event.target.classList.contains('js-book-course')) return;
                    
                    const button = event.target;
                    if (button.disabled) return;
                    
                    const occurrenceId = parseInt(button.dataset.occurrenceId);
                    const action = button.dataset.action;
                    
                    if (!occurrenceId || !action) {
                        console.error('Missing occurrence ID or action', {occurrenceId, action});
                        return;
                    }
                    
                    // Call Livewire method
                    try {
                        const wireId = document.querySelector('[wire\\:id]')?.getAttribute('wire:id');
                        if (!wireId) {
                            console.error('Livewire component not found');
                            return;
                        }
                        
                        const component = window.Livewire?.find(wireId);
                        if (component) {
                            console.log('Calling openBookingModal', {occurrenceId, action});
                            component.call('openBookingModal', occurrenceId, action);
                        } else {
                            console.error('Livewire component instance not found');
                        }
                    } catch (error) {
                        console.error('Error calling Livewire method:', error);
                    }
                });
            };

            const init = () => {
                setupGlobalModalHandlers();
                setupCourseOverlay();
                setupTimeSlotHandlers();

            };

            document.addEventListener('DOMContentLoaded', init);
            document.addEventListener('livewire:navigated', init);
        })();
    </script>
@endpush
