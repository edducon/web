function pow(x, n) {
    let r = 1;
    for (let i = 0; i < n; i++) r *= x;
    return r;
}

function gcd(a, b) {
    a = a | 0; b = b | 0;
    while (b !== 0) { const t = b; b = a % b; a = t; }
    return a;
}

function minDigit(x) {
    x = BigInt(x);
    if (x === 0n) return 0n;
    let min = 9n;
    while (x > 0n) {
        const d = x % 10n;
        if (d < min) min = d;
        if (min === 0n) break;
        x /= 10n;
    }
    return min;
}

function pluralizeRecords(n) {
    n = Math.abs(n) | 0;
    const m10 = n % 10, m100 = n % 100;
    const one = (m10 === 1) && (m100 !== 11);
    const few = (m10 >= 2 && m10 <= 4) && !(m100 >= 12 && m100 <= 14);

    const word = one ? 'запись' : few ? 'записи' : 'записей';
    const verb = one ? 'была найдена' : few ? 'были найдены' : 'было найдено';

    return `В результате выполнения запроса ${verb} ${n} ${word}`;
}

function fibb(n) {
    n = n | 0;
    let a = 0n, b = 1n;
    for (let i = 0; i < n; i++) { const c = a + b; a = b; b = c; }
    return a;
}

document.getElementById('pow-run').addEventListener('click', () => {
    const x = Number(document.getElementById('pow-x').value);
    const n = Number(document.getElementById('pow-n').value);
    document.getElementById('pow-out').textContent = String(pow(x, n));
});

document.getElementById('gcd-run').addEventListener('click', () => {
    const a = Number(document.getElementById('gcd-a').value);
    const b = Number(document.getElementById('gcd-b').value);
    document.getElementById('gcd-out').textContent = String(gcd(a, b));
});

document.getElementById('md-run').addEventListener('click', () => {
    const x = document.getElementById('md-x').value;
    document.getElementById('md-out').textContent = minDigit(x).toString();
});

document.getElementById('pl-run').addEventListener('click', () => {
    const n = Number(document.getElementById('pl-n').value);
    document.getElementById('pl-out').textContent = pluralizeRecords(n);
});

document.getElementById('fb-run').addEventListener('click', () => {
    const n = Number(document.getElementById('fb-n').value);
    document.getElementById('fb-out').textContent = fibb(n).toString();
});

document.getElementById('run-all').addEventListener('click', (e) => {
    e.preventDefault();
    document.getElementById('pow-run').click();
    document.getElementById('gcd-run').click();
    document.getElementById('md-run').click();
    document.getElementById('pl-run').click();
    document.getElementById('fb-run').click();
});
