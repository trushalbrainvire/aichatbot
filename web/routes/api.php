<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ChatController;

Route::middleware('auth.proxy')->group(function(){
    Route::get('/initiation', [ChatController::class, 'invoke'])->name('ai.initialization');
    Route::post('/message', [ChatController::class, 'message'])->name('ai.message');
});

/* Route::post('/message', function(Request $request){
    return response()->json(['message'=> 'Data retrived !', 'data'=>$request->all()],200);
})->name('ai.message')->middleware('auth.proxy'); */
