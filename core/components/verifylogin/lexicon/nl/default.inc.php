<?php
/**
 * Default Dutch Lexicon Entries for VerifyLogin
 *
 * @package verifylogin
 * @subpackage lexicon
 */

$_lang['verifylogin'] = 'VerifyLogin';

$_lang['setting_verifylogin.email_closure'] = 'E-mail closure';
$_lang['setting_verifylogin.email_date_format'] = 'E-mail date format';
$_lang['setting_verifylogin.email_powered_by'] = 'E-mail show powered by logo\'s';
$_lang['setting_verifylogin.email_additional_content'] = 'E-mail additional content';
$_lang['setting_verifylogin.email_chunk'] = 'E-mail chunk';
$_lang['setting_verifylogin.user_email'] = 'User e-mail';
$_lang['setting_verifylogin.user_name'] = 'User name';

$_lang['verifylogin.mail.subject'] = 'Nieuwe login via [[+browser]] op [[+os]]';
$_lang['verifylogin.mail.content'] = '<p>Beste [[+fullname]],</p>
<p>Je account ([[+email]]) is gebruikt om in te loggen bij de [[+site_name]] manager.</p>
<p><strong>Datum en tijd</strong><br /> [[+date]]</p>
<p><strong>IP adres</strong><br /> [[+ip_address]]</p>
<p><strong>Browser</strong><br /> [[+browser]]</p>
<p>Als de informatie hierboven je bekend voorkomt, kun je deze e-mail negeren.</p>
<p>Als je niet onlangs hebt ingelogd bij de [[+site_name]] manager en je denkt dat iemand heeft geprobeerd toegang te krijgen tot je account, dan dien je je wachtwoord opnieuw in te stellen</p>
<p>
    <table cellpadding="0" cellspacing="0" border="0" id="backgroundTable" bgcolor="#de005a" style="background-color:#de005a;max-width: 200px;text-align:center;">
        <tr>
            <td>
                <a href="[[+manager_url]]" target="_blank" style="padding:15px 14px;color:#ffffff;font-weight:bold;background-color:#de005a;font-size:15px;display:inline-block;text-decoration:none;">Wachtwoord aanpassen</a>
            </td>
        </tr>
    </table>
</p>
<p style="font-family:Arial,sans-serif;line-height:17px;text-align:left;font-size:13px;color:#666666;text-decoration:none;">Of gebruik de onderstaande link:<br /> [[+manager_url]]</p>
<p>[[+additional]]</p>
<p>Met vriendelijke groet,<br />
[[+closure]]</p>';