{{#if_eq show_herc_posts_list "true"}}
    {{#if posts}}
        <br />
        <div class="hercules_posts_list_area">
            {{{pagination}}}
            {{#each posts}}
                <h2><a href="{{this.permalink}}">{{this.title}}</a></h2>
                {{#if_eq ../show_herc_posts_list_excerpt 'true'}}
                    <p>{{this.excerpt}} <a href="{{this.permalink}}">Read more</a></p>
                {{/if_eq}}
            {{/each}}
            {{{pagination}}}
        </div>
    {{/if}}
{{/if_eq}}