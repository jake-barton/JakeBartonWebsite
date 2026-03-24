    </div> <!-- End container -->

    <!-- Additional Scripts -->
    <?php if (isset($custom_scripts)): ?>
        <?php echo $custom_scripts; ?>
    <?php endif; ?>

    <!-- Style Kit JS -->
    <script src="<?php echo $base_path; ?>assets/js/beams-bg.js"></script>
    <script src="<?php echo $base_path; ?>assets/js/cursor-ribbons.js"></script>
    <script src="<?php echo $base_path; ?>assets/js/fuzzy-text.js"></script>
    <script src="<?php echo $base_path; ?>assets/js/effects-stylekit.js"></script>
    
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
