# Username Protection

ClassicPress provides many helpful REST API endpoints that expose data about your site. For the most part, this is a handy feature. In the case of usernames, however, allowing this endpoint to be accessed anonymously can make your site more susceptible to brut force attacks. This plugin prevents anonymous access to the endpoint.

# Before Installing

**Do you even need this plugin?** To find out, log out of your site, then open the following URLs in separate browser tabs. Be sure to replace  **https://www.yoursite.com/** with the URL to your ClassicPress installation.

* https://www.yoursite.com/wp-json/wp/v2/users
* https://www.yoursite.com/wp-json/wp/v2/posts?_embed

Inspect the output of each URL. Do you find any _usernames_ **or** _display names_? If not, you're all set – you don't need this plugin! If you _do_ find usernames or display names, follow the instructions below to install the plugin.

# Installation

* Download the [package](https://github.com/johnalarcon/username-protection/archive/master.zip) to your local computer.
* Navigate to `Dashboard > Plugins > Add New > Upload Plugin` and upload the package to your site.
* Click to install, then activate the plugin.

# Usage

There are no configuration settings – the plugin is designed to _just work_. If you would like to personally verify that it is working as expected, you can log out of the site and revisit the URLs above. When you are logged out, the usernames and display names are removed; when you are logged in, they are accessible, as expected.

**NOTE**: While this plugin _does_ work with WordPress, it may cause certain aspects of JetPack to fail. See [this thread](https://twitter.com/CodePotent/status/1078044924052795392) for a workaround.
