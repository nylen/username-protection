# Username Protection

There are many vectors for hackers to discover your site's usernames – the starting point of a brute-force attack. Showing usernames is not a security risk, in itself – it becomes a risk when even a single user has a weak password. Usernames are exposed by the REST API, feeds, author archives, and more. This plugin locks them down without disabling any core functionality. In addition, because _usernames_ can often be extrapolated from _display names_, those are also protected.

# Installation

* Download the [package](https://github.com/johnalarcon/username-protection/archive/master.zip) to your local computer.
* Navigate to `Dashboard > Plugins > Add New > Upload Plugin` and upload the package to your site.
* Click to install, then activate the plugin.

# Usage

There are no configuration settings – the plugin is designed to _just work_. If you would like to personally verify that it is working as expected, you can log out of your site and you'll find that usernames and display names have magically vanished. The usernames and display names will be shown to all users who are logged in, to maintain the expected user experience.

# Filters

The plugin replaces usernames and display names with texts that should make sense in 99% of cases. If the texts don't suit your site design, you can use the built-in filters to customize them. Please see [this gist](https://gist.github.com/johnalarcon/ef83d7dd6ba11ac92795d2fa8256c43f) for examples of each.

# Contributing

Issue reports and pull requests are welcome – please submit your pulls against the [develop branch](https://github.com/johnalarcon/username-protection/tree/develop).

**NOTE**: While this plugin _does_ work with WordPress, it may cause certain aspects of JetPack to fail. See [this thread](https://twitter.com/CodePotent/status/1078044924052795392) for a workaround.
