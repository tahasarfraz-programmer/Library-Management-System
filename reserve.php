<?php require 'config.php'; auth(); $u = $_SESSION['u'];
if ($u['role'] == 'admin') { flash('The librarian issues books from the Loans page.'); header('Location: dashboard.php'); exit; }
$id = (int)($_POST['book'] ?? 0); $s = $pdo->prepare('SELECT * FROM books WHERE id=?'); $s->execute([$id]); $book = $s->fetch();
$n = $pdo->prepare("SELECT (SELECT COUNT(*) FROM reservations WHERE user_id=? AND status='pending') + (SELECT COUNT(*) FROM loans WHERE user_id=? AND returned IS NULL) n"); $n->execute([$u['id'], $u['id']]);
$d = $pdo->prepare("SELECT 1 FROM reservations WHERE user_id=? AND book_id=? AND status='pending' UNION SELECT 1 FROM loans WHERE user_id=? AND book_id=? AND returned IS NULL"); $d->execute([$u['id'], $id, $u['id'], $id]);
if (!$book) $m = 'That book was not found.';
elseif ($book['available'] < 1) $m = 'All copies are on loan right now. Try again later.';
elseif ($d->fetch()) $m = 'You already have this book reserved or borrowed.';
elseif ($n->fetch()['n'] >= MAX_ACTIVE[$u['role']]) $m = 'You reached your limit of ' . MAX_ACTIVE[$u['role']] . ' books. Return one or cancel a reservation first.';
else { $pdo->prepare('INSERT INTO reservations(user_id,book_id) VALUES(?,?)')->execute([$u['id'], $id]); $m = 'Reserved "' . $book['title'] . '". The librarian will confirm it soon.'; }
flash($m); header('Location: my.php');
