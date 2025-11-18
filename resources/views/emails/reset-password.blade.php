<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Atur Ulang Password - {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Inline CSS untuk kompatibilitas email client */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', Arial, sans-serif;
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
            -ms-interpolation-mode: bicubic;
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

            .button {
                padding: 12px 20px !important;
                font-size: 14px !important;
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

<body style="margin:0; padding:0; font-family:'Inter', Arial, sans-serif; background-color:#f8fafc;">

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
                                                        style="margin:0; font-family:'Inter', Arial, sans-serif; font-size: 20px; color: #1a202c; font-weight: 600;">
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
                                            Atur Ulang Password Anda
                                        </h1>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 20px;">
                                        <p class="body-text"
                                            style="margin:0; font-size:16px; line-height:26px; color:#334155;">

                                            Hai Pengguna,
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 20px;">
                                        <p class="body-text"
                                            style="margin:0; font-size:16px; line-height:26px; color:#334155;">
                                            Kami menerima permintaan untuk mengatur ulang password akun
                                            {{ config('app.name') }} Anda.
                                            Silakan klik tombol di
                                            bawah ini untuk membuat password baru.
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left" style="padding-top: 10px; padding-bottom: 25px;">
                                        <a href="{{ $resetUrl }}" class="button"
                                            style="display:inline-block; background-color:#175C9E; color:#ffffff; font-size:16px; font-weight:600; line-height:1; padding:14px 28px; border-radius:8px; text-align:center; text-decoration:none;">
                                            Atur Ulang Password
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 20px;">
                                        <p class="body-text"
                                            style="margin:0; font-size:16px; line-height:26px; color:#334155;">
                                            Link ini akan kedaluwarsa dalam
                                            <b>{{ config('auth.passwords.' . config('auth.defaults.passwords') . '.expire') }}
                                                menit</b>.
                                        </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-bottom: 30px;">
                                        <p class="body-text"
                                            style="margin:0; font-size:16px; line-height:26px; color:#334155;">
                                            Jika Anda tidak meminta reset password, Anda dapat dengan aman mengabaikan
                                            email ini.
                                        </p>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding-top: 10px;">
                                        <p class="footer-text"
                                            style="margin:0 0 10px; font-size:14px; line-height:22px; color:#64748b;">
                                            Jika Anda kesulitan mengeklik tombol, salin dan tempel URL di bawah ini ke
                                            browser Anda:
                                        </p>
                                        <p class="footer-text"
                                            style="margin:0; font-size:14px; line-height:22px; word-break:break-all;">
                                            <a href="{{ $resetUrl }}"
                                                style="color:#175C9E; text-decoration:none;">{{ $resetUrl }}</a>
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
