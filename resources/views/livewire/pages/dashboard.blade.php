@push('styles')
    <style>
        main.page-dashboard { padding: 16px 16px 90px; }
        .section-block { margin-bottom: 24px; }
        .section-title {
            font-size: 13px;
            letter-spacing: 0.08em;
            color: var(--muted);
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 14px;
        }
        
        /* Wallet Card */
        .wallet-card {
            background: linear-gradient(135deg, rgba(126,252,91,0.08) 0%, rgba(126,252,91,0.02) 100%);
            border: 1.5px solid rgba(126,252,91,0.3);
            border-radius: 24px;
            padding: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 8px 24px rgba(0,0,0,0.3);
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: var(--text);
        }
        .wallet-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.4);
            border-color: rgba(126,252,91,0.5);
        }
        .wallet-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .wallet-icon {
            width: 56px;
            height: 56px;
            background: rgba(126,252,91,0.15);
            border: 1px solid rgba(126,252,91,0.3);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: var(--accent);
        }
        .wallet-info .amount {
            font-size: 36px;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 6px;
            background: linear-gradient(135deg, var(--accent) 0%, #fff 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .wallet-info .label {
            color: var(--muted);
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 600;
        }
        .wallet-action {
            color: var(--accent);
            font-weight: 700;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Book Button */
        .book-button {
            display: block;
            background: linear-gradient(135deg, var(--accent) 0%, #8fff6b 100%);
            color: #000;
            border: none;
            border-radius: 20px;
            padding: 20px 28px;
            font-size: 18px;
            font-weight: 800;
            text-align: center;
            text-decoration: none;
            box-shadow: 0 8px 24px rgba(126,252,91,0.3);
            transition: all 0.3s ease;
            cursor: pointer;
            letter-spacing: 0.02em;
        }
        .book-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 32px rgba(126,252,91,0.4);
            background: linear-gradient(135deg, #8fff6b 0%, var(--accent) 100%);
        }
        .book-button i {
            margin-right: 8px;
            font-size: 20px;
        }

        /* Lessons Carousel */
        .lessons-carousel-container {
            position: relative;
            overflow: hidden;
        }
        .lessons-carousel {
            display: flex;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            gap: 14px;
        }
        .lesson-card {
            background: linear-gradient(150deg, #1b1b20, #0f0f12);
            border: 1px solid var(--line);
            border-radius: 20px;
            padding: 18px;
            min-height: 160px;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            gap: 10px;
            flex-shrink: 0;
            width: calc(100% - 4px);
            transition: all 0.3s ease;
        }
        .lesson-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.4);
            border-color: rgba(126,252,91,0.2);
        }
        .lesson-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 8px;
            font-size: 12px;
            color: var(--muted);
            font-weight: 600;
        }
        .lesson-date {
            flex: 1;
        }
        .lesson-title {
            font-size: 19px;
            font-weight: 700;
            line-height: 1.3;
            margin-bottom: 4px;
        }
        .lesson-meta { 
            font-size: 13px; 
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .lesson-meta i {
            font-size: 12px;
            opacity: 0.7;
        }
        .badge-status {
            background: rgba(126,252,91,0.12);
            border: 1px solid rgba(126,252,91,0.4);
            color: var(--accent);
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .empty-state {
            background: rgba(126,252,91,0.05);
            border: 1.5px dashed rgba(126,252,91,0.3);
            text-align: center;
            padding: 32px 20px;
            justify-content: center;
        }
        .empty-state .lesson-title {
            margin-bottom: 8px;
        }
        
        /* Available Lessons Carousel */
        .available-carousel-container {
            position: relative;
            overflow: hidden;
        }
        .available-carousel {
            display: flex;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            gap: 14px;
        }
        .available-lesson-card {
            background: linear-gradient(150deg, #1b1b20, #0f0f12);
            border: 1px solid var(--line);
            border-radius: 20px;
            padding: 18px;
            box-shadow: var(--shadow);
            display: flex;
            flex-direction: column;
            gap: 10px;
            transition: all 0.3s ease;
            flex-shrink: 0;
            width: calc(100% - 4px);
        }
        .available-lesson-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.4);
            border-color: rgba(126,252,91,0.3);
        }
        .badge-spots {
            background: rgba(91,150,252,0.12);
            border: 1px solid rgba(91,150,252,0.4);
            color: #5b96fc;
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .btn-book-lesson {
            margin-top: auto;
            background: linear-gradient(135deg, var(--accent) 0%, #8fff6b 100%);
            color: #000;
            border: none;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            font-weight: 700;
            text-align: center;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(126,252,91,0.2);
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }
        .btn-book-lesson:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(126,252,91,0.3);
            background: linear-gradient(135deg, #8fff6b 0%, var(--accent) 100%);
            color: #000;
        }
        .btn-book-lesson i {
            font-size: 16px;
        }

        /* Membership Status */
        .membership-status-card {
            background: linear-gradient(150deg, #14141a, #0f0f12);
            border: 1px solid var(--line);
            border-radius: 20px;
            padding: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            box-shadow: var(--shadow);
        }
        .membership-status-card.expiring {
            border-color: rgba(252, 91, 91, 0.45);
            background: linear-gradient(150deg, rgba(252, 91, 91, 0.08), rgba(15, 15, 18, 0.9));
        }
        .membership-status-left {
            display: flex;
            gap: 14px;
            align-items: center;
        }
        .membership-icon {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display: grid;
            place-items: center;
            border: 1px solid rgba(126, 252, 91, 0.35);
            background: rgba(126, 252, 91, 0.12);
            color: var(--accent);
            font-size: 22px;
        }
        .membership-status-card.expiring .membership-icon {
            border-color: rgba(252, 91, 91, 0.45);
            background: rgba(252, 91, 91, 0.12);
            color: #fc5b5b;
        }
        .membership-info h4 {
            margin: 0 0 6px;
            font-size: 16px;
            font-weight: 700;
        }
        .membership-info p {
            margin: 0;
            color: var(--muted);
            font-size: 12px;
        }
        .membership-meta {
            text-align: right;
            font-size: 12px;
            color: var(--muted);
        }
        .membership-meta strong {
            display: block;
            color: var(--text);
            font-size: 16px;
            margin-bottom: 4px;
        }
        .btn-renew {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px 14px;
            border-radius: 999px;
            background: linear-gradient(135deg, var(--accent) 0%, #8fff6b 100%);
            color: #000;
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            margin-top: 10px;
        }
        
        /* Carousel Navigation */
        .carousel-dots {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 16px;
        }
        .carousel-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(126,252,91,0.2);
            border: 1px solid rgba(126,252,91,0.3);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .carousel-dot.active {
            background: var(--accent);
            width: 24px;
            border-radius: 4px;
        }
        .carousel-dot:hover {
            background: rgba(126,252,91,0.4);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .wallet-icon {
                width: 48px;
                height: 48px;
                font-size: 24px;
            }
            .wallet-info .amount {
                font-size: 32px;
            }
            .book-button {
                font-size: 16px;
                padding: 18px 24px;
            }
        }
        @media (max-width: 640px) {
            .membership-status-card {
                flex-direction: column;
                align-items: stretch;
                text-align: left;
            }
            .membership-status-left {
                align-items: flex-start;
            }
            .membership-meta {
                text-align: left;
            }
            .btn-renew {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
@endpush

<div>
    <main class="page-dashboard">
        {{-- Wallet Section --}}
        <section class="section-block">
            <div class="section-title">La tua situazione</div>
            <a href="{{ route('pricing') }}" class="wallet-card" wire:navigate>
                <div class="wallet-left">
                    <div class="wallet-icon">
                        <i class="bi bi-wallet2"></i>
                    </div>
                    <div class="wallet-info">
                        <div class="amount">{{ (int) $walletSummary['available'] }}</div>
                        <div class="label">{{ $walletSummary['label'] }}</div>
                    </div>
                </div>
                <div class="wallet-action">
                    Acquista <i class="bi bi-arrow-right"></i>
                </div>
            </a>
        </section>

        {{-- Book Button --}}
        <section class="section-block">
            <a href="{{ route('calendar') }}" class="book-button" wire:navigate>
                <i class="bi bi-calendar-plus"></i>
                Prenota Lezione
            </a>
        </section>

        {{-- Upcoming Lessons --}}
        <section class="section-block">
            <div class="section-title">Prossime lezioni</div>
            @if (!empty($bookedLessons))
                <div class="lessons-carousel-container">
                    <div class="lessons-carousel" id="lessonsCarousel">
                        @foreach ($bookedLessons as $lesson)
                            <article class="lesson-card">
                                <div class="lesson-top">
                                    <span class="lesson-date">{{ strtoupper($lesson['date']) }} â€¢ {{ $lesson['time'] }}</span>
                                    <span class="badge-status">{{ $lesson['status'] }}</span>
                                </div>
                                <div class="lesson-title">{{ $lesson['title'] }}</div>
                                <div class="lesson-meta">
                                    <i class="bi bi-person"></i>
                                    {{ $lesson['coach'] }}
                                </div>
                                <div class="lesson-meta">
                                    <i class="bi bi-geo-alt"></i>
                                    {{ $lesson['room'] }}
                                </div>
                            </article>
                        @endforeach
                    </div>
                    @if (count($bookedLessons) > 1)
                        <div class="carousel-dots" id="carouselDots"></div>
                    @endif
                </div>
            @else
                <article class="lesson-card empty-state">
                    <div class="lesson-title">Nessuna lezione prenotata</div>
                    <div class="lesson-meta" style="justify-content: center;">
                        <i class="bi bi-calendar-x"></i>
                        Prenota la tua prima lezione dal calendario
                    </div>
                </article>
            @endif
        </section>

        {{-- Available Lessons --}}
        <section class="section-block">
            <div class="section-title">Lezioni Disponibili</div>
            @if (!empty($availableLessons))
                <div class="available-carousel-container">
                    <div class="available-carousel" id="availableCarousel">
                        @foreach ($availableLessons as $lesson)
                            <article class="available-lesson-card">
                                <div class="lesson-top">
                                    <span class="lesson-date">{{ strtoupper($lesson['date']) }} ƒ?½ {{ $lesson['time'] }}</span>
                                    @if ($lesson['spots_left'] !== null)
                                        <span class="badge-spots">{{ $lesson['spots_left'] }} posti</span>
                                    @endif
                                </div>
                                <div class="lesson-title">{{ $lesson['title'] }}</div>
                                <div class="lesson-meta">
                                    <i class="bi bi-person"></i>
                                    {{ $lesson['coach'] }}
                                </div>
                                <div class="lesson-meta">
                                    <i class="bi bi-geo-alt"></i>
                                    {{ $lesson['room'] }}
                                </div>
                                <a href="{{ route('calendar') }}?date={{ $lesson['full_date'] }}&book={{ $lesson['occurrence_id'] }}"
                                   class="btn-book-lesson"
                                   wire:navigate>
                                    <i class="bi bi-calendar-check"></i>
                                    Prenota
                                </a>
                            </article>
                        @endforeach
                    </div>
                    @if (count($availableLessons) > 1)
                        <div class="carousel-dots" id="availableCarouselDots"></div>
                    @endif
                </div>
            @else
                <article class="lesson-card empty-state">
                    <div class="lesson-title">Nessuna lezione disponibile</div>
                    <div class="lesson-meta" style="justify-content: center;">
                        <i class="bi bi-calendar-x"></i>
                        Al momento non ci sono lezioni disponibili
                    </div>
                </article>
            @endif
        </section>

        {{-- Membership Status --}}
        <section class="section-block">
            <div class="section-title">Stato membership</div>
            <div class="membership-status-card {{ $membershipStatus['renew_recommended'] ? 'expiring' : '' }}">
                <div class="membership-status-left">
                    <div class="membership-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div class="membership-info">
                        <h4>{{ $membershipStatus['label'] }}</h4>
                        <p>{{ $membershipStatus['message'] }}</p>
                    </div>
                </div>
                <div class="membership-meta">
                    @if (!empty($membershipStatus['expires_at']))
                        <strong>{{ $membershipStatus['days_left'] }} giorni</strong>
                        Scadenza {{ $membershipStatus['expires_at']->format('d/m/Y') }}
                    @else
                        <strong>—</strong>
                        Nessuna scadenza
                    @endif
                    @if ($membershipStatus['renew_recommended'])
                        <a class="btn-renew" href="{{ route('membership') }}" wire:navigate>
                            Rinnova ora
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    @endif
                </div>
            </div>
        </section>
    </main>
</div>

@push('scripts')
<script>
    (function() {
        function initCarousel() {
            const carousel = document.getElementById('lessonsCarousel');
            const dotsContainer = document.getElementById('carouselDots');
            
            if (!carousel) return;
            if (carousel.dataset.initialized) return;
            carousel.dataset.initialized = "true";
            
            const cards = carousel.querySelectorAll('.lesson-card');
            const totalCards = cards.length;
            
            if (totalCards <= 1) return;
            
            let currentIndex = 0;
            let startX = 0;
            let isDragging = false;
            
            // Create dots
            // Check if dots already exist (in case of partial update? unlikely but safe)
            if (dotsContainer.innerHTML === '') {
                for (let i = 0; i < totalCards; i++) {
                    const dot = document.createElement('div');
                    dot.className = 'carousel-dot' + (i === 0 ? ' active' : '');
                    dot.addEventListener('click', () => goToSlide(i));
                    dotsContainer.appendChild(dot);
                }
            }
            
            const dots = dotsContainer.querySelectorAll('.carousel-dot');
            
            function updateCarousel() {
                const offset = -currentIndex * 100;
                carousel.style.transform = `translateX(${offset}%)`;
                
                dots.forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentIndex);
                });
            }
            
            function goToSlide(index) {
                currentIndex = Math.max(0, Math.min(index, totalCards - 1));
                updateCarousel();
            }
            
            function nextSlide() {
                if (currentIndex < totalCards - 1) {
                    currentIndex++;
                    updateCarousel();
                }
            }
            
            function prevSlide() {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateCarousel();
                }
            }
            
            // Touch events for swipe
            carousel.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
                isDragging = true;
            });
            
            carousel.addEventListener('touchmove', (e) => {
                if (!isDragging) return;
            });
            
            carousel.addEventListener('touchend', (e) => {
                if (!isDragging) return;
                isDragging = false;
                
                const endX = e.changedTouches[0].clientX;
                const diff = startX - endX;
                
                if (Math.abs(diff) > 50) {
                    if (diff > 0) {
                        nextSlide();
                    } else {
                        prevSlide();
                    }
                }
            });
            
            // Mouse events for desktop drag
            carousel.addEventListener('mousedown', (e) => {
                startX = e.clientX;
                isDragging = true;
                carousel.style.cursor = 'grabbing';
            });
            
            carousel.addEventListener('mousemove', (e) => {
                if (!isDragging) return;
                e.preventDefault();
            });
            
            carousel.addEventListener('mouseup', (e) => {
                if (!isDragging) return;
                isDragging = false;
                carousel.style.cursor = 'grab';
                
                const endX = e.clientX;
                const diff = startX - endX;
                
                if (Math.abs(diff) > 50) {
                    if (diff > 0) {
                        nextSlide();
                    } else {
                        prevSlide();
                    }
                }
            });
            
            carousel.addEventListener('mouseleave', () => {
                if (isDragging) {
                    isDragging = false;
                    carousel.style.cursor = 'grab';
                }
            });
            
            // Auto-advance carousel every 5 seconds
            const intervalId = setInterval(() => {
                // Check if carousel still exists in DOM
                if (!document.getElementById('lessonsCarousel')) {
                    clearInterval(intervalId);
                    return;
                }
                if (currentIndex < totalCards - 1) {
                    nextSlide();
                } else {
                    goToSlide(0);
                }
            }, 5000);
        }

        document.addEventListener('DOMContentLoaded', initCarousel);
        document.addEventListener('livewire:navigated', initCarousel);
    })();
</script>
<script>
    (function() {
        function initAvailableCarousel() {
            const carousel = document.getElementById('availableCarousel');
            const dotsContainer = document.getElementById('availableCarouselDots');

            if (!carousel) return;
            if (carousel.dataset.initialized) return;
            carousel.dataset.initialized = "true";

            const cards = carousel.querySelectorAll('.available-lesson-card');
            const totalCards = cards.length;

            if (totalCards <= 1) return;

            let currentIndex = 0;
            let startX = 0;
            let isDragging = false;

            if (dotsContainer && dotsContainer.innerHTML === '') {
                for (let i = 0; i < totalCards; i++) {
                    const dot = document.createElement('div');
                    dot.className = 'carousel-dot' + (i === 0 ? ' active' : '');
                    dot.addEventListener('click', () => goToSlide(i));
                    dotsContainer.appendChild(dot);
                }
            }

            const dots = dotsContainer ? dotsContainer.querySelectorAll('.carousel-dot') : [];

            function updateCarousel() {
                const offset = -currentIndex * 100;
                carousel.style.transform = `translateX(${offset}%)`;

                dots.forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentIndex);
                });
            }

            function goToSlide(index) {
                currentIndex = Math.max(0, Math.min(index, totalCards - 1));
                updateCarousel();
            }

            function nextSlide() {
                if (currentIndex < totalCards - 1) {
                    currentIndex++;
                    updateCarousel();
                }
            }

            function prevSlide() {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateCarousel();
                }
            }

            carousel.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
                isDragging = true;
            });

            carousel.addEventListener('touchmove', () => {
                if (!isDragging) return;
            });

            carousel.addEventListener('touchend', (e) => {
                if (!isDragging) return;
                isDragging = false;

                const endX = e.changedTouches[0].clientX;
                const diff = startX - endX;

                if (Math.abs(diff) > 50) {
                    if (diff > 0) {
                        nextSlide();
                    } else {
                        prevSlide();
                    }
                }
            });

            carousel.addEventListener('mousedown', (e) => {
                startX = e.clientX;
                isDragging = true;
                carousel.style.cursor = 'grabbing';
            });

            carousel.addEventListener('mousemove', (e) => {
                if (!isDragging) return;
                e.preventDefault();
            });

            carousel.addEventListener('mouseup', (e) => {
                if (!isDragging) return;
                isDragging = false;
                carousel.style.cursor = 'grab';

                const endX = e.clientX;
                const diff = startX - endX;

                if (Math.abs(diff) > 50) {
                    if (diff > 0) {
                        nextSlide();
                    } else {
                        prevSlide();
                    }
                }
            });

            carousel.addEventListener('mouseleave', () => {
                if (isDragging) {
                    isDragging = false;
                    carousel.style.cursor = 'grab';
                }
            });

            const intervalId = setInterval(() => {
                if (!document.getElementById('availableCarousel')) {
                    clearInterval(intervalId);
                    return;
                }
                if (currentIndex < totalCards - 1) {
                    nextSlide();
                } else {
                    goToSlide(0);
                }
            }, 6000);
        }

        document.addEventListener('DOMContentLoaded', initAvailableCarousel);
        document.addEventListener('livewire:navigated', initAvailableCarousel);
    })();
</script>
@endpush

