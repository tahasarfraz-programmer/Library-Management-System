<?php
require_once __DIR__ . '/../config.php';
function logo(){ return '<svg class="logo" viewBox="0 0 250 44" height="36" role="img" aria-label="'.APP.'"><g fill="none" stroke="currentColor" stroke-width="2.6" stroke-linejoin="round" stroke-linecap="round"><path d="M4 9c6-3 11-3 16 1v24c-5-4-10-4-16-1z"/><path d="M20 10c5-4 10-4 16-1v24c-6-3-11-3-16 1z"/></g><text x="46" y="21" class="w1">Library</text><text x="46" y="39" class="w2">Management system</text></svg>'; }
function shelf(){ $o = '<div class="shelf" aria-hidden="true">'; foreach (array_values(CATS) as $i => $c) $o .= "<span style='--i:$i;--h:".(150 + ($i*47)%120)."px;--c:{$c[1]}'></span>"; return $o . '</div>'; }
// $mode: false = public page, 'user' = any logged-in user, true = library admin only
function top($title, $mode = false, $home = false){ global $pdo; if ($mode === 'user') auth(); elseif ($mode) admin();
  $u = $_SESSION['u'] ?? null; $role = $u['role'] ?? ''; $p = basename($_SERVER['PHP_SELF']); $items = ['index.php' => 'Catalogue'];
  if ($role == 'admin') { $n = (int)$pdo->query("SELECT COUNT(*) FROM reservations WHERE status='pending'")->fetchColumn();
    $items += ['dashboard.php'=>'Dashboard','requests.php'=>'Requests'.($n ? " <b class='dot'>$n</b>" : ''),'loans.php'=>'Loans','members.php'=>'Members','books.php'=>'Books']; }
  elseif ($u) $items += ['my.php' => 'My books']; ?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($title) ?> · <?= APP ?></title>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600;9..144,800&family=Instrument+Sans:wght@400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css"><?= $GLOBALS['css'] ?? '' ?></head><body class="<?= ($home ? 'home ' : '') . ($GLOBALS['bc'] ?? '') ?>"><div class="curtain"></div>
<nav class="nav"><a href="index.php"><?= logo() ?></a><div><?php foreach ($items as $f => $l) echo "<a class='".($p==$f?'on':'')."' href='$f'>$l</a>";
if ($u) echo "<span class='me'>".e($u['name'])." · ".e($role)."</span><a class='pill' href='logout.php'>Log out</a>"; else echo "<a href='login.php'>Log in</a><a class='pill' href='register.php'>Create account</a>"; ?></div></nav>
<?php if ($m = flash()) echo '<p class="flash">'.e($m).'</p>'; }
function bottom(){ ?><footer class="foot"><?= logo() ?><span>PHP and MySQL</span></footer><script src="assets/js/app.js"></script></body></html><?php }
function fld($n, $l, $v = '', $t = 'text', $a = '', $lid = ''){ $pw = $t == 'password';
  return '<label class="f"><input name="'.$n.'" type="'.$t.'" value="'.e($v).'" placeholder=" " autocomplete="'.$a.'"><span'.($lid ? ' id="'.$lid.'"' : '').'>'.$l.'</span>'
    .($pw ? '<button type="button" class="eye" tabindex="-1">Show</button><em class="caps" hidden>Caps Lock is on</em>' : '').'<small class="msg"></small></label>'; }
