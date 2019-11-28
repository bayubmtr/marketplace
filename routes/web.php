<?php
Route::get('/auth/social/{provider}', 'User\SocialAuthController@providerRedirect');
Route::get('/auth/{provider}/callback', 'User\SocialAuthController@providerRedirectCallback');
Route::group(['namespace' => 'User',], function () {
Route::get('/invoice/{id}/print', 'InvoiceController@index');
});
Route::get('/admin/{vue?}', function () {
    return view('admin');
})->where('vue', '[\/\w\.-]*')->name('admin');

Route::get('/{vue?}', function () {
    return view('user');
})->where('vue', '[\/\w\.-]*')->name('user');