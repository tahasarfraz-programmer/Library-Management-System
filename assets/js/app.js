document.addEventListener('click', e => { const a = e.target.closest('a[href]');
  if (!a || a.target || a.origin !== location.origin || a.pathname === location.pathname) return;
  e.preventDefault(); document.body.classList.add('leaving'); setTimeout(() => location.href = a.href, 350); });
document.querySelectorAll('.count').forEach(el => { const to = +el.dataset.to, t0 = performance.now();
  const f = t => { const p = Math.min((t - t0) / 1200, 1); el.textContent = Math.round(to * (1 - (1 - p) ** 3)); if (p < 1) requestAnimationFrame(f); }; requestAnimationFrame(f); });
const dlg = document.getElementById('dlg');
if (dlg) { document.querySelectorAll('.bk').forEach(b => b.onclick = () => { const d = b.dataset, ok = +d.v > 0, R = (window.ME || {}).role,
      act = !ok ? '' : (R === 'student' || R === 'teacher') ? `<form method="post" action="reserve.php"><input type="hidden" name="book" value="${d.id}"><button class="btn big">Reserve this book</button></form>` : R === 'admin' ? '<small>Issue books from the Loans page.</small>' : '<a class="btn big" href="login.php">Log in to reserve</a>';
    dlg.querySelector('#dc').innerHTML = b.querySelector('.cover').outerHTML + `<div><h2>${d.t}</h2><p class="by">by ${d.a}</p><p>${d.t} is a ${d.c.toLowerCase()} title by ${d.a}, first published in ${d.y}. Find it on shelf ${d.s}.</p>
      <dl><dt>Category</dt><dd>${d.c}</dd><dt>First published</dt><dd>${d.y}</dd><dt>ISBN</dt><dd>${d.i}</dd><dt>Shelf</dt><dd>${d.s}</dd><dt>Copies</dt><dd>${d.v} of ${d.n} available</dd></dl>
      <span class="tag ${ok ? '' : 'bad'}">${ok ? 'Available to borrow' : 'All copies on loan'}</span> ${act}</div>`; dlg.showModal(); });
  dlg.querySelector('.x').onclick = () => dlg.close(); dlg.onclick = e => e.target === dlg && dlg.close(); }
