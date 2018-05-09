<form action="<?php echo route('users', 'postLogin');?>" method="post">
    <div class="field">
        <label class="label">Email</label>
        <div class="control has-icons-left has-icons-right">
            <input class="input" type="email" name="email" placeholder="youremail@">
            <span class="icon is-small is-left">
              <i class="fas fa-envelope"></i>
            </span>
        </div>

    </div>

    <div class="field">
        <label class="label">Password</label>
        <div class="control">
            <input class="input" type="password" name="password">
        </div>
    </div>

    <div class="field is-grouped">
        <div class="control">
            <button class="button" type="submit">Login</button>
        </div>
    </div>
</form>