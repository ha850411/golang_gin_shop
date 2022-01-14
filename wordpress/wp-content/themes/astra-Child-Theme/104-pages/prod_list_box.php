<?php

//列表區塊
function prod_list_box($post_id){

    $title = get_the_title($post_id);
    $content = get_the_excerpt();
    $post_link = get_post_permalink();
    $user_id = get_current_user_id();
    
    $post_time = format_post_time($post_id);
    $views = 0;
    if ( function_exists('wpp_get_views') ) {
        $views = wpp_get_views($post_id);
    }

    // 取得精選圖片id
    $rand = rand(0,9999);
    $display_pic = "https://picsum.photos/300/200?random={$rand}";
    $query = $GLOBALS['wpdb']->prepare(
        "SELECT meta_value FROM wp_postmeta WHERE post_id=%d AND meta_key='_thumbnail_id'",$post_id
    );
    $pic_id = intval($GLOBALS['wpdb']->get_var($query,0,0));
    if($pic_id > 0) {
        $query = $GLOBALS['wpdb']->prepare(
            "SELECT meta_value FROM wp_postmeta WHERE post_id=%d AND meta_key='_wp_attached_file'",$pic_id
        );
        $pic_path = $GLOBALS['wpdb']->get_var($query,0,0);
        if(!empty($pic_path)) {
            $display_pic = "/wp-content/uploads/" . $pic_path;
        }
    }

    //分類
    $terms = get_the_terms($post_id,'category');
    $term_html = '';
    $parent_term = '';
    if(isset($terms[0])){
        $first_term_name = get_term($terms[0]->term_id)->name;
        $term_url = get_category_link($terms[0]->term_id);
        if($terms[0]->parent != 0){
            $parent_term = get_term($terms[0]->parent);
            $parent_term_url = get_category_link($terms[0]->parent);
            $term_html =
            <<<HTML
                <!-- <li class="breadcrumb-item"><a href="#">首頁</a></li> -->
                <li class="breadcrumb-item"><a href="$parent_term_url">$parent_term->name</a></li>
                <li class="breadcrumb-item active" aria-current="page"><a href="$term_url">$first_term_name</a></li>
            HTML;
        }else{
            $term_html =
            <<<HTML
                <!-- <li class="breadcrumb-item"><a href="#">首頁</a></li> -->
                <li class="breadcrumb-item"><a href="$term_url">$first_term_name</a></li>
            HTML;
        }
    }

    // 去除 read-more
    $content = preg_replace("/<p class=\"read-more\">.*<\/p>/","",$content);
    return 
    <<<HTML
        <!--1筆資料-->
        <div class="listBlock-item">
            <div style="display:flex;flex-direction:column;overflow:hidden;width:100%;">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        {$term_html}
                    </ol>
                </nav>
                <a class="listBlock-item__left" href="{$post_link}">
                    <div class="txt-title text-bk-dark" >{$title}</div>
                    <div class="txt-inner text-bk-gray lh-15">{$content}</div>
                </a>
            </div>
            <a class="listBlock-item__right" href="{$post_link}">
                <img src="{$display_pic}" alt="">
                <div class="t14 text-bk-gray">{$post_time} | {$views}次觀看</div>
            </a>
        </div>
    HTML;
}