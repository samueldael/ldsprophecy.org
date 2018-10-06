<?PHP
/*
Simfatic Forms Main Form processor script

This script does all the server side processing. 
(Displaying the form, processing form submissions,
displaying errors, making CAPTCHA image, and so on.) 

All pages (including the form page) are displayed using 
templates in the 'templ' sub folder. 

The overall structure is that of a list of modules. Depending on the 
arguments (POST/GET) passed to the script, the modules process in sequence. 

Please note that just appending  a header and footer to this script won't work.
To embed the form, use the 'Copy & Paste' code in the 'Take the Code' page. 
To extend the functionality, see 'Extension Modules' in the help.

*/

@ini_set("display_errors", 1);//the error handler is added later in FormProc
error_reporting(E_ALL & ~((defined('E_STRICT')?E_STRICT:0)|E_NOTICE));

require_once(dirname(__FILE__)."/includes/Send_Message-lib.php");
$formproc_obj =  new SFM_FormProcessor('Send_Message');
$formproc_obj->initTimeZone('default');
$formproc_obj->setFormID('f41bf4f9-c039-4d25-aed1-e49960d5a4e2');
$formproc_obj->setFormKey('6ea727dd-1373-467d-b015-e0a675787e29');
$formproc_obj->setLocale('en-US','yyyy-MM-dd');
$formproc_obj->setEmailFormatHTML(true);
$formproc_obj->EnableLogging(false);
$formproc_obj->SetErrorEmail('louis@samueldael.org');
$formproc_obj->SetDebugMode(false);
$formproc_obj->setIsInstalled(true);
$formproc_obj->EnableLoadFormValuesFromURL(true);
$formproc_obj->SetPrintPreviewPage(sfm_readfile(dirname(__FILE__)."/templ/Send_Message_print_preview_file.txt"));
$formproc_obj->SetSingleBoxErrorDisplay(true);
$formproc_obj->setFormPage(0,sfm_readfile(dirname(__FILE__)."/templ/Send_Message_form_page_0.txt"));
$formproc_obj->AddElementInfo('Textbox','text','');
$formproc_obj->AddElementInfo('Multiline','multiline','');
$formproc_obj->SetHiddenInputTrapVarName('tc2e50299dee6d63becca');
$formproc_obj->SetFromAddress('louis@samueldael.org');
$formproc_obj->InitSMTP('samueldael.org','','',25);
$page_renderer =  new FM_FormPageDisplayModule();
$formproc_obj->addModule($page_renderer);

$validator =  new FM_FormValidator();
$validator->addValidation("Textbox","required","Please fill in Textbox");
$validator->addValidation("Textbox","alpha_s","The input for Textbox should be a valid alphabetic value");
$validator->addValidation("Multiline","required","Please fill in Multiline");
$validator->addValidation("Multiline","maxlen=300","The length of the input for Multiline should not exceed 300");
$validator->addValidation("Multiline","minlen=24","The length of the input for Multiline should be at least 24.");
$formproc_obj->addModule($validator);

$confirmpage =  new FM_ConfirmPage(sfm_readfile(dirname(__FILE__)."/templ/Send_Message_confirm_page.txt"));
$confirmpage->SetButtonCode(sfm_readfile(dirname(__FILE__)."/templ/Send_Message_confirm_button_code.txt"),sfm_readfile(dirname(__FILE__)."/templ/Send_Message_edit_button_code.txt"),sfm_readfile(dirname(__FILE__)."/templ/Send_Message_print_button_code.txt"));
$confirmpage->SetExtraCode(sfm_readfile(dirname(__FILE__)."/templ/Send_Message_confirm_ie6_png_fix.txt"));
$formproc_obj->addModule($confirmpage);

$data_email_sender =  new FM_FormDataSender(sfm_readfile(dirname(__FILE__)."/templ/Send_Message_email_subj.txt"),sfm_readfile(dirname(__FILE__)."/templ/Send_Message_email_body.txt"),'');
$data_email_sender->AddToAddr('Samuel Louis Dael <louis@samueldael.org>');
$formproc_obj->addModule($data_email_sender);

$tupage =  new FM_ThankYouPage(sfm_readfile(dirname(__FILE__)."/templ/Send_Message_thank_u.txt"));
$formproc_obj->addModule($tupage);

$page_renderer->SetFormValidator($validator);
$formproc_obj->ProcessForm();

?>