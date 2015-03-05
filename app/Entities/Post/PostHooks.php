<?php namespace SimpleFavorites\Entities\Post;

use SimpleFavorites\Config\SettingsRepository;
use SimpleFavorites\Entities\Favorite\FavoriteButton;

/**
* Post Actions and Filters
*/
class PostHooks {

	/**
	* Settings Repository
	*/
	private $settings_repo;

	/**
	* The Content
	*/
	private $content;

	/**
	* The Post Object
	*/
	private $post;


	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
		add_filter('the_content', array($this, 'filterContent'));
	}


	/**
	* Filter the Content
	*/
	public function filterContent($content)
	{
		global $post;
		$this->post = $post;
		$this->content = $content;

		$display = $this->settings_repo->displayInPostType($post->post_type);
		if ( !$display ) return $content;

		return $this->addFavoriteButton($display);
	}


	/**
	* Add the Favorite Button
	* @todo add favorite button html
	*/
	private function addFavoriteButton($display_in)
	{
		$output = '';

		$button = new FavoriteButton($this->post->ID);
		
		if ( isset($display_in['before_content']) && $display_in['before_content'] == 'true' ){
			$output .= $button->display();
		}
		
		$output .= $this->content;

		if ( isset($display_in['after_content']) && $display_in['after_content'] == 'true' ){
			$output .= $button->display();
		}
		return $output;
	}

}