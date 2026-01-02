<div>
    <main class="page-dashboard">
        <section class="token-card" aria-labelledby="token-balance">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="eyebrow mb-1">Lezioni rimanenti</div>
                    <div id="token-balance" class="fw-semibold">Saldo token aggiornato</div>
                </div>
                <div class="user-pill small">{{ auth()->user()->name ?? 'Ospite' }}</div>
            </div>
            <div class="donut" style="--percent: {{ max(0, min(100, (int) $tokens['percentage'])) }};" role="img" aria-label="{{ (int) $tokens['percentage'] }}% dei token ancora disponibili">
                <div class="donut-value">
                    <strong>{{ (int) $tokens['percentage'] }}%</strong>
                    <span>token disponibili</span>
                </div>
            </div>
            <div class="token-stats">
                <div class="token-stat">
                    <div class="label">Token totali</div>
                    <div class="value">{{ (int) $tokens['total'] }}</div>
                </div>
                <div class="token-stat">
                    <div class="label">Token prenotati</div>
                    <div class="value">{{ (int) $tokens['booked'] }}</div>
                </div>
                <div class="token-stat">
                    <div class="label">Token disponibili</div>
                    <div class="value">{{ (int) $tokens['available'] }}</div>
                </div>
            </div>
            <div class="dot-indicators" aria-hidden="true">
                <span></span><span></span><span class="active"></span><span></span><span></span>
            </div>
        </section>

        <section class="slider-block" data-slider>
            <div class="d-flex justify-content-between align-items-center">
                <div class="section-title">Le tue prossime lezioni prenotate</div>
            </div>
            <div class="slider-window mt-3">
                <div class="slider-track" data-track>
                    @foreach ($bookedLessons as $lesson)
                        @if (!empty($lesson['empty']))
                            <article class="slider-card">
                                <div class="title">{{ $lesson['message'] }}</div>
                                <div class="coach">{{ $lesson['hint'] }}</div>
                            </article>
                        @else
                            <article class="slider-card">
                                <span class="tag">{{ strtoupper($lesson['date']) }} - {{ $lesson['time'] }}</span>
                                <div class="title">{{ $lesson['title'] }}</div>
                                <div class="coach mb-2">Coach: {{ $lesson['coach'] }}</div>
                                <div class="coach text-success fw-semibold">{{ $lesson['status'] }}</div>
                            </article>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="slider-dots" data-dots>
                @foreach ($bookedLessons as $index => $lesson)
                    <button type="button" aria-label="Slide {{ $index + 1 }}" data-slide="{{ $index }}"></button>
                @endforeach
            </div>
        </section>

        <section class="slider-block" data-slider>
            <div class="d-flex justify-content-between align-items-center">
                <div class="section-title text-success">Corsi disponibili per la tua sede</div>
            </div>
            <div class="slider-window mt-3">
                <div class="slider-track" data-track>
                    @foreach ($availableCourses as $course)
                        <article class="slider-card">
                            <span class="tag">{{ ($course['tag'] ?? '') . ' - ' . $course['time'] }}</span>
                            <div class="title">{{ $course['title'] }}</div>
                            <div class="coach mb-2">Coach: {{ $course['coach'] }}</div>
                            <a href="#" class="cta link">{{ $course['cta'] }}</a>
                        </article>
                    @endforeach
                </div>
            </div>
            <div class="slider-dots" data-dots>
                @foreach ($availableCourses as $index => $course)
                    <button type="button" aria-label="Slide {{ $index + 1 }}" data-slide="{{ $index }}"></button>
                @endforeach
            </div>
            <a href="#" class="bottom-link">
                <span>Scopri tutti i corsi disponibili</span>
                <i class="bi bi-chevron-right"></i>
            </a>
        </section>
    </main>

</div>

@push('scripts')
    <script>
        (function () {
            const setupSliders = () => {
                document.querySelectorAll('[data-slider]').forEach((slider) => {
                    const track = slider.querySelector('[data-track]');
                    const dots = Array.from(slider.querySelectorAll('[data-slide]'));
                    if (!track || dots.length === 0) {
                        return;
                    }
                    let index = 0;
                    const setIndex = (next) => {
                        index = Math.max(0, Math.min(next, dots.length - 1));
                        track.style.setProperty('--index', index);
                        dots.forEach((dot, dotIndex) => dot.classList.toggle('active', dotIndex === index));
                    };
                    dots.forEach((dot, dotIndex) => {
                        dot.addEventListener('click', () => setIndex(dotIndex));
                    });
                    setIndex(0);
                });
            };

            document.addEventListener('DOMContentLoaded', setupSliders);
            document.addEventListener('livewire:navigated', setupSliders);
        }());
    </script>
@endpush
