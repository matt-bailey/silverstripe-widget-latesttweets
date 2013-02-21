<% if LatestTweets %>
<div class="widget latest-tweets-widget">
    <ul class="latest-tweets">
        <% control LatestTweets %>
        <li class="tweet">
            <div class="tweet-pic">
                <a href="http://twitter.com/$user.screen_name" title="View this user profile on Twitter">
                    <img src="$user.profile_image_url" alt="$user.name"/>
                </a>
            </div>
            <p class="tweet-text">$text</p>
            <p class="tweet-date"><a href="http://twitter.com/$user.screen_name/statuses/$id_str" title="View this tweet on Twitter">$created_at</a></p>
        </li>
        <% end_control %>
    </ul>
</div>
<% end_if %>
