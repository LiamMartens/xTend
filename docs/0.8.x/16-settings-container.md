#Settings container
If you want a general place to store variables you can use your application's `SettingsContainer`.

###Using the SettingsContainer
You can get your application's container by calling the `getSettingsContainer()` method. This handler has 2 methds you'll need being `get` and `set`. The both methods accept 2 parameters. In the `get` method you can pass the `key` and the default value to return and the `set` function expects a `key` and a `value`.
