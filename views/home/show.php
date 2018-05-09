<?php require_once 'views/showpost.php'; ?>
<nav class="pagination is-rounded" role="navigation" aria-label="pagination">
  <a class="pagination-previous">Previous</a>
  <a class="pagination-next">Next page</a>
  <ul class="pagination-list">
    <?php $page = 0; ?>
    <?php for ($i = 1; $i < $count->total; $i += $limit) :
      $page += 1;
      ?>
      <li><a href="<?php echo route('home', 'show/' . $pageName . '?page=' . $page); ?>"
             class="pagination-link" aria-label="Goto page "><?php echo $page; ?></a>
      </li>
    <? endfor; ?>
  </ul>
</nav>
