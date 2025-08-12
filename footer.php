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
            <div class="footer-widget" id="footer-widget-uenf">
                <div class="setor-info">
                <?php
                            if ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) :
                                the_custom_logo();
                ?>
                    <div class="logo-setor">
                    </div>
                <?php
                    endif
                ?>

                    <h4><?php echo get_bloginfo( 'name' ); ?></h4>
                    <p><strong>Email:</strong> <?php dynamic_sidebar( 'endereco-email-setor' ); ?><br>
                    <strong>Telefone:</strong> <?php dynamic_sidebar('telefone-setor'); ?></p>
                </div>
            </div>
            <?php if (get_theme_mod('disable_footer_sidebar', false)) : ?>
            <?php add_filter('body_class', function($classes) { $classes[] = 'footer-sidebar-disabled'; return $classes; }); ?>
            <?php endif; ?>
        </div>
    </div>
</footer>
</div><!-- #page -->

<a href="#" class="back-to-top">
    <i class="fas fa-arrow-up"></i>
</a>

<?php wp_footer(); ?>

<?php // Painel de atalhos reativado com container dedicado
if (!is_admin()) : ?>
<!-- Container dedicado para o painel de atalhos -->
<div id="uenf-shortcut-panel-container">
    <div class="shortcut-icon" data-start-y="50%" title="Atalhos Rápidos">
        <i class="fas fa-cog"></i>
    </div>

    <div class="shortcut-panel">
        <div class="shortcut-panel-header">
            <h3>Atalhos Rápidos</h3>
            <button class="close-panel" aria-label="Fechar painel de atalhos">&times;</button>
        </div>
        <div class="shortcut-panel-content">
            <!-- ...existing shortcut items... -->
            <a href="<?php echo esc_url(home_url('/')); ?>" class="shortcut-item">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a href="<?php echo esc_url(home_url('/contato')); ?>" class="shortcut-item">
                <i class="fas fa-envelope"></i>
                <span>Contato</span>
            </a>
            <a href="#footer-widget-uenf" class="shortcut-item" data-scroll="smooth">
                <i class="fas fa-phone"></i>
                <span>Telefone</span>
            </a>
            <a href="<?php echo esc_url(home_url('/contato')); ?>" class="shortcut-item">
                <i class="fas fa-map-marker-alt"></i>
                <span>Localização</span>
            </a>
        </div>
    </div>
</div>

<script>
// Garante que o DOM esteja totalmente carregado
jQuery(document).ready(function($) {
    // Verifica se o container existe
    var $panelContainer = $('#uenf-shortcut-panel-container');
    if ($panelContainer.length === 0) return;
    
    // Move o container para o final do body para evitar problemas de z-index
    $panelContainer.appendTo('body');
    
    // Inicializa o painel de atalhos
    function initShortcutPanel() {
        var $icon = $panelContainer.find('.shortcut-icon');
        var $panel = $panelContainer.find('.shortcut-panel');
        var $closeBtn = $panelContainer.find('.close-panel');
        
        // Função para abrir o painel
        function openPanel() {
            $panel.addClass('active');
            $icon.addClass('active');
            $('body').css('overflow', 'hidden');
        }
        
        // Função para fechar o painel
        function closePanel() {
            $panel.removeClass('active');
            $icon.removeClass('active');
            $('body').css('overflow', '');
        }
        
        // Evento de clique no ícone
        $icon.on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if ($panel.hasClass('active')) {
                closePanel();
            } else {
                openPanel();
            }
        });
        
        // Evento de clique no botão fechar
        $closeBtn.on('click', function(e) {
            e.preventDefault();
            closePanel();
        });
        
        // Fechar ao clicar fora do painel
        $(document).on('click', function(e) {
            if ($(e.target).closest('.shortcut-panel').length === 0 && 
                $(e.target).closest('.shortcut-icon').length === 0) {
                closePanel();
            }
        });
        
        // Prevenir fechamento ao clicar dentro do painel
        $panel.on('click', function(e) {
            e.stopPropagation();
        });
        
        // Fechar com a tecla ESC
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' || e.keyCode === 27) {
                closePanel();
            }
        });
        
        // Scroll suave para links internos
        $('.shortcut-item[href^="#"]').on('click', function(e) {
            var target = $(this).attr('href');
            if (target !== '#') {
                e.preventDefault();
                closePanel();
                
                if ($(target).length) {
                    $('html, body').animate({
                        scrollTop: $(target).offset().top - 100
                    }, 800);
                }
            }
        });
        
    // log removido
    }
    
    // Inicializa o painel
    initShortcutPanel();
});
</script>

<script src="<?php echo esc_url(get_template_directory_uri() . '/js/shortcut-panel.js'); ?>"></script>
<?php endif; ?>

</body>
</html> 