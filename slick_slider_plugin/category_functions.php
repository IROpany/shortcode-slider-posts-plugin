<?php
/*
Plugin Name: Custom Support Button
Description: Add a custom support button to your posts.
Version: 1.0
Author: Iro
*/





function custom_slider_shortcode() {

  // カテゴリーのIDを取得
  $category = get_term_by('slug', 'category-slug名', 'category');
  $category_id = $category->term_id;

  // カテゴリーに属する最新の4件の記事を取得
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

/*
フックによって追加された部分には、
コメントで
// slickのdiv要素をフックで追加するや
// slickのJavaScriptをフックで追加する
といったように、フックで追加された部分であることを明示するようにしました。


/ slickのJavaScriptをフックで追加する
    add_action('wp_footer', function() { ?>

この一行を入れることで、wp_footer アクションフックに対して、
無名関数（コールバック関数）を追加しています。この関数は、
フックが実行されたときに自動的に呼び出されます。
具体的には、WordPressがページのフッターをレンダリングするときに、このフックが実行されます。

この無名関数は、slickのJavaScriptを出力するためのコードを含んでおり、
これによって slick スライダーがページに表示されます。
wp_footer アクションは、ページが完全にロードされた後に呼び出されるので、
slickのスクリプトが正常に動作することが保証されます。

つまり、この一行を追加することで、
slickスライダーを表示するための必要なJavaScriptコードをページのフッターに出力し、
それを WordPress が自動的に実行することができるようになるため、
コードの追加が簡単になります。

ショートコードをフック化することで、
ショートコードが実行されるタイミングでフック関数が実行されるようになり、
追加の機能を簡単に実装できるようになります。

また、WordPressでは、functions.phpファイルがあれば、自動的に読み込まれます。
そのため、新しいファイルを作成する必要はありません。
functions.phpファイルにフック関数を追加することで、
WordPressの動作を変更することができます。

*/