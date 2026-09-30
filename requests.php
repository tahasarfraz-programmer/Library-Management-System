<?php require 'includes/layout.php'; admin(); $msg = '';
if ($_POST) { try { $pdo->beginTransaction(); $s = $pdo->prepare("SELECT * FROM reservations WHERE id=? AND status='pending' FOR UPDATE"); $s->execute([$_POST['id']]); $r = $s->fetch();
  if (!$r) throw new Exception('That request was already handled.');
  if ($_POST['a'] == 'approve') { $d = issue($pdo, $r['book_id'], $r['user_id']); $st = 'approved'; $msg = "Approved and issued. Due in $d days. Saved to the library record."; } else { $st = 'rejected'; $msg = 'Request rejected.'; }
  $pdo->prepare('UPDATE reservations SET status=?, decided=NOW() WHERE id=?')->execute([$st, $r['id']]); $pdo->commit(); } catch (Exception $x) { $pdo->rollBack(); $msg = $x->getMessage(); } }
$rows = $pdo->query("SELECT r.id,r.requested,b.title,b.author,b.category,b.shelf,b.available,u.name,u.role,u.id_no,u.department,u.level,u.phone,u.email,u.address FROM reservations r JOIN books b ON b.id=r.book_id JOIN users u ON u.id=r.user_id WHERE r.status='pending' ORDER BY r.id")->fetchAll();
$done = $pdo->query("SELECT r.status,r.decided,b.title,u.name FROM reservations r JOIN books b ON b.id=r.book_id JOIN users u ON u.id=r.user_id WHERE r.status<>'pending' ORDER BY r.decided DESC LIMIT 8")->fetchAll();
top('Requests', true); ?>
<main class="wrap"><h1>Booking requests</h1><?php if ($msg) echo '<p class="note">'.e($msg).'</p>'; ?>
<?php foreach ($rows as $r): ?><div class="card req"><div class="mini"><?= cover($r) ?></div><div><h3><?= e($r['title']) ?></h3><small>Shelf <?= e($r['shelf']) ?> · <?= $r['available'] ?> on the shelf · requested <?= $r['requested'] ?></small>
<dl><dt>Requested by</dt><dd><?= e($r['name']) ?> <b class="tag"><?= e($r['role']) ?></b></dd><dt><?= $r['role'] == 'teacher' ? 'Employee ID' : 'Student ID' ?></dt><dd><?= e($r['id_no']) ?></dd><dt>Department</dt><dd><?= e($r['department']) ?></dd><dt><?= $r['role'] == 'teacher' ? 'Designation' : 'Class or year' ?></dt><dd><?= e($r['level']) ?></dd><dt>Phone</dt><dd><?= e($r['phone']) ?></dd><dt>Email</dt><dd><?= e($r['email']) ?></dd><dt>Address</dt><dd><?= e($r['address']) ?></dd></dl>
<form method="post" class="add"><input type="hidden" name="id" value="<?= $r['id'] ?>"><button class="btn" name="a" value="approve">Approve and issue book</button><button class="btn ghost" name="a" value="reject">Reject</button></form></div></div><?php endforeach;
if (!$rows) echo '<p class="empty card">No pending requests. New reservations from students and teachers appear here.</p>'; ?>
<div class="card"><h3>Recently decided</h3><?php foreach ($done as $d) echo '<p class="row">'.e($d['title']).'<small>'.e($d['name']).'</small><b class="tag '.($d['status']=='approved' ? '' : 'bad').'">'.e($d['status']).'</b></p>'; ?></div></main>
<?php bottom();
