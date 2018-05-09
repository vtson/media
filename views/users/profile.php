<div class="box">
    <article class="media">
        <div class="media-left">
            <figure class="image is-128x128">
                <img src="<?php echo asset($user->avatar); ?>" alt="Image">
            </figure>
        </div>
        <div class="media-content">
            <div class="content">
                <strong><?php echo $user->username ?></strong>
            </div>
        </div>
    </article>
</div>
<?php require_once 'views/showpost.php'; ?>