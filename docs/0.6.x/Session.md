#Session
The Session class provides a more secure approach to the default `session_start`

##Configuration
You can call multiple configuration methods (a configuration file has already been created). 
###SessionName
By calling `Session::SessionName("{name-of-session}")` you can set which name is used for the session.
###InitiatedKey
By calling `Session::InitiatedKey("{key}")` you can set a secure key used for keeping the initiated session variable more securely and less predictable.
###UserAgentKey
The `Session::UserAgentKey` method works the same way the `InitiatedKey` method works and has the same effect only on a different session variable.
###Salt
The `Session::Salt("{salt}")` method sets the salt used for a less predictable signature.

##Methods
###Start
Starts the session
###Destroy
Destroys the session
