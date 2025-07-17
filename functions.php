<?php
// ✅ Charger les styles du thème parent + enfant
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('kadence-parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('kadence-child-style', get_stylesheet_uri(), ['kadence-parent-style']);

    // ✅ Swiper.js + fichier custom swiper.js
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js', array(), null, true);
    wp_enqueue_script('figuier-swiper', get_stylesheet_directory_uri() . '/js/swiper.js', array('swiper-js'), null, true);

    // ✅ Interface dictionnaire + localisation des sources JSON
    wp_enqueue_script('figuier-dictionary', get_stylesheet_directory_uri() . '/interface-unifiee.js', array(), null, true);
    $upload_dir = wp_upload_dir();
    $dictionary_sources = array(
        'BYM'   => $upload_dir['baseurl'] . '/dictionnaires/lexique-bym.json',
        'Easton' => $upload_dir['baseurl'] . '/dictionnaires/eastons.json',
    );
    wp_localize_script('figuier-dictionary', 'dictionarySources', $dictionary_sources);
});

// ✅ Fonction pour afficher les derniers posts (grille ou carrousel)
if (!function_exists('figuier_latest_posts')) {
  function figuier_latest_posts($post_type, $as_carousel = false) {
    $query = new WP_Query(array(
        'post_type' => $post_type,
        'posts_per_page' => 3,
        'post_status' => 'publish',
    ));

    if ($as_carousel) {
      $output = '<div class="swiper"><div class="swiper-wrapper">';
    } else {
      $output = '<div class="figuier-grid">';
    }

    while ($query->have_posts()) {
        $query->the_post();

        $card_class = $as_carousel ? 'swiper-slide' : 'figuier-card';

        $output .= '<article class="' . $card_class . '">';
        if (has_post_thumbnail()) {
            $output .= '<div class="figuier-thumb"><a href="' . get_permalink() . '">' . get_the_post_thumbnail(null, 'medium') . '</a></div>';
        }
        $output .= '<h3><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
        $output .= '<p>' . get_the_excerpt() . '</p>';
        $output .= '</article>';
    }

    wp_reset_postdata();

    if ($as_carousel) {
      $output .= '</div>';
      $output .= '<div class="swiper-button-prev"></div><div class="swiper-button-next"></div>';
      $output .= '</div>'; // .swiper
    } else {
      $output .= '</div>';
    }

    return $output;
  }
}

// 1. Fonction d'extraction d'image mise en avant ou depuis le contenu
function figuier_get_image() {
  if (has_post_thumbnail()) {
    return get_the_post_thumbnail_url(get_the_ID(), 'medium');
  }

  $content = get_the_content();
  preg_match('/<img.+src=[\'"](?P<src>.+?)[\'"].*>/i', $content, $image);
  return !empty($image['src']) ? esc_url($image['src']) : '';
}

// 2. Fonction générique d'affichage de section
function figuier_render_section($args, $titre, $lien_voir_tout, $style = 'grille') {
  $query = new WP_Query($args);
  if (!$query->have_posts()) return '';

  ob_start();
  echo '<section class="homepage-latest">';
  echo '<h2>' . esc_html($titre) . '</h2>';
  echo '<div class="' . ($style === 'carrousel' ? 'carrousel-actus' : 'figuier-latest-items') . '">';

  while ($query->have_posts()) : $query->the_post();
    $image_url = figuier_get_image();
    ?>
    <div class="<?php echo $style === 'carrousel' ? 'slide' : 'figuier-item'; ?>">
      <?php if ($image_url) : ?>
        <div class="figuier-thumb">
          <img src="<?php echo $image_url; ?>" alt="<?php the_title(); ?>">
        </div>
      <?php endif; ?>
      <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
      <p class="excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
    </div>
  <?php endwhile;


  echo '</div>';
  echo '<div class="voir-tout-container"><a class="btn-voir-tout" href="' . esc_url($lien_voir_tout) . '">Voir tout</a></div>';
  echo '</section>';

  wp_reset_postdata();
  return ob_get_clean();
}

// 3. Shortcode principal : [homepage_custom]
function figuier_homepage_custom_shortcode() {
  ob_start();

  // Bloc outils bibliques (fixe)
  echo '<section class="tools-block">';
  echo '<h2>Outils d’étude biblique</h2>';
  echo '<p>Explorez nos dictionnaires, encyclopédies, cartes et illustrations bibliques pour enrichir votre compréhension des Écritures.</p>';
  echo '<a class="btn-voir-tout" href="/ressources-bibliques/">Découvrir les outils</a>';
  echo '</section>';
  
    // Grille Études & Réflexions
  echo figuier_render_section([
  'post_type' => 'post',
  'posts_per_page' => 6,
  'category_name' => 'etude-reflexion-meditation'
], 'Études & Réflexions', '/etudes-reflexions', 'carrousel');

  // Carrousel actualités
  echo figuier_render_section([
    'post_type' => 'post',
    'posts_per_page' => 6,
    'category_name' => 'actualites'
  ], 'Dernières actualités', '/actualites', 'carrousel');


  // Grille Témoignages & Réveils
  echo figuier_render_section([
    'post_type' => 'post',
    'posts_per_page' => 6,
    'category_name' => 'temoignage-reveils'
  ], 'Témoignages & Réveils', '/temoignages-reveils', 'grille');

  // Carrousel Podcasts
  echo figuier_render_section([
    'post_type' => 'podcast',
    'posts_per_page' => 6
  ], 'Podcasts récents', '/podcasts', 'carrousel');

  return ob_get_clean();
}
add_shortcode('homepage_custom', 'figuier_homepage_custom_shortcode');



function figuier_enqueue_google_fonts() {
  wp_enqueue_style(
    'figuier-google-fonts',
    'https://fonts.googleapis.com/css2?family=EB+Garamond&family=Inter:wght@400;500;600&display=swap',
    false
  );
}
add_action('wp_enqueue_scripts', 'figuier_enqueue_google_fonts');

add_action('rest_api_init', function () {
    register_rest_route('figuier-api/v1', '/dictionnaires/', [
        'methods' => 'GET',
        'callback' => 'get_fichiers_dictionnaires',
        'permission_callback' => function () {
            return is_user_logged_in(); // sécurisé via token JWT
        },
    ]);
});

function get_fichiers_dictionnaires() {
    $upload_dir = wp_upload_dir();
    $chemin = $upload_dir['basedir'] . '/dictionnaires/';
    $url_base = $upload_dir['baseurl'] . '/dictionnaires/';

    if (!file_exists($chemin)) {
        return new WP_Error('no_dir', 'Le dossier dictionnaires n\'existe pas.', ['status' => 404]);
    }

    $fichiers = glob($chemin . '*');
    $resultat = [];

    foreach ($fichiers as $fichier) {
        if (is_file($fichier)) {
            $resultat[] = [
                'nom' => basename($fichier),
                'url' => $url_base . basename($fichier),
                'taille_ko' => round(filesize($fichier) / 1024, 2),
                'modifié_le' => date("Y-m-d H:i:s", filemtime($fichier)),
            ];
        }
    }

    return rest_ensure_response($resultat);
}

