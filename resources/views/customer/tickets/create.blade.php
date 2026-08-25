<x-app-layout>

    <x-slot name="header">

        <div class="flex justify-between items-center">

            <h2 class="font-semibold text-xl text-gray-800">
                Create Support Ticket
            </h2>

            <a
                href="{{ route('customer.dashboard') }}"
                class="text-blue-600 hover:underline"
            >
                ← Back
            </a>

        </div>

    </x-slot>

    <div class="py-6">

        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm rounded-lg p-6">

                @if($errors->any())

                    <div class="mb-5 bg-red-100 text-red-800 p-4 rounded-lg">

                        <ul class="list-disc ml-5">

                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach

                        </ul>

                    </div>

                @endif

                <form
                    method="POST"
                    action="{{ route('customer.tickets.store') }}"
                >

                    @csrf

                    <div class="mb-4">

                        <label class="block mb-2 font-medium">
                            Customer Name
                        </label>

                        <input
                            type="text"
                            value="{{ auth()->user()->name }}"
                            class="w-full border rounded-lg px-3 py-2 bg-gray-100"
                            readonly
                        >

                    </div>

                    <div class="mb-4">

                        <label class="block mb-2 font-medium">
                            Email
                        </label>

                        <input
                            type="email"
                            value="{{ auth()->user()->email }}"
                            class="w-full border rounded-lg px-3 py-2 bg-gray-100"
                            readonly
                        >

                    </div>

                    <div class="mb-4">

                        <label class="block mb-2 font-medium">
                            Subject
                        </label>

                        <input
                            type="text"
                            name="subject"
                            value="{{ old('subject') }}"
                            class="w-full border rounded-lg px-3 py-2"
                            placeholder="Enter your issue"
                            required
                        >

                    </div>

                    <div class="mb-6">

                        <label class="block mb-2 font-medium">
                            Message
                        </label>

                        <textarea
                            name="message"
                            rows="6"
                            class="w-full border rounded-lg px-3 py-2"
                            placeholder="Describe your problem..."
                            required
                        >{{ old('message') }}</textarea>

                    </div>

                    <div class="flex gap-3">

                        <button
                            type="submit"
                            class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700"
                        >
                            Create Ticket
                        </button>

                        <a
                            href="{{ route('customer.dashboard') }}"
                            class="bg-gray-200 px-5 py-2 rounded-lg"
                        >
                            Cancel
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>
