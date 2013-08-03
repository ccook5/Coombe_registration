<?php


/*** print a complete error page in html. 
 *
 * @param $msg            The error message as a text string
 * @paran $redirect_to    Specify a url to redirect to automatically.
 * @param $redirect_time  How long to wait before redirecting (in seconds).
 */
function error_page($msg, $redirect_to = "", $redirect_time = 0)
{
    page_header($redirect_to, $redirect_time);
    echo '   <div class="error">'.$msg.'</div>';
    page_footer();
    exit;
}

/*** print a complete warning page in html. 
 *
 * @param $msg            The warning message as a text string
 * @paran $redirect_to    Specify a url to redirect to automatically.
 * @param $redirect_time  How long to wait before redirecting (in seconds).
 */
function warning_page($msg, $redirect_to = "", $redirect_time = 0)
{
    page_header($redirect_to, $redirect_time);
    echo '   <div class="warning">'.$msg.'</div>';
    page_footer();
    exit;
}

/*** print a success_page in html.
 *
 * @param $msg            The success message as a text string
 * @paran $redirect_to    Specify a url to redirect to automatically.
 * @param $redirect_time  How long to wait before redirecting (in seconds).
 */
function success_page($msg, $redirect_to = "", $redirect_time = 0)
{
    page_header($redirect_to, $redirect_time);
    echo '   <div class="success">'.$msg.'</div>';
    page_footer();
    exit;
}


?>

