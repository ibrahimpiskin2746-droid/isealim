    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>İş Platformu</h3>
                    <p>AI destekli, yeni nesil iş bulma ve işe alım platformu.</p>
                    <div class="footer-social">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-linkedin"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4>Hızlı Linkler</h4>
                    <ul>
                        <li><a href="<?= url('jobs') ?>">İş İlanları</a></li>
                        <li><a href="<?= url('about') ?>">Hakkımızda</a></li>
                        <li><a href="<?= url('contact') ?>">İletişim</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>İşverenler İçin</h4>
                    <ul>
                        <li><a href="<?= url('auth/register') ?>">İlan Ver</a></li>
                        <li><a href="<?= url('employer/dashboard') ?>">İşveren Paneli</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>İletişim</h4>
                    <p><i class="fas fa-envelope"></i> info@isplatformu.com</p>
                    <p><i class="fas fa-phone"></i> +90 (555) 123 45 67</p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> İş Platformu. Tüm hakları saklıdır.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="<?= JS_URL ?>/app.js"></script>
    
    <script>
        // CSRF Token
        const CSRF_TOKEN = '<?= generateCsrfToken() ?>';
        const BASE_URL = '<?= SITE_URL ?>';
        
        // Flash mesaj kapatma
        document.querySelectorAll('.alert-close').forEach(btn => {
            btn.addEventListener('click', function() {
                this.parentElement.style.display = 'none';
            });
        });
        
        // Otomatik kaybolma
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
        
        // Dropdown toggle
        document.querySelectorAll('.navbar-toggle').forEach(toggle => {
            toggle.addEventListener('click', function(e) {
                e.stopPropagation();
                const menu = this.nextElementSibling;
                menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
            });
        });
        
        // Dropdown dışında tıklama
        document.addEventListener('click', () => {
            document.querySelectorAll('.navbar-dropdown-menu').forEach(menu => {
                menu.style.display = 'none';
            });
        });
    </script>
</body>
</html>
