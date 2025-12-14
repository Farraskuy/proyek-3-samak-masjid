<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kode Verifikasi - Samak Masjid</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Inline CSS untuk kompatibilitas email client */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', Arial, sans-serif;
            background-color: #f8fafc;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            color: #334155;
        }

        table {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        td {
            padding: 0;
            vertical-align: top;
        }

        a {
            text-decoration: none;
            color: #175C9E;
        }

        img {
            border: 0;
            -ms-Poppinspolation-mode: bicubic;
        }

        /* Responsive Styles */
        @media only screen and (max-width: 600px) {
            .container {
                width: 100% !important;
                padding: 0 15px !important;
            }

            .content-padding {
                padding: 25px 20px !important;
            }

            .header-logo img {
                max-width: 120px !important;
            }

            .title {
                font-size: 24px !important;
                line-height: 32px !important;
            }

            .body-text {
                font-size: 14px !important;
                line-height: 22px !important;
            }

            /* Style untuk kode */
            .code-block h1 {
                font-size: 32px !important;
                letter-spacing: 4px !important;
            }

            .footer-text {
                font-size: 12px !important;
            }

            .logo-text {
                font-size: 18px !important;
            }
        }
    </style>
</head>

<body style="margin:0; padding:0; font-family:'Poppins', Arial, sans-serif; background-color:#f8fafc; display: flex; justify-content: center; align-items: center;">

    <table role="presentation" style="background-color:#f8fafc;">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <table class="container" width="600" role="presentation"
                    style="width:600px; background-color:#ffffff; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.05);">
                    <tr>
                        <td class="content-padding" style="padding: 40px;">

                            <table role="presentation" style="margin-bottom: 30px;">
                                <tr>
                                    <td class="header-logo" style="text-align: left;">
                                        <table role="presentation">
                                            <tr>
                                                <td style="padding-right: 12px; vertical-align: middle;">
                                                    <img src="{{ asset('assets/images/logo.png') }}"
                                                        alt="{{ config('app.name') }} Logo"
                                                        style="width:40px; height:40px; display:block; border:0;">
                                                </td>
                                                <td style="vertical-align: middle;">
                                                    <h4 class="logo-text"
                                                        style="margin:0; font-family:'Poppins', Arial, sans-serif; font-size: 20px; color: #1a202c; font-weight: 600;">
                                                        Samak Masjid
                                                    </h4>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation">
                                <tr>
                                    <td style="padding-bottom: 25px;">
                                        <h1 class="title"
                                            style="margin:0; font-size:28px; line-height:36px; font-weight:700; color:#1a202c;">
                                            Kode Verifikasi
                                        </h1>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 20px;">
                                        <p class="body-text"
                                            style="margin:0; font-size:16px; line-height:26px; color:#334155;">
                                            Halo,
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 25px;">
                                        <p class="body-text"
                                            style="margin:0; font-size:16px; line-height:26px; color:#334155;">
                                            Gunakan kode berikut untuk memverifikasi {{ $destination }} pada Samak
                                            Masjid. Kode ini berlaku selama 10 menit.
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="code-block"
                                        style="padding-top: 10px; padding-bottom: 30px; text-align: center;">
                                        <table role="presentation" style="margin: 0 auto;">
                                            <tr>
                                                <td align="center"
                                                    style="background-color:#f8fafc; border-radius:8px; padding: 20px 25px;">
                                                    <h1
                                                        style="margin:0; font-family:'Poppins', Arial, sans-serif; font-size: 40px; font-weight: 700; color: #175C9E; letter-spacing: 6px; mso-line-height-rule: exactly;">
                                                        {{ $code }}
                                                    </h1>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding-bottom: 30px;">
                                        <p class="body-text"
                                            style="margin:0; font-size:16px; line-height:26px; color:#334155;">
                                            Jika Anda tidak meminta kode ini, abaikan pesan ini.
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p class="body-text"
                                            style="margin:0; font-size:16px; line-height:26px; color:#334155;">
                                            Salam,<br>Tim Samak Masjid
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation"
                                style="margin-top: 40px; border-top: 1px solid #e2e8f0; padding-top: 30px;">
                                <tr>
                                    <td>
                                        <p class="footer-text"
                                            style="margin:5px 0 0; font-size:14px; line-height:22px; color:#64748b;">
                                            © {{ date('Y') }} {{ config('app.name') }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>

</html>
