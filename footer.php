<!-- =========================
   PREMIUM FOOTER (SHEIN STYLE)
========================= -->
<footer class="premium-footer">
  <div class="footer-wrap">

    <!-- Column 1 -->
    <div class="footer-col">
      <h4>COMPANY INFO</h4>
      <a href="#">About GoBuyNow</a>
      <a href="#">Our Story</a>
      <a href="#">Careers</a>
      <a href="#">Newsroom</a>
      <a href="#">Legal Information</a>
    </div>

    <!-- Column 2 -->
    <div class="footer-col">
      <h4>HELP & SUPPORT</h4>
      <a href="#">Shopping Guide</a>
      <a href="#">Payment Method</a>
      <a href="#">Shipping Info</a>
      <a href="#">Return Policy</a>
      <a href="#">Refund Method</a>
      <a href="#">Track Order</a>
    </div>

    <!-- Column 3 -->
    <div class="footer-col">
      <h4>CUSTOMER CARE</h4>
      <a href="contact.php">Contact Us</a>
      <a href="my_orders.php">My Orders</a>
      <a href="return_history.php">Return History</a>
      <a href="profile.php">My Profile</a>
      <a href="liked_products.php">Wishlist</a>
      <a href="#">FAQ</a>
    </div>

    <!-- Column 4 -->
    <div class="footer-col footer-col-wide">
      <h4>FIND US ON SNS</h4>

      <div class="footer-social">
        <a href="#" title="Facebook">f</a>
        <a href="#" title="Instagram">⌁</a>
        <a href="#" title="TikTok">♪</a>
        <a href="#" title="YouTube">▶</a>
        <a href="#" title="Twitter">𝕏</a>
      </div>

      <h4 style="margin-top:18px;">SIGN UP FOR NEWS</h4>

      <form class="footer-form" onsubmit="return false;">
        <input type="email" placeholder="Your Email Address">
        <button type="submit">Subscribe</button>
      </form>

      <div class="footer-note">
        By subscribing, you agree to our <a href="#">Terms</a> and <a href="#">Privacy Policy</a>.
      </div>
    </div>

  </div>

  <!-- Bottom Line -->
  <div class="footer-bottom">
    <div class="footer-copy">
      © 2026 GoBuyNow. All Rights Reserved.
    </div>
  </div>
</footer>


<style>
/* =========================
   Premium Footer Style
========================= */
.premium-footer{
  margin-top:40px;
  padding:40px 18px 18px;
  background: rgba(2,6,23,0.55);
  border-top: 1px solid rgba(255,255,255,0.10);
  backdrop-filter: blur(18px);
  -webkit-backdrop-filter: blur(18px);
  color: rgba(255,255,255,0.90);
  position:relative;
  overflow:hidden;
}

/* Glow */
.premium-footer::before{
  content:"";
  position:absolute;
  inset:-30%;
  background:
    radial-gradient(circle at 20% 30%, rgba(80,120,255,0.35), transparent 60%),
    radial-gradient(circle at 80% 70%, rgba(170,90,255,0.30), transparent 65%);
  filter: blur(120px);
  opacity:0.8;
  pointer-events:none;
}

.footer-wrap{
  max-width:1400px;
  margin:0 auto;
  display:grid;
  grid-template-columns: repeat(4, 1fr);
  gap:26px;
  position:relative;
  z-index:2;
}

/* Columns */
.footer-col h4{
  margin:0 0 14px;
  font-size:13px;
  letter-spacing:0.8px;
  font-weight:800;
  color:#fff;
  text-transform:uppercase;
}

.footer-col a{
  display:block;
  text-decoration:none;
  color: rgba(255,255,255,0.72);
  font-size:13px;
  padding:6px 0;
  transition: .25s ease;
}

.footer-col a:hover{
  color:#fff;
  transform: translateX(4px);
  text-shadow: 0 0 12px rgba(255,255,255,0.12);
}

/* Wide column */
.footer-col-wide{
  grid-column: span 1;
}

/* Social Icons */
.footer-social{
  display:flex;
  gap:10px;
  flex-wrap:wrap;
}

.footer-social a{
  width:40px;
  height:40px;
  border-radius:12px;
  display:flex;
  align-items:center;
  justify-content:center;
  background: rgba(255,255,255,0.08);
  border: 1px solid rgba(255,255,255,0.12);
  color:#fff;
  font-weight:800;
  font-size:14px;
  transition:.25s ease;
}

.footer-social a:hover{
  transform: translateY(-2px);
  box-shadow: 0 14px 30px rgba(0,0,0,0.35);
  background: rgba(255,255,255,0.12);
}

/* Subscribe Form */
.footer-form{
  display:flex;
  gap:10px;
  margin-top:10px;
}

.footer-form input{
  flex:1;
  padding:12px 12px;
  border-radius:12px;
  border: 1px solid rgba(255,255,255,0.14);
  background: rgba(0,0,0,0.35);
  color:#fff;
  outline:none;
}

.footer-form input::placeholder{
  color: rgba(255,255,255,0.45);
}

.footer-form button{
  padding:12px 16px;
  border:none;
  border-radius:12px;
  cursor:pointer;
  font-weight:800;
  color:#fff;

  background: linear-gradient(135deg, rgba(31,111,235,1), rgba(170,90,255,1));
  transition:.25s ease;
}

.footer-form button:hover{
  transform: translateY(-2px);
  box-shadow: 0 14px 30px rgba(31,111,235,0.25);
}

/* Note */
.footer-note{
  margin-top:10px;
  font-size:12px;
  color: rgba(255,255,255,0.55);
  line-height:1.5;
}

.footer-note a{
  color: rgba(255,255,255,0.85);
  text-decoration:none;
  font-weight:700;
}
.footer-note a:hover{
  text-decoration:underline;
}

/* Bottom */
.footer-bottom{
  max-width:1400px;
  margin:22px auto 0;
  padding-top:14px;
  border-top: 1px solid rgba(255,255,255,0.10);
  position:relative;
  z-index:2;
}

.footer-copy{
  font-size:13px;
  color: rgba(255,255,255,0.60);
  text-align:center;
}

/* Responsive */
@media(max-width:1000px){
  .footer-wrap{
    grid-template-columns: repeat(2, 1fr);
  }
}
@media(max-width:560px){
  .footer-wrap{
    grid-template-columns: 1fr;
  }
  .footer-form{
    flex-direction:column;
  }
  .footer-form button{
    width:100%;
  }
}
</style>
