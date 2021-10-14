<?php

namespace Buatin\Accurate\Controllers;

use App\Http\Controllers\Controller;
use Buatin\Accurate\Accurate;
use Buatin\Accurate\Models\AccurateSetting;
use GuzzleHttp\Exception\GuzzleException;

class AuthController extends Controller
{
    protected $model;

    public function __construct()
    {
        $this->model = new AccurateSetting();
    }

    public function connect()
    {
        $url = Accurate::ACCURATE_AUTH_ENDPONT . '/authorize';
        $query = [
            'client_id' => config('accurate.ACCURATE_CLIENT_ID'),
            'response_type' => 'code',
            'redirect_uri' => config('accurate.ACCURATE_CALLBACK'),
            'scope' => 'customer_view customer_save customer_delete item_save item_delete item_view stock_mutation_history_view sellingprice_adjustment_view purchase_invoice_save purchase_invoice_delete purchase_invoice_view sales_invoice_save sales_invoice_view glaccount_save glaccount_delete glaccount_view sales_receipt_save sales_receipt_delete sales_receipt_view journal_voucher_save journal_voucher_delete journal_voucher_view sales_order_view employee_view employee_delete',
        ];

        $scope = $this->model->find('scope');
        $scope->value = $query['scope'];
        $scope->save();

        $session = $this->model->find('session');
        $session->value = null;
        $session->save();

        return redirect(url($url) . '?' . http_build_query($query));
    }

    /**
     * @throws GuzzleException
     */
    public function code($code)
    {
        if (!empty($code)) {
            $code = $this->model->find('code');
            $code->value = request()->get('code');
            $code->save();

            $basic_auth = base64_encode(config('accurate.ACCURATE_CLIENT_ID') . ':' . config('accurate.ACCURATE_CLIENT_SECRET'));

            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . $basic_auth,
            ];

            $client = new \GuzzleHttp\Client([
                'headers' => $headers,
                'verify' => false,
            ]);

            $url = Accurate::ACCURATE_AUTH_ENDPONT . '/token';

            $body = [
                'code' => $code->value,
                'grant_type' => 'authorization_code',
                'redirect_uri' => config('accurate.ACCURATE_CALLBACK'),
            ];
            $request = $client->post(
                $url,
                [
                    'form_params' => $body,
                ]
            );
            if ($request->getStatusCode() == 200) {
                $response = json_decode($request->getBody()->getContents());

                $access_token = $this->model->find('access_token');
                $access_token->value = $response->access_token;
                $access_token->save();

                $refresh_token = $this->model->find('refresh_token');
                $refresh_token->value = $response->refresh_token;
                $refresh_token->save();

                $token_type = $this->model->find('token_type');
                $token_type->value = $response->token_type;
                $token_type->save();

                $expire = $this->model->find('expire');
                $expire->value = now()->addSeconds($response->expires_in);
                $expire->save();
            }
        }
    }
}
