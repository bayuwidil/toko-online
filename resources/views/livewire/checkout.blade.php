<div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8 flex flex-col lg:flex-row gap-10">
    
    <!-- Bagian Kiri: Form Data -->
    <div class="w-full lg:w-3/5 space-y-8">
        
        <!-- Informasi Kontak -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Informasi Kontak</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" wire:model="email" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP / WhatsApp</label>
                    <input type="text" wire:model="phone" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    @error('phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">

    <h2 class="text-xl font-bold text-gray-900 mb-6">
        Alamat Pengiriman
    </h2>

    <div class="space-y-4">

        {{-- Nama --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Nama Lengkap Penerima
            </label>

            <input
                type="text"
                wire:model.live="name"
                class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
            >

            @error('name')
                <span class="text-red-500 text-xs">
                    {{ $message }}
                </span>
            @enderror
        </div>


        {{-- No HP --}}
        <div>

    <label class="block text-sm font-medium text-gray-700 mb-1">
        Nomor HP / WhatsApp
    </label>

    <div class="flex gap-2">

        <input
            type="text"
            wire:model.live="phone"
            placeholder="08xxxxxxxxxx"
            class="flex-1 border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
            @if($phoneVerified) readonly @endif
        >

        @if(!$phoneVerified)

            <button
                type="button"
                wire:click="sendVerificationCode"
                wire:loading.attr="disabled"
                wire:target="sendVerificationCode"
                class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700"
            >
                <span wire:loading.remove wire:target="sendVerificationCode">
                    Kirim Kode
                </span>

                <span wire:loading wire:target="sendVerificationCode">
                    Mengirim...
                </span>
            </button>

        @else

            <span class="flex items-center px-3 text-green-600 font-semibold">
                ✓ Terverifikasi
            </span>

        @endif

    </div>

    @error('phone')
        <span class="text-red-500 text-xs">
            {{ $message }}
        </span>
    @enderror

</div>

@if($verificationNotice)
    <div class="mt-4 bg-green-50 border border-green-200 rounded-xl p-4">
        <p class="text-sm font-medium text-green-700">{{ $verificationNotice }}</p>
    </div>
@endif

@if($otpSent && !$phoneVerified)

    <div class="mt-4 bg-indigo-50 border border-indigo-100 rounded-xl p-4">

        <label class="block text-sm font-semibold text-gray-800 mb-2">
            Kode Verifikasi WhatsApp
        </label>

        <p class="text-xs text-gray-600 mb-3">
            Masukkan kode 6 digit yang dikirim ke WhatsApp Anda.
        </p>

        <div class="flex gap-2">

            <input
                type="text"
                wire:model.live="otp"
                maxlength="6"
                inputmode="numeric"
                placeholder="000000"
                class="flex-1 border-gray-300 rounded-lg text-center tracking-widest font-bold"
            >

            <button
                type="button"
                wire:click="verifyPhone"
                wire:loading.attr="disabled"
                wire:target="verifyPhone"
                class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
            >
                <span wire:loading.remove wire:target="verifyPhone">
                    Verifikasi
                </span>

                <span wire:loading wire:target="verifyPhone">
                    Memeriksa...
                </span>
            </button>

        </div>

        @error('otp')
            <span class="text-red-500 text-xs mt-2 block">
                {{ $message }}
            </span>
        @enderror

    </div>

@endif


        {{-- Alamat Manual --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Alamat Lengkap
            </label>

            <textarea
                wire:model.live="full_address"
                rows="3"
                placeholder="Nama jalan, nomor rumah, RT/RW, patokan, dll."
                class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
            ></textarea>

            @error('full_address')
                <span class="text-red-500 text-xs">
                    {{ $message }}
                </span>
            @enderror
        </div>


        {{-- Search Lokasi --}}
        <div class="relative">

            <label class="block text-sm font-medium text-gray-700 mb-1">
                Cari Desa / Kelurahan / Kecamatan / Kota
            </label>

            <input
                type="text"
                wire:model.live.debounce.300ms="citySearch"
                placeholder="Ketik nama desa, kecamatan, kota atau kabupaten"
                class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                autocomplete="off"
            >

            @if(!empty($citySearchResults))

                <div class="absolute left-0 right-0 mt-1 border border-gray-200 rounded-lg bg-white shadow-xl max-h-64 overflow-y-auto z-50">

                    @foreach($citySearchResults as $city)

                        <button
                            type="button"
                            wire:click="selectCity('{{ $city['id'] ?? '' }}')"
                            class="w-full text-left px-4 py-3 hover:bg-indigo-50 border-b border-gray-100 last:border-b-0"
                        >

                            <span class="block text-sm font-semibold text-gray-900">
                                {{ $city['label'] ?? '-' }}
                            </span>

                            <span class="block text-xs text-gray-500 mt-1">

                                @if(!empty($city['subdistrict_name']))
                                    {{ $city['subdistrict_name'] }},
                                @endif

                                @if(!empty($city['district_name']))
                                    {{ $city['district_name'] }},
                                @endif

                                {{ $city['city_name'] ?? '' }},
                                {{ $city['province_name'] ?? '' }}

                                @if(!empty($city['zip_code']))
                                    - {{ $city['zip_code'] }}
                                @endif

                            </span>

                        </button>

                    @endforeach

                </div>

            @endif

            @error('selectedCity')
                <span class="text-red-500 text-xs">
                    {{ $message }}
                </span>
            @enderror

        </div>


        {{-- Detail lokasi terpilih --}}
        @if($selectedCity)

            <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-4">

                <div class="flex items-start gap-3">

                    <div class="text-indigo-600">
                        📍
                    </div>

                    <div class="flex-1">

                        <p class="text-sm font-semibold text-indigo-900">
                            Lokasi Pengiriman
                        </p>

                        <p class="text-sm text-gray-700 mt-1">
                            {{ $selectedSubdistrictName }},
                            {{ $selectedDistrictName }},
                            {{ $selectedCityName }},
                            {{ $selectedProvinceName }}
                        </p>

                    </div>

                </div>

            </div>

        @endif


        {{-- Kode Pos --}}
        <div>

            <label class="block text-sm font-medium text-gray-700 mb-1">
                Kode Pos
            </label>

            <input
                type="text"
                wire:model.live="postal_code"
                class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                maxlength="10"
            >

            @error('postal_code')
                <span class="text-red-500 text-xs">
                    {{ $message }}
                </span>
            @enderror

        </div>

    </div>

</div>

        <!-- Metode Pengiriman -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Metode Pengiriman</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Ekspedisi</label>
                    <select wire:model.live="courier" class="w-full border-gray-300 rounded-lg" @if(!$selectedCity) disabled @endif>
                        <option value="">Pilih Kurir</option>
                        <option value="jne">JNE</option>
                        <option value="pos">POS Indonesia</option>
                        <option value="tiki">TIKI</option>
                    </select>
                </div>
                
                @if(count($availableServices) > 0)

    <div>

        <label class="block text-sm font-medium text-gray-700 mb-1">
            Layanan Kurir
        </label>

        <select
            wire:model.live="selectedService"
            class="w-full border-gray-300 rounded-lg"
        >

            <option value="">
                Pilih Layanan
            </option>

            @foreach($availableServices as $service)

                <option value="{{ $service['service'] }}">

                    {{ $service['service'] }}

                    -
                    Rp {{ number_format($service['cost'], 0, ',', '.') }}

                    @if(!empty($service['etd']))
                        ({{ $service['etd'] }} hari)
                    @endif

                </option>

            @endforeach

        </select>

        @error('selectedService')
            <span class="text-red-500 text-xs">
                {{ $message }}
            </span>
        @enderror

    </div>

@endif
            </div>
        </div>
    </div>

    <!-- Bagian Kanan: Ringkasan Pesanan (Sticky) -->
    <div class="w-full lg:w-2/5">
        <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200 sticky top-24">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Ringkasan Pesanan</h2>
            
            <div class="divide-y divide-gray-200 mb-6 max-h-64 overflow-y-auto pr-2">
                @foreach($cart as $item)
                    <div class="py-4 flex gap-4">
                        <div class="w-16 h-16 bg-white border border-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                            @if($item['image'])
                                <img src="{{ asset('storage/' . $item['image']) }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="flex-1 flex flex-col justify-between">
                            <p class="text-sm font-medium text-gray-900 line-clamp-2">{{ $item['name'] }}</p>
                            <p class="text-xs text-gray-500">Qty: {{ $item['qty'] }}</p>
                        </div>
                        <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($item['price'] * $item['qty'], 0, ',', '.') }}</p>
                    </div>
                @endforeach
            </div>

            <div class="space-y-3 text-sm mb-6 border-t border-gray-200 pt-4">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal</span>
                    <span class="font-medium text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-600">
                    <span>Total Berat</span>
                    <span class="font-medium text-gray-900">
                        {{ number_format($totalWeight, 0, ',', '.') }} gram
                    </span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Ongkos Kirim</span>
                    <span class="font-medium text-gray-900">
                        {{ $shippingCost > 0 ? 'Rp ' . number_format($shippingCost, 0, ',', '.') : '-' }}
                    </span>
                </div>
            </div>

            <div class="flex justify-between text-lg font-bold border-t border-gray-200 pt-4 mb-6">
                <span class="text-gray-900">Total Pembayaran</span>
                <span class="text-indigo-600">Rp {{ number_format($grandTotal, 0, ',', '.') }}</span>
            </div>

            @if (session()->has('error'))
                <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <button wire:click="processCheckout" wire:loading.attr="disabled" @disabled(!$phoneVerified)
                    class="w-full bg-gray-900 hover:bg-indigo-600 text-white font-bold py-4 px-6 rounded-xl transition-colors duration-200 shadow-lg flex justify-center items-center gap-2 disabled:bg-gray-400">
                <span wire:loading.remove wire:target="processCheckout">Bayar Sekarang &rarr;</span>
                <span wire:loading wire:target="processCheckout">Memproses...</span>
            </button>
        </div>
    </div>
</div>

<!-- Script Midtrans (Dimuat di Halaman Ini Saja) -->
@push('scripts')

<script
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"
></script>

<script>

document.addEventListener('livewire:init', () => {

    const CHECKOUT_FORM_KEY = 'checkout_form_state';

    function saveCheckoutState() {
        const root = document.querySelector('[wire\\:id]');
        if (!root) return;

        const componentId = root.getAttribute('wire:id');
        if (!componentId) return;

        const component = Livewire.find(componentId);
        if (!component) return;

        const state = {
            name: component.get('name'),
            email: component.get('email'),
            phone: component.get('phone'),
            full_address: component.get('full_address'),
            postal_code: component.get('postal_code'),
            citySearch: component.get('citySearch'),
            selectedCity: component.get('selectedCity'),
            selectedCityName: component.get('selectedCityName'),
            selectedProvince: component.get('selectedProvince'),
            selectedProvinceName: component.get('selectedProvinceName'),
            selectedDistrict: component.get('selectedDistrict'),
            selectedDistrictName: component.get('selectedDistrictName'),
            selectedSubdistrict: component.get('selectedSubdistrict'),
            selectedSubdistrictName: component.get('selectedSubdistrictName'),
            courier: component.get('courier'),
            selectedService: component.get('selectedService'),
        };

        localStorage.setItem(CHECKOUT_FORM_KEY, JSON.stringify(state));
    }

    function restoreCheckoutState() {
        const root = document.querySelector('[wire\\:id]');
        if (!root) return;

        const componentId = root.getAttribute('wire:id');
        if (!componentId) return;

        const component = Livewire.find(componentId);
        if (!component) return;

        const saved = localStorage.getItem(CHECKOUT_FORM_KEY);
        if (!saved) return;

        try {
            const state = JSON.parse(saved);
            Object.entries(state).forEach(([key, value]) => {
                if (value !== undefined && value !== null) {
                    component.set(key, value);
                }
            });
        } catch (error) {
            console.warn('Checkout form restore failed:', error);
        }
    }

    document.addEventListener('input', (event) => {
        const target = event.target;
        if (!target || !target.closest('[wire\\:model]')) return;
        saveCheckoutState();
    });

    Livewire.on('verification-success', () => {
        saveCheckoutState();
        setTimeout(() => {
            window.location.reload();
        }, 400);
    });

    restoreCheckoutState();

    Livewire.on('pay-with-midtrans', (event) => {

        const token = event.token;

        if (!token) {

            alert('Token pembayaran tidak ditemukan.');

            return;
        }

        snap.pay(token, {

            onSuccess: function(result) {

                window.location.href = "{{ route('payment.success') }}";

            },

            onPending: function(result) {

                window.location.href = "{{ route('payment.pending') }}";

            },

            onError: function(result) {

                window.location.href = "{{ route('payment.failed') }}";

            },

            onClose: function() {

                console.log(
                    'User menutup pembayaran Midtrans.'
                );

            }

        });

    });

});

</script>

@endpush