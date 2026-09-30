const $ = (s, r = document) => r.querySelector(s), $$ = (s, r = document) => [...r.querySelectorAll(s)];
// Scene follows the pointer (books and library card tilt in 3D)
const sc = $('.scene'); sc && addEventListener('pointermove', e => { sc.style.setProperty('--mx', e.clientX / innerWidth - .5); sc.style.setProperty('--my', e.clientY / innerHeight - .5); });
// Show/hide password and Caps Lock warning
$$('.eye').forEach(b => b.onclick = () => { const i = b.parentElement.querySelector('input'); i.type = i.type === 'password' ? 'text' : 'password'; b.textContent = i.type === 'password' ? 'Show' : 'Hide'; });
$$('input[type=password]').forEach(i => { const w = i.parentElement.querySelector('.caps'); ['keydown', 'keyup'].forEach(ev => i.addEventListener(ev, e => w && (w.hidden = !e.getModifierState('CapsLock')))); });
// Rotating headline
const rot = $('.rot'); if (rot) { const m = JSON.parse(rot.dataset.m); let k = 0; setInterval(() => { rot.classList.add('out'); setTimeout(() => { k = (k + 1) % m.length; rot.textContent = m[k]; rot.classList.remove('out'); }, 350); }, 3200); }
// Login: remember email, busy button
const lf = $('#login'); if (lf) { lf.email.value = lf.email.value || localStorage.lm_email || '';
  lf.onsubmit = () => { $('.go', lf).classList.add('busy'); $('#rem').checked ? localStorage.lm_email = lf.email.value : localStorage.removeItem('lm_email'); }; }
// Register: three-step wizard with inline validation and a live library card
const rf = $('#reg'); if (rf) {
  let step = 0; const slides = $$('.slide', rf), dots = $$('.dot', rf), n = slides.length - 1;
  const show = (to, back) => { slides[step].classList.remove('on'); step = to; slides[to].classList.add('on'); slides[to].classList.toggle('back', !!back);
    dots.forEach((d, i) => d.classList.toggle('done', i <= to)); $('.fill', rf).style.width = to / n * 100 + '%';
    $('.prev', rf).hidden = to === 0; $('.next', rf).hidden = to === n; $('.submit', rf).hidden = to !== n; $('input:not([type=radio])', slides[to])?.focus(); };
  const rules = { name: v => v.trim().length >= 2 || 'Enter your full name.', email: v => /^\S+@\S+\.\S+$/.test(v) || 'Enter a valid email address.', id_no: v => v.trim() || 'Enter your ID.',
    phone: v => v.replace(/\D/g, '').length >= 7 || 'Enter a phone number with at least 7 digits.', department: v => v.trim() || 'Enter your department.', level: v => v.trim() || 'This field is required.',
    address: v => v.trim().length >= 5 || 'Enter your full address.', password: v => v.length >= 8 || 'Use at least 8 characters.', confirm: v => v === rf.password.value || 'The two passwords do not match.' };
  const check = el => { const r = rules[el.name]; if (!r) return true; const ok = r(el.value), f = el.closest('.f'); f.classList.toggle('bad', ok !== true); f.querySelector('.msg').textContent = ok === true ? '' : ok; return ok === true; };
  const valid = i => $$('input', slides[i]).map(check).every(Boolean);
  $$('.f input', rf).forEach(i => { i.addEventListener('blur', () => i.value && check(i)); i.addEventListener('input', () => i.closest('.f').classList.remove('bad')); });
  $('.next', rf).onclick = () => valid(step) && show(step + 1); $('.prev', rf).onclick = () => show(step - 1, true);
  rf.onkeydown = e => { if (e.key === 'Enter' && step < n) { e.preventDefault(); $('.next', rf).click(); } };
  rf.onsubmit = e => { const ok = $('#agree').checked; $('.agree-msg').hidden = ok; if (!valid(step) || !ok) return e.preventDefault(); $('.submit', rf).classList.add('busy'); };
  // Live library card
  const card = $('.lcard'), bind = { name: '.c-name', id_no: '.c-id', department: '.c-dep', level: '.c-lv' }, def = { name: 'Your name', id_no: 'ID pending', department: 'Department', level: 'Class or year' };
  const fill = t => { const s = bind[t.name]; if (!s) return; const el = $(s, card); el.textContent = t.value.trim() || def[t.name]; el.classList.remove('pop'); void el.offsetWidth; el.classList.add('pop'); };
  rf.addEventListener('input', e => fill(e.target));
  const role = r => { const t = r.value === 'teacher'; card.dataset.role = r.value; $('.c-role', card).textContent = t ? 'Teacher' : 'Student'; $('#idl').textContent = t ? 'Employee ID' : 'Student ID';
    $('#lv').textContent = def.level = t ? 'Designation' : 'Class or year'; if (!rf.level.value) $('.c-lv', card).textContent = def.level; };
  $$('[name=role]', rf).forEach(r => r.onchange = () => role(r)); role($('[name=role]:checked', rf)); $$('.f input', rf).forEach(fill);
  // Password strength
  const pw = rf.password, mt = $('.meter', rf), W = ['Too short', 'Weak', 'Okay', 'Good', 'Strong'];
  pw.oninput = () => { const p = pw.value, s = [p.length >= 8, /[a-z]/.test(p) && /[A-Z]/.test(p), /\d/.test(p), /[^A-Za-z0-9]/.test(p)].filter(Boolean).length;
    mt.dataset.s = p ? s : 0; $$('i', mt).forEach((b, i) => b.classList.toggle('on', i < s)); $('small', mt).textContent = p ? W[s] : 'Use 8+ characters, upper and lower case, a number and a symbol'; };
}
