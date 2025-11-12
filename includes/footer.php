<?php
/**
 * Footer global du site Loulou Play
 */
?>
<style>
    .footer-copyright {
        margin: 0;
        line-height: 1.8;
    }
    
    .footer-separator {
        display: inline;
    }
    
    /* Sur mobile et tablette : retour à la ligne */
    @media (max-width: 1024px) {
        .footer-separator {
            display: block;
            margin: 10px 0;
        }
    }
</style>

<footer style="
    max-width: 1200px;
    margin: 40px auto 0;
    background: rgba(255, 255, 255, 0.95);
    padding: 25px;
    text-align: center;
    border-radius: 25px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
    font-family: 'Fredoka', Arial, sans-serif;
    color: #5E35B1;
    font-size: 1em;
    font-weight: 600;
    position: relative;       /* +++ */
    z-index: 2000;            /* +++ au-dessus des décos */
">
    <p class="footer-copyright">
        © <?php echo date('Y'); ?> Loulou Play · 
        <span class="footer-separator">
            <a href="<?php echo PATH_ROOT; ?>cgu.php" 
               style="color: #667eea; text-decoration: none; font-weight: 700;"
               onmouseover="this.style.textDecoration='underline';" 
               onmouseout="this.style.textDecoration='none';">
               Conditions Générales d'Utilisation
            </a>
        </span>
    </p>
</footer>
<!-- Bandeau cookies (information uniquement, Suisse) -->
<style>
  .lp-cookie-banner {
    position: fixed;
    left: 20px;
    right: 160px; /* on laisse de l’espace pour la mascotte en bas à droite */
    bottom: 20px;
    z-index: 3000;
    display: none; /* sera rendu visible par JS si non accepté */
    background: rgba(255,255,255,0.98);
    color: #2C3E50;
    border-radius: 18px;
    box-shadow: 0 8px 30px rgba(0,0,0,0.25);
    padding: 16px 18px;
    font-family: 'Fredoka', Arial, sans-serif;
    align-items: center;
    gap: 16px;
  }
  .lp-cookie-text {
    font-size: 1.05em;
    line-height: 1.5;
    text-align: left;
  }
  .lp-cookie-actions {
    display: flex;
    gap: 10px;
    flex-shrink: 0;
  }
  .lp-cookie-btn {
    padding: 12px 18px;
    border: none;
    border-radius: 14px;
    cursor: pointer;
    font-weight: 700;
    color: #fff;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    box-shadow: 0 6px 15px rgba(102,126,234,0.35);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .lp-cookie-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 22px rgba(102,126,234,0.55);
  }
  .lp-cookie-link {
    color: #667eea;
    text-decoration: none;
    font-weight: 700;
  }
  .lp-cookie-link:hover {
    text-decoration: underline;
  }
  @media (max-width: 1024px) {
    .lp-cookie-banner { right: 20px; } /* sur tablette on recentre un peu */
  }
  @media (max-width: 750px) {
    .lp-cookie-banner {
      left: 10px;
      right: 10px; /* pleine largeur utile sur mobile */
      bottom: 10px;
      flex-direction: column;
      align-items: stretch;
      gap: 12px;
    }
    .lp-cookie-actions { justify-content: flex-end; }
  }
</style>

<div id="lpCookieBanner" class="lp-cookie-banner" role="region" aria-label="Information sur les cookies" aria-live="polite">
  <div class="lp-cookie-text">
    🛡️ Sur Loulou Play, seuls des cookies strictement nécessaires au fonctionnement sont utilisés. Aucun suivi, analytics ni publicité.
    <a class="lp-cookie-link" href="<?php echo PATH_ROOT; ?>cgu.php">En savoir plus</a>
  </div>
  <div class="lp-cookie-actions">
    <button id="lpCookieOk" class="lp-cookie-btn" aria-label="J’ai compris">J’ai compris</button>
  </div>
</div>

<script>
  (function () {
    var KEY = 'lp_cookie_notice_ack';
    try {
      if (!localStorage.getItem(KEY)) {
        var el = document.getElementById('lpCookieBanner');
        if (el) el.style.display = 'flex';
      }
      var btn = document.getElementById('lpCookieOk');
      if (btn) {
        btn.addEventListener('click', function () {
          try { localStorage.setItem(KEY, '1'); } catch (e) {}
          var el = document.getElementById('lpCookieBanner');
          if (el) el.remove();
        });
      }
    } catch (e) {
      /* en cas de blocage du stockage, on n’affiche rien de persistant */
    }
  })();
</script>

</body>
</html>
