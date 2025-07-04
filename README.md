<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
php artisan key:generate
php artisan serve

php artisan migrate:fresh --seed
php artisan route:list

php artisan migrate
php artisan storage:link
chmod -R 775 storage bootstrap/cache

php artisan make:model ProductPrice -mc

buatkan migration table invoice

id
inv_no unique
sales_agents_id fk
company id nullable fk
customer id fk
g_total_purchase_price
g_total_qty index
g_total_sell_price index
g_total_komisi_sales index
discount_pcnt
discount_amount index
g_total_profit_gross
packaging_fee
additional_fee
additional_fee_note
g_total_invoice_amount index
note
platform_id nullable index // 1=tokopedia 2=tiktokshop 3=shopee 4=blibli 5=toco
platform_paid_amount
lunas_at nullable index
lunas_by fk user_id
checked_finance_at nullable index
checked_finance_by fk user_id
cancel true false index
cancel_reason
komisi_paid_at nullable index
komisi_paid_by fk user
komisi_paid_proof_doc
timestamp index
created_by fk user id
updated_by fk user id
cancel_by fk user id

dan buatkan juga migration table invoice_item
id
inv_id fk
default_sell_price id fk
ss_online_sell_price
ss_offline_sell_price
ss_reseller_sell_price
ss_resto_sell_price
ss_bottom_sell_price
product_id fk
stok_id nullable fk
purchase_price
customer_price_id fk
sell_price
ss_komisi_sales
profit_gross
qty
total_purchase_price
total_sell_price
total_komisi_sales
total_profit_gross
note
created_at 
created_by fk user id

indexnya terpisah

--

buatkan controller model dan view blade nya

index blade dulu 
ada fitur filter berdasarkan 

inv no 
sales agent gunakan tomselect
company gunakan tomselect
customer gunakan tomselect
invoice amount range
platform gunakan tomselect
lunas true false
checked finance true false
cancel true false
periode tanggal (dari invoice_transaction_date)



dan yang di tampilkan di minidatatable 
id, inv no, platform jika null maka teks offline, customer (tampilkan company name , customer name , customer address), total invoice (g_total_invoice_amount), status (lunas , checked , cancel), action (lihat)
