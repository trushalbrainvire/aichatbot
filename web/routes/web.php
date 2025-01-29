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
use Illuminate\Http\Client\Pool;


/* Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
}); */
Route::get('/', function () {
    $shopQuery = <<<GRAPHQL
        {
            shop {
                shopPolicies{
                    __typename
                    title
                    body
                }
            }
        }
    GRAPHQL;
    $rest_endpoint = "https://".auth()->user()->name."/admin/api/".config('shopify-app.api_version')."/shop.json";
    $graph_endpoint = "https://".auth()->user()->name."/admin/api/".config('shopify-app.api_version')."/graphql.json";

    $header = [
        'X-Shopify-Access-Token'=> auth()->user()->password
    ];
    $responses = Http::pool(fn (Pool $pool) => [
        $pool->as('first')->withHeaders($header)->get( $rest_endpoint),
        $pool->as('second')->withHeaders($header)->post($graph_endpoint,["query"=> $shopQuery])
    ]);

    dd($responses['first']->json(), $responses['second']->json());
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
