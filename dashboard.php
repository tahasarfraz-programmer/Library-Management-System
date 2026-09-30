<?php require 'includes/layout.php'; top('Dashboard', true);
$q = fn($sql) => (int)$pdo->query($sql)->fetch()['v'];
$stats = ['Titles' => $q('SELECT COUNT(*) v FROM books'), 'Copies on shelf' => $q('SELECT COALESCE(SUM(available),0) v FROM books'), 'Pending requests' => $q("SELECT COUNT(*) v FROM reservations WHERE status='pending'"), 'Books on loan' => $q('SELECT COUNT(*) v FROM loans WHERE returned IS NULL'), 'Overdue' => $q('SELECT COUNT(*) v FROM loans WHERE returned IS NULL AND due<CURDATE()')];
$cats = $pdo->query('SELECT category, COUNT(*) n FROM books GROUP BY category ORDER BY n DESC')->fetchAll(); $max = max(array_column($cats, 'n'));
$od = $pdo->query('SELECT b.title, m.name, DATEDIFF(CURDATE(), l.due) late FROM loans l JOIN books b ON b.id=l.book_id JOIN users m ON m.id=l.user_id WHERE l.returned IS NULL AND l.due<CURDATE() ORDER BY late DESC LIMIT 6')->fetchAll();
$rec = $pdo->query('SELECT b.title, m.name, l.issued, l.returned FROM loans l JOIN books b ON b.id=l.book_id JOIN users m ON m.id=l.user_id ORDER BY l.id DESC LIMIT 6')->fetchAll(); ?>
<main class="wrap"><h1>Good to see you, <?= e(explode(' ', $_SESSION['u']['name'])[0]) ?></h1>
<div class="stats"><?php foreach ($stats as $k => $v) echo "<div class='stat ".($k=='Overdue'&&$v?'warn':'')."'><span class='count' data-to='$v'>$v</span><small>$k</small></div>"; ?></div>
<div class="two"><div class="card"><h3>Books by category</h3><?php foreach ($cats as $c): ?><div class="bar"><span><?= e($c['category']) ?></span><div><i style="width:<?= $c['n'] / $max * 100 ?>%;background:<?= CATS[$c['category']][1] ?>"></i></div><b><?= $c['n'] ?></b></div><?php endforeach; ?></div>
<div><div class="card"><h3>Overdue</h3><?php foreach ($od as $r) echo '<p class="row">'.e($r['title']).'<small>'.e($r['name']).'</small><b class="tag bad">'.$r['late'].' days late</b></p>'; if (!$od) echo '<p>Nothing is overdue.</p>'; ?></div>
<div class="card"><h3>Recent activity</h3><?php foreach ($rec as $r) echo '<p class="row">'.e($r['title']).'<small>'.e($r['name']).'</small><b class="tag">'.($r['returned'] ? 'Returned' : 'Issued').'</b></p>'; ?></div></div></div></main>
<?php bottom();
