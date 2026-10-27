@push('scripts')
<script>
document.addEventListener('click', function (e) {
    const btn = e.target.closest('.order-up, .order-down');
    if (!btn) return;

    const li = btn.closest('li');
    if (!li) return;

    if (btn.classList.contains('order-up') && li.previousElementSibling) {
        li.parentNode.insertBefore(li, li.previousElementSibling);
    } else if (btn.classList.contains('order-down') && li.nextElementSibling) {
        li.parentNode.insertBefore(li.nextElementSibling, li);
    }

    renumber(li.closest('.ordering-list'));
});

function renumber(list) {
    if (!list) return;
    list.querySelectorAll('li').forEach((li, i) => {
        const num = li.querySelector('.order-num');
        if (num) num.textContent = (i + 1) + '.';
    });
}

document.querySelectorAll('.ordering-list').forEach(renumber);
</script>
@endpush
