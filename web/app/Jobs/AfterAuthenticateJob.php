<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Merchant;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AfterAuthenticateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $shopQuery = <<<GRAPHQL
        {
            shop {
                id
                name
                shopOwnerName
                contactEmail
                currencyCode
                currencyFormats{
                    moneyFormat
                    moneyWithCurrencyFormat
                }
                billingAddress{
                    id
                    phone
                    address1
                    address2
                    company
                    city
                    province
                    country
                    zip
                }
                primaryDomain{
                    host
                    url
                }
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
        $endpoint = "https://".$this->user->name."/admin/api/".config('shopify-app.api_version')."/graphql.json";

        $httpClientReq = Http::withHeaders([
            'X-Shopify-Access-Token'=> $this->user->password
        ])->post($endpoint,["query"=> $this->shopQuery]);

        $shop = $httpClientReq->json()['data']['shop'];

        $merchnatData = [
            'merchant_id'=> $shop['id'],
            'store'=>$shop['name'],
            'owner'=>$shop['shopOwnerName'],
            'email'=>$shop['contactEmail'],
            'currency_code'=>$shop['currencyCode'],
            'currency_formats'=>json_encode($shop['currencyFormats']),
            'address'=>json_encode($shop['billingAddress']),
            'domain'=>$shop['primaryDomain']['url'],
            'user_id'=>$this->user->id
        ];

        $merchant = Merchant::create($merchnatData);

        $policies = $shop['shopPolicies'];
        for ($i = 0; $i < count($policies); $i++) {
            $policy = $policies[$i];
            $merchant->policies()->create([
                'name' => $policy['title'],
                'body'=> $policy['body']
            ]);
        }

        $httpClientAccessTokenReq = Http::withHeaders([
            'X-Shopify-Access-Token'=> $this->user->password
        ])->post($endpoint,["query"=> $this->accessTokenMutation, "variables"=>['input'=>['title'=> config('app.name').' storefront access token']]]);

        $storefront_access_token = $httpClientAccessTokenReq->json()['data']['storefrontAccessTokenCreate']['storefrontAccessToken']['accessToken'];

        Merchant::find($merchant->id)->update([
            'storefront_password'=> $storefront_access_token
        ]);
    }
}
