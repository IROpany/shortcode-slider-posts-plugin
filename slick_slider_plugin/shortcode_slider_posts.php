<?php
/**
 * Plugin Name: Shortcode Slider Posts Plugin
 * Description: Adds a shortcode slider to your posts.
 * Version: 1.0
 * Author: Iro
 */

// Shortcode for the slider
function custom_slider_shortcode() {
  ob_start(); // バッファリングを開始

  ?>

  <div class="slider">
    <?php
    // カテゴリーのIDを取得
    $category = get_category_by_slug('カテゴリー名');　//カテゴリー名を入れて下さい
    $category_id = $category->cat_ID;

    // カテゴリーに属する最新の3件の記事を取得
    $args = array(
      'post_type' => 'post',
      'posts_per_page' => 4,
      'cat' => $category_id,
      'orderby' => 'date',
      'order' => 'DESC'
    );
    $query = new WP_Query($args);

    // 記事が存在する場合、表示する
    if ($query->have_posts()) {
      while ($query->have_posts()) {
        $query->the_post();
        echo '<div>';
        echo '<a href="' . get_the_permalink() . '"><img src="' . get_the_post_thumbnail_url() . '"></a>';
        echo '<p><a href="' . get_the_permalink() . '">' . get_the_date() . ' <span>NEW OPEN</span></a></p>';
        echo '<p><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></p>';
        echo '</div>';
      }
    }

    // クエリのリセット
    wp_reset_postdata();
    // ...
    ?>
  </div>

  <script type="text/javascript">
    jQuery(document).ready(function() {
      jQuery('.slider').slick({
        autoplay: true,
        slidesToShow: 3,
        slidesToScroll: 1
      });
    });
  </script>

  <?php
  return ob_get_clean(); // バッファリングしたコンテンツを返す
}

// ショートコードを登録
add_shortcode('custom_slider', 'custom_slider_shortcode');

// カスタムCSSを読み込むための関数
function enqueue_custom_css() {
  wp_enqueue_style('custom-slider-css', plugins_url('/css/style.css', __FILE__));
}

// カスタムCSSの読み込みを設定
add_action('wp_enqueue_scripts', 'enqueue_custom_css');
