<?php
// Shortcode : [dictionnaire_interactif]
function afficher_dictionnaire_interactif() {
    ob_start(); ?>
    
    <div id="dictionnaire-app">
        <!-- Onglets dictionnaires -->
        <div id="dictionary-tabs">
            <button class="dict-tab active" data-dict="BYM">BYM</button>
            <button class="dict-tab" data-dict="Easton">Easton</button>
            <button class="dict-tab" data-dict="Smith">Smith</button>
            <button class="dict-tab" data-dict="Watson">Watson</button>
        </div>

        <!-- Présentation du dictionnaire actif -->
        <div id="dictionary-description" class="dictionary-note">
            <p><strong>Dictionnaire biblique de la BYM :</strong> Lexique original publié par la Bible de Yéhoshoua ha Mashiah (BYM). Domaine public sous la licence interne BYM.</p>
        </div>

        <!-- Alphabet -->
        <div id="alphabet-selector"></div>

        <!-- Liste des mots -->
        <div id="word-list"></div>

        <!-- Zone d'affichage de la définition -->
        <div id="dictionary-content"></div>
    </div>

    <!-- CSS -->
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/css/dictionnaire-style-unifiee.css">

    <!-- JS: Marked.js + Script principal -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/interface-unifiee.js"></script>

    <!-- JS additionnel pour la note et affichage "en cours de traduction" -->
    <script>
      document.addEventListener("DOMContentLoaded", () => {
        const descriptions = {
          BYM: "<p><strong>Dictionnaire biblique de la BYM :</strong> Lexique original publié par la Bible de Yéhoshoua ha Mashiah (BYM). Contenu entièrement restauré dans une optique d’exactitude sémantique et spirituelle. Domaine public sous la licence interne BYM.</p>",
          Easton: "<p><strong>Easton's Bible Dictionary (1897)</strong> – Auteur : M. G. Easton. Dictionnaire classique en anglais, domaine public. <strong>En cours de traduction</strong> par les Éditions À l’ombre du figuier.</p>",
          Smith: "<p><strong>Smith's Bible Dictionary</strong> – Auteur : William Smith. Dictionnaire biblique du XIXe siècle, domaine public. <strong>En cours de traduction</strong> par les Éditions À l’ombre du figuier.</p>",
          Watson: "<p><strong>Watson's Biblical & Theological Dictionary</strong> – Auteur : Richard Watson. Ouvrage théologique du XIXe siècle, domaine public. <strong>En cours de traduction</strong> par les Éditions À l’ombre du figuier.</p>"
        };

        const tabs = document.querySelectorAll(".dict-tab");
        const note = document.getElementById("dictionary-description");
        const content = document.getElementById("dictionary-content");
        const list = document.getElementById("word-list");
        const alpha = document.getElementById("alphabet-selector");

        tabs.forEach(tab => {
          tab.addEventListener("click", () => {
            tabs.forEach(t => t.classList.remove("active"));
            tab.classList.add("active");

            const key = tab.dataset.dict;
            if (descriptions[key]) {
              note.innerHTML = descriptions[key];
            }

            // Affiche un message central si en cours de traduction
            if (key === "Smith" || key === "Watson") {
              list.innerHTML = "";
              alpha.innerHTML = "";
              content.innerHTML = `
                <div style="text-align: center; padding: 3em 1em;">
                  <p style="font-size: 1.3rem; font-weight: bold; color: #6B4C3B;">
                    Ce dictionnaire est en cours de traduction.
                  </p>
                </div>
              `;
            } else {
              // Réinitialise interface pour Easton ou BYM (si implémenté dans le JS global)
              content.innerHTML = "";
              list.style.display = "block";
            }
          });
        });
      });
    </script>

    <?php
    return ob_get_clean();
}
add_shortcode('dictionnaire_interactif', 'afficher_dictionnaire_interactif');
