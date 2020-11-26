# Laravel Model-Schema

Allows you to define your model's database schema in the model file itself. Field additions, changes, and deletions are automatically detected and applied, after running `php artisan model-schema:migrate`.

## Credit

This functionality was 95% lifted from [redbastie/swift](https://github.com/redbastie/swift#automatic-migrations). This stand-alone package changes some terminology and adds some minor additional functionality and tests.

## 🐉 Here Be Dragons: Indexes

>Laravel will automatically generate an index name based on the table, column names, and the index type, but you may pass a second argument to the method to specify the index name yourself

— [Creating Indexes](https://laravel.com/docs/8.x/migrations#creating-indexes)

If you plan on using this package in its current state, **it is best practice to always specify index names**. This is based on a couple of gotchas:

1. This package will generate tables prefixed by `temp_`, which Laravel will then use in the automatically generated index name, which will in-turn throw off the detection of changes to indexes.
2. (On MySQL, at least) Index names are limited to 64 characters, which can be easy to run in to when using the default index name generation, particulary on pivot tables, and further exacerbated by the `temp_` prefix.

A future update to this package may rely on using a parallel database so that prefixes are not required.