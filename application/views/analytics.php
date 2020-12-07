<?php
require_once "./application/helpers/api/environment.php";
if (APP_ENV == 'production') {
?>
<!-- Google Analytics -->
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];
a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

<?php
// New Google Analytics code to set User ID.
// $userId is a unique, persistent, and non-personally identifiable string ID.
$userId = $this->session->userdata('session_id');
if (isset($userId)) {
  $gacode = "ga('create', 'UA-48767385-4', { 'userId': '%s' });";
  echo sprintf($gacode, $userId);
} else {
  $gacode = "ga('create', 'UA-48767385-4');";
  echo sprintf($gacode);
}?>

ga('send', 'pageview');

</script>
<!-- End Google Analytics -->

<?php } ?>