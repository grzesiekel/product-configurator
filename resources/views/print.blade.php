<x-layout>

<div class="container mx-auto py-6 print:py-0">
        <h1 class="text-2xl font-bold mb-4 print:text-xl print:mb-2">Order Details</h1>
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden print:shadow-none print:rounded-none">
            <thead class="bg-gray-100 print:bg-gray-200">
                <tr>
                @php
                        $firstItem = reset($cartData);
                        $headers = array_keys($firstItem);
                        $quantityIndex = array_search('quantity', $headers);
                        if ($quantityIndex !== false) {
                            unset($headers[$quantityIndex]);
                            $headers[] = 'quantity';
                        }

                        $translations = [
                            'width' => 'Szerokość',
                            'height' => 'Wysokość',
                            'thickness' => 'Grubość',
                            'color' => 'Kolor',
                            'quantity' => 'Ilość',
                            // Dodaj tutaj więcej tłumaczeń, jeśli są potrzebne
                        ];
                    @endphp
                    @foreach($headers as $header)
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider print:text-xxs print:py-2">
                            {{ $translations[$header] ?? ucfirst($header) }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($cartData as $item)
                    <tr class="hover:bg-gray-50 print:hover:bg-white">
                        @foreach($headers as $header)
                            <td class="px-4 py-4 whitespace-nowrap print:px-2 print:py-2 print:text-sm">
                                @if($header === 'color')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-{{ strtolower($item[$header]) }}-100 text-{{ strtolower($item[$header]) }}-800 print:bg-white print:text-black print:border print:border-gray-300">
                                        {{ $item[$header] }}
                                    </span>
                                @else
                                    {{ $item[$header] }}
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</x-layout>