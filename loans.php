<?php require 'includes/layout.php'; admin(); $msg = '';
if ($_POST) { try { $pdo->beginTransaction();
  if ($_POST['a'] == 'issue') { $d = issue($pdo, $_POST['book'], $_POST['member']); $msg = "Book issued. Due in $d days."; }
  else { $s = $pdo->prepare('SELECT book_id, GREATEST(DATEDIFF(CURDATE(),due),0) late FROM loans WHERE id=? AND returned IS NULL'); $s->execute([$_POST['loan']]); $l = $s->fetch(); if (!$l) throw new Exception('That loan is already closed.');
    $fine = $l['late'] * FINE_PER_DAY; $pdo->prepare('UPDATE loans SET returned=CURDATE(), fine=? WHERE id=?')->execute([$fine, $_POST['loan']]);
    $pdo->prepare('UPDATE books SET available=available+1 WHERE id=?')->execute([$l['book_id']]); $msg = $fine ? 'Returned late. Fine to collect: '.number_format($fine, 2) : 'Book returned on time.'; }
  $pdo->commit(); } catch (Exception $x) { $pdo->rollBack(); $msg = $x->getMessage(); } }
$mem = $pdo->query("SELECT id,name,id_no,role FROM users WHERE role<>'admin' ORDER BY name")->fetchAll(); $bk = $pdo->query('SELECT id,title FROM books WHERE available>0 ORDER BY title')->fetchAll();
$open = $pdo->query('SELECT l.id,b.title,m.name,m.id_no,l.due,DATEDIFF(CURDATE(),l.due) late FROM loans l JOIN books b ON b.id=l.book_id JOIN users m ON m.id=l.user_id WHERE l.returned IS NULL ORDER BY l.due')->fetchAll();
top('Loans', true); ?>
<main class="wrap"><h1>Loans</h1><?php if ($msg) echo '<p class="note">'.e($msg).'</p>'; ?>
<form method="post" class="card add"><input type="hidden" name="a" value="issue"><select name="member" required><?php foreach ($mem as $m) echo "<option value='{$m['id']}'>".e($m['name'])." ({$m['id_no']})</option>"; ?></select>
<select name="book" required><?php foreach ($bk as $b) echo "<option value='{$b['id']}'>".e($b['title'])."</option>"; ?></select><button class="btn">Issue book</button></form>
<div class="card scroll"><table><tr><th>Book</th><th>Borrower</th><th>Due</th><th>Status</th><th></th></tr>
<?php foreach ($open as $o): ?><tr><td><?= e($o['title']) ?></td><td><?= e($o['name']) ?> <small><?= e($o['id_no']) ?></small></td><td><?= $o['due'] ?></td><td><b class="tag <?= $o['late'] > 0 ? 'bad' : '' ?>"><?= $o['late'] > 0 ? $o['late'].' days late' : 'On time' ?></b></td>
<td><form method="post"><input type="hidden" name="a" value="return"><input type="hidden" name="loan" value="<?= $o['id'] ?>"><button class="btn sm">Return</button></form></td></tr><?php endforeach; if (!$open) echo '<tr><td colspan=5>No books are out. Issue one above.</td></tr>'; ?></table></div></main>
<?php bottom();
