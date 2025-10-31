document.addEventListener('input', (e)=>{
  if (e.target.matches('input[type=number][name=qty]')) {
    const max = +e.target.getAttribute('max') || 99;
    if (+e.target.value < 1) e.target.value = 1;
    if (+e.target.value > max) e.target.value = max;
  }
});

document.addEventListener('DOMContentLoaded', () => {
  const reel = document.getElementById('reel');
  if (!reel) return;
  let dir = 1;
  const step = 260;
  function tick(){
    const max = reel.scrollWidth - reel.clientWidth;
    if (reel.scrollLeft >= max - 4) dir = -1;
    if (reel.scrollLeft <= 0) dir = 1;
    reel.scrollBy({left: step*dir, behavior: 'smooth'});
  }
  const timer = setInterval(tick, 2400);
  reel.addEventListener('mouseenter', ()=>clearInterval(timer), {once:true});

  document.querySelectorAll('[data-reel-left]').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      const id = btn.getAttribute('data-reel-left');
      const el = document.getElementById(id);
      el && el.scrollBy({left: -step, behavior: 'smooth'});
    });
  });
  document.querySelectorAll('[data-reel-right]').forEach(btn=>{
    btn.addEventListener('click', ()=>{
      const id = btn.getAttribute('data-reel-right');
      const el = document.getElementById(id);
      el && el.scrollBy({left: step, behavior: 'smooth'});
    });
  });
});

document.addEventListener('DOMContentLoaded', () => {
  const feed = document.getElementById('liveFeed');
  const track = document.getElementById('liveTrack');
  const modal = document.getElementById('gameModal');
  const body = document.getElementById('modalBody');
  if (!feed || !track || !modal) return;

  function pause(){ feed.classList.add('paused'); }
  function resume(){ feed.classList.remove('paused'); }

  feed.addEventListener('mouseenter', pause);
  feed.addEventListener('mouseleave', resume);

  feed.addEventListener('click', async (e) => {
    const img = e.target.closest('.tile');
    if (!img) return;
    pause();
    body.innerHTML = 'Загрузка…';
    try {
      const r = await fetch('rec_details.php?id='+encodeURIComponent(img.dataset.id), {cache:'no-store'});
      body.innerHTML = await r.text();
    } catch(e) {
      body.innerHTML = '<p class="notice err">Не удалось загрузить данные.</p>';
    }
    if (typeof modal.showModal === 'function') modal.showModal();
  });

  document.getElementById('modalClose')?.addEventListener('click', () => {
    resume();
  });
});
