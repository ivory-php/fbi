<?php
namespace Ivory;

use String;
use Int;

/**
 * feh is a command line program for displaying images. This is a PHP class for working with it
 * in a PHP like manner
 */
class Fbi {

	/**
	 * The options to pass to the command
	 * @var array
	 */
	protected $options = [];

	/**
	 * The File to display
	 */
	protected $file;

	/**
	 * The length of time to display the image in a slideshow
	 * @var integer
	 */
	protected $displayFor = 10;


	/**
	 * One of two frame buffers can be used.  -T 1 (default) is the local machine. 
	 * -T 2 is the local display attached for someone using SSH. 
	 * @param string $frameBuffer 
	 */
	public function __construct()
	{
		$this
		// Set the default Frame Buffer
		->withFrameBuffer('1')
		// Set the default device
		->forDevice()
		// Set No Verbose by default
		->withoutStatusBar();
		
	}

	/**
	 * Which Framebuffer Should be used. 1 is default.
	 * @param  int $frameBuffer 
	 * @return $this
	 */
	public function withFrameBuffer($frameBuffer)
	{
		$this->options['T'] = $frameBuffer;

		return $this;
	}

	/**
	 * Which output device to use. Note that calling this script via
	 * ssh will not be the default device. 
	 * @param  string $device The device to use
	 * @return $this
	 */
	public function forDevice(string $device = '/dev/fb0')
	{
		$this->options['d'] = $device;

		return $this;
	}

	/**
	 * Changes the video mode to use. The video mode must be listed in
	 * /etc/fb.modes in order to work. Default is not to change the settings.
	 * @param  string $mode The Video Mode to use
	 * @return $this
	 */
	public function videoMode(string $mode)
	{
		$this->options['m'] = $mode;

		return $this;
	}

	/**
	 * This shows the status bar at the bottom of the screen with image
	 * about the file being displayed in it.	
	 * @return $this
	 */
	public function showStatusBar()
	{
		/**
		 * In case the without status bar has been set, remove it first.
		 */
		if( array_key_exists('noverbose', $this->options) ) unset($this->options['noverbose']);

		$this->options['v'] = '';

		return $this;
	}

	/**
	 * Don't display the status bar at the bottom of the screen
	 * @return $this
	 */
	public function withoutStatusBar()
	{
		if( array_key_exists('v', $this->options) ) unset($this->options['v']);

		$this->options['noverbose'] = '';

		return $this;
	}

	/**
	 * Case Fbi to dispaly large images without verticle offset (default is 
	 * to center the images). Space will first try to scroll down and go to 
	 * next image only if it is already on the bottom of the page. Useful
	 * if the images you are watching text pages, all you have to do to get 
	 * the next piece of text is to press space. 
	 * @return $this
	 */
	public function enableTextTreading()
	{
		$this->options['P'] = '';

		return $this;
	}

	/**
	 * Remove any Text Treading options
	 * @return $this
	 */
	public function withoutTextThreading()
	{
		if( array_key_exists('P', $this->options) ) unset($this->options['P']);

		return $this;
	}

	/**
	 * Load the next image after x number of seconds without 
	 * any keypress (eg, slideshow)
	 * @return $this
	 */
	public function displayFor(int $seconds)
	{
		$this->options['t'] = $seconds;
		$this->displayFor = $seconds;

		return $this;
	}

	/**
	 * Enables Gamma Correction on the image
	 * @return $this
	 */
	public function withGammaCorrection()
	{
		$this->options['g'] = '';

		return $this;
	}

	/**
	 * Disabled Gamma Correction
	 * @return $this
	 */
	public function withoutGammaCorrection()
	{
		if( array_key_exists('g', $this->options) ) unset($this->options['g']);

		return $this;
	}

	/**
	 * Set the scroll steps in pixels. Default is 50
	 * @param  int    $steps 
	 * @return $this
	 */
	public function scrollSteps(int $steps)
	{
		$this->options['s'] = $steps;

		return $this;
	}

	/**
	 * Causes Fbi to automatically pick up resonable zoom factor
	 * when loading a new image. 
	 * @return $this
	 */
	public function withAutozoom()
	{
		$this->options['a'] = '';

		return $this;
	}

	/**
	 * Removes Autozoom Settings
	 * @return $this
	 */
	public function disableAutozoom()
	{
		if( array_key_exists('a', $this->options) ) unset($this->options['a']);

		return $this;
	}

	/**
	 * Similar to AutoZoom, but scale up only. Not down.
	 * @return $this 
	 */
	public function autoUp()
	{
		$this->options['autoup'] = '';

		return $this;
	}

	/**
	 * Remove AutoUp Settings
	 * @return $this
	 */
	public function noAutoUp()
	{
		if( array_key_exists('autoup', $this->options) ) unset($this->options['autoup']);

		return $this;
	}

	/**
	 * Similar to Autzoom, but scale down only. 
	 * @return $this
	 */
	public function autoDown()
	{
		$this->options['autodown'] = '';

		return $this;
	}

	/**
	 * Disable Auto Down Settings
	 */
	public function NoAutoDown()
	{
		if( array_key_exists('autodown', $this->options) ) unset($this->options['autodown']);

		return $this;
	}

	/**
	 * When showing an entire directory of photos, randomize 
	 * the order. 
	 * @return $this
	 */
	public function inRandomOrder()
	{
		$this->options['u'] = '';

		return $this;
	}

	/**
	 * Remove the Random Flag if it is set
	 * @return $this
	 */
	public function inOrder()
	{
		if( array_key_exists('u', $this->options) ) unset($this->options['u']);

		return $this;
	}

	/**
	 * Display Comment Tags (if present) insteda of the filename. 
	 * @return $this
	 */
	public function withComments()
	{
		$this->options['comments'] = '';

		return $this;
	}

	/**
	 * Remove the comments flag if it has been set. 
	 * @return $this
	 */
	public function withoutComments()
	{
		if( array_key_exists('comments', $this->options) ) unset($this->options['comments']);

		return $this;
	}

	/**
	 * Sets which image to display
	 * @param  string $file The file to display
	 */
	public function image($file)
	{
		$this->file = $file;

		return $this;
	}

	/**
	 * Display The Image
	 * @return 
	 */
	public function display()
	{
		$options = $this->_compileOptions();		
		$command = system('sudo fbi ' .  $this->_compileOptions() . ' ' .$this->file . ' > /dev/null 2>&1');

		/**
		 * If $displayFor is set to 0, then the application doesn't need to be terminated. It's showing
		 * indefinetly. 
		 */
		if( $this->displayFor > 0 ) 
		{
			sleep($this->displayFor);
			$this->terminate();
		}
	}

	/**
	 * Compile the options to a string passable to the command
	 * @return string 
	 */
	public function _compileOptions()
	{
		$compiled = '';

		/**
		 * We'll cycle through the options. If the option has only one 
		 * character, it needs a hash, multiple characters needs two hashes. 
		 */
		foreach( $this->options as $option => $value ) :
			if( strlen($option) > 1 ) $compiled.= "--{$option} {$value} ";
			else $compiled.= "-{$option} {$value}";
		endforeach;

		return $compiled;		
	}

	/**
	 * Terminate the FBI. NOTE: Sudo access is required. 
	 * This keeps memory usage low. Otherwise a new instance
	 * will be created each time it is used. 
	 * 
	 * @return void
	 */
	public function terminate()
	{
		system('sudo killall fbi > /dev/null 2>&1');
	}
}
