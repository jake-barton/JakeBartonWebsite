    </div> <!-- End container -->

    <!-- Additional Scripts -->
    <?php if (isset($custom_scripts)): ?>
        <?php echo $custom_scripts; ?>
    <?php endif; ?>

    <!-- Site-wide smooth scrolling -->
    <script src="https://cdn.jsdelivr.net/npm/lenis@1.3.25/dist/lenis.min.js" defer></script>
    <script src="<?php echo $base_path; ?>assets/js/site-scroll.js?v=20261109-chapter-links" defer></script>

    <!-- Style Kit JS -->
    
    <!-- Analytics (Add your tracking code here when ready) -->
    <?php if (!IS_DEVELOPMENT): ?>
    <!-- 
    <script async src="https://www.googletagmanager.com/gtag/js?id=YOUR-GA-ID"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'YOUR-GA-ID');
    </script>
    -->
    <?php endif; ?>
</body>
</html>
