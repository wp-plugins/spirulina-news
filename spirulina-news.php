<?php
/*
Plugin Name: Balsamico News
Description: Adds a widget for displaying the latest news from http://www.aceto-balsamico.net/
Version: 1.14
Author: MS
Author URI: http://www.aceto-balsamico.net/
License: GPL3
*/

<?php

function spirulinan()
{
  $config = get_option("widget_spirulinan");
  if (!is_array($config)){
    $config = array(
      'title' => 'Spirulina News',
      'news' => '5',
      'chars' => '30'
    );
  }

  // create rss
  $rss = simplexml_load_file( 
  'http://www.spirulina-handel.de/blog/feed/'); 
  ?> 
  
  <ul> 
  
  <?php 
  // maximum show posts
  $max_news = $config['news'];
  // maximum text length
  $max_length = $config['chars'];
  
  // procedure
  $cnt = 0;
  foreach($rss->channel->item as $i) { 
    if($max_news > 0 AND $cnt >= $max_news){
        break;
    }
    ?> 
    
    <li>
    <?php
    $title = $i->title;
    // get length
    $length = strlen($title);
	//strip
    if($length > $max_length){
      $title = substr($title, 0, $max_length)."...";
    }
    ?>
    <a href="<?=$i->link?>"><?=$title?></a> 
    </li> 
    
    <?php 
    $cnt++;
  } 
  ?> 
  
  </ul>
<?php  
}

function widget_spirulinan($args)
{
  extract($args);
  
  $config = get_option("widget_spirulinan");
  if (!is_array($config)){
    $config = array(
      'title' => 'Spirulina News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  echo $before_widget;
  echo $before_title;
  echo $config['title'];
  echo $after_title;
  spirulinan();
  echo $after_widget;
}

function spirulinan_control()
{
  $config = get_option("widget_spirulinan");
  if (!is_array($config)){
    $config = array(
      'title' => 'Spirulina News',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  if($_POST['spirulinan-Submit'])
  {
    $config['title'] = htmlspecialchars($_POST['spirulinan-WidgetTitle']);
    $config['news'] = htmlspecialchars($_POST['spirulinan-NewsCount']);
    $config['chars'] = htmlspecialchars($_POST['spirulinan-CharCount']);
    update_option("widget_spirulinan", $config);
  }
?> 
  <p>
    <label for="spirulinan-WidgetTitle">Name of Widget: </label>
    <input type="text" id="spirulinan-WidgetTitle" name="spirulinan-WidgetTitle" value="<?php echo $config['title'];?>" />
    <br /><br />
    <label for="spirulinan-NewsCount">Maximum News shown: </label>
    <input type="text" id="spirulinan-NewsCount" name="spirulinan-NewsCount" value="<?php echo $config['news'];?>" />
    <br /><br />
    <label for="spirulinan-CharCount">Maximum Characters shown: </label>
    <input type="text" id="spirulinan-CharCount" name="spirulinan-CharCount" value="<?php echo $config['chars'];?>" />
    <br /><br />
    <input type="hidden" id="spirulinan-Submit"  name="spirulinan-Submit" value="1" />
  </p>
  
<?php
}

function spirulinan_init()
{
  register_sidebar_widget(__('Spirulina News'), 'widget_spirulinan');    
  register_widget_control('Spirulina News', 'spirulinan_control', 300, 200);
}
add_action("plugins_loaded", "spirulinan_init");
?>
