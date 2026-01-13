@extends('layouts.admin')

@section('content')
    {{-- This script is preserved to maintain functionality --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const url = window.location.href;
            const requestidMatch = url.match(/admin\/partner-requests\/(\d+)/);
            if (requestidMatch && requestidMatch[1]) {
                let requestId = parseInt(requestidMatch[1], 10);
                const editButtonLink = document.querySelector('.btn-edit');
                if (editButtonLink) {
                    editButtonLink.href = "{{ route('admin.partner-requests.accept', '__request_ID__') }}".replace('__request_ID__', requestId);
                }
                const deleteForm = document.querySelector('.form-delete');
                if (deleteForm) {
                    deleteForm.action = "{{ route('admin.partner-requests.reject', '__request_ID__') }}".replace('__request_ID__', requestId);
                }
            }
        });
    </script>

    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        @if(session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
            </div>
        @endif

        <div class="flex w-full items-center mb-4">
            <div>
                <a href="{{url()->previous()}}" class="font-medium text-sm text-gray-500">{{'< back to reqeusts'}}</a>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <div class="flex flex-col flex-wrap items-start justify-between -mt-2 -ml-4">
                    <!-- main header -->
                    <div class="flex flex-col w-full border-b mt-2 ml-4 pb-2">
                        <h1 class="text-2xl font-bold text-gray-800">{{ $request->providerUser->name }} - {{ $request->providerUser->provider->provider_name }}</h1>
                        <div class="mt-1 flex items-center">
                            <svg class="h-5 w-5 text-gray-400 mr-1.5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                            </svg>
                            <p class="text-sm text-gray-600 truncate">{{ $request->providerUser->provider->booking_address }}</p>
                        </div>
                    </div>

                    <!-- user verification documents -->
                    <div class="mt-4 ml-4 flex items-center text-sm text-gray-500">
                        <div class="flex flex-col items-start justify-start">
                            <h2 class="text-lg font-bold text-black" style="padding-left: 2rem;">User Request docuemnts</h2>
                            

                            <div class="flex w-full start justify-start px-4 py-2 rounded-lg mt-2">
                                <!-- Proof of identity -->
                                <div class="flex w-full start justify-start px-4 py-2 rounded-lg mt-2">
                                    @if ($request->identityProofs)
                                        <div class="flex flex-col py-2 bg-white">
                                            <h3 class="mb-2 text-black font-semibold ">Proof of identity: {{ $request->identityProofs->document_type }}</h3>
                                            <a href="{{route('document.show', ['filename' => $request->identityProofs ? $request->identityProofs->id_proof_path : ''])}}" target="_blank" class="text-sm text-blue-600 hover:underline cursor-pointer">View Document: {{ $request->identityProofs->original_name }}</a>
                                        </div>
                                    @else
                                        <p class="text-gray-500 font-medium">no proof of identity uploaded</p>
                                    @endif

                                </div>
                            </div>

                            <div class="flex w-full start justify-start px-4 py-2 rounded-lg mt-2">
                                <!-- proof of address -->
                                <div class="flex w-full start justify-start px-4 py-2 rounded-lg mt-2">
                                    @if ($request->addressProof)
                                        <div class="flex flex-col py-2 bg-white">
                                            <h3 class="mb-2 text-black font-semibold ">Proof of address: {{ $request->addressProof->document_type }}</h3>
                                            <a href="{{route('document.show', ['filename' => $request->addressProof ? $request->addressProof->address_proof_path : ''])}}" target="_blank" class="text-sm text-blue-600 hover:underline cursor-pointer">View Document: {{ $request->addressProof->original_name }}</a>
                                        </div>
                                    @else
                                        <p class="text-gray-500 font-medium">no proof of address uploaded</p>
                                    @endif

                                </div>

                                 <!-- proof of ownership -->
                                <div class="flex w-full start justify-start px-4 py-2 rounded-lg mt-2">
                                    @if ($request->ownershipProofs)
                                        <div class="flex flex-col py-2 bg-white">
                                            <h3 class="mb-2 text-black font-semibold ">Proof of ownership: {{ $request->ownershipProofs->document_type }}</h3>
                                            <a href="{{route('document.show', ['filename' => $request->ownershipProofs ? $request->ownershipProofs->ownership_proof_path : ''])}}" target="_blank" class="text-sm text-blue-600 hover:underline cursor-pointer">View Document: {{ $request->ownershipProofs->original_name }}</a>
                                        </div>
                                    @else
                                        <p class="text-gray-500 font-medium">no proof of ownership uploaded</p>
                                    @endif

                                </div>
                                
                            </div>


                            <!-- business documents -->
                            <div class="flex w-full start justify-start px-4 py-2 rounded-lg mt-2">
                                @if ($request->businessDetail)
                                    <div class="flex w-full start justify-start px-4 py-2 rounded-lg mt-2">
                                        @if ($request->BusinessDetail->Business_license_path)
                                            <div class="flex flex-col py-2 bg-white">
                                                <h3 class="mb-2 text-black font-semibold ">Business license:</h3>
                                                <a href="{{route('document.show', ['filename' => $request->BusinessDetail ? $request->BusinessDetail->Business_license_path : ''])}}" target="_blank" class="text-sm text-blue-600 hover:underline cursor-pointer">View Document: {{ $request->BusinessDetail->business_license_name }}</a>
                                            </div>
                                        @else
                                            <p class="text-gray-500 font-medium">no Business license uploaded</p>
                                        @endif
                                    </div>

                                    <div class="flex w-full start justify-start px-4 py-2 rounded-lg mt-2">
                                        @if ($request->BusinessDetail->Tax_registration_number_path)
                                            <div class="flex flex-col py-2 bg-white">
                                                <h3 class="mb-2 text-black font-semibold ">Tax Registration Number: </h3>
                                                <a href="{{route('document.show', ['filename' => $request->BusinessDetail ? $request->BusinessDetail->Tax_registration_number_path : ''])}}" target="_blank" class="text-sm text-blue-600 hover:underline cursor-pointer">View Document: {{ $request->BusinessDetail->tax_number_name }}</a>
                                            </div>
                                        @else
                                            <p class="text-gray-500 font-medium">no Tax Registration Uploaded</p>
                                        @endif

                                    </div>
                                @else
                                    <div class="flex w-full start justify-start px-4 py-2 rounded-lg mt-2">
                                        <p class="text-gray-500 font-medium">no business details provided</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    

                    <!-- action buttons -->
                    <div class="mt-4 ml-4 flex-shrink-0 flex flex-col space-x-2 w-full justify-start" style="padding:0 4rem; padding-left:2rem; width: 100%;">
                        <div class="flex flex-col items-center w-full mb-6">
                            <a href="#" class="w-full justify-center btn-edit inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-gray-700 bg-[rgb(22 163 74)] hover:bg-[rgb(21 128 61)]">Accept Request</a>
                        </div>
                        <div class="flex flex-col w-full items-center justify-start">
                            <form action="#" method="POST" class="form-delete w-full">
                                @csrf
                                @method('POST')
                                
                                <label for="reject_reason" class="block text-gray-700 font-medium mb-2">Reason for Rejection</label>
                                <select name="reject_reason" id="reject_reason" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 mb-2" required>
                                    <option value="identity">Unclear Proof of Identity</option>
                                    <option value="ownership">Unclear Proof of Ownership</option>
                                    <option value="address">Unclear Proof of Address</option>
                                    <option value="license">Unclear business license</option>
                                    <option value="tax">Unclear Tax Regisitration number</option>
                                </select>
                                <div class="flex w-full justify-end">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700" onclick="return confirm('Are you sure you want to delete this request?');">Reject Request</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 p-6">
                <div class="lg:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-900">Email Address</h2>
                    <div class="mt-2 text-sm text-gray-600 space-y-4" style="color: #e4ad68;">
                        <a href="mailto:{{$request->provideruser->email}}">{{ $request->provideruser->email }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection