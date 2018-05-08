<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use Session;
use Stripe\Charge;
use Stripe\Stripe;

class CheckoutController extends Controller
{

    public function __construct()
    {
        $this->middleware("auth", ["except" => ["checkout", "postcheckoutaddress", "checkoutaddress", "checkoutpayment", "completecheckout", "completecheckoutpaypal"]]);

        // test keys //
        define("PAYPAL_CLIENTID", "AVSaVKCcTKLnagQLFxn4nFrOate_Tb9sxXbsvnvNp1wVYdhAn5eG7maIBo6icXPBau0e6TybTBhbkuVK");
        define("PAYPAL_SECRET", "EJ-Yayj6NJURyAVkt7NYwd1Wo_7rhB_T_frGAIpa2A0acji6p1ak98Ky-lOPhQgDpuP3iDyu7eBpzKdg");
        define("STRIPE_KEY", "sk_test_JJ7cu8Hrdi0wda1MHgvBSi3i");
    }

    public function checkoutaddress()
    {
        if(!Session::has("cart"))
        {
            return view("cart/index");
        }

        $oldCart = Session::get("cart");
        $cart = new Cart($oldCart);
        $totalPrice = $cart->getFullPrice();
        $countries = [
            "Select" => "Select Country",
            "AF" => "Afghanistan",
            "AX" => "Åland Islands",
            "AL" => "Albania",
            "DZ" => "Algeria",
            "AS" => "American Samoa",
            "AD" => "Andorra",
            "AO" => "Angola",
            "AI" => "Anguilla",
            "AQ" => "Antarctica",
            "AG" => "Antigua and Barbuda",
            "AR" => "Argentina",
            "AM" => "Armenia",
            "AW" => "Aruba",
            "AU" => "Australia",
            "AT" => "Austria",
            "AZ" => "Azerbaijan",
            "BS" => "Bahamas",
            "BH" => "Bahrain",
            "BD" => "Bangladesh",
            "BB" => "Barbados",
            "BY" => "Belarus",
            "BE" => "Belgium",
            "BZ" => "Belize",
            "BJ" => "Benin",
            "BM" => "Bermuda",
            "BT" => "Bhutan",
            "BO" => "Bolivia, Plurinational State of",
            "BQ" => "Bonaire, Sint Eustatius and Saba",
            "BA" => "Bosnia and Herzegovina",
            "BW" => "Botswana",
            "BV" => "Bouvet Island",
            "BR" => "Brazil",
            "IO" => "British Indian Ocean Territory",
            "BN" => "Brunei Darussalam",
            "BG" => "Bulgaria",
            "BF" => "Burkina Faso",
            "BI" => "Burundi",
            "KH" => "Cambodia",
            "CM" => "Cameroon",
            "CA" => "Canada",
            "CV" => "Cape Verde",
            "KY" => "Cayman Islands",
            "CF" => "Central African Republic",
            "TD" => "Chad",
            "CL" => "Chile",
            "CN" => "China",
            "CX" => "Christmas Island",
            "CC" => "Cocos (Keeling) Islands",
            "CO" => "Colombia",
            "KM" => "Comoros",
            "CG" => "Congo",
            "CD" => "Congo, the Democratic Republic of the",
            "CK" => "Cook Islands",
            "CR" => "Costa Rica",
            "CI" => "Côte d'Ivoire",
            "HR" => "Croatia",
            "CU" => "Cuba",
            "CW" => "Curaçao",
            "CY" => "Cyprus",
            "CZ" => "Czech Republic",
            "DK" => "Denmark",
            "DJ" => "Djibouti",
            "DM" => "Dominica",
            "DO" => "Dominican Republic",
            "EC" => "Ecuador",
            "EG" => "Egypt",
            "SV" => "El Salvador",
            "GQ" => "Equatorial Guinea",
            "ER" => "Eritrea",
            "EE" => "Estonia",
            "ET" => "Ethiopia",
            "FK" => "Falkland Islands (Malvinas)",
            "FO" => "Faroe Islands",
            "FJ" => "Fiji",
            "FI" => "Finland",
            "FR" => "France",
            "GF" => "French Guiana",
            "PF" => "French Polynesia",
            "TF" => "French Southern Territories",
            "GA" => "Gabon",
            "GM" => "Gambia",
            "GE" => "Georgia",
            "DE" => "Germany",
            "GH" => "Ghana",
            "GI" => "Gibraltar",
            "GR" => "Greece",
            "GL" => "Greenland",
            "GD" => "Grenada",
            "GP" => "Guadeloupe",
            "GU" => "Guam",
            "GT" => "Guatemala",
            "GG" => "Guernsey",
            "GN" => "Guinea",
            "GW" => "Guinea-Bissau",
            "GY" => "Guyana",
            "HT" => "Haiti",
            "HM" => "Heard Island and McDonald Islands",
            "VA" => "Holy See (Vatican City State)",
            "HN" => "Honduras",
            "HK" => "Hong Kong",
            "HU" => "Hungary",
            "IS" => "Iceland",
            "IN" => "India",
            "ID" => "Indonesia",
            "IR" => "Iran, Islamic Republic of",
            "IQ" => "Iraq",
            "IE" => "Ireland",
            "IM" => "Isle of Man",
            "IL" => "Israel",
            "IT" => "Italy",
            "JM" => "Jamaica",
            "JP" => "Japan",
            "JE" => "Jersey",
            "JO" => "Jordan",
            "KZ" => "Kazakhstan",
            "KE" => "Kenya",
            "KI" => "Kiribati",
            "KP" => "Korea, Democratic People's Republic of",
            "KR" => "Korea, Republic of",
            "KW" => "Kuwait",
            "KG" => "Kyrgyzstan",
            "LA" => "Lao People's Democratic Republic",
            "LV" => "Latvia",
            "LB" => "Lebanon",
            "LS" => "Lesotho",
            "LR" => "Liberia",
            "LY" => "Libya",
            "LI" => "Liechtenstein",
            "LT" => "Lithuania",
            "LU" => "Luxembourg",
            "MO" => "Macao",
            "MK" => "Macedonia, the former Yugoslav Republic of",
            "MG" => "Madagascar",
            "MW" => "Malawi",
            "MY" => "Malaysia",
            "MV" => "Maldives",
            "ML" => "Mali",
            "MT" => "Malta",
            "MH" => "Marshall Islands",
            "MQ" => "Martinique",
            "MR" => "Mauritania",
            "MU" => "Mauritius",
            "YT" => "Mayotte",
            "MX" => "Mexico",
            "FM" => "Micronesia, Federated States of",
            "MD" => "Moldova, Republic of",
            "MC" => "Monaco",
            "MN" => "Mongolia",
            "ME" => "Montenegro",
            "MS" => "Montserrat",
            "MA" => "Morocco",
            "MZ" => "Mozambique",
            "MM" => "Myanmar",
            "NA" => "Namibia",
            "NR" => "Nauru",
            "NP" => "Nepal",
            "NL" => "Netherlands",
            "NC" => "New Caledonia",
            "NZ" => "New Zealand",
            "NI" => "Nicaragua",
            "NE" => "Niger",
            "NG" => "Nigeria",
            "NU" => "Niue",
            "NF" => "Norfolk Island",
            "MP" => "Northern Mariana Islands",
            "NO" => "Norway",
            "OM" => "Oman",
            "PK" => "Pakistan",
            "PW" => "Palau",
            "PS" => "Palestinian Territory, Occupied",
            "PA" => "Panama",
            "PG" => "Papua New Guinea",
            "PY" => "Paraguay",
            "PE" => "Peru",
            "PH" => "Philippines",
            "PN" => "Pitcairn",
            "PL" => "Poland",
            "PT" => "Portugal",
            "PR" => "Puerto Rico",
            "QA" => "Qatar",
            "RE" => "Réunion",
            "RO" => "Romania",
            "RU" => "Russian Federation",
            "RW" => "Rwanda",
            "BL" => "Saint Barthélemy",
            "SH" => "Saint Helena, Ascension and Tristan da Cunha",
            "KN" => "Saint Kitts and Nevis",
            "LC" => "Saint Lucia",
            "MF" => "Saint Martin (French part)",
            "PM" => "Saint Pierre and Miquelon",
            "VC" => "Saint Vincent and the Grenadines",
            "WS" => "Samoa",
            "SM" => "San Marino",
            "ST" => "Sao Tome and Principe",
            "SA" => "Saudi Arabia",
            "SN" => "Senegal",
            "RS" => "Serbia",
            "SC" => "Seychelles",
            "SL" => "Sierra Leone",
            "SG" => "Singapore",
            "SX" => "Sint Maarten (Dutch part)",
            "SK" => "Slovakia",
            "SI" => "Slovenia",
            "SB" => "Solomon Islands",
            "SO" => "Somalia",
            "ZA" => "South Africa",
            "GS" => "South Georgia and the South Sandwich Islands",
            "SS" => "South Sudan",
            "ES" => "Spain",
            "LK" => "Sri Lanka",
            "SD" => "Sudan",
            "SR" => "Suriname",
            "SJ" => "Svalbard and Jan Mayen",
            "SZ" => "Swaziland",
            "SE" => "Sweden",
            "CH" => "Switzerland",
            "SY" => "Syrian Arab Republic",
            "TW" => "Taiwan, Province of China",
            "TJ" => "Tajikistan",
            "TZ" => "Tanzania, United Republic of",
            "TH" => "Thailand",
            "TL" => "Timor-Leste",
            "TG" => "Togo",
            "TK" => "Tokelau",
            "TO" => "Tonga",
            "TT" => "Trinidad and Tobago",
            "TN" => "Tunisia",
            "TR" => "Turkey",
            "TM" => "Turkmenistan",
            "TC" => "Turks and Caicos Islands",
            "TV" => "Tuvalu",
            "UG" => "Uganda",
            "UA" => "Ukraine",
            "AE" => "United Arab Emirates",
            "GB" => "United Kingdom",
            "US" => "United States",
            "UM" => "United States Minor Outlying Islands",
            "UY" => "Uruguay",
            "UZ" => "Uzbekistan",
            "VU" => "Vanuatu",
            "VE" => "Venezuela, Bolivarian Republic of",
            "VN" => "Viet Nam",
            "VG" => "Virgin Islands, British",
            "VI" => "Virgin Islands, U.S.",
            "WF" => "Wallis and Futuna",
            "EH" => "Western Sahara",
            "YE" => "Yemen",
            "ZM" => "Zambia",
            "ZW" => "Zimbabwe"
        ];

        $order = new Order();

        if($cart->orderId != 0)
        {
            $order = $order->find($cart->orderId);
        }

        return view("cart/checkoutaddress")->with(["artworks" => $cart->artworks, "totalPrice" => $totalPrice, "countries" => $countries, "order" => $order]);
    }

    public function checkoutpayment($type)
    {
        if(!Session::has("cart"))
        {
            return view("cart/index");
        }

        $oldCart = Session::get("cart");
        $cart = new Cart($oldCart);
        $totalPrice = $cart->getFullPrice();

        if($type == "credit")
        {
            return view("cart/checkoutpayment")->with(["artworks" => $cart->artworks, "totalPrice" => $totalPrice, "type" => $type]);
        }
        else if($type == "paypal")
        {
            return view("cart/checkoutpaypal")->with(["artworks" => $cart->artworks, "totalPrice" => $totalPrice, "type" => $type]);
        }
        else
        {
            return view("cart/checkoutpayment")->with(["artworks" => $cart->artworks, "totalPrice" => $totalPrice, "type" => $type]);
        }

    }

    public function postcheckoutaddress(Request $request)
    {
        if(!Session::has("cart"))
        {
            return view("cart/index");
        }

        $this->validate($request,
            [
                "name" => "required",
                "surname" => "required",
                "email" => "required",
                "name_shipping" => "required",
                "surname_shipping" => "required",
                "address" => "required",
                "apartment" => "required",
                "country" => "required",
                "postal_code" => "required",
                "phone" => "required"
            ]);

        $oldCart = Session::get("cart");
        $cart = new Cart($oldCart);

        if($cart->orderId == 0)
        {
            $order = new Order();
        }
        else
        {
            $order = Order::find($cart->orderId);
        }

        $order->cart = serialize($cart);
        $order->name = $request->input("name");
        $order->surname = $request->input("surname");
        $order->email = $request->input("email");
        $order->name_shipping = $request->input("name_shipping");
        $order->surname_shipping = $request->input("surname_shipping");
        $order->address = $request->input("address");
        $order->apartment = $request->input("apartment");
        $order->country = $request->input("country");
        $order->postal_code = $request->input("postal_code");
        $order->phone = $request->input("phone");

        if(Auth::user())
        {
            Auth::user()->orders()->save($order);
        }
        else
        {
            $order->save();
        }

        $cart->setOrderId($order->id);
        $request->session()->put("cart", $cart);

        return redirect("/checkoutpayment/credit");
    }

    public function completecheckout(Request $request)
    {

        $this->validate($request,
            [
                "card_type" => "required",
                "name_credit" => "required",
                "surname_credit" => "required",
                "card_number" => "required",
                "card_expirity_month" => "required",
                "card_expirity_year" => "required",
                "card_cvc" => "required"
            ]);

        Stripe::setApiKey(STRIPE_KEY);
        $oldCart = Session::get("cart");
        $cart = new Cart($oldCart);

        try
        {

            $charge = Charge::create(
                array(
                    "amount" => $cart->getFullPrice() * 100,
                    "currency" => "eur",
                    "source" => $request->input("stripeToken"),
                    "description" => "Payment"
                ));

            $order = Order::find($cart->orderId);
            $order->cart = serialize($cart);
            $order->card_type = $request->input("card_type");
            $order->name_credit = $request->input("name_credit");
            $order->surname_credit = $request->input("surname_credit");
            $order->card_number = $request->input("card_number");
            $order->payment_type = $request->input("payment_type");
            $order->completed = true;
            $order->price = $cart->getFullPrice();
            $order->payment_id = $charge->id;

            if(Auth::user())
            {
                Auth::user()->orders()->save($order);
            }
            else
            {
                $order->save();
            }

        }
        catch(\Exception $exception)
        {
            return redirect("/checkoutpayment/" . $request->input("payment_type"))->with(["error" => $exception->getMessage()]);
        }

        Session::forget("cart");
        return redirect("/thankyou");
    }

    public function completecheckoutpaypal(Request $request)
    {
        // save order to the database
        $oldCart = Session::get("cart");
        $cart = new Cart($oldCart);

        try
        {
            $order = Order::find($cart->orderId);
            $order->cart = serialize($cart);
            $order->payment_type = $request->input("payment_type");
            $order->price = $cart->getFullPrice();

            if(Auth::user())
            {
                Auth::user()->orders()->save($order);
            }
            else
            {
                $order->save();
            }
        }
        catch(\Exception $exception)
        {
            return redirect("/checkoutpayment/" . $request->input("payment_type"))->with(["error" => $exception->getMessage()]);
        }


        // start paypal payment
        $authtoken = new OAuthTokenCredential(
            PAYPAL_CLIENTID,
            PAYPAL_SECRET
        );
        $paypal = new ApiContext($authtoken);

        $price = $cart->getFullPrice();
        $shipping = 4.99;
        $totalPrice = $price + $shipping;

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $itemList = new ItemList();
        $itemArray = array();

        foreach ($cart->artworks as $artwork)
        {
            $item = new Item();
            $item->setName($artwork["artwork"]->title . " " . $artwork["size"])->setCurrency("EUR")->setQuantity($artwork["count"])->setPrice($artwork["price"]);
            array_push($itemArray, $item);
        }

        $itemList->setItems($itemArray);

        $details = new Details();
        $details->setShipping($shipping)->setSubtotal($price);

        $amount = new Amount();
        $amount->setCurrency("EUR")->setTotal($totalPrice)->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)->setDescription("Payment")->setItemList($itemList)->setInvoiceNumber(uniqid());

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(url("/executepaypal"))->setCancelUrl(url("/gallery"));

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions([$transaction]);

        try
        {
            $payment->create($paypal);
        }
        catch(\Exception $exception)
        {
            die($exception);
        }

        $approvalUrl = $payment->getApprovalLink();

        header("Location: " . $approvalUrl);
        die();
    }

    public function executepaypal()
    {

        $paymentId = $_GET["paymentId"];
        $payerId = $_GET["PayerID"];

        if(!isset($paymentId, $payerId))
        {
            return redirect("/checkoutpayment/credit")->with(["error" => "Payment failed!"]);
        }

        $authtoken = new OAuthTokenCredential(
            PAYPAL_CLIENTID,
            PAYPAL_SECRET
        );
        $paypal = new ApiContext($authtoken);

        $payment = Payment::get($paymentId, $paypal);

        $execute = new PaymentExecution();
        $execute->setPayerId($payerId);

        try
        {
            $result = $payment->execute($execute, $paypal);
        }
        catch(\Exception $exception)
        {
            return redirect("/checkoutpayment/credit")->with(["error" => $exception]);
        }

        $oldCart = Session::get("cart");
        $cart = new Cart($oldCart);

        try
        {
            $order = Order::find($cart->orderId);
            $order->completed = true;
            $order->payment_id = $paymentId;
            $order->payer_id = $payerId;

            if(Auth::user())
            {
                Auth::user()->orders()->save($order);
            }
            else
            {
                $order->save();
            }
        }
        catch(\Exception $exception)
        {
            return redirect("/get-cart/")->with(["error" => $exception->getMessage()]);
        }

        Session::forget("cart");
        return redirect("/thankyou");
    }

    public function thankyou()
    {
        return view("cart/thankyou");
    }
}
