<?php


namespace App\GraphQL\Mutations;

use App\Models\Webshop;
use Automattic\WooCommerce\Client;
use GraphQL\Type\Definition\Type;
use Grayloon\Magento\Magento;
use phpDocumentor\Reflection\Types\Boolean;
use PhpParser\Node\Expr\Cast\Int_;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Query;
use function Sodium\add;


class OrderQuery extends Query
{
    protected $attributes = [
        'name' => 'Order',
    ];

    public function type(): Type
    {
        return GraphQL::type("Boolean");
    }
    public function args(): array
    {
        return [
            'webshopID' => [
                'name' => 'webshopID',
                'type' => Type::int(),
                'rules' => ['required']
            ],
            'first_name' => [
                'name' => 'first_name',
                'type' => Type::String(),
                'rules' => ['required']
            ],
            'last_name' => [
                'name' => 'last_name',
                'type' => Type::String(),
                'rules' => ['required']
            ],
            'address' => [
                'name' => 'address',
                'type' => Type::String(),
                'rules' => ['required']
            ],
            'postcode' => [
                'name' => 'postcode',
                'type' => Type::String(),
                'rules' => ['required']
            ],
            'city' => [
                'name' => 'city',
                'type' => Type::String(),
                'rules' => ['required']
            ],
            'payment_method' => [
                'name' => 'payment_method',
                'type' => Type::String(),
                'rules' => ['required']
            ],
            'payment_method_title' => [
                'name' => 'payment_method_title',
                'type' => Type::String(),
                'rules' => ['required']
            ],
            'email' => [
                'name' => 'email',
                'type' => Type::String(),
                'rules' => ['required']
            ],
            'phone' => [
                'name' => 'phone',
                'type' => Type::String(),
                'rules' => ['required']
            ],
            'set_paid' => [
                'name' => 'set_paid',
                'type' => Type::Boolean(),
                'rules' => ['required']
            ],
            'products' => [
                'name' => 'products',
                'type' => Type::listOf(Type::listOf(Type::int())),
            ],

        ];
    }


    public function resolve($root, $args): object
    {
        $webshop = Webshop::query()->where("id", $args["webshopID"])->get();
        $return = [];
        switch ( $webshop[0]['type']){
            case "WooCommerce";
                $return = $this->WooCommerce($webshop, $args);
                break;
            case "Magento";
                $return = $this->Magento($webshop, $args);
                break;
        }
        return $return;
    }

    public function Magento($webshop, $args): object
    {
        $magento = new Magento();
        $magento->token = $webshop[0]['customer_key'];
        $magento->baseUrl = $webshop[0]['url'];
        $cardID = $magento->api('guestCarts')->create();
        $cardID = str_replace("\"", "", $cardID->body());
        foreach ($args["products"] as $product){
            $magento->api('guestCarts')->addItem($cardID,  $product["sku"], 1);
        }

        $info = $magento->api('guestCarts')->items($cardID);
        print($info);

        $orderInfo = [];
//        $billingInfo["region"] = "New York";
//        $billingInfo["region_id"] = 43;
//        $billingInfo["region_code"] = "NY";


        $billingInfo["country_id"] = "NL";
        $billingInfo["street"] = [$args["address"]];
        $billingInfo["postcode"] = $args["postcode"];
        $billingInfo["city"] = $args["city"];
        $billingInfo["firstname"] = $args["first_name"];
        $billingInfo["lastname"] = $args["last_name"];
        $billingInfo["email"] = $args["email"];
        $billingInfo["telephone"] = $args["phone"];
        //$billingInfo["same_as_billing"] = 1;
        $addressInformation = [];
        $addressInformation["shipping_address"] = $billingInfo;
        $addressInformation["billing_address"] = $billingInfo;
        $addressInformation["shipping_carrier_code"] = "flatrate";
        $addressInformation["shipping_method_code"] = "flatrate";
        $orderInfo["addressInformation"] = $addressInformation;
        $test = [];
        $test["billing_address"] = $billingInfo;
        $payment = [];
        $payment["method"] = "checkmo";




        $paymentID = $magento->api('guestCarts')->shippingInformation($cardID, $orderInfo);

        print($paymentID);
        $test["paymentMethod"] = $payment;
        $test['email'] = $args["email"];
        $magento->api('guestCarts')->paymentInformation($cardID, $test);

        $orderID = $magento->api('guestCarts')->estimateShippingMethods($cardID, $test);


        print $orderID;
        return (object) $orderID;
    }

    public function WooCommerce($webshop, $args): object
    {
        $order = [];
        $billing = [];
        $billing["first_name"] = $args["first_name"];
        $billing["last_name"] = $args["last_name"];
        $billing["address_1"] = $args["address"];
        $billing["address_2"] = "";
        $billing["city"] = $args["city"];
        $billing["state"] = "";
        $billing["postcode"] = $args["postcode"];
        $billing["country"] = "NL";
        $shipping = $billing;
        $billing["email"] = $args["email"];
        $billing["phone"] = $args["phone"];
        $order["payment_method"] = $args["payment_method"];
        $order["payment_method_title"] = $args["payment_method_title"];
        $order["set_paid"] = $args["set_paid"];
        $order["billing"] = $billing;
        $order["shipping"] = $shipping;
        $productlist = [];
        $counter = 0;
        foreach ($args["products"] as $product){
            $tempproduct =[];
            $tempproduct["product_id"] = $product[0];
            $tempproduct["quantity"] = $product[1];
            $productlist[$counter] = $tempproduct;
            $counter = $counter+1;
        }
        $order["line_items"] = $productlist;
        $shipping_lines = [];
        $shipping_lines["method_id"] = "flat_rate";
        $shipping_lines["method_title"] = "flat_rate";
        $shipping_lines["total"] = "10.00";
        //$order["shipping_lines"] = $shipping_lines;

        $woocommerce = new Client(
            $webshop[0]['url'],
            $webshop[0]['customer_key'],
            $webshop[0]['customer_secret'],
            [
                'wp_api' => true,
                'version' => 'wc/v2',
                'verify_ssl' => false,
            ]
        );
        $returnValue = $woocommerce->post("orders",$order);
        return (object)$returnValue;
    }
}
