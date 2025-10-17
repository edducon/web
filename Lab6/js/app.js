function pow(x, n) {
  if (!Number.isFinite(x)) throw new Error('x — число');
  if (!Number.isInteger(n) || n < 1) throw new Error('n — натуральное (>=1)');
  let result = 1;
  for (let i = 0; i < n; i++) result *= x;
  return result;
}
function gcd(a, b) {
  a = Math.abs(Math.trunc(a)); b = Math.abs(Math.trunc(b));
  while (b !== 0) { const t = b; b = a % b; a = t; } return a;
}
function minDigit(x) {
  try { x = BigInt(x); } catch { throw new Error('x — целое неотрицательное'); }
  if (x < 0n) throw new Error('x — неотрицательное');
  if (x === 0n) return 0n;
  let min = 9n;
  while (x > 0n) { const d = x % 10n; if (d < min) min = d; if (min === 0n) break; x /= 10n; }
  return min;
}
function pluralizeRecords(n) {
  n = Math.trunc(Math.abs(n));
  const mod10 = n % 10, mod100 = n % 100;
  const one = (mod10 === 1) && (mod100 !== 11);
  const few = (mod10 >= 2 && mod10 <= 4) && !(mod100 >= 12 && mod100 <= 14);
  const word = one ? 'запись' : few ? 'записи' : 'записей';
  return `В результате выполнения запроса было найдено ${n} ${word}`;
}
function fibb(n) {
  n = Math.trunc(n);
  if (n < 0 || n > 1000) throw new Error('n от 0 до 1000');
  let a = 0n, b = 1n; for (let i = 0; i < n; i++) { [a, b] = [b, a + b]; } return a;
}
document.getElementById('pow-run').addEventListener('click', () => {
  const x = Number(document.getElementById('pow-x').value);
  const n = Number(document.getElementById('pow-n').value);
  try { document.getElementById('pow-out').textContent = String(pow(x,n)); }
  catch(e){ document.getElementById('pow-out').textContent = e.message; }
});
document.getElementById('gcd-run').addEventListener('click', () => {
  const a = Number(document.getElementById('gcd-a').value);
  const b = Number(document.getElementById('gcd-b').value);
  try { document.getElementById('gcd-out').textContent = String(gcd(a,b)); }
  catch(e){ document.getElementById('gcd-out').textContent = e.message; }
});
document.getElementById('md-run').addEventListener('click', () => {
  const x = document.getElementById('md-x').value;
  try { document.getElementById('md-out').textContent = minDigit(x).toString(); }
  catch(e){ document.getElementById('md-out').textContent = e.message; }
});
document.getElementById('pl-run').addEventListener('click', () => {
  const n = Number(document.getElementById('pl-n').value);
  try { document.getElementById('pl-out').textContent = pluralizeRecords(n); }
  catch(e){ document.getElementById('pl-out').textContent = e.message; }
});
document.getElementById('fb-run').addEventListener('click', () => {
  const n = Number(document.getElementById('fb-n').value);
  try { document.getElementById('fb-out').textContent = fibb(n).toString(); }
  catch(e){ document.getElementById('fb-out').textContent = e.message; }
});
document.getElementById('run-all').addEventListener('click', (e) => {
  e.preventDefault();
  document.getElementById('pow-run').click();
  document.getElementById('gcd-run').click();
  document.getElementById('md-run').click();
  document.getElementById('pl-run').click();
  document.getElementById('fb-run').click();
});
