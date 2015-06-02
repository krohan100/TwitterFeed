<?php
	
	class TwitterStats
	{
		private $my_connection;
		private $screen_name;
		private $twitter_id;
		private $oauth_token;				
		private $oauth_token_secret;		// For a given session
		
		/*Constructor for the TwitterStats class. Initializes the TwitterOAuth connection, screen name, twitter id, oauth token, 
		 *and oauth token secret values
		 */
		public function __construct( $sn, $tid, $ot, $ots)
		{			
			$this->my_connection 		 = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $ot, $ots);
			$this->screen_name	 		 = $sn;
			$this->twitter_id	 		 = $tid;
			$this->oauth_token	 		 = $ot;
			$this->oauth_token_secret	 = $ots;
		}

		/* Method gets the user's Twitter timeline with the maximum amount of tweets retrieved by count.*/
		public function get_home_timeline($count)
		{
			$home_timeline_tweets = $this->my_connection->get('statuses/home_timeline', array('screen_name' => $this->screen_name, 'count' => $count) );
	 		return $home_timeline_tweets;	
		}
		
		/*Get user info like screen name, friends count, Statuses Count*/
		public function get_user_info()
		{
			$user_info = $this->my_connection->get('users/show', array('user_id' => $this->twitter_id, 'screen_name' => $this->screen_name));	
			return $user_info;
		}

		/*Fetch conversation between the user and a target user*/
		public function get_conversation($target_user)
		{
			
			$tweet_collection = $this->my_connection->get('statuses/user_timeline', array('screen_name' => $this->screen_name));
			//var_dump($tweet_collection);
			$required_tweet_array = array();
			$conversation = array();
				
			foreach($tweet_collection as $status)
			{
				//echo $status->{'text'};
				if($status->{"in_reply_to_screen_name"} === $target_user)		// Fetch only those tweets
				{
					//echo $status->{"in_reply_to_screen_name"};
					array_push($required_tweet_array, $status);					// Please improve!!
				}
			}

			if(!empty($required_tweet_array))
			{
				$highest_id_tweet  = $required_tweet_array[0];
				array_push($conversation, $highest_id_tweet->{'text'});
					
				while($highest_id_tweet->{'in_reply_to_status_id'}!=NULL)
				{
					$parent_tweet  = $this->my_connection->get('statuses/show', array('id'=> $highest_id_tweet->{'in_reply_to_status_id'}));
					$highest_id_tweet = $parent_tweet;
					array_push($conversation, $highest_id_tweet->{'text'});				
				}

				$conversation = array_reverse($conversation);
			}

			return $conversation;
			
		}

	}

?>