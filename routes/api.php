<?php
use Illuminate\Http\Request;

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
  Route::post('/login','AuthController@authenticate');
  Route::post('/check','AuthController@check');
  Route::get('/manager','AuthController@manager');
});
Route::group(['prefix' => 'admin', 'middleware' => 'jwt.auth', 'namespace' => 'Admin',], function () {
  Route::get('/auth/user','AuthController@getAuthUser');
  Route::post('/logout','AuthController@logout');
  Route::get('/statistic','CountController@index');
  Route::resources([
    'users' => 'UserController',
    'products' => 'ProductController',
    'transactions' => 'TransactionController',
    'stores' => 'StoreController',
    'withdraws' => 'WithdrawController'
]);
Route::group(['prefix' => 'web'], function () {
  Route::resources([
    'promos' => 'PromoController',
    'categories' => 'CategoryController',
    'logistics' => 'LogisticController',
    'administrative' => 'AdministrativeController'
  ]);
  Route::group(['middleware' => 'manager'], function () {
    Route::resources([
      'admins' => 'ManageAdminController'
    ]);
  });
});
});

Route::group([ 'prefix' => 'user', 'namespace' => 'User'], function () {
  Route::post('/login','AuthController@authenticate');
  Route::post('/activate','AuthController@activate');
  Route::post('/request-reset-password','AuthController@password');
  Route::post('/validate-reset-password','AuthController@validatePasswordReset');
  Route::post('/reset-password','AuthController@reset');
  Route::post('/register','AuthController@register');
  Route::post('/check','AuthController@check');
  Route::post('/social','SocialAuthController@getToken');

  Route::get('/products','ProductController@index')->name('product.show');
  Route::get('/discussions','ProductDiscussionController@index');
  Route::get('/promos','WebsiteController@getPromos');
  Route::get('/promo/{id}','WebsiteController@getPromo');
  Route::get('/categories','CategoryController@index');

  Route::get('/store/{userid}','StoreController@getStore');
  Route::get('/store/{userid}/product','StoreController@getAllProducts');
  Route::get('/products/{product_id}','ProductController@show');

  Route::post('/payment/notification','PaymentNotificationController@notification');
  Route::post('/payment/success','PaymentNotificationController@paymentSuccess');
  
  
  Route::resource(
    'stores' , 'StoreController')->except([
    'store','edit','update'
  ]);
});

Route::group(['prefix' => 'user', 'middleware' => 'jwt', 'namespace' => 'User',], function () {
  Route::get('/auth/user','AuthController@getAuthUser');
  Route::post('/logout','AuthController@logout');
  Route::get('/notifications','NotificationController@index');
  Route::get('/incomes','IncomeController@index');
  Route::post('/seller/stores','Seller\StoreController@store');
  Route::post('/seller/stores/checkstoreurl','Seller\StoreController@checkStoreUrl');
  Route::group(['prefix' => 'seller', 'middleware' => 'check.store', 'namespace' => 'Seller', 'middleware' => 'check.store'], function () {
    Route::resources([
      'sales' => 'SaleController',
      'products' => 'ProductController'
    ]);
    Route::resource(
      'stores' , 'StoreController')->except([
      'store'
    ]);
  });
  Route::resources([
    'messages' => 'MessageController',
    'purchases' => 'PurchaseController',
    'payments' => 'PaymentController',
    'carts' => 'CartController',
    'wishlists' => 'WishlistController',
    'accounts' => 'AccountController'
  ]);
  Route::resource(
    'follows' , 'FollowController')->except([
    'edit','update'
  ]);
  Route::resource(
    'wallets' , 'WalletController')->except([
    'edit', 'destroy', 'show', 'update'
  ]);
  Route::resource(
    'banks' , 'BankController')->except([
      'edit', 'update'
  ]);
  Route::resource(
    'withdraws' , 'WithdrawController')->except([
      'edit', 'update', 'destroy'
  ]);
  Route::resource('addresses', 'AddressController');
  Route::resource('categories', 'CategoryController')->except([
    'index'
  ]);
  Route::resource('discussions', 'ProductDiscussionController')->except([
    'index'
  ]);
});