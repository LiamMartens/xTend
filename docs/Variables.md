#Variables
##Config or Variables?
In xTend there is also a Config class defined in the `/System/Config/Core.php` file which contains some very necessary configuration variables.
These variables are defined as constants so if you can't use `const` for the variable you want to save, when it can change or when you use some functions to generate the actual value, you have to use the `Variables` class.

##More on
In the file `/System/Config/Variables.php` there are some predefined variables such as `app.root`, `app.system` and `app.web`. You should not remove these variables since they are used by the `Dir` and `File` core classes but you can edit the `app.web` one to define your public directory (in fact you should when you rename the public directory)

###Usage of this class should be pretty straight forward after taking a look at the `/System/Config/Variables.php` file