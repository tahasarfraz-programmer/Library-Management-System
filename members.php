<?php require 'includes/layout.php'; admin();
$rows = $pdo->query("SELECT u.*, (SELECT COUNT(*) FROM loans l WHERE l.user_id=u.id AND l.returned IS NULL) active FROM users u WHERE u.role<>'admin' ORDER BY u.id DESC")->fetchAll(); top('Members', true); ?>
<main class="wrap"><h1>Students and teachers</h1><p class="count-line"><?= count($rows) ?> registered. People sign up themselves on the Create account page.</p>
<div class="card scroll"><table><tr><th>Name</th><th>Role</th><th>ID</th><th>Department</th><th>Class / designation</th><th>Phone</th><th>Email</th><th>Address</th><th>Books out</th></tr>
<?php foreach ($rows as $r) echo '<tr><td>'.e($r['name']).'</td><td><b class="tag">'.e($r['role']).'</b></td><td>'.e($r['id_no']).'</td><td>'.e($r['department']).'</td><td>'.e($r['level']).'</td><td>'.e($r['phone']).'</td><td>'.e($r['email']).'</td><td>'.e($r['address']).'</td><td>'.$r['active'].'</td></tr>'; ?></table></div></main>
<?php bottom();
