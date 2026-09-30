<?php
session_start();
const APP = 'Library Management system';
const FINE_PER_DAY = 0.50, LOAN_DAYS = 14;
$pdo = new PDO('mysql:host=localhost;dbname=library_db;charset=utf8mb4', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
const CATS = ['Classics'=>['#7a1f3d','#c2456b'],'Fantasy'=>['#1f5f4a','#3fae86'],'Science Fiction'=>['#1d3c8f','#4d7cff'],'Mystery & Thriller'=>['#2b2b3d','#6b5b95'],
  'History'=>['#8a5a17','#d99a3a'],'Science'=>['#0f6f7a','#37c1cf'],'Philosophy'=>['#5a2a83','#a066d6'],'Biography'=>['#a13d1f','#e8763f'],'Poetry & Drama'=>['#6b7a1a','#b5c93a'],'Young Adult'=>['#a3157a','#f05fc0']];
function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES); }
function yr($y){ return $y < 0 ? abs($y) . ' BC' : $y; }
function auth(){ if (empty($_SESSION['u'])) { header('Location: login.php'); exit; } }
function cover($b){ [$a, $c] = CATS[$b['category']] ?? ['#333', '#666']; return '<div class="cover" style="--a:'.$a.';--b:'.$c.'"><i></i><b>'.e($b['title']).'</b><span>'.e($b['author']).'</span></div>'; }
const MAX_ACTIVE = ['student' => 3, 'teacher' => 5];
function days($r){ return $r == 'teacher' ? 30 : LOAN_DAYS; }
function admin(){ auth(); if ($_SESSION['u']['role'] != 'admin') { header('Location: my.php'); exit; } }
function flash($m = null){ if ($m !== null) { $_SESSION['f'] = $m; return ''; } $x = $_SESSION['f'] ?? ''; unset($_SESSION['f']); return $x; }
// Issue a book to a user. Call inside a transaction; the loan is saved to the library record.
function issue($pdo, $book, $user){
  $s = $pdo->prepare('SELECT available FROM books WHERE id=? FOR UPDATE'); $s->execute([$book]);
  if (($s->fetch()['available'] ?? 0) < 1) throw new Exception('No copies of that book are on the shelf.');
  $r = $pdo->prepare('SELECT role FROM users WHERE id=?'); $r->execute([$user]); $d = days($r->fetch()['role'] ?? 'student');
  $pdo->prepare("INSERT INTO loans(book_id,user_id,issued,due) VALUES(?,?,CURDATE(),DATE_ADD(CURDATE(),INTERVAL $d DAY))")->execute([$book, $user]);
  $pdo->prepare('UPDATE books SET available=available-1 WHERE id=?')->execute([$book]); return $d; }
