<?php require 'includes/layout.php';
$err = ''; $f = ['role'=>'student','name'=>'','email'=>'','phone'=>'','id_no'=>'','department'=>'','level'=>'','address'=>''];
if ($_POST) { foreach ($f as $k => $_) $f[$k] = trim($_POST[$k] ?? ''); $pw = $_POST['password'] ?? '';
  if (!in_array($f['role'], ['student', 'teacher'])) $err = 'Choose student or teacher.'; // the admin role can never be registered here
  elseif (strlen($f['name']) < 2 || !$f['id_no'] || !$f['department'] || !$f['level'] || !$f['phone'] || !$f['address']) $err = 'Please fill in every field so the library keeps a complete record.';
  elseif (!filter_var($f['email'], FILTER_VALIDATE_EMAIL)) $err = 'Enter a valid email address.';
  elseif (strlen($pw) < 8) $err = 'Use at least 8 characters for your password.';
  elseif ($pw !== ($_POST['confirm'] ?? '')) $err = 'The two passwords do not match.';
  else { $x = $pdo->prepare('SELECT 1 FROM users WHERE email=? OR id_no=?'); $x->execute([$f['email'], $f['id_no']]);
    if ($x->fetch()) $err = 'An account with this email or ID already exists. Log in instead.';
    else { $pdo->prepare('INSERT INTO users(role,name,email,password,phone,id_no,department,level,address) VALUES(?,?,?,?,?,?,?,?,?)')->execute([$f['role'],$f['name'],$f['email'],password_hash($pw, PASSWORD_DEFAULT),$f['phone'],$f['id_no'],$f['department'],$f['level'],$f['address']]);
      $s = $pdo->prepare('SELECT * FROM users WHERE id=?'); $s->execute([$pdo->lastInsertId()]); $_SESSION['u'] = $s->fetch();
      flash('Welcome! Your account is ready. Find a book and press Reserve.'); header('Location: index.php'); exit; } } }
$GLOBALS['css'] = '<link rel="stylesheet" href="assets/css/auth.css">'; $GLOBALS['bc'] = 'auth-page';
top('Create account'); ?>
<div class="ax"><section class="scene" style="--mx:0;--my:0"><a class="home" href="index.php"><?= logo() ?></a>
<div class="copy"><h2>Your library card, <span class="rot" data-m='["ready in three steps.","always in your pocket.","one tap from any book."]'>ready in three steps.</span></h2><p>Watch it fill in as you type.</p></div>
<div class="lcard" data-role="student"><div class="top"><div class="chip"></div><span class="c-role">Student</span></div><b class="c-name">Your name</b>
<div class="c-meta"><span class="c-id">ID pending</span><span class="c-dep">Department</span><span class="c-lv">Class or year</span></div><div class="bars"></div></div>
<p class="fine">Students borrow 3 books for 14 days. Teachers borrow 5 for 30.</p></section>
<main class="pane"><form id="reg" method="post" class="ac" novalidate><a class="back" href="index.php">Back to catalogue</a><h1>Create your account</h1><p class="sub">Three quick steps. Already registered? <a href="login.php">Log in</a></p>
<?php if ($err) echo '<p class="err" role="alert">'.e($err).'</p>'; ?>
<div class="prog"><div class="track"><i class="fill"></i></div><div class="dots"><span class="dot done">Who you are</span><span class="dot">Your details</span><span class="dot">Secure it</span></div></div>
<div class="slides"><div class="slide on"><div class="roles2">
<label><input type="radio" name="role" value="student" <?= $f['role'] == 'student' ? 'checked' : '' ?>><span><svg viewBox="0 0 24 24"><path d="M2 9l10-5 10 5-10 5zM6 12v5c3 2 9 2 12 0v-5"/></svg>Student</span></label>
<label><input type="radio" name="role" value="teacher" <?= $f['role'] == 'teacher' ? 'checked' : '' ?>><span><svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8M12 16v4"/></svg>Teacher</span></label></div>
<?= fld('name', 'Full name', $f['name'], 'text', 'name') ?><?= fld('email', 'Email address', $f['email'], 'email', 'email') ?></div>
<div class="slide"><?= fld('id_no', $f['role'] == 'teacher' ? 'Employee ID' : 'Student ID', $f['id_no'], 'text', 'off', 'idl') ?><?= fld('phone', 'Phone number', $f['phone'], 'tel', 'tel') ?>
<?= fld('department', 'Department', $f['department']) ?><?= fld('level', $f['role'] == 'teacher' ? 'Designation' : 'Class or year', $f['level'], 'text', 'off', 'lv') ?><?= fld('address', 'Home address', $f['address'], 'text', 'street-address') ?></div>
<div class="slide"><?= fld('password', 'Password', '', 'password', 'new-password') ?>
<div class="meter" data-s="0"><div><i></i><i></i><i></i><i></i></div><small>Use 8+ characters, upper and lower case, a number and a symbol</small></div>
<?= fld('confirm', 'Confirm password', '', 'password', 'new-password') ?><label class="chk"><input type="checkbox" id="agree"> I agree to the library rules and privacy policy</label><p class="msg agree-msg" hidden>Please agree to continue.</p></div></div>
<div class="btns"><button type="button" class="ghostb prev" hidden>Back</button><button type="button" class="go next"><span>Continue</span></button><button class="go submit" hidden><span>Create account</span><i class="spin"></i></button></div></form></main></div>
<script src="assets/js/auth.js"></script>
<?php bottom();
