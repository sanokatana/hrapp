@extends('layouts.presensi')

@section('header')
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Your Files</div>
    <div class="right"></div>
</div>
<!-- * App Header -->
<style>

    :root {
        --viewport-height: 100vh; /* Default value */
    }
    /* Modal background */
    .custom-modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Stay in place */
        z-index: 1;
        /* Sit on top */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgba(0, 0, 0, 0.4);
        /* Black w/ opacity */
        animation: fadeIn 0.3s;
    }

    /* Modal content */
    .custom-modal-content {
        background-color: #fefefe;
        margin: auto;
        /* Centered horizontally and vertically */
        padding: 10px;
        border: 1px solid #888;
        width: 100%;
        /* Could be more or less, depending on screen size */
        max-width: 600px;
        /* Optional: max-width for large screens */
        border-radius: 8px;
        /* Rounded corners */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        position: relative;
        top: 30%;
        transform: translateY(-50%);
        opacity: 0;
        animation: modalIn 0.3s forwards;
    }

    .custom-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #ddd;
        padding-bottom: 10px;
        margin-bottom: 10px;
    }

    .custom-modal-header h5 {
        margin: 0;
    }

    .custom-modal .close {
        color: #aaa;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .custom-modal .close:hover,
    .custom-modal .close:focus {
        color: black;
        text-decoration: none;
    }

    /* Animation for modal appearance */
    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes modalIn {
        from {
            transform: translateY(-50px);
            opacity: 0;
        }

        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Animation for modal exit */
    .custom-modal .hide {
        animation: fadeOut 0.3s;
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
        }

        to {
            opacity: 0;
        }
    }

    .rounded-custom {
        border-radius: 10px;
        border: 1px solid #092c9f;
        margin-bottom: 4px;
        /* Customize the radius as needed */
    }

    #filesList {
        max-height: calc(var(--viewport-height) - 130px); /* Adjust 70px according to the size of your bottom nav bar */
        overflow-y: auto; /* Enable vertical scrolling */
        padding: 10px; /* Optional: Add padding if needed */
        box-sizing: border-box; /* Ensure padding doesn't affect height calculation */
    }
</style>

@endsection

@section('content')
<div class="container" style="margin-top:70px" id="filesList">
    @if(count($files) > 0)
    <div class="list-group">
        @foreach ($files as $fileUrl)
        <?php $fileName = basename($fileUrl); ?>
        <ul class="listview image-listview rounded-custom btnDocument" data-file-url="{{ $fileUrl }}">
            <li>
                <div class="item">
                    <div class="icon-box bg-success">
                        <ion-icon name="document-outline"></ion-icon>
                    </div>

                    <div class="in">
                        <div><b>{{ $fileName }}</b></div>
                    </div>

                    <div class="icon-box bg-secondary" style="margin-right: 0px; margin-left: 16px;">
                        <ion-icon name="search-outline"></ion-icon>
                    </div>
                </div>
            </li>
        </ul>
        @endforeach
    </div>
    @else
    <p>No files found.</p>
    @endif
</div>

<!-- Custom Modal -->
<div id="fileModal" class="custom-modal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5>File Preview</h5>
            <span class="close">&times;</span>
        </div>
        <div class="custom-modal-body">
            <img id="filePreview" src="" alt="File Preview" class="img-fluid" />
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    function updateViewportHeight() {
        document.documentElement.style.setProperty('--viewport-height', `${window.innerHeight}px`);
    }

    // Update viewport height on initial load
    updateViewportHeight();

    // Update viewport height on resize
    window.addEventListener('resize', updateViewportHeight);


    document.addEventListener('DOMContentLoaded', function() {
        var modal = document.getElementById('fileModal');
        var closeBtn = document.querySelector('.custom-modal .close');
        var filePreview = document.getElementById('filePreview');

        document.querySelectorAll('.btnDocument').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var fileUrl = this.getAttribute('data-file-url');
                var fileExtension = fileUrl.split('.').pop().toLowerCase(); // Get file extension

                // If the file is a PDF, show SweetAlert and download it after 2 seconds
                if (fileExtension === 'pdf') {
                    Swal.fire({
                        title: 'Downloading PDF',
                        text: 'Please wait...',
                        icon: 'info',
                        showConfirmButton: false,
                        timer: 2000 // Set timer for 2 seconds
                    }).then(() => {
                        // Trigger the download after SweetAlert closes
                        window.location.href = fileUrl;
                    });
                } else {
                    // Otherwise, show the image preview (for non-PDF files)
                    filePreview.style.display = 'block'; // Show image preview
                    filePreview.src = fileUrl;
                    document.querySelector('.custom-modal-body').innerHTML = ''; // Clear any existing iframe (for PDFs)
                    document.querySelector('.custom-modal-body').appendChild(filePreview);

                    modal.style.display = 'block';
                    setTimeout(() => modal.classList.add('show'), 10); // Add animation class with slight delay
                }
            });
        });

        closeBtn.addEventListener('click', function() {
            modal.classList.remove('show');
            setTimeout(() => modal.style.display = 'none', 300); // Match duration with animation time
        });

        // Close the modal if the user clicks outside of the modal content
        window.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.classList.remove('show');
                setTimeout(() => modal.style.display = 'none', 300); // Match duration with animation time
            }
        });
    });

</script>
@endpush
