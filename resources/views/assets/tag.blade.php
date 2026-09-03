<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Asset Tag - {{ $asset->asset_code }}
    </title>

    <style>

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: #ffffff;
            font-family: Arial, Helvetica, sans-serif;
        }

        .print-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /*
        |--------------------------------------------------------------------------
        | PHYSICAL ASSET STICKER
        |--------------------------------------------------------------------------
        */

        .asset-tag {
            width: 90mm;
            min-height: 55mm;

            border: 1px solid #222;
            border-radius: 4px;

            padding: 5mm;

            background: #ffffff;

            display: flex;
            flex-direction: column;

            page-break-inside: avoid;
        }

        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        .tag-header {
            display: flex;
            align-items: center;
            justify-content: space-between;

            border-bottom: 1px solid #222;

            padding-bottom: 3mm;
            margin-bottom: 3mm;
        }

        .crb-logo {
            display: flex;
            align-items: center;
            gap: 2mm;
        }

        .crb-box {
            width: 12mm;
            height: 12mm;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #111;
            color: #fff;

            font-weight: 700;
            font-size: 13px;

            border-radius: 2px;
        }

        .crb-name {
            line-height: 1.1;
        }

        .crb-name strong {
            display: block;
            font-size: 13px;
            letter-spacing: .4px;
        }

        .crb-name span {
            display: block;
            font-size: 8px;
            color: #555;
        }

        .property-label {
            font-size: 8px;
            font-weight: 700;
            text-transform: uppercase;
            text-align: right;
        }

        /*
        |--------------------------------------------------------------------------
        | CONTENT
        |--------------------------------------------------------------------------
        */

        .tag-content {
            display: flex;
            align-items: center;
            gap: 5mm;

            flex: 1;
        }

        .qr-container {
            width: 30mm;
            height: 30mm;

            display: flex;
            align-items: center;
            justify-content: center;

            flex-shrink: 0;
        }

        .qr-container svg {
            width: 30mm !important;
            height: 30mm !important;
        }

        .asset-details {
            flex: 1;
            min-width: 0;
        }

        .asset-code-label {
            font-size: 7px;
            text-transform: uppercase;
            color: #555;

            margin-bottom: 1mm;
        }

        .asset-code {
            font-size: 15px;
            font-weight: 800;

            letter-spacing: .5px;

            margin-bottom: 2mm;
        }

        .asset-name {
            font-size: 10px;
            font-weight: 700;

            line-height: 1.2;

            margin-bottom: 2mm;
        }

        .asset-type {
            font-size: 8px;
            color: #444;
        }

        /*
        |--------------------------------------------------------------------------
        | FOOTER
        |--------------------------------------------------------------------------
        */

        .tag-footer {
            border-top: 1px solid #222;

            margin-top: 3mm;
            padding-top: 2mm;

            display: flex;
            justify-content: space-between;
            align-items: center;

            font-size: 7px;
            color: #444;
        }

        .scan-text {
            font-weight: 700;
            color: #111;
        }

        .asset-id {
            font-family: monospace;
        }

        /*
        |--------------------------------------------------------------------------
        | PRINT CONTROLS
        |--------------------------------------------------------------------------
        */

        .print-controls {
            position: fixed;
            top: 20px;
            right: 20px;

            display: flex;
            gap: 8px;
        }

        .print-button {
            border: 0;
            background: #111;
            color: #fff;

            padding: 10px 16px;

            border-radius: 4px;

            cursor: pointer;

            font-size: 14px;
        }

        .close-button {
            border: 1px solid #aaa;
            background: #fff;
            color: #222;

            padding: 10px 16px;

            border-radius: 4px;

            cursor: pointer;

            font-size: 14px;
        }

        /*
        |--------------------------------------------------------------------------
        | PRINT MEDIA
        |--------------------------------------------------------------------------
        */

        @media print {

            @page {
                size: 90mm 55mm;
                margin: 0;
            }

            html,
            body {
                width: 90mm;
                height: 55mm;
            }

            .print-page {
                width: 90mm;
                height: 55mm;

                min-height: 0;

                padding: 0;

                display: block;
            }

            .asset-tag {
                width: 90mm;
                height: 55mm;

                min-height: 0;

                border: 1px solid #222;
                border-radius: 0;

                padding: 5mm;
            }

            .print-controls {
                display: none !important;
            }
        }

    </style>

</head>

<body>

    <div class="print-controls">

        <button
            class="print-button"
            onclick="window.print()"
        >
            Print Tag
        </button>

        <button
            class="close-button"
            onclick="window.close()"
        >
            Close
        </button>

    </div>


    <div class="print-page">

        <div class="asset-tag">

            {{-- =====================================================
                HEADER
            ====================================================== --}}

            <div class="tag-header">

                <div class="crb-logo">

                    <div class="crb-box">
                        CRB
                    </div>

                    <div class="crb-name">

                        <strong>
                            CRB ASSETS
                        </strong>

                        <span>
                            Asset Management System
                        </span>

                    </div>

                </div>

                <div class="property-label">
                    Property of CRB
                </div>

            </div>


            {{-- =====================================================
                QR + ASSET INFORMATION
            ====================================================== --}}

            <div class="tag-content">

                <div class="qr-container">

                    {!! \F9WebLtd\QrCode\Facades\QrCode::size(180)->margin(0)->generate($assetUrl) !!}

                </div>


                <div class="asset-details">

                    <div class="asset-code-label">
                        Asset Code
                    </div>

                    <div class="asset-code">
                        {{ $asset->asset_code }}
                    </div>

                    <div class="asset-name">
                        {{ $asset->asset_name }}
                    </div>

                    @if ($asset->type)
                        <div class="asset-type">
                            {{ $asset->type->name }}
                        </div>
                    @endif

                </div>

            </div>


            {{-- =====================================================
                FOOTER
            ====================================================== --}}

            <div class="tag-footer">

                <span class="scan-text">
                    Scan QR to view asset record
                </span>

                <span class="asset-id">
                    ID: {{ $asset->id }}
                </span>

            </div>

        </div>

    </div>

</body>

</html>