<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Statement Upload</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('assets/style.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

</head>

<body>
    <div class="container">
        <div class="upload-card">
            <div class="header">
                <h1><i class="fas fa-cloud-upload-alt"></i> Bank Statement Upload</h1>
                <p>Securely upload your monthly statemet</p>
            </div>

            <div class="form-container">
                <form id="upload-form">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-store"></i> Select Merchant
                        </label>
                        <div class="select-wrapper">
                            <select id="merchant-select" class="custom-select" required>
                                <option value="">Choose your merchant platform...</option>
                                <option value="amazon">🛒 Amazon</option>
                                <option value="stripe">💳 Stripe</option>
                                <option value="paypal">🏦 PayPal</option>
                                <option value="square">⬜ Square</option>
                                <option value="shopify">🛍️ Shopify</option>
                                <option value="woocommerce">🔧 WooCommerce</option>
                                <option value="bigcommerce">📊 BigCommerce</option>
                                <option value="other">🔗 Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-file-upload"></i> Upload Documents
                        </label>
                        <div class="upload-zone" id="upload-zone">
                            <div class="upload-icon">
                                <i class="fas fa-cloud-upload-alt"></i>
                            </div>
                            <div class="upload-text">Drag & Drop your files here</div>
                            <div class="upload-subtext">or click to browse from your device</div>
                            <div class="file-types">
                                <span class="file-type"><i class="fas fa-file-pdf"></i> PDF</span>
                                <span class="file-type"><i class="fas fa-file-csv"></i> CSV</span>
                            </div>
                            <input type="file" id="file-input" multiple accept=".pdf,.csv" />
                        </div>
                    </div>

                    <div class="selected-files" id="selected-files">
                        <div class="files-header">
                            <i class="fas fa-list"></i> Selected Files
                        </div>
                        <div id="files-list"></div>
                    </div>

                    <button type="submit" class="submit-btn" id="submit-btn" disabled>
                        <i class="fas fa-upload"></i> Upload Files
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="notification" id="notification">
        <div id="notification-text"></div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('assets/script.js') }}"></script>
</body>

</html>
