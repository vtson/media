<form action="<?php echo route('categories', 'store');?>" method="post">
    <div class="field">
        <label for="name">Name of Category</label>
        <p class="control ">
            <input class="input" type="text" name="name">
        </p>
    </div>
    <div class="field">
        <label for="description">Description</label>
        <p class="control ">
            <input class="input" type="text" name="description">
        </p>
    </div>
    <div class="field">
        <p class="control">
            <button class="button is-success" type="submit">
                Create
            </button>
        </p>
    </div>
</form>