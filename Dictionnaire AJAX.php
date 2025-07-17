<?php
function afficher_dictionnaire_ajax() {
  ob_start(); ?>
<div id="dictionnaire-app">
  <div id="dictionary-tabs">
    <button data-dict="BYM" class="dict-tab active">BYM</button>
    <button data-dict="Easton" class="dict-tab">Easton</button>
  </div>

  <div id="alphabet-selector"></div>
  <div id="word-list"></div>
  <div id="dictionary-content"></div>
</div>

<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/dictionnaire-style-unifiee.css">
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify@3.0.5/dist/purify.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/interface-unifiee.js"></script>

<?php
return ob_get_clean();
}
add_shortcode('dictionnaire_ajax', 'afficher_dictionnaire_ajax');
