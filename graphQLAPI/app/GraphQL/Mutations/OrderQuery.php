<?php


namespace App\GraphQL\Mutations;

use App\Models\Webshop;
use Automattic\WooCommerce\Client;
use GraphQL\Type\Definition\Type;
use Grayloon\Magento\Magento;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;


class OrderQuery extends Mutation
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
                'type' => Type::listOf(Type::listOf(Type::string())),
                'rules' => ['required']
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

        //Making a guestCart
        $cardID = $magento->api('guestCarts')->create();
        $cardID = str_replace("\"", "", $cardID->body());

        //Adding products to cart
        foreach ($args["products"] as $product){
            $magento->api('guestCarts')->addItem($cardID,  $product[0], $product[1]);
        }

        //Setting the orderInfo
        $orderInfo = [];
        $billingInfo["country_id"] = "NL";
        $billingInfo["street"] = [$args["address"]];
        $billingInfo["postcode"] = $args["postcode"];
        $billingInfo["city"] = $args["city"];
        $billingInfo["firstname"] = $args["first_name"];
        $billingInfo["lastname"] = $args["last_name"];
        $billingInfo["email"] = $args["email"];
        $billingInfo["telephone"] = $args["phone"];
        $addressInformation = [];
        $addressInformation["shipping_address"] = $billingInfo;
        $addressInformation["billing_address"] = $billingInfo;
        $addressInformation["shipping_carrier_code"] = "flatrate";
        $addressInformation["shipping_method_code"] = "flatrate";
        $orderInfo["addressInformation"] = $addressInformation;
        $magento->api('guestCarts')->shippingInformation($cardID, $orderInfo);

        //Setting the payment info
        $payment = [];
        $payment["billing_address"] = $billingInfo;
        $paymentInfo = [];
        $paymentInfo["method"] = "checkmo";
        $payment["paymentMethod"] = $paymentInfo;
        $payment['email'] = $args["email"];
        $orderID =$magento->api('guestCarts')->paymentInformation($cardID, $payment);

        return (object) $orderID;
    }

    public function WooCommerce($webshop, $args): object
    {
        //Setting the billing and shipping address
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

        //Adding all the products to the body
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

        $woocommerce = new Client(
            $webshop[0]['url'],
            $webshop[0]['customer_key'],
            $webshop[0]['customer_secret'],
            [
                'wp_api' => true,
                'version' => 'wc/v2',
            ]
        );
        $returnValue = $woocommerce->post("orders",$order);
        return (object)$returnValue;
    }
}
