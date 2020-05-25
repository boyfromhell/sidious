<?php
Route::post("transactions", "\Modules\Economy\Actions\MakeTransaction")->name('transaction.make');
Route::delete("transactions", "\Modules\Economy\Actions\CancelTransaction")->name('transaction.cancel');
Route::apiResource("fiches.economy-lines", "EconomyLineController");
Route::get("fiches/{personnage}", "FicheController@show")->name('fiche.show');
Route::post("accounts/complex", "AccountController@complexTransaction")->name('account.complexTransaction');
Route::get("accounts/{account}", "AccountController@show")->name('account.show');
Route::post("accounts/{account}", "AccountController@fromAccountToAnother")->name('account.fromAccountToAnother');
Route::get("accounts/{account}/freeze", "AccountController@freeze")->name('account.freeze');
