<?php

namespace App\Services\Chat\DTOs;

use App\Models\User;
use Illuminate\Support\Facades\Session;

final class Customer {

    protected static $customerQuery = <<<GRAPHQL
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

    private static string $gidPrefix = "gid://shopify/Customer/";

    /**
     * fetchCustomer
     *
     * @param  int $customerId
     * @return mixed
     */
    public static function fetchCustomer(User $user, $customerId): mixed {
        if (is_null($customerId)) {
            return null;
        }
        $customerReq = $user->api()->graph(self::$customerQuery, ["id"=> self::$gidPrefix.$customerId]);
        return $customerReq['body']['container']['data']['customer'];
    }

}
