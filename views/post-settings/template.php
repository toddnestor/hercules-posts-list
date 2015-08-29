<div class="herc-bootstrap" id="herc_posts_list">
    <div class="checkbox">
        <label>
            <input type="hidden" name="show_herc_posts_list" value="false" />
            <input type="checkbox" name="show_herc_posts_list" value="true" {{#if_eq show_herc_posts_list "true"}}checked{{/if_eq}} /> Show
        </label>
    </div>
    <div class="form-group">
        <label for="posts_to_show">Posts to Show</label>
        <input type="number" name="posts_to_show" class="form-control" id="posts_to_show" min="0" placeholder="Number of Posts to Show" value="{{posts_to_show}}">
    </div>
    <div class="form-group">
        <label for="post_category">Choose Category</label>
        <select class="form-control" id="post_category" name="post_category">
            {{#each categories}}
            <option value="{{this.id}}" {{#if_eq this.id ../post_category}}selected{{/if_eq}}>{{this.name}}</option>
            {{/each}}
        </select>
    </div>
    <div class="checkbox">
        <label>
            <input type="hidden" name="show_herc_posts_list_excerpt" value="false" />
            <input type="checkbox" name="show_herc_posts_list_excerpt" value="true" {{#if show_herc_posts_list_excerpt}}checked{{/if}} /> Show
        </label>
    </div>
    <div class="form-group">
        <label for="max_excerpt_length">Max Excerpt Length (number of words)</label>
        <input type="number" class="form-control" id="max_excerpt_length" min="0" placeholder="Max length of excerpt" name="max_excerpt_length" value="{{max_excerpt_length}}">
    </div>
</div>