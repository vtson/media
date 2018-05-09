<div class="comments">
    <div class="card" style="margin-bottom: 50px;" data-post-id="<?php echo $post->id; ?>">
        <header class="card-header">
            <p class="card-header-title">
                <?php echo $post->title; ?>
            </p>
        </header>
        <div class="card-content">
            <div class="content">
              <?php $url = isset($post->image) ? $post->image : $post->video; ?>
              <?php echo isUrl($url, $post->type); ?>
              <?php isset($post->story) ? $post->story : '';
              echo $post->story;
              ?>
                <br>
                <time datetime="2016-1-1">11:09 PM - 1 Jan 2016</time>
            </div>
        </div>
        <footer class="card-footer">
          <?php  $like_action = isset($post->like_id) ? 'like-on' : 'like-off'; ?>
          <?php $like_total = isset($post->like_total) ? $post->like_total : ''; ?>
            <a href="#" data-object="post"
               class="like <?php echo $like_action; ?> card-footer-item"><?php echo $like_total; ?> Like</a>
            <a href="#" class="card-footer-item">Share</a>
          <?php if ($post->user_id === Auth::user()->id) : ?>
              <a href="#" data-action="like" class="card-footer-item">Delete</a>
          <?php endif; ?>
        </footer>
    </div>
  <?php foreach ($parentComments as $parentComment) : ?>
      <article class="media media-parent">
          <figure class="media-left">
              <p class="image is-64x64">
                  <img src="<?php echo asset($parentComment->avatar); ?>">
              </p>
          </figure>
          <div class="media-content" data-user-id="<?php echo $parentComment->user_id; ?>"
               data-comment-id="<?php echo $parentComment->id; ?>"
          >
              <div class="content">
                  <p><a href="<?php echo route('users', 'profile/'.$parentComment->username); ?>" >
                      <strong><?php echo $parentComment->username; ?></strong></a>
                      <br>
                    <?php echo $parentComment->content; ?>
                      <br>
                    <?php $like_action = isset($parentComment->like_id) ? 'like-on' : 'like-off'; ?>
                    <?php $like_total = isset($parentComment->like_total) ? $parentComment->like_total : ''; ?>
                      <small><a href="#" class="like <?php echo $like_action; ?>"
                                data-object="comment"><?php echo $like_total; ?> Like</a> ·
                          <a href="#" class="reply-link">Reply</a> · 3 hrs
                      </small>
                  </p>
              </div>
              <div class="media-child">
                <?php  foreach ($childComments as $childComment) :
                  if ($childComment->parent_id === $parentComment->id) :
                    ?>
                      <article class="media">
                          <figure class="media-left">
                              <p class="image is-48x48">
                                  <img src="<?php echo asset($childComment->avatar); ?>">
                              </p>
                          </figure>
                          <div class="media-content" data-user-id="<?php echo $childComment->user_id; ?>"
                               data-comment-id="<?php echo $childComment->id; ?>"
                          >
                              <div class="content">
                                  <p><a href="<?php echo route('users', 'profile/'.$childComment->username); ?>" >
                                          <strong><?php echo $childComment->username; ?></strong></a>
                                      <br>
                                    <?php echo $childComment->content; ?>
                                      <br>
                                    <?php $like_action = isset($childComment->like_id) ? 'like-on' : 'like-off'; ?>
                                    <?php $like_total = isset($childComment->like_total) ? $childComment->like_total : ''; ?>
                                      <small><a href="#" class="like <?php echo $like_action; ?>"
                                                data-object="comment"><?php echo $like_total; ?> Like</a>
                                          ·
                                          <a href="#" class="reply-link">Reply</a> ·
                                          2
                                          hrs
                                      </small>
                                  </p>
                              </div>
                          </div>
                      </article>
                  <? endif; endforeach; ?>
              </div>
          </div>
      </article>
  <? endforeach; ?>
    <form class="comment-form" action="<?php echo route('comments', 'store'); ?>">
        <article class="media">
            <figure class="media-left">
                <p class="image is-64x64">
                    <img src="<?php echo asset(Auth::user()->avatar); ?>">
                </p>
            </figure>
            <div class="media-content">
                <div class="field">
                    <p class="control">
                        <textarea class="textarea" name="comment-content" id="comment-content"
                                  placeholder="Add a comment..."></textarea>
                    </p>
                </div>
                <div class="field">
                    <p class="control">
                        <button class="button" type="submit">Post comment</button>
                    </p>
                </div>
            </div>
        </article>
    </form>
</div>