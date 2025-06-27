</div><!-- #content -->

<footer id="colophon" class="site-footer">
    <div class="container">
        <div class="footer-widgets">
            <div class="footer-widget">
                <a href="https://uenf.br" target="_blank" class="footer-logo">
                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/logo-Uenf-vertical-sem-assinatura.png" alt="UENF">
                </a>
            </div>
            <div class="footer-widget">
                <div class="enderecos">
                    <h4>UENF campus Campos dos Goytacazes</h4>
                    <p>Av. Alberto Lamego, 200 Parque Califórnia,<br>
                    Campos - RJ, Brasil CEP: 28013-602</p>
                    <h4>UENF campus Macaé</h4>
                    <p>Rua Hermenegildo Adolpho Parque Bellavista Imboassica<br>
                    Macaé - RJ, Brasil CEP: 27.925-310</p>
                </div>
            </div>
            <div class="footer-widget">
                <div class="setor-info">
                    <div class="logo-setor">
                        <?php
                            if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) :
                                the_custom_logo();
                            endif
                        ?>
                    </div>
                    <h4><?php echo get_bloginfo( 'name' ); ?></h4>
                    <p><strong>Email:</strong> <?php dynamic_sidebar( 'endereco-email-setor' ); ?><br>
                    <strong>Telefone:</strong> <?php 
                        ob_start();
                        dynamic_sidebar('telefone-setor');
                        $telefone_widget = ob_get_clean();
                        echo formatarTelefoneBrasil($telefone_widget);
                    ?></p>
                </div>
            </div>
        </div>
    </div>
</footer>
</div><!-- #page -->

<!-- Botão de atalhos -->
<div class="shortcut-icon" data-start-y="50%" title="Atalhos Rápidos">
<i class="fas fa-cog"></i>
</div>

<!-- Painel de atalhos -->
<div class="shortcut-panel">
<div class="shortcut-panel-header">
    <h3>Atalhos Rápidos</h3>
    <button class="close-panel">&times;</button>
</div>
<div class="shortcut-panel-content">
    <a href="<?php echo home_url(); ?>" class="shortcut-item">
        <i class="fas fa-home"></i>
        <span>Home</span>
    </a>
    <a href="<?php echo esc_url(home_url()); ?>/contato" class="shortcut-item">
        <i class="fas fa-envelope"></i>
        <span>Contato</span>
    </a>
    <a href="tel:<?php 
                        dynamic_sidebar( 'telefone-setor' );
                    ?>" class="shortcut-item">
        <i class="fas fa-phone"></i>
        <span>Telefone</span>
    </a>
    <a href="<?php echo esc_url(home_url()); ?>/contato" target="_blank" class="shortcut-item">
        <i class="fas fa-map-marker-alt"></i>
        <span>Localização</span>
    </a>
</div>
</div>

<a href="#" class="back-to-top">
<i class="fas fa-arrow-up"></i>
</a>

<?php wp_footer(); ?>

<script src="<?php echo get_template_directory_uri(); ?>/js/shortcut-panel.js"></script>

</body>
</html> 