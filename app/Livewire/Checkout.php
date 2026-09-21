<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class Checkout extends Component
{
    public $cart = [];

    public $subtotal = 0;
    public $totalWeight = 0;
    public $shippingCost = 0;
    public $grandTotal = 0;

    /*
    |--------------------------------------------------------------------------
    | Pelanggan
    |--------------------------------------------------------------------------
    */

    public $name= '';
    public $email= '';
    public $phone= '';

    /*
    |--------------------------------------------------------------------------
    | Alamat
    |--------------------------------------------------------------------------
    */

    public $full_address= '';
    public $postal_code= '';

    /*
    |--------------------------------------------------------------------------
    | RajaOngkir Destination
    |--------------------------------------------------------------------------
    */

    public $selectedCity = '';
    public $selectedCityName = '';

    public $selectedProvince = '';
    public $selectedProvinceName = '';

    public $selectedDistrict = '';
    public $selectedDistrictName = '';

    public $selectedSubdistrict = '';
    public $selectedSubdistrictName = '';

    public $citySearch = '';
    public $citySearchResults = [];

    /*
    |--------------------------------------------------------------------------
    | Shipping
    |--------------------------------------------------------------------------
    */

    public $courier = '';
    public $availableServices = [];
    public $selectedService = '';

    /*
    |--------------------------------------------------------------------------
    | Origin
    |--------------------------------------------------------------------------
    */

    public $originCityId = 39;

    public function mount()
    {
        $this->cart = session()->get('cart', []);

        if (empty($this->cart)) {
            return redirect('/');
        }

        $this->calculateCart();
    }

    /*
    |--------------------------------------------------------------------------
    | Hitung Cart
    |--------------------------------------------------------------------------
    */

    private function calculateCart()
    {
        $this->subtotal = 0;
        $this->totalWeight = 0;

        foreach ($this->cart as $item) {

            $qty = (int) ($item['qty'] ?? 1);
            $price = (int) ($item['price'] ?? 0);
            $weight = (int) ($item['weight'] ?? 0);

            $this->subtotal += $price * $qty;

            /*
             * Berat produk × quantity
             */
            $this->totalWeight += $weight * $qty;
        }

        $this->grandTotal =
            $this->subtotal + $this->shippingCost;
    }

    /*
    |--------------------------------------------------------------------------
    | Search Kota / Kabupaten / Kecamatan / Desa
    |--------------------------------------------------------------------------
    */

    public function updatedCitySearch($value)
    {
        $this->citySearch = trim((string) $value);

        /*
         * Kalau user mengetik ulang setelah memilih lokasi,
         * pilihan lama harus dihapus.
         */
        if (
            $this->selectedCity &&
            $this->selectedCityName &&
            strcasecmp(
                $this->citySearch,
                $this->selectedCityName
            ) !== 0
        ) {
            $this->selectedCity = '';
            $this->selectedCityName = '';

            $this->selectedProvince = '';
            $this->selectedProvinceName = '';

            $this->selectedDistrict = '';
            $this->selectedDistrictName = '';

            $this->selectedSubdistrict = '';
            $this->selectedSubdistrictName = '';

            $this->postal_code = '';
        }

        $this->resetShipping();

        if (mb_strlen($this->citySearch) < 2) {
            $this->citySearchResults = [];
            return;
        }

        try {

            $response = Http::timeout(10)
                ->withHeaders([
                    'key' => env('RAJAONGKIR_API_KEY'),
                ])
                ->get(
                    'https://rajaongkir.komerce.id/api/v1/destination/domestic-destination',
                    [
                        'search' => $this->citySearch,
                        'limit' => 10,
                        'offset' => 0,
                    ]
                );

            if (!$response->successful()) {
                $this->citySearchResults = [];
                return;
            }

            $payload = $response->json();

            $this->citySearchResults =
                collect($payload['data'] ?? [])
                    ->filter(function ($location) {
                        return !empty($location['id']);
                    })
                    ->take(10)
                    ->values()
                    ->all();

        } catch (\Throwable $e) {

            Log::error(
                'RajaOngkir destination search error',
                [
                    'message' => $e->getMessage(),
                    'search' => $this->citySearch,
                ]
            );

            $this->citySearchResults = [];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Pilih Destination
    |--------------------------------------------------------------------------
    */

    public function selectCity($cityId)
    {
        $city = collect($this->citySearchResults)
            ->firstWhere('id', (string) $cityId);

        if (!$city) {
            return;
        }

        $this->selectedCity =
            (string) ($city['id'] ?? '');

        $this->selectedCityName =
            $city['city_name'] ?? '';

        $this->selectedProvince =
            $city['province_id'] ?? '';

        $this->selectedProvinceName =
            $city['province_name'] ?? '';

        $this->selectedDistrict =
            $city['district_id'] ?? '';

        $this->selectedDistrictName =
            $city['district_name'] ?? '';

        $this->selectedSubdistrict =
            $city['subdistrict_id'] ?? '';

        $this->selectedSubdistrictName =
            $city['subdistrict_name'] ?? '';

        $this->postal_code =
            $city['zip_code'] ?? '';

        $this->citySearch =
            $city['label']
            ?? trim(
                ($city['subdistrict_name'] ?? '') . ', ' .
                ($city['district_name'] ?? '') . ', ' .
                ($city['city_name'] ?? '') . ', ' .
                ($city['province_name'] ?? '')
            );

        $this->citySearchResults = [];

        $this->resetShipping();
    }

    /*
    |--------------------------------------------------------------------------
    | Courier
    |--------------------------------------------------------------------------
    */

    public function updatedCourier()
    {
        $this->resetShipping();

        if (!$this->selectedCity || !$this->courier) {
            return;
        }

        /*
         * Pastikan berat dihitung ulang dari cart.
         */
        $this->calculateCart();

        $weight = (int) max(
            $this->totalWeight,
            1
        );

        try {

            $response = Http::timeout(15)
                ->asForm()
                ->withHeaders([
                    'key' => env('RAJAONGKIR_API_KEY'),
                ])
                ->post(
                    'https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost',
                    [
                        'origin' => $this->originCityId,
                        'destination' => $this->selectedCity,
                        'weight' => $weight,
                        'courier' => strtolower($this->courier),
                    ]
                );

            if ($response->successful()) {

                $payload = $response->json();

                $results =
                    $payload['data']
                    ?? $payload['rajaongkir']['results']
                    ?? $payload['results']
                    ?? [];

                $this->availableServices =
                    $this->extractRajaOngkirServices(
                        $results
                    );
            }

        } catch (\Throwable $e) {

            Log::error(
                'RajaOngkir shipping calculation error',
                [
                    'message' => $e->getMessage(),
                    'destination' => $this->selectedCity,
                    'weight' => $weight,
                    'courier' => $this->courier,
                ]
            );

            $this->availableServices = [];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Pilih Service
    |--------------------------------------------------------------------------
    */

    public function updatedSelectedService($value)
    {
        if (!$value) {

            $this->shippingCost = 0;

            $this->grandTotal =
                $this->subtotal;

            return;
        }

        $service = collect(
            $this->availableServices
        )->firstWhere(
            'service',
            $value
        );

        if (!$service) {
            return;
        }

        $this->shippingCost =
            (int) ($service['cost'] ?? 0);

        $this->grandTotal =
            $this->subtotal
            + $this->shippingCost;
    }

    /*
    |--------------------------------------------------------------------------
    | Reset Shipping
    |--------------------------------------------------------------------------
    */

    private function resetShipping()
    {
        $this->availableServices = [];

        $this->selectedService = '';

        $this->shippingCost = 0;

        $this->grandTotal =
            $this->subtotal;
    }

    /*
    |--------------------------------------------------------------------------
    | Extract Destination
    |--------------------------------------------------------------------------
    */

    private function extractRajaOngkirResults($response)
    {
        $payload = $response->json();

        if (!is_array($payload)) {
            return [];
        }

        return $payload['data']
            ?? $payload['rajaongkir']['results']
            ?? $payload['results']
            ?? [];
    }

    /*
    |--------------------------------------------------------------------------
    | Extract Shipping Services
    |--------------------------------------------------------------------------
    */

    private function extractRajaOngkirServices($results)
    {
        if (!is_array($results) || empty($results)) {
            return [];
        }

        return collect($results)
            ->map(function ($service) {

                return [
                    'service' =>
                        $service['service'] ?? '',

                    'description' =>
                        $service['description'] ?? '',

                    'cost' =>
                        (int) ($service['cost'] ?? 0),

                    'etd' =>
                        $service['etd'] ?? '',
                ];
            })
            ->filter(function ($service) {
                return !empty($service['service']);
            })
            ->values()
            ->all();
    }

    /*
    |--------------------------------------------------------------------------
    | Buat Alamat Lengkap
    |--------------------------------------------------------------------------
    */

    private function buildShippingAddress()
    {
        return implode(', ', array_filter([

            $this->full_address,

            $this->selectedSubdistrictName
                ? 'Desa/Kelurahan ' .
                    $this->selectedSubdistrictName
                : null,

            $this->selectedDistrictName
                ? 'Kecamatan ' .
                    $this->selectedDistrictName
                : null,

            $this->selectedCityName
                ? 'Kabupaten/Kota ' .
                    $this->selectedCityName
                : null,

            $this->selectedProvinceName,

            $this->postal_code
                ? 'Kode Pos ' .
                    $this->postal_code
                : null,
        ]));
    }

    /*
    |--------------------------------------------------------------------------
    | PROCESS CHECKOUT
    |--------------------------------------------------------------------------
    */

    public function processCheckout()
    {
        $this->validate([

            'name' =>
                'required|string|max:255',

            'email' =>
                'required|email|max:255',

            'phone' =>
                'required|string|max:30',

            'full_address' =>
                'required|string|min:10',

            'selectedCity' =>
                'required',

            'selectedCityName' =>
                'required',

            'selectedProvinceName' =>
                'required',

            'selectedDistrictName' =>
                'required',

            'selectedSubdistrictName' =>
                'required',

            'postal_code' =>
                'required|string|max:10',

            'courier' =>
                'required',

            'selectedService' =>
                'required',
        ]);

        /*
         * Pastikan cart masih ada.
         */
        if (empty($this->cart)) {

            session()->flash(
                'error',
                'Keranjang kosong.'
            );

            return;
        }

        /*
         * Hitung ulang berat dan subtotal
         */
        $this->calculateCart();

        /*
         * Pastikan ongkir tersedia
         */
        if ($this->shippingCost <= 0) {

            session()->flash(
                'error',
                'Silakan pilih layanan pengiriman terlebih dahulu.'
            );

            return;
        }

        /*
         * Grand total
         */
        $this->grandTotal =
            $this->subtotal
            + $this->shippingCost;

        DB::beginTransaction();

        try {

            /*
             * Alamat lengkap
             */
            $shippingAddress =
                $this->buildShippingAddress();

            /*
             * Invoice unik
             */
            $orderNumber =
                'INV-' .
                now()->format('YmdHis') .
                '-' .
                strtoupper(
                    substr(uniqid(), -5)
                );

            /*
             * Simpan Order
             */
            $order = Order::create([

                'user_id' =>
                    auth()->id(),

                'order_number' =>
                    $orderNumber,

                /*
                 * Data penerima
                 */
                'shipping_name' =>
                    $this->name,

                'shipping_phone' =>
                    $this->phone,

                /*
                 * Alamat lengkap
                 */
                'shipping_address' =>
                    $shippingAddress,

                'shipping_subdistrict' =>
                    $this->selectedSubdistrictName,

                'shipping_district' =>
                    $this->selectedDistrictName,

                'shipping_city' =>
                    $this->selectedCityName,

                'shipping_province' =>
                    $this->selectedProvinceName,

                'shipping_postal_code' =>
                    $this->postal_code,

                'shipping_destination_id' =>
                    $this->selectedCity,

                /*
                 * Shipping
                 */
                'shipping_weight' =>
                    $this->totalWeight,

                'shipping_courier' =>
                    strtoupper($this->courier),

                'shipping_service' =>
                    $this->selectedService,

                'subtotal' =>
                    $this->subtotal,

                'shipping_cost' =>
                    $this->shippingCost,

                'grand_total' =>
                    $this->grandTotal,

                /*
                 * Status
                 */
                'status' =>
                    'pending',

                'payment_status' =>
                    'unpaid',
            ]);

            /*
             * Simpan item order
             */
            foreach ($this->cart as $item) {

                OrderItem::create([

                    'order_id' =>
                        $order->id,

                    'product_id' =>
                        $item['id'],

                    'quantity' =>
                        (int) $item['qty'],

                    'price' =>
                        (int) $item['price'],

                    /*
                     * Simpan berat snapshot
                     */
                    'weight' =>
                        (int) ($item['weight'] ?? 0),
                ]);
            }

            /*
             * Konfigurasi Midtrans
             */
            Config::$serverKey =
                env('MIDTRANS_SERVER_KEY');

            Config::$isProduction =
                (bool) env(
                    'MIDTRANS_IS_PRODUCTION',
                    false
                );

            Config::$isSanitized = true;

            Config::$is3ds = true;

            /*
             * Parameter Midtrans
             */
            $params = [

                'transaction_details' => [

                    'order_id' =>
                        $orderNumber,

                    'gross_amount' =>
                        (int) $this->grandTotal,
                ],

                'customer_details' => [

                    'first_name' =>
                        $this->name,

                    'email' =>
                        $this->email,

                    'phone' =>
                        $this->phone,
                ],

                'shipping_address' => [

                    'first_name' =>
                        $this->name,

                    'phone' =>
                        $this->phone,

                    'address' =>
                        $shippingAddress,

                    'city' =>
                        $this->selectedCityName,

                    'postal_code' =>
                        $this->postal_code,

                    'country_code' =>
                        'IDN',
                ],
            ];

            /*
             * Ambil Snap Token
             */
            $snapToken =
                Snap::getSnapToken($params);

            /*
             * Simpan Snap Token
             */
            $order->update([

                'snap_token' =>
                    $snapToken,
            ]);

            /*
             * Commit
             */
            DB::commit();

            /*
             * Kosongkan cart
             */
            session()->forget('cart');

            /*
             * Buka Midtrans
             */
            $this->dispatch(
                'pay-with-midtrans',
                token: $snapToken
            );

        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error(
                'Checkout Error',
                [
                    'message' =>
                        $e->getMessage(),

                    'trace' =>
                        $e->getTraceAsString(),
                ]
            );

            session()->flash(
                'error',
                'Terjadi kesalahan saat membuat pesanan: ' .
                $e->getMessage()
            );
        }
    }

    public function render()
    {
        return view(
            'livewire.checkout'
        );
    }
}