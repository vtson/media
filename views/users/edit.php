<form action="<?php echo route('users', 'update'); ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $userModel->id; ?>">
    <div class="field columns">
        <div class="column is-3" >
            <figure class="image is-128x128 ">
                <img class="reviewImageUpload" src="<?php echo asset($profile->avatar); ?>" alt="user avatar">
            </figure>
        </div>
        <div class="column">
            <label class="field-label">
                <input class="file-input" value="<?php echo $profile->avatar; ?>" type="file" name="avatar"
                       placeholder="Avatar" accept='image/*'>
                <span class="file-cta">
              <span class="file-icon">
                <i class="fas fa-upload"></i>
              </span>
              <span class="file-label">
                Choose a file…
              </span>
            </span>
            </label>
        </div>
    </div>

    <div class="field">
        <label class="label">Your full name</label>
        <div class="control">
            <input class="input" value="<?php echo $userModel->fullname; ?>" type="text" name="fullname"
                   placeholder="Full name">
        </div>
    </div>

    <div class="field">
        <label class="label">User name</label>
        <div class="control has-icons-left has-icons-right">
            <input class="input" type="text" value="<?php echo $userModel->username; ?>" disabled
                   placeholder="User name">
            <span class="icon is-small is-left">
              <i class="fas fa-user"></i>
            </span>
        </div>
    </div>

    <div class="field">
        <label class="label">Email</label>
        <div class="control has-icons-left has-icons-right">
            <input class="input" type="text" value="<?php echo $userModel->email; ?>" disabled placeholder="@">
            <span class="icon is-small is-left">
              <i class="fas fa-envelope"></i>
            </span>
        </div>
    </div>

    <div class="field">
        <label class="label">Mobile</label>
        <div class="control">
            <input class="input" value="<?php echo $profile->mobile; ?>" type="text" name="mobile" placeholder="Mobile">
        </div>
    </div>

    <div class="field">
        <label class="label">Birth of Date</label>
        <div class="control">
            <input class="input" value="<?php echo $profile->bod; ?>" type="date" name="bod" placeholder="Bod">
        </div>
    </div>

    <div class="field is-grouped">
        <div class="control">
            <button class="button is-link" type="submit">Update</button>
        </div>
    </div>
</form>