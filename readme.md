# LaravelReverseRelation
Reverse relation for laravel eloquent.
We define one to one and one to many relations. We often want to get the reverse relation which means
we should query from database. And this is unnecessary.Because we maybe already get the data.
## 安装

1. 修改composer.json
```json
{
    "require":
    {
        "tusimo/laravel-reverse-relation": "^0.1"
    }
}
```

2. 修改config/app.php
```php
<?php
return [
    'providers' => [
        /*
         * Package Service Providers...
         */
        \Tusimo\ReverseRelation\ReverseRelationProvider::class,
    ]
];
```

## 使用
before:
```php
class User extends Model {
    use \Tusimo\ReverseRelation\Traits\ReverseRelation;

    public function books ()
    {
        return $this->hasMany(Book::class);
    }
}

class Book extends Model {
    public function user ()
    {
        return $this->>belongsTo(User::class);
    }
}
$books = User::with('books')->first();
dd($books->first()->user);//we maybe use like this way.this will be a sql query for db.
```
after:
```php
class User extends Model {
    use \Tusimo\ReverseRelation\Traits\ReverseRelation;

    public function books ()
    {
        return $this->hasMany(Book::class)->withReverse('user');
    }
}

class Book extends Model {
    public function user ()
    {
        return $this->>belongsTo(User::class);
    }
}
$books = User::with('books')->first();
dd($books->first()->user);//this time there will be no sql for db because we have already know.
```
## support
also support for [tusimo/embed-relation](https://github.com/tusimo/embed-relation)
which is a new relation for laravel.