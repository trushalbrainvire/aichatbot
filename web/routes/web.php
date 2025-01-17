<?php

use Inertia\Inertia;
use App\Models\Product;
use EchoLabs\Prism\Prism;
use Pgvector\Laravel\Vector;
use Pgvector\Laravel\Distance;
use EchoLabs\Prism\Facades\Tool;
use EchoLabs\Prism\Enums\Provider;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Log;
use EchoLabs\Prism\Enums\ToolChoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use EchoLabs\Prism\Schema\StringSchema;
use App\Http\Controllers\ProfileController;
use App\Models\User;

/* Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
}); */
Route::get('/', function () {
    $customerQuery = <<<GRAPHQL
        query Customer(\$id: ID!){
            customer(id: \$id){
                id
                firstName
                lastName
                email
                phone
                addressesV2(first: 10){
                    nodes{
                        id
                        company
                        address1
                        address2
                        city
                        zip
                        province
                        provinceCode
                        country
                        countryCodeV2
                    }
                }
            }
        }
    GRAPHQL;

    $gidPrefix = "gid://shopify/Customer/";

    dd(User::first()->api()->graph($customerQuery,['id'=>$gidPrefix."8163588309285"]));
})->middleware(['verify.shopify'])->name('home');



Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
