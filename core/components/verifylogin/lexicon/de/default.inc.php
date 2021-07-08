<?php
/**
 * Default German Lexicon Entries for VerifyLogin.
 */
$_lang['verifylogin'] = 'VerifyLogin';

$_lang['setting_verifylogin.email_closure'] = 'E-Mail-Unterschrift';
$_lang['setting_verifylogin.email_date_format'] = 'E-Mail-Datumsformat';
$_lang['setting_verifylogin.email_powered_by'] = 'Powered-by-Logos in der E-Mail anzeigen';
$_lang['setting_verifylogin.email_additional_content'] = 'Zusätzlicher E-Mail-Inhalt';
$_lang['setting_verifylogin.email_chunk'] = 'E-Mail-Chunk';
$_lang['setting_verifylogin.user_email'] = 'E-Mail-Adresse des Benutzers';
$_lang['setting_verifylogin.user_name'] = 'Benutzername';
$_lang['setting_verifylogin.email_ignore_list'] = 'Liste der zu ignorierenden E-Mail-Adressen';

$_lang['verifylogin.mail.subject'] = 'Neuer MODX-Login [[+site_url]] über [[+browser]] auf [[+os]]';
$_lang['verifylogin.mail.content'] = '<p>Hallo [[+fullname]],</p>
<p>Ihr Account ([[+email]]) wurde verwendet, um sich in den MODX-Manager der Site [[+site_url]] einzuloggen.</p>
<p><strong>Datum und Zeit</strong><br /> [[+date]]</p>
<p><strong>IP-Adresse</strong><br /> [[+ip_address]]</p>
<p><strong>Browser</strong><br /> [[+browser]]</p>
<p><strong>Wenn Sie das waren</strong><br /> Es gibt nichts, über das Sie sich Sorgen machen müssten. Sie müssen nichts weiter tun.</p>
<p><strong>Wenn das nicht Sie waren</strong><br /> Ihr Account könnte kompromittiert worden sein. Sie sollten Ihr Passwort umgehend ändern.</p>
<p>
    <table cellpadding="0" cellspacing="0" border="0" id="backgroundTable" bgcolor="#de005a" style="background-color:#de005a;max-width:200px;text-align:center;">
        <tr>
            <td>
                <a href="[[+manager_url]]" target="_blank" style="padding:15px 35px;color:#ffffff;font-weight:bold;background-color:#de005a;font-size:15px;display:inline-block;text-decoration:none;">Passwort ändern</a>
            </td>
        </tr>
    </table>
</p>
<p style="font-family:Arial,sans-serif;line-height:17px;text-align:left;font-size:13px;color:#666666;text-decoration:none;">Oder verwenden Sie den folgenden Link:<br /> [[+manager_url]]</p>
<p>[[+additional]]</p>
<p>Mit freundlichen Grüßen<br />
[[+closure]]</p>';
