# xORM
xORM is the small and simple built-in PDO ORM wrapper.
It boasts most features you might need for database interaction.

### Configuration
To use xORM you have to configure it to connect to the right database. A default config file is located
at `Config/xORM/Config.php` and out of the box, xORM supports MySQL and SQLite.

*Notice It shouldn't be too hard to add another driver to the xORM class in `Core/xORM`, but if you want me to add one by default you can request it*

### Selecting records
You can select records using your application's orm instance (fetched using `$app->orm()`).

#### Starting a select
To start a select you can call `select` on xORM. The function accepts 2 parameters at most.
The first one is the column name, however this can also be an array of 2 with the second
one being the alias. The second parameter is a boolean determining whether this column is
the primary key. This is only important if you want to use the result of your select statement
to save or delete the record again.

```
    // SELECT username
    // where username is considered the PRIMARY KEY column
    $app->orm()->select('username', true);

    //SELECT email AS mail
    $app->orm()->select(['email', 'mail']);
``` 

#### Setting the primary key afterwards
Instead of setting the primary key directly you can also use a statement to
do so afterwards.

```
    $app->orm()->select('username')
                ->primary('username');
```

#### From what table?
You always have to define the from clause of your statement by using the `from()` method.

```
    $app->orm()->select('*')
                ->from('users');
```

#### Making it DISTINCT
To make the select statement DISTINCT you can simply use `distinct()`.

```
    $app->orm()->select('*')
                ->from('users')
                ->distinct();
```

#### Adding WHERE clauses
You can use the where methods to add where clauses to your statement. There are 
multiple to choose from. (`where`, `whereGt`, `whereLt`, `whereLike`, `whereIn`, `whereBetween`, `orWhere`, `orWhereGt`, `orWhereLt`, `orWhereLike`, `orWhereIn`, `orWhereBetween`).
All where clauses are chained after one another with the `AND` statements being first.
```
    $app->orm()->select('*')
                ->from('users')
                ->where('username', 'user')
                ->whereGt('reputation', 100)
                ->orWhere('role', 'admin');
```
*Notice This will result in following query, `SELECT * FROM users WHERE username='user' AND reputation>100 OR role='admin'`*

#### Wrapping WHERE clauses
You can also wrap where statements together using the `wrap()` method.
```
    $app->orm()->select('*')
                ->from('users')
                ->wrap(function($query) {
                    $query->where('username', 'user');
                    $query->whereGt('reputation', 100);
                })
                ->orWrap(function($query) {
                    $query->where('role', 'user');
                    $query->whereGt('reputation', 200);
                })
                ->orWhere('role', 'admin');
```
*Notice this will result in following query, `SELECT * FROM users WHERE (username='user' AND reputation>100) OR (role='user' AND reputation>200) OR role='admin'`*

#### Ordering and grouping
`ORDER BY` and `GROUP BY` are both supported as well.
```
    $app->orm()->select('*')
                ->from('users')
                ->orderAsc('username')
                ->group('country');
    // there is also orderDesc
```

*Notice this will result in following query, `SELECT * FROM users ORDER BY username ASC GROUP BY country`*

#### Limit and offset
Use the `limit` and `offset` methods to set the SQL LIMIT and OFFSET parameters.
```
    $app->orm()->select('*')
                ->from('users')
                ->limit(10)
                ->offset(20);
```

*Notice this will result in following query, `SELECT * FROM users LIMIT 10 OFFSET 20`*

#### Joining
All major joining options are available (`INNER JOIN`, `LEFT JOIN`, `LEFT OUTER JOIN`, `RIGHT JOIN`, `RIGHT OUTER JOIN`, `FULL OUTER JOIN`).
You can use the `join`, `leftJoin`, `leftOuterJoin`, `rightJoin`, `rightOuterJoin` and `fullJoin` or `fullOuterJoin` methods respectively.
```
    $app->orm()->select('users.*')
                ->from('users')
                ->join('posts', [
                    'posts.user_id', '=', 'users.id'
                ], 'user_posts');
    // Other join methods are used the same
    // The last parameter, the AS alias, is optional 
```

*Notice this will result in following query, `SELECT users.* FROM users INNER JOIN posts AS user_posts ON posts.user_id=users.id`*

#### SQL functions
There is support for several SQL functions, these are
`avg`, `count`, `max`, `min`, `sum`, `len`, `upper` and `lower`.
All methods accept 2 parameters, the first one being the column
to execute the function on and the second is an optional alias parameter.
```
    $app->orm()->select('score')
                ->from('game')
                ->avg('score', 'avg_score');
    // You don't HAVE to pass the alias in the function method
    // As shown below
    $app->orm()->select(['score', 'avg_score'])
                ->from('game')
                ->avg('score');
```

*Notice both statements will result in following query, `SELECT AVG(score) AS avg_score FROM game`*

#### Executing your SELECT statement
There are 2 methods you can use to fetch your results, these
are `findOne` and `findMany`. 

#### Dealing with results
The `findOne` method will return a `ResultObject` while the `findMany`
method returns an array of ResultObjects. Below you will find more info
about the `ResultObject`.

### The ResultObject
#### Accessing members
You can access a ResultObject's members by using array syntax
or by using the arrow pointer. (You can also set members this way).

#### Saving a record
To save a record you can use the `save` method, but keep in mind
you'll have to set the primary key when selecting the record (otherwise
the default primary key column name `id` is used).

#### Deleting a record
Deleting a record is done with the `delete` method. When deleting
records you will also have to set the primary key otherwise there
is no reference to the record (or the default `id` column is used)

*Notice using models omits the need to set the primary key for every select*

#### Example
Let's say we have a record in our database with 3 fields being
user_id with a value of 5, username with a value of 'liammartens' and
email with a value of 'example@domain.com'
```
    $user = $app->orm()->select('user_id')
                ->select('username')
                ->select('email')
                ->primary('user_id')
                ->from('users')
                ->where('id', 5)
                ->findOne();
    echo $user->email;
    echo $user['username'];
    $user->username = 'liam';
    $user->save();
```
This will output
```
    liammartens
    example@domain.com
```
And the record will have been updated in the database
with it's new username.

### Transaction
xORM has a simple transaction function called `transaction`.
```
    $app->orm()->transaction(function() {
        // your code
        return true|false
    });
```
When your function returns true, xORM will `commit` the transaction,
otherwise xORM will `rollBack` the transaction.

### Creating a record
Use xORM's `create` method to create a new record. The method accepts
a max of 3 parameters being the name of the table, an associative array
with values and the primary key column. Only the name of the table is
required. The method returns a `ResultObject` so you can always set
values afterwards.

Lastly to insert the record call the `insert` method.

```
    $app->orm()->create('users', [
        'username' => 'liam'
    ])->insert();
```

### Executing a raw query
If the built in methods don't suit your needs you can also
use a raw SQL query.
```
    $app->orm()->raw('SELECT username AS un FROM users WHERE username=?', [ 'liam' ])
                ->table('users')
                ->primary('user_id')
                ->alias('username', 'un)
                ->findOne();
```
As you can see the `findOne` method can also be used using a 
raw query (as well as `findMany`). Both will return ResultObjects,
however if you want to use the ResultObject to update a record you
have to also set the table name, the primary key and if necessary
all aliases you have used in the query.

### Using an xORM model
The `BaseDataModel` can be used to use models with database interaction
using the built-in xORM. When extending the model there are 2 protected
members you can set to configure your model and there are 2 methods you
can use.

#### Protected member $_table
By default the table has a value of `false`. In this case xORM
will lowercase your class name and add an 's' for pluralization.

#### Protected member $_id_column
By default the id column member has a value of `id`. You can set
this to your own primary key column name for updating records.

#### select method
The select method in your model is analog to xORM's select method,
however you don't have to use `from` anymore and you won't have to
set the primary key.

#### create method
The create method in your model is analog to xORM's create method,
but you won't have to specify the table name and the primary key.