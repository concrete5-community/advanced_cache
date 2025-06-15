# Advanced Cache

This add-on is for (theme) developers only. It comes with an advanced caching wrapper to support caching of PHP data, hard-coded blocks, and areas.

Use it when:

*   You have Full Page Caching disabled on one or more pages.
*   You are a PHP developer or a theme designer.
*   You want to cache data, hard coded blocks, or (global) areas.

## Code examples

1\. Example of infinite caching of autonav 'breadcrumbs' block:

```php
$bt = BlockType::getByHandle('autonav');
$bt->controller->orderBy = 'display_asc';
$bt->controller->displayPages = 'top';
$bt->controller->displaySubPages = 'relevant_breadcrumb';
$bt->controller->displaySubPageLevels = 'all';

// The breadcrumbs are different on every page
$cacher = \A3020\AdvancedCache\Facade\AdvancedCache::make('breadcrumbs');
$cacher
    ->settings()
    ->setDiffersPerPage();

echo $cacher->renderBlock($bt, 'templates/breadcrumb');
```

2\. Example of **automatically** **flushing** a blocks' cache when pages of the **Blog** page type change:

```php
$bt = BlockType::getByHandle('next_previous');
$bt->controller->orderBy = 'display_asc';

/** @var \A3020\AdvancedCache\Cacher $cacher */
$cacher = AdvancedCache::make('next_previous_addon');
$cacher
    ->settings()
    ->setDiffersPerPage()
    
    // Hook into events like 'on_page_delete'
    // Automatically invalidates the cache e.g. if a page of
    // page type 'blog' is added, updated, or deleted.
    ->addFlushRule((new FlushOnPageActions())->onlyForPageTypes(['blog']))

echo $cacher->renderBlock($bt, 'templates/addons');
```

3\. Example of only using a cached block only in **certain conditions**:

```php
$cacher = AdvancedCache::make('custom_block');
$cacher
    ->settings()
    ->onlyCacheIf(function() {
        $user = new \Concrete\Core\User\User();

        // Do not cache the block for users who are logged in.
        return $user->isRegistered() === false;
    });

echo $cacher->renderBlock(BlockType::getByHandle('custom_block'));
```

4\. Example of how to automatically expire cache items:

```php
$cacher = AdvancedCache::make('a_unique_handle');
$cacher
    ->settings()
    ->setExpiresIn(3600) // Cache for one hour

echo $cacher->renderBlock($aBlockType);
```

5\. Invalidating a cache entry:

```php
// The facade passes the invalidate method to the AdvancedCacheService class.
AdvancedCache::invalidate('header_autonav');
```

6\. Cache the output of a (Global) Area:

```php
$ga = new GlobalArea('Footer - Copyright');

$cacher = \A3020\AdvancedCache\Facade\AdvancedCache::make('area_footer_copyright');
echo $cacher->renderArea($ga);
```

7\. Cache strings / arrays / serializable objects:

```php
$cacher = AdvancedCache::make('json_from_api');
$data = $cacher->cache([
    'id' => 1,
    'name' => 'A3020',
]);

var_dump($data);

// array (size=2)
//   'id' => int 1
//   'name' => string 'A3020' (length=5)
```

## FAQ

**How does this make my website faster?**  
Say you render a menu with the **autonav** block with tens of pages, then you end up with hundreds of queries. E.g. because for each ````$page->getAttribute('...')```` a query is executed. If you do profiling with e.g. Blackfire, you'll notice that the autonav is a very resource intensive block. If you can cache its output, you should definitely do. Once a cache file exists, it's just a matter of grabbing a file (either from disk or memory), and return it. That could very well limit the execution of that block from e.g. 300ms to 30ms.

**What happens with 'on\_start' or 'registerViewAssets' methods?**  
First of all, those methods might be used to make sure certain assets (e.g. a JavaScript file) are included. Well, these methods will only be called if a block is not cached. Once a block is cached, the methods won't be called anymore. If you are using block types that require assets, I highly recommend using '[providesAsset](https://documentation.concretecms.org/developers/pages-themes/working-themes/advanced-css-and-javascript-usage/overriding-or-providing-core-javascript-or-css-in-a-theme)' in your page\_theme.php file.

## FAQ - Usage

**When do I need to cache a block?**  
Hard-coding blocks in your template is often very convenient. However, they will never be cached, because blocks are not attached to a page. This add-on works as a cache wrapper that basically stores the output of a block for x-amount of time in the cache.

**When do I need to cache a (global) area?**  
In case the blocks you have added in your area are not cached by default, but you want to cache them. Maybe only in certain conditions, e.g. if a user is not logged in, or a user is not in a certain group. **Conditional caching** is made possible with Advanced Cache.

**When do I need to cache data?**  
You may use Advanced Cache to cache strings, arrays, and serializable objects. It acts as a convenient wrapper around the expensive cache. E.g. say you make a request to another server to get a JSON response, you can cache that JSON response for x-amount of time.

**What's the deal with the autonav block?**  
First of all, it's a very resource-intensive block. It does lots of queries and loops to generate a menu. Something you might want to avoid on every page request! By default, its output is only cached if you are not logged in. Once you are logged in, it will regenerate the navigation on each page request. With Advanced Cache you can cache its output globally, per user, or per page, or both.

**How can I define how long a cache entry should be cached?**  
The TTL (Time To Live) can be set via ````$settings->setExpiresIn(3600)````. The value is in seconds. By default, all cache entries are stored indefinitely.

**What is a 'handle'?**  
It's a unique identifier that is used to save in the cache file. Keep it short, otherwise you'll end up with lots of nested folders in the cache/expensive directory. Each handle and it's associated configuration are stored in a config file in _application/config/generated\_overrides/advanced\_cache/settings.php_.

**What is a flush rule?**  
It's a rule that defines when a cache entry should be invalidated. The add-on comes with various flush rules, but you are allowed to use your own flush rules. Just make sure they implement the RuleInterface.

**Can I use this in combination with e.g. Redis?**  
Yes, it uses the ExpensiveCache level implementation. The cache files can be stored on the file system, or e.g. in memory.

**When does it make sense to use the 'onlyCacheIf' method?**  
If you only want to cache a block in certain conditions. E.g. only for certain user groups. Or only for guest users. Or only on a certain page or page type. If the method evaluates to 'false', the block output won't be cached.
