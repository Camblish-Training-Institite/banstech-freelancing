@extends('dashboards.Freelancer.dashboard')
@section('body')
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Submit Entry to "{{ $contest->title }}"
    </h2>
</x-slot>

@push('styles')
<style>
    .file-upload-wrapper {
        border: 2px dashed #ddd;
        border-radius: 5px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: border-color 0.3s;
    }

    .file-upload-wrapper:hover {
        border-color: #0d6efd;
    }

    .file-upload-wrapper p {
        margin: 0;
        font-size: 1.1rem;
        color: #6c757d;
    }

    .file-upload-wrapper .btn-browse {
        background-color: #e9ecef;
        border-color: #dee2e6;
    }

    #file-upload-input {
        display: none;
    }

    .promo-card {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 1rem;
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
    }
</style>
@endpush

<form action="{{ route('freelancer.contests.submit.store', $contest) }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="card-body">
        <div class="max-w mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('freelancer.contests.submit.store', $contest) }}"
                enctype="multipart/form-data">
                @csrf
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="row g-5">
                            <div class="col-lg-8">
                                <div class="mb-4">
                                    <label for="title"
                                        class="form-label fs-5 fw-semibold text-gray-800">Entry
                                        Title</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="description"
                                        class="form-label fs-5 fw-semibold text-gray-800">Describe
                                        your entry</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                        id="description" name="description" rows="5" maxlength="1000"
                                        required>{{ old('description') }}</textarea>
                                    <small class="form-text text-muted" id="char-counter">0/1000 Characters</small>
                                    @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <h3 class="fs-5 fw-semibold text-gray-800 mb-3">Licensed Content
                                    </h3>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="licensed_content"
                                            id="own_content" value="own" checked>
                                        <label class="form-check-label" for="own_content">This entry is entirely my
                                            own.</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="licensed_content"
                                            id="not_own_content" value="not_own">
                                        <label class="form-check-label" for="not_own_content">This entry contains
                                            elements I did not create.</label>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="sell_price"
                                        class="form-label fs-5 fw-semibold text-gray-800">Entry Sell
                                        Price</label>
                                    <p class="text-muted small">Enter the price you want to sell this entry for. If you
                                        don't win, the contest holder may still buy your entry at this price.</p>
                                    <div class="input-group" style="max-width: 200px;">
                                        <span class="input-group-text">$</span>
                                        <input type="number"
                                            class="form-control @error('sell_price') is-invalid @enderror"
                                            id="sell_price" name="sell_price" value="{{ old('sell_price') }}"
                                            step="0.01" placeholder="USD">
                                    </div>
                                    @error('sell_price')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <h3 class="fs-5 fw-semibold text-gray-800 mb-3">Promote my entry
                                    </h3>
                                    <div class="promo-card">
                                        <input class="form-check-input me-3" type="checkbox" value="1" id="highlight"
                                            name="highlight" style="transform: scale(1.5);">
                                        <div>
                                            <label class="form-check-label fw-bold" for="highlight">Highlight <span
                                                    class="badge bg-secondary">FREE</span></label>
                                            <p class="small text-muted mb-0">Highlight your entry to make it visually
                                                stand out from the rest.</p>
                                        </div>
                                    </div>
                                    <div class="promo-card">
                                        <input class="form-check-input me-3" type="checkbox" value="1" id="sealed"
                                            name="sealed" style="transform: scale(1.5);">
                                        <div>
                                            <label class="form-check-label fw-bold" for="sealed">Sealed <span
                                                    class="badge bg-secondary">FREE</span></label>
                                            <p class="small text-muted mb-0">Seal your entry to ensure your idea is
                                                unique. Only you and the contest holder will be able to view your sealed
                                                entry.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <h3 class="fs-5 fw-semibold text-gray-800  mb-3">Add Files</h3>
                                <div class="file-upload-wrapper" id="file-upload-area">
                                    <input type="file" name="entry_files[]" id="file-upload-input" multiple>
                                    <p>Drag multiple files here</p>
                                    <p class="my-2">or</p>
                                    <button type="button" class="btn btn-browse" id="browse-btn">Browse Your
                                        Files</button>
                                </div>
                                <div id="file-list" class="mt-2 small text-muted"></div>
                                @error('entry_files.*')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror

                                <div
                                    class="mt-4 p-3 bg-light rounded small text-gray-600">
                                    <p>Please ensure you have read the contest brief and any feedback left by the
                                        contest holder on the public clarification board. Supported files types are:
                                        <strong>GIF, JPEG, JPG, PNG</strong>.</p>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="text-center mb-5">
                            <button type="submit" class="btn btn-success p-1 text-white  hover:bg-green-600 
                    transition duration-300">Submit My Entry</button>
                            <a href="{{ route('freelancer.contests.show', $contest) }}"
                                class="btn btn-secondary btn-lg">Cancel</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</form>

{{-- @push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
            // Character Counter for description
            const description = document.getElementById('description');
            const charCounter = document.getElementById('char-counter');
            description.addEventListener('input', function() {
                const count = this.value.length;
                charCounter.textContent = `${count}/1000 Characters`;
            });

            // File Upload UI
            const fileUploadArea = document.getElementById('file-upload-area');
            const fileInput = document.getElementById('file-upload-input');
            const browseBtn = document.getElementById('browse-btn');
            const fileList = document.getElementById('file-list');

            browseBtn.addEventListener('click', () => fileInput.click());
            fileUploadArea.addEventListener('click', () => fileInput.click());

            fileInput.addEventListener('change', handleFiles);
            
            // Drag and Drop functionality
            fileUploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                fileUploadArea.style.borderColor = '#0d6efd';
            });
            fileUploadArea.addEventListener('dragleave', (e) => {
                e.preventDefault();
                fileUploadArea.style.borderColor = '#ddd';
            });
            fileUploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                fileUploadArea.style.borderColor = '#ddd';
                fileInput.files = e.dataTransfer.files;
                handleFiles();
            });

            function handleFiles() {
                fileList.innerHTML = ''; // Clear previous list
                if (fileInput.files.length > 0) {
                    const files = Array.from(fileInput.files);
                    fileList.innerHTML = `<strong>Selected files:</strong><br>` + files.map(f => f.name).join('<br>');
                }
            }
        });
</script>
@endpush --}}

@endsection