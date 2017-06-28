<?php
/**
 * Default English Lexicon Entries for VerifyLogin
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

$_lang['verifylogin.mail.subject'] = 'New login via [[+browser]] on [[+os]]';
$_lang['verifylogin.mail.content'] = '<p>Dear [[+fullname]],</p>
<p>Your account ([[+email]]) is used to log in at the [[+site_name]] manager.</p>
<p><strong>Date and time</strong><br /> [[+date]]</p>
<p><strong>IP address</strong><br /> [[+ip_address]]</p>
<p><strong>Browser</strong><br /> [[+browser]]</p>
<p><strong>If this was you</strong><br /> Nothing to worry about. There\'s nothing else you need to do.</p>
<p><strong>If this wasn\'t you</strong><br /> Your account may have been compromised and you should reset your password now.</p>
<p>
    <table cellpadding="0" cellspacing="0" border="0" id="backgroundTable" bgcolor="#de005a" style="background-color:#de005a;max-width:200px;text-align:center;">
        <tr>
            <td>
                <a href="[[+manager_url]]" target="_blank" style="padding:15px 35px;color:#ffffff;font-weight:bold;background-color:#de005a;font-size:15px;display:inline-block;text-decoration:none;">Change password</a>
            </td>
        </tr>
    </table>
</p>
<p style="font-family:Arial,sans-serif;line-height:17px;text-align:left;font-size:13px;color:#666666;text-decoration:none;">Or use the following link:<br /> [[+manager_url]]</p>
<p>[[+additional]]</p>
<p>With kind regards,<br />
[[+closure]]</p>';