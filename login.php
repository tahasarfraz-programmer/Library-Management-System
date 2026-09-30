<?php require 'includes/layout.php';
// Exactly one library admin exists; it is created here if missing. Registration can only make students and teachers.
if (!$pdo->query("SELECT COUNT(*) c FROM users WHERE role='admin'")->fetch()['c']) $pdo->prepare("INSERT INTO users(role,name,email,password,phone,id_no,department,level,address) VALUES('admin',?,?,?,'-','ADMIN','Library','Head Librarian','-')")->execute(['Head Librarian','admin@library.local',password_hash('admin123', PASSWORD_DEFAULT)]);
$err = '';
if ($_POST) { $s = $pdo->prepare('SELECT * FROM users WHERE email=?'); $s->execute([$_POST['email']]); $u = $s->fetch();
  if ($u && password_verify($_POST['password'], $u['password'])) { $_SESSION['u'] = $u; header('Location: ' . ($u['role'] == 'admin' ? 'dashboard.php' : 'index.php')); exit; } $err = 'Email or password is wrong. Try again.'; }
$GLOBALS['css'] = '<link rel="stylesheet" href="assets/css/auth.css">'; $GLOBALS['bc'] = 'auth-page';
$books = [['Dune','Science Fiction'],['Hamlet','Poetry & Drama'],['Sapiens','History'],['Middlemarch','Classics'],['Piranesi','Fantasy'],['Cosmos','Science']];
top('Log in'); ?>
<div class="ax"><section class="scene" style="--mx:0;--my:0"><a class="home" href="index.php"><?= logo() ?></a>
<div class="copy"><h2>Welcome back to <span class="rot" data-m='["the reading room.","your next great read.","every shelf, live."]'>the reading room.</span></h2></div>
<div class="stack" aria-hidden="true"><?php foreach ($books as $i => [$t, $c]) echo "<div class='bk3' style='--i:$i;--c:".CATS[$c][1].";--w:".(230 - ($i * 17) % 60).";--o:".(($i * 23) % 40).";--r:".(($i % 3) - 1).".5deg'>".e($t)."</div>"; ?></div>
<p class="fine">200 books are waiting on the shelves.</p></section>
<main class="pane"><form id="login" method="post" class="ac <?= $err ? 'shake' : '' ?>"><a class="back" href="index.php">Back to catalogue</a><h1>Welcome back</h1><p class="sub">Log in to reserve books and see what you have borrowed.</p>
<?php if ($err) echo '<p class="err" role="alert">'.e($err).'</p>'; ?>
<?= fld('email', 'Email address', $_POST['email'] ?? '', 'email', 'email') ?><?= fld('password', 'Password', '', 'password', 'current-password') ?>
<label class="chk"><input type="checkbox" id="rem" checked> Remember my email on this device</label>
<button class="go"><span>Log in</span><i class="spin"></i></button>
<p class="alt">New student or teacher? <a href="register.php">Create an account</a></p><p class="alt tiny">The librarian logs in here too and lands on the dashboard.</p></form></main></div>
<script src="assets/js/auth.js"></script>
<?php bottom();
