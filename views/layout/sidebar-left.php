<nav class="panel" id="sidebar-left">
    <p class="panel-heading" style="background: rgba(0, 209, 178, 1);color: #ffffff;">
        Category
    </p>
  <?php $categories = categories(); ?>
  <?php foreach ($categories as $category) : ?>
      <a class="panel-block is-active" href="<?php echo route('home', 'show/' . $category->slug); ?>">
    <span class="panel-icon">
      <i class="fas fa-book" aria-hidden="true"></i>
    </span>
        <?php echo $category->name; ?>
      </a>
  <? endforeach; ?>
</nav>

