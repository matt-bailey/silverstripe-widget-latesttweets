<?php
/**
 * Latest Tweets Widget
 *
 * This widget uses tmhOAuth, an OAuth 1.0A library specifically for use with
 * the Twitter API: https://github.com/themattharris/tmhOAuth
 */

class LatestTweetsWidget extends Widget {

    static $title = "Latest Tweets";
    static $cmsTitle = "Latest Tweets Widget";
    static $description = "<strong>This widget shows your latest tweets.</strong><br><strong>Note:</strong> You need to create a Twitter App in order to use this widget <a href='https://dev.twitter.com/apps'>https://dev.twitter.com/apps</a>";

    static $db = array(
        "WidgetTitle" => "Varchar(255)",
        "ConsumerKey" => "Varchar(255)",
        "ConsumerSecret" => "Varchar(255)",
        "AccessToken" => "Varchar(255)",
        "AccessTokenSecret" => "Varchar(255)",
        "Username" => "Varchar(255)",
        "Count" => "Int"
    );

    static $defaults = array(
        "WidgetTitle" => "Latest Tweets",
        "Username" => "twitter",
        "Count" => "5"
    );

    function getCMSFields(){
        return new FieldList(
            new TextField("WidgetTitle", "Widget Title"),
            new TextField("ConsumerKey", "Consumer Key"),
            new TextField("ConsumerSecret", "Consumer Secret"),
            new TextField("AccessToken", "Access Token"),
            new TextField("AccessTokenSecret", "Access Token Secret"),
            new TextField("Username", "Twitter Username"),
            new TextField("Count", "Tweet Count")
        );
    }

    // Override default title value to use WidgetTitle value in template
    public function Title() {
        return $this->WidgetTitle ? $this->WidgetTitle : self::$title;
    }

    /**
     * Function to convert links, mentions and hashtags: http://goo.gl/ciKGs
     */
    function tweetConvert($tweet_string) {
        $tweet_string = preg_replace("/((http(s?):\/\/)|(www\.))([\w\.]+)([a-zA-Z0-9?&%.;:\/=+_-]+)/i", "<a href='http$3://$4$5$6' target='_blank'>$2$4$5$6</a>", $tweet_string);
        $tweet_string = preg_replace("/(?<=\A|[^A-Za-z0-9_])@([A-Za-z0-9_]+)(?=\Z|[^A-Za-z0-9_])/", "<a href='http://twitter.com/$1' target='_blank'>$0</a>", $tweet_string);
        $tweet_string = preg_replace("/(?<=\A|[^A-Za-z0-9_])#([A-Za-z0-9_]+)(?=\Z|[^A-Za-z0-9_])/", "<a href='http://twitter.com/search?q=%23$1' target='_blank'>$0</a>", $tweet_string);
        return $tweet_string;
    }

    /**
     * Relative date function: http://goo.gl/pDzmV
     */
    function relativeDate($time, $AllValues = true) {
        // The elapsed amount of time in seconds (integers only please!)
        $elapsed = time() - floor($time);
        // Is there any real difference?
        if ($elapsed < 1) { return '0 seconds'; }
        // Setup an array of all possible time differences to check against
        $times = array (
            12 * 30 * 24 * 60 * 60 => 'year',
            30 * 24 * 60 * 60      => 'month',
            07 * 24 * 60 * 60      => 'week',
            24 * 60 * 60           => 'day',
            60 * 60                => 'hour',
            60                     => 'minute',
            1                      => 'second' );
        // Setup a return string
        $returned = '';
        // Loop through all of the time "constants"
        foreach ($times AS $seconds => $string) {
            // Get the difference
            $difference = floor($elapsed / $seconds);
            // Is there an actual (positive) difference?
            if ($difference >= 1) {
                // Add this difference to the return string. Will use a
                // pluralization sub-function if available. Modify as desired.
                if (function_exists('IsPlural'))
                { $returned .= " $difference " . IsPlural($difference, $string) . ","; }
                else
                { $returned .= " $difference $string" . ($difference > 1 ? 's' : '') . ','; }
                // Should we continue adding all possible differences?
                if (!$AllValues) { break; }
                // Subtract this difference from the total elapsed for the next loop
                $elapsed -= $difference * $seconds;
            }
        }
        // Strip the first space and final comma from the string before returning it
        return substr($returned, 1, -1) . ' ago';
    }

    function getLatestTweets() {

        // Load tmhOAuth
        require(Director::baseFolder() . '/widget_latesttweets/libs/tmhOAuth.php');
        require(Director::baseFolder() . '/widget_latesttweets/libs/tmhUtilities.php');

        // Load widget css
        Requirements::css("widget_latesttweets/css/latestTweetsWidget.css");

        $tmhOAuth = new tmhOAuth(array(
            'consumer_key' => $this->ConsumerKey,
            'consumer_secret' => $this->ConsumerSecret,
            'user_token' => $this->AccessToken,
            'user_secret' => $this->AccessTokenSecret,
            'curl_ssl_verifypeer' => false
        ));

        $code = $tmhOAuth->request('GET', $tmhOAuth->url('1.1/statuses/user_timeline'), array(
            'screen_name' => $this->Username,
            'count' => $this->Count
        ));

        $response = $tmhOAuth->response['response'];
        $tweets = json_decode($response, true);
        $output = new ArrayList();
        foreach ($tweets as &$tweet) {
            $tweet['text'] = $this->tweetConvert($tweet['text']);
            //$tweet['created_at'] = date("jS F Y", strtotime($tweet['created_at']));
            $tweet['created_at'] = $this->relativeDate(strtotime($tweet['created_at']), false);
            $output->push(new ArrayData($tweet));
        }
        return $output;

    }

}
