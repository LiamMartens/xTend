#Crypt
More info on how to use the encryption class.

##Create
You can use the `Create` method to encrypt. Just supply some data and an encryption key and it will return encrypted data. 
example use: `Crypt::Create('data','key');`.

##Solve
The `Solve` parameter also expects data and a key but will return the decrypted data for you.
example use: `Crypt::Solve('data','key');`.