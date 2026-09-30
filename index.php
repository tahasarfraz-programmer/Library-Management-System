<?php require 'includes/layout.php';
$q = trim($_GET['q'] ?? ''); $c = $_GET['c'] ?? ''; $av = isset($_GET['a']); $pg = max(1, (int)($_GET['p'] ?? 1)); $per = 24;
$w = ['(title LIKE :q OR author LIKE :q OR isbn LIKE :q)']; $p = [':q' => "%$q%"];
if (isset(CATS[$c])) { $w[] = 'category=:c'; $p[':c'] = $c; } if ($av) $w[] = 'available>0'; $W = implode(' AND ', $w);
$n = $pdo->prepare("SELECT COUNT(*) n FROM books WHERE $W"); $n->execute($p); $total = (int)$n->fetch()['n'];
$s = $pdo->prepare("SELECT * FROM books WHERE $W ORDER BY title LIMIT $per OFFSET " . (($pg - 1) * $per)); $s->execute($p); $books = $s->fetchAll();
$all = $pdo->query('SELECT COUNT(*) t, COALESCE(SUM(available),0) a FROM books')->fetch();
$u = fn($x) => '?' . http_build_query(['q' => $q ?: null, 'c' => $c ?: null, 'a' => $av ? 1 : null] + $x);
top('Catalogue', false, true); ?>
<header class="hero"><div class="hero-in"><h1>Every book,<br>one quiet search away.</h1><p>Browse the whole collection, see what is on the shelf right now, and find your next read.</p>
<form class="search"><input name="q" value="<?= e($q) ?>" placeholder="Search by title, author or ISBN" aria-label="Search books"><button class="btn big">Search</button></form>
<div class="facts"><div><span class="count" data-to="<?= $all['t'] ?>">0</span><small>titles</small></div><div><span class="count" data-to="<?= $all['a'] ?>">0</span><small>copies on the shelf</small></div><div><span class="count" data-to="<?= count(CATS) ?>">0</span><small>categories</small></div></div></div>
<div class="spines" aria-hidden="true"><?php for ($i = 0; $i < 26; $i++) { $k = array_values(CATS)[$i % count(CATS)]; echo "<span style='--i:$i;--h:" . (120 + ($i * 53) % 130) . "px;--c:{$k[1]}'></span>"; } ?></div></header>
<section class="cat" id="catalogue"><div class="chips"><a class="<?= !$c ? 'on' : '' ?>" href="<?= $u(['c' => null]) ?>#catalogue">All</a>
<?php foreach (CATS as $k => $col) echo "<a class='".($c==$k?'on':'')."' style='--c:{$col[1]}' href='".$u(['c'=>$k])."#catalogue'>".e($k)."</a>"; ?>
<a class="tog <?= $av ? 'on' : '' ?>" href="<?= $u(['a' => $av ? null : 1]) ?>#catalogue">Available now</a></div>
<p class="count-line"><?= $total ?> book<?= $total == 1 ? '' : 's' ?> found</p>
<div class="books"><?php foreach ($books as $b): ?><button class="bk" data-id="<?= $b['id'] ?>" data-t="<?= e($b['title']) ?>" data-a="<?= e($b['author']) ?>" data-c="<?= e($b['category']) ?>" data-y="<?= yr($b['year']) ?>" data-i="<?= e($b['isbn']) ?>" data-s="<?= e($b['shelf']) ?>" data-v="<?= $b['available'] ?>" data-n="<?= $b['copies'] ?>">
<?= cover($b) ?><em class="<?= $b['available'] ? 'in' : 'out' ?>"><?= $b['available'] ? $b['available'] . ' available' : 'All on loan' ?></em></button><?php endforeach; ?></div>
<?php if (!$books) echo '<p class="empty">No books match. Clear the filters or try a shorter search.</p>'; ?>
<div class="pager"><?php for ($i = 1; $i <= ceil($total / $per); $i++) echo "<a class='".($i==$pg?'on':'')."' href='".$u(['p'=>$i])."#catalogue'>$i</a>"; ?></div></section>
<dialog id="dlg"><button class="x" aria-label="Close">×</button><div id="dc"></div></dialog>
<script>window.ME = <?= json_encode(['role' => $_SESSION['u']['role'] ?? null]) ?>;</script>
<?php bottom();
