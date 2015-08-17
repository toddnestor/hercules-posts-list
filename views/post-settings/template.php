<div class="herc-bootstrap" id="herc_posts_list">
    <div class="checkbox">
        <label>
            <input type="hidden" name="show_herc_posts_list" value="false" />
            <input type="checkbox" name="show_herc_posts_list" value="true" {{#if show_posts_list}}checked{{/if}} /> Show
        </label>
    </div>
    <div class="form-group">
        <label for="posts_to_show">Posts to Show</label>
        <input type="number" name="posts_to_show" class="form-control" id="posts_to_show" min="0" placeholder="Number of Posts to Show">
    </div>
    <div class="form-group">
        <label for="posts_to_show">Choose Category</label>
        <select class="form-control" id="post_category">
            {{#each categories}}
            <option value="{{this.id}}">{{this.name}}</option>
            {{/each}}
        </select>
    </div>
    <div class="checkbox">
        <label>
            <input type="hidden" name="show_herc_posts_list_excerpt" value="false" />
            <input type="checkbox" name="show_herc_posts_list_excerpt" value="true" {{#if show_excerpt}}checked{{/if}} /> Show
        </label>
    </div>
    <div class="form-group">
        <label for="posts_to_show">Max Excerpt Length (number of words)</label>
        <input type="number" class="form-control" id="posts_to_show" min="0" placeholder="Max length of excerpt">
    </div>
</div>