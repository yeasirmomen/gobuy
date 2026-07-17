<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Order Completed | GoBuyNow</title>

<style>
*{box-sizing:border-box}

:root{
    --smoke-blue:rgba(96,165,250,0.22);
    --smoke-dark:rgba(15,23,42,0.45);
}

/* ================= BODY + FLOATING SMOKE (MENU STYLE) ================= */
body{
    margin:0;
    font-family:"Segoe UI","Noto Sans JP",system-ui,Arial,sans-serif;
    background:#020617;
    overflow-x:hidden;
    color:#e5e7eb;
    min-height:100vh;

    display:flex;
    justify-content:center;
    align-items:center;
    flex-direction:column;
    padding:20px;
}

body::before,
body::after{
    content:"";
    position:fixed;
    inset:-35%;
    z-index:-2;
}

body::before{
    background:
        radial-gradient(circle at 30% 40%, var(--smoke-blue), transparent 60%),
        radial-gradient(circle at 70% 60%, var(--smoke-dark), transparent 65%);
    animation: smokeFloat1 28s ease-in-out infinite;
}

body::after{
    background:
        radial-gradient(circle at 50% 20%, var(--smoke-dark), transparent 65%),
        radial-gradient(circle at 20% 80%, var(--smoke-blue), transparent 55%);
    animation: smokeFloat2 36s ease-in-out infinite;
    opacity:.7;
}

@keyframes smokeFloat1{
    0%{transform:translate(0,0) scale(1);opacity:.5}
    50%{transform:translate(-6%,4%) scale(1.15);opacity:.75}
    100%{transform:translate(0,0) scale(1);opacity:.5}
}
@keyframes smokeFloat2{
    0%{transform:translate(0,0) scale(1);opacity:.4}
    50%{transform:translate(5%,-6%) scale(1.2);opacity:.65}
    100%{transform:translate(0,0) scale(1);opacity:.4}
}

/* ===== Noise overlay ===== */
.bg-noise{
    position:fixed;
    inset:0;
    pointer-events:none;
    z-index:-1;
    opacity:0.06;
    background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='180' height='180'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='180' height='180' filter='url(%23n)' opacity='.35'/%3E%3C/svg%3E");
}

/* ================= GLASS CARD ================= */
.thank-card{
    width: min(440px, 92vw);
    padding: 44px 38px;
    border-radius: 22px;
    text-align: center;

    background: rgba(255,255,255,0.08);
    border: 1px solid rgba(255,255,255,0.18);
    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);

    box-shadow: 0 22px 60px rgba(0,0,0,0.55);
    animation: fadeUp 0.75s ease;
    position:relative;
    overflow:hidden;
}

@keyframes fadeUp{
    from{opacity:0;transform:translateY(20px) scale(.98)}
    to{opacity:1;transform:translateY(0) scale(1)}
}

/* glow inside card */
.thank-card::before{
    content:"";
    position:absolute;
    inset:-2px;
    background:
        radial-gradient(circle at 25% 20%, rgba(31,111,235,.35), transparent 55%),
        radial-gradient(circle at 80% 90%, rgba(170,90,255,.28), transparent 60%);
    opacity:.9;
    pointer-events:none;
}

/* ================= ICON ================= */
.check-icon{
    width: 92px;
    height: 92px;
    margin: 0 auto 18px;
    border-radius: 50%;
    display:flex;
    justify-content:center;
    align-items:center;

    background: linear-gradient(135deg, rgba(34,197,94,1), rgba(16,185,129,1));
    color:#ffffff;
    font-size: 44px;
    box-shadow: 0 18px 40px rgba(34,197,94,0.25);

    animation: popPulse 1.2s ease;
}

@keyframes popPulse{
    0%{transform:scale(.7);opacity:0}
    60%{transform:scale(1.08);opacity:1}
    100%{transform:scale(1)}
}

/* ================= TEXT ================= */
.thank-card h1{
    font-size: 26px;
    margin: 0 0 10px;
    color:#ffffff;
    position:relative;
    z-index:1;
}

.thank-card p{
    font-size: 15px;
    color: rgba(255,255,255,0.78);
    margin: 0 0 26px;
    line-height: 1.7;
    position:relative;
    z-index:1;
}

/* ================= BUTTONS ================= */
.actions{
    display:flex;
    flex-direction:column;
    gap:12px;
    position:relative;
    z-index:1;
}

.home-btn{
    padding: 13px;
    border-radius: 14px;
    text-decoration:none;
    font-weight:800;
    letter-spacing:.2px;

    background: linear-gradient(135deg, rgba(31,111,235,1), rgba(170,90,255,1));
    color:#fff;
    transition: all .25s ease;
    border: 1px solid rgba(255,255,255,0.18);
}

.home-btn:hover{
    transform: translateY(-2px);
    box-shadow: 0 16px 34px rgba(31,111,235,0.25);
}

.shop-btn{
    padding: 12px;
    border-radius: 14px;
    text-decoration:none;
    font-weight:700;

    background: rgba(255,255,255,0.08);
    color: rgba(255,255,255,0.92);
    border: 1px solid rgba(255,255,255,0.18);
    transition: all .25s ease;
}

.shop-btn:hover{
    transform: translateY(-2px);
    box-shadow: 0 16px 34px rgba(0,0,0,0.35);
}

/* ================= FOOTER GLASS BAR ================= */
.footer-bar{
    width:min(640px, 95vw);
    margin-top: 18px;
    padding: 12px 14px;
    border-radius: 18px;

    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.12);
    backdrop-filter: blur(14px);
    -webkit-backdrop-filter: blur(14px);

    text-align:center;
    color: rgba(255,255,255,0.70);
    font-size: 13px;
    box-shadow: 0 18px 45px rgba(0,0,0,0.35);
}

/* ================= CLICK BURST (SAME STYLE) ================= */
.smoke-burst{
    position:fixed;
    inset:0;
    background: radial-gradient(circle at center, rgba(255,255,255,.20), transparent 60%);
    animation: burst .8s ease forwards;
    pointer-events:none;
    z-index:2000;
}
@keyframes burst{
    0%{opacity:0;transform:scale(.6)}
    40%{opacity:1}
    100%{opacity:0;transform:scale(1.4)}
}
</style>
</head>

<body>

<div class="bg-noise"></div>

<div class="thank-card">

    <div class="check-icon">✓</div>

    <h1>ありがとうございました。</h1>
    <p>
        ご注文が正常に完了しました。<br>
        商品の準備ができ次第、発送いたします。
    </p>

    <div class="actions">
        <a href="menu.php" class="home-btn">🏠 ホームへ戻る</a>
        <a href="menu.php" class="shop-btn">🛍 買い物を続ける</a>
    </div>

</div>

<div class="footer-bar">
    © 2026 GoBuyNow. All Rights Reserved.
</div>

<script>
/* click smoke burst */
document.querySelectorAll('a,button').forEach(el=>{
    el.addEventListener('click',()=>{
        const smoke=document.createElement('div');
        smoke.className='smoke-burst';
        document.body.appendChild(smoke);
        setTimeout(()=>smoke.remove(),900);
    });
});
</script>

</body>
</html>

