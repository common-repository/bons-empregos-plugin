<?php
/*
Plugin Name: Bons Empregos Plugin
Plugin URI: http://bonsempregos.com/empregos-no-teu-blog
Description: PT O plugin Bons Empregos adciona um widget ao teu blog que mostra as ultimas ofertas de emprego em Portugal. Pode ser integrado em qualquer sitio no do teu blog. The Bons Empregos Plugin adds a customizable widget which displays the latest Job offers from Portugal. It can be integrated anywhere in the blog. This Jobticker shows up the last five or more posts and is a great solution to help spread job oportunities.
Version: 1.0
Author: Miguel Sousa
Author URI: http://bonsempregos.com
License: GPL3
*/

function bonsempregos()
{
  $options = get_option("widget_bonsempregos");
  if (!is_array($options)){
    $options = array(
      'title' => 'Empregos',
      'news' => '5',
      'chars' => '30'
    );
  }

  // RSS Feed 
  $rss = simplexml_load_file( 
  'http://bonsempregos.com/oferta/portugal/feed/'); 
  ?> 
  
  <ul> 
  
  <?php 
  // max number of news slots, with 0 (zero) all display
  $max_news = $options['news'];
  // maximum length to which a title may be reduced if necessary,
  $max_length = $options['chars'];
  
  // RSS Elements 
  $cnt = 0;
  foreach($rss->channel->item as $i) { 
    if($max_news > 0 AND $cnt >= $max_news){
        break;
    }
    ?> 
    
    <li>
    <?php
    // Title
    $title = $i->title;
    // Length of title
    $length = strlen($title);
    // if the title is longer than the previously defined maximum length,
    // it'll he shortened and "..." added, or it'll output normaly
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

function widget_bonsempregos($args)
{
  extract($args);
  
  $options = get_option("widget_bonsempregos");
  if (!is_array($options)){
    $options = array(
      'title' => 'Bons Empregos',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  echo $before_widget;
  echo $before_title;
  echo $options['title'];
  echo $after_title;
  bonsempregos();
  echo $after_widget;
}

function bonsempregos_control()
{
  $options = get_option("widget_bonsempregos");
  if (!is_array($options)){
    $options = array(
      'title' => 'Bons Empregos',
      'news' => '5',
      'chars' => '30'
    );
  }
  
  if($_POST['bonsempregos-Submit'])
  {
    $options['title'] = htmlspecialchars($_POST['bonsempregos-WidgetTitle']);
    $options['news'] = htmlspecialchars($_POST['bonsempregos-NewsCount']);
    $options['chars'] = htmlspecialchars($_POST['bonsempregos-CharCount']);
    update_option("widget_bonsempregos", $options);
  }
?> 
  <p>
    <label for="bonsempregos-WidgetTitle">Widget Title: </label>
    <input type="text" id="bonsempregos-WidgetTitle" name="bonsempregos-WidgetTitle" value="<?php echo $options['title'];?>" />
    <br /><br />
    <label for="bonsempregos-NewsCount">Max. News: </label>
    <input type="text" id="bonsempregos-NewsCount" name="bonsempregos-NewsCount" value="<?php echo $options['news'];?>" />
    <br /><br />
    <label for="bonsempregos-CharCount">Max. Characters: </label>
    <input type="text" id="bonsempregos-CharCount" name="bonsempregos-CharCount" value="<?php echo $options['chars'];?>" />
    <br /><br />
    <input type="hidden" id="bonsempregos-Submit"  name="bonsempregos-Submit" value="1" />
  </p>
  
<?php
}

function bonsempregos_init()
{
  register_sidebar_widget(__('Bons Empregos'), 'widget_bonsempregos');    
  register_widget_control('Bons Empregos', 'bonsempregos_control', 300, 200);
}
add_action("plugins_loaded", "bonsempregos_init");
?>
