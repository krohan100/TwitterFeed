<?php
//start session
session_start();
// Include config file and twitter PHP Library by Abraham Williams (abraham@abrah.am)
include_once("config.php");
include_once("inc/twitteroauth.php");
include_once("twitterstats.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Login with Twitter</title>
    <style type="text/css">
	.wrapper{width:600px; margin-left:auto;margin-right:auto;}
	.welcome_txt{
		margin: 20px;
		background-color: #EBEBEB;
		padding: 10px;
		border: #D6D6D6 solid 1px;
		-moz-border-radius:5px;
		-webkit-border-radius:5px;
		border-radius:5px;
	}
	.tweet_box{
		margin: 20px;
		background-color: #FFF0DD;
		padding: 10px;
		border: #F7CFCF solid 1px;
		-moz-border-radius:5px;
		-webkit-border-radius:5px;
		border-radius:5px;
	}
	.tweet_box textarea{
		width: 500px;
		border: #F7CFCF solid 1px;
		-moz-border-radius:5px;
		-webkit-border-radius:5px;
		border-radius:5px;
	}
	.tweet_list{
		margin: 20px;
		padding:20px;
		background-color: #E2FFF9;
		border: #CBECCE solid 1px;
		-moz-border-radius:5px;
		-webkit-border-radius:5px;
		border-radius:5px;
	}
	.tweet_list ul{
		padding: 0px;
		font-family: verdana;
		font-size: 12px;
		color: #5C5C5C;
	}
	.tweet_list li{
		border-bottom: silver dashed 1px;
		list-style: none;
		padding: 5px;
	}
	.search_list{
		margin: 20px;
		padding:20px;
		background-color: #E2F0A9;
		border: #CBECCE solid 1px;
		-moz-border-radius:5px;
		-webkit-border-radius:5px;
		border-radius:5px;
	}
	.search_list ul{
		padding: 0px;
		font-family: verdana;
		font-size: 12px;
		color: #5C5C5C;
	}
	.search_list li{
		border-bottom: silver dashed 1px;
		list-style: none;
		padding: 5px;
	}
	
	.id_list{
		margin: 20px;
		padding:20px;
		background-color: #E2F0A9;
		border: #CBECCE solid 1px;
		-moz-border-radius:5px;
		-webkit-border-radius:5px;
		border-radius:5px;
	}
	.id_list ul{
		padding: 0px;
		font-family: verdana;
		font-size: 12px;
		color: #5C5C5C;
	}
	.id_list li{
		border-bottom: silver dashed 1px;
		list-style: none;
		padding: 5px;
	}


	</style>
</head>
<!-- Piwik -->
<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(["setCookieDomain", "*.localhost"]);
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u="//localhost/analytics/";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', 2]);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<noscript><p><img src="//localhost/analytics/piwik.php?idsite=2" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Code -->

<body>
<?php
	if(isset($_SESSION['status']) && $_SESSION['status'] == 'verified') 
	{
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		
		//Retrive variables------------------------------------------------------------------------------------------------------
		$screen_name 		= $_SESSION['request_vars']['screen_name'];
		$twitter_id			= $_SESSION['request_vars']['user_id'];
		$oauth_token 		= $_SESSION['request_vars']['oauth_token'];
		$oauth_token_secret = $_SESSION['request_vars']['oauth_token_secret'];
	
		//Show welcome message----------------------------------------------------------------------------------------------------
		echo '<div class="welcome_txt">Welcome <strong>'.$screen_name.'</strong> (Twitter ID : '.$twitter_id.'). <a href="logout.php?logout">Logout</a>!</div>';
		
		//Make twitterstat object--------------------------------------------------------------------------------------------------
		$user_stat  = new TwitterStats($screen_name, $twitter_id, $oauth_token, $oauth_token_secret);
		
		//Fetch user Timeline data-------------------------------------------------------------------------------------------------		
		$home_timeline_data = $user_stat->get_home_timeline(10);

		echo '<div class="tweet_list"><strong> User Home Timeline : </strong>';
		echo '<ul>';
		
		foreach ($home_timeline_data  as $tweet) {
			echo '<li>'.$tweet->text.' <br />-<i>'.$tweet->created_at.'</i></li>';
		}
				
		echo '</ul></div>';

		//Get user info----------------------------------------------------------------------------------------------------

		$user_info = $user_stat->get_user_info();

		echo '<div class="tweet_list"><strong> User Info : </strong>';
		echo '<ul>';
		
		echo '<li> UserName : '.$user_info->{"name"}.'</li>';
		echo '<li> Location : '.$user_info->{"location"}.'</li>';
		echo '<li> Friend Count : '.$user_info->{"friends_count"}.'</li>';
		echo '<li> Statuses Count: '.$user_info->{"statuses_count"}.'</li>';
		echo '<li> Screen Name: '.$user_info->{"screen_name"}.'</li>';				
		echo '</ul></div>';

		echo '</ul></div>';

		//Get conversation with  another user--------------------------------------------------------------------------------------------------------------------

		$conversation = $user_stat->get_conversation("ug201210029");

		echo '<div class="tweet_list"><strong> Friends Conversations : </strong>';
		echo '<ul>';
		
		foreach($conversation as $sentence)
		{
			echo '<li>'.$sentence.'</i></li>';	
		}
		
		echo '</ul></div>';		
		
	}else{
		//Display login button
		echo '<a href="process.php"><img src="images/sign-in-with-twitter.png" width="151" height="24" border="0" /></a>';
	}
?>  
</body>
</html>