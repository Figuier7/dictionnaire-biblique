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

<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/dictionnaire-biblique-style-unifiee.css">
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/interface-unifiee.js"></script>

<?php
return ob_get_clean();
}
add_shortcode('dictionnaire_ajax', 'afficher_dictionnaire_ajax');
