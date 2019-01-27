# Username Protection

ClassicPress provides many helpful REST API endpoints that expose data about your site. For the most part, this is a handy feature. In the case of usernames, however, allowing this endpoint to be accessed anonymously can make your site more susceptible to brut force attacks. This plugin prevents anonymous access to the endpoint.

# Usage
There are no configuration settings. To verify the plugin is working, do the following:

1. Before installing the plugin, navigate to `https://yoursite.com/wp-json/wp/v2/users`
2. If your usernames are NOT shown at the URL above, you don't need this plugin. If they ARE shown, continue.
3. Upload and install the plugin.
4. Log out of the site.
5. Refresh the page (from step 1). Usernames are now hidden because you're an anonymous user.
6. Log back in to the site.
7. Refresh the page (from step 1) again. Usernames are now shown because you are authenticated.

**NOTE**: While this plugin does work with WordPress, it may cause certain aspects of JetPack to fail. See [this thread](https://twitter.com/CodePotent/status/1078044924052795392) for information.
