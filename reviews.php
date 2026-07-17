<?php
session_start();
require_once '../config.php';
include 'header.php';

/* ==========================
   Toggle review visibility
========================== */
if (isset($_GET['toggle'])) {
    $rid = (int)$_GET['toggle'];
    $conn->query("UPDATE product_reviews SET is_hidden = 1 - is_hidden WHERE id=$rid");
    header("Location: reviews.php");
    exit;
}

/* ==========================
   Fetch reviews (NEWEST FIRST)
========================== */
$sql = "
SELECT 
    r.id AS review_id,
    r.product_id,
    r.comment,
    r.created_at,
    r.is_hidden,
    p.name AS product_name,
    COALESCE(u.name,'退会ユーザー') AS user_name
FROM product_reviews r
LEFT JOIN products p ON r.product_id = p.id
LEFT JOIN users u ON r.user_id = u.id
ORDER BY r.created_at DESC, r.id DESC
";
$reviews = $conn->query($sql);
?>

<div class="admin-container">

<h1>💬 レビュー管理</h1>
<p class="subtext">新しいレビューが上に表示されます</p>

<table class="review-table">
<thead>
<tr>
<th>ID</th>
<th>商品ID</th>
<th>商品名</th>
<th>ユーザー</th>
<th>コメント</th>
<th>状態</th>
<th>操作</th>
</tr>
</thead>

<tbody>
<?php while ($r = $reviews->fetch_assoc()): 

    /* New = within 24 hours */
    $isNew = (time() - strtotime($r['created_at'])) < 86400;
?>
<tr class="<?= $isNew ? 'new-row' : '' ?>">
<td>#<?= $r['review_id'] ?></td>
<td><?= $r['product_id'] ?></td>
<td><?= htmlspecialchars($r['product_name']) ?></td>
<td><?= htmlspecialchars($r['user_name']) ?></td>

<td class="comment-cell">
<?= nl2br(htmlspecialchars($r['comment'])) ?>
<div class="date"><?= date('Y-m-d H:i', strtotime($r['created_at'])) ?></div>
</td>

<td>
<span class="status <?= $r['is_hidden'] ? 'hidden' : 'visible' ?>">
<?= $r['is_hidden'] ? '非表示' : '表示中' ?>
</span>
</td>

<td>
<a class="btn <?= $r['is_hidden'] ? 'show' : 'hide' ?>"
href="reviews.php?toggle=<?= $r['review_id'] ?>">
<?= $r['is_hidden'] ? '表示する' : '非表示にする' ?>
</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>

</div>

<style>
.admin-container{
    max-width:1300px;
    margin:30px auto;
    padding:0 20px;
}
.subtext{
    color:#666;
    margin-bottom:14px;
}

.review-table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
    box-shadow:0 8px 24px rgba(0,0,0,.08);
    border-radius:12px;
    overflow:hidden;
}

.review-table th{
    background:#111;
    color:#fff;
    padding:12px;
    font-size:14px;
}

.review-table td{
    padding:12px;
    border-bottom:1px solid #eee;
    vertical-align:top;
    font-size:14px;
}

.comment-cell{
    max-width:380px;
}

.comment-cell .date{
    margin-top:6px;
    font-size:12px;
    color:#888;
}

.status{
    padding:4px 10px;
    border-radius:14px;
    font-size:12px;
    font-weight:600;
}
.status.visible{background:#d4f4e3;color:#0b6b4b}
.status.hidden{background:#f8d7da;color:#842029}

.btn{
    padding:6px 12px;
    border-radius:6px;
    text-decoration:none;
    font-size:13px;
    color:#fff;
}
.btn.hide{background:#dc3545}
.btn.show{background:#1f6feb}

.new-row{
    background:#f4f8ff;
}
</style>

</body>
</html>
