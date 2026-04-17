---
id: enqueue-conditional
agent: architect
execution: inline
---

# Step 7: Enqueue Condicional

Implementar o carregamento condicional dos CSS por device no functions.php.

## Instruções

1. No `cct_scripts()` em functions.php, adicionar após os enqueues existentes:
   ```php
   // CSS responsivo — carregado condicionalmente por media query
   $responsive_files = [
       'mobile'  => ['file' => '/css/responsive/mobile.css',  'media' => '(max-width:767.98px)'],
       'tablet'  => ['file' => '/css/responsive/tablet.css',  'media' => '(min-width:768px) and (max-width:991.98px)'],
       'desktop' => ['file' => '/css/responsive/desktop.css', 'media' => '(min-width:992px)'],
   ];
   foreach ($responsive_files as $device => $config) {
       $path = get_template_directory() . $config['file'];
       if (file_exists($path)) {
           wp_enqueue_style("cct-responsive-{$device}", CCT_THEME_URI . $config['file'], ['cct-custom-fixes'], filemtime($path));
           wp_style_add_data("cct-responsive-{$device}", 'media', $config['media']);
       }
   }
   ```
2. Verificar que as tags `<link>` geradas têm o atributo `media` correto
3. Testar com curl que o HTML inclui as 3 tags link condicionais
