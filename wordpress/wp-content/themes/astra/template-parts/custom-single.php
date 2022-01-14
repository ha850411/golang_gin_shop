<?php 

//the_post();
$post_id = get_the_ID();
$post = get_post($post_id);
$user_id = get_current_user_id();

//title
$title             = '';
$blog_post_title   = astra_get_option( 'blog-post-structure' );
$single_post_title = astra_get_option( 'blog-single-post-structure' );
if ( ( ( ! is_singular() && in_array( 'title-meta', $blog_post_title ) ) || ( is_single() && in_array( 'single-title-meta', $single_post_title ) ) || is_page() ) ) {
    if ( apply_filters( 'astra_the_title_enabled', true ) ) {
        $title  = astra_get_the_title( $post_id );
        $title = $title;
    }
}

//content
$content = get_the_content();
$content = apply_filters( 'the_content', $content );
$content = str_replace( ']]>', ']]&gt;', $content );

//描述匡
$excerpt = $post->post_excerpt;
$excerpt_html = '';
if($excerpt != ''){
    $excerpt_html = 
    <<<HTML
        <div class="descBox">
            {$excerpt}
        </div>
    HTML;
}

//tag
$tags = get_the_tags($post_id);
$tag_html = '';
if($tags){
    $tag_html .= "<p class='d-inline text-bk-dark me-3'>相關連結：</p>";
    $site_url = get_site_url();
    foreach($tags as $tag){
        $tag_url = $site_url.'/tag/'.$tag->name.'/';
        $tag_content = $tag->term_id.",'".$tag_url."'";
        $tag_html.= ' <div class="kmTag kmTag_blue"  onclick="clickTag( '.$tag_content.')">'.$tag->name.'</div>';
    }
}
//分類
$terms = get_the_terms($post_id,'category');
$first_term_name = '';
$link_html = '';
if(isset($terms[0])){
    $term = get_term($terms[0]->term_id);
    $first_term_name = $term->name;
    $home_url = get_site_url();
    if($term->parent != 0){
        $parent_term = get_category($term->parent);
        $parent_term_url = get_category_link($parent_term);
        $link_html = 
        <<<HTML
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="$home_url">首頁</a></li>
                    <li class="breadcrumb-item"><a href="$parent_term_url">$parent_term->name</a></li>
                    <li class="breadcrumb-item active" aria-current="page">$first_term_name</li>
                </ol>
            </nav>
        HTML;
    }else{
        $link_html = 
        <<<HTML
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="$home_url">首頁</a></li>
                    <li class="breadcrumb-item active" aria-current="page">$first_term_name</li>
                </ol>
            </nav>
        HTML;
    }
}
$term_url = get_category_link($terms[0]->term_id);

//結束日
$post_enddate = get_post_meta($post_id, '104_post_enddate', true);
$post_enddate_html = "";
if($post_enddate!=null){
    $post_enddate_html = "&nbsp;活動結束日：{$post_enddate}";
}

//收藏星星(心心)
$is_favorite = is_post_favorite($post_id,$user_id);
if($is_favorite){
    $star_html = "<div id=$post_id class='icon icon-heart-on mb-2' onclick='changeStar(this)'></div>";
}else{
    $star_html = "<div id=$post_id class='icon icon-heart-off mb-2' onclick='changeStar(this)'></div>";
}


//資料
$post_time = format_post_time($post_id);
$post_author = get_user_by( 'id', $post->post_author);

//我的收藏
$favorites_html = '';
$user_id = get_current_user_id();
$favorites = retrieve_favorite_posts($user_id);
$myFavoritesUrl = get_site_url().'/104-my-favorites';
$count = count($favorites);
if($count>2)$count = 2;

for($i=0;$i<$count;$i++){
    $favorite_data = get_post_list_box_datas($favorites[$i]);
    $bottom_html = $i == $count-1 ? '' : 'border-bottom';
    $favorites_html .= 
    <<<HTML
        <!--1筆資料-->
        <a href="$favorite_data->url" style='color: inherit;'>
            <div class="listItem $bottom_html">
                <div class="t14b text-blue-click mb-1">$favorite_data->term</div>
                <div class="cursorBox mb-2">
                    <div class="t20b text-bk-dark">$favorite_data->title</div>
                    <div class="t16 text-bk-gray line-2">
                        $favorite_data->content
                    </div>
                </div>
                <!-- <a href="#" class="t14 text-blue-click">https://km.104dc.com/</a> -->
            </div>
        </a>
    HTML;
}

//特選分類頁表
$particular_id = get_option('104_particular_term_2');
$particular_list_datas = (object)get_particular_term_list_datas($particular_id);
$particular_html = '';
$count = count($particular_list_datas->post_list);

if($count>4)$count = 4;
for($i=0;$i<$count;$i++){
    $bottom_html = $i == $count-1 ? '' : 'borderBottom';
    $particular_data = (object)$particular_list_datas->post_list[$i];
    $particular_html .= 
    <<<HTML
        <!--1筆資料-->
        <a href="$particular_data->url" style='color: inherit;'>
            <div class="listItem pb-0">
                <div class="t14-500 text-bk-gray mb-2">$particular_data->time</div>
                <div class="$bottom_html">
                    <div class="cursorBox t16b text-bk-dark line-3">
                        $particular_data->title
                    </div>
                </div>
            </div>
        </a>
    HTML;
}

echo 
<<<HTML
    <!doctype html>
        <html lang="en">
        <body>
            <div class="wrap-sm mb-4">
                {$link_html}
            </div>     
            <div class="wrap-sm d-flex pb-5">
                <div class="detail-left flex-grow-1">
                    <div class="label-title text-white t14b px-2 py-1" onclick="location.href='{$term_url}'">{$first_term_name}</div>{$post_enddate_html}
                    <!-- <a href="#" class="link-more">修改本篇文章 ></a> -->
                    <div class="t36b mt-2">
                        {$title}
                    </div>
                    <div class="py-3 d-flex align-items-center">
                        {$star_html}
                        <p class="t16 ps-2 text-bk-dark">加入收藏</p>
                        <p class="t16 ms-auto text-bk-dark">最後維護：{$post_time} | {$post_author->display_name}</p>
                    </div>
                    <!--描述框-->
                    {$excerpt_html}
                    <div class="detail-txt text-bk-deep t16 lh-188">
                        {$content}
                    </div>
                    <!--自定義圖文區塊-->
                    <!-- <div class="mb-4" style="background-color: #d8d8d8; height: 380px;"></div> -->
                    <!--相關連結-->
                    <div class="tagBox">
                        {$tag_html}
                    </div>
                </div>
                <div class="detail-right flex-shrink-0">
                    <div class="sidebar mb-3 pt-5 pb-3 px-4">
                        <div class="t24b text-blue-dark mb-2">我的收藏</div>
                        <a href="$myFavoritesUrl" class="link-more">看更多 ></a>
                        {$favorites_html}
                    </div>
                    <div class="sidebar pt-5 pb-3 px-4">
                        <div class="t24b text-blue-dark mb-2">$particular_list_datas->term_name</div>
                        <a href="$particular_list_datas->term_url" class="link-more">開啟方案 ></a>
                        {$particular_html}
                    </div>
                </div>
            </div>
            <!-- Optional JavaScript -->
            <!-- jQuery first, then Popper.js, then Bootstrap JS -->
            <!-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
                integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
                crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
                integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
                crossorigin="anonymous"></script> -->
        </body>

        </html>
HTML;