<?php
// profile.php — user profile edit (Japanese UI, NO ?? operators)
session_start();
require_once __DIR__ . '/config.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

// Fetch user from DB
$stmt = $conn->prepare("SELECT id, name, email, address FROM users WHERE id = ?");
if (!$stmt) {
    die("DBエラー: " . htmlspecialchars($conn->error));
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();

if (!$res || $res->num_rows === 0) {
    $stmt->close();
    die("ユーザーが見つかりません。");
}

$user = $res->fetch_assoc();
$stmt->close();

// Safe fallback values for old PHP (no ??)
$u_name    = isset($user['name']) ? $user['name'] : '';
$u_email   = isset($user['email']) ? $user['email'] : '';
$u_address = isset($user['address']) ? $user['address'] : '';
$u_postal  = isset($user['postal_code']) ? $user['postal_code'] : '';
$u_phone   = isset($user['phone']) ? $user['phone'] : '';

// Flash message
$flash = '';
if (isset($_SESSION['flash_msg'])) {
    $flash = $_SESSION['flash_msg'];
    unset($_SESSION['flash_msg']);
}
?>
<!doctype html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>プロフィール編集 - ゴーバイナウ</title>

<style>
/* ✅ FIX: keep everything inside card */
*{
    box-sizing:border-box;
}

/* =========================
   Menu Style Background
========================= */
body{
    margin:0;
    min-height:100vh;
    background:#070A12;
    position:relative;
    overflow-x:hidden;
    color:#fff;
    font-family: Meiryo, Arial, sans-serif;
}

/* Moving glow blobs */
body::before,
body::after{
    content:"";
    position:fixed;
    width:520px;
    height:520px;
    border-radius:50%;
    filter:blur(120px);
    opacity:0.55;
    z-index:-2;
    animation: blobMove 10s ease-in-out infinite alternate;
}

/* Blue glow */
body::before{
    top:-120px;
    left:-160px;
    background:radial-gradient(circle, rgba(80,120,255,0.9), transparent 60%);
}

/* Purple glow */
body::after{
    bottom:-160px;
    right:-200px;
    background:radial-gradient(circle, rgba(170,90,255,0.9), transparent 60%);
    animation-duration:12s;
}

@keyframes blobMove{
    0%   { transform: translate(0px,0px) scale(1); }
    50%  { transform: translate(120px,80px) scale(1.1); }
    100% { transform: translate(-60px,120px) scale(0.95); }
}

/* Noise overlay */
.bg-noise{
    position:fixed;
    inset:0;
    pointer-events:none;
    z-index:-1;
    opacity:0.06;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='180' height='180'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='180' height='180' filter='url(%23n)' opacity='.35'/%3E%3C/svg%3E");
}

/* =========================
   Layout
========================= */
.container{
    max-width:720px;
    margin:28px auto;
    padding:16px;
}

/* =========================
   Glass Card (Premium)
========================= */
.card{
    background:rgba(20,22,32,0.72);
    border:1px solid rgba(255,255,255,0.12);
    padding:18px;
    border-radius:18px;
    box-shadow:0 20px 60px rgba(0,0,0,.45);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);
    overflow:hidden; /* ✅ safe */
}

/* Head area */
.top-actions{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:10px;
}
h2{
    margin:0;
    font-size:22px;
    color:#fff;
}

/* Form fields */
.field{
    margin-bottom:14px;
}
label{
    display:block;
    font-weight:700;
    margin-bottom:6px;
    color:rgba(255,255,255,0.92);
}

input[type="text"], input[type="email"], textarea{
    width:100%;
    box-sizing:border-box; /* ✅ FIX */
    padding:12px 14px;
    border:1px solid rgba(255,255,255,0.14);
    border-radius:14px;
    outline:none;
    background:rgba(255,255,255,0.06);
    color:#fff;
    transition:.2s;
}

input[type="text"]::placeholder,
textarea::placeholder{
    color:rgba(255,255,255,0.45);
}

input[type="text"]:focus,
input[type="email"]:focus,
textarea:focus{
    border-color:rgba(80,120,255,0.65);
    box-shadow:0 0 0 4px rgba(80,120,255,0.18);
}

/* Buttons */
button{
    padding:10px 16px;
    border-radius:999px;
    background:linear-gradient(135deg, rgba(31,111,235,1), rgba(170,90,255,1));
    color:#fff;
    border:none;
    cursor:pointer;
    font-weight:800;
    transition:.25s ease;
}
button:hover{
    transform:translateY(-2px);
    filter:brightness(1.05);
}

.cancel{
    display:inline-block;
    background:rgba(255,255,255,0.10);
    border:1px solid rgba(255,255,255,0.16);
    margin-left:8px;
    text-decoration:none;
    padding:10px 16px;
    border-radius:999px;
    color:#fff;
    font-weight:800;
    transition:.25s ease;
}
.cancel:hover{
    transform:translateY(-2px);
    background:rgba(255,255,255,0.14);
}

/* Flash message */
.flash{
    background:rgba(55,211,155,0.14);
    border:1px solid rgba(55,211,155,0.22);
    padding:10px 12px;
    margin:14px 0;
    color:#b9ffe6;
    border-radius:12px;
}

/* Logout button */
.logout-btn{
    background:rgba(239,68,68,0.9);
    padding:8px 14px;
    border-radius:999px;
    color:#fff;
    text-decoration:none;
    font-weight:800;
    border:1px solid rgba(255,255,255,0.16);
    transition:.25s ease;
}
.logout-btn:hover{
    transform:translateY(-2px);
    filter:brightness(1.05);
}

.small-note{
    font-size:12px;
    color:rgba(255,255,255,0.65);
    margin-top:6px;
}

/* Responsive */
@media(max-width:520px){
    .top-actions{
        flex-direction:column;
        align-items:flex-start;
    }
    .cancel{
        margin-left:0;
        margin-top:10px;
    }
}
</style>

<!-- AjaxZip3: 郵便番号→住所 自動入力 -->
<script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>

</head>
<body>

<div class="bg-noise"></div>

<?php require_once __DIR__ . '/partial_site_header.php'; ?>

<div class="container">

  <div class="card">
    <div class="top-actions">
      <h2>プロフィール編集</h2>
      <a href="logout.php" class="logout-btn">ログアウト</a>
    </div>

    <?php if ($flash != ""): ?>
      <div class="flash"><?php echo htmlspecialchars($flash, ENT_QUOTES); ?></div>
    <?php endif; ?>

    <form method="post" action="update_profile.php">
      <input type="hidden" name="user_id" value="<?php echo (int)$user['id']; ?>">

      <div class="field">
        <label for="name">名前</label>
        <input id="name" name="name" type="text" value="<?php echo htmlspecialchars($u_name, ENT_QUOTES); ?>" required>
      </div>

      <div class="field">
        <label for="email">メールアドレス（変更不可）</label>
        <input id="email" name="email" type="email" readonly value="<?php echo htmlspecialchars($u_email, ENT_QUOTES); ?>">
      </div>

      <div class="field">
        <label for="postal_code">郵便番号</label>
        <input
          id="postal_code"
          name="postal_code"
          type="text"
          value="<?php echo htmlspecialchars($u_postal, ENT_QUOTES); ?>"
          placeholder="例）123-4567"
          onKeyUp="AjaxZip3.zip2addr('postal_code','','address','address');"
        >
        <div class="small-note">郵便番号を入力すると、住所が自動で入力されます。</div>
      </div>

      <div class="field">
        <label for="phone">電話番号</label>
        <input
          id="phone"
          name="phone"
          type="text"
          value="<?php echo htmlspecialchars($u_phone, ENT_QUOTES); ?>"
          placeholder="例）090-1234-5678"
        >
      </div>

      <div class="field">
        <label for="address">住所</label>
        <textarea id="address" name="address" rows="4"><?php echo htmlspecialchars($u_address, ENT_QUOTES); ?></textarea>
      </div>

      <div style="margin-top:12px;">
        <button type="submit">変更を保存</button>
        <a href="menu.php" class="cancel">ショップに戻る</a>
      </div>
    </form>

  </div>

</div>

</body>
</html>
