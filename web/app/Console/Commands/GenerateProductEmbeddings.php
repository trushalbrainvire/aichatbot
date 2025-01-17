<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\User;
use EchoLabs\Prism\Prism;
use Illuminate\Console\Command;
use EchoLabs\Prism\Enums\Provider;
use Illuminate\Support\Facades\Log;
use Pgvector\Laravel\Vector;

class GenerateProductEmbeddings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-product-embeddings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Command Generate the product Embeddings for the First Store';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $query = <<<GRAPHQL
                query Products(\$first: Int!, \$after: String) {
                    products(first: \$first , query: "status:ACTIVE", after: \$after) {
                        nodes {
                            id
                            title
                            description
                            handle
                            productType
                            vendor
                            status
                            onlineStoreUrl
                            priceRangeV2 {
                                minVariantPrice {
                                    amount
                                }
                            }
                            compareAtPriceRange {
                                minVariantCompareAtPrice {
                                    amount
                                }
                            }
                            tags
                            options(first: 10){
                                name
                                values
                            }
                        }
                        pageInfo {
                            hasNextPage
                            endCursor
                        }
                    }
                }
            GRAPHQL;

            $products = array();
            // Variables for the query
            $variables = [
                'first' => 5,
                'after' => null
            ];
            $shop = User::first();

            $productQuery = $shop->api()->graph($query, $variables);
            $productsData = $productQuery['body']['container']['data']['products'];
            $products = $productsData['nodes'];

            do {
                $nextPageLink = $productsData['pageInfo']['endCursor'];
                $variables['after'] = $nextPageLink;
                $productQuery = $shop->api()->graph($query, $variables);
                $productsData = $productQuery['body']['container']['data']['products'];

                foreach ($productsData['nodes'] as $product) {
                    array_push($products, $product);
                }
            } while ($productsData['pageInfo']['hasNextPage']);

            foreach ($products as $product) {
                $id = explode("/", $product['id']);
                $product_id = array_pop($id);

                $response = Prism::embeddings()
                    ->using(Provider::OpenAI, 'text-embedding-ada-002')
                    ->fromInput(json_encode($product))
                    ->generate();

                $embeddings = new Vector($response->embeddings);

                $dbproduct = Product::create([
                    'embeddings' => $embeddings,
                    'product_id' => $product_id,
                    'graphql_id' => $product['id'],
                    'title' => $product['title'],
                    'body' => $product['description'],
                    'handle' => $product['handle'],
                    'productType' => $product['productType'],
                    'vendor' => $product['vendor'],
                    'onlineStoreUrl' => $product['onlineStoreUrl'],
                    'price' => isset($product['priceRangeV2']) ? $product['priceRangeV2']['minVariantPrice']['amount'] : 0.00,
                    'comparedAtPrice' => isset($product['compareAtPriceRange']) ? $product['compareAtPriceRange']['minVariantCompareAtPrice']['amount'] : 0.00,
                    'tags' => json_encode($product['tags']),
                    'options_and_values' => json_encode($product['options']),
                    'merchant_id' => $shop->merchant->id
                ]);
            }

            return Command::SUCCESS;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return Command::FAILURE;
        }
    }
}
