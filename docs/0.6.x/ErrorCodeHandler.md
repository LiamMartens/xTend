#The ErrorCodeHandler class and ErrorCode object
How to use and what to do with the `ErrorCodeHandler` class and the `ErrorCode` object

##Default application error codes
By default there are already 3 error codes present in `ErrorCodeHandler`. These are 0x0000 (0), 0x0001 (1) and 0x0194 (404). The codes 0 and 1 identify errors inside the `ErrorCodeHandler`, being `Invalid error code` and `Invalid error name` respectively. The code 0x0194 (404 in decimal) is the default code used for throwing 404 errors.

##Registering new error codes
You can always register new error codes to use throughout your xTend setup. For example if you want an error code for the situation where a user is not logged in. You want to give it decimal error code 165, thus the HEX code will be `0x00A5` (I have chosen to use HEX codes to identify errors but feel free to replace all codes with their decimal values). Next I will make a file inside the `System/Config` directory called `ErrorCodes.php`. There I'll add the following code:
```
	<?php
		namespace xTend
		{
			ErrorCodeHandler::RegisterErrorCode(0x00A5, 'user:not-logged-in', 'User is not authorized');
		}
```
This will register a new `ErrorCode` object for you to use.

##Finding errors and getting their exceptions
You can retrieve the instance of your registered `ErrorCode` object by using the `FindError` method of the `ErrorCodeHandler`. For example, if I would want to retrieve my previously created `user:not-logged-in` error I can use following code:
```
	ErrorCodeHandler::FindError(0x00A5); //returns ErrorCode object with code 0x00A5
	//or you can also use
	ErrorCodeHandler::FindError('user:not-logged-in') //returns the same 0x00A5 ErrorCode object
```
As you can see, you can retrieve by name or by code.
To get an `Exception` object from the `ErrorCode` you can call the method `getException()`. So in our case `ErrorCodeHandler::FindError(0x00A5)->getException();` will return a standard PHP exception object with a message and error code (in decimal).