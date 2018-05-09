<?php foreach ($posts as $postId => $post): if ($postId > 25) break; ?>
  <div class="card" style="margin-bottom: 50px;" data-post-id="<?php echo $post->id; ?>">
    <header class="card-header">
      <p class="card-header-title">
        <a href="<?php echo route('posts', 'show/' . $post->slug); ?>"><?php echo $post->title; ?></a>
      </p>
    </header>
    <div class="card-content">
      <div class="content">
        <?php $url = isset($post->image) ? $post->image : $post->video; ?>
        <?php echo isUrl($url, $post->type); ?>
        <?php if (isset($post->story)) : ?>
          <?php echo $post->story; ?>
        <? endif; ?>
        <br>
        <time datetime="2016-1-1">11:09 PM - 1 Jan 2016</time>
      </div>
    </div>
    <footer class="card-footer">
      <?php $like_action = isset($post->like_id) ? 'like-on' : 'like-off'; ?>
      <?php $like_total = isset($post->like_total) ? $post->like_total : ''; ?>
      <a href="#" data-object="post"
         class="like <?php echo $like_action; ?> card-footer-item"><?php echo $like_total; ?> Like</a>
      <a href="#" class="card-footer-item">Comment</a>
      <a href="#" class="card-footer-item">Share</a>
    </footer>
  </div>
<?php endforeach; ?>

