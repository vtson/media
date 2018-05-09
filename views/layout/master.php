<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hello Bulma!</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.0/css/bulma.min.css">
    <link rel="stylesheet" href="<?php echo ROOT_URL; ?>/resource/css/main.css">
</head>
<body>
<nav class="navbar is-transparent container">
    <div class="navbar-brand">
        <a class="navbar-item" href="<?php echo route('home', 'index'); ?>">
            <img src="https://bulma.io/images/bulma-logo.png"
                 alt="Bulma: a modern CSS framework based on Flexbox" width="112" height="28">
        </a>
        <div class="navbar-burger burger" data-target="navbarExampleTransparentExample">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>

    <div id="navbarExampleTransparentExample" class="navbar-menu">
        <div class="navbar-start">
            <a class="navbar-item" href="<?php echo route('home', 'show/image'); ?>">
                Image
            </a>
            <a class="navbar-item" href="<?php echo route('home', 'show/video'); ?>">
                Video
            </a>
            <a class="navbar-item" href="<?php echo route('home', 'show/story'); ?>">
                Story
            </a>
        </div>
    </div>

    <div class="navbar-end">
        <div class="navbar-item">
            <div class="field is-grouped">
              <?php if (Auth::checkLogin()) : ?>
                <?php if (Auth::user()->role == 'admin') : ?>
                      <p class="control">
                          <a class="button is-primary" href="<?php echo route('categories', 'create'); ?>">
                              <span>Create New Categories</span>
                          </a>
                      </p>
                <? endif; ?>
                  <p class="control">
                      <a class="button is-primary" href="<?php echo route('posts', 'create'); ?>">
                          <span>New Post</span>
                      </a>
                  </p>

                  <div class="dropdown">
                      <div class="dropdown-trigger">
                          <button class="button" aria-haspopup="true" aria-controls="dropdown-menu">
                              <span><?php echo Auth::user()->username; ?></span>
                              <span class="icon is-small">
                                    <i class="fas fa-angle-down" aria-hidden="true"></i>
                              </span>
                          </button>
                      </div>
                      <div class="dropdown-menu" id="dropdown-menu" role="menu">
                          <div class="dropdown-content">
                              <a class="dropdown-item" href="<?php echo route('users', 'profile/'.Auth::user()->username); ?>">
                                  <span>My Profile</span>
                              </a>
                              <a class="dropdown-item" href="<?php echo route('users', 'setting'); ?>">
                                  <span>Setting</span>
                              </a>
                                  <a class="dropdown-item" href="<?php echo route('users', 'logout'); ?>">
                                      <span>Logout</span>
                                  </a>
                          </div>
                      </div>
                  </div>
              <? else : ?>
                  <p class="control">
                      <a class="button is-primary" href="<?php echo route('users', 'create'); ?>">
                          <span>SignUp</span>
                      </a>
                  </p>
                  <p class="control">
                      <a class="button is-primary" href="<?php echo route('users', 'login'); ?>">
                          <span>Login</span>
                      </a>
                  </p>
              <? endif; ?>
            </div>
        </div>
    </div>
    </div>
</nav>
<section class="section">

    <div class="container">

      <?php
      if (isset($flashMessage) && array_key_exists('errors', $flashMessage)):
        ?>
          <div class="notification is-danger">
              <button class="delete"></button>
              <strong>Error!</strong>
              <ul>
                <?php foreach ($flashMessage['errors'] as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
              </ul>
          </div>
      <?php endif; ?>


      <?php
      if (isset($flashMessage) && array_key_exists('success', $flashMessage)):
        ?>
          <div class="notification is-success">
              <button class="delete"></button>
              <strong>Success!</strong>
              <ul>
                <?php foreach ($flashMessage['success'] as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
              </ul>
          </div>
      <?php endif; ?>

        <div class="columns">
            <div class="column is-3">
              <?php require_once 'views/layout/sidebar-left.php'; ?>
            </div>
            <div class="column is-6" id="main-content">
              <?php require_once $viewName; ?>
            </div>
            <div class="column is-3">
              <?php require_once 'views/layout/sidebar-right.php'; ?>
            </div>
        </div>

    </div>

</section>
<footer class="footer">
    <div class="container">
        <div class="content has-text-centered">
            <p>
                <strong>Bulma</strong> by <a href="https://jgthms.com">Jeremy Thomas</a>. The source code is licensed
                <a href="http://opensource.org/licenses/mit-license.php">MIT</a>. The website content
                is licensed <a href="http://creativecommons.org/licenses/by-nc-sa/4.0/">CC BY NC SA 4.0</a>.
            </p>
        </div>
    </div>
</footer>
<script defer src="https://use.fontawesome.com/releases/v5.0.7/js/all.js"></script>
<script
        src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
        crossorigin="anonymous"></script>

<script src="<?php echo ROOT_URL; ?>/resource/js/reviewUpload.js"></script>
<script src="<?php echo ROOT_URL; ?>/resource/js/main.js"></script>
<script src="http://bigspotteddog.github.io/ScrollToFixed/jquery-scrolltofixed-min.js" type="text/javascript"></script>
<script src="https://cdn.ckeditor.com/4.7.1/standard/ckeditor.js"></script>

<script>
    $(function () {
        $('#sidebar-left').scrollToFixed();
    });
</script>
</body>
</html>