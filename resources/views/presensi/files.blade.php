
@extends('layouts.presensi')

@section('header')
<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Document Center</div>
    <div class="right">
        <a href="javascript:;" class="headerButton" id="headerUploadBtn">
            <ion-icon name="cloud-upload-outline"></ion-icon>
        </a>
    </div>
</div>
<!-- * App Header -->
<style>
    :root {
        --viewport-height: 100vh;
    }

    .custom-modal {
        display: none;
        position: fixed;
        z-index: 1050;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
        animation: fadeIn 0.3s;
    }

    .custom-modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 15px;
        border: 1px solid #e0e0e0;
        width: 100%;
        max-width: 600px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        position: relative;
        top: 50%;
        transform: translateY(-50%);
        opacity: 0;
        animation: modalIn 0.3s forwards;
        max-height: 85vh;
        overflow-y: auto;
    }

    .custom-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #eaeaea;
        padding-bottom: 12px;
        margin-bottom: 15px;
        position: sticky;
        top: 0;
        background-color: #fefefe;
        z-index: 5;
    }

    .custom-modal-header h5 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
    }

    .custom-modal .close {
        color: #888;
        font-size: 24px;
        font-weight: bold;
        cursor: pointer;
        padding: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
    }

    .custom-modal .close:hover {
        color: #333;
        background-color: rgba(0,0,0,0.05);
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes modalIn {
        from {
            transform: translateY(-60px);
            opacity: 0;
        }
        to {
            transform: translateY(-50%);
            opacity: 1;
        }
    }

    .custom-modal.hide {
        animation: fadeOut 0.3s;
    }

    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
    }

    #filesList {
        max-height: calc(var(--viewport-height) - 120px);
        overflow-y: auto;
        padding: 15px;
        box-sizing: border-box;
    }

    .file-status {
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
    }

    .file-status ion-icon {
        margin-right: 4px;
        font-size: 14px;
    }

    .file-status-1 {
        background-color: #d1fae5;
        color: #047857;
    }

    .file-status-0 {
        background-color: #fee2e2;
        color: #b91c1c;
    }

    .file-status-pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .doc-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    /* Simplified cards for cleaner look */
    .document-card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        margin-bottom: 10px;
        transition: all 0.2s ease;
        border: 1px solid #eaeaea;
    }

    .document-card:active {
        transform: scale(0.98);
    }

    .document-card-header {
        padding: 10px 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #f5f5f5;
    }

    .document-card-title {
        font-weight: 600;
        color: #333;
    }

    .document-card-body {
        padding: 8px 12px;
    }

    .document-card-footer {
        padding: 6px 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        color: #666;
    }

    .document-card-footer a {
        color: #007bff;
        text-decoration: none;
        display: flex;
        align-items: center;
    }

    .document-card-footer a ion-icon {
        margin-right: 4px;
    }

    .upload-btn {
        position: fixed;
        bottom: 80px;
        right: 20px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #4f46e5;
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        z-index: 999;
        transition: all 0.2s ease;
    }

    .upload-btn:active {
        transform: scale(0.95);
        box-shadow: 0 2px 8px rgba(79, 70, 229, 0.3);
    }

    .document-type-select {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-bottom: 20px;
    }

    .document-type-option {
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        padding: 12px;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        display: flex;
        align-items: center;
    }

    .document-type-option:hover {
        border-color: #b3b3b3;
        background-color: #f9fafb;
    }

    .document-type-option.selected {
        border-color: #4f46e5;
        background-color: #f5f3ff;
    }

    .document-type-option input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .document-type-option .document-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        margin-right: 10px;
        flex-shrink: 0;
    }

    .document-type-option .document-icon ion-icon {
        font-size: 20px;
        color: white;
    }

    .document-type-option .document-info {
        flex-grow: 1;
    }

    .document-type-option .document-name {
        font-weight: 500;
        color: #333;
        margin-bottom: 2px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .document-drop-zone {
        border: 2px dashed #ddd;
        border-radius: 12px;
        padding: 20px;
        text-align: center;
        transition: all 0.2s ease;
        margin-bottom: 20px;
        cursor: pointer;
    }

    .document-drop-zone:hover,
    .document-drop-zone.dragover {
        border-color: #4f46e5;
        background-color: #f5f3ff;
    }

    .document-drop-zone .upload-icon {
        font-size: 48px;
        color: #666;
        margin-bottom: 10px;
    }

    .document-drop-zone .upload-text {
        color: #666;
        margin-bottom: 5px;
    }

    .document-drop-zone .upload-hint {
        font-size: 12px;
        color: #888;
    }

    .file-preview {
        background-color: #f5f5f5;
        border-radius: 12px;
        padding: 12px;
        margin-top: 15px;
        display: flex;
        align-items: center;
    }

    .file-preview .file-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        background-color: #e0e0e0;
        flex-shrink: 0;
    }

    .file-preview .file-icon ion-icon {
        font-size: 20px;
        color: #555;
    }

    .file-preview .file-info {
        flex-grow: 1;
        overflow: hidden;
    }

    .file-preview .file-name {
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .file-preview .file-size {
        font-size: 12px;
        color: #666;
    }

    .file-preview .remove-file {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: #f0f0f0;
        color: #666;
        border: none;
        cursor: pointer;
    }

    .file-preview .remove-file:hover {
        background-color: #e0e0e0;
        color: #333;
    }

    .doc-action-btn {
        padding: 7px 12px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e0e0e0;
        background-color: white;
        color: #333;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .doc-action-btn ion-icon {
        margin-right: 4px;
    }

    .doc-action-btn:hover {
        background-color: #f5f5f5;
        border-color: #d0d0d0;
    }

    .doc-action-btn-primary {
        background-color: #4f46e5;
        color: white;
        border-color: #4f46e5;
    }

    .doc-action-btn-primary:hover {
        background-color: #4338ca;
        border-color: #4338ca;
        color: white;
    }

    /* Document icons colors */
    .icon-photo { background-color: #3b82f6; }
    .icon-ktp { background-color: #f59e0b; }
    .icon-kk { background-color: #10b981; }
    .icon-npwp { background-color: #6366f1; }
    .icon-ijazah { background-color: #ec4899; }
    .icon-sim { background-color: #8b5cf6; }
    .icon-skck { background-color: #ef4444; }
    .icon-cv { background-color: #0ea5e9; }

    .section-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #333;
        display: flex;
        align-items: center;
    }

    .section-title ion-icon {
        margin-right: 6px;
    }

    .section-divider {
        height: 1px;
        background-color: #eaeaea;
        margin: 20px 0;
    }

    @media (min-width: 400px) {
        .doc-grid {
            grid-template-columns: repeat(1, 1fr);
        }
    }
</style>
@endsection

@section('content')
<div id="filesList">

<!-- Other Files Section -->
    <div class="section mt-5">
        <div class="section-title">
            <ion-icon name="folder-outline"></ion-icon>
            Files & Folders
        </div>

        <!-- Files and Folders Navigation -->
        @if(isset($parentPath))
        <ul class="listview image-listview rounded-custom">
            <li>
                <a href="{{ route('presensi.checkFile', ['path' => $parentPath]) }}" class="item">
                    <div class="icon-box bg-primary">
                        <ion-icon name="arrow-back-outline"></ion-icon>
                    </div>
                    <div class="in">
                        <div><b>.. (Back)</b></div>
                    </div>
                </a>
            </li>
        </ul>
        @endif

        @if(count($items) > 0)
        <div class="list-group">
            @foreach ($items as $item)
                @if($item['type'] === 'folder')
                <ul class="listview image-listview rounded-custom" style="border-radius: 20px;">
                    <li>
                        <a href="{{ route('presensi.checkFile', ['path' => $item['path']]) }}" class="item">
                            <div class="icon-box bg-warning">
                                <ion-icon name="folder-outline"></ion-icon>
                            </div>
                            <div class="in">
                                <div><b>{{ $item['name'] }}</b></div>
                            </div>
                        </a>
                    </li>
                </ul>
                @else
                <ul class="listview image-listview rounded-custom btnDocument" data-file-url="{{ $item['url'] }}">
                    <li>
                        <div class="item">
                            <div class="icon-box bg-success">
                                <ion-icon name="{{ getFileIconName($item['name']) }}"></ion-icon>
                            </div>
                            <div class="in">
                                <div><b>{{ $item['name'] }}</b></div>
                            </div>
                            <div class="icon-box bg-primary" style="margin-right: 0px; margin-left: 16px;">
                                <ion-icon name="eye-outline"></ion-icon>
                            </div>
                        </div>
                    </li>
                </ul>
                @endif
            @endforeach
        </div>
        @else
        <div class="card">
            <div class="card-body text-center">
                <ion-icon name="folder-open-outline" style="font-size: 48px; color: #ccc;"></ion-icon>
                <p class="mt-2">No files or folders found.</p>
            </div>
        </div>
        @endif
    </div>

    <div class="section-divider"></div>

    <!-- Required Documents Section -->
<div class="section mb-3">
    <div class="section-title">
        <ion-icon name="document-text-outline"></ion-icon>
        Required Documents
    </div>

    <div class="doc-grid">
        <!-- Photo Document Card -->
        <div class="document-card" onclick="openDocumentModal('photo')">
            <div class="document-card-header">
                <div class="document-card-title">Photo</div>
                @if($documentStatus->file_photo)
                    <span class="file-status file-status-1">
                        <ion-icon name="checkmark-circle"></ion-icon> Uploaded
                    </span>
                @else
                    <span class="file-status file-status-0">
                        <ion-icon name="close-circle"></ion-icon> Missing
                    </span>
                @endif
            </div>
            @if($documentStatus->file_photo && $documentStatus->file_photo != 'No_Document')
            <div class="document-card-footer">
                <a href="{{ asset('storage/uploads/karyawan/'.$nik.'.'.$nama_lengkap.'/files/'.$documentStatus->file_photo) }}" target="_blank" class="text-primary">
                    <ion-icon name="eye-outline"></ion-icon> View
                </a>
            </div>
            @else
            <div class="document-card-body">
                <p class="mb-0 text-muted text-center">Upload Document</p>
            </div>
            @endif
        </div>

        <!-- KTP Document Card -->
        <div class="document-card" onclick="openDocumentModal('ktp')">
            <div class="document-card-header">
                <div class="document-card-title">KTP</div>
                @if($documentStatus->file_ktp)
                    <span class="file-status file-status-1">
                        <ion-icon name="checkmark-circle"></ion-icon> Uploaded
                    </span>
                @else
                    <span class="file-status file-status-0">
                        <ion-icon name="close-circle"></ion-icon> Missing
                    </span>
                @endif
            </div>
            @if($documentStatus->file_ktp && $documentStatus->file_ktp != 'No_Document')
            <div class="document-card-footer">
                <a href="{{ asset('storage/uploads/karyawan/'.$nik.'.'.$nama_lengkap.'/files/'.$documentStatus->file_ktp) }}" target="_blank" class="text-primary">
                    <ion-icon name="eye-outline"></ion-icon> View
                </a>
            </div>
            @else
            <div class="document-card-body">
                <p class="mb-0 text-muted text-center">Upload Document</p>
            </div>
            @endif
        </div>

        <!-- KK Document Card -->
        <div class="document-card" onclick="openDocumentModal('kk')">
            <div class="document-card-header">
                <div class="document-card-title">KK</div>
                @if($documentStatus->file_kk)
                    <span class="file-status file-status-1">
                        <ion-icon name="checkmark-circle"></ion-icon> Uploaded
                    </span>
                @else
                    <span class="file-status file-status-0">
                        <ion-icon name="close-circle"></ion-icon> Missing
                    </span>
                @endif
            </div>
            @if($documentStatus->file_kk && $documentStatus->file_kk != 'No_Document')
            <div class="document-card-footer">
                <a href="{{ asset('storage/uploads/karyawan/'.$nik.'.'.$nama_lengkap.'/files/'.$documentStatus->file_kk) }}" target="_blank" class="text-primary">
                    <ion-icon name="eye-outline"></ion-icon> View
                </a>
            </div>
            @else
            <div class="document-card-body">
                <p class="mb-0 text-muted text-center">Upload Document</p>
            </div>
            @endif
        </div>

        <!-- NPWP Document Card -->
        <div class="document-card" onclick="openDocumentModal('npwp')">
            <div class="document-card-header">
                <div class="document-card-title">NPWP</div>
                @if($documentStatus->file_npwp)
                    <span class="file-status file-status-1">
                        <ion-icon name="checkmark-circle"></ion-icon> Uploaded
                    </span>
                @else
                    <span class="file-status file-status-0">
                        <ion-icon name="close-circle"></ion-icon> Missing
                    </span>
                @endif
            </div>
            @if($documentStatus->file_npwp && $documentStatus->file_npwp != 'No_Document')
            <div class="document-card-footer">
                <a href="{{ asset('storage/uploads/karyawan/'.$nik.'.'.$nama_lengkap.'/files/'.$documentStatus->file_npwp) }}" target="_blank" class="text-primary">
                    <ion-icon name="eye-outline"></ion-icon> View
                </a>
            </div>
            @else
            <div class="document-card-body">
                <p class="mb-0 text-muted text-center">Upload Document</p>
            </div>
            @endif
        </div>

        <!-- Ijazah Document Card -->
        <div class="document-card" onclick="openDocumentModal('ijazah')">
            <div class="document-card-header">
                <div class="document-card-title">Ijazah</div>
                @if($documentStatus->file_ijazah)
                    <span class="file-status file-status-1">
                        <ion-icon name="checkmark-circle"></ion-icon> Uploaded
                    </span>
                @else
                    <span class="file-status file-status-0">
                        <ion-icon name="close-circle"></ion-icon> Missing
                    </span>
                @endif
            </div>
            @if($documentStatus->file_ijazah && $documentStatus->file_ijazah != 'No_Document')
            <div class="document-card-footer">
                <a href="{{ asset('storage/uploads/karyawan/'.$nik.'.'.$nama_lengkap.'/files/'.$documentStatus->file_ijazah) }}" target="_blank" class="text-primary">
                    <ion-icon name="eye-outline"></ion-icon> View
                </a>
            </div>
            @else
            <div class="document-card-body">
                <p class="mb-0 text-muted text-center">Upload Document</p>
            </div>
            @endif
        </div>

        <!-- SIM Document Card -->
        <div class="document-card" onclick="openDocumentModal('sim')">
            <div class="document-card-header">
                <div class="document-card-title">SIM</div>
                @if($documentStatus->file_sim)
                    <span class="file-status file-status-1">
                        <ion-icon name="checkmark-circle"></ion-icon> Uploaded
                    </span>
                @else
                    <span class="file-status file-status-0">
                        <ion-icon name="close-circle"></ion-icon> Missing
                    </span>
                @endif
            </div>
            @if($documentStatus->file_sim && $documentStatus->file_sim != 'No_Document')
            <div class="document-card-footer">
                <a href="{{ asset('storage/uploads/karyawan/'.$nik.'.'.$nama_lengkap.'/files/'.$documentStatus->file_sim) }}" target="_blank" class="text-primary">
                    <ion-icon name="eye-outline"></ion-icon> View
                </a>
            </div>
            @else
            <div class="document-card-body">
                <p class="mb-0 text-muted text-center">Upload Document</p>
            </div>
            @endif
        </div>

        <!-- SKCK Document Card -->
        <div class="document-card" onclick="openDocumentModal('skck')">
            <div class="document-card-header">
                <div class="document-card-title">SKCK</div>
                @if($documentStatus->file_skck)
                    <span class="file-status file-status-1">
                        <ion-icon name="checkmark-circle"></ion-icon> Uploaded
                    </span>
                @else
                    <span class="file-status file-status-0">
                        <ion-icon name="close-circle"></ion-icon> Missing
                    </span>
                @endif
            </div>
            @if($documentStatus->file_skck && $documentStatus->file_skck != 'No_Document')
            <div class="document-card-footer">
                <a href="{{ asset('storage/uploads/karyawan/'.$nik.'.'.$nama_lengkap.'/files/'.$documentStatus->file_skck) }}" target="_blank" class="text-primary">
                    <ion-icon name="eye-outline"></ion-icon> View
                </a>
            </div>
            @else
            <div class="document-card-body">
                <p class="mb-0 text-muted text-center">Upload Document</p>
            </div>
            @endif
        </div>

        <!-- CV Document Card -->
        <div class="document-card" onclick="openDocumentModal('cv')">
            <div class="document-card-header">
                <div class="document-card-title">CV</div>
                @if($documentStatus->file_cv)
                    <span class="file-status file-status-1">
                        <ion-icon name="checkmark-circle"></ion-icon> Uploaded
                    </span>
                @else
                    <span class="file-status file-status-0">
                        <ion-icon name="close-circle"></ion-icon> Missing
                    </span>
                @endif
            </div>
            @if($documentStatus->file_cv && $documentStatus->file_cv != 'No_Document')
            <div class="document-card-footer">
                <a href="{{ asset('storage/uploads/karyawan/'.$nik.'.'.$nama_lengkap.'/files/'.$documentStatus->file_cv) }}" target="_blank" class="text-primary">
                    <ion-icon name="eye-outline"></ion-icon> View
                </a>
            </div>
            @else
            <div class="document-card-body">
                <p class="mb-0 text-muted text-center">Upload Document</p>
            </div>
            @endif
        </div>
    </div>
</div>

</div>


<!-- Custom Modal for File Preview -->
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

<!-- Upload Modal -->
<div id="uploadModal" class="custom-modal">
    <div class="custom-modal-content">
        <div class="custom-modal-header">
            <h5>Upload Document</h5>
            <span class="close">&times;</span>
        </div>
        <div class="custom-modal-body">
            <form id="uploadDocumentForm" action="{{ route('presensi.uploadDocument') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="document_type" id="selectedDocType" value="">

                <div id="documentTypeSummary" class="mb-3">
                    <div class="d-flex align-items-center">
                        <div id="docTypeIcon" class="document-icon icon-photo" style="width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                            <ion-icon name="image-outline" style="font-size: 24px; color: white;"></ion-icon>
                        </div>
                        <div>
                            <h6 id="docTypeName" class="mb-1">Photo</h6>
                            <div id="docTypeStatus" class="file-status file-status-0 mb-1">
                                <ion-icon name="close-circle"></ion-icon> Missing
                            </div>
                            <div id="docTypeCurrentFile" class="small text-muted d-none">
                                Current file: <a href="#" target="_blank" id="docTypeCurrentFileLink">filename.pdf</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="document-drop-zone" id="dropZone">
                    <input type="file" class="form-control d-none" id="documentFile" name="document_file" accept=".jpg,.jpeg,.png,.pdf">
                    <div class="upload-icon">
                        <ion-icon name="cloud-upload-outline"></ion-icon>
                    </div>
                    <div class="upload-text">Drag & drop file here or click to browse</div>
                    <div class="upload-hint">Accepted file types: JPG, PNG, PDF (Max 5MB)</div>
                </div>

                <div id="filePreviewContainer" class="d-none">
                    <div class="file-preview">
                        <div id="fileTypeIcon" class="file-icon">
                            <ion-icon name="document-outline"></ion-icon>
                        </div>
                        <div class="file-info">
                            <div id="fileName" class="file-name">document.pdf</div>
                            <div id="fileSize" class="file-size">235 KB</div>
                        </div>
                        <button type="button" id="removeFile" class="remove-file">
                            <ion-icon name="close-outline"></ion-icon>
                        </button>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label for="documentNote" class="form-label">Note (Optional)</label>
                    <textarea class="form-control" id="documentNote" name="document_note" rows="2" placeholder="Add optional note about this document"></textarea>
                </div>

                <div class="alert alert-info mt-3">
                    <div class="d-flex">
                        <div class="me-2">
                            <ion-icon name="information-circle-outline" style="font-size: 24px;"></ion-icon>
                        </div>
                        <div>
                            <strong>Note:</strong> Uploading a new document will replace any previously uploaded document of the same type.
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <button type="button" class="doc-action-btn" id="cancelUpload">
                        <ion-icon name="close-outline"></ion-icon> Cancel
                    </button>
                    <button type="submit" class="doc-action-btn doc-action-btn-primary" id="uploadBtn">
                        <ion-icon name="cloud-upload-outline"></ion-icon> Upload Document
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    function updateViewportHeight() {
        document.documentElement.style.setProperty('--viewport-height', `${window.innerHeight}px`);
    }

    updateViewportHeight();
    window.addEventListener('resize', updateViewportHeight);

    // Document type info
    const documentTypes = {
        photo: {
            name: 'Photo',
            icon: 'image-outline',
            iconClass: 'icon-photo',
            status: {{ $documentStatus->file_photo ? '1' : '0' }},
            currentFile: '{{ $documentStatus->file_photo ? Storage::url($documentStatus->file_photo) : "" }}',
            fileName: '{{ $documentStatus->file_photo ? basename($documentStatus->file_photo) : "" }}'
        },
        ktp: {
            name: 'KTP',
            icon: 'card-outline',
            iconClass: 'icon-ktp',
            status: {{ $documentStatus->file_ktp ? '1' : '0' }},
            currentFile: '{{ $documentStatus->file_ktp ? Storage::url($documentStatus->file_ktp) : "" }}',
            fileName: '{{ $documentStatus->file_ktp ? basename($documentStatus->file_ktp) : "" }}'
        },
        kk: {
            name: 'Kartu Keluarga',
            icon: 'people-outline',
            iconClass: 'icon-kk',
            status: {{ $documentStatus->file_kk ? '1' : '0' }},
            currentFile: '{{ $documentStatus->file_kk ? Storage::url($documentStatus->file_kk) : "" }}',
            fileName: '{{ $documentStatus->file_kk ? basename($documentStatus->file_kk) : "" }}'
        },
        npwp: {
            name: 'NPWP',
            icon: 'card-outline',
            iconClass: 'icon-npwp',
            status: {{ $documentStatus->file_npwp ? '1' : '0' }},
            currentFile: '{{ $documentStatus->file_npwp ? Storage::url($documentStatus->file_npwp) : "" }}',
            fileName: '{{ $documentStatus->file_npwp ? basename($documentStatus->file_npwp) : "" }}'
        },
        ijazah: {
            name: 'Ijazah',
            icon: 'school-outline',
            iconClass: 'icon-ijazah',
            status: {{ $documentStatus->file_ijazah ? '1' : '0' }},
            currentFile: '{{ $documentStatus->file_ijazah ? Storage::url($documentStatus->file_ijazah) : "" }}',
            fileName: '{{ $documentStatus->file_ijazah ? basename($documentStatus->file_ijazah) : "" }}'
        },
        sim: {
            name: 'SIM',
            icon: 'car-outline',
            iconClass: 'icon-sim',
            status: {{ $documentStatus->file_sim ? '1' : '0' }},
            currentFile: '{{ $documentStatus->file_sim ? Storage::url($documentStatus->file_sim) : "" }}',
            fileName: '{{ $documentStatus->file_sim ? basename($documentStatus->file_sim) : "" }}'
        },
        skck: {
            name: 'SKCK',
            icon: 'shield-outline',
            iconClass: 'icon-skck',
            status: {{ $documentStatus->file_skck ? '1' : '0' }},
            currentFile: '{{ $documentStatus->file_skck ? Storage::url($documentStatus->file_skck) : "" }}',
            fileName: '{{ $documentStatus->file_skck ? basename($documentStatus->file_skck) : "" }}'
        },
        cv: {
            name: 'CV',
            icon: 'document-text-outline',
            iconClass: 'icon-cv',
            status: {{ $documentStatus->file_cv ? '1' : '0' }},
            currentFile: '{{ $documentStatus->file_cv ? Storage::url($documentStatus->file_cv) : "" }}',
            fileName: '{{ $documentStatus->file_cv ? basename($documentStatus->file_cv) : "" }}'
        }
    };

    function openDocumentModal(docType) {
        const docInfo = documentTypes[docType];
        if (!docInfo) return;

        // Set hidden input value
        document.getElementById('selectedDocType').value = docType;

        // Set document summary
        document.getElementById('docTypeName').textContent = docInfo.name;
        document.getElementById('docTypeIcon').className = `document-icon ${docInfo.iconClass}`;
        document.getElementById('docTypeIcon').innerHTML = `<ion-icon name="${docInfo.icon}" style="font-size: 24px; color: white;"></ion-icon>`;

        // Set status
        const statusElement = document.getElementById('docTypeStatus');
        if (docInfo.status) {
            statusElement.className = 'file-status file-status-1 mb-1';
            statusElement.innerHTML = '<ion-icon name="checkmark-circle"></ion-icon> Uploaded';
        } else {
            statusElement.className = 'file-status file-status-0 mb-1';
            statusElement.innerHTML = '<ion-icon name="close-circle"></ion-icon> Missing';
        }

        // Current file
        const currentFileElement = document.getElementById('docTypeCurrentFile');
        const currentFileLinkElement = document.getElementById('docTypeCurrentFileLink');

        if (docInfo.currentFile) {
            currentFileElement.classList.remove('d-none');
            currentFileLinkElement.textContent = docInfo.fileName;
            currentFileLinkElement.href = docInfo.currentFile;
        } else {
            currentFileElement.classList.add('d-none');
        }

        // Reset file selection
        document.getElementById('documentFile').value = '';
        document.getElementById('filePreviewContainer').classList.add('d-none');

        // Show modal
        const uploadModal = document.getElementById('uploadModal');
        uploadModal.style.display = 'block';
        setTimeout(() => uploadModal.classList.add('show'), 10);
    }

    document.addEventListener('DOMContentLoaded', function() {
        // File preview functionality
        var fileModal = document.getElementById('fileModal');
        var uploadModal = document.getElementById('uploadModal');
        var closeButtons = document.querySelectorAll('.custom-modal .close');
        var filePreview = document.getElementById('filePreview');
        var openUploadModalBtn = document.getElementById('openUploadModal');
        var headerUploadBtn = document.getElementById('headerUploadBtn');
        var cancelUploadBtn = document.getElementById('cancelUpload');
        var dropZone = document.getElementById('dropZone');
        var fileInput = document.getElementById('documentFile');
        var filePreviewContainer = document.getElementById('filePreviewContainer');
        var fileName = document.getElementById('fileName');
        var fileSize = document.getElementById('fileSize');
        var fileTypeIcon = document.getElementById('fileTypeIcon');
        var removeFileBtn = document.getElementById('removeFile');

        // Open file preview modal
        document.querySelectorAll('.btnDocument').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                var fileUrl = this.getAttribute('data-file-url');
                var fileExtension = fileUrl.split('.').pop().toLowerCase();

                if (fileExtension === 'pdf') {
                    Swal.fire({
                        title: 'Downloading PDF',
                        text: 'Please wait...',
                        icon: 'info',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.href = fileUrl;
                    });
                } else {
                    filePreview.style.display = 'block';
                    filePreview.src = fileUrl;
                    document.querySelector('#fileModal .custom-modal-body').innerHTML = '';
                    document.querySelector('#fileModal .custom-modal-body').appendChild(filePreview);

                    fileModal.style.display = 'block';
                    setTimeout(() => fileModal.classList.add('show'), 10);
                }
            });
        });

        // Open upload modal from header button or FAB
        [openUploadModalBtn, headerUploadBtn].forEach(function(btn) {
            if (btn) {
                btn.addEventListener('click', function() {
                    // Open for a random document type that isn't uploaded yet
                    const missingDocs = Object.keys(documentTypes).filter(key => documentTypes[key].status === 0);
                    if (missingDocs.length > 0) {
                        openDocumentModal(missingDocs[0]);
                    } else {
                        openDocumentModal('photo'); // Default to photo if all are uploaded
                    }
                });
            }
        });

        // Close modals
        closeButtons.forEach(function(closeBtn) {
            closeBtn.addEventListener('click', function() {
                var modal = this.closest('.custom-modal');
                modal.classList.remove('show');
                setTimeout(() => modal.style.display = 'none', 300);
            });
        });

        // Cancel upload
        cancelUploadBtn.addEventListener('click', function() {
            uploadModal.classList.remove('show');
            setTimeout(() => uploadModal.style.display = 'none', 300);
        });

        // Close modals when clicking outside
        window.addEventListener('click', function(e) {
            if (e.target === fileModal || e.target === uploadModal) {
                e.target.classList.remove('show');
                setTimeout(() => e.target.style.display = 'none', 300);
            }
        });

        // File drop zone
        dropZone.addEventListener('click', function() {
            fileInput.click();
        });

        // Drag and drop handlers
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            dropZone.classList.add('dragover');
        }

        function unhighlight() {
            dropZone.classList.remove('dragover');
        }

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            handleFiles(files);
        }

        fileInput.addEventListener('change', function() {
            handleFiles(this.files);
        });

        function handleFiles(files) {
            if (files.length === 0) return;
            const file = files[0];
            updateFilePreview(file);
        }

        function updateFilePreview(file) {
            fileName.textContent = file.name;

            // Format file size
            let size = file.size;
            let sizeDisplay = '';

            if (size > 1024 * 1024) {
                sizeDisplay = (size / (1024 * 1024)).toFixed(2) + ' MB';
            } else if (size > 1024) {
                sizeDisplay = (size / 1024).toFixed(2) + ' KB';
            } else {
                sizeDisplay = size + ' bytes';
            }

            fileSize.textContent = sizeDisplay;

            // Set icon based on file type
            const fileExtension = file.name.split('.').pop().toLowerCase();
            let iconName = 'document-outline';

            if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
                iconName = 'image-outline';
            } else if (fileExtension === 'pdf') {
                iconName = 'document-text-outline';
            }

            fileTypeIcon.innerHTML = `<ion-icon name="${iconName}"></ion-icon>`;

            // Show preview container
            filePreviewContainer.classList.remove('d-none');
        }

        // Remove selected file
        removeFileBtn.addEventListener('click', function() {
            fileInput.value = '';
            filePreviewContainer.classList.add('d-none');
        });

        // Form submission with validation
        document.getElementById('uploadDocumentForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Check if document type is selected
            var documentType = document.getElementById('selectedDocType').value;
            if (!documentType) {
                Swal.fire({
                    title: 'Error',
                    text: 'Please select a document type',
                    icon: 'error'
                });
                return false;
            }

            // Check if file is selected
            var file = document.getElementById('documentFile').files[0];
            if (!file) {
                Swal.fire({
                    title: 'Error',
                    text: 'Please select a file to upload',
                    icon: 'error'
                });
                return false;
            }

            // Show loading indicator
            Swal.fire({
                title: 'Uploading...',
                text: 'Please wait while we upload your document',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Submit the form
            var formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                    }).then(() => {
                        // Reload the page to show updated document status
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message || 'Upload failed',
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'An unexpected error occurred',
                    icon: 'error'
                });
            });
        });
    });

    // Helper functions for file type icons
    function getFileIconName(filename) {
        const extension = filename.split('.').pop().toLowerCase();

        switch(extension) {
            case 'pdf': return 'document-text-outline';
            case 'doc': case 'docx': return 'document-text-outline';
            case 'xls': case 'xlsx': return 'document-text-outline';
            case 'jpg': case 'jpeg': case 'png': case 'gif': return 'image-outline';
            default: return 'document-outline';
        }
    }

    // Helper function to display file type
    function getFileType(filename) {
        const extension = filename.split('.').pop().toLowerCase();

        switch(extension) {
            case 'pdf': return 'PDF Document';
            case 'doc': case 'docx': return 'Word Document';
            case 'xls': case 'xlsx': return 'Excel Spreadsheet';
            case 'jpg': case 'jpeg': case 'png': case 'gif': return 'Image';
            default: return 'Document';
        }
    }
</script>
@endpush

<?php
// Helper functions for blade template
function getFileIconName($filename) {
    $extension = pathinfo($filename, PATHINFO_EXTENSION);

    switch(strtolower($extension)) {
        case 'pdf': return 'document-text-outline';
        case 'doc': case 'docx': return 'document-text-outline';
        case 'xls': case 'xlsx': return 'document-text-outline';
        case 'jpg': case 'jpeg': case 'png': case 'gif': return 'image-outline';
        default: return 'document-outline';
    }
}

function getFileType($filename) {
    $extension = pathinfo($filename, PATHINFO_EXTENSION);

    switch(strtolower($extension)) {
        case 'pdf': return 'PDF Document';
        case 'doc': case 'docx': return 'Word Document';
        case 'xls': case 'xlsx': return 'Excel Spreadsheet';
        case 'jpg': case 'jpeg': case 'png': case 'gif': return 'Image';
        default: return 'Document';
    }
}
?>
