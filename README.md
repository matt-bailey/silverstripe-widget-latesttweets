#Latest Tweets SilverStripe widget

**This [SilverStripe](http://www.silverstripe.org/) widget shows the latest tweets from a named Twitter account.**

It makes use of [tmhOAuth](https://github.com/themattharris/tmhOAuth), an OAuth 1.0A library specifically for use with the Twitter API: https://github.com/themattharris/tmhOAuth

To use the widget you will also need to create a Twitter App: https://dev.twitter.com/apps

##Installation & Usage

Simply rename the folder to `widget_latesttweets` (that bit is important), drop it into the root directory of your SilverStripe site and run a `/dev/build`. You will then see the widget in your list of available widgets.

You will need the [SilverStripe Widgets module](https://github.com/silverstripe/silverstripe-widgets) installed and a widget area in your template.

Because the new Twitter API v 1.1 requires all calls to be authenticated you will also need to [create a Twitter app](https://dev.twitter.com/apps) in order to create the neccessary access tokens.

##Screenshots

**Front-end:**

![Latest tweets widget - Frontend screen grab](https://dl.dropbox.com/u/35123605/GitHub/latesttweets-frontend.gif)

**Admin:**

![Latest tweets widget - SilverStripe admin](https://dl.dropbox.com/u/35123605/GitHub/latesttweets-admin.gif)

## Requirements

* SilverStripe v3.*
* The [SilverStripe Widgets module](https://github.com/silverstripe/silverstripe-widgets).

##License
Feel free to use this as you like.
