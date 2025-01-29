<?php

namespace App\Jobs;

use App\Models\Merchant;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\Pool;

class AfterAuthenticateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $shopQuery = <<<GRAPHQL
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

    private $accessTokenMutation = <<<GRAPHQL
        mutation StorefrontAccessTokenCreate(\$input: StorefrontAccessTokenInput!) {
            storefrontAccessTokenCreate(input: \$input) {
                userErrors {
                    field
                    message
                }
                shop {
                    id
                }
                storefrontAccessToken {
                    accessScopes {
                        handle
                    }
                    accessToken
                    title
                }
            }
        }
    GRAPHQL;

    protected $user;
    /**
     * Create a new job instance.
     */
    public function __construct($data){
        $this->user = $data['user'];
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $graphql_endpoint = "https://".$this->user->name."/admin/api/".config('shopify-app.api_version')."/graphql.json";
        $shop_rest_endpoint = "https://".$this->user->name."/admin/api/".config('shopify-app.api_version')."/shop.json";
        $header = [
            'X-Shopify-Access-Token'=> $this->user->password
        ];

        $apiResponses = Http::pool(fn (Pool $pool) => [
            $pool->as('first')->withHeaders($header)->get($shop_rest_endpoint),
            $pool->as('second')->withHeaders($header)->post($graphql_endpoint,["query"=> $this->shopQuery])
        ]);

        $shop = $apiResponses['first']->json()['shop'];
        $shopPolicies = $apiResponses['second']->json()['data']['shop']['shopPolicies'];

        $address = [
            "address1" => $shop['address1'],
            "address2" => $shop['address2'],
            "city" => $shop['city'],
            "province" => $shop['province'],
            "country" => $shop['country'],
            "zip" => $shop['zip']
        ];

        $merchnatData = [
            'merchant_id'=> $shop['id'],
            'store'=> $shop['name'],
            'owner'=> $shop['shop_owner'],
            'email'=> $shop['email'],
            'currency_code'=> $shop['country_code'],
            'currency_formats'=> json_encode($shop['money_format']),
            'address'=> json_encode($address),
            'domain'=> $shop['domain'],
            'is_password_protected'=> $shop['password_enabled'],
            'plan'=> $shop['plan_name'],
            'user_id'=>$this->user->id
        ];

        $merchant = Merchant::create($merchnatData);

        for ($i = 0; $i < count($shopPolicies); $i++) {
            $policy = $shopPolicies[$i];
            $merchant->policies()->create([
                'name' => $policy['title'],
                'body'=> $policy['body']
            ]);
        }

        $httpClientAccessTokenReq = Http::withHeaders([
            'X-Shopify-Access-Token'=> $this->user->password
        ])->post($graphql_endpoint,["query"=> $this->accessTokenMutation, "variables"=>['input'=>['title'=> config('app.name').' storefront access token']]]);

        $storefront_access_token = $httpClientAccessTokenReq->json()['data']['storefrontAccessTokenCreate']['storefrontAccessToken']['accessToken'];

        Merchant::find($merchant->id)->update([
            'storefront_password'=> $storefront_access_token
        ]);
    }
}
