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

</body>
</html>
