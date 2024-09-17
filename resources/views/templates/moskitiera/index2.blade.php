<x-layout>

    @php
    $attribute = $attributes->firstWhere('id', 1);
    @endphp

    <div class="container mx-auto px-4 " x-data="productPage()">
        {{-- <div class="flex items-center justify-between mb-4">
            <h1 class="text-3xl font-bold">Nazwa Produktu</h1>
            <div class="w-1/4 w-full-mobile">
                <img src="https://a.allegroimg.com/original/11820b/6579b4734fdc88fb0e6a68adee24/MOSKITIERA-ALUMINIOWA-RAMKA-Z-SIATKA-przeciw-owadom-na-komary-muchy-Kod-producenta-MOS-1mb-obw">
            </div>
            
        </div> --}}

        @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
            role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        <div class="flex flex-wrap -mx-4">

            <!-- Formularz produktu -->
            <div class="w-full md:w-1/2 px-4 mb-8">
                <h2 class="text-2xl font-bold mb-4">{{$product->name}}</h2>
                <form @submit.prevent="addToCart">
                    <div class="mb-4">
                        <label for="width" class="block mb-2">Szerokość:</label>
                        <input type="number" id="width" x-model="product.width" required
                            class="w-full p-2 border rounded" step="0.1">
                    </div>
                    <div class="mb-4">
                        <label for="height" class="block mb-2">Wysokość:</label>
                        <input type="number" id="height" x-model="product.height" required
                            class="w-full p-2 border rounded" step="0.1">
                    </div>
                    <div class="mb-4">
                        <label for="thickness" class="block mb-2">Grubość:</label>
                        <input type="number" id="thickness" x-model="product.thickness" required
                            class="w-full p-2 border rounded">
                    </div>
                    @if($attribute && $attribute->items->isNotEmpty())
                    <div class="mb-4">
                        <label for="color" class="block mb-2">Kolor:</label>
                        <select id="color" x-model="product.color" required class="w-full p-2 border rounded">
                            <option value="">Wybierz kolor</option>
                            @foreach($attribute->items as $item)
                            <option value="{{$item->value}}">{{$item->value}}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                    <div class="mb-4">
                        <label for="thickness" class="block mb-2">Ilość:</label>
                        <input type="number" id="quantity" x-model="product.quantity" required
                            class="w-full p-2 border rounded">
                    </div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Dodaj do
                        koszyka</button>
                </form>
            </div>

            <!-- Koszyk -->
            <div class="w-full md:w-1/2 px-4">
                <h2 class="text-2xl font-bold mb-4">Koszyk</h2>
                <div class="bg-white p-4 rounded shadow">
                    <template x-if="cart.length === 0">
                        <p>Koszyk jest pusty</p>
                    </template>
                    <template x-if="cart.length > 0">
                        <div>
                            <template x-for="(item, index) in cart" :key="index">
                                <div class="mb-2 p-2 border rounded">
                                    <p
                                        x-text="`Szerokość: ${item.width}cm, Wysokość: ${item.height}cm, Grubość: ${item.thickness}cm, Kolor: ${item.color}, Ilość: ${item.quantity}`">
                                    </p>
                                    <p x-text="`Metry bieżące: ${calculateRunningMeters(item)}m`"></p>
                                    <button @click="removeFromCart(index)" class="text-red-500 mt-1">Usuń</button>
                                </div>
                            </template>
                            <div class="mt-4">
                                <p class="font-bold"
                                    x-text="`Suma metrów bieżących: ${totalRunningMeters.toFixed(0)}m`"></p>
                            </div>
                            <form action="{{ route('order.store') }}" method="POST" @submit="onSubmitOrder"
                                class="mt-4">
                                @csrf
                                <input type="hidden" name="cart" :value="JSON.stringify(cart)">
                                <input type="hidden" name="totalRunningMeters" :value="totalRunningMeters.toFixed(2)">
                                <div class="mb-4">
                                    <label for="email" class="block mb-2">Email:</label>
                                    <input type="email" id="email" name="email"
                                        class="w-full p-2 border rounded">
                                </div>
                                <div class="mb-4">
                                    <label for="phone" class="block mb-2">Telefon:</label>
                                    <input type="tel" id="phone" name="phone"
                                        class="w-full p-2 border rounded">
                                </div>
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit"
                                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Zamów</button>
                            </form>
                        </div>
                    </template>
                </div>
            </div>
        </div>

    </div>

    <script>
        function productPage() {
            return {
                product: {
                    width: '',
                    height: '',
                    thickness: '',
                    color: '',
                    quantity: ''
                },
                cart: JSON.parse(localStorage.getItem('cart') || '[]'),
                get totalRunningMeters() {
                    const total = this.cart.reduce((total, item) => {
                        return total + this.calculateRunningMeters(item, false);
                    }, 0);
                    return Math.ceil(total);
                },
                calculateRunningMeters(item, round = true) {
                    const meters = ((parseFloat(item.width) + parseFloat(item.height)) * 2 / 100) * item.quantity;
                    return round ? meters.toFixed(2) : meters;
                },
                addToCart() {
                    this.cart.push({
                        ...this.product
                    });
                    this.saveCart();
                    this.resetForm();
                },
                removeFromCart(index) {
                    this.cart.splice(index, 1);
                    this.saveCart();
                },
                saveCart() {
                    localStorage.setItem('cart', JSON.stringify(this.cart));
                },
                resetForm() {
                    this.product = {
                        width: '',
                        height: '',
                        thickness: '',
                        color: '',
                        quantity: ''
                    };
                },
                clearCart() {
                    this.cart = [];
                    this.saveCart();
                },
                onSubmitOrder() {
                    // Czyścimy koszyk po wysłaniu formularza
                    this.$nextTick(() => {
                        this.clearCart();
                    });
                }
            }
        }
    </script>
</x-layout>