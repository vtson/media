<form action="<?php echo route('posts', 'store'); ?>" method="post" enctype="multipart/form-data">
    <div class="field">
        <label for="title">Give your post a title</label>
        <p class="control ">
            <input class="input" type="text" name="title">
        </p>
    </div>

    <div class="tabs is-toggle is-fullwidth" id="tabs">
        <ul>
            <li class="is-active" id="file">
                <a>
                    <span class="icon is-small"><i class="fa fa-file-image"></i></span>
                    <span>Upload Video/Image</span>
                </a>
            </li>
            <li id="url">
                <a>
                    <span class="icon is-small"><i class="fa fa-globe"></i></span>
                    <span>Paste Url</span>
                </a>
            </li>
            <li id="story">
                <a>
                    <span class="icon is-small"><i class="fa fa-newspaper"></i></span>
                    <span>New Story</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="field">
        <img hidden class="reviewImageUpload" src="#" alt="image"/>
        <video hidden class="reviewVideoUpload" src="#">
            Your browser does not support HTML5 video.
        </video>
    </div>
    <div class="field" id="tabs-content">
        <?php require_once "views/posts/partitial/post-file.php"?>
    </div>
    <div class="field" >
        <div class="control">
            <div class="select is-primary">
                <select name="category_id">
                    <option value="0">Please select a section below</option>
                  <?php $categories = categories(); ?>
                  <?php foreach ($categories as $category) : ?>
                      <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                  <? endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <div class="field">
        <p class="control">
            <button class="button is-success" type="submit">
                Create
            </button>
        </p>
    </div>
</form>