<div>
    <main class="page-calendar">
        <div class="filter-row mt-1">
            <i class="bi bi-geo-alt"></i>
            <div>
                <div>Filtra per Sede</div>
                <div class="small text-secondary">(preimpostato su sede cliente)</div>
            </div>
            <i class="bi bi-caret-right-fill ms-auto"></i>
        </div>

        <div class="title-block">
            CALENDARIO <span>LEZIONI</span>
        </div>

        <div class="trainer-row">
            <div class="section-title mb-2">Scegli il trainer</div>
            <div class="trainer-chips">
                @foreach ($trainers as $trainer)
                    <a class="trainer-chip" href="#{{ $trainer['id'] }}">
                        {{ $trainer['name'] }}
                        <span>{{ $trainer['specialty'] }}</span>
                    </a>
                @endforeach
            </div>
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
                        @endphp
                        @if ($day === null)
                            <div></div>
                        @else
                            <div class="{{ implode(' ', $classes) }}" wire:click="selectDay({{ $day }})">{{ $day }}</div>
                        @endif
                    @endforeach
                </div>
            @endforeach
        </section>

        <div class="filters-inline">
            <span>Filtra per orario</span>
            <span>Filtra per orario</span>
        </div>

        @foreach ($lessonCards as $card)
            <article class="list-card" id="{{ $card['id'] }}">
                <div class="thumb" aria-hidden="true"></div>
                <div class="list-body d-flex flex-column">
                    <div class="category">{{ $card['category'] }}</div>
                    <div class="trainer">Trainer: {{ $card['trainer'] }}</div>
                    <div class="title">{{ $card['title'] }}</div>
                    <div class="list-tags">
                        @foreach ($card['tags'] as $tag)
                            <span>{{ $tag }}</span>
                        @endforeach
                    </div>
                    <button class="btn-cta" type="button">{{ $card['cta'] }}</button>
                </div>
            </article>
        @endforeach
    </main>

</div>
