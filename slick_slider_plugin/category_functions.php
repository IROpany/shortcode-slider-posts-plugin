<?php
/*
Plugin Name: Shortcode Slider Plugin
Description: Add a slider block to your posts.
Version: 1.0
Author: Iro
*/


function custom_slider_shortcode() {

  // カテゴリーのIDを取得
  $category = get_term_by('slug', 'category-slug名', 'category');
  $category_id = $category->term_id;

  // カテゴリーに属する最新の3件の記事を取得
  $args = array(
    'post_type' => 'post',
    'posts_per_page' => 3,
    'cat' => $category_id,
    'orderby' => 'date',
    'order' => 'DESC'
  );
  
  $query = new WP_Query($args);

  // 記事が存在する場合、表示する
  if ($query->have_posts()) {

    // slickのdiv要素をフックで追加する
    echo '<div class="slider">';

    // 記事ループの開始
    while ($query->have_posts()) {
      $query->the_post();
      echo '<div>';
      echo '<a href="' . get_the_permalink() . '"><img src="' . get_the_post_thumbnail_url() . '"></a>';
      echo '<p><a href="' . get_the_permalink() . '">' . get_the_date() . ' <span>NEW OPEN</span></a></p>';
      echo '<p><a href="' . get_the_permalink() . '">' . get_the_title() . '</a></p>';
      echo '</div>';
    }

    // slickのdiv要素を閉じる
    echo '</div>';
    
    // slickのJavaScriptをフックで追加する
    add_action('wp_footer', function() { ?>



      <script type="text/javascript">
        jQuery(document).ready(function() {
          jQuery('.slider').slick({
            autoplay: true,
            slidesToShow: 3,
            slidesToScroll: 1
          });
        });
      </script>

    <?php });

  }

  // クエリのリセット
  wp_reset_postdata();
}

// ショートコードを追加する
add_shortcode('custom_slider', 'custom_slider_shortcode');

