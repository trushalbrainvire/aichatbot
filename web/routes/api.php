<?php

use Mitie\NER;
use App\Models\User;
use App\Models\Product;
use App\Models\Embedding;
use EchoLabs\Prism\Prism;
use EchoLabs\Prism\Enums\Provider;
use Illuminate\Support\Facades\Route;
use EchoLabs\Prism\Schema\ObjectSchema;
use EchoLabs\Prism\Schema\StringSchema;
use App\Http\Controllers\API\ChatController;

Route::middleware('auth.proxy')->group(function(){
    Route::get('/initiation', [ChatController::class, 'invoke'])->name('ai.initialization');
    Route::post('/message', [ChatController::class, 'message'])->name('ai.message');
});

Route::get('/test', function(){
    /* $schema = new ObjectSchema(
        name: 'movie_review',
        description: 'A structured movie review',
        properties: [
            new StringSchema('title', 'The movie title'),
            new StringSchema('rating', 'Rating out of 5 stars'),
            new StringSchema('summary', 'Brief review summary')
        ],
        requiredFields: ['title', 'rating', 'summary']
    );

    $response = Prism::structured()
        ->using(Provider::OpenAI, 'gpt-4o')
        ->withSchema($schema)
        ->withPrompt('Review the movie Inception')
        ->generate(); */


    $user = Embedding::take(5)->with('embeddable')->get()->pluck('embeddable')->select(['title','body','handle','vendor','price'])->toArray();
    dd($user);

    dd($user->merchant->products()->create([
        'product_id' => 12345678,
        'graphql_id' => "iuyf56hdettpshtte",
        'title' => "ie4876iufyotds8t8",
        'body' => "g87ey7oce8ucRGBELIUGF",
        'handle' => "ISAJOID",
        'productType' => "asda",
        'vendor' => "SDASSSSsssz",
        'onlineStoreUrl' => "https://asd/asd",
        'price' =>  0.00,
        'comparedAtPrice' =>  0.00,
        'tags' => ["asdas","asfasdwer","er3r2"],
        'options_and_values' => ['data'=>["name"=>"trushal","surname"=>"Ponkiya"]]
    ]));

    dd($response, $response->text, $response->structured);

})->name('ai.test');

/* Route::post('/message', function(Request $request){
    return response()->json(['message'=> 'Data retrived !', 'data'=>$request->all()],200);
})->name('ai.message')->middleware('auth.proxy'); */
