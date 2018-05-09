<nav class="panel" id="sidebar-right">

    <div class="card" id="side-head-right">
        <p class="panel-heading" style="background: rgba(0, 209, 178, 1);color: #ffffff;">
            Story
        </p>
    </div>
    <div style="margin-bottom: 20px;"></div>
  <?php $stories = stories();?>
  <?php foreach ($stories as $story) : ?>
    <div class="card">
        <div class="card-header">
            <p class="card-header-title">
                <a href="<?php echo route('posts', 'show/' . $story->slug); ?>"><?php echo $story->title; ?></a>
            </p>
        </div>

        <div class="card-content">
            <div class="content content-right">
                <?php echo $story->story ;?>
            </div>
        </div>
    </div>
    <div style="margin-bottom: 20px;"></div>
  <?php endforeach; ?>
</nav>