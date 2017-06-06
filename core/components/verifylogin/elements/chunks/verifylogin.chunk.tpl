<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>[[%verifylogin.mail.subject? &topic=`default` &namespace=`verifylogin` &language=`[[+language]]` &user_agent.browser=`[[+user_agent.browser]]` &user_agent.os=`[[+user_agent.os]]`]]</title>
    <style type="text/css">
        /* Based on The MailChimp Reset INLINE: Yes. */
        /* Client-specific Styles */
        #outlook a {padding:0;} /* Force Outlook to provide a "view in browser" menu link. */
        body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;}
            /* Prevent Webkit and Windows Mobile platforms from changing default font sizes.*/
        .ExternalClass {width:100%;} /* Force Hotmail to display emails at full width */
        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;}
            /* Forces Hotmail to display normal line spacing.  More on that: http://www.emailonacid.com/forum/viewthread/43/ */
        #backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
            /* End reset */

            /* Some sensible defaults for images
            Bring inline: Yes. */
        img {outline:none; text-decoration:none; -ms-interpolation-mode: bicubic;}
        a img {border:none;}
        .image_fix {display:block;}

            /* Yahoo paragraph fix
            Bring inline: Yes. */
        p {margin: 1em 0;}

            /* Hotmail header color reset
            Bring inline: Yes. */
        h1, h2, h3, h4, h5, h6 {color: black !important;}

        h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {color: blue !important;}

        h1 a:active, h2 a:active,  h3 a:active, h4 a:active, h5 a:active, h6 a:active {
            color: red !important; /* Preferably not the same color as the normal header link color.  There is limited support for psuedo classes in email clients, this was added just for good measure. */
        }

        h1 a:visited, h2 a:visited,  h3 a:visited, h4 a:visited, h5 a:visited, h6 a:visited {
            color: purple !important; /* Preferably not the same color as the normal header link color. There is limited support for psuedo classes in email clients, this was added just for good measure. */
        }

        /* Outlook 07, 10 Padding issue fix
        Bring inline: No.*/
        table td {border-collapse: collapse;}

            /* Remove spacing around Outlook 07, 10 tables
            Bring inline: Yes */
        table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }

        /* Styling your links has become much simpler with the new Yahoo.  In fact, it falls in line with the main credo of styling in email and make sure to bring your styles inline.  Your link colors will be uniform across clients when brought inline.
        Bring inline: Yes. */
        a {color: orange;}


        /***************************************************
        ****************************************************
        MOBILE TARGETING
        ****************************************************
        ***************************************************/
        @media only screen and (max-device-width: 480px) {
            /* Part one of controlling phone number linking for mobile. */
            a[href^="tel"], a[href^="sms"] {
                text-decoration: none;
                color: blue; /* or whatever your want */
                pointer-events: none;
                cursor: default;
            }

            .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: orange !important;
                pointer-events: auto;
                cursor: default;
            }

        }

        /* More Specific Targeting */

        @media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
            /* You guessed it, ipad (tablets, smaller screens, etc) */
            /* repeating for the ipad */
            a[href^="tel"], a[href^="sms"] {
                text-decoration: none;
                color: blue; /* or whatever your want */
                pointer-events: none;
                cursor: default;
            }

            .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: orange !important;
                pointer-events: auto;
                cursor: default;
            }
        }

        @media only screen and (-webkit-min-device-pixel-ratio: 2) {
            /* Put your iPhone 4g styles in here */
        }

        /* Android targeting */
        @media only screen and (-webkit-device-pixel-ratio:.75){
            /* Put CSS for low density (ldpi) Android layouts in here */
        }
        @media only screen and (-webkit-device-pixel-ratio:1){
            /* Put CSS for medium density (mdpi) Android layouts in here */
        }
        @media only screen and (-webkit-device-pixel-ratio:1.5){
            /* Put CSS for high density (hdpi) Android layouts in here */
        }
        /* end Android targeting */

    </style>
</head>
<body style="background-color: #ffffff;">
    <table cellpadding="0" cellspacing="0" border="0" id="backgroundTable" bgcolor="#ffffff" style="background-color: #ffffff;">
        <tr>
            <td valign="top" style="font-family:Arial,sans-serif;line-height:23px;padding:40px 40px 15px 40px;text-align:left;font-size:15px;color:#303030;">
                <table cellpadding="0" cellspacing="0" border="0" id="backgroundTable" bgcolor="#00FF00" style="background-color:#f0f0f0;">
                    <tr>
                        <td valign="top" align="center" style="text-align:center;background-color:#f0f0f0;padding-top:17px;">
                            <img alt="Top logo" style="text-align:center" src="[[+site_url]][[+assets_url]]img/top-logo.png" border="0" height="65" width="52">
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" style="font-family:Arial,sans-serif;line-height:23px;padding:15px 37px 35px 37px;text-align:left;font-size:15px;color:#303030;border-bottom: 5px solid #c4c4c4;background-color:#f0f0f0;">
                            [[%verifylogin.mail.content?
                            &topic=`default`
                            &namespace=`verifylogin`
                            &language=`[[+language]]`
                            &fullname=`[[+fullname]]`
                            &email=`[[+email]]`
                            &user_agent.browser=`[[+user_agent.browser]]`
                            &date=`[[+date]]`
                            ]]
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0" border="0" id="backgroundTable" width="100%" style="margin-bottom:40px;">
                    <tr>
                        <td valign="top" align="left" style="text-align:left;padding-left:40px;">
                            <a href="https://www.sterc.nl" target="_blank">
                                <img alt="Sterc logo" style="text-align:left" src="[[+site_url]][[+assets_url]]img/sterc-logo.png" border="0" height="68" width="107">
                            </a>
                        </td>
                        <td valign="top" align="right" style="text-align:right;padding-right:40px;">
                            <img alt="MODX logo" style="text-align:right" src="[[+site_url]][[+assets_url]]img/modx-logo.png" border="0" height="31" width="99">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>