<?php 

//include the Facebook PHP SDK
include_once 'src/facebook.php';
 
//start the session if necessary
if( session_id() ) {
 
} else {
   session_start();
}

//instantiate the Facebook library with the APP ID and APP SECRET
$facebook = new Facebook(array(
		'appId' => '183326848397861',
		'secret' => '58c189ca8c65ff084ea7a8af725dc487',
   		'cookie' => true
	));
 
//get the news feed of the active page using the page's access token
$page_feed = $facebook->api(
   '/me/feed',
   'GET',
   array(
      'access_token' => $_SESSION['active']['access_token']
   )
);

//var_dump($page_feed);

?>

<!DOCTYPE html>
<html>
<head>
   <title>Be Monkey Page Manager</title>
   <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css" />
   <link href="css/bootstrap-responsive.css" rel="stylesheet">
   <style>
	   body {
	      padding-top: 40px;
	      background-color: #EEEEEE;
	   }
	</style>
 
</head>
<body>
<div class="navbar navbar-fixed-top">
    <div class="navbar-inner">
	    <div class="container">
	      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </a>
	      <a class="brand" href="#">Be Monkey Page Manager</a>
	      <ul class="nav">
	      	<?php foreach($_SESSION['accounts'] as $page): ?>
			  <li>
			  	<a href="switch.php?page_id=<?php echo $page['id']; ?>">
	                 <img width="20" src="http://graph.facebook.com/<?php echo $page['id']; ?>/picture" alt="<?php echo $page['name']; ?>" />
	                 <?php echo $page['name']; ?>
	              </a>
	          </li>
	       <?php endforeach; ?>
			</ul>
	   </div>
  	</div>
</div>
<div id="main" class="container">
   <div class="content">
      <div class="page-header">
         <h1>
            <img width="50" src="http://graph.facebook.com/<?php echo $_SESSION['active']['id']; ?>/picture" alt="<?php echo $_SESSION['active']['name']; ?>" />
            <?php echo $_SESSION['active']['name']; ?>
            <small><a href="http://facebook.com/profile.php?id=<?php echo $_SESSION['active']['id']; ?>" target="_blank">go to page</a></small>
         </h1>
      </div>
      <div class="row">
         <div class="span8">
            
               <?php foreach($page_feed['data'] as $post): ?>

	               <?php if( ($post['type'] == 'status' || $post['type'] == 'link') && !isset($post['story'])): ?>
					
					   <div class="post_photo">
					      <img src="http://graph.facebook.com/<?php echo $post['from']['id']; ?>/picture" alt="<?php echo $post['from']['name']; ?>"/>
					   </div>
					 
					   <div class="post_data">
					      <p><a href="http://facebook.com/profile.php?id=<?php echo $post['from']['id']; ?>" target="_blank"><?php echo $post['from']['name']; ?></a></p>
					      
					      <p><?php echo $post['message']; ?></p>
					      
					      <?php if( $post['type'] == 'link' ): ?>
						      <div>
						         <div class="post_picture">
						            <?php if( isset($post['picture']) ): ?>
						            <a target="_blank" href="<?php echo $post['link']; ?>">
						               <img src="<?php echo $post['picture']; ?>" width="90" />
						            </a>
						            <?php else: ?>
						            &nbsp;
						            <?php endif; ?>
						         </div>
						         
						         <div class="post_data_again">
						            <p><a target="_blank" href="<?php echo $post['link']; ?>"><?php echo $post['name']; ?></a></p>
						            <p><small><?php echo $post['caption']; ?></small></p>
						            <p><?php if(!empty($post['description'])) echo $post['description']; ?></small></p>
						         </div>
						         
						         <div class="clearfix"></div>
						      </div>
					      <?php endif; ?>
					   </div>
					   
					   <div class="clearfix"></div>

	               <?php endif; ?>

               <?php endforeach; ?>
            
         </div>
         <div class="span4">
            <h3>Post a new update</h3>
            <img src="img/post_breakdown.png" alt="Facebook Post Cheat Sheet" width="340" /><br />
			<form method="POST" action="newpost.php" class="form-stacked">
			   <label for="message">Message:</label>
			   <input class="span5" type="text" id="message" name="message" placeholder="Message of post" />
			   <label for="picture">Picture:</label>
			   <input class="span5" type="text" id="picture" name="picture" placeholder="Picture of post" />
			   <label for="link">Link:</label>
			   <input class="span5" type="text" id="link" name="link" placeholder="Link of post" />
			   <label for="name">Name:</label>
			   <input class="span5" type="text" id="name" name="name" placeholder="Name of post" />
			   <label for="caption">Caption:</label>
			   <input class="span5" type="text" id="caption" name="caption" placeholder="Caption of post" />
			   <label for="description">Description:</label>
			   <input class="span5" type="text" id="description" name="description" placeholder="Description of post" />
			 
			   <div class="actions">
			      <input type="submit" class="btn primary" value="Post" />
			      <input type="reset" class="btn" value="Reset" />
			   </div>
			</form>
         </div>
      </div>
   </div>
</div>
<!-- Le javascript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/dropdown.js"></script>
<script>
	$('.secondary-nav').dropdown()
</script>
</body>
</html>