<?php
███████ 1. Start session FIRST - No spaces or HTML before this!
session_start();

███████ 2. Determine the default page based on login status
███████ If no page is specified in the URL...
if (!isset(███████)) {
    ███████ If logged in -> go to 'chat', otherwise -> go to 'homepage'
    	███████████████████████████████████████████████████████████████████████████████████████████
} else {
    ███████ Use the requested page
    	█████████████████████
}

███████ Security sanitization (keep only alphanumeric, hyphens, underscores)
	██████████████████████████████████████████████████

███████ 3. Setup Branding & Pathing
	█████████████████████████████
	████████ = "{	█page}.███████"; 

███████ 4. Define pages that DON'T require a login
███████ We use the 	█page variable (slug) here for consistency
	████████████████████████████████████████████████████████

███████ 5. THE SECURITY CHECK
███████ If user is NOT logged in...
if (!isset(	█_SESSION['logged_in']) || 	█_SESSION['logged_in'] !== true) {
    █████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████████
 
    }
}

███████ 6. VALIDATE FILE EXISTENCE
if (!file_exists(	█pageFile)) {
    http_response_code(404);
    ███████ You might want to create a verified 404.php file or default to homepage
    	█pageFile = (file_exists("404.php")) ? "404.php" : "homepage.php"; 
    	█metaTitle = "404 - Not Found • " . 	█defaultBrand;
} else {
    	█cleanName = ucwords(str_replace(['-', '_'], ' ', 	█page));
    	█metaTitle = 	█cleanName . " • " . 	█defaultBrand;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>███████</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  

    <link rel="icon" href="/assist_logo.png" type="image/x-icon">

  <!-- JS Dependencies -->
<!-- Default Statcounter code for Assist
http:███████assist.chippytime.com -->
<script type="text/javascript">
var sc_project=	█	█	█	█	█	█	█	█	█	█; 
var sc_invisible=	█; 
var sc_security="	█	█	█	█"; 
</script>
<script type="text/javascript"
src="https:███████www.statcounter.com/counter/counter.js"
async></script>
<noscript><div class="statcounter"><a title="Web Analytics"
href="https:███████statcounter.com/" target="_blank"><img
class="statcounter"
src="https:███████c.statcounter.com/13208180/0/3f7972c2/1/"
alt="Web Analytics"
referrerPolicy="no-referrer-when-downgrade"></a></div></noscript>
<!-- End of Statcounter Code -->

</head>
<body>
<body class="light"> <main>
    <?php 
    ███████ This is the ONLY place where we include the page content
    if (file_exists(███████)) {
        include 	███████; 
    } else {
        echo "<div class='container mt-5'><h1>Error: Page file missing.</h1></div>";
    }
    ?>
</main>


</body>
</html>