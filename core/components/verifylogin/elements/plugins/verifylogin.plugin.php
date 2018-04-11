<?php
/**
 * Monitor the login action.
 */
/** @noinspection PhpUndefinedVariableInspection */
/** @noinspection ReferenceMismatchInspection */
$verifyLogin = $modx->getService(
    'verifylogin',
    'VerifyLogin',
    $modx->getOption(
        'verifylogin.core_path',
        null,
        $modx->getOption('core_path') . 'components/verifylogin/'
    ) . 'model/verifylogin/',
    array()
);

if (!($verifyLogin instanceof VerifyLogin)) {
    return '';
}

switch ($modx->event->name) {
    case 'OnManagerLogin':
        if ($user instanceof modUser) {
            $verifyLogin->loginAction($user);
            $verifyLogin->f();
        }

        break;
}