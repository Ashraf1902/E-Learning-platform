{{-- Shared quiz question builder — expects: $questions (initial state collection, optional), $isEdit --}}
@php
    $initialQuestions = collect($questions ?? [])->map(function ($q) {
        if ($q instanceof \App\Models\Question) {
            $list    = $q->option_list ?? [];
            $correct = null;

            if ($q->type === 'mcq') {
                $canonical = \App\Support\AnswerChecker::canonical($q->correct_answer, $q);
                $idx       = $canonical === null ? 0 : (ord($canonical) - 97);
                $correct   = max(0, min($idx, count($list) - 1));
            } elseif ($q->type === 'true_false') {
                $correct = $q->correct_answer ?: 'true';
            } elseif ($q->type === 'fill_blank') {
                $correct = (string) $q->correct_answer;
            }

            return [
                'id'       => $q->id,
                'type'     => $q->type,
                'text'     => $q->question_text,
                'points'   => $q->points,
                'options'  => $q->type === 'mcq' ? array_values($list) : [],
                'correct'  => $correct,
                'accepted' => $q->data['accepted'] ?? [],
                'pairs'    => $q->pairs(),
                'items'    => $q->items(),
            ];
        }

        // Restored old() input after a validation error
        return [
            'id'       => null,
            'type'     => $q['type'] ?? 'mcq',
            'text'     => $q['text'] ?? '',
            'points'   => $q['points'] ?? 1,
            'options'  => array_values($q['options'] ?? []),
            'correct'  => $q['type'] === 'mcq' ? (int) ($q['correct'] ?? 0) : ($q['correct'] ?? null),
            'accepted' => array_values($q['accepted'] ?? []),
            'pairs'    => array_values($q['pairs'] ?? []),
            'items'    => array_values($q['items'] ?? []),
        ];
    })->values();
@endphp

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
    <h3 style="font-size:1rem;font-weight:700">{{ __('app.questions') }}</h3>
    <button type="button" class="btn btn-primary btn-sm" onclick="addQuestion()">+ {{ app()->getLocale() === 'ar' ? 'إضافة سؤال' : 'Add Question' }}</button>
</div>
<div id="questions-container"></div>

@push('scripts')
<script>
const isAr = {{ app()->getLocale() === 'ar' ? 'true' : 'false' }};
const TYPE_LABELS = @json(\App\Models\Question::TYPES);
const OPTION_LABELS = ['أ', 'ب', 'ج', 'د', 'هـ', 'و', 'ز', 'ح'];
const ICON_X = '<svg viewBox="0 0 24 24" width="1em" height="1em" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" style="vertical-align:-.125em"><path d="M18 6 6 18M6 6l12 12"/></svg>';
let qs = @json($initialQuestions);
if (qs.length === 0) qs.push(freshQuestion('mcq'));

function freshQuestion(type) {
    const q = { id: null, type: type, text: '', points: 1, options: [], correct: null, accepted: [], pairs: [], items: [] };
    applyTypeDefaults(q);
    return q;
}

function applyTypeDefaults(q) {
    if (q.type === 'mcq' && q.options.length < 2) { q.options = ['', '']; q.correct = 0; }
    if (q.type === 'mcq' && q.correct === null) q.correct = 0;
    if (q.type === 'true_false' && !q.correct) q.correct = 'true';
    if (q.type === 'fill_blank' && q.correct === null) q.correct = '';
    if (q.type === 'matching' && q.pairs.length < 2) q.pairs = [{left:'',right:''},{left:'',right:''}];
    if (q.type === 'ordering' && q.items.length < 2) q.items = ['', ''];
}

const container = document.getElementById('questions-container');

function render() {
    container.innerHTML = qs.map((q, i) => questionHtml(q, i)).join('');
}

function questionHtml(q, i) {
    return `
    <div class="question-block">
        ${q.id ? `<input type="hidden" name="questions[${i}][id]" value="${q.id}">` : ''}
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;gap:10px;flex-wrap:wrap">
            <div class="qnum">${isAr ? 'السؤال' : 'QUESTION'} #${i + 1}</div>
            <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
                <select name="questions[${i}][type]" data-field="type" data-i="${i}" style="width:auto;padding:6px 10px;font-size:0.82rem">
                    ${Object.entries(TYPE_LABELS).map(([value, label]) =>
                        `<option value="${value}" ${q.type === value ? 'selected' : ''}>${label}</option>`
                    ).join('')}
                </select>
                <input type="number" name="questions[${i}][points]" data-field="points" data-i="${i}" value="${q.points}" min="1" max="100"
                       title="${isAr ? 'النقاط' : 'Points'}"
                       style="width:72px;padding:6px 8px;font-size:0.82rem;text-align:center">
                <button type="button" data-action="remove-question" data-i="${i}"
                        style="background:none;border:none;color:var(--red);cursor:pointer;font-size:0.85rem">\</button>
            </div>
        </div>
        <div class="form-group">
            <label>${isAr ? 'نص السؤال' : 'Question Text'} *</label>
            <textarea name="questions[${i}][text]" data-field="text" data-i="${i}" placeholder="${isAr ? 'اكتب سؤالك هنا...' : 'Write your question...'}" required>${escapeHtml(q.text)}</textarea>
        </div>
        ${bodyHtml(q, i)}
    </div>`;
}

function bodyHtml(q, i) {
    switch (q.type) {
        case 'mcq': return mcqHtml(q, i);
        case 'true_false': return tfHtml(q, i);
        case 'fill_blank': return fillBlankHtml(q, i);
        case 'essay': return essayHtml();
        case 'matching': return matchingHtml(q, i);
        case 'ordering': return orderingHtml(q, i);
    }
    return '';
}

function mcqHtml(q, i) {
    const options = q.options.map((opt, j) => `
        <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px">
            <input type="radio" name="questions[${i}][correct]" data-field="correct" data-i="${i}" value="${j}"
                   ${parseInt(q.correct) === j ? 'checked' : ''} style="accent-color:var(--gold);width:auto" title="${isAr ? 'الإجابة الصحيحة' : 'Correct'}">
            <span style="font-weight:800;font-size:0.85rem;width:20px;color:var(--muted)">${OPTION_LABELS[j]}</span>
            <input type="text" name="questions[${i}][options][${j}]" data-field="opt" data-i="${i}" data-j="${j}" value="${escapeHtml(opt)}"
                   placeholder="${isAr ? 'الخيار ' + (j + 1) : 'Option ' + (j + 1)}" style="flex:1">
            ${q.options.length > 2 ? `<button type="button" data-action="remove-option" data-i="${i}" data-j="${j}"
                   style="background:none;border:none;color:var(--red);cursor:pointer">\</button>` : ''}
        </div>`).join('');

    return `
        <label style="font-size:0.8rem;color:var(--muted);font-weight:700">${isAr ? 'الخيارات (اختر دائرة الإجابة الصحيحة)' : 'Options (pick the correct one)'}</label>
        <div style="margin:8px 0">${options}</div>
        ${q.options.length < 8 ? `<button type="button" class="btn btn-ghost btn-sm" data-action="add-option" data-i="${i}">+ ${isAr ? 'إضافة خيار' : 'Add option'}</button>` : ''}`;
}

function tfHtml(q, i) {
    return `
        <div class="form-group" style="max-width:280px">
            <label>${isAr ? 'الإجابة الصحيحة' : 'Correct Answer'} *</label>
            <select name="questions[${i}][correct]" data-field="tf" data-i="${i}">
                <option value="true" ${q.correct === 'true' ? 'selected' : ''}>${isAr ? 'صح' : 'True'}</option>
                <option value="false" ${q.correct === 'false' ? 'selected' : ''}>${isAr ? 'خطأ' : 'False'}</option>
            </select>
        </div>`;
}

function fillBlankHtml(q, i) {
    const accepted = (q.accepted || []).map((a, j) => `
        <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px">
            <input type="text" name="questions[${i}][accepted][${j}]" data-field="acc" data-i="${i}" data-j="${j}" value="${escapeHtml(a)}"
                   placeholder="${isAr ? 'إجابة مقبولة أخرى' : 'Another accepted answer'}" style="flex:1">
            <button type="button" data-action="remove-acc" data-i="${i}" data-j="${j}"
                    style="background:none;border:none;color:var(--red);cursor:pointer">\</button>
        </div>`).join('');

    return `
        <div class="form-group">
            <label>${isAr ? 'الإجابة الصحيحة' : 'Correct Answer'} *</label>
            <input type="text" name="questions[${i}][correct]" data-field="fb" data-i="${i}" value="${escapeHtml(q.correct ?? '')}"
                   placeholder="${isAr ? 'اكتب الإجابة النموذجية' : 'Model answer'}">
            <div class="form-hint">${isAr ? 'تُقبل الإجابة بغض النظر عن المسافات والتشكيل وهمزة الألف.' : 'Matching ignores spaces, diacritics and hamza variants.'}</div>
        </div>
        <label style="font-size:0.8rem;color:var(--muted);font-weight:700">${isAr ? 'إجابات مقبولة أخرى (اختياري)' : 'Other accepted answers (optional)'}</label>
        <div style="margin:8px 0">${accepted}</div>
        <button type="button" class="btn btn-ghost btn-sm" data-action="add-acc" data-i="${i}">+ ${isAr ? 'إضافة إجابة' : 'Add answer'}</button>`;
}

function essayHtml() {
    return `<div class="alert alert-info" style="margin-bottom:0">
        ${isAr ? 'هذا السؤال سيُصحَّح يدوياً من لوحة التحكم بعد إرسال الطالب للاختبار.' : 'This question is graded manually by the admin after the student submits.'}
    </div>`;
}

function matchingHtml(q, i) {
    const pairs = q.pairs.map((p, j) => `
        <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px">
            <input type="text" name="questions[${i}][pairs][${j}][left]" data-field="pair-left" data-i="${i}" data-j="${j}" value="${escapeHtml(p.left)}"
                   placeholder="${isAr ? 'العنصر' : 'Item'} ${j + 1}" style="flex:1">
            <span style="color:var(--muted)">↔</span>
            <input type="text" name="questions[${i}][pairs][${j}][right]" data-field="pair-right" data-i="${i}" data-j="${j}" value="${escapeHtml(p.right)}"
                   placeholder="${isAr ? 'المقابل' : 'Match'}" style="flex:1">
            ${q.pairs.length > 2 ? `<button type="button" data-action="remove-pair" data-i="${i}" data-j="${j}"
                   style="background:none;border:none;color:var(--red);cursor:pointer">\</button>` : ''}
        </div>`).join('');

    return `
        <label style="font-size:0.8rem;color:var(--muted);font-weight:700">${isAr ? 'أزواج التوصيل — عمود أ ↔ عمود ب' : 'Matching pairs — column A ↔ column B'}</label>
        <div style="margin:8px 0">${pairs}</div>
        ${q.pairs.length < 8 ? `<button type="button" class="btn btn-ghost btn-sm" data-action="add-pair" data-i="${i}">+ ${isAr ? 'إضافة زوج' : 'Add pair'}</button>` : ''}`;
}

function orderingHtml(q, i) {
    const items = q.items.map((item, j) => `
        <div style="display:flex;gap:8px;align-items:center;margin-bottom:8px">
            <span style="font-weight:800;font-size:0.85rem;width:20px;color:var(--muted)">${j + 1}.</span>
            <input type="text" name="questions[${i}][items][${j}]" data-field="item" data-i="${i}" data-j="${j}" value="${escapeHtml(item)}"
                   placeholder="${isAr ? 'العنصر رقم ' + (j + 1) : 'Item ' + (j + 1)}" style="flex:1">
            ${q.items.length > 2 ? `<button type="button" data-action="remove-item" data-i="${i}" data-j="${j}"
                   style="background:none;border:none;color:var(--red);cursor:pointer">\</button>` : ''}
        </div>`).join('');

    return `
        <label style="font-size:0.8rem;color:var(--muted);font-weight:700">${isAr ? 'العناصر بالترتيب الصحيح' : 'Items in the correct order'}</label>
        <div class="form-hint" style="margin:4px 0 8px">${isAr ? 'أدخل العناصر بالترتيب الصحيح — سيخلطها النظام للطالب تلقائياً.' : 'Enter items in the correct order — students receive them shuffled.'}</div>
        <div style="margin:8px 0">${items}</div>
        ${q.items.length < 8 ? `<button type="button" class="btn btn-ghost btn-sm" data-action="add-item" data-i="${i}">+ ${isAr ? 'إضافة عنصر' : 'Add item'}</button>` : ''}`;
}

function addQuestion() { qs.push(freshQuestion('mcq')); render(); }

// --- event delegation ---
container.addEventListener('click', function (e) {
    const btn = e.target.closest('[data-action]');
    if (!btn) return;
    const i = parseInt(btn.dataset.i), j = parseInt(btn.dataset.j);
    const q = qs[i];
    if (!q) return;

    switch (btn.dataset.action) {
        case 'remove-question': qs.splice(i, 1); if (qs.length === 0) qs.push(freshQuestion('mcq')); break;
        case 'add-option':    q.options.push(''); if (q.correct === null) q.correct = 0; break;
        case 'remove-option': q.options.splice(j, 1); if (parseInt(q.correct) === j) q.correct = 0; else if (parseInt(q.correct) > j) q.correct = parseInt(q.correct) - 1; break;
        case 'add-acc':       q.accepted.push(''); break;
        case 'remove-acc':    q.accepted.splice(j, 1); break;
        case 'add-pair':      q.pairs.push({left:'',right:''}); break;
        case 'remove-pair':   q.pairs.splice(j, 1); break;
        case 'add-item':      q.items.push(''); break;
        case 'remove-item':   q.items.splice(j, 1); break;
        default: return;
    }
    render();
});

container.addEventListener('input', function (e) {
    const t = e.target;
    if (!t.dataset.field) return;
    const i = parseInt(t.dataset.i), j = parseInt(t.dataset.j);
    const q = qs[i];
    if (!q) return;

    switch (t.dataset.field) {
        case 'text':   q.text = t.value; return; // no re-render (keeps focus)
        case 'points': q.points = t.value; return;
        case 'opt':    q.options[j] = t.value; return;
        case 'acc':    q.accepted[j] = t.value; return;
        case 'fb':     q.correct = t.value; return;
        case 'pair-left':  q.pairs[j].left = t.value; return;
        case 'pair-right': q.pairs[j].right = t.value; return;
        case 'item':   q.items[j] = t.value; return;
    }
});

container.addEventListener('change', function (e) {
    const t = e.target;
    if (!t.dataset.field) return;
    const i = parseInt(t.dataset.i);
    const q = qs[i];
    if (!q) return;

    if (t.dataset.field === 'type') {
        q.type = t.value;
        applyTypeDefaults(q);
        render();
    } else if (t.dataset.field === 'correct') {
        q.correct = parseInt(t.value);
    } else if (t.dataset.field === 'tf') {
        q.correct = t.value;
    }
});

function escapeHtml(str) {
    return String(str ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

render();
</script>
@endpush
