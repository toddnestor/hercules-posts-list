{{#if_eq show_herc_posts_list "true"}}
{{#if posts}}
    {{pagination}}
    {{#each posts}}
        <h2><a href="{{this.permalink}}">{{this.title}}</a></h2>
        {{#if_eq ../show_herc_posts_list_excerpt 'true'}}
            <p>{{this.excerpt}} <a href="{{this.permalink}}">Read more</a></p>
        {{/if_eq}}
    {{/each}}
    {{pagination}}
{{/if}}
{{/if_eq}}