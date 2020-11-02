### 1. Introduction:

With the help of Bagisto AliExpress Dropship extension, the admin can import and list products from the AliExpress to its Bagisto store. After receiving the orders, the admin just need to click a single button and then make the payment as we have automated the order process for the AliExpress orders.

It packs in lots of demanding features that allows your business to scale in no time:

* Import AliExpress products(simple and configurable) from AliExpress into your selected channel.
* Create configurable product if the product has options otherwise, create a simple product.
* Edit the product information(along with images) on AliExpress website before importing to your store.
* Update AliExpress order status from AliExpress chrome extension.
* Use filter in AliExpress Chrome extension for e-packet identification.
* Import products from the AliExpress Product, Search and Category pages.
* Shop URL verification while registering your Bagisto store URL.
* Add Product Price Synchronization from AliExpress using cron.
* Description of the product also gets imported same as AliExpress.
* The product information like base name, price, quantity, product reviews, meta title, meta keywords, meta description, SKU, description(simple tags, HTML tags, and multiple images), and configurable attributes get imported along with the product.
* View all AliExpress Orders in a separate section.
* Automated Order Process for the AliExpress Orders.


### 2. Requirements:

* **Bagisto**: v1.1.0


### 3. Installation:

* Unzip the respective extension zip and then merge "packages" folders into project root directory.
* Goto config/app.php file and add following line under 'providers'

~~~
Webkul\Dropship\Providers\DropshipServiceProvider::class
~~~

* Goto composer.json file and add following line under 'psr-4'

~~~
"Webkul\\Dropship\\": "packages/Webkul/Dropship"
~~~

* Run these commands below to complete the setup

~~~
composer dump-autoload
~~~

~~~
php artisan migrate
~~~

~~~
php artisan route:cache
~~~

~~~
php artisan vendor:publish

-> Press 0 and then press enter to publish all assets and configurations.
~~~

~~~

composer require symfony/dom-crawler

~~~

* Add the following line to the crontab for automatic product information updation (eg. Price and Quantity)

~~~
*/5 * * * * php /project_root_folder_path/artisan dropship:product:update
~~~

> That's it, now just execute the project on your specified domain.
