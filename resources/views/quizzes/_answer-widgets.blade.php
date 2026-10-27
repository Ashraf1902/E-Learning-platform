{{-- Answer input widgets for one question — expects $question --}}
@php
    $labels  = \App\Models\Question::OPTION_LABELS;
    $name    = "answers[{$question->id}]";
    $shuffle = fn (array $items) => collect($items)->shuffle()->values()->all();
@endphp

@switch($question->type)
    @case('mcq')
        <div style="display:flex;flex-direction:column;gap:8px">
            @foreach(($question->option_list ?? []) as $idx => $text)
            <label class="answer-choice" style="display:flex;align-items:center;gap:10px;cursor:pointer;padding:10px 14px;border-radius:10px;border:1px solid var(--border);transition:border 0.2s">
                <input type="radio" name="{{ $name }}" value="{{ chr(97 + $idx) }}" style="accent-color:var(--gold);width:auto" required>
                <span style="font-size:0.88rem">
                    <strong style="color:var(--gold)">{{ $labels[$idx] ?? strtoupper(chr(97 + $idx)) }}</strong>
                    &nbsp; {{ $text }}
                </span>
            </label>
            @endforeach
        </div>
        @break

    @case('true_false')
        <div style="display:flex;gap:10px;flex-wrap:wrap">
            @foreach(['true' => 'صح', 'false' => 'خطأ'] as $value => $label)
            <label class="answer-choice" style="flex:1;min-width:140px;display:flex;align-items:center;justify-content:center;gap:10px;cursor:pointer;padding:14px;border-radius:10px;border:1px solid var(--border);transition:border 0.2s;font-weight:700">
                <input type="radio" name="{{ $name }}" value="{{ $value }}" style="accent-color:var(--gold);width:auto" required>
                <span>
                    @if($value === 'true')<x-icon name="check" />@else<x-icon name="x" />@endif
                    {{ $label }}
                </span>
            </label>
            @endforeach
        </div>
        @break

    @case('fill_blank')
        <input type="text" name="{{ $name }}" required autocomplete="off"
               placeholder="{{ app()->getLocale() === 'ar' ? 'اكتب إجابتك هنا...' : 'Type your answer...' }}"
               style="width:100%;padding:12px 14px;border-radius:10px;border:1px solid var(--border);background:var(--surface2);color:var(--text)">
        @break

    @case('essay')
        <textarea name="{{ $name }}" rows="5"
                  placeholder="{{ app()->getLocale() === 'ar' ? 'اكتب إجابتك بالتفصيل...' : 'Write your answer in detail...'}}"
                  style="width:100%;padding:12px 14px;border-radius:10px;border:1px solid var(--border);background:var(--surface2);color:var(--text);resize:vertical"></textarea>
        <div class="form-hint" style="margin-top:6px"><x-icon name="clipboard" /> {{ app()->getLocale() === 'ar' ? 'سيتم تصحيح هذا السؤال يدوياً وسيظهر نتيجته لاحقاً.' : 'This question is graded manually; the result appears later.' }}</div>
        @break

    @case('matching')
        @php $rights = $shuffle(collect($question->pairs())->pluck('right')->all()); @endphp
        <div style="display:flex;flex-direction:column;gap:10px">
            @foreach($question->pairs() as $idx => $pair)
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                <span style="flex:1;min-width:140px;font-weight:600;font-size:0.9rem">{{ $idx + 1 }}. {{ $pair['left'] }}</span>
                <span style="color:var(--muted)">↔</span>
                <select name="{{ $name }}[{{ $idx }}]" required style="flex:1;min-width:160px;padding:10px 12px;border-radius:10px;border:1px solid var(--border);background:var(--surface2);color:var(--text)">
                    <option value="">— {{ app()->getLocale() === 'ar' ? 'اختر المقابل' : 'Pick a match' }} —</option>
                    @foreach($rights as $right)
                    <option value="{{ $right }}">{{ $right }}</option>
                    @endforeach
                </select>
            </div>
            @endforeach
        </div>
        @break

    @case('ordering')
        @php $items = $shuffle($question->items()); @endphp
        <ul class="ordering-list" style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px">
            @foreach($items as $item)
            <li style="display:flex;gap:10px;align-items:center;padding:10px 14px;border-radius:10px;border:1px solid var(--border);background:var(--surface2)">
                <input type="hidden" name="{{ $name }}[]" value="{{ $item }}">
                <span class="order-num" style="font-weight:800;font-family:monospace;color:var(--gold);font-size:0.85rem;min-width:22px"></span>
                <span style="flex:1;font-size:0.9rem">{{ $item }}</span>
                <button type="button" class="order-up" style="background:none;border:1px solid var(--border);border-radius:8px;color:var(--text);cursor:pointer;padding:4px 10px">↑</button>
                <button type="button" class="order-down" style="background:none;border:1px solid var(--border);border-radius:8px;color:var(--text);cursor:pointer;padding:4px 10px">↓</button>
            </li>
            @endforeach
        </ul>
        <div class="form-hint" style="margin-top:6px">{{ app()->getLocale() === 'ar' ? 'رتب العناصر باستخدام الأسهم ↑ ↓' : 'Arrange items using the ↑ ↓ arrows' }}</div>
        @break
@endswitch

